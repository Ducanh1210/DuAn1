<?php
class CommentsController
{
    public $comment;
    public $movie;
    public $user;

    public function __construct()
    {
        $this->comment = new Comment();
        $this->movie = new Movie();
        $this->user = new User();
    }

    /**
     * Hiển thị danh sách bình luận (Admin/Manager/Staff)
     * 
     * LUỒNG CHẠY:
     * 1. Kiểm tra quyền (requireAdminOrManager hoặc requireAdminOrStaff)
     * 2. Lấy tham số lọc từ URL (page, search, movie_id, cinema_id)
     * 3. Phân quyền lọc dữ liệu:
     *    - Admin: xem tất cả hoặc lọc theo rạp
     *    - Manager/Staff: chỉ xem bình luận của phim thuộc rạp được gán
     * 4. Gọi Model để lấy dữ liệu
     * 5. Render view với dữ liệu đã lọc
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_GET: page, search, movie_id, cinema_id
     * - Từ Model Comment: paginate(), search(), getByMovie() -> danh sách bình luận
     * - Từ Model Movie: all() -> danh sách phim (cho filter)
     * - Từ Model Cinema: all() -> danh sách rạp (cho filter, chỉ admin)
     */
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
            $cinemaFilter = getCurrentCinemaId();
        }
        
        // Lấy dữ liệu
        if (!empty($searchKeyword)) {
            $data = $this->comment->search($searchKeyword, $cinemaFilter);
            $pagination = null;
        } elseif (!empty($movieFilter)) {
            $data = $this->comment->getByMovie($movieFilter);
            // Nếu có filter theo rạp, lọc lại kết quả
            if ($cinemaFilter) {
                // Kiểm tra phim có showtimes thuộc rạp này không
                require_once __DIR__ . '/../models/Showtime.php';
                require_once __DIR__ . '/../models/Room.php';
                $showtimeModel = new Showtime();
                $roomModel = new Room();
                $showtimes = $showtimeModel->getByMovie($movieFilter);
                $hasAccess = false;
                foreach ($showtimes as $st) {
                    $room = $roomModel->find($st['room_id']);
                    if ($room && $room['cinema_id'] == $cinemaFilter) {
                        $hasAccess = true;
                        break;
                    }
                }
                // Nếu phim không có showtimes thuộc rạp này, xóa hết data
                if (!$hasAccess) {
                    $data = [];
                }
            }
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

    /**
     * Xem chi tiết bình luận (Admin/Manager/Staff)
     */
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
                header('Location: ' . BASE_URL . '?act=comments');
                exit;
            }
            
            // Kiểm tra xem bình luận này có thuộc phim có showtimes trong rạp này không
            // Sử dụng cùng logic với method paginate() để đảm bảo nhất quán
            try {
                // Kiểm tra bằng cách query xem có bình luận này trong danh sách đã lọc theo rạp không
                $sql = "SELECT COUNT(*) as count FROM comments 
                        WHERE comments.id = :comment_id
                        AND EXISTS (
                            SELECT 1 FROM showtimes 
                            INNER JOIN rooms ON showtimes.room_id = rooms.id 
                            WHERE showtimes.movie_id = comments.movie_id 
                            AND rooms.cinema_id = :cinema_id
                        )";
                $stmt = $this->comment->conn->prepare($sql);
                $stmt->execute([
                    ':comment_id' => $id,
                    ':cinema_id' => $cinemaId
                ]);
                $result = $stmt->fetch();
                
                // Nếu không tìm thấy bình luận trong rạp này, không cho phép xem
                if (!$result || $result['count'] == 0) {
                    header('Location: ' . BASE_URL . '?act=comments');
                    exit;
                }
            } catch (Exception $e) {
                // Nếu có lỗi, không cho phép xem để đảm bảo an toàn
                error_log("Error checking comment access: " . $e->getMessage());
                header('Location: ' . BASE_URL . '?act=comments');
                exit;
            }
        }

        render('admin/comments/show.php', ['comment' => $comment]);
    }

    /**
     * Xóa bình luận (Admin/Manager/Staff)
     */
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
            // Kiểm tra quyền: Manager/Staff chỉ xóa bình luận của rạp mình
            if (isManager() || isStaff()) {
                $cinemaId = getCurrentCinemaId();
                if ($cinemaId) {
                    // Kiểm tra phim có thuộc rạp này không
                    require_once __DIR__ . '/../models/Showtime.php';
                    $showtimeModel = new Showtime();
                    $showtimes = $showtimeModel->getByMovie($comment['movie_id']);
                    $hasAccess = false;
                    foreach ($showtimes as $st) {
                        require_once __DIR__ . '/../models/Room.php';
                        $roomModel = new Room();
                        $room = $roomModel->find($st['room_id']);
                        if ($room && $room['cinema_id'] == $cinemaId) {
                            $hasAccess = true;
                            break;
                        }
                    }
                    if (!$hasAccess) {
                        header('Location: ' . BASE_URL . '?act=comments');
                        exit;
                    }
                }
            }
            
            $this->comment->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=comments');
        exit;
    }
}

?>

