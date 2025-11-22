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
        require_once __DIR__ . '/../models/DiscountCode.php';
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

            // Lấy giá vé từ ticket_prices
            require_once __DIR__ . '/../models/TicketPrice.php';
            $ticketPriceModel = new TicketPrice();
            
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

            // Tính tổng tiền sử dụng giá vé từ database
            require_once __DIR__ . '/../models/TicketPrice.php';
            $ticketPriceModel = new TicketPrice();
            
            $seatModel = new Seat();
            $seatIdArray = explode(',', $seatIds);
            $totalPrice = 0;

            // Lấy số lượng người lớn và sinh viên
            $adultCount = isset($_POST['adult_count']) ? (int)$_POST['adult_count'] : 0;
            $studentCount = isset($_POST['student_count']) ? (int)$_POST['student_count'] : 0;

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
                $discountCodeModel = new DiscountCode();
                $discountCode = $discountCodeModel->validateDiscountCode($voucherCode, $totalPrice);
                
                if ($discountCode && $discountCode['discount_percent'] > 0) {
                    $discountId = $discountCode['id'];
                    $discountAmount = $discountCode['discount_amount'];
                }
            }

            $finalAmount = $totalPrice - $discountAmount;

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
                echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn đặt vé']);
                exit;
            }

            // Xử lý thanh toán theo phương thức
            if ($paymentMethod === 'vnpay') {
                require_once __DIR__ . '/../commons/VNPay.php';
                $vnpay = new VNPay();
                
                // Sử dụng finalAmount (sau khi giảm giá) thay vì totalPrice
                $paymentUrl = $vnpay->createPaymentUrl([
                    'txn_ref' => $bookingId . '_' . time(),
                    'amount' => $finalAmount, // Dùng finalAmount (đã trừ discount) thay vì totalPrice
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
            // Thanh toán thành công - cập nhật status thành 'paid' ngay lập tức
            $paymentStatus = 'paid';
            $bookingStatus = 'paid'; // Thanh toán thành công ngay lập tức

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
                    'discount_amount' => $booking['discount_amount'] ?? 0,
                    'final_amount' => $booking['final_amount'] ?? $callbackData['amount'],
                    'status' => $paymentStatus
                ];
                $payment->insert($paymentData);
            }

            // Cập nhật booking status thành 'paid'
            $this->booking->updateStatus($bookingId, $bookingStatus);

            // Cập nhật total_spending và tier_id của user ngay sau khi thanh toán thành công
            $this->updateUserSpendingAndTier($booking['user_id'], $booking['final_amount']);

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

    /**
     * Cập nhật total_spending và tier_id của user sau khi thanh toán thành công
     */
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

    /**
     * Lấy tier_id phù hợp dựa trên total_spending
     */
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

    /**
     * Tạo notification cho admin khi có booking mới
     */
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

    /**
     * Tạo notification cho user khi booking được duyệt hoặc hủy
     */
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