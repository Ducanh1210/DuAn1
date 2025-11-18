<?php
class BookingController
{
    public $showtime;
    public $seat;
    public $movie;
    public $room;
    public $booking;

    public function __construct()
    {
        require_once './models/Showtime.php';
        require_once './models/Seat.php';
        require_once './models/Movie.php';
        require_once './models/Room.php';
        require_once './models/Booking.php';
        $this->showtime = new Showtime();
        $this->seat = new Seat();
        $this->movie = new Movie();
        $this->room = new Room();
        $this->booking = new Booking();
    }

    /**
     * Hiển thị trang chọn ghế
     */
    public function selectSeats()
    {
        $showtimeId = $_GET['showtime_id'] ?? null;
        if (!$showtimeId) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy thông tin showtime
        $showtime = $this->showtime->find($showtimeId);
        if (!$showtime || !$showtime['room_id']) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy thông tin phim
        $movie = $this->movie->find($showtime['movie_id']);
        if (!$movie) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy thông tin phòng
        $room = $this->room->find($showtime['room_id']);
        if (!$room) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy tất cả ghế của phòng
        $seats = $this->seat->getByRoom($showtime['room_id']);

        // Lấy các ghế đã đặt cho showtime này
        $bookedSeats = $this->getBookedSeats($showtimeId);

        // Sắp xếp ghế theo hàng và số
        $seatsByRow = [];
        foreach ($seats as $seat) {
            $row = $seat['row_label'] ?? 'A';
            if (!isset($seatsByRow[$row])) {
                $seatsByRow[$row] = [];
            }
            $seatsByRow[$row][] = $seat;
        }
        ksort($seatsByRow);

        // Sắp xếp ghế trong mỗi hàng theo số
        foreach ($seatsByRow as $row => &$rowSeats) {
            usort($rowSeats, function($a, $b) {
                return ($a['seat_number'] ?? 0) - ($b['seat_number'] ?? 0);
            });
        }

        // Tính thời gian countdown (15 phút = 900 giây)
        $countdownSeconds = 15 * 60; // 15 phút

        renderClient('client/select_seats.php', [
            'showtime' => $showtime,
            'movie' => $movie,
            'room' => $room,
            'seatsByRow' => $seatsByRow,
            'bookedSeats' => $bookedSeats,
            'countdownSeconds' => $countdownSeconds
        ], 'Chọn ghế - ' . htmlspecialchars($movie['title']));
        exit;
    }

    /**
     * Lấy danh sách ghế đã đặt cho showtime
     */
    private function getBookedSeats($showtimeId)
    {
        try {
            $conn = connectDB();
            $sql = "SELECT booked_seats FROM bookings 
                    WHERE showtime_id = :showtime_id 
                    AND status IN ('confirmed', 'pending', 'paid')";
            $stmt = $conn->prepare($sql);
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

            return $bookedSeats;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * API endpoint để lấy dữ liệu ghế theo showtime (JSON)
     */
    public function getSeatsApi()
    {
        $showtimeId = $_GET['showtime_id'] ?? null;
        if (!$showtimeId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Showtime ID is required']);
            exit;
        }

        // Lấy thông tin showtime
        $showtime = $this->showtime->find($showtimeId);
        if (!$showtime || !$showtime['room_id']) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Showtime not found']);
            exit;
        }

        // Lấy thông tin phim
        $movie = $this->movie->find($showtime['movie_id']);
        if (!$movie) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Movie not found']);
            exit;
        }

        // Lấy thông tin phòng
        $room = $this->room->find($showtime['room_id']);
        if (!$room) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Room not found']);
            exit;
        }

        // Lấy tất cả ghế của phòng
        $seats = $this->seat->getByRoom($showtime['room_id']);

        // Lấy các ghế đã đặt cho showtime này
        $bookedSeats = $this->getBookedSeats($showtimeId);

        // Sắp xếp ghế theo hàng và số
        $seatsByRow = [];
        foreach ($seats as $seat) {
            $row = $seat['row_label'] ?? 'A';
            if (!isset($seatsByRow[$row])) {
                $seatsByRow[$row] = [];
            }
            $seatsByRow[$row][] = $seat;
        }
        ksort($seatsByRow);

        // Sắp xếp ghế trong mỗi hàng theo số
        foreach ($seatsByRow as $row => &$rowSeats) {
            usort($rowSeats, function($a, $b) {
                return ($a['seat_number'] ?? 0) - ($b['seat_number'] ?? 0);
            });
        }

        header('Content-Type: application/json');
        echo json_encode([
            'showtime' => $showtime,
            'movie' => $movie,
            'room' => $room,
            'seatsByRow' => $seatsByRow,
            'bookedSeats' => $bookedSeats
        ]);
        exit;
    }

    /**
     * Hiển thị danh sách đặt vé (Admin)
     */
    public function list()
    {
        $status = $_GET['status'] ?? null;
        $date = $_GET['date'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $result = $this->booking->paginate($page, 10, $status, $date);
        
        render('admin/bookings/list.php', [
            'data' => $result['data'],
            'selectedStatus' => $status,
            'selectedDate' => $date,
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ]
        ]);
    }

    /**
     * Hiển thị chi tiết đặt vé (Admin)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $booking = $this->booking->find($id);
        if (!$booking) {
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        // Lấy booking items và payment
        $bookingItems = $this->booking->getBookingItems($id);
        $payment = $this->booking->getPayment($id);

        render('admin/bookings/show.php', [
            'booking' => $booking,
            'bookingItems' => $bookingItems,
            'payment' => $payment
        ]);
    }

    /**
     * Xóa đặt vé (Admin)
     */
    public function deleteBooking()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $this->booking->delete($id);
        header('Location: ' . BASE_URL . '?act=bookings');
        exit;
    }

    /**
     * Cập nhật trạng thái đặt vé (Admin)
     */
    public function updateStatus()
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        $result = $this->booking->updateStatus($id, $status);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Cập nhật thành công' : 'Cập nhật thất bại'
        ]);
        exit;
    }

    /**
     * Hiển thị lịch sử đặt vé của khách hàng (Client)
     */
    public function myBookings()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?act=dangnhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $result = $this->booking->getByUser($userId, $page, 10);

        renderClient('client/my-bookings.php', [
            'data' => $result['data'],
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ]
        ], 'Lịch sử đặt vé');
    }
}

?>

