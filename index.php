<?php
// INDEX.PHP - File chính, điểm vào của ứng dụng
// Luồng: Request -> Load config/models/controllers -> Routing -> Controller xử lý -> Render View

// Khởi động session để quản lý đăng nhập (user_id, user_role, etc.)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bật error reporting để debug (tắt display_errors cho API endpoints để tránh output trước JSON)
$isApiEndpoint = isset($_GET['act']) && in_array($_GET['act'], ['payment-process', 'api-seats', 'check-voucher', 'update-booking-status', 'get-available-vouchers']);
if (!$isApiEndpoint) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    while (ob_get_level() > 0) ob_end_clean(); // Xóa output buffer cũ
    ob_start(); // Bắt đầu output buffering cho API
}
error_reporting(E_ALL);

// Load cấu hình: database, BASE_URL, helper functions
require_once('./commons/env.php');
require_once('./commons/function.php');

// ============================================
// LOAD CONTROLLERS VÀ MODELS
// Controllers: Xử lý logic, routing, validate
// Models: Tương tác database (CRUD)
// ============================================

// Core Controllers - Controllers chính của hệ thống
require_once('./controllers/MoviesController.php');
require_once('./models/Movie.php');
require_once('./models/Genre.php');
require_once('./controllers/DashboardController.php');
require_once('./controllers/AuthController.php');

// User Management - Quản lý người dùng, phân quyền, hồ sơ
require_once('./controllers/UsersController.php');
require_once('./models/User.php');
require_once('./controllers/PermissionsController.php');
require_once('./models/Permission.php');
require_once('./controllers/ProfileController.php');

// Cinema Management - Quản lý rạp, phòng, ghế, lịch chiếu
require_once('./controllers/CinemasController.php');
require_once('./models/Cinema.php');
require_once('./controllers/RoomsController.php');
require_once('./models/Room.php');
require_once('./controllers/SeatsController.php');
require_once('./models/Seat.php');
require_once('./controllers/ShowtimesController.php');
require_once('./models/Showtime.php');

// Content Management - Quản lý nội dung (phim, thể loại, bình luận)
require_once('./controllers/GenresController.php');
require_once('./controllers/CommentsController.php');
require_once('./models/Comment.php');

// Booking & Payment - Đặt vé, thanh toán
require_once('./controllers/BookingController.php');
require_once('./models/Booking.php');
require_once('./models/Payment.php');

// Discount & Pricing - Khuyến mãi, mã giảm giá, giá vé
require_once('./controllers/DiscountsController.php');
require_once('./controllers/DiscountCodesController.php');
require_once('./models/DiscountCode.php');
require_once('./controllers/TicketPriceController.php');
require_once('./models/TicketPrice.php');

// Communication - Liên hệ, thông báo
require_once('./controllers/ContactsController.php');
require_once('./models/Contact.php');
require_once('./controllers/NotificationController.php');
require_once('./models/Notification.php');

// Statistics - Thống kê
require_once('./controllers/StatisticsController.php');

// Routing: Lấy 'act' từ URL (?act=trangchu), mặc định là 'trangchu'
$act = $_GET['act'] ?? 'trangchu';

