<?php

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
require_once('./controllers/ProfileController.php');
require_once('./models/DiscountCode.php');
//route

$act = $_GET['act'] ?? 'trangchu';

match ($act) {

    // Client routes (public pages)
    'trangchu' => (new MoviesController)->trangchu(),
    'gioithieu' => (new MoviesController)->gioithieu(),
    'lichchieu' => (new MoviesController)->lichchieu(),
    'khuyenmai' => (new MoviesController)->khuyenmai(),
    'giave' => (new MovieController)->giave(),
    'movies' => (new MoviesController)->movieDetail(),
    'datve' => (new BookingController)->selectSeats(),
    'api-seats' => (new BookingController)->getSeatsApi(),
    'my-bookings' => (new BookingController)->myBookings(),
    'payment' => (new BookingController)->payment(),
    'payment-process' => (new BookingController)->processPayment(),

    // Auth routes (Client)
    'dangky' => (new AuthController)->register(),
    'dangnhap' => (new AuthController)->login(),
    'dangxuat' => (new AuthController)->logout(),

    // Profile routes (Client)
    'profile' => (new ProfileController)->index(),
    'profile-update' => (new ProfileController)->update(),
    'profile-change-password' => (new ProfileController)->changePassword(),

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

    // Bookings routes (Admin)
    'bookings' => (new BookingController)->list(),
    'bookings-show' => (new BookingController)->show(),
    'bookings-delete' => (new BookingController)->deleteBooking(),
    'bookings-update-status' => (new BookingController)->updateStatus(),

    default => notFound(),
};
