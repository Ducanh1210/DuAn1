<?php
// CINEMAS CONTROLLER - Xử lý logic quản lý rạp
// Chức năng: CRUD rạp (Admin xem tất cả, Manager/Staff chỉ xem rạp được gán)
class CinemasController
{
    public $cinema; // Model Cinema để tương tác với database

    public function __construct()
    {
        $this->cinema = new Cinema(); // Khởi tạo Model Cinema
    }

    // Danh sách rạp (Admin/Manager/Staff - Manager/Staff chỉ xem rạp được gán)
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        // Nếu là manager hoặc staff, chỉ hiển thị rạp của mình
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $cinema = $this->cinema->find($cinemaId);
                $data = $cinema ? [$cinema] : [];
            } else {
                $data = [];
            }
        } else {
            // Admin xem tất cả
            $data = $this->cinema->all();
        }
        
        render('admin/cinemas/list.php', [
            'data' => $data
        ]);
    }

    // Hiển thị form tạo rạp mới (Admin only) - validate, insert vào DB
    public function create()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin(); // Chỉ admin mới được tạo rạp
        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên rạp";
            }

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'name' => trim($_POST['name']),
                    'address' => trim($_POST['address'] ?? '')
                ];
                
                $result = $this->cinema->insert($data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=cinemas');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm rạp. Vui lòng thử lại.';
                }
            }
        }

        render('admin/cinemas/create.php', ['errors' => $errors]);
    }

    // Hiển thị form sửa rạp (Admin/Manager) - validate, update
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        $cinema = $this->cinema->find($id);
        if (!$cinema) {
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }
        
        // Manager chỉ được sửa rạp của mình
        if (isManager() && !canAccessCinema($id)) {
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên rạp";
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'name' => trim($_POST['name']),
                    'address' => trim($_POST['address'] ?? '')
                ];
                
                $result = $this->cinema->update($id, $data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=cinemas');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật rạp. Vui lòng thử lại.';
                }
            }
        }

        render('admin/cinemas/edit.php', ['cinema' => $cinema, 'errors' => $errors]);
    }

    // Xóa rạp (Admin only)
    public function delete()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin(); // Chỉ admin mới được xóa rạp
        
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy rạp cần xóa.';
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        $cinema = $this->cinema->find($id);
        if (!$cinema) {
            $_SESSION['error'] = 'Không tìm thấy rạp cần xóa.';
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        // Kiểm tra rạp có phòng chiếu không
        if ($this->cinema->hasRooms($id)) {
            $_SESSION['error'] = 'Không thể xóa rạp này vì đang có phòng chiếu. Vui lòng xóa tất cả phòng chiếu trước.';
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        // Kiểm tra rạp có lịch chiếu không
        if ($this->cinema->hasShowtimes($id)) {
            $_SESSION['error'] = 'Không thể xóa rạp này vì đang có lịch chiếu. Vui lòng xóa tất cả lịch chiếu trước.';
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        // Kiểm tra rạp có phim đang chiếu không
        if ($this->cinema->hasActiveMovies($id)) {
            $_SESSION['error'] = 'Không thể xóa rạp này vì đang có phim đang chiếu. Vui lòng đợi đến khi phim kết thúc chiếu.';
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        // Nếu không có vấn đề gì, tiến hành xóa
        $result = $this->cinema->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa rạp thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa rạp. Vui lòng thử lại.';
        }

        header('Location: ' . BASE_URL . '?act=cinemas');
        exit;
    }
}

?>
