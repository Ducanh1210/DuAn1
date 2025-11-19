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
     * Hiển thị danh sách bình luận (Admin)
     */
    public function list()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $searchKeyword = $_GET['search'] ?? '';
        $movieFilter = $_GET['movie_id'] ?? '';
        
        // Lấy dữ liệu
        if (!empty($searchKeyword)) {
            $data = $this->comment->search($searchKeyword);
            $pagination = null;
        } elseif (!empty($movieFilter)) {
            $data = $this->comment->getByMovie($movieFilter);
            $pagination = null;
        } else {
            $result = $this->comment->paginate($page, 10);
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
        $stats = [
            'total' => $this->comment->count()
        ];

        render('admin/comments/list.php', [
            'data' => $data,
            'pagination' => $pagination,
            'stats' => $stats,
            'movies' => $movies,
            'searchKeyword' => $searchKeyword,
            'movieFilter' => $movieFilter
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

