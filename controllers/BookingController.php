<?php

class BookingController
{
    public $booking;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Booking.php';
        require_once __DIR__ . '/../models/Seat.php';
        require_once __DIR__ . '/../models/Showtime.php';
        require_once __DIR__ . '/../models/Room.php';
        require_once __DIR__ . '/../models/Movie.php';
        require_once __DIR__ . '/../models/Cinema.php';
        $this->booking = new Booking();
    }

    /**
     * Hiển thị trang chọn ghế
     */
    public function selectSeats()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireLogin();

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

            $roomModel = new Room();
            $room = $roomModel->find($showtime['room_id']);
            
            if (!$room) {
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

            // Lấy danh sách ghế đã đặt cho suất chiếu này
            $bookedSeats = $this->booking->getBookedSeatsByShowtime($showtimeId);
            
            // Đảm bảo bookedSeats là array
            if (!is_array($bookedSeats)) {
                $bookedSeats = [];
            }

            // Thời gian đếm ngược (15 phút = 900 giây)
            $countdownSeconds = 900;

            renderClient('client/select_seats.php', [
                'showtime' => $showtime,
                'movie' => $movie,
                'room' => $room,
                'seatsByRow' => $seatsByRow,
                'bookedSeats' => $bookedSeats,
                'countdownSeconds' => $countdownSeconds
            ], 'Chọn ghế');
        } catch (Exception $e) {
            // Log lỗi và redirect
            error_log('Error in selectSeats: ' . $e->getMessage());
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }
    }

    /**
     * API lấy danh sách ghế (AJAX)
     */
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

            $roomModel = new Room();
            $room = $roomModel->find($showtime['room_id']);
            
            if (!$room) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy phòng chiếu']);
                exit;
            }

            $seatModel = new Seat();
            $seatsByRow = $seatModel->getSeatMapByRoom($room['id']);
            $bookedSeats = $this->booking->getBookedSeatsByShowtime($showtimeId);

            echo json_encode([
                'success' => true,
                'showtime' => $showtime,
                'room' => $room,
                'seatsByRow' => $seatsByRow,
                'bookedSeats' => $bookedSeats
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi tải dữ liệu ghế: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Hiển thị trang thanh toán
     */
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
        
        // Lấy giá từ showtime
        $adultPrice = $showtime['adult_price'] ?? 80000;
        $studentPrice = $showtime['student_price'] ?? 60000;
        $vipExtraPrice = 10000; // Phụ thu VIP

        // Lấy thông tin ghế đã chọn
        $seatModel = new Seat();
        $seatIdArray = explode(',', $seatIds);
        $selectedSeats = [];
        $totalPrice = 0;

        foreach ($seatIdArray as $index => $seatId) {
            $seat = $seatModel->find(trim($seatId));
            if ($seat) {
                // Xác định giá cơ bản: người lớn hay sinh viên
                // Số ghế đầu = người lớn, số ghế sau = sinh viên
                $basePrice = ($index < $adultCount) ? $adultPrice : $studentPrice;
                $customerType = ($index < $adultCount) ? 'adult' : 'student';
                
                // Tính giá: giá cơ bản + phụ thu VIP (nếu có)
                $price = $basePrice;
                if ($seat['seat_type'] === 'vip') {
                    $price += $vipExtraPrice;
                }
                
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
     * Xử lý thanh toán
     */
    public function processPayment()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireLogin();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit;
        }

        $showtimeId = $_POST['showtime_id'] ?? null;
        $seatIds = $_POST['seats'] ?? '';
        $seatLabels = $_POST['seat_labels'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';

        if (!$showtimeId || !$seatIds || !$paymentMethod) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin thanh toán']);
            exit;
        }

        try {
            $showtimeModel = new Showtime();
            $showtime = $showtimeModel->find($showtimeId);
            
            if (!$showtime) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy suất chiếu']);
                exit;
            }

            // Tính tổng tiền
            $seatModel = new Seat();
            $seatIdArray = explode(',', $seatIds);
            $totalPrice = 0;
            $seatPrice = 80000;
            $vipExtraPrice = 10000;

            foreach ($seatIdArray as $seatId) {
                $seat = $seatModel->find(trim($seatId));
                if ($seat) {
                    $price = $seatPrice;
                    if ($seat['seat_type'] === 'vip') {
                        $price += $vipExtraPrice;
                    }
                    $totalPrice += $price;
                }
            }

            // Tạo booking
            $bookingCode = 'BK' . time() . rand(1000, 9999);
            $bookingData = [
                'user_id' => $_SESSION['user_id'],
                'showtime_id' => $showtimeId,
                'room_id' => $showtime['room_id'],
                'cinema_id' => $showtime['cinema_id'] ?? null,
                'booked_seats' => $seatLabels,
                'total_amount' => $totalPrice,
                'discount_amount' => 0,
                'final_amount' => $totalPrice,
                'status' => 'pending',
                'booking_code' => $bookingCode
            ];

            $bookingId = $this->booking->insert($bookingData);

            if (!$bookingId) {
                echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn đặt vé']);
                exit;
            }

            // Xử lý thanh toán theo phương thức
            if ($paymentMethod === 'vnpay') {
                require_once __DIR__ . '/../commons/VNPay.php';
                $vnpay = new VNPay();
                
                $paymentUrl = $vnpay->createPaymentUrl([
                    'txn_ref' => $bookingId . '_' . time(),
                    'amount' => $totalPrice,
                    'order_info' => 'Thanh toan dat ve xem phim - ' . $bookingCode
                ]);

                echo json_encode([
                    'success' => true,
                    'payment_method' => 'vnpay',
                    'payment_url' => $paymentUrl
                ]);
            } else {
                // Các phương thức thanh toán khác (chưa implement)
                echo json_encode([
                    'success' => true,
                    'message' => 'Thanh toán thành công!',
                    'booking_id' => $bookingId
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Hiển thị danh sách đặt vé (Admin)
     */
    public function list()
    {
        // Lọc theo trạng thái và ngày nếu có
        $status = $_GET['status'] ?? null;
        $date = $_GET['date'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo page >= 1
        
        // Lấy dữ liệu phân trang
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
     * Xem chi tiết đặt vé (Admin)
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
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$id || !$status) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        try {
            $this->booking->updateStatus($id, $status);
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Hiển thị danh sách đặt vé của user
     */
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
     * Xử lý callback từ VNPay (Client)
     */
    public function vnpayReturn()
    {
        // Session đã được start ở index.php, không cần start lại
        require_once __DIR__ . '/../commons/VNPay.php';
        require_once __DIR__ . '/../models/Payment.php';

        // DEBUG: Xem dữ liệu từ VNPay (có thể xóa sau)
        // Uncomment dòng sau để xem chi tiết dữ liệu callback
        // echo '<pre>'; print_r($_GET); echo '</pre>'; die();

        $vnpay = new VNPay();
        $callbackData = $vnpay->processCallback();

        // Debug: Log dữ liệu callback (có thể xóa sau khi test xong)
        // error_log('VNPay Callback Data: ' . json_encode($callbackData));

        // Lấy booking_id từ txn_ref (format: bookingId_timestamp)
        $txnRef = $callbackData['txn_ref'] ?? '';
        $bookingId = null;
        if (!empty($txnRef)) {
            $parts = explode('_', $txnRef);
            $bookingId = $parts[0] ?? null;
        }

        if (!$bookingId) {
            // Không tìm thấy booking
            renderClient('client/payment-result.php', [
                'success' => false,
                'message' => 'Không tìm thấy thông tin đơn đặt vé',
                'booking_id' => null
            ], 'Kết quả thanh toán');
            exit;
        }

        // Lấy thông tin booking
        $booking = $this->booking->find($bookingId);
        if (!$booking) {
            renderClient('client/payment-result.php', [
                'success' => false,
                'message' => 'Đơn đặt vé không tồn tại',
                'booking_id' => null
            ], 'Kết quả thanh toán');
            exit;
        }

        // Kiểm tra thanh toán có thành công không
        $isSuccess = $vnpay->isPaymentSuccess($callbackData);

        // Cập nhật thông tin thanh toán
        $payment = new Payment();
        $existingPayment = $payment->findByBookingId($bookingId);

        if ($isSuccess) {
            // Thanh toán thành công
            $paymentStatus = 'paid';
            $bookingStatus = 'paid';

            // Cập nhật payment
            if ($existingPayment) {
                $payment->update($existingPayment['id'], [
                    'transaction_code' => $callbackData['transaction_no'],
                    'payment_date' => date('Y-m-d H:i:s'),
                    'status' => $paymentStatus
                ]);
            } else {
                $paymentData = [
                    'booking_id' => $bookingId,
                    'method' => 'vnpay',
                    'transaction_code' => $callbackData['transaction_no'],
                    'total_amount' => $callbackData['amount'],
                    'discount_amount' => 0,
                    'final_amount' => $callbackData['amount'],
                    'status' => $paymentStatus
                ];
                $payment->insert($paymentData);
            }

            // Cập nhật booking status
            $this->booking->updateStatus($bookingId, $bookingStatus);

            renderClient('client/payment-result.php', [
                'success' => true,
                'message' => 'Thanh toán thành công! Cảm ơn bạn đã đặt vé.',
                'booking_id' => $bookingId,
                'booking' => $booking,
                'transaction_code' => $callbackData['transaction_no']
            ], 'Thanh toán thành công');
        } else {
            // Thanh toán thất bại hoặc bị hủy
            $paymentStatus = 'failed';
            $bookingStatus = 'cancelled';

            // Cập nhật payment
            if ($existingPayment) {
                $payment->update($existingPayment['id'], [
                    'transaction_code' => $callbackData['transaction_no'] ?? null,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'status' => $paymentStatus
                ]);
            }

            // Cập nhật booking status
            $this->booking->updateStatus($bookingId, $bookingStatus);

            $errorMessage = 'Thanh toán thất bại';
            
            // Kiểm tra mã lỗi cụ thể
            $responseCode = $callbackData['response_code'] ?? '';
            if (!$callbackData['is_valid']) {
                $errorMessage = 'Thông tin thanh toán không hợp lệ (Checksum không đúng)';
            } elseif ($responseCode === '24') {
                $errorMessage = 'Giao dịch bị hủy';
            } elseif ($responseCode === '07') {
                $errorMessage = 'Trừ tiền thành công nhưng giao dịch bị nghi ngờ';
            } elseif (!empty($responseCode) && $responseCode !== '00') {
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
}