<?php
require_once __DIR__ . '/../models/Contact.php';

class ContactsController
{
    public $contact;

    public function __construct()
    {
        $this->contact = new Contact();
    }

    /**
     * Hiển thị danh sách liên hệ (Admin/Manager)
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff(); // Admin và Manager/Staff đều có thể xem
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $status = $_GET['status'] ?? null;
        $cinemaId = null;
        
        // Lấy danh sách rạp cho admin
        $cinemas = [];
        if (isAdmin()) {
            require_once __DIR__ . '/../models/Cinema.php';
            $cinemaModel = new Cinema();
            $cinemas = $cinemaModel->all();
            
            // Lọc theo rạp nếu admin chọn
            $cinemaId = $_GET['cinema_id'] ?? null;
        } elseif (isManager() || isStaff()) {
            // Manager/Staff chỉ xem liên hệ của rạp mình
            $cinemaId = getCurrentCinemaId();
        }
        
        $result = $this->contact->paginate($page, 10, $status, $cinemaId);
        
        // Đếm số liên hệ theo trạng thái (chỉ admin mới đếm tất cả)
        if (isAdmin() && !$cinemaId) {
            $statusCounts = [
                'pending' => $this->contact->countByStatus('pending'),
                'processing' => $this->contact->countByStatus('processing'),
                'resolved' => $this->contact->countByStatus('resolved'),
                'closed' => $this->contact->countByStatus('closed')
            ];
        } else {
            // Manager hoặc admin đã lọc theo rạp - đếm trong phạm vi lọc
            $statusCounts = [
                'pending' => 0,
                'processing' => 0,
                'resolved' => 0,
                'closed' => 0
            ];
            foreach ($result['data'] as $item) {
                $itemStatus = $item['status'] ?? 'pending';
                if (isset($statusCounts[$itemStatus])) {
                    $statusCounts[$itemStatus]++;
                }
            }
        }
        
        render('admin/contacts/list.php', [
            'data' => $result['data'],
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ],
            'statusCounts' => $statusCounts,
            'selectedStatus' => $status,
            'cinemas' => $cinemas,
            'selectedCinemaId' => $cinemaId,
            'isAdmin' => isAdmin()
        ]);
    }

    /**
     * Xem chi tiết liên hệ (Admin)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        $contact = $this->contact->find($id);
        if (!$contact) {
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        render('admin/contacts/show.php', ['contact' => $contact]);
    }

    /**
     * Hiển thị form sửa liên hệ (Admin)
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        $contact = $this->contact->find($id);
        if (!$contact) {
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $status = trim($_POST['status'] ?? 'pending');

            // Validation
            if (empty($name)) {
                $errors['name'] = "Bạn vui lòng nhập họ tên";
            }

            if (empty($email)) {
                $errors['email'] = "Bạn vui lòng nhập email";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ";
            }

            if (empty($subject)) {
                $errors['subject'] = "Bạn vui lòng nhập chủ đề";
            }

            if (empty($message)) {
                $errors['message'] = "Bạn vui lòng nhập nội dung";
            }

            $validStatuses = ['pending', 'processing', 'resolved', 'closed'];
            if (!in_array($status, $validStatuses)) {
                $errors['status'] = "Trạng thái không hợp lệ";
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message,
                    'status' => $status
                ];
                
                $result = $this->contact->update($id, $data);
                
                if ($result) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['success'] = 'Đã cập nhật liên hệ thành công';
                    header('Location: ' . BASE_URL . '?act=contacts-show&id=' . $id);
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật liên hệ. Vui lòng thử lại.';
                }
            }
        }

        render('admin/contacts/edit.php', ['contact' => $contact, 'errors' => $errors]);
    }

    /**
     * Cập nhật trạng thái liên hệ
     */
    public function updateStatus()
    {
        $id = $_GET['id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$id || !$status) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = 'Thiếu thông tin cần thiết';
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        $validStatuses = ['pending', 'processing', 'resolved', 'closed'];
        if (!in_array($status, $validStatuses)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = 'Trạng thái không hợp lệ';
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        $result = $this->contact->updateStatus($id, $status);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($result) {
            $_SESSION['success'] = 'Đã cập nhật trạng thái thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái';
        }
        
        header('Location: ' . BASE_URL . '?act=contacts-show&id=' . $id);
        exit;
    }

    /**
     * Xóa liên hệ
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }

        $result = $this->contact->delete($id);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($result) {
            $_SESSION['success'] = 'Đã xóa liên hệ thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa liên hệ';
        }
        
        header('Location: ' . BASE_URL . '?act=contacts');
        exit;
    }
}

?>

