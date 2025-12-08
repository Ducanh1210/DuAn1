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
     * Hiển thị danh sách liên hệ (Admin/Manager/Staff)
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff(); // Cho phép admin, manager và staff truy cập
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $status = $_GET['status'] ?? null;
        $cinemaFilter = null;
        
        // Phân quyền lọc theo rạp
        if (isAdmin()) {
            // Admin có thể lọc theo rạp hoặc xem tất cả
            $cinemaFilter = !empty($_GET['cinema_id']) ? (int)$_GET['cinema_id'] : null;
        } elseif (isManager() || isStaff()) {
            // Manager và Staff chỉ xem phản hồi của rạp được gán
            $cinemaFilter = getCurrentCinemaId();
        }
        
        $result = $this->contact->paginate($page, 10, $status, $cinemaFilter);
        
        // Đếm số liên hệ theo trạng thái (có lọc theo rạp nếu cần)
        $statusCounts = [
            'pending' => $this->contact->countByStatus('pending', $cinemaFilter),
            'processing' => $this->contact->countByStatus('processing', $cinemaFilter),
            'resolved' => $this->contact->countByStatus('resolved', $cinemaFilter),
            'closed' => $this->contact->countByStatus('closed', $cinemaFilter)
        ];
        
        // Lấy danh sách rạp để hiển thị trong filter (chỉ admin)
        $cinemas = [];
        $currentCinemaName = null;
        if (isAdmin()) {
            require_once __DIR__ . '/../models/Cinema.php';
            $cinemaModel = new Cinema();
            $cinemas = $cinemaModel->all();
        } elseif (isStaff() || isManager()) {
            // Lấy tên rạp hiện tại cho staff/manager
            require_once __DIR__ . '/../models/Cinema.php';
            $cinemaModel = new Cinema();
            $currentCinemaId = getCurrentCinemaId();
            if ($currentCinemaId) {
                $currentCinema = $cinemaModel->find($currentCinemaId);
                $currentCinemaName = $currentCinema['name'] ?? null;
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
            'cinemaFilter' => $cinemaFilter,
            'cinemas' => $cinemas,
            'isAdmin' => isAdmin(),
            'isManager' => isManager(),
            'isStaff' => isStaff(),
            'currentCinemaName' => $currentCinemaName
        ]);
    }

    /**
     * Xem chi tiết liên hệ (Admin/Manager/Staff)
     */
    public function show()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
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
        
        // Kiểm tra quyền: Manager và Staff chỉ xem phản hồi của rạp mình
        if (isManager() || isStaff()) {
            $currentCinemaId = getCurrentCinemaId();
            if ($contact['cinema_id'] != $currentCinemaId) {
                header('Location: ' . BASE_URL . '?act=contacts');
                exit;
            }
        }

        render('admin/contacts/show.php', [
            'contact' => $contact,
            'isAdmin' => isAdmin(),
            'isManager' => isManager(),
            'isStaff' => isStaff()
        ]);
    }

    /**
     * Hiển thị form sửa liên hệ (Admin/Manager/Staff)
     */
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
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
        
        // Kiểm tra quyền: Manager và Staff chỉ sửa phản hồi của rạp mình
        if (isManager() || isStaff()) {
            $currentCinemaId = getCurrentCinemaId();
            if ($contact['cinema_id'] != $currentCinemaId) {
                header('Location: ' . BASE_URL . '?act=contacts');
                exit;
            }
        }

        $errors = [];
        
        // Lấy danh sách rạp (chỉ admin mới có thể thay đổi rạp)
        $cinemas = [];
        if (isAdmin()) {
            require_once __DIR__ . '/../models/Cinema.php';
            $cinemaModel = new Cinema();
            $cinemas = $cinemaModel->all();
        }

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = trim($_POST['status'] ?? 'pending');
            
            // Staff chỉ có thể cập nhật trạng thái
            if (isStaff()) {
                $validStatuses = ['pending', 'processing', 'resolved', 'closed'];
                if (!in_array($status, $validStatuses)) {
                    $errors['status'] = "Trạng thái không hợp lệ";
                }
                
                // Nếu không có lỗi, chỉ cập nhật trạng thái
                if (empty($errors)) {
                    $result = $this->contact->updateStatus($id, $status);
                    
                    if ($result) {
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['success'] = 'Đã cập nhật trạng thái thành công';
                        header('Location: ' . BASE_URL . '?act=contacts-show&id=' . $id);
                        exit;
                    } else {
                        $errors['general'] = 'Có lỗi xảy ra khi cập nhật trạng thái. Vui lòng thử lại.';
                    }
                }
            } else {
                // Admin và Manager có thể sửa tất cả thông tin (nhưng Manager không thể thay đổi rạp)
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $subject = trim($_POST['subject'] ?? '');
                $message = trim($_POST['message'] ?? '');
                // Manager không thể thay đổi rạp, giữ nguyên rạp hiện tại
                if (isManager()) {
                    $cinema_id = $contact['cinema_id'] ?? null;
                } else {
                    $cinema_id = !empty($_POST['cinema_id']) ? (int)$_POST['cinema_id'] : null;
                }

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
                        'cinema_id' => $cinema_id,
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
        }

        render('admin/contacts/edit.php', [
            'contact' => $contact, 
            'errors' => $errors,
            'cinemas' => $cinemas,
            'isAdmin' => isAdmin(),
            'isManager' => isManager(),
            'isStaff' => isStaff()
        ]);
    }

    /**
     * Cập nhật trạng thái liên hệ
     */
    public function updateStatus()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
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
        
        // Kiểm tra quyền: Manager và Staff chỉ cập nhật phản hồi của rạp mình
        $contact = $this->contact->find($id);
        if (!$contact) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = 'Không tìm thấy liên hệ';
            header('Location: ' . BASE_URL . '?act=contacts');
            exit;
        }
        
        if (isManager() || isStaff()) {
            $currentCinemaId = getCurrentCinemaId();
            if ($contact['cinema_id'] != $currentCinemaId) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['error'] = 'Bạn không có quyền cập nhật phản hồi này';
                header('Location: ' . BASE_URL . '?act=contacts');
                exit;
            }
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
     * Xóa liên hệ (Chỉ Admin)
     */
    public function delete()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin(); // Chỉ admin mới được xóa
        
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

