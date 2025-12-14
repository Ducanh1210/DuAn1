<?php
// COMMENT MODEL - Tương tác với bảng comments
// Chức năng: CRUD bình luận, lọc theo phim/user/rạp, tính điểm trung bình
class Comment
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    // Lấy tất cả bình luận với thông tin user và movie (LEFT JOIN)
    public function all()
    {
        try {
            // SQL query với LEFT JOIN để lấy thông tin user và movie
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    ORDER BY comments.created_at DESC"; // Sắp xếp mới nhất trước
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(); // Trả về mảng tất cả bình luận
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Lấy bình luận theo ID
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
            return $stmt->fetch(); // Trả về 1 bình luận hoặc null
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Lấy bình luận theo movie_id (có thể lọc theo rạp, includeHidden=false chỉ lấy active)
    public function getByMovie($movieId, $cinemaId = null, $includeHidden = false)
    {
        try {
            $whereClause = "WHERE comments.movie_id = :movie_id";
            $params = [':movie_id' => $movieId];
            
            // Thêm filter theo rạp nếu có - dùng cinema_id trực tiếp
            if ($cinemaId) {
                $whereClause .= " AND comments.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Chỉ lấy bình luận active nếu không cho phép lấy hidden
            if (!$includeHidden) {
                $whereClause .= " AND (comments.status = 'active' OR comments.status IS NULL)";
            }
            
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    cinemas.name AS cinema_name
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    " . $whereClause . "
                    ORDER BY comments.created_at DESC"; // Sắp xếp mới nhất trước
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(); // Trả về mảng bình luận của phim
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    // Lấy bình luận theo user_id (hiển thị trong trang profile)
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

    // Lấy bình luận theo user_id và movie_id (DEPRECATED - dùng getByUserAndMovieAndCinema)
    public function getByUserAndMovie($userId, $movieId)
    {
        try {
            $sql = "SELECT * FROM comments WHERE user_id = :user_id AND movie_id = :movie_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':movie_id' => $movieId]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    // Lấy tất cả bình luận của user cho một phim (theo các rạp khác nhau)
    public function getAllByUserAndMovie($userId, $movieId)
    {
        try {
            $sql = "SELECT comments.*, 
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM comments
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    WHERE comments.user_id = :user_id AND comments.movie_id = :movie_id
                    ORDER BY comments.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':movie_id' => $movieId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    // Lấy bình luận theo user_id, movie_id và cinema_id (mỗi user chỉ đánh giá 1 lần/phim/rạp)
    public function getByUserAndMovieAndCinema($userId, $movieId, $cinemaId)
    {
        try {
            $sql = "SELECT * FROM comments WHERE user_id = :user_id AND movie_id = :movie_id AND cinema_id = :cinema_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId, 
                ':movie_id' => $movieId,
                ':cinema_id' => $cinemaId
            ]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    // Tìm kiếm bình luận (theo nội dung, tên user, tên phim - có thể lọc theo rạp)
    public function search($keyword, $cinemaId = null, $includeHidden = true)
    {
        try {
            $whereClause = "WHERE (comments.content LIKE :keyword 
                    OR users.full_name LIKE :keyword
                    OR movies.title LIKE :keyword)";
            $params = [':keyword' => '%' . $keyword . '%'];
            
            // Thêm filter theo rạp nếu có - dùng cinema_id trực tiếp
            if ($cinemaId) {
                $whereClause .= " AND comments.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Chỉ lấy bình luận active nếu không cho phép lấy hidden
            if (!$includeHidden) {
                $whereClause .= " AND (comments.status = 'active' OR comments.status IS NULL)";
            }
            
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title,
                    cinemas.name AS cinema_name
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    " . $whereClause . "
                    ORDER BY comments.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    // Lấy bình luận với phân trang (có thể lọc theo rạp, includeHidden=true cho admin)
    public function paginate($page = 1, $perPage = 10, $cinemaId = null, $includeHidden = true)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause cho cinema filter
            $whereClause = "";
            $params = [];
            
            if ($cinemaId) {
                // Lọc theo rạp - dùng cinema_id trực tiếp
                $whereClause = "WHERE comments.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Chỉ lấy bình luận active nếu không cho phép lấy hidden
            if (!$includeHidden) {
                if ($whereClause) {
                    $whereClause .= " AND (comments.status = 'active' OR comments.status IS NULL)";
                } else {
                    $whereClause = "WHERE (comments.status = 'active' OR comments.status IS NULL)";
                }
            }
            
            // Đếm tổng số bình luận
            $countSql = "SELECT COUNT(*) as total FROM comments " . $whereClause;
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang với thông tin rạp
            $sql = "SELECT comments.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    movies.title AS movie_title,
                    cinemas.name AS cinema_name
                    FROM comments
                    LEFT JOIN users ON comments.user_id = users.id
                    LEFT JOIN movies ON comments.movie_id = movies.id
                    LEFT JOIN cinemas ON comments.cinema_id = cinemas.id
                    " . $whereClause . "
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
            return [
                'data' => [],
                'page' => 1,
                'perPage' => $perPage,
                'total' => 0,
                'totalPages' => 0
            ];
        }
    }

    // Thêm bình luận mới vào database
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO comments (
                user_id,
                movie_id,
                cinema_id,
                rating,
                content,
                status
            ) VALUES (
                :user_id,
                :movie_id,
                :cinema_id,
                :rating,
                :content,
                :status
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'] ?? null,
                ':movie_id' => $data['movie_id'] ?? null,
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':rating' => $data['rating'] ?? null,
                ':content' => $data['content'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Cập nhật bình luận
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

    // Xóa bình luận (DEPRECATED - dùng toggleStatus, thực chất là ẩn thay vì xóa)
    public function delete($id)
    {
        return $this->toggleStatus($id, 'hidden');
    }

    // Ẩn/Hiện bình luận (status='active' hoặc 'hidden')
    public function toggleStatus($id, $status = 'hidden')
    {
        try {
            $sql = "UPDATE comments SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Đếm số lượng bình luận
     * 
     * @param int|null $cinemaId Lọc theo rạp (null = tất cả)
     * @return int Số lượng bình luận
     */
    // Đếm số lượng bình luận (có thể lọc theo rạp, includeHidden=true cho admin)
    public function count($cinemaId = null, $includeHidden = true)
    {
        try {
            $whereClause = "";
            $params = [];
            
            if ($cinemaId) {
                // Lọc theo rạp - dùng cinema_id trực tiếp
                $whereClause = "WHERE comments.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Chỉ đếm bình luận active nếu không cho phép đếm hidden
            if (!$includeHidden) {
                if ($whereClause) {
                    $whereClause .= " AND (comments.status = 'active' OR comments.status IS NULL)";
                } else {
                    $whereClause = "WHERE (comments.status = 'active' OR comments.status IS NULL)";
                }
            }
            
            $sql = "SELECT COUNT(*) as count FROM comments " . $whereClause;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Tính điểm trung bình và số lượng đánh giá của phim (có thể lọc theo rạp)
    public function getAverageRating($movieId, $cinemaId = null)
    {
        try {
            $whereClause = "WHERE movie_id = :movie_id AND rating IS NOT NULL";
            $params = [':movie_id' => $movieId];
            
            // Thêm filter theo rạp nếu có
            if ($cinemaId) {
                $whereClause .= " AND cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                    FROM comments 
                    " . $whereClause;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
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

