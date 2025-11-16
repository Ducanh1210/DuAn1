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
//route

$act = $_GET['act'] ?? 'trangchu';

match ($act) {

    // Client routes (public pages)
    'trangchu' => (new MoviesController)->trangchu(),
    'lichchieu' => (new MoviesController)->lichchieu(),
    'movies' => (new MoviesController)->movieDetail(),
    
    // Auth routes (Client)
    'dangky' => (new AuthController)->register(),
    'dangnhap' => (new AuthController)->login(),
    'dangxuat' => (new AuthController)->logout(),

    // Dashboard route
    'dashboard' => (new DashboardController)->index(),

    // Movies routes (Admin)
    '/' => (new MoviesController)->list(),
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
    'users-delete' => (new UsersController)->delete(),
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

    default => notFound(),
}

?>