<?php
class Booking
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả đặt vé với thông tin liên quan
     */
    public function all()
    {
        try {
            $sql = "SELECT bookings.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    users.phone AS user_phone,
                    movies.title AS movie_title,
                    movies.image AS movie_image,
                    showtimes.show_date,
                    showtimes.start_time,
                    showtimes.end_time,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM bookings
                    LEFT JOIN users ON bookings.user_id = users.id
                    LEFT JOIN showtimes ON bookings.showtime_id = showtimes.id
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON bookings.room_id = rooms.id
                    LEFT JOIN cinemas ON bookings.cinema_id = cinemas.id
                    ORDER BY bookings.booking_date DESC, bookings.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy đặt vé với phân trang
     */
    public function paginate($page = 1, $perPage = 10, $status = null, $date = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereClause = "";
            $params = [];
            
            if ($status) {
                $whereClause = "WHERE bookings.status = :status";
                $params[':status'] = $status;
            }
            
            if ($date) {
                if ($whereClause) {
                    $whereClause .= " AND DATE(bookings.booking_date) = :date";
                } else {
                    $whereClause = "WHERE DATE(bookings.booking_date) = :date";
                }
                $params[':date'] = $date;
            }
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM bookings $whereClause";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT bookings.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    users.phone AS user_phone,
                    movies.title AS movie_title,
                    movies.image AS movie_image,
                    showtimes.show_date,
                    showtimes.start_time,
                    showtimes.end_time,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM bookings
                    LEFT JOIN users ON bookings.user_id = users.id
                    LEFT JOIN showtimes ON bookings.showtime_id = showtimes.id
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON bookings.room_id = rooms.id
                    LEFT JOIN cinemas ON bookings.cinema_id = cinemas.id
                    $whereClause
                    ORDER BY bookings.booking_date DESC, bookings.id DESC
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
     * Lấy đặt vé theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT bookings.*, 
                    users.full_name AS user_name,
                    users.email AS user_email,
                    users.phone AS user_phone,
                    movies.title AS movie_title,
                    movies.image AS movie_image,
                    movies.duration AS movie_duration,
                    showtimes.show_date,
                    showtimes.start_time,
                    showtimes.end_time,
                    showtimes.format AS showtime_format,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name,
                    cinemas.id AS cinema_id
                    FROM bookings
                    LEFT JOIN users ON bookings.user_id = users.id
                    LEFT JOIN showtimes ON bookings.showtime_id = showtimes.id
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON bookings.room_id = rooms.id
                    LEFT JOIN cinemas ON bookings.cinema_id = cinemas.id
                    WHERE bookings.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Lấy đặt vé theo user_id
     */
    public function getByUser($userId, $page = 1, $perPage = 10)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM bookings WHERE user_id = :user_id";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute([':user_id' => $userId]);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT bookings.*, 
                    movies.title AS movie_title,
                    movies.image AS movie_image,
                    showtimes.show_date,
                    showtimes.start_time,
                    showtimes.end_time,
                    showtimes.format AS showtime_format,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM bookings
                    LEFT JOIN showtimes ON bookings.showtime_id = showtimes.id
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON bookings.room_id = rooms.id
                    LEFT JOIN cinemas ON bookings.cinema_id = cinemas.id
                    WHERE bookings.user_id = :user_id
                    ORDER BY bookings.booking_date DESC, bookings.id DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
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
     * Tạo mã đặt vé
     */
    private function generateBookingCode()
    {
        return 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Thêm đặt vé mới
     */
    public function insert($data)
    {
        try {
            // Sử dụng booking_code từ data nếu có, nếu không thì tạo mới
            $bookingCode = $data['booking_code'] ?? $this->generateBookingCode();
            
            $sql = "INSERT INTO bookings (
                user_id,
                showtime_id,
                discount_id,
                booked_seats,
                seat_type,
                customer_type,
                total_amount,
                discount_amount,
                final_amount,
                status,
                booking_code,
                cinema_id,
                room_id
            ) VALUES (
                :user_id,
                :showtime_id,
                :discount_id,
                :booked_seats,
                :seat_type,
                :customer_type,
                :total_amount,
                :discount_amount,
                :final_amount,
                :status,
                :booking_code,
                :cinema_id,
                :room_id
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $data['user_id'] ?? null,
                ':showtime_id' => $data['showtime_id'] ?? null,
                ':discount_id' => $data['discount_id'] ?? null,
                ':booked_seats' => $data['booked_seats'] ?? null,
                ':seat_type' => $data['seat_type'] ?? null,
                ':customer_type' => $data['customer_type'] ?? null,
                ':total_amount' => $data['total_amount'] ?? null,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':final_amount' => $data['final_amount'] ?? null,
                ':status' => $data['status'] ?? 'pending',
                ':booking_code' => $bookingCode,
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':room_id' => $data['room_id'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật trạng thái đặt vé
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE bookings SET status = :status WHERE id = :id";
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
     * Xóa đặt vé
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM bookings WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Lấy booking items (đồ ăn/nước)
     */
    public function getBookingItems($bookingId)
    {
        try {
            $sql = "SELECT booking_items.*,
                    food_drinks.name AS food_name,
                    food_drinks.image AS food_image
                    FROM booking_items
                    LEFT JOIN food_drinks ON booking_items.food_drink_id = food_drinks.id
                    WHERE booking_items.booking_id = :booking_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':booking_id' => $bookingId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy thông tin thanh toán
     */
    public function getPayment($bookingId)
    {
        try {
            $sql = "SELECT * FROM payments WHERE booking_id = :booking_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':booking_id' => $bookingId]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Lấy đặt vé theo user_id (không phân trang)
     */
    public function getByUserId($userId)
    {
        try {
            $sql = "SELECT bookings.*, 
                    movies.title AS movie_title,
                    movies.image AS movie_image,
                    showtimes.show_date,
                    showtimes.start_time,
                    showtimes.end_time,
                    showtimes.format AS showtime_format,
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM bookings
                    LEFT JOIN showtimes ON bookings.showtime_id = showtimes.id
                    LEFT JOIN movies ON showtimes.movie_id = movies.id
                    LEFT JOIN rooms ON bookings.room_id = rooms.id
                    LEFT JOIN cinemas ON bookings.cinema_id = cinemas.id
                    WHERE bookings.user_id = :user_id
                    ORDER BY bookings.booking_date DESC, bookings.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy danh sách ghế đã đặt cho suất chiếu
     */
    public function getBookedSeatsByShowtime($showtimeId)
    {
        try {
            $sql = "SELECT booked_seats FROM bookings 
                    WHERE showtime_id = :showtime_id 
                    AND status IN ('pending', 'confirmed', 'paid', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':showtime_id' => $showtimeId]);
            $bookings = $stmt->fetchAll();
            
            $bookedSeats = [];
            foreach ($bookings as $booking) {
                if (!empty($booking['booked_seats'])) {
                    $seats = explode(',', $booking['booked_seats']);
                    foreach ($seats as $seat) {
                        $seat = trim($seat);
                        if (!empty($seat)) {
                            $bookedSeats[] = $seat;
                        }
                    }
                }
            }
            
            return array_unique($bookedSeats);
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }
}

?>

