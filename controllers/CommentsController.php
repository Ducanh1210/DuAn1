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
     * Hiển thị danh sách bình luận (Admin/Manager)
     */
    public function list()
    {
        // Kiểm tra quyền admin hoặc manager/staff
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $searchKeyword = $_GET['search'] ?? '';
        $movieFilter = $_GET['movie_id'] ?? '';
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
            // Manager/Staff chỉ xem bình luận của rạp mình
            $cinemaId = getCurrentCinemaId();
        }
        
        // Lấy dữ liệu
        if (!empty($searchKeyword)) {
            $data = $this->comment->search($searchKeyword);
            // Nếu là manager/staff, lọc thêm theo cinema_id
            if ($cinemaId) {
                $filteredData = [];
                foreach ($data as $item) {
                    if (isset($item['cinema_id']) && $item['cinema_id'] == $cinemaId) {
                        $filteredData[] = $item;
                    }
                }
                $data = $filteredData;
            }
            $pagination = null;
        } elseif (!empty($movieFilter)) {
            $data = $this->comment->getByMovie($movieFilter);
            // Nếu là manager/staff, lọc thêm theo cinema_id
            if ($cinemaId) {
                $filteredData = [];
                foreach ($data as $item) {
                    if (isset($item['cinema_id']) && $item['cinema_id'] == $cinemaId) {
                        $filteredData[] = $item;
                    }
                }
                $data = $filteredData;
            }
            $pagination = null;
        } else {
            $result = $this->comment->paginate($page, 10, $cinemaId);
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
        
        // Thống kê
        if (isAdmin() && !$cinemaId) {
            $stats = [
                'total' => $this->comment->count()
            ];
        } else {
            $stats = [
                'total' => count($data)
            ];
        }

        render('admin/comments/list.php', [
            'data' => $data,
            'pagination' => $pagination,
            'stats' => $stats,
            'movies' => $movies,
            'searchKeyword' => $searchKeyword,
            'movieFilter' => $movieFilter,
            'cinemas' => $cinemas,
            'selectedCinemaId' => $cinemaId,
            'isAdmin' => isAdmin()
        ]);
    }

    /**
     * Xem chi tiết bình luận (Admin)
     */
    public function show()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
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

        render('admin/comments/show.php', ['comment' => $comment]);
    }

    /**
     * Xóa bình luận (Admin)
     */
    public function delete()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=comments');
            exit;
        }

        $comment = $this->comment->find($id);
        if ($comment) {
            $this->comment->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=comments');
        exit;
    }
}

?>

