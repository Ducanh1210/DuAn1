<?php

class ShowtimesController
{
    public $showtime;
    public $movie;
    public $room;

    public function __construct()
    {
        $this->showtime = new Showtime();
        $this->movie = new Movie();
    }

    /**
     * Hiển thị danh sách lịch chiếu (Admin/Staff)
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        // Lọc theo ngày nếu có
        $date = $_GET['date'] ?? null;
        // Lọc theo trạng thái nếu có
        $status = $_GET['status'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo page >= 1
        
        // Nếu là manager hoặc staff, chỉ lấy lịch chiếu của rạp mình quản lý
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $result = $this->showtime->paginateByCinema($cinemaId, $page, 5, $date, $status);
            } else {
                $result = ['data' => [], 'page' => 1, 'totalPages' => 0, 'total' => 0, 'perPage' => 5];
            }
        } else {
            $result = $this->showtime->paginate($page, 5, $date, $status);
        }
        
        // Lấy danh sách phim và phòng cho filter
        $movies = $this->movie->all();
        $rooms = $this->getRooms();
        
        render('admin/showtimes/list.php', [
            'data' => $result['data'],
            'movies' => $movies,
            'rooms' => $rooms,
            'selectedDate' => $date,
            'selectedStatus' => $status,
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ]
        ]);
    }

    /**
     * Hiển thị form tạo lịch chiếu mới (Admin/Manager)
     */
    public function create()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager();
        
        $errors = [];
        $movies = $this->movie->all();
        $cinemas = $this->getCinemas();
        $rooms = $this->getRooms();
        
        // Lấy cinema_id của manager nếu là manager
        $managerCinemaId = null;
        $managerCinemaName = null;
        if (isManager()) {
            $managerCinemaId = getCurrentCinemaId();
            if ($managerCinemaId) {
                require_once __DIR__ . '/../models/Cinema.php';
                $cinemaModel = new Cinema();
                $cinema = $cinemaModel->find($managerCinemaId);
                $managerCinemaName = $cinema['name'] ?? null;
            }
        }

        if (!empty($_POST)) {
            $data = [
                'movie_id' => $_POST['movie_id'] ?? '',
                'room_id' => $_POST['room_id'] ?? '',
                'show_date' => $_POST['show_date'] ?? '',
                'start_time' => $_POST['start_time'] ?? '',
                'end_time' => $_POST['end_time'] ?? '',
                'format' => $_POST['format'] ?? '2D'
            ];

            // Validate
            $validationErrors = $this->showtime->validate($data);
            $errors = array_merge($errors, $validationErrors);

            // Manager chỉ được tạo lịch chiếu cho phòng thuộc rạp mình quản lý
            if (isManager()) {
                $cinemaId = getCurrentCinemaId();
                if ($cinemaId) {
                    require_once __DIR__ . '/../models/Room.php';
                    $roomModel = new Room();
                    $room = $roomModel->find($data['room_id']);
                    if (!$room || $room['cinema_id'] != $cinemaId) {
                        $errors['room_id'] = "Bạn chỉ được tạo lịch chiếu cho phòng thuộc rạp của mình";
                    }
                } else {
                    $errors['general'] = "Bạn chưa được gán cho rạp nào. Vui lòng liên hệ quản trị viên.";
                }
            }
            
            if (empty($errors)) {
                $this->showtime->insert($data);
                header('Location: ' . BASE_URL . '?act=showtimes');
                exit;
            }
        }

