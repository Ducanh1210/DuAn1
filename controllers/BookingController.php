<?php
// BOOKING CONTROLLER - Xử lý logic đặt vé
// Chức năng: Chọn ghế, thanh toán, xử lý thanh toán VNPay, lịch sử đặt vé, quản lý đặt vé (admin)
class BookingController
{
    public $booking; // Model Booking để tương tác với database

    public function __construct()
    {
        require_once __DIR__ . '/../models/Booking.php';
        require_once __DIR__ . '/../models/Seat.php';
        require_once __DIR__ . '/../models/Showtime.php';
        require_once __DIR__ . '/../models/Room.php';
        require_once __DIR__ . '/../models/Movie.php';
        require_once __DIR__ . '/../models/Cinema.php';
        require_once __DIR__ . '/../models/DiscountCode.php';
        $this->booking = new Booking(); // Khởi tạo Model Booking
    }

    // Trang chọn ghế - hiển thị sơ đồ ghế, tính giá vé, lấy ghế đã đặt
    public function selectSeats()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireLogin(); // Yêu cầu đăng nhập - nếu chưa đăng nhập sẽ redirect về trang đăng nhập

        // Thêm no-cache headers để tránh load giao diện cũ từ cache
        header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        try {
            $showtimeId = $_GET['showtime_id'] ?? null;
            if (!$showtimeId) {
                header('Location: ' . BASE_URL . '?act=trangchu');
                exit;
            }

            $showtimeModel = new Showtime();
            $showtime = $showtimeModel->find($showtimeId);

            if (!$showtime) {
                header('Location: ' . BASE_URL . '?act=trangchu');
                exit;
            }

            // Kiểm tra room_id có tồn tại không
            if (empty($showtime['room_id'])) {
                error_log('Showtime ' . $showtimeId . ' không có room_id');
                $_SESSION['error_message'] = 'Suất chiếu chưa được gán phòng chiếu';
                header('Location: ' . BASE_URL . '?act=trangchu');
                exit;
            }

            $roomModel = new Room();
            $room = $roomModel->find($showtime['room_id']);

            if (!$room) {
                error_log('Không tìm thấy phòng với ID: ' . $showtime['room_id'] . ' cho showtime: ' . $showtimeId);
                $_SESSION['error_message'] = 'Không tìm thấy phòng chiếu';
                header('Location: ' . BASE_URL . '?act=trangchu');
                exit;
            }

            $movieModel = new Movie();
            $movie = $movieModel->find($showtime['movie_id']);

            if (!$movie) {
                header('Location: ' . BASE_URL . '?act=trangchu');
                exit;
            }

            $seatModel = new Seat();
            $seatsByRow = $seatModel->getSeatMapByRoom($room['id']);

            // Đảm bảo seatsByRow là array
            if (!is_array($seatsByRow)) {
                $seatsByRow = [];
            }

            // Kiểm tra nếu phòng không có ghế
            if (empty($seatsByRow) || count($seatsByRow) === 0) {
                error_log('Phòng ' . $room['id'] . ' (' . ($room['room_code'] ?? 'N/A') . ') không có ghế nào');
                $_SESSION['error_message'] = 'Phòng chiếu ' . ($room['room_code'] ?? $room['name'] ?? 'N/A') . ' chưa có ghế. Vui lòng liên hệ quản trị viên.';
                header('Location: ' . BASE_URL . '?act=trangchu');
                exit;
            }

            // Lấy danh sách ghế đã đặt cho suất chiếu này
            $bookedSeats = $this->booking->getBookedSeatsByShowtime($showtimeId);

            // Đảm bảo bookedSeats là array
            if (!is_array($bookedSeats)) {
                $bookedSeats = [];
            }

            // Thời gian đếm ngược (15 phút = 900 giây)
            $countdownSeconds = 900;

            // Đảm bảo format được lấy đúng từ showtime
            $showtimeFormat = isset($showtime['format']) ? trim($showtime['format']) : '2D';

            // Format chỉ có 2D hoặc 3D
            $formatUpper = strtoupper($showtimeFormat);
            if ($formatUpper !== '3D') {
                $showtimeFormat = '2D';
            }

            // Đảm bảo showtime có format đúng trước khi tính giá
            $showtime['format'] = $showtimeFormat;

            // Lấy giá vé từ database (mặc định dùng giá người lớn để hiển thị)
            require_once __DIR__ . '/../models/TicketPrice.php';
            $ticketPriceModel = new TicketPrice();
            $normalPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'adult', 'normal');
            $vipPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'adult', 'vip');
            $studentNormalPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'student', 'normal');
            $studentVipPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'student', 'vip');

            renderClient('client/select_seats.php', [
                'showtime' => $showtime,
                'movie' => $movie,
                'room' => $room,
                'seatsByRow' => $seatsByRow,
                'bookedSeats' => $bookedSeats,
                'countdownSeconds' => $countdownSeconds,
                'normalPrice' => $normalPrice,
                'vipPrice' => $vipPrice
            ], 'Chọn ghế');
        } catch (Exception $e) {
            // Log lỗi và redirect
            error_log('Error in selectSeats: ' . $e->getMessage());
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }
    }

    // API lấy danh sách ghế của suất chiếu (AJAX)
    public function getSeatsApi()
    {
        header('Content-Type: application/json');

        $showtimeId = $_GET['showtime_id'] ?? null;
        if (!$showtimeId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu showtime_id']);
            exit;
        }

        try {
            $showtimeModel = new Showtime();
            $showtime = $showtimeModel->find($showtimeId);

            if (!$showtime) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy suất chiếu']);
                exit;
            }

            // Kiểm tra room_id có tồn tại không
            if (empty($showtime['room_id'])) {
                error_log('Showtime ' . $showtimeId . ' không có room_id');
                echo json_encode(['success' => false, 'message' => 'Suất chiếu chưa được gán phòng chiếu']);
                exit;
            }

            $roomModel = new Room();
            $room = $roomModel->find($showtime['room_id']);

            if (!$room) {
                error_log('Không tìm thấy phòng với ID: ' . $showtime['room_id'] . ' cho showtime: ' . $showtimeId);
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy phòng chiếu (ID: ' . $showtime['room_id'] . ')']);
                exit;
            }

            $seatModel = new Seat();
            $seatsByRow = $seatModel->getSeatMapByRoom($room['id']);

            // Kiểm tra nếu phòng không có ghế
            if (empty($seatsByRow) || !is_array($seatsByRow) || count($seatsByRow) === 0) {
                error_log('Phòng ' . $room['id'] . ' (' . ($room['room_code'] ?? 'N/A') . ') không có ghế nào');
                echo json_encode([
                    'success' => false,
                    'message' => 'Phòng chiếu ' . ($room['room_code'] ?? $room['name'] ?? 'N/A') . ' chưa có ghế. Vui lòng liên hệ quản trị viên để tạo ghế cho phòng này.'
                ]);
                exit;
            }

            $bookedSeats = $this->booking->getBookedSeatsByShowtime($showtimeId);

            // Lấy giá vé từ ticket_prices
            require_once __DIR__ . '/../models/TicketPrice.php';
            $ticketPriceModel = new TicketPrice();

            // Đảm bảo format được lấy đúng từ showtime
            $showtimeFormat = isset($showtime['format']) ? trim($showtime['format']) : '2D';

            // Format chỉ có 2D hoặc 3D
            $formatUpper = strtoupper($showtimeFormat);
            if ($formatUpper !== '3D') {
                $showtimeFormat = '2D';
            }

            // Đảm bảo showtime có format đúng trước khi tính giá
            $showtime['format'] = $showtimeFormat;

            $adultNormalPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'adult', 'normal');
            $adultVipPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'adult', 'vip');
            $studentNormalPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'student', 'normal');
            $studentVipPrice = $ticketPriceModel->getPriceForShowtime($showtime, 'student', 'vip');

            echo json_encode([
                'success' => true,
                'showtime' => $showtime,
                'room' => $room,
                'seatsByRow' => $seatsByRow,
                'bookedSeats' => $bookedSeats,
                'prices' => [
                    'adult_normal' => $adultNormalPrice,
                    'adult_vip' => $adultVipPrice,
                    'student_normal' => $studentNormalPrice,
                    'student_vip' => $studentVipPrice
                ],
                'format' => $showtimeFormat
            ]);
        } catch (Exception $e) {
            error_log('Error in getSeatsApi: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi tải dữ liệu ghế: ' . $e->getMessage()]);
        }
        exit;
    }

    // Trang thanh toán - nhập thông tin, áp dụng mã giảm giá, tính tiền
    public function payment()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireLogin();

        $showtimeId = $_GET['showtime_id'] ?? null;
        $seatIds = $_GET['seats'] ?? '';
        $seatLabels = $_GET['seat_labels'] ?? '';

        if (!$showtimeId || !$seatIds) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        $showtimeModel = new Showtime();
        $showtime = $showtimeModel->find($showtimeId);

        if (!$showtime) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Đảm bảo format được lấy đúng từ showtime
        $showtimeFormat = isset($showtime['format']) ? trim($showtime['format']) : '2D';

        // Chuyển đổi format để đảm bảo đúng: IMAX/4DX -> 3D
        $formatUpper = strtoupper($showtimeFormat);
        if (in_array($formatUpper, ['3D', 'IMAX', '4DX'])) {
            $showtimeFormat = '3D';
        } else {
            $showtimeFormat = '2D';
        }

        // Đảm bảo showtime có format đúng trước khi tính giá
        $showtime['format'] = $showtimeFormat;

        $roomModel = new Room();
        $room = $roomModel->find($showtime['room_id']);

        // Lấy thông tin rạp
        $cinema = null;
        if ($room && !empty($room['cinema_id'])) {
            $cinemaModel = new Cinema();
            $cinema = $cinemaModel->find($room['cinema_id']);
        }

        $movieModel = new Movie();
        $movie = $movieModel->find($showtime['movie_id']);

        // Lấy số lượng người lớn và sinh viên
        $adultCount = isset($_GET['adult_count']) ? (int)$_GET['adult_count'] : 0;
        $studentCount = isset($_GET['student_count']) ? (int)$_GET['student_count'] : 0;

        // Sử dụng giá vé từ database
        require_once __DIR__ . '/../models/TicketPrice.php';
        $ticketPriceModel = new TicketPrice();

        // Lấy thông tin ghế đã chọn
        $seatModel = new Seat();
        $seatIdArray = explode(',', $seatIds);
        $selectedSeats = [];
        $totalPrice = 0;

        foreach ($seatIdArray as $index => $seatId) {
            $seat = $seatModel->find(trim($seatId));
            if ($seat) {
                // Xác định loại khách hàng
                $customerType = ($index < $adultCount) ? 'adult' : 'student';

                // Lấy giá vé từ database dựa trên showtime, loại khách hàng và loại ghế
                $seatType = $seat['seat_type'] === 'vip' ? 'vip' : 'normal';
                $price = $ticketPriceModel->getPriceForShowtime($showtime, $customerType, $seatType);

                $selectedSeats[] = [
                    'id' => $seat['id'],
                    'label' => ($seat['row_label'] ?? '') . ($seat['seat_number'] ?? ''),
                    'type' => $seat['seat_type'] ?? 'normal',
                    'customer_type' => $customerType,
                    'price' => $price
                ];
                $totalPrice += $price;
            }
        }

        $vipExtraPrice = 0; // Không cần nữa vì giá đã bao gồm trong database

        renderClient('client/thanhtoan.php', [
            'showtime' => $showtime,
            'movie' => $movie,
            'room' => $room,
            'cinema' => $cinema,
            'selectedSeats' => $selectedSeats,
            'seatIds' => $seatIds,
            'seatLabels' => $seatLabels,
            'totalPrice' => $totalPrice,
            'vipExtraPrice' => $vipExtraPrice,
            'adultCount' => $adultCount,
            'studentCount' => $studentCount
        ], 'Thanh toán');
    }

    /**
     * XỬ LÝ THANH TOÁN - TẠO BOOKING, GỬI ĐẾN VNPAY
     * 
     * Chức năng: Xử lý request thanh toán từ trang thanhtoan.php, tạo booking và redirect đến VNPay
     * 
     * LUỒNG XỬ LÝ:
     * 1. Validate request (POST, đăng nhập, tham số đầy đủ)
     * 2. Lấy thông tin showtime, room, seats
     * 3. Tính tổng tiền (dựa trên giá vé, loại ghế, loại khách hàng)
     * 4. Validate và áp dụng mã giảm giá (nếu có)
     * 5. Kiểm tra ghế đã được đặt chưa (tránh double booking)
     * 6. Sử dụng TRANSACTION để đảm bảo atomic operation
     * 7. Tạo booking với status = 'pending'
     * 8. Tạo URL thanh toán VNPay
     * 9. Trả về JSON với payment_url để frontend redirect
     * 
     * API ENDPOINT: ?act=payment-process (POST)
     * 
     * REQUEST PARAMS:
     * - showtime_id: ID suất chiếu
     * - seats: Danh sách ID ghế (comma-separated)
     * - seat_labels: Danh sách nhãn ghế (comma-separated, ví dụ: A1,A2)
     * - payment_method: Phương thức thanh toán (vnpay)
     * - adult_count: Số lượng người lớn
     * - student_count: Số lượng sinh viên
     * - voucher_code: Mã giảm giá (tùy chọn)
     * 
     * RESPONSE (JSON):
     * - success: true/false
     * - payment_method: 'vnpay'
     * - payment_url: URL thanh toán VNPay (nếu success = true)
     * - message: Thông báo lỗi (nếu success = false)
     */
    public function processPayment()
    {
        // Tắt tất cả output để đảm bảo chỉ trả về JSON (không có HTML, warning, notice)
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);

        // Bắt đầu output buffering ngay từ đầu (để clean output trước khi trả về JSON)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        ob_start();

        // Set header JSON ngay từ đầu (để frontend biết response là JSON)
        header('Content-Type: application/json; charset=utf-8');

        require_once __DIR__ . '/../commons/auth.php';

        // Kiểm tra đăng nhập mà không redirect (để trả về JSON)
        if (!isLoggedIn()) {
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thanh toán'
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        $showtimeId = $_POST['showtime_id'] ?? null;
        $seatIds = $_POST['seats'] ?? '';
        $seatLabels = $_POST['seat_labels'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';

        if (!$showtimeId || !$seatIds || !$paymentMethod) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin thanh toán'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        try {
            $showtimeModel = new Showtime();
            $showtime = $showtimeModel->find($showtimeId);

            if (!$showtime) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy suất chiếu'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            }

            // Đảm bảo format được lấy đúng từ showtime
            $showtimeFormat = isset($showtime['format']) ? trim($showtime['format']) : '2D';

            // Format chỉ có 2D hoặc 3D
            $formatUpper = strtoupper($showtimeFormat);
            if ($formatUpper !== '3D') {
                $showtimeFormat = '2D';
            }

            // Đảm bảo showtime có format đúng trước khi tính giá
            $showtime['format'] = $showtimeFormat;

            // Lấy thông tin phòng để xác định số cột
            $roomModel = new Room();
            $room = $roomModel->find($showtime['room_id'] ?? null);

            // Tính tổng tiền sử dụng giá vé từ database
            require_once __DIR__ . '/../models/TicketPrice.php';
            $ticketPriceModel = new TicketPrice();

            $seatModel = new Seat();
            $seatIdArray = explode(',', $seatIds);
            $totalPrice = 0;

            // Lấy số lượng người lớn và sinh viên
            $adultCount = isset($_POST['adult_count']) ? (int)$_POST['adult_count'] : 0;
            $studentCount = isset($_POST['student_count']) ? (int)$_POST['student_count'] : 0;
            $totalPeople = $adultCount + $studentCount;

            // Validation: Nếu chọn 1 vé, chỉ cho phép các cột: 1, 3, 4, 6, 7, 9, 10, 12
            if ($totalPeople === 1) {
                $allowedColumns = [1, 3, 4, 6, 7, 9, 10, 12];
                foreach ($seatIdArray as $seatId) {
                    $seat = $seatModel->find(trim($seatId));
                    if ($seat) {
                        $seatColumn = (int)($seat['seat_number'] ?? 0);
                        if (!in_array($seatColumn, $allowedColumns)) {
                            ob_clean();
                            echo json_encode([
                                'success' => false,
                                'message' => 'Khi chọn 1 vé, bạn chỉ có thể chọn các cột: 1, 3, 4, 6, 7, 9, 10, 12'
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            exit;
                        }
                    }
                }
            }

            foreach ($seatIdArray as $index => $seatId) {
                $seat = $seatModel->find(trim($seatId));
                if ($seat) {
                    $seatType = $seat['seat_type'] === 'vip' ? 'vip' : 'normal';
                    // Xác định loại khách hàng: số ghế đầu = người lớn, số ghế sau = sinh viên
                    $customerType = ($index < $adultCount) ? 'adult' : 'student';
                    $price = $ticketPriceModel->getPriceForShowtime($showtime, $customerType, $seatType);
                    $totalPrice += $price;
                }
            }

            // Xử lý discount code
            $discountId = null;
            $discountAmount = 0;
            $voucherCode = $_POST['voucher_code'] ?? '';

            if (!empty($voucherCode)) {
                // Lấy movie_id từ showtime để validate mã giảm giá cho phim cụ thể
                $movieId = isset($showtime['movie_id']) && !empty($showtime['movie_id']) ? (int)$showtime['movie_id'] : null;
                
                // Lấy user_id để kiểm tra user đã dùng mã này chưa
                $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

                $discountCodeModel = new DiscountCode();
                $result = $discountCodeModel->validateDiscountCode($voucherCode, $totalPrice, $movieId, count($seatIdArray), $userId);

                // Kiểm tra nếu có lỗi
                if (isset($result['error'])) {
                    ob_clean();
                    echo json_encode([
                        'success' => false,
                        'message' => $result['error']
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    exit;
                }

                // Nếu hợp lệ và có discount_percent
                if ($result && !isset($result['error']) && isset($result['discount_percent']) && $result['discount_percent'] > 0) {
                    $discountId = $result['id'];
                    $discountAmount = $result['discount_amount'];
                } elseif ($result && !isset($result['error'])) {
                    // Mã hợp lệ nhưng không có discount (có thể là 0%)
                    // Không cần làm gì, chỉ bỏ qua
                } else {
                    // Mã không hợp lệ
                    ob_clean();
                    echo json_encode([
                        'success' => false,
                        'message' => 'Mã giảm giá không hợp lệ'
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    exit;
                }
            }

            $finalAmount = $totalPrice - $discountAmount;

            // ============================================
            // KIỂM TRA GHẾ ĐÃ ĐƯỢC ĐẶT CHƯA (TRƯỚC KHI TẠO BOOKING)
            // ============================================
            // Lấy danh sách ghế đã được đặt (bao gồm cả pending, confirmed, paid)
            $bookedSeats = $this->booking->getBookedSeatsByShowtime($showtimeId);
            
            // Parse ghế người dùng đang muốn đặt (chuẩn hóa về uppercase)
            $requestedSeats = [];
            if (!empty($seatLabels)) {
                $requestedSeatsArray = explode(',', $seatLabels);
                foreach ($requestedSeatsArray as $seat) {
                    $seat = trim($seat);
                    if (!empty($seat) && $seat !== ',') {
                        $requestedSeats[] = strtoupper($seat);
                    }
                }
            }
            
            // Kiểm tra xem có ghế nào đã được đặt chưa
            $conflictedSeats = [];
            foreach ($requestedSeats as $seat) {
                // bookedSeats đã được normalize về uppercase trong model
                if (in_array($seat, $bookedSeats)) {
                    $conflictedSeats[] = $seat;
                }
            }
            
            if (!empty($conflictedSeats)) {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Một số ghế bạn chọn đã được đặt: ' . implode(', ', $conflictedSeats) . '. Vui lòng chọn ghế khác!'
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            }

            // ============================================
            // SỬ DỤNG TRANSACTION ĐỂ ĐẢM BẢO ATOMIC OPERATION
            // ============================================
            $conn = connectDB();
            $conn->beginTransaction();
            
            try {
                // Lock bảng bookings để tránh race condition
                // Check lại một lần nữa sau khi lock (double-check locking pattern)
                // Sử dụng SELECT FOR UPDATE để lock các bản ghi trong transaction
                $lockedBookedSeats = $this->booking->getBookedSeatsByShowtime($showtimeId, true);
                $conflictedSeatsAfterLock = [];
                foreach ($requestedSeats as $seat) {
                    // lockedBookedSeats đã được normalize về uppercase trong model
                    if (in_array($seat, $lockedBookedSeats)) {
                        $conflictedSeatsAfterLock[] = $seat;
                    }
                }
                
                if (!empty($conflictedSeatsAfterLock)) {
                    $conn->rollBack();
                    ob_clean();
                    echo json_encode([
                        'success' => false,
                        'message' => 'Một số ghế bạn chọn đã được đặt: ' . implode(', ', $conflictedSeatsAfterLock) . '. Vui lòng chọn ghế khác!'
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    exit;
                }

                // Tạo booking
                $bookingCode = 'BK' . time() . rand(1000, 9999);
                $bookingData = [
                    'user_id' => $_SESSION['user_id'],
                    'showtime_id' => $showtimeId,
                    'room_id' => $showtime['room_id'],
                    'discount_id' => $discountId,
                    'cinema_id' => $showtime['cinema_id'] ?? null,
                    'booked_seats' => $seatLabels,
                    'total_amount' => $totalPrice,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'status' => 'pending',
                    'booking_code' => $bookingCode
                ];

                $bookingId = $this->booking->insert($bookingData);
                
                if (!$bookingId) {
                    $conn->rollBack();
                    ob_clean();
                    echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn đặt vé'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    exit;
                }

                // Commit transaction
                $conn->commit();
            } catch (Exception $e) {
                $conn->rollBack();
                throw $e;
            }

            if (!$bookingId) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn đặt vé'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            }

            // ============================================
            // XỬ LÝ THANH TOÁN THEO PHƯƠNG THỨC
            // ============================================
            if ($paymentMethod === 'vnpay') {
                // Tích hợp VNPay: Tạo URL thanh toán
                require_once __DIR__ . '/../commons/VNPay.php';
                $vnpay = new VNPay();

                // Tạo URL thanh toán VNPay
                // QUAN TRỌNG: Sử dụng finalAmount (sau khi giảm giá) thay vì totalPrice
                // VNPay sẽ thanh toán số tiền finalAmount, không phải totalPrice
                $paymentUrl = $vnpay->createPaymentUrl([
                    'txn_ref' => $bookingId . '_' . time(), // Mã đơn hàng: bookingId_timestamp (để parse lại khi callback)
                    'amount' => $finalAmount, // Số tiền thanh toán (đã trừ discount)
                    'order_info' => 'Thanh toan dat ve xem phim - ' . $bookingCode // Thông tin đơn hàng
                ]);

                // Trả về JSON với payment_url để frontend redirect user đến VNPay
                ob_clean();
                echo json_encode([
                    'success' => true,
                    'payment_method' => 'vnpay',
                    'payment_url' => $paymentUrl // URL thanh toán VNPay (frontend sẽ redirect)
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            } else {
                // Các phương thức thanh toán khác (chưa implement: MoMo, ZaloPay, ...)
                ob_clean();
                echo json_encode([
                    'success' => true,
                    'message' => 'Thanh toán thành công!',
                    'booking_id' => $bookingId
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            }
        } catch (Exception $e) {
            // Xóa output buffer trước khi trả về JSON
            ob_clean();
            error_log('Error in processPayment: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thanh toán. Vui lòng thử lại.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Error $e) {
            // Xử lý lỗi PHP 7+ (TypeError, ParseError, etc.)
            ob_clean();
            error_log('Fatal error in processPayment: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thanh toán. Vui lòng thử lại.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        exit;
    }

    // Bán vé cho nhân viên (Staff) - DEPRECATED, đã vô hiệu hóa
    public function sellTicket()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireStaff(); // Chỉ nhân viên mới được bán vé

        $errors = [];
        $cinemaId = getCurrentCinemaId();

        if (!$cinemaId) {
            $errors['general'] = 'Bạn chưa được gán cho rạp nào. Vui lòng liên hệ quản trị viên.';
        }

        // Lấy danh sách lịch chiếu của rạp
        $showtimeModel = new Showtime();
        $showtimes = [];
        if ($cinemaId) {
            $result = $showtimeModel->paginateByCinema($cinemaId, 1, 100); // Lấy tất cả lịch chiếu
            $showtimes = $result['data'] ?? [];
        }

        // Lọc chỉ lấy lịch chiếu sắp tới và đang chiếu
        $today = date('Y-m-d');
        $now = date('H:i:s');
        $availableShowtimes = [];
        foreach ($showtimes as $st) {
            $showDate = $st['show_date'];
            $startTime = $st['start_time'];

            // Chỉ lấy lịch chiếu chưa bắt đầu hoặc đang chiếu
            if ($showDate > $today || ($showDate == $today && $startTime >= $now)) {
                $availableShowtimes[] = $st;
            }
        }

        render('admin/bookings/sell_ticket.php', [
            'errors' => $errors,
            'showtimes' => $availableShowtimes,
            'cinemaId' => $cinemaId
        ]);
    }

    // Danh sách đặt vé (Admin/Manager/Staff) - có phân trang, lọc theo trạng thái/ngày
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();

        // Lọc theo trạng thái và ngày nếu có
        $status = $_GET['status'] ?? null;
        $date = $_GET['date'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo page >= 1

        // Nếu là manager hoặc staff, chỉ lấy đặt vé của rạp mình quản lý
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $result = $this->booking->paginateByCinema($cinemaId, $page, 10, $status, $date);
            } else {
                $result = ['data' => [], 'page' => 1, 'totalPages' => 0, 'total' => 0, 'perPage' => 10];
            }
        } else {
            // Admin xem tất cả
            $result = $this->booking->paginate($page, 10, $status, $date);
        }

        // Loại bỏ booking trùng lặp (cùng showtime_id và booked_seats)
        $data = $result['data'];
        $uniqueBookings = [];
        $seenKeys = [];
        
        // Thứ tự ưu tiên trạng thái: paid > confirmed > pending > cancelled
        $statusPriority = [
            'paid' => 4,
            'confirmed' => 3,
            'pending' => 2,
            'cancelled' => 1
        ];
        
        foreach ($data as $booking) {
            // Tạo key duy nhất dựa trên showtime_id và booked_seats
            $bookedSeats = trim($booking['booked_seats'] ?? '', ', ');
            $bookedSeats = preg_replace('/,+/', ',', $bookedSeats);
            $bookedSeats = trim($bookedSeats, ', ');
            
            if (empty($bookedSeats) || $bookedSeats === ',') {
                // Nếu không có ghế, vẫn hiển thị (không loại bỏ)
                $uniqueBookings[] = $booking;
                continue;
            }
            
            $key = $booking['showtime_id'] . '_' . md5(strtolower($bookedSeats));
            
            if (!isset($seenKeys[$key])) {
                // Chưa có booking nào với key này, thêm vào
                $seenKeys[$key] = [
                    'booking' => $booking,
                    'index' => count($uniqueBookings)
                ];
                $uniqueBookings[] = $booking;
            } else {
                // Đã có booking với key này, so sánh để giữ booking tốt hơn
                $existing = $seenKeys[$key]['booking'];
                $existingIndex = $seenKeys[$key]['index'];
                $existingPriority = $statusPriority[$existing['status'] ?? 'pending'] ?? 0;
                $currentPriority = $statusPriority[$booking['status'] ?? 'pending'] ?? 0;
                
                // Nếu booking hiện tại có priority cao hơn, thay thế
                if ($currentPriority > $existingPriority) {
                    $uniqueBookings[$existingIndex] = $booking;
                    $seenKeys[$key] = [
                        'booking' => $booking,
                        'index' => $existingIndex
                    ];
                }
                // Nếu cùng priority, giữ booking mới hơn (booking_date lớn hơn)
                elseif ($currentPriority === $existingPriority) {
                    $existingDate = strtotime($existing['booking_date'] ?? '1970-01-01');
                    $currentDate = strtotime($booking['booking_date'] ?? '1970-01-01');
                    if ($currentDate > $existingDate) {
                        $uniqueBookings[$existingIndex] = $booking;
                        $seenKeys[$key] = [
                            'booking' => $booking,
                            'index' => $existingIndex
                        ];
                    }
                }
                // Nếu booking hiện tại không tốt hơn, bỏ qua (không thêm vào)
            }
        }
        
        // Cập nhật lại dữ liệu sau khi loại bỏ duplicate
        $result['data'] = $uniqueBookings;

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

    // Xem chi tiết đặt vé (Admin) - thông tin vé, ghế, thanh toán
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

        // Lấy thông tin liên quan
        $showtimeModel = new Showtime();
        $showtime = $showtimeModel->find($booking['showtime_id']);

        $movieModel = new Movie();
        $movie = $movieModel->find($showtime['movie_id'] ?? null);

        $roomModel = new Room();
        $room = $roomModel->find($booking['room_id']);

        // Lấy thông tin thanh toán
        require_once __DIR__ . '/../models/Payment.php';
        $paymentModel = new Payment();
        $payment = $paymentModel->findByBookingId($booking['id']);

        render('admin/bookings/show.php', [
            'booking' => $booking,
            'showtime' => $showtime,
            'movie' => $movie,
            'room' => $room,
            'payment' => $payment
        ]);
    }

    // Xóa đặt vé (Admin)
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

    // Cập nhật trạng thái đặt vé (Admin) - pending/confirmed/paid/cancelled
    public function updateStatus()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        try {
            // Lấy thông tin booking trước khi cập nhật
            $booking = $this->booking->find($id);
            if (!$booking) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn đặt vé']);
                exit;
            }

            // Lấy trạng thái cũ trước khi cập nhật
            $oldStatus = $booking['status'];

            // Cập nhật status
            $updateResult = $this->booking->updateStatus($id, $status);

            if (!$updateResult) {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
                exit;
            }

            // Lấy lại booking sau khi cập nhật để đảm bảo có dữ liệu mới nhất
            $updatedBooking = $this->booking->find($id);

            // Nếu status = 'paid' và trước đó chưa phải 'paid' (ví dụ: từ cancelled -> paid)
            // Lưu ý: Nếu booking đã là 'paid' từ khi thanh toán thành công thì không cần cập nhật lại
            if ($status === 'paid' && $oldStatus !== 'paid') {
                // Cập nhật total_spending và tier_id của user (chỉ khi chưa được cập nhật)
                $this->updateUserSpendingAndTier($booking['user_id'], $booking['final_amount']);

                // Tạo notification cho user
                $this->createUserNotification($id, $updatedBooking, 'paid');
            } elseif ($status === 'cancelled' && $oldStatus !== 'cancelled') {
                // Tạo notification cho user nếu bị hủy
                $this->createUserNotification($id, $updatedBooking, 'cancelled');
            }

            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
        exit;
    }

    // Lịch sử đặt vé của user - danh sách vé đã đặt
    public function myBookings()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireLogin();

        $userId = $_SESSION['user_id'];
        $bookings = $this->booking->getByUserId($userId);

        renderClient('client/my-bookings.php', [
            'bookings' => $bookings
        ], 'Đặt vé của tôi');
    }

    /**
     * CALLBACK TỪ VNPAY SAU KHI THANH TOÁN XONG
     * 
     * Chức năng: Xử lý callback từ VNPay sau khi user thanh toán xong, cập nhật trạng thái booking
     * 
     * LUỒNG XỬ LÝ:
     * 1. VNPay gửi callback về URL: ?act=vnpay-return với các tham số trong $_GET
     * 2. Parse dữ liệu callback (processCallback): Lấy response_code, txn_ref, amount, ...
     * 3. Xác thực checksum (validateResponse): Đảm bảo dữ liệu không bị giả mạo
     * 4. Parse booking_id từ txn_ref (format: bookingId_timestamp)
     * 5. Lấy thông tin booking từ database
     * 6. Kiểm tra thanh toán thành công (isPaymentSuccess)
     * 7. Nếu thành công:
     *    - Kiểm tra lại ghế đã được đặt bởi booking khác chưa (tránh conflict)
     *    - Cập nhật payment: status = 'paid', lưu transaction_code
     *    - Cập nhật booking: status = 'paid'
     *    - Cập nhật total_spending và tier_id của user
     *    - Tạo notification cho user
     * 8. Nếu thất bại:
     *    - Cập nhật payment: status = 'failed'
     *    - Cập nhật booking: status = 'cancelled'
     * 9. Render trang kết quả thanh toán (payment-result.php)
     * 
     * API ENDPOINT: ?act=vnpay-return (GET)
     * 
     * CALLBACK PARAMS (từ VNPay):
     * - vnp_ResponseCode: Mã phản hồi (00 = thành công)
     * - vnp_TxnRef: Mã đơn hàng (bookingId_timestamp)
     * - vnp_Amount: Số tiền (đã nhân 100)
     * - vnp_TransactionStatus: Trạng thái giao dịch (00 = thành công)
     * - vnp_TransactionNo: Mã giao dịch VNPay (unique)
     * - vnp_SecureHash: Checksum để xác thực
     * 
     * BẢO MẬT:
     * - Luôn validate checksum trước khi xử lý
     * - Kiểm tra lại ghế đã được đặt chưa (tránh double booking)
     * - Sử dụng transaction để đảm bảo atomic operation
     */
    public function vnpayReturn()
    {
        // Session đã được start ở index.php, không cần start lại
        require_once __DIR__ . '/../commons/VNPay.php';
        require_once __DIR__ . '/../models/Payment.php';

        // DEBUG: Xem dữ liệu từ VNPay (có thể xóa sau khi test xong)
        // Uncomment dòng sau để xem chi tiết dữ liệu callback
        // echo '<pre>'; print_r($_GET); echo '</pre>'; die();

        // Khởi tạo VNPay và xử lý callback
        $vnpay = new VNPay();
        // processCallback(): Parse dữ liệu từ $_GET, chuyển đổi số tiền, xác thực checksum
        $callbackData = $vnpay->processCallback();

        // Debug: Log dữ liệu callback (có thể xóa sau khi test xong)
        // error_log('VNPay Callback Data: ' . json_encode($callbackData));

        // ============================================
        // PARSE BOOKING_ID TỪ TXN_REF
        // ============================================
        // txn_ref format: bookingId_timestamp (ví dụ: 123_1699123456)
        // Tách để lấy booking_id
        $txnRef = $callbackData['txn_ref'] ?? '';
        $bookingId = null;
        if (!empty($txnRef)) {
            $parts = explode('_', $txnRef);
            $bookingId = $parts[0] ?? null; // Lấy phần đầu (booking_id)
        }

        // Validate booking_id
        if (!$bookingId) {
            // Không tìm thấy booking_id trong txn_ref
            renderClient('client/payment-result.php', [
                'success' => false,
                'message' => 'Không tìm thấy thông tin đơn đặt vé',
                'booking_id' => null
            ], 'Kết quả thanh toán');
            exit;
        }

        // ============================================
        // LẤY THÔNG TIN BOOKING TỪ DATABASE
        // ============================================
        $booking = $this->booking->find($bookingId);
        if (!$booking) {
            // Booking không tồn tại
            renderClient('client/payment-result.php', [
                'success' => false,
                'message' => 'Đơn đặt vé không tồn tại',
                'booking_id' => null
            ], 'Kết quả thanh toán');
            exit;
        }

        // ============================================
        // KIỂM TRA THANH TOÁN CÓ THÀNH CÔNG KHÔNG
        // ============================================
        // isPaymentSuccess(): Kiểm tra 3 điều kiện:
        // 1. is_valid = true (checksum hợp lệ)
        // 2. response_code = '00' (VNPay phản hồi thành công)
        // 3. transaction_status = '00' (Giao dịch thành công)
        $isSuccess = $vnpay->isPaymentSuccess($callbackData);

        // ============================================
        // LẤY THÔNG TIN PAYMENT (NẾU CÓ)
        // ============================================
        // Tìm payment record đã tạo trước đó (nếu có)
        // Nếu có -> update, nếu không có -> insert mới
        $payment = new Payment();
        $existingPayment = $payment->findByBookingId($bookingId);

        // ============================================
        // XỬ LÝ THEO KẾT QUẢ THANH TOÁN
        // ============================================
        if ($isSuccess) {
            // ============================================
            // THANH TOÁN THÀNH CÔNG
            // ============================================
            // Kiểm tra lại xem ghế đã được đặt bởi booking khác chưa (trước khi cập nhật status)
            // Đây là double-check để tránh conflict khi nhiều user đặt cùng ghế
            $showtimeId = $booking['showtime_id'] ?? null;
            $bookedSeats = trim($booking['booked_seats'] ?? '', ', ');
            $bookedSeats = preg_replace('/,+/', ',', $bookedSeats);
            $bookedSeats = trim($bookedSeats, ', ');
            
            if (!empty($bookedSeats) && $showtimeId) {
                // Lấy tất cả booking khác (không phải booking này) đã đặt cùng ghế với status paid/confirmed
                $conn = connectDB();
                $checkSql = "SELECT booked_seats FROM bookings 
                            WHERE showtime_id = :showtime_id 
                            AND id != :booking_id
                            AND status IN ('paid', 'confirmed')
                            AND booked_seats IS NOT NULL 
                            AND booked_seats != ''";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->execute([
                    ':showtime_id' => $showtimeId,
                    ':booking_id' => $bookingId
                ]);
                $otherBookings = $checkStmt->fetchAll();
                
                // Parse ghế của booking này
                $thisBookingSeats = [];
                $seatsArray = explode(',', $bookedSeats);
                foreach ($seatsArray as $seat) {
                    $seat = trim($seat);
                    if (!empty($seat) && $seat !== ',') {
                        $thisBookingSeats[] = strtoupper($seat);
                    }
                }
                
                // Kiểm tra xem có booking khác đã đặt cùng ghế chưa
                $conflictedSeats = [];
                foreach ($otherBookings as $otherBooking) {
                    $otherSeats = [];
                    if (!empty($otherBooking['booked_seats'])) {
                        $otherSeatsArray = explode(',', $otherBooking['booked_seats']);
                        foreach ($otherSeatsArray as $seat) {
                            $seat = trim($seat);
                            if (!empty($seat) && $seat !== ',') {
                                $otherSeats[] = strtoupper($seat);
                            }
                        }
                    }
                    
                    // Kiểm tra conflict
                    foreach ($thisBookingSeats as $seat) {
                        if (in_array($seat, $otherSeats)) {
                            $conflictedSeats[] = $seat;
                        }
                    }
                }
                
                // Nếu có conflict, hủy booking này
                if (!empty($conflictedSeats)) {
                    $conflictedSeats = array_unique($conflictedSeats);
                    $this->booking->updateStatus($bookingId, 'cancelled');
                    renderClient('client/payment-result.php', [
                        'success' => false,
                        'message' => 'Một số ghế bạn chọn đã được đặt bởi người khác: ' . implode(', ', $conflictedSeats) . '. Đơn đặt vé của bạn đã bị hủy.',
                        'booking_id' => $bookingId,
                        'booking' => $booking
                    ], 'Kết quả thanh toán');
                    exit;
                }
            }

            // ============================================
            // CẬP NHẬT PAYMENT VÀ BOOKING STATUS
            // ============================================
            // Thanh toán thành công - cập nhật status thành 'paid' ngay lập tức
            $paymentStatus = 'paid';
            $bookingStatus = 'paid'; // Thanh toán thành công ngay lập tức

            // Cập nhật payment: Nếu đã có payment record -> update, nếu chưa có -> insert mới
            if ($existingPayment) {
                // Update payment đã tồn tại
                $payment->update($existingPayment['id'], [
                    'transaction_code' => $callbackData['transaction_no'], // Mã giao dịch VNPay
                    'payment_date' => date('Y-m-d H:i:s'), // Ngày giờ thanh toán
                    'status' => $paymentStatus // 'paid'
                ]);
            } else {
                // Insert payment mới
                $paymentData = [
                    'booking_id' => $bookingId,
                    'method' => 'vnpay', // Phương thức thanh toán
                    'transaction_code' => $callbackData['transaction_no'], // Mã giao dịch VNPay
                    'total_amount' => $callbackData['amount'], // Tổng tiền từ VNPay (đã chia 100)
                    'discount_amount' => $booking['discount_amount'] ?? 0, // Số tiền giảm giá
                    'final_amount' => $booking['final_amount'] ?? $callbackData['amount'], // Tổng tiền cuối cùng
                    'status' => $paymentStatus // 'paid'
                ];
                $payment->insert($paymentData);
            }

            // Cập nhật booking status thành 'paid' (đơn đặt vé đã thanh toán)
            $this->booking->updateStatus($bookingId, $bookingStatus);

            // ============================================
            // CẬP NHẬT USER SPENDING VÀ TIER
            // ============================================
            // Cập nhật total_spending và tier_id của user ngay sau khi thanh toán thành công
            // total_spending: Tổng số tiền user đã chi
            // tier_id: Hạng thành viên (dựa trên total_spending)
            $this->updateUserSpendingAndTier($booking['user_id'], $booking['final_amount']);

            // ============================================
            // LẤY LẠI BOOKING VÀ TẠO NOTIFICATION
            // ============================================
            // Lấy lại booking sau khi cập nhật để đảm bảo có dữ liệu mới nhất
            $updatedBooking = $this->booking->find($bookingId);

            // Tạo notification cho user khi thanh toán thành công
            $this->createUserNotification($bookingId, $updatedBooking, 'paid');

            renderClient('client/payment-result.php', [
                'success' => true,
                'message' => 'Thanh toán thành công! Đơn đặt vé của bạn đã được xác nhận.',
                'booking_id' => $bookingId,
                'booking' => $updatedBooking,
                'transactionCode' => $callbackData['transaction_no'] ?? null
            ], 'Kết quả thanh toán');
        } else {
            // ============================================
            // THANH TOÁN THẤT BẠI HOẶC BỊ HỦY
            // ============================================
            $paymentStatus = 'failed';
            $bookingStatus = 'cancelled'; // Hủy đơn đặt vé

            // Cập nhật payment: status = 'failed'
            if ($existingPayment) {
                $payment->update($existingPayment['id'], [
                    'transaction_code' => $callbackData['transaction_no'] ?? null, // Có thể null nếu thanh toán thất bại
                    'payment_date' => date('Y-m-d H:i:s'),
                    'status' => $paymentStatus // 'failed'
                ]);
            }

            // Cập nhật booking status thành 'cancelled' (đơn đặt vé bị hủy)
            $this->booking->updateStatus($bookingId, $bookingStatus);

            // ============================================
            // XÁC ĐỊNH THÔNG BÁO LỖI CỤ THỂ
            // ============================================
            $errorMessage = 'Thanh toán thất bại';

            // Kiểm tra mã lỗi cụ thể từ VNPay
            $responseCode = $callbackData['response_code'] ?? '';
            if (!$callbackData['is_valid']) {
                // Checksum không hợp lệ -> dữ liệu có thể bị giả mạo
                $errorMessage = 'Thông tin thanh toán không hợp lệ (Checksum không đúng)';
            } elseif ($responseCode === '24') {
                // User hủy giao dịch trên VNPay
                $errorMessage = 'Giao dịch bị hủy';
            } elseif ($responseCode === '07') {
                // Trừ tiền thành công nhưng giao dịch bị nghi ngờ (có thể do fraud detection)
                $errorMessage = 'Trừ tiền thành công nhưng giao dịch bị nghi ngờ';
            } elseif (!empty($responseCode) && $responseCode !== '00') {
                // Các mã lỗi khác (có thể do hết tiền, thẻ bị khóa, ...)
                $errorMessage = 'Thanh toán thất bại (Mã lỗi: ' . $responseCode . ')';
            }

            renderClient('client/payment-result.php', [
                'success' => false,
                'message' => $errorMessage,
                'booking_id' => $bookingId,
                'booking' => $booking,
                'debug_info' => [
                    'is_valid' => $callbackData['is_valid'],
                    'response_code' => $callbackData['response_code'],
                    'transaction_status' => $callbackData['transaction_status']
                ]
            ], 'Thanh toán thất bại');
        }
        exit;
    }

    // Cập nhật total_spending và tier_id của user sau khi thanh toán thành công (private helper)
    private function updateUserSpendingAndTier($userId, $amount)
    {
        try {
            require_once __DIR__ . '/../models/User.php';
            $userModel = new User();

            // Lấy thông tin user hiện tại
            $user = $userModel->find($userId);
            if (!$user) {
                return;
            }

            // Tính total_spending mới
            $currentSpending = floatval($user['total_spending'] ?? 0);
            $newSpending = $currentSpending + floatval($amount);

            // Lấy tier phù hợp dựa trên total_spending mới
            $tierId = $this->getTierBySpending($newSpending);

            // Cập nhật user
            $userModel->update($userId, [
                'total_spending' => $newSpending,
                'tier_id' => $tierId
            ]);
        } catch (Exception $e) {
            // Log lỗi nhưng không làm gián đoạn quá trình thanh toán
            error_log('Error updating user spending: ' . $e->getMessage());
        }
    }

    // Lấy tier_id phù hợp dựa trên total_spending (private helper)
    private function getTierBySpending($totalSpending)
    {
        try {
            $conn = connectDB();
            // Lấy tier cao nhất mà user đủ điều kiện
            $sql = "SELECT id FROM customer_tiers 
                    WHERE spending_min <= :spending 
                    AND (spending_max IS NULL OR spending_max >= :spending)
                    ORDER BY spending_min DESC 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':spending' => $totalSpending]);
            $tier = $stmt->fetch();

            return $tier ? $tier['id'] : null;
        } catch (Exception $e) {
            error_log('Error getting tier: ' . $e->getMessage());
            return null;
        }
    }

    // Tạo notification cho admin khi có booking mới (private helper)
    private function createBookingNotification($bookingId, $booking)
    {
        try {
            require_once __DIR__ . '/../models/Notification.php';
            $notificationModel = new Notification();

            // Kiểm tra xem đã có thông báo CHƯA ĐỌC cho booking này chưa (tránh duplicate)
            if ($notificationModel->existsForBooking($bookingId, null, 'Đơn đặt vé mới cần duyệt')) {
                error_log('Unread notification already exists for booking ID: ' . $bookingId . ', skipping...');
                return;
            }

            // Lấy thông tin phim và showtime
            $showtimeModel = new Showtime();
            $showtime = $showtimeModel->find($booking['showtime_id']);

            $movieModel = new Movie();
            $movie = $movieModel->find($showtime['movie_id'] ?? null);

            $movieTitle = $movie ? $movie['title'] : 'Phim';
            $bookingCode = $booking['booking_code'] ?? 'N/A';
            $finalAmount = number_format($booking['final_amount'] ?? 0, 0, ',', '.') . '₫';

            $notificationData = [
                'type' => 'booking',
                'title' => 'Đơn đặt vé mới cần duyệt',
                'message' => "Đơn đặt vé {$bookingCode} - {$movieTitle} - Tổng tiền: {$finalAmount}",
                'related_id' => $bookingId,
                'user_id' => null, // NULL = notification cho admin
                'is_read' => 0
            ];

            $result = $notificationModel->insert($notificationData);
            if (!$result) {
                error_log('Failed to create admin notification for booking ID: ' . $bookingId);
            } else {
                error_log('Created admin notification ID: ' . $result . ' for booking ID: ' . $bookingId);
            }
        } catch (Exception $e) {
            error_log('Error creating admin notification: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
        }
    }

    // Tạo notification cho user khi booking được duyệt hoặc hủy (private helper)
    private function createUserNotification($bookingId, $booking, $status)
    {
        try {
            require_once __DIR__ . '/../models/Notification.php';
            $notificationModel = new Notification();

            // Kiểm tra user_id có tồn tại không
            if (empty($booking['user_id'])) {
                error_log('Cannot create user notification: booking has no user_id. Booking ID: ' . $bookingId);
                return;
            }

            // Kiểm tra xem đã có thông báo CHƯA ĐỌC cho booking này chưa (tránh duplicate)
            $expectedTitle = ($status === 'paid') ? 'Đơn đặt vé đã được duyệt' : 'Đơn đặt vé bị hủy';
            if ($notificationModel->existsForBooking($bookingId, $booking['user_id'], $expectedTitle)) {
                error_log('Unread user notification already exists for booking ID: ' . $bookingId . ', skipping...');
                return;
            }

            // Lấy thông tin phim và showtime
            $showtimeModel = new Showtime();
            $showtime = $showtimeModel->find($booking['showtime_id']);

            $movieModel = new Movie();
            $movie = $movieModel->find($showtime['movie_id'] ?? null);

            $movieTitle = $movie ? $movie['title'] : 'Phim';
            $bookingCode = $booking['booking_code'] ?? 'N/A';

            if ($status === 'paid') {
                $title = 'Đơn đặt vé đã được duyệt';
                $message = "Đơn đặt vé {$bookingCode} - {$movieTitle} đã được duyệt thành công!";
            } else {
                $title = 'Đơn đặt vé bị hủy';
                $message = "Đơn đặt vé {$bookingCode} - {$movieTitle} đã bị hủy.";
            }

            $notificationData = [
                'type' => 'booking',
                'title' => $title,
                'message' => $message,
                'related_id' => $bookingId,
                'user_id' => $booking['user_id'], // Notification cho user cụ thể
                'is_read' => 0
            ];

            $result = $notificationModel->insert($notificationData);
            if (!$result) {
                error_log('Failed to create user notification for booking ID: ' . $bookingId . ', user ID: ' . $booking['user_id']);
            } else {
                error_log('Created user notification ID: ' . $result . ' for user ID: ' . $booking['user_id'] . ', booking ID: ' . $bookingId);
            }
        } catch (Exception $e) {
            error_log('Error creating user notification: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
