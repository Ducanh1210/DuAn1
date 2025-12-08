<?php
/**
 * COMMENT MODEL - TƯƠNG TÁC VỚI BẢNG COMMENTS
 * 
 * CHỨC NĂNG:
 * - CRUD bình luận: all(), find(), insert(), update(), delete()
 * - Lọc bình luận: getByMovie(), getByUser(), getByUserAndMovie()
 * - Tính điểm trung bình: getAverageRating()
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi method của Model
 * 2. Model thực hiện SQL query với JOIN để lấy thông tin user và movie
 * 3. Trả về dữ liệu dạng array cho Controller
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: comments
 * - JOIN với: users (thông tin người đánh giá), movies (thông tin phim)
 */
class Comment
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        // Kết nối database khi khởi tạo Model
        $this->conn = connectDB();
    }

    /**
     * LẤY TẤT CẢ BÌNH LUẬN VỚI THÔNG TIN USER VÀ MOVIE
     * 
     * Mục đích: Lấy danh sách tất cả bình luận
     * Cách hoạt động: Query SQL với LEFT JOIN để lấy tên user và tên phim
     * 
     * DỮ LIỆU TRẢ VỀ:
     * - Tất cả cột từ bảng comments
     * - user_name, user_email từ bảng users
     * - movie_title từ bảng movies
     */
    public function all()
    {
        try {
            // SQL query với LEFT JOIN để lấy thông tin user và movie
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    ORDER BY comments.created_at DESC"; // Sắp xếp mới nhất trước
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(); // Trả về mảng tất cả bình luận
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * LẤY BÌNH LUẬN THEO ID
     * 
     * Mục đích: Lấy thông tin chi tiết của 1 bình luận
     * 
     * @param int $id ID của bình luận
     * @return array|null Thông tin bình luận hoặc null nếu không tìm thấy
     */
    public function find($id)
    {
        try {
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title,
                    movies.image AS movie_image,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    WHERE comments.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(); // Trả về 1 bình luận hoặc null
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * LẤY BÌNH LUẬN THEO MOVIE_ID
     * 
     * Mục đích: Lấy tất cả bình luận của 1 phim (hiển thị trong trang chi tiết phim)
     * 
     * @param int $movieId ID của phim
     * @return array Danh sách bình luận của phim đó
     */
    public function getByMovie($movieId)
    {
        try {
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    WHERE comments.movie_id = :movie_id
                    ORDER BY comments.created_at DESC"; // Sắp xếp mới nhất trước
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':movie_id' => $movieId]);
            return $stmt->fetchAll(); // Trả về mảng bình luận của phim
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * LẤY BÌNH LUẬN THEO USER_ID
     * 
     * Mục đích: Lấy tất cả bình luận của 1 user (hiển thị trong trang profile)
     * 
     * @param int $userId ID của user
     * @return array Danh sách bình luận của user đó
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
            return $stmt->fetchAll(); // Trả về mảng bình luận của user
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * LẤY BÌNH LUẬN THEO USER_ID, MOVIE_ID VÀ CINEMA_ID
     * 
     * Mục đích: Kiểm tra user đã đánh giá phim này ở rạp này chưa (mỗi user chỉ đánh giá 1 lần/phim/rạp)
     * 
     * @param int $userId ID của user
     * @param int $movieId ID của phim
     * @param int|null $cinemaId ID của rạp (bắt buộc)
     * @return array|null Bình luận của user cho phim ở rạp đó hoặc null nếu chưa đánh giá
     */
    public function getByUserAndMovie($userId, $movieId, $cinemaId = null)
    {
        try {
            if ($cinemaId) {
                $sql = "SELECT * FROM comments WHERE user_id = :user_id AND movie_id = :movie_id AND cinema_id = :cinema_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userId, 
                    ':movie_id' => $movieId,
                    ':cinema_id' => $cinemaId
                ]);
            } else {
                // Nếu không có cinema_id, tìm theo user và movie (backward compatibility)
                $sql = "SELECT * FROM comments WHERE user_id = :user_id AND movie_id = :movie_id LIMIT 1";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':user_id' => $userId, ':movie_id' => $movieId]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
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
                    movies.title AS movie_title,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
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
    public function paginate($page = 1, $perPage = 10, $cinemaId = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause để lọc theo cinema_id (từ comments.cinema_id)
            $whereClause = '';
            $params = [];
            
            if ($cinemaId) {
                $whereClause = "WHERE comments.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Đếm tổng số bình luận
            $countSql = "SELECT COUNT(*) as total FROM comments {$whereClause}";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang với thông tin rạp
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    {$whereClause}
                    ORDER BY comments.created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
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
                cinema_id,
                rating,
                content
            ) VALUES (
                :user_id,
                :movie_id,
                :cinema_id,
                :rating,
                :content
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'] ?? null,
                ':movie_id' => $data['movie_id'] ?? null,
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':rating' => $data['rating'] ?? null,
                ':content' => $data['content'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
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

