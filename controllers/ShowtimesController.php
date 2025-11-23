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
     * Hiển thị danh sách lịch chiếu (Admin)
     */
    public function list()
    {
        // Lọc theo ngày nếu có
        $date = $_GET['date'] ?? null;
        // Lọc theo trạng thái nếu có
        $status = $_GET['status'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo page >= 1
        
        // Lấy dữ liệu phân trang
        $result = $this->showtime->paginate($page, 5, $date, $status);
        
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
     * Hiển thị form tạo lịch chiếu mới (Admin)
     */
    public function create()
    {
        $errors = [];
        $movies = $this->movie->all();
        $cinemas = $this->getCinemas();
        $rooms = $this->getRooms();

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
            'rooms' => $rooms
        ]);
    }

    /**
     * Hiển thị form sửa lịch chiếu (Admin)
     */
    public function edit()
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

        $errors = [];
        $movies = $this->movie->all();
        $cinemas = $this->getCinemas();
        $rooms = $this->getRooms();
        
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
            'currentCinemaId' => $currentCinemaId
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
        try {
            $conn = connectDB();
            $sql = "SELECT * FROM cinemas ORDER BY name ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy danh sách phòng
     */
    private function getRooms()
    {
        try {
            $conn = connectDB();
            $sql = "SELECT rooms.*, 
                    rooms.cinema_id,
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    ORDER BY cinemas.name ASC, rooms.name ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
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