// Bảng định tuyến: Match $act với Controller và method tương ứng
match ($act) {
    // CLIENT ROUTES - Trang dành cho khách hàng
    'trangchu' => (new MoviesController)->trangchu(), // Trang chủ - danh sách phim đang chiếu và sắp chiếu
    'gioithieu' => (new MoviesController)->gioithieu(), // Trang giới thiệu về rạp
    'lichchieu' => (new MoviesController)->lichchieu(), // Trang lịch chiếu - xem lịch chiếu theo ngày, rạp, phim
    'khuyenmai' => (new MoviesController)->khuyenmai(), // Trang khuyến mãi - hiển thị các chương trình khuyến mãi
    'giave' => (new TicketPriceController)->index(), // Trang giá vé - hiển thị bảng giá vé theo loại
    'lienhe' => (new MoviesController)->lienhe(), // Trang liên hệ
    'check-voucher' => (new MoviesController)->checkVoucher(), // API kiểm tra mã voucher có hợp lệ không
    'get-available-vouchers' => (new MoviesController)->getAvailableVouchers(), // API lấy danh sách mã khuyến mãi available
    'movies' => (new MoviesController)->movieDetail(), // Trang chi tiết phim - thông tin phim, lịch chiếu, đánh giá
    'datve' => (new BookingController)->selectSeats(), // Trang chọn ghế - chọn ghế để đặt vé
    'api-seats' => (new BookingController)->getSeatsApi(), // API lấy danh sách ghế của suất chiếu (AJAX)
    'my-bookings' => (new BookingController)->myBookings(), // Trang lịch sử đặt vé của user
    'payment' => (new BookingController)->payment(), // Trang thanh toán - nhập thông tin thanh toán
    'payment-process' => (new BookingController)->processPayment(), // Xử lý thanh toán - tạo booking, gửi đến VNPay
    'vnpay-return' => (new BookingController)->vnpayReturn(), // Callback từ VNPay sau khi thanh toán

    // AUTH ROUTES - Xác thực người dùng
    'dangky' => (new AuthController)->register(), // Trang đăng ký tài khoản mới
    'dangnhap' => (new AuthController)->login(), // Trang đăng nhập - kiểm tra email/password, tạo session
    'dangxuat' => (new AuthController)->logout(), // Đăng xuất - xóa session, chuyển về trang chủ
    'quenmatkhau' => (new AuthController)->forgotPassword(), // Trang quên mật khẩu

    // PROFILE ROUTES - Quản lý hồ sơ
    'profile' => (new ProfileController)->index(), // Trang hồ sơ cá nhân
    'profile-update' => (new ProfileController)->update(), // Cập nhật thông tin cá nhân
    'profile-change-password' => (new ProfileController)->changePassword(), // Đổi mật khẩu
    'review-movie' => (new ProfileController)->reviewMovie(), // Trang đánh giá phim (cũ)
    'submit-review' => (new ProfileController)->submitReview(), // Submit đánh giá phim (cũ)
    'submit-movie-review' => (new MoviesController)->submitMovieReview(), // Submit đánh giá phim

    // ADMIN ROUTES - Trang quản lý
    'dashboard' => (new DashboardController)->index(), // Dashboard - trang chủ admin

    // MOVIES ROUTES - Quản lý phim (Admin/Manager/Staff)
    '/' => (new MoviesController)->list(), // Danh sách phim (route mặc định cho admin)
    'movies-list' => (new MoviesController)->list(), // Danh sách phim - lấy từ Model Movie, phân trang
    'movies-create' => (new MoviesController)->create(), // Form tạo phim mới - validate, upload ảnh, insert vào DB
    'movies-edit' => (new MoviesController)->edit(), // Form sửa phim - load dữ liệu từ DB, validate, update
    'movies-delete' => (new MoviesController)->delete(), // Xóa phim - xóa record trong database
    'movies-show' => (new MoviesController)->show(), // Xem chi tiết phim

    // SHOWTIMES ROUTES - Quản lý lịch chiếu (Admin/Manager)
    'showtimes' => (new ShowtimesController)->list(), // Danh sách lịch chiếu
    'showtimes-create' => (new ShowtimesController)->create(), // Tạo lịch chiếu mới - chọn phim, phòng, ngày, giờ
    'showtimes-edit' => (new ShowtimesController)->edit(), // Sửa lịch chiếu - cập nhật thông tin suất chiếu
    'showtimes-delete' => (new ShowtimesController)->delete(), // Xóa lịch chiếu
    'showtimes-show' => (new ShowtimesController)->show(), // Xem chi tiết lịch chiếu

    // USERS ROUTES - Quản lý người dùng (Admin)
    'users' => (new UsersController)->list(), // Danh sách người dùng
    'users-create' => (new UsersController)->create(), // Tạo tài khoản mới (admin, manager, staff)
    'users-edit' => (new UsersController)->edit(), // Sửa thông tin người dùng
    'users-ban' => (new UsersController)->ban(), // Khóa tài khoản - set status = 'banned'
    'users-unban' => (new UsersController)->unban(), // Mở khóa tài khoản - set status = 'active'
    'users-show' => (new UsersController)->show(), // Xem chi tiết người dùng

    // PERMISSIONS ROUTES - Phân quyền (Admin)
    'permissions' => (new PermissionsController)->list(), // Danh sách phân quyền
    'permissions-assign' => (new PermissionsController)->assign(), // Gán quyền cho user

    // GENRES ROUTES - Quản lý thể loại (Chỉ Admin)
    'genres' => (new GenresController)->list(), // Danh sách thể loại phim
    'genres-create' => (new GenresController)->create(), // Tạo thể loại mới
    'genres-edit' => (new GenresController)->edit(), // Sửa thể loại
    'genres-delete' => (new GenresController)->delete(), // Xóa thể loại

    // CINEMAS ROUTES - Quản lý rạp (Admin/Manager)
    'cinemas' => (new CinemasController)->list(), // Danh sách rạp chiếu phim
    'cinemas-create' => (new CinemasController)->create(), // Tạo rạp mới
    'cinemas-edit' => (new CinemasController)->edit(), // Sửa thông tin rạp
    'cinemas-delete' => (new CinemasController)->delete(), // Xóa rạp

    // ROOMS ROUTES - Quản lý phòng chiếu (Admin/Manager)
    'rooms' => (new RoomsController)->list(), // Danh sách phòng chiếu
    'rooms-create' => (new RoomsController)->create(), // Tạo phòng chiếu mới - chọn rạp, nhập tên, mã phòng
    'rooms-edit' => (new RoomsController)->edit(), // Sửa thông tin phòng
    'rooms-delete' => (new RoomsController)->delete(), // Xóa phòng
    'rooms-show' => (new RoomsController)->show(), // Xem chi tiết phòng - sơ đồ ghế

    // SEATS ROUTES - Quản lý ghế (Admin/Manager)
    'seats' => (new SeatsController)->list(), // Danh sách ghế
    'seats-create' => (new SeatsController)->create(), // Tạo ghế thủ công
    'seats-edit' => (new SeatsController)->edit(), // Sửa thông tin ghế (loại ghế, vị trí)
    'seats-delete' => (new SeatsController)->delete(), // Xóa ghế
    'seats-show' => (new SeatsController)->show(), // Xem chi tiết ghế
    'seats-seatmap' => (new SeatsController)->seatMap(), // Sơ đồ ghế - hiển thị layout phòng, trạng thái ghế
    'seats-generate' => (new SeatsController)->generateSeats(), // Tự động tạo ghế

    // COMMENTS ROUTES - Quản lý bình luận (Admin)
    'comments' => (new CommentsController)->list(), // Danh sách bình luận
    'comments-show' => (new CommentsController)->show(), // Xem chi tiết bình luận
    'comments-delete' => (new CommentsController)->delete(), // Xóa bình luận

    // BOOKINGS ROUTES - Quản lý đặt vé (Admin/Manager/Staff)
    'bookings' => (new BookingController)->list(), // Danh sách đặt vé
    'bookings-show' => (new BookingController)->show(), // Xem chi tiết đặt vé - thông tin vé, ghế, thanh toán
    'bookings-delete' => (new BookingController)->deleteBooking(), // Xóa đặt vé
    'bookings-update-status' => (new BookingController)->updateStatus(), // Cập nhật trạng thái đặt vé

    // DISCOUNTS ROUTES - Quản lý khuyến mãi (Admin)
    'discounts' => (new DiscountsController)->list(), // Danh sách chương trình khuyến mãi
    'discounts-create' => (new DiscountsController)->create(), // Tạo khuyến mãi mới
    'discounts-edit' => (new DiscountsController)->edit(), // Sửa khuyến mãi
    'discounts-delete' => (new DiscountsController)->delete(), // Xóa khuyến mãi

    // TICKET PRICES ROUTES - Quản lý giá vé (Admin)
    'ticket-prices' => (new TicketPriceController)->list(), // Danh sách giá vé
    'ticket-prices-edit' => (new TicketPriceController)->edit(), // Form sửa giá vé
    'ticket-prices-update' => (new TicketPriceController)->update(), // Cập nhật giá vé

    // DISCOUNT CODES ROUTES - Quản lý mã giảm giá (Admin)
    'discount-codes' => (new DiscountCodesController)->list(), // Danh sách mã giảm giá
    'discount-codes-create' => (new DiscountCodesController)->create(), // Tạo mã giảm giá mới - nhập mã, % giảm, số lần dùng
    'discount-codes-edit' => (new DiscountCodesController)->edit(), // Sửa mã giảm giá
    'discount-codes-delete' => (new DiscountCodesController)->delete(), // Xóa mã giảm giá

    // CONTACTS ROUTES - Quản lý liên hệ (Admin)
    'contacts' => (new ContactsController)->list(), // Danh sách liên hệ từ khách hàng
    'contacts-show' => (new ContactsController)->show(), // Xem chi tiết liên hệ
    'contacts-edit' => (new ContactsController)->edit(), // Sửa thông tin liên hệ
    'contacts-update-status' => (new ContactsController)->updateStatus(), // Cập nhật trạng thái (chưa xử lý, đã xử lý)
    'contacts-delete' => (new ContactsController)->delete(), // Xóa liên hệ

    // STATISTICS ROUTES - Thống kê (Admin/Manager/Staff)
    'thongke' => (new StatisticsController)->index(), // Trang thống kê
    'statistics' => (new StatisticsController)->index(), // Alias của thongke

    // NOTIFICATIONS API ROUTES - Thông báo (Admin)
    'api-notifications' => (new NotificationController)->getNotifications(), // API lấy danh sách thông báo (AJAX)
    'api-notifications-count' => (new NotificationController)->getUnreadCount(), // API lấy số thông báo chưa đọc
    'api-notifications-mark-read' => (new NotificationController)->markAsRead(), // API đánh dấu đã đọc 1 thông báo
    'api-notifications-mark-all-read' => (new NotificationController)->markAllAsRead(), // API đánh dấu tất cả đã đọc

    // CLIENT NOTIFICATIONS API ROUTES - Thông báo khách hàng
    'api-client-notifications' => (new NotificationController)->getNotifications(), // API lấy thông báo cho khách hàng
    'api-client-notifications-mark-read' => (new NotificationController)->markAsRead(), // Đánh dấu đã đọc
    'api-client-notifications-mark-all-read' => (new NotificationController)->markAllAsRead(), // Đánh dấu tất cả đã đọc

    // DEFAULT - Không tìm thấy route
    default => notFound(), // Hiển thị lỗi 404 nếu route không tồn tại
};
