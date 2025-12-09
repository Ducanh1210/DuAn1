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
     * LẤY TẤT CẢ PHIM VỚI THÔNG TIN THỂ LOẠI VÀ RẠP
     * 
     * LUỒNG CHẠY:
     * 1. Query SQL với LEFT JOIN để lấy tên thể loại và tên rạp
     * 2. Sắp xếp theo ID và ngày tạo
     * 3. Trả về mảng tất cả phim
     * 
     * DỮ LIỆU TRẢ VỀ:
     * - Tất cả cột từ bảng movies
     * - genre_name từ bảng movie_genres (LEFT JOIN)
     * - cinema_name từ bảng cinemas (LEFT JOIN)
     * 
     * @param int|null $cinemaId Lọc theo rạp (null = tất cả)
     * @return array Danh sách phim
     */
    public function all($cinemaId = null)
    {
        try {
            $whereClause = "";
            $params = [];

            if ($cinemaId) {
                // Lọc theo rạp thông qua bảng trung gian movie_cinemas
                $whereClause = "WHERE movies.id IN (SELECT movie_id FROM movie_cinemas WHERE cinema_id = :cinema_id)";
                $params[':cinema_id'] = $cinemaId;
            }

            // SQL query: SELECT từ movies, JOIN với movie_genres
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    " . $whereClause . "
                    ORDER BY movies.id ASC, movies.created_at ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $movies = $stmt->fetchAll();

            // Lấy danh sách rạp cho mỗi phim
            foreach ($movies as &$movie) {
                $movie['cinemas'] = $this->getCinemasByMovieId($movie['id']);
                $movie['cinema_names'] = array_column($movie['cinemas'], 'name');
                $movie['cinema_name'] = implode(', ', $movie['cinema_names']); // Giữ tương thích với code cũ
            }

            return $movies; // Trả về mảng tất cả phim
        } catch (Exception $e) {
            debug($e); // Hiển thị lỗi nếu có
            return [];
        }
    }

    /**
     * Lấy phim với phân trang
     * 
     * @param int $page Số trang
     * @param int $perPage Số phim mỗi trang
     * @param int|null $cinemaId Lọc theo rạp (null = tất cả)
     * @param string|null $status Lọc theo trạng thái (null = tất cả)
     * @param string|null $searchKeyword Tìm kiếm theo tên phim (null = không tìm)
     * @return array Dữ liệu phân trang
     */
    public function paginate($page = 1, $perPage = 5, $cinemaId = null, $status = null, $searchKeyword = null)
    {
        try {
            $offset = ($page - 1) * $perPage;

            // Xây dựng WHERE clause
            $whereConditions = [];
            $params = [];

            if ($cinemaId) {
                // Lọc theo rạp thông qua bảng trung gian movie_cinemas
                $whereConditions[] = "movies.id IN (SELECT movie_id FROM movie_cinemas WHERE cinema_id = :cinema_id)";
                $params[':cinema_id'] = $cinemaId;
            }

            if ($status) {
                $whereConditions[] = "movies.status = :status";
                $params[':status'] = $status;
            }

            if ($searchKeyword) {
                $whereConditions[] = "movies.title LIKE :search";
                $params[':search'] = '%' . $searchKeyword . '%';
            }

            $whereClause = "";
            if (!empty($whereConditions)) {
                $whereClause = "WHERE " . implode(" AND ", $whereConditions);
            }

            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM movies " . $whereClause;
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];

            // Lấy dữ liệu phân trang
            $sql = "SELECT movies.*, 
                    movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    " . $whereClause . "
                    ORDER BY movies.id ASC, movies.created_at ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();

            // Lấy danh sách rạp cho mỗi phim
            foreach ($data as &$movie) {
                $movie['cinemas'] = $this->getCinemasByMovieId($movie['id']);
                $movie['cinema_names'] = array_column($movie['cinemas'], 'name');
                $movie['cinema_name'] = implode(', ', $movie['cinema_names']); // Giữ tương thích với code cũ
            }

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
            $movie = $stmt->fetch();

            // Lấy danh sách rạp của phim
            if ($movie) {
                $movie['cinemas'] = $this->getCinemasByMovieId($id);
                $movie['cinema_ids'] = array_column($movie['cinemas'], 'id');
            }

            return $movie;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy danh sách rạp của một phim
     */
    public function getCinemasByMovieId($movieId)
    {
        try {
            $sql = "SELECT cinemas.* 
                    FROM cinemas
                    INNER JOIN movie_cinemas ON cinemas.id = movie_cinemas.cinema_id
                    WHERE movie_cinemas.movie_id = :movie_id
                    ORDER BY cinemas.name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':movie_id' => $movieId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy danh sách cơ bản để hiển thị trong dropdown
     *
     * @param bool $onlyActive Chỉ lấy phim đang hoạt động
     * @return array
     */
    public function getBasicList($onlyActive = false)
    {
        try {
            $sql = "SELECT id, title, image, status FROM movies";
            if ($onlyActive) {
                $sql .= " WHERE status = 'active'";
            }
            $sql .= " ORDER BY title ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lưu danh sách rạp cho một phim
     * @param int $movieId ID của phim
     * @param array $cinemaIds Danh sách ID rạp cần lưu
     * @param bool $replace Nếu true thì xóa rạp cũ và thay thế, nếu false thì chỉ thêm rạp mới
     */
    public function saveMovieCinemas($movieId, $cinemaIds, $replace = true)
    {
        try {
            if ($replace) {
                // Xóa tất cả rạp cũ của phim (dùng khi tạo mới hoặc thay thế toàn bộ)
                $deleteSql = "DELETE FROM movie_cinemas WHERE movie_id = :movie_id";
                $deleteStmt = $this->conn->prepare($deleteSql);
                $deleteStmt->execute([':movie_id' => $movieId]);
            }

            // Thêm các rạp mới (chỉ thêm nếu chưa tồn tại)
            if (!empty($cinemaIds)) {
                $insertSql = "INSERT IGNORE INTO movie_cinemas (movie_id, cinema_id) VALUES (:movie_id, :cinema_id)";
                $insertStmt = $this->conn->prepare($insertSql);

                foreach ($cinemaIds as $cinemaId) {
                    $cinemaId = (int)$cinemaId;
                    if ($cinemaId > 0) {
                        $insertStmt->execute([
                            ':movie_id' => $movieId,
                            ':cinema_id' => $cinemaId
                        ]);
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
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
                cinema_id,
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
                :cinema_id,
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
                ':cinema_id' => null, // Không dùng cinema_id nữa, dùng bảng trung gian
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
            $movieId = $this->conn->lastInsertId();

            // Lưu danh sách rạp vào bảng trung gian
            if ($movieId && !empty($data['cinema_ids'])) {
                $this->saveMovieCinemas($movieId, $data['cinema_ids']);
            }

            return $movieId;
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
                cinema_id = :cinema_id,
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
                ':cinema_id' => null, // Không dùng cinema_id nữa, dùng bảng trung gian
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

            // Cập nhật danh sách rạp vào bảng trung gian
            // Kiểm tra xem có flag replace không (mặc định là false để chỉ thêm rạp mới)
            $replace = $data['replace_cinemas'] ?? false;
            if (!empty($data['cinema_ids'])) {
                $this->saveMovieCinemas($id, $data['cinema_ids'], $replace);
            } elseif ($replace) {
                // Chỉ xóa tất cả nếu replace = true
                $this->saveMovieCinemas($id, [], true);
            }

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
            // Xóa các rạp liên quan (sẽ tự động xóa do CASCADE)
            // Hoặc xóa thủ công để chắc chắn
            $deleteCinemasSql = "DELETE FROM movie_cinemas WHERE movie_id = :movie_id";
            $deleteCinemasStmt = $this->conn->prepare($deleteCinemasSql);
            $deleteCinemasStmt->execute([':movie_id' => $id]);

            // Xóa phim
            $sql = "DELETE FROM movies WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }
}
