<?php
class Showtime
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả lịch chiếu với thông tin phim và phòng
     * Chỉ lấy lịch chiếu của phim đang chiếu (status = 'active' và còn trong thời gian chiếu)
     */
    public function all()
    {
        try {
            $sql = "SELECT showtimes.*, 
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.image AS movie_image,
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)
                    ORDER BY showtimes.show_date ASC, showtimes.start_time ASC, showtimes.id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Tính trạng thái của showtime
     * @param array $showtime Dữ liệu showtime (có show_date, start_time, end_time, movie_status, movie_end_date)
     * @return string 'upcoming' (sắp chiếu), 'showing' (đang chiếu), 'ended' (dừng)
     */
    public function getStatus($showtime)
    {
        // Kiểm tra trạng thái phim trước
        $movieStatus = $showtime['movie_status'] ?? 'inactive';
        $movieEndDate = $showtime['movie_end_date'] ?? null;
        $movieReleaseDate = $showtime['movie_release_date'] ?? null;
        
        // Nếu phim không active hoặc đã hết thời gian chiếu thì dừng
        if ($movieStatus !== 'active') {
            return 'ended';
        }
        
        // Kiểm tra end_date của phim
        if ($movieEndDate) {
            $today = date('Y-m-d');
            if ($movieEndDate < $today) {
                return 'ended';
            }
        }
        
        // Kiểm tra release_date của phim
        if ($movieReleaseDate) {
            $today = date('Y-m-d');
            if ($movieReleaseDate > $today) {
                return 'upcoming';
            }
        }
        
        if (empty($showtime['show_date'])) {
            return 'ended';
        }
        
        $today = date('Y-m-d');
        $now = date('H:i:s');
        $showDate = $showtime['show_date'];
        $startTime = $showtime['start_time'] ?? '00:00:00';
        $endTime = $showtime['end_time'] ?? '23:59:59';
        
        // So sánh ngày
        if ($showDate < $today) {
            // Ngày chiếu đã qua
            return 'ended';
        } elseif ($showDate > $today) {
            // Ngày chiếu chưa tới
            return 'upcoming';
        } else {
            // Cùng ngày, so sánh giờ
            if ($now < $startTime) {
                // Chưa đến giờ bắt đầu
                return 'upcoming';
            } elseif ($now >= $startTime && $now <= $endTime) {
                // Đang trong khoảng thời gian chiếu
                return 'showing';
            } else {
                // Đã qua giờ kết thúc
                return 'ended';
            }
        }
    }

    /**
     * Lấy lịch chiếu với phân trang
     */
    public function paginate($page = 1, $perPage = 5, $date = null, $status = null, $cinemaId = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereClause = "";
            $params = [];
            
            if ($date) {
                $whereClause = "WHERE showtimes.show_date = :date";
                $params[':date'] = $date;
            }
            
            // Filter theo rạp
            if ($cinemaId) {
                if ($whereClause) {
                    $whereClause .= " AND cinemas.id = :cinema_id";
                } else {
                    $whereClause = "WHERE cinemas.id = :cinema_id";
                }
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Filter theo trạng thái
            if ($status) {
                $today = date('Y-m-d');
                $now = date('H:i:s');
                
                if ($whereClause) {
                    $whereClause .= " AND";
                } else {
                    $whereClause = "WHERE";
                }
                
                switch ($status) {
                    case 'upcoming':
                        // Sắp chiếu: show_date > today hoặc (show_date = today và start_time > now)
                        $whereClause .= " (showtimes.show_date > :status_today OR (showtimes.show_date = :status_today2 AND showtimes.start_time > :status_now))";
                        $params[':status_today'] = $today;
                        $params[':status_today2'] = $today;
                        $params[':status_now'] = $now;
                        break;
                    case 'showing':
                        // Đang chiếu: show_date = today và start_time <= now <= end_time
                        $whereClause .= " showtimes.show_date = :status_today AND showtimes.start_time <= :status_now AND showtimes.end_time >= :status_now2";
                        $params[':status_today'] = $today;
                        $params[':status_now'] = $now;
                        $params[':status_now2'] = $now;
                        break;
                    case 'ended':
                        // Dừng: show_date < today hoặc (show_date = today và end_time < now)
                        $whereClause .= " (showtimes.show_date < :status_today OR (showtimes.show_date = :status_today2 AND showtimes.end_time < :status_now))";
                        $params[':status_today'] = $today;
                        $params[':status_today2'] = $today;
                        $params[':status_now'] = $now;
                        break;
                }
            }
            
            // Thêm filter theo trạng thái phim (chỉ lấy phim đang chiếu)
            $movieFilter = "movies.status = 'active' 
                           AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                           AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)";
            
            if ($whereClause) {
                $whereClause .= " AND " . $movieFilter;
            } else {
                $whereClause = "WHERE " . $movieFilter;
            }
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total 
                        FROM showtimes
                        LEFT JOIN movies ON showtimes.movie_id = movies.id
                        LEFT JOIN rooms ON showtimes.room_id = rooms.id
                        LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                        $whereClause";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang (sắp xếp: ngày tăng dần, giờ tăng dần, ID tăng dần)
            $sql = "SELECT showtimes.*, 
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.image AS movie_image,
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    $whereClause
                    ORDER BY showtimes.show_date ASC, showtimes.start_time ASC, showtimes.id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            
            // Bind params
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            // Thêm trạng thái vào mỗi item
            foreach ($data as &$item) {
                $item['status'] = $this->getStatus($item);
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
     * Lấy lịch chiếu với phân trang theo cinema_id
     */
    public function paginateByCinema($cinemaId, $page = 1, $perPage = 5, $date = null, $status = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereClause = "WHERE cinemas.id = :cinema_id";
            $params = [':cinema_id' => $cinemaId];
            
            if ($date) {
                $whereClause .= " AND showtimes.show_date = :date";
                $params[':date'] = $date;
            }
            
            // Filter theo trạng thái
            if ($status) {
                $today = date('Y-m-d');
                $now = date('H:i:s');
                
                switch ($status) {
                    case 'upcoming':
                        $whereClause .= " AND (showtimes.show_date > :status_today OR (showtimes.show_date = :status_today2 AND showtimes.start_time > :status_now))";
                        $params[':status_today'] = $today;
                        $params[':status_today2'] = $today;
                        $params[':status_now'] = $now;
                        break;
                    case 'showing':
                        $whereClause .= " AND showtimes.show_date = :status_today AND showtimes.start_time <= :status_now AND showtimes.end_time >= :status_now2";
                        $params[':status_today'] = $today;
                        $params[':status_now'] = $now;
                        $params[':status_now2'] = $now;
                        break;
                    case 'ended':
                        $whereClause .= " AND (showtimes.show_date < :status_today OR (showtimes.show_date = :status_today2 AND showtimes.end_time < :status_now))";
                        $params[':status_today'] = $today;
                        $params[':status_today2'] = $today;
                        $params[':status_now'] = $now;
                        break;
                }
            }
            
            // Thêm filter theo trạng thái phim (chỉ lấy phim đang chiếu)
            $movieFilter = "movies.status = 'active' 
                           AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                           AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)";
            $whereClause .= " AND " . $movieFilter;
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total 
                        FROM showtimes
                        LEFT JOIN movies ON showtimes.movie_id = movies.id
                        LEFT JOIN rooms ON showtimes.room_id = rooms.id
                        LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                        $whereClause";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT showtimes.*, 
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.image AS movie_image,
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    $whereClause
                    ORDER BY showtimes.show_date ASC, showtimes.start_time ASC, showtimes.id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            
            // Bind params
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            // Thêm trạng thái vào mỗi item
            foreach ($data as &$item) {
                $item['status'] = $this->getStatus($item);
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
     * Lấy lịch chiếu theo ID
     */
    public function find($id)
    {
        try {
            // Lấy format từ showtimes trước, đảm bảo không bị override bởi movies.format
            $sql = "SELECT showtimes.id,
                    showtimes.movie_id,
                    showtimes.room_id,
                    showtimes.show_date,
                    showtimes.start_time,
                    showtimes.end_time,
                    showtimes.format,
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.image AS movie_image,
                    movies.age_rating AS movie_age_rating,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE showtimes.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy lịch chiếu theo phim
     * Chỉ lấy lịch chiếu của phim đang chiếu
     */
    public function getByMovie($movie_id)
    {
        try {
            $sql = "SELECT showtimes.*, 
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE showtimes.movie_id = :movie_id
                    AND movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)
                    AND showtimes.show_date >= CURDATE()
                    ORDER BY showtimes.show_date ASC, showtimes.start_time ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':movie_id' => $movie_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy lịch chiếu theo phim và ngày
     * Chỉ lấy lịch chiếu của phim đang chiếu
     */
    public function getByMovieAndDate($movie_id, $date, $cinema_id = '')
    {
        try {
            $sql = "SELECT showtimes.*, 
                    showtimes.format AS format,
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE showtimes.movie_id = :movie_id
                    AND showtimes.show_date = :date
                    AND movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)";
            
            $params = [
                ':movie_id' => $movie_id,
                ':date' => $date
            ];
            
            // Lọc theo rạp nếu có
            if (!empty($cinema_id)) {
                $sql .= " AND cinemas.id = :cinema_id";
                $params[':cinema_id'] = $cinema_id;
            }
            
            $sql .= " ORDER BY showtimes.start_time ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy lịch chiếu theo ngày
     * Chỉ lấy lịch chiếu của phim đang chiếu
     */
    public function getByDate($date, $cinemaId = '', $movieId = '')
    {
        try {
            $sql = "SELECT showtimes.*, 
                    showtimes.format AS format,
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.image AS movie_image,
                    movies.age_rating AS movie_age_rating,
                    movies.original_language AS movie_original_language,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    movies.format AS movie_format,
                    movies.status AS movie_status,
                    movie_genres.name AS genre_name,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    rooms.cinema_id AS cinema_id,
                    cinemas.name AS cinema_name
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE showtimes.show_date = :date
                    AND movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)";
            
            $params = [':date' => $date];
            
            // Lọc theo rạp nếu có
            if (!empty($cinemaId)) {
                $sql .= " AND rooms.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            // Lọc theo phim nếu có
            if (!empty($movieId)) {
                $sql .= " AND showtimes.movie_id = :movie_id";
                $params[':movie_id'] = $movieId;
            }
            
            $sql .= " ORDER BY movies.title ASC, showtimes.start_time ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy lịch chiếu theo phòng
     * Chỉ lấy lịch chiếu của phim đang chiếu
     */
    public function getByRoom($room_id, $date = null)
    {
        try {
            $sql = "SELECT showtimes.*, 
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    WHERE showtimes.room_id = :room_id
                    AND movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)";
            
            $params = [':room_id' => $room_id];
            
            if ($date) {
                $sql .= " AND showtimes.show_date = :date";
                $params[':date'] = $date;
            }
            
            $sql .= " ORDER BY showtimes.show_date ASC, showtimes.start_time ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy lịch chiếu sắp tới (từ hôm nay)
     * Chỉ lấy lịch chiếu của phim đang chiếu
     */
    public function getUpcoming($limit = 50)
    {
        try {
            $sql = "SELECT showtimes.*, 
                    movies.title AS movie_title,
                    movies.duration AS movie_duration,
                    movies.image AS movie_image,
                    movies.status AS movie_status,
                    movies.release_date AS movie_release_date,
                    movies.end_date AS movie_end_date,
                    rooms.name AS room_name,
                    cinemas.name AS cinema_name
                    FROM showtimes
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON showtimes.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE showtimes.show_date >= CURDATE()
                    AND movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)
                    ORDER BY showtimes.show_date ASC, showtimes.start_time ASC
                    LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Thêm lịch chiếu mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO showtimes (
                movie_id,
                room_id,
                show_date,
                start_time,
                end_time,
                format
            ) VALUES (
                :movie_id,
                :room_id,
                :show_date,
                :start_time,
                :end_time,
                :format
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':movie_id' => $data['movie_id'] ?? null,
                ':room_id' => $data['room_id'] ?? null,
                ':show_date' => $data['show_date'] ?? null,
                ':start_time' => $data['start_time'] ?? null,
                ':end_time' => $data['end_time'] ?? null,
                ':format' => $data['format'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Cập nhật lịch chiếu
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE showtimes SET
                movie_id = :movie_id,
                room_id = :room_id,
                show_date = :show_date,
                start_time = :start_time,
                end_time = :end_time,
                format = :format
                WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':movie_id' => $data['movie_id'] ?? null,
                ':room_id' => $data['room_id'] ?? null,
                ':show_date' => $data['show_date'] ?? null,
                ':start_time' => $data['start_time'] ?? null,
                ':end_time' => $data['end_time'] ?? null,
                ':format' => $data['format'] ?? null
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Xóa lịch chiếu
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM showtimes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Kiểm tra xung đột lịch chiếu (trùng giờ, cùng phòng)
     */
    public function checkConflict($room_id, $show_date, $start_time, $end_time, $exclude_id = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM showtimes
                    WHERE room_id = :room_id
                    AND show_date = :show_date
                    AND (
                        (start_time <= :start_time AND end_time > :start_time)
                        OR (start_time < :end_time AND end_time >= :end_time)
                        OR (start_time >= :start_time AND end_time <= :end_time)
                    )";
            
            if ($exclude_id) {
                $sql .= " AND id != :exclude_id";
            }
            
            $stmt = $this->conn->prepare($sql);
            $params = [
                ':room_id' => $room_id,
                ':show_date' => $show_date,
                ':start_time' => $start_time,
                ':end_time' => $end_time
            ];
            
            if ($exclude_id) {
                $params[':exclude_id'] = $exclude_id;
            }
            
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Validate dữ liệu lịch chiếu
     */
    public function validate($data, $isUpdate = false)
    {
        $errors = [];

        // Validate movie_id
        if (empty($data['movie_id'])) {
            $errors['movie_id'] = 'Phim không được để trống';
        }

        // Validate room_id
        if (empty($data['room_id'])) {
            $errors['room_id'] = 'Phòng chiếu không được để trống';
        }

        // Validate show_date
        if (empty($data['show_date'])) {
            $errors['show_date'] = 'Ngày chiếu không được để trống';
        } elseif (strtotime($data['show_date']) < strtotime(date('Y-m-d'))) {
            $errors['show_date'] = 'Ngày chiếu không được là quá khứ';
        }

        // Validate start_time
        if (empty($data['start_time'])) {
            $errors['start_time'] = 'Giờ bắt đầu không được để trống';
        }

        // Validate end_time
        if (empty($data['end_time'])) {
            $errors['end_time'] = 'Giờ kết thúc không được để trống';
        }

        // Validate start_time < end_time
        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            $start = strtotime($data['start_time']);
            $end = strtotime($data['end_time']);
            if ($end <= $start) {
                $errors['end_time'] = 'Giờ kết thúc phải sau giờ bắt đầu';
            }
        }

        // Giá vé được quản lý tại bảng ticket_prices, không cần validate ở đây

        // Check conflict
        if (!empty($data['room_id']) && !empty($data['show_date']) && 
            !empty($data['start_time']) && !empty($data['end_time'])) {
            $exclude_id = $isUpdate && isset($data['id']) ? $data['id'] : null;
            if ($this->checkConflict($data['room_id'], $data['show_date'], 
                $data['start_time'], $data['end_time'], $exclude_id)) {
                $errors['conflict'] = 'Lịch chiếu bị trùng với suất chiếu khác trong cùng phòng';
            }
        }

        return $errors;
    }

    /**
     * Lấy danh sách ngày có lịch chiếu
     */
    public function getAvailableDates($limit = 7)
    {
        try {
            $sql = "SELECT DISTINCT show_date 
                    FROM showtimes
                    WHERE show_date >= CURDATE()
                    ORDER BY show_date ASC
                    LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $dates;
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }
}

?>

