<?php
// Start session ngay từ đầu, trước mọi output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require_once file common
require_once('./commons/env.php');
require_once('./commons/function.php');

require_once('./controllers/MoviesController.php');
require_once('./models/Movie.php');
require_once('./models/Genre.php');
require_once('./controllers/DashboardController.php');
require_once('./controllers/ShowtimesController.php');
require_once('./models/Showtime.php');
require_once('./controllers/UsersController.php');
require_once('./models/User.php');
require_once('./controllers/PermissionsController.php');
require_once('./models/Permission.php');
require_once('./controllers/AuthController.php');
require_once('./controllers/GenresController.php');
require_once('./controllers/CinemasController.php');
require_once('./models/Cinema.php');
require_once('./controllers/RoomsController.php');
require_once('./models/Room.php');
require_once('./controllers/SeatsController.php');
require_once('./models/Seat.php');
require_once('./controllers/CommentsController.php');
require_once('./models/Comment.php');
require_once('./controllers/BookingController.php');
require_once('./models/Booking.php');
require_once('./models/Payment.php');
require_once('./controllers/ProfileController.php');
require_once('./models/DiscountCode.php');
require_once('./controllers/DiscountsController.php');
require_once('./controllers/TicketPriceController.php');
require_once('./models/TicketPrice.php');
require_once('./models/DiscountCode.php');
require_once('./controllers/DiscountCodesController.php');
require_once('./controllers/NotificationController.php');
require_once('./models/Notification.php');
require_once('./controllers/ContactsController.php');
require_once('./models/Contact.php');
require_once('./controllers/StatisticsController.php');
//route

$act = $_GET['act'] ?? 'trangchu';

