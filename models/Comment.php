<?php
class Comment
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả bình luận với thông tin user và movie
     */
    public function all()
    {
        try {
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    ORDER BY comments.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy bình luận theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title,
                    movies.image AS movie_image
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    WHERE comments.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy bình luận theo movie_id
     */
    public function getByMovie($movieId)
    {
        try {
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    WHERE comments.movie_id = :movie_id
                    ORDER BY comments.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':movie_id' => $movieId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy bình luận theo user_id
     */
    public function getByUser($userId)
    {
        try {
            $sql = "SELECT comments.*, 
                    movies.title AS movie_title
                    FROM comments
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    WHERE comments.user_id = :user_id
                    ORDER BY comments.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Tìm kiếm bình luận
     */
    public function search($keyword)
    {
        try {
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    WHERE comments.content LIKE :keyword 
                    OR users.full_name LIKE :keyword
                    OR movies.title LIKE :keyword
                    ORDER BY comments.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => '%' . $keyword . '%']);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy bình luận với phân trang
     */
    public function paginate($page = 1, $perPage = 10)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Đếm tổng số bình luận
            $countSql = "SELECT COUNT(*) as total FROM comments";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    ORDER BY comments.created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            $totalPages = ceil($total / $perPage);
            
            return [
                'data' => $data,
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'totalPages' => $totalPages
            ];
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Thêm bình luận mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO comments (
                user_id,
                movie_id,
                rating,
                content
            ) VALUES (
                :user_id,
                :movie_id,
                :rating,
                :content
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'] ?? null,
                ':movie_id' => $data['movie_id'] ?? null,
                ':rating' => $data['rating'] ?? null,
                ':content' => $data['content'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Cập nhật bình luận
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE comments SET 
                rating = :rating,
                content = :content
                WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':rating' => $data['rating'] ?? null,
                ':content' => $data['content'] ?? null
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Xóa bình luận
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM comments WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Đếm số lượng bình luận
     */
    public function count()
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM comments";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy đánh giá trung bình của phim
     */
    public function getAverageRating($movieId)
    {
        try {
            $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                    FROM comments 
                    WHERE movie_id = :movie_id AND rating IS NOT NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':movie_id' => $movieId]);
            $result = $stmt->fetch();
            return [
                'avg_rating' => round($result['avg_rating'] ?? 0, 1),
                'total_reviews' => $result['total_reviews'] ?? 0
            ];
        } catch (Exception $e) {
            return ['avg_rating' => 0, 'total_reviews' => 0];
        }
    }
}

?>

