<?php
// COMMENTS CONTROLLER - Xử lý logic quản lý bình luận
// Chức năng: CRUD bình luận, phân trang, lọc theo phim/rạp (Admin xem tất cả, Manager/Staff chỉ rạp được gán)
class CommentsController
{
    public $comment; // Model Comment để tương tác với database
    public $movie; // Model Movie để lấy danh sách phim
    public $user; // Model User để lấy thông tin user

    public function __construct()
    {
        $this->comment = new Comment(); // Khởi tạo Model Comment
        $this->movie = new Movie(); // Khởi tạo Model Movie
        $this->user = new User(); // Khởi tạo Model User
    }

    // Danh sách bình luận (Admin/Manager/Staff) - có phân trang, lọc theo phim/rạp, tìm kiếm
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager(); // Cho phép admin, manager và staff truy cập
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $searchKeyword = $_GET['search'] ?? '';
        $movieFilter = $_GET['movie_id'] ?? '';
        $cinemaFilter = null; // Lọc theo rạp
        
        // Phân quyền lọc theo rạp
        if (isAdmin()) {
            // Admin có thể lọc theo rạp hoặc xem tất cả
            $cinemaFilter = !empty($_GET['cinema_id']) ? (int)$_GET['cinema_id'] : null;
        } elseif (isManager() || isStaff()) {
            // Manager/Staff chỉ xem bình luận của rạp được gán
            // BỎ QUA cinema_id từ URL để tránh bypass
            $cinemaFilter = getCurrentCinemaId();
            // Nếu Manager/Staff không có rạp được gán, không cho phép xem
            if (!$cinemaFilter) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['error'] = 'Bạn chưa được gán cho rạp nào. Vui lòng liên hệ quản trị viên.';
                header('Location: ' . BASE_URL . '?act=dashboard');
                exit;
            }
        }
        
        // Lấy dữ liệu
        if (!empty($searchKeyword)) {
            $data = $this->comment->search($searchKeyword, $cinemaFilter);
            $pagination = null;
        } elseif (!empty($movieFilter)) {
            // Truyền cinemaFilter vào getByMovie để lọc trực tiếp trong SQL
            $data = $this->comment->getByMovie($movieFilter, $cinemaFilter);
            $pagination = null;
        } else {
            $result = $this->comment->paginate($page, 10, $cinemaFilter);
            $data = $result['data'];
            $pagination = [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ];
        }
        
        // Lấy danh sách phim để filter
        $movies = $this->movie->all();
        
        // Lấy danh sách rạp để filter (chỉ admin)
        $cinemas = [];
        if (isAdmin()) {
            require_once __DIR__ . '/../models/Cinema.php';
            $cinemaModel = new Cinema();
            $cinemas = $cinemaModel->all();
        }
        
        // Thống kê
        $stats = [
            'total' => $this->comment->count($cinemaFilter)
        ];

        render('admin/comments/list.php', [
            'data' => $data,
            'pagination' => $pagination,
            'stats' => $stats,
            'movies' => $movies,
            'cinemas' => $cinemas,
            'searchKeyword' => $searchKeyword,
            'movieFilter' => $movieFilter,
            'cinemaFilter' => $cinemaFilter,
            'isAdmin' => isAdmin() // Truyền isAdmin vào view
        ]);
    }

    // Xem chi tiết bình luận (Admin/Manager/Staff)
    public function show()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager(); // Cho phép admin, manager và staff truy cập
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=comments');
            exit;
        }

        $comment = $this->comment->find($id);
        if (!$comment) {
            header('Location: ' . BASE_URL . '?act=comments');
            exit;
        }
        
        // Kiểm tra quyền: Manager/Staff chỉ xem bình luận của rạp mình
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if (!$cinemaId) {
                // Manager/Staff không có rạp được gán, không cho phép xem
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['error'] = 'Bạn chưa được gán cho rạp nào. Vui lòng liên hệ quản trị viên.';
                header('Location: ' . BASE_URL . '?act=dashboard');
                exit;
            }
            
            // Kiểm tra xem bình luận này có thuộc rạp này không - dùng cinema_id trực tiếp
            if ($comment['cinema_id'] != $cinemaId) {
                header('Location: ' . BASE_URL . '?act=comments');
                exit;
            }
        }

        render('admin/comments/show.php', ['comment' => $comment]);
    }

    // Ẩn/Hiện bình luận (Admin/Manager/Staff) - thay thế xóa bằng ẩn để có thể khôi phục
    public function delete()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager(); // Cho phép admin, manager và staff truy cập
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=comments');
            exit;
        }

        $comment = $this->comment->find($id);
        if ($comment) {
            // Kiểm tra quyền: Manager/Staff chỉ ẩn bình luận của rạp mình
            if (isManager() || isStaff()) {
                $cinemaId = getCurrentCinemaId();
                if (!$cinemaId) {
                    // Manager/Staff không có rạp được gán, không cho phép
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['error'] = 'Bạn chưa được gán cho rạp nào. Vui lòng liên hệ quản trị viên.';
                    header('Location: ' . BASE_URL . '?act=dashboard');
                    exit;
                }
                // Kiểm tra bình luận có thuộc rạp này không - dùng cinema_id trực tiếp
                if ($comment['cinema_id'] != $cinemaId) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['error'] = 'Bạn không có quyền ẩn bình luận này.';
                    header('Location: ' . BASE_URL . '?act=comments');
                    exit;
                }
            }
            
            // Ẩn bình luận thay vì xóa
            $currentStatus = $comment['status'] ?? 'active';
            $newStatus = ($currentStatus === 'hidden') ? 'active' : 'hidden';
            $this->comment->toggleStatus($id, $newStatus);
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($newStatus === 'hidden') {
                $_SESSION['success'] = 'Đã ẩn bình luận thành công.';
            } else {
                $_SESSION['success'] = 'Đã hiện lại bình luận thành công.';
            }
        }

        header('Location: ' . BASE_URL . '?act=comments');
        exit;
    }
}

?>