match ($act) {

    // Client routes (public pages)
    'trangchu' => (new MoviesController)->trangchu(),
    'gioithieu' => (new MoviesController)->gioithieu(),
    'lichchieu' => (new MoviesController)->lichchieu(),
    'khuyenmai' => (new MoviesController)->khuyenmai(),
    'giave' => (new TicketPriceController)->index(),
    'khuyenmai' => (new MoviesController)->khuyenmai(),
    'lienhe' => (new MoviesController)->lienhe(),
    'check-voucher' => (new MoviesController)->checkVoucher(),
    'movies' => (new MoviesController)->movieDetail(),
    'datve' => (new BookingController)->selectSeats(),
    'api-seats' => (new BookingController)->getSeatsApi(),
    'my-bookings' => (new BookingController)->myBookings(),
    'payment' => (new BookingController)->payment(),
    'payment-process' => (new BookingController)->processPayment(),
    'vnpay-return' => (new BookingController)->vnpayReturn(),
    

    // Auth routes (Client)
    'dangky' => (new AuthController)->register(),
    'dangnhap' => (new AuthController)->login(),
    'dangxuat' => (new AuthController)->logout(),

    // Profile routes (Client)
    'profile' => (new ProfileController)->index(),
    'profile-update' => (new ProfileController)->update(),
    'profile-change-password' => (new ProfileController)->changePassword(),
    'review-movie' => (new ProfileController)->reviewMovie(),
    'submit-review' => (new ProfileController)->submitReview(),

    // Dashboard route
    'dashboard' => (new DashboardController)->index(),

    // Movies routes (Admin)
    '/' => (new MoviesController)->list(),
    'movies-list' => (new MoviesController)->list(),
    'movies-create' => (new MoviesController)->create(),
    'movies-edit' => (new MoviesController)->edit(),
    'movies-delete' => (new MoviesController)->delete(),
    'movies-show' => (new MoviesController)->show(),

    // Showtimes routes (Admin)
    'showtimes' => (new ShowtimesController)->list(),
    'showtimes-create' => (new ShowtimesController)->create(),
    'showtimes-edit' => (new ShowtimesController)->edit(),
    'showtimes-delete' => (new ShowtimesController)->delete(),
    'showtimes-show' => (new ShowtimesController)->show(),

    // Users routes (Admin)
    'users' => (new UsersController)->list(),
    'users-create' => (new UsersController)->create(),
    'users-edit' => (new UsersController)->edit(),
    'users-ban' => (new UsersController)->ban(),
    'users-unban' => (new UsersController)->unban(),
    'users-show' => (new UsersController)->show(),

    // Permissions routes (Admin)
    'permissions' => (new PermissionsController)->list(),
    'permissions-assign' => (new PermissionsController)->assign(),

    // Genres routes (Admin)
    'genres' => (new GenresController)->list(),
    'genres-create' => (new GenresController)->create(),
    'genres-edit' => (new GenresController)->edit(),
    'genres-delete' => (new GenresController)->delete(),

    // Cinemas routes (Admin)
    'cinemas' => (new CinemasController)->list(),
    'cinemas-create' => (new CinemasController)->create(),
    'cinemas-edit' => (new CinemasController)->edit(),
    'cinemas-delete' => (new CinemasController)->delete(),

    // Rooms routes (Admin)
    'rooms' => (new RoomsController)->list(),
    'rooms-create' => (new RoomsController)->create(),
    'rooms-edit' => (new RoomsController)->edit(),
    'rooms-delete' => (new RoomsController)->delete(),
    'rooms-show' => (new RoomsController)->show(),

    // Seats routes (Admin)
    'seats' => (new SeatsController)->list(),
    'seats-create' => (new SeatsController)->create(),
    'seats-edit' => (new SeatsController)->edit(),
    'seats-delete' => (new SeatsController)->delete(),
    'seats-show' => (new SeatsController)->show(),
    'seats-seatmap' => (new SeatsController)->seatMap(),
    'seats-generate' => (new SeatsController)->generateSeats(),

    // Comments routes (Admin)
    'comments' => (new CommentsController)->list(),
    'comments-show' => (new CommentsController)->show(),
    'comments-delete' => (new CommentsController)->delete(),

    // Bookings routes (Admin/Manager/Staff)
    'bookings' => (new BookingController)->list(),
    'bookings-show' => (new BookingController)->show(),
    'bookings-delete' => (new BookingController)->deleteBooking(),
    'bookings-update-status' => (new BookingController)->updateStatus(),
    'banve' => (new BookingController)->sellTicket(),

    // Discounts routes (Admin)
    'discounts' => (new DiscountsController)->list(),
    'discounts-create' => (new DiscountsController)->create(),
    'discounts-edit' => (new DiscountsController)->edit(),
    'discounts-delete' => (new DiscountsController)->delete(),
    // Ticket Prices routes (Admin)
    'ticket-prices' => (new TicketPriceController)->list(),
    'ticket-prices-edit' => (new TicketPriceController)->edit(),
    'ticket-prices-update' => (new TicketPriceController)->update(),

    // Discount Codes routes (Admin)
    'discounts' => (new DiscountCodesController)->list(),
    'discounts-create' => (new DiscountCodesController)->create(),
    'discounts-edit' => (new DiscountCodesController)->edit(),
    'discounts-delete' => (new DiscountCodesController)->delete(),

    // Contacts routes (Admin)
    'contacts' => (new ContactsController)->list(),
    'contacts-show' => (new ContactsController)->show(),
    'contacts-edit' => (new ContactsController)->edit(),
    'contacts-update-status' => (new ContactsController)->updateStatus(),
    'contacts-delete' => (new ContactsController)->delete(),

    // Statistics routes (Admin)
    'thongke' => (new StatisticsController)->index(),

    // Notifications API routes (Admin)
    'api-notifications' => (new NotificationController)->getNotifications(),
    'api-notifications-count' => (new NotificationController)->getUnreadCount(),
    'api-notifications-mark-read' => (new NotificationController)->markAsRead(),
    'api-notifications-mark-all-read' => (new NotificationController)->markAllAsRead(),

    // Client Notifications API routes
    'api-client-notifications' => (new NotificationController)->getNotifications(),
    'api-client-notifications-mark-read' => (new NotificationController)->markAsRead(),
    'api-client-notifications-mark-all-read' => (new NotificationController)->markAllAsRead(),

    default => notFound(),
};
