<?php
/**
 * SHOWTIMES CONTROLLER - XỬ LÝ LOGIC QUẢN LÝ LỊCH CHIẾU
 * 
 * CHỨC NĂNG:
 * - CRUD lịch chiếu: danh sách, tạo, sửa, xóa, xem chi tiết
 * - Lọc lịch chiếu: theo ngày, trạng thái, rạp
 * - Phân quyền: Admin xem tất cả, Manager/Staff chỉ xem rạp được gán
 * 
 * LUỒNG CHẠY TỔNG QUÁT:
 * 1. Kiểm tra quyền truy cập (requireAdminOrManager, requireAdminOrStaff)
 * 2. Lọc dữ liệu theo role (Admin: tất cả, Manager/Staff: chỉ rạp được gán)
 * 3. Lấy dữ liệu từ Model (Showtime, Movie, Room, Cinema)
 * 4. Render View với dữ liệu đã lọc
 */
class ShowtimesController
{
    public $showtime; // Model Showtime để tương tác với database
    public $movie; // Model Movie để lấy danh sách phim
    public $room; // Model Room để lấy danh sách phòng

    public function __construct()
    {
        // Khởi tạo các Models cần thiết
        $this->showtime = new Showtime(); // Model để query bảng showtimes
        $this->movie = new Movie(); // Model để query bảng movies
    }

    /**
     * DANH SÁCH LỊCH CHIẾU (ADMIN/MANAGER/STAFF)
     * 
     * LUỒNG CHẠY:
     * 1. Kiểm tra quyền (requireAdminOrStaff)
     * 2. Lấy tham số lọc từ URL (date, status, cinema, page)
     * 3. Phân quyền lọc dữ liệu:
     *    - Admin: có thể lọc theo rạp, xem tất cả
     *    - Manager/Staff: chỉ xem lịch chiếu của rạp được gán
     * 4. Gọi Model để lấy dữ liệu phân trang
     * 5. Lấy danh sách phim, phòng, rạp để hiển thị filter
     * 6. Render view với dữ liệu
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_GET: date, status, cinema, page
     * - Từ Model Showtime: paginate() hoặc paginateByCinema() -> danh sách lịch chiếu
     * - Từ Model Movie: all() -> danh sách phim (cho filter)
     * - Từ Model Room: getRooms() -> danh sách phòng (cho filter)
     * - Từ Model Cinema: all() -> danh sách rạp (cho filter, chỉ admin)
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff(); // Yêu cầu quyền admin, manager hoặc staff
        
        // ============================================
        // LẤY THAM SỐ LỌC TỪ URL
        // ============================================
        $date = $_GET['date'] ?? null; // Lọc theo ngày chiếu
        $status = $_GET['status'] ?? null; // Lọc theo trạng thái (upcoming, showing, ended)
        $filterCinemaId = null; // Lọc theo rạp (chỉ admin mới có)
        if (isAdmin()) {
            $filterCinemaId = !empty($_GET['cinema']) ? (int)$_GET['cinema'] : null;
        }
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Số trang
        $page = max(1, $page); // Đảm bảo page >= 1
        
        // ============================================
        // PHÂN QUYỀN LỌC DỮ LIỆU
        // ============================================
        // Nếu là manager hoặc staff, chỉ lấy lịch chiếu của rạp mình quản lý
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId(); // Lấy cinema_id từ session
            if ($cinemaId) {
                // Lấy lịch chiếu của rạp được gán, có phân trang
                $result = $this->showtime->paginateByCinema($cinemaId, $page, 5, $date, $status);
            } else {
                // Không có rạp được gán -> trả về rỗng
                $result = ['data' => [], 'page' => 1, 'totalPages' => 0, 'total' => 0, 'perPage' => 5];
            }
        } else {
            // Admin có thể lọc theo rạp và xem tất cả
            $result = $this->showtime->paginate($page, 5, $date, $status, $filterCinemaId);
        }
        
        // ============================================
        // LẤY DỮ LIỆU CHO FILTER
        // ============================================
        // Lấy danh sách phim và phòng để hiển thị trong dropdown filter
        $movies = $this->movie->all();
        $rooms = $this->getRooms();
        
        // Lấy danh sách rạp cho filter (chỉ admin mới có dropdown chọn rạp)
        $cinemas = [];
        if (isAdmin()) {
            require_once __DIR__ . '/../models/Cinema.php';
            $cinemaModel = new Cinema();
            $cinemas = $cinemaModel->all();
        }
        
        // ============================================
        // RENDER VIEW
        // ============================================
        render('admin/showtimes/list.php', [
            'data' => $result['data'], // Danh sách lịch chiếu
            'movies' => $movies, // Danh sách phim (cho filter)
            'rooms' => $rooms, // Danh sách phòng (cho filter)
            'cinemas' => $cinemas, // Danh sách rạp (cho filter, chỉ admin)
            'selectedDate' => $date, // Ngày đã chọn (để giữ giá trị trong form)
            'selectedStatus' => $status, // Trạng thái đã chọn
            'selectedCinema' => $filterCinemaId, // Rạp đã chọn
            'pagination' => [
                'currentPage' => $result['page'], // Trang hiện tại
                'totalPages' => $result['totalPages'], // Tổng số trang
                'total' => $result['total'], // Tổng số lịch chiếu
                'perPage' => $result['perPage'] // Số lịch chiếu mỗi trang
            ]
        ]);
    }

    /**
     * TẠO LỊCH CHIẾU MỚI (ADMIN/MANAGER)
     * 
     * LUỒNG CHẠY:
     * 1. Kiểm tra quyền (requireAdminOrManager - staff không có quyền)
     * 2. Hiển thị form tạo lịch chiếu (GET request)
     * 3. Nhận dữ liệu từ form (POST request)
     * 4. Validate dữ liệu (phim, phòng, ngày, giờ, format)
     * 5. Kiểm tra conflict (phòng đã có suất chiếu khác trong khoảng thời gian này)
     * 6. Manager chỉ được tạo lịch chiếu cho phòng thuộc rạp mình quản lý
     * 7. Insert vào database
     * 8. Redirect về danh sách lịch chiếu
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_POST: movie_id, room_id, show_date, start_time, end_time, format
     * - Từ Model Movie: all() -> danh sách phim
     * - Từ Model Cinema: all() hoặc find() -> danh sách rạp hoặc rạp của manager
     * - Từ Model Room: getRooms() -> danh sách phòng
     * - Lưu vào database: bảng showtimes
     */
    public function create()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager(); // Chỉ admin và manager mới có quyền tạo lịch chiếu
        
        $errors = []; // Mảng lưu lỗi validation
        $movies = $this->movie->all(); // Lấy danh sách phim để hiển thị trong dropdown
        $cinemas = $this->getCinemas(); // Lấy danh sách rạp (admin: tất cả, manager: chỉ rạp mình)
        $rooms = $this->getRooms(); // Lấy danh sách phòng (admin: tất cả, manager: chỉ phòng của rạp mình)
        
        // ============================================
        // XỬ LÝ CHO MANAGER
        // ============================================
        // Lấy cinema_id của manager nếu là manager (để giới hạn phòng chỉ thuộc rạp này)
        $managerCinemaId = null;
        $managerCinemaName = null;
        if (isManager()) {
            $managerCinemaId = getCurrentCinemaId(); // Lấy cinema_id từ session
            if ($managerCinemaId) {
                require_once __DIR__ . '/../models/Cinema.php';
                $cinemaModel = new Cinema();
                $cinema = $cinemaModel->find($managerCinemaId);
                $managerCinemaName = $cinema['name'] ?? null; // Tên rạp để hiển thị
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

