<?php
/**
 * MOVIE MODEL - TƯƠNG TÁC VỚI BẢNG MOVIES
 * 
 * CHỨC NĂNG:
 * - CRUD phim: all(), find(), insert(), update(), delete()
 * - Phân trang: paginate()
 * - Lọc phim: đang chiếu, sắp chiếu, theo thể loại
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi method của Model (ví dụ: $movie->all())
 * 2. Model thực hiện SQL query
 * 3. Trả về dữ liệu dạng array cho Controller
 * 4. Controller truyền dữ liệu vào View để hiển thị
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: movies
 * - JOIN với: movie_genres (để lấy tên thể loại)
 */
class Movie
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        // Kết nối database khi khởi tạo Model
        $this->conn = connectDB();
    }
    
    /**
     * LẤY TẤT CẢ PHIM VỚI THÔNG TIN THỂ LOẠI
     * 
     * LUỒNG CHẠY:
     * 1. Query SQL với LEFT JOIN để lấy tên thể loại
     * 2. Sắp xếp theo ID và ngày tạo
     * 3. Trả về mảng tất cả phim
     * 
     * DỮ LIỆU TRẢ VỀ:
     * - Tất cả cột từ bảng movies
     * - genre_name từ bảng movie_genres (LEFT JOIN)
     */
    public function all()
    {
        try {
            // SQL query: SELECT từ movies, JOIN với movie_genres để lấy tên thể loại
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    ORDER BY movies.id ASC, movies.created_at ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(); // Trả về mảng tất cả phim
        } catch (Exception $e) {
            debug($e); // Hiển thị lỗi nếu có
        }
    }

    /**
     * Lấy phim với phân trang
     */
    public function paginate($page = 1, $perPage = 5)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM movies";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang (mới nhất lên trên - ID lớn nhất lên đầu)
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    ORDER BY movies.id ASC, movies.created_at ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (Exception $e) {
            debug($e);
            return [
                'data' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0
            ];
        }
    }

    /**
     * Lấy phim theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    WHERE movies.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy phim đang chiếu (active)
     */
    public function getActiveMovies()
    {
        try {
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    WHERE movies.status = 'active' 
                    AND movies.release_date <= CURDATE() 
                    AND (movies.end_date IS NULL OR movies.end_date >= CURDATE())
                    ORDER BY movies.release_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy phim theo thể loại
     */
    public function getByGenre($genre_id)
    {
        try {
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    WHERE movies.genre_id = :genre_id AND movies.status = 'active'
                    AND (movies.end_date IS NULL OR movies.end_date >= CURDATE())
                    ORDER BY movies.release_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':genre_id' => $genre_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Tìm kiếm phim theo tên
     */
    public function search($keyword)
    {
        try {
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    WHERE movies.title LIKE :keyword AND movies.status = 'active'
                    AND (movies.end_date IS NULL OR movies.end_date >= CURDATE())
                    ORDER BY movies.release_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => '%' . $keyword . '%']);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lọc phim theo rạp (thông qua showtimes)
     */
    public function getByCinema($cinema_id)
    {
        try {
            $sql = "SELECT DISTINCT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    INNER JOIN showtimes ON movies.id = showtimes.movie_id
                    INNER JOIN rooms ON showtimes.room_id = rooms.id
                    WHERE rooms.cinema_id = :cinema_id 
                    AND movies.status = 'active'
                    AND (movies.end_date IS NULL OR movies.end_date >= CURDATE())
                    ORDER BY movies.release_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':cinema_id' => $cinema_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Thêm phim mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO movies (
                genre_id,
                title, 
                description, 
                image, 
                trailer, 
                duration, 
                release_date,
                end_date,
                format,
                original_language,
                subtitle_or_dub,
                age_rating,
                producer,
                status
            ) VALUES (
                :genre_id,
                :title, 
                :description, 
                :image, 
                :trailer, 
                :duration, 
                :release_date,
                :end_date,
                :format,
                :original_language,
                :subtitle_or_dub,
                :age_rating,
                :producer,
                :status
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':genre_id' => $data['genre_id'] ?? null,
                ':title' => $data['title'],
                ':description' => $data['description'] ?? null,
                ':image' => $data['image'] ?? null,
                ':trailer' => $data['trailer'] ?? null,
                ':duration' => $data['duration'] ?? null,
                ':release_date' => $data['release_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':format' => $data['format'] ?? null,
                ':original_language' => $data['original_language'] ?? null,
                ':subtitle_or_dub' => $data['subtitle_or_dub'] ?? null,
                ':age_rating' => $data['age_rating'] ?? null,
                ':producer' => $data['producer'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Cập nhật phim
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE movies SET 
                genre_id = :genre_id,
                title = :title, 
                description = :description, 
                image = :image, 
                trailer = :trailer, 
                duration = :duration, 
                release_date = :release_date,
                end_date = :end_date,
                format = :format,
                original_language = :original_language,
                subtitle_or_dub = :subtitle_or_dub,
                age_rating = :age_rating,
                producer = :producer,
                status = :status
                WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':genre_id' => $data['genre_id'] ?? null,
                ':title' => $data['title'],
                ':description' => $data['description'] ?? null,
                ':image' => $data['image'] ?? null,
                ':trailer' => $data['trailer'] ?? null,
                ':duration' => $data['duration'] ?? null,
                ':release_date' => $data['release_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':format' => $data['format'] ?? null,
                ':original_language' => $data['original_language'] ?? null,
                ':subtitle_or_dub' => $data['subtitle_or_dub'] ?? null,
                ':age_rating' => $data['age_rating'] ?? null,
                ':producer' => $data['producer'] ?? null,
                ':status' => $data['status'] ?? 'active'
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Xóa phim
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM movies WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

}

?>