        render('admin/showtimes/create.php', [
            'errors' => $errors,
            'movies' => $movies,
            'cinemas' => $cinemas,
            'rooms' => $rooms,
            'managerCinemaId' => $managerCinemaId,
            'managerCinemaName' => $managerCinemaName
        ]);
    }

    /**
     * Hiển thị form sửa lịch chiếu (Admin/Manager)
     */
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=showtimes');
            exit;
        }

        $showtime = $this->showtime->find($id);
        if (!$showtime) {
            header('Location: ' . BASE_URL . '?act=showtimes');
            exit;
        }
        
        // Manager chỉ được sửa lịch chiếu của rạp mình quản lý
        if (isManager()) {
            require_once __DIR__ . '/../models/Room.php';
            $roomModel = new Room();
            $room = $roomModel->find($showtime['room_id']);
            if (!$room || !canAccessCinema($room['cinema_id'])) {
                header('Location: ' . BASE_URL . '?act=showtimes');
                exit;
            }
        }

        $errors = [];
        $movies = $this->movie->all();
        $cinemas = $this->getCinemas();
        $rooms = $this->getRooms();
        
        // Lấy cinema_id của manager nếu là manager
        $managerCinemaId = null;
        $managerCinemaName = null;
        if (isManager()) {
            $managerCinemaId = getCurrentCinemaId();
            if ($managerCinemaId) {
                require_once __DIR__ . '/../models/Cinema.php';
                $cinemaModel = new Cinema();
                $cinema = $cinemaModel->find($managerCinemaId);
                $managerCinemaName = $cinema['name'] ?? null;
            }
        }
        
        // Lấy cinema_id của room hiện tại để set selected
        $currentCinemaId = null;
        if (!empty($showtime['room_id'])) {
            foreach ($rooms as $room) {
                if ($room['id'] == $showtime['room_id']) {
                    $currentCinemaId = $room['cinema_id'];
                    break;
                }
            }
        }

        if (!empty($_POST)) {
            $data = [
                'id' => $id,
                'movie_id' => $_POST['movie_id'] ?? '',
                'room_id' => $_POST['room_id'] ?? '',
                'show_date' => $_POST['show_date'] ?? '',
                'start_time' => $_POST['start_time'] ?? '',
                'end_time' => $_POST['end_time'] ?? '',
                'format' => $_POST['format'] ?? '2D'
            ];

            // Validate
            $validationErrors = $this->showtime->validate($data, true);
            $errors = array_merge($errors, $validationErrors);
            
            // Manager chỉ được sửa lịch chiếu cho phòng thuộc rạp mình quản lý
            if (isManager()) {
                $cinemaId = getCurrentCinemaId();
                if ($cinemaId) {
                    require_once __DIR__ . '/../models/Room.php';
                    $roomModel = new Room();
                    $room = $roomModel->find($data['room_id']);
                    if (!$room || $room['cinema_id'] != $cinemaId) {
                        $errors['room_id'] = "Bạn chỉ được sửa lịch chiếu cho phòng thuộc rạp của mình";
                    }
                }
            }

            if (empty($errors)) {
                $this->showtime->update($id, $data);
                header('Location: ' . BASE_URL . '?act=showtimes');
                exit;
            }
        }

        render('admin/showtimes/edit.php', [
            'showtime' => $showtime,
            'errors' => $errors,
            'movies' => $movies,
            'cinemas' => $cinemas,
            'rooms' => $rooms,
            'currentCinemaId' => $currentCinemaId,
            'managerCinemaId' => $managerCinemaId,
            'managerCinemaName' => $managerCinemaName
        ]);
    }

    /**
     * Xóa lịch chiếu (Admin)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=showtimes');
            exit;
        }

        $this->showtime->delete($id);
        header('Location: ' . BASE_URL . '?act=showtimes');
        exit;
    }

    /**
     * Xem chi tiết lịch chiếu (Admin)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=showtimes');
            exit;
        }

        $showtime = $this->showtime->find($id);
        if (!$showtime) {
            header('Location: ' . BASE_URL . '?act=showtimes');
            exit;
        }

        render('admin/showtimes/show.php', ['showtime' => $showtime]);
    }

    /**
     * Lấy danh sách rạp
     */
    private function getCinemas()
    {
        require_once __DIR__ . '/../commons/auth.php';
        try {
            $conn = connectDB();
            
            // Nếu là staff, chỉ lấy rạp của mình
            if (isStaff()) {
                $cinemaId = getCurrentCinemaId();
                if ($cinemaId) {
                    $sql = "SELECT * FROM cinemas WHERE id = :cinema_id ORDER BY name ASC";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':cinema_id' => $cinemaId]);
                    return $stmt->fetchAll();
                } else {
                    return [];
                }
            } else {
                $sql = "SELECT * FROM cinemas ORDER BY name ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll();
            }
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy danh sách phòng
     */
    private function getRooms()
    {
        require_once __DIR__ . '/../commons/auth.php';
        try {
            $conn = connectDB();
            
            // Nếu là manager hoặc staff, chỉ lấy phòng của rạp mình quản lý
            if (isManager() || isStaff()) {
                $cinemaId = getCurrentCinemaId();
                if ($cinemaId) {
                    $sql = "SELECT rooms.*, 
                            rooms.cinema_id,
                            cinemas.name AS cinema_name
                            FROM rooms
                            LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                            WHERE rooms.cinema_id = :cinema_id
                            ORDER BY rooms.name ASC";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':cinema_id' => $cinemaId]);
                    return $stmt->fetchAll();
                } else {
                    return [];
                }
            } else {
                $sql = "SELECT rooms.*, 
                        rooms.cinema_id,
                        cinemas.name AS cinema_name
                        FROM rooms
                        LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                        ORDER BY cinemas.name ASC, rooms.name ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll();
            }
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy lịch chiếu theo phim (API endpoint)
     */
    public function getByMovie()
    {
        $movie_id = $_GET['movie_id'] ?? null;
        if (!$movie_id) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Movie ID is required']);
            exit;
        }

        $showtimes = $this->showtime->getByMovie($movie_id);
        header('Content-Type: application/json');
        echo json_encode($showtimes);
        exit;
    }

    /**
     * Lấy lịch chiếu theo ngày (API endpoint)
     */
    public function getByDate()
    {
        $date = $_GET['date'] ?? date('Y-m-d');
        $showtimes = $this->showtime->getByDate($date, '');
        header('Content-Type: application/json');
        echo json_encode($showtimes);
        exit;
    }
}

?>

