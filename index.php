<?php
/**
 * FILE CHÍNH - ĐIỂM VÀO CỦA ỨNG DỤNG
 * 
 * LUỒNG CHẠY:
 * 1. Khởi động session để quản lý đăng nhập
 * 2. Load các file cấu hình và helper functions
 * 3. Load tất cả Controllers và Models cần thiết
 * 4. Lấy tham số 'act' từ URL để xác định route
 * 5. Gọi method tương ứng trong Controller dựa trên route
 * 
 * CÁCH HOẠT ĐỘNG:
 * - User truy cập: ?act=trangchu -> gọi MoviesController->trangchu()
 * - User truy cập: ?act=dangnhap -> gọi AuthController->login()
 * - Admin truy cập: ?act=dashboard -> gọi DashboardController->index()
 */

// Start session ngay từ đầu, trước mọi output
// Session dùng để lưu thông tin đăng nhập (user_id, user_role, etc.)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bật hiển thị lỗi để debug (nên tắt khi deploy production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load file cấu hình môi trường (database, base URL, etc.)
require_once('./commons/env.php');
// Load các hàm helper (render, connectDB, debug, etc.)
require_once('./commons/function.php');

/**
 * LOAD TẤT CẢ CONTROLLERS VÀ MODELS
 * Controllers: Xử lý logic, nhận request từ user, gọi Models để lấy dữ liệu, render View
 * Models: Tương tác với database (SELECT, INSERT, UPDATE, DELETE)
 */

// Controllers và Models cho quản lý Phim
require_once('./controllers/MoviesController.php');
require_once('./models/Movie.php');
require_once('./models/Genre.php');

// Controller cho Dashboard (trang chủ admin)
require_once('./controllers/DashboardController.php');

// Controllers và Models cho quản lý Lịch chiếu
require_once('./controllers/ShowtimesController.php');
require_once('./models/Showtime.php');

// Controllers và Models cho quản lý Người dùng
require_once('./controllers/UsersController.php');
require_once('./models/User.php');

// Controllers và Models cho quản lý Phân quyền
require_once('./controllers/PermissionsController.php');
require_once('./models/Permission.php');

// Controller cho xác thực (đăng nhập, đăng ký, đăng xuất)
require_once('./controllers/AuthController.php');

// Controllers và Models cho quản lý Thể loại (chỉ Admin)
require_once('./controllers/GenresController.php');

// Controllers và Models cho quản lý Rạp
require_once('./controllers/CinemasController.php');
require_once('./models/Cinema.php');

// Controllers và Models cho quản lý Phòng chiếu
require_once('./controllers/RoomsController.php');
require_once('./models/Room.php');

// Controllers và Models cho quản lý Ghế
require_once('./controllers/SeatsController.php');
require_once('./models/Seat.php');

// Controllers và Models cho quản lý Bình luận
require_once('./controllers/CommentsController.php');
require_once('./models/Comment.php');

// Controllers và Models cho quản lý Đặt vé
require_once('./controllers/BookingController.php');
require_once('./models/Booking.php');
require_once('./models/Payment.php');

// Controller cho quản lý Profile người dùng
require_once('./controllers/ProfileController.php');

// Controllers và Models cho quản lý Khuyến mãi và Mã giảm giá
require_once('./models/DiscountCode.php');
require_once('./controllers/DiscountsController.php');
require_once('./controllers/TicketPriceController.php');
require_once('./models/TicketPrice.php');
require_once('./controllers/DiscountCodesController.php');

// Controllers và Models cho quản lý Thông báo
require_once('./controllers/NotificationController.php');
require_once('./models/Notification.php');

// Controllers và Models cho quản lý Liên hệ
require_once('./controllers/ContactsController.php');
require_once('./models/Contact.php');

// Controller cho quản lý Thống kê
require_once('./controllers/StatisticsController.php');

/**
 * ROUTING - XÁC ĐỊNH TRANG CẦN HIỂN THỊ
 * 
 * Lấy tham số 'act' từ URL (?act=trangchu)
 * Nếu không có 'act', mặc định là 'trangchu' (trang chủ client)
 */
$act = $_GET['act'] ?? 'trangchu';

/**
 * ROUTING TABLE - BẢNG ĐỊNH TUYẾN
 * 
 * Dựa vào giá trị $act, gọi method tương ứng trong Controller
 * Mỗi route sẽ:
 * 1. Tạo instance của Controller
 * 2. Gọi method tương ứng
 * 3. Method sẽ xử lý logic, lấy dữ liệu từ Model, render View
 */
match ($act) {

    // ============================================
    // CLIENT ROUTES - TRANG DÀNH CHO KHÁCH HÀNG
    // ============================================
    
    'trangchu' => (new MoviesController)->trangchu(), // Trang chủ - hiển thị danh sách phim đang chiếu và sắp chiếu
    'gioithieu' => (new MoviesController)->gioithieu(), // Trang giới thiệu về rạp
    'lichchieu' => (new MoviesController)->lichchieu(), // Trang lịch chiếu - xem lịch chiếu theo ngày, rạp, phim
    'khuyenmai' => (new MoviesController)->khuyenmai(), // Trang khuyến mãi - hiển thị các chương trình khuyến mãi
    'giave' => (new TicketPriceController)->index(), // Trang giá vé - hiển thị bảng giá vé theo loại
    'lienhe' => (new MoviesController)->lienhe(), // Trang liên hệ
    'check-voucher' => (new MoviesController)->checkVoucher(), // API kiểm tra mã voucher có hợp lệ không
    'movies' => (new MoviesController)->movieDetail(), // Trang chi tiết phim - thông tin phim, lịch chiếu, đánh giá
    'datve' => (new BookingController)->selectSeats(), // Trang chọn ghế - chọn ghế để đặt vé
    'api-seats' => (new BookingController)->getSeatsApi(), // API lấy danh sách ghế của suất chiếu (AJAX)
    'my-bookings' => (new BookingController)->myBookings(), // Trang lịch sử đặt vé của user
    'payment' => (new BookingController)->payment(), // Trang thanh toán - nhập thông tin thanh toán
    'payment-process' => (new BookingController)->processPayment(), // Xử lý thanh toán - tạo booking, gửi đến VNPay
    'vnpay-return' => (new BookingController)->vnpayReturn(), // Callback từ VNPay sau khi thanh toán xong
    

    // ============================================
    // AUTH ROUTES - XÁC THỰC NGƯỜI DÙNG
    // ============================================
    
    'dangky' => (new AuthController)->register(), // Trang đăng ký tài khoản mới
    'dangnhap' => (new AuthController)->login(), // Trang đăng nhập - kiểm tra email/password, tạo session
    'dangxuat' => (new AuthController)->logout(), // Đăng xuất - xóa session, chuyển về trang chủ

    // ============================================
    // PROFILE ROUTES - QUẢN LÝ HỒ SƠ
    // ============================================
    
    'profile' => (new ProfileController)->index(), // Trang hồ sơ cá nhân
    'profile-update' => (new ProfileController)->update(), // Cập nhật thông tin cá nhân
    'profile-change-password' => (new ProfileController)->changePassword(), // Đổi mật khẩu
    'review-movie' => (new ProfileController)->reviewMovie(), // Trang đánh giá phim (cũ)
    'submit-review' => (new ProfileController)->submitReview(), // Submit đánh giá phim (cũ)
    'submit-movie-review' => (new MoviesController)->submitMovieReview(), // Submit đánh giá phim từ trang chi tiết phim

    // ============================================
    // ADMIN ROUTES - TRANG QUẢN LÝ
    // ============================================
    
    'dashboard' => (new DashboardController)->index(), // Dashboard - trang chủ admin, hiển thị thống kê

    // ============================================
    // MOVIES ROUTES - QUẢN LÝ PHIM (Admin/Manager/Staff)
    // ============================================
    '/' => (new MoviesController)->list(), // Danh sách phim (route mặc định cho admin)
    'movies-list' => (new MoviesController)->list(), // Danh sách phim - lấy từ Model Movie, phân trang
    'movies-create' => (new MoviesController)->create(), // Form tạo phim mới - validate, upload ảnh, insert vào DB
    'movies-edit' => (new MoviesController)->edit(), // Form sửa phim - load dữ liệu từ DB, validate, update
    'movies-delete' => (new MoviesController)->delete(), // Xóa phim - xóa record trong database
    'movies-show' => (new MoviesController)->show(), // Xem chi tiết phim - load thông tin phim từ DB

    // ============================================
    // SHOWTIMES ROUTES - QUẢN LÝ LỊCH CHIẾU (Admin/Manager)
    // ============================================
    'showtimes' => (new ShowtimesController)->list(), // Danh sách lịch chiếu - lọc theo ngày, rạp, trạng thái
    'showtimes-create' => (new ShowtimesController)->create(), // Tạo lịch chiếu mới - chọn phim, phòng, ngày, giờ
    'showtimes-edit' => (new ShowtimesController)->edit(), // Sửa lịch chiếu - cập nhật thông tin suất chiếu
    'showtimes-delete' => (new ShowtimesController)->delete(), // Xóa lịch chiếu
    'showtimes-show' => (new ShowtimesController)->show(), // Xem chi tiết lịch chiếu

    // ============================================
    // USERS ROUTES - QUẢN LÝ NGƯỜI DÙNG (Admin)
    // ============================================
    'users' => (new UsersController)->list(), // Danh sách người dùng - phân trang, lọc theo role
    'users-create' => (new UsersController)->create(), // Tạo tài khoản mới (admin, manager, staff)
    'users-edit' => (new UsersController)->edit(), // Sửa thông tin người dùng
    'users-ban' => (new UsersController)->ban(), // Khóa tài khoản - set status = 'banned'
    'users-unban' => (new UsersController)->unban(), // Mở khóa tài khoản - set status = 'active'
    'users-show' => (new UsersController)->show(), // Xem chi tiết người dùng

    // ============================================
    // PERMISSIONS ROUTES - PHÂN QUYỀN (Admin)
    // ============================================
    'permissions' => (new PermissionsController)->list(), // Danh sách phân quyền - xem user nào có quyền gì
    'permissions-assign' => (new PermissionsController)->assign(), // Gán quyền cho user - thêm vào bảng permissions

    // ============================================
    // GENRES ROUTES - QUẢN LÝ THỂ LOẠI (Chỉ Admin)
    // ============================================
    'genres' => (new GenresController)->list(), // Danh sách thể loại phim
    'genres-create' => (new GenresController)->create(), // Tạo thể loại mới
    'genres-edit' => (new GenresController)->edit(), // Sửa thể loại
    'genres-delete' => (new GenresController)->delete(), // Xóa thể loại

    // ============================================
    // CINEMAS ROUTES - QUẢN LÝ RẠP (Admin/Manager)
    // ============================================
    'cinemas' => (new CinemasController)->list(), // Danh sách rạp chiếu phim
    'cinemas-create' => (new CinemasController)->create(), // Tạo rạp mới
    'cinemas-edit' => (new CinemasController)->edit(), // Sửa thông tin rạp
    'cinemas-delete' => (new CinemasController)->delete(), // Xóa rạp

    // ============================================
    // ROOMS ROUTES - QUẢN LÝ PHÒNG CHIẾU (Admin/Manager)
    // ============================================
    'rooms' => (new RoomsController)->list(), // Danh sách phòng chiếu - lọc theo rạp
    'rooms-create' => (new RoomsController)->create(), // Tạo phòng chiếu mới - chọn rạp, nhập tên, mã phòng
    'rooms-edit' => (new RoomsController)->edit(), // Sửa thông tin phòng
    'rooms-delete' => (new RoomsController)->delete(), // Xóa phòng
    'rooms-show' => (new RoomsController)->show(), // Xem chi tiết phòng - hiển thị sơ đồ ghế

    // ============================================
    // SEATS ROUTES - QUẢN LÝ GHẾ (Admin/Manager)
    // ============================================
    'seats' => (new SeatsController)->list(), // Danh sách ghế - lọc theo phòng
    'seats-create' => (new SeatsController)->create(), // Tạo ghế thủ công
    'seats-edit' => (new SeatsController)->edit(), // Sửa thông tin ghế (loại ghế, vị trí)
    'seats-delete' => (new SeatsController)->delete(), // Xóa ghế
    'seats-show' => (new SeatsController)->show(), // Xem chi tiết ghế
    'seats-seatmap' => (new SeatsController)->seatMap(), // Sơ đồ ghế - hiển thị layout phòng, trạng thái ghế
    'seats-generate' => (new SeatsController)->generateSeats(), // Tự động tạo ghế - nhập số hàng, số cột, tự tạo

    // ============================================
    // COMMENTS ROUTES - QUẢN LÝ BÌNH LUẬN (Admin)
    // ============================================
    'comments' => (new CommentsController)->list(), // Danh sách bình luận - lọc theo phim, tìm kiếm
    'comments-show' => (new CommentsController)->show(), // Xem chi tiết bình luận
    'comments-delete' => (new CommentsController)->delete(), // Xóa bình luận

    // ============================================
    // BOOKINGS ROUTES - QUẢN LÝ ĐẶT VÉ (Admin/Manager/Staff)
    // ============================================
    'bookings' => (new BookingController)->list(), // Danh sách đặt vé - lọc theo trạng thái, ngày
    'bookings-show' => (new BookingController)->show(), // Xem chi tiết đặt vé - thông tin vé, ghế, thanh toán
    'bookings-delete' => (new BookingController)->deleteBooking(), // Xóa đặt vé
    'bookings-update-status' => (new BookingController)->updateStatus(), // Cập nhật trạng thái đặt vé (pending, confirmed, paid, cancelled)
    'banve' => (new BookingController)->sellTicket(), // Trang bán vé cho staff - chọn suất chiếu, chuyển đến trang chọn ghế

    // ============================================
    // DISCOUNTS ROUTES - QUẢN LÝ KHUYẾN MÃI (Admin)
    // ============================================
    'discounts' => (new DiscountsController)->list(), // Danh sách chương trình khuyến mãi
    'discounts-create' => (new DiscountsController)->create(), // Tạo khuyến mãi mới
    'discounts-edit' => (new DiscountsController)->edit(), // Sửa khuyến mãi
    'discounts-delete' => (new DiscountsController)->delete(), // Xóa khuyến mãi
    
    // ============================================
    // TICKET PRICES ROUTES - QUẢN LÝ GIÁ VÉ (Admin)
    // ============================================
    'ticket-prices' => (new TicketPriceController)->list(), // Danh sách giá vé - theo ngày (weekday/weekend), loại (2D/3D), loại khách hàng, loại ghế
    'ticket-prices-edit' => (new TicketPriceController)->edit(), // Form sửa giá vé
    'ticket-prices-update' => (new TicketPriceController)->update(), // Cập nhật giá vé vào database

    // ============================================
    // DISCOUNT CODES ROUTES - QUẢN LÝ MÃ GIẢM GIÁ (Admin)
    // ============================================
    'discount-codes' => (new DiscountCodesController)->list(), // Danh sách mã giảm giá
    'discount-codes-create' => (new DiscountCodesController)->create(), // Tạo mã giảm giá mới - nhập mã, % giảm, số lần dùng
    'discount-codes-edit' => (new DiscountCodesController)->edit(), // Sửa mã giảm giá
    'discount-codes-delete' => (new DiscountCodesController)->delete(), // Xóa mã giảm giá

    // ============================================
    // CONTACTS ROUTES - QUẢN LÝ LIÊN HỆ (Admin)
    // ============================================
    'contacts' => (new ContactsController)->list(), // Danh sách liên hệ từ khách hàng
    'contacts-show' => (new ContactsController)->show(), // Xem chi tiết liên hệ
    'contacts-edit' => (new ContactsController)->edit(), // Sửa thông tin liên hệ
    'contacts-update-status' => (new ContactsController)->updateStatus(), // Cập nhật trạng thái (chưa xử lý, đã xử lý)
    'contacts-delete' => (new ContactsController)->delete(), // Xóa liên hệ

    // ============================================
    // STATISTICS ROUTES - THỐNG KÊ (Admin/Manager/Staff)
    // ============================================
    'thongke' => (new StatisticsController)->index(), // Trang thống kê - doanh thu, số vé, phim bán chạy
    'statistics' => (new StatisticsController)->index(), // Alias của thongke

    // ============================================
    // NOTIFICATIONS API ROUTES - THÔNG BÁO (Admin)
    // ============================================
    'api-notifications' => (new NotificationController)->getNotifications(), // API lấy danh sách thông báo (AJAX)
    'api-notifications-count' => (new NotificationController)->getUnreadCount(), // API lấy số thông báo chưa đọc
    'api-notifications-mark-read' => (new NotificationController)->markAsRead(), // API đánh dấu đã đọc 1 thông báo
    'api-notifications-mark-all-read' => (new NotificationController)->markAllAsRead(), // API đánh dấu tất cả đã đọc

    // ============================================
    // CLIENT NOTIFICATIONS API ROUTES - THÔNG BÁO KHÁCH HÀNG
    // ============================================
    'api-client-notifications' => (new NotificationController)->getNotifications(), // API lấy thông báo cho khách hàng
    'api-client-notifications-mark-read' => (new NotificationController)->markAsRead(), // Đánh dấu đã đọc
    'api-client-notifications-mark-all-read' => (new NotificationController)->markAllAsRead(), // Đánh dấu tất cả đã đọc

    // ============================================
    // DEFAULT - KHÔNG TÌM THẤY ROUTE
    // ============================================
    default => notFound(), // Hiển thị lỗi 404 nếu route không tồn tại
};
