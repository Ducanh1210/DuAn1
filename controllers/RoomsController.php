<?php
// ROOMS CONTROLLER - Xử lý logic quản lý phòng chiếu
// Chức năng: CRUD phòng, phân trang, lọc theo rạp (Admin xem tất cả, Manager/Staff chỉ rạp được gán)
class RoomsController
{
    public $room; // Model Room để tương tác với database
    public $cinema; // Model Cinema để lấy danh sách rạp

    public function __construct()
    {
        $this->room = new Room(); // Khởi tạo Model Room
        $this->cinema = new Cinema(); // Khởi tạo Model Cinema
    }

    // Danh sách phòng (Admin/Manager/Staff) - có phân trang, lọc theo rạp, tìm kiếm
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        // Lấy tham số tìm kiếm và lọc
        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $cinemaFilter = isset($_GET['cinema_id']) ? (int)$_GET['cinema_id'] : null;
        $cinemaFilter = $cinemaFilter > 0 ? $cinemaFilter : null;
        
        // Lấy danh sách rạp cho filter (chỉ admin mới thấy dropdown)
        $cinemas = [];
        if (isAdmin()) {
            $cinemas = $this->cinema->all();
        }
        
        // Nếu là manager hoặc staff, chỉ lấy phòng của rạp mình quản lý
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $result = $this->room->paginateByCinema($cinemaId, $page, 10, $searchKeyword);
            } else {
                $result = ['data' => [], 'page' => 1, 'totalPages' => 0, 'total' => 0, 'perPage' => 10];
            }
        } else {
            // Admin có thể lọc theo rạp và tìm kiếm
            $result = $this->room->paginate($page, 10, $cinemaFilter, $searchKeyword);
        }
        
        // Lấy số ghế thực tế từ bảng seats cho mỗi phòng
        require_once __DIR__ . '/../models/Seat.php';
        $seatModel = new Seat();
        
        foreach ($result['data'] as &$room) {
            $room['actual_seat_count'] = $seatModel->getCountByRoom($room['id']);
        }
        
        render('admin/rooms/list.php', [
            'data' => $result['data'],
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ],
            'cinemas' => $cinemas,
            'searchKeyword' => $searchKeyword,
            'cinemaFilter' => $cinemaFilter
        ]);
    }

    // Hiển thị form tạo phòng mới (Admin/Manager) - validate, insert vào DB
    public function create()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager();
        
        $errors = [];
        
        // Nếu là manager, chỉ hiển thị rạp của mình
        if (isManager()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $cinema = $this->cinema->find($cinemaId);
                $cinemas = $cinema ? [$cinema] : [];
            } else {
                $cinemas = [];
            }
        } else {
            $cinemas = $this->cinema->all();
        }

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['cinema_id'] ?? ''))) {
                $errors['cinema_id'] = "Bạn vui lòng chọn rạp";
            }

            if (empty(trim($_POST['room_code'] ?? ''))) {
                $errors['room_code'] = "Bạn vui lòng nhập mã phòng";
            }

            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên phòng";
            }

            // Số ghế mặc định là 0 (sẽ được cập nhật khi tạo ghế)
            $seatCount = isset($_POST['seat_count']) ? (int)trim($_POST['seat_count']) : 0;
            if ($seatCount < 0) {
                $errors['seat_count'] = "Số ghế không được âm";
            }

            // Manager chỉ được tạo phòng cho rạp của mình
            if (isManager()) {
                $cinemaId = getCurrentCinemaId();
                $postCinemaId = trim($_POST['cinema_id'] ?? '');
                if ($cinemaId != $postCinemaId) {
                    $errors['cinema_id'] = "Bạn chỉ được tạo phòng cho rạp của mình";
                }
            }
            
            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'cinema_id' => trim($_POST['cinema_id']),
                    'room_code' => trim($_POST['room_code']),
                    'name' => trim($_POST['name']),
                    'seat_count' => $seatCount // Mặc định 0, sẽ được cập nhật khi tạo ghế
                ];
                
                $result = $this->room->insert($data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=rooms');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm phòng. Vui lòng thử lại.';
                }
            }
        }

        render('admin/rooms/create.php', ['errors' => $errors, 'cinemas' => $cinemas]);
    }

    // Hiển thị form sửa phòng (Admin/Manager) - validate, update
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }

        $room = $this->room->find($id);
        if (!$room) {
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }
        
        // Manager chỉ được sửa phòng của rạp mình quản lý
        if (isManager() && !canAccessCinema($room['cinema_id'])) {
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }

        $errors = [];
        
        // Nếu là manager, chỉ hiển thị rạp của mình
        if (isManager()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $cinema = $this->cinema->find($cinemaId);
                $cinemas = $cinema ? [$cinema] : [];
            } else {
                $cinemas = [];
            }
        } else {
            $cinemas = $this->cinema->all();
        }

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['cinema_id'] ?? ''))) {
                $errors['cinema_id'] = "Bạn vui lòng chọn rạp";
            }

            if (empty(trim($_POST['room_code'] ?? ''))) {
                $errors['room_code'] = "Bạn vui lòng nhập mã phòng";
            }

            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên phòng";
            }
            
            // Manager chỉ được sửa phòng cho rạp của mình
            if (isManager()) {
                $cinemaId = getCurrentCinemaId();
                $postCinemaId = trim($_POST['cinema_id'] ?? '');
                if ($cinemaId != $postCinemaId) {
                    $errors['cinema_id'] = "Bạn chỉ được sửa phòng cho rạp của mình";
                }
            }

            // Số ghế mặc định là 0 hoặc lấy từ số ghế thực tế
            $seatCount = isset($_POST['seat_count']) ? (int)trim($_POST['seat_count']) : 0;
            if ($seatCount < 0) {
                $errors['seat_count'] = "Số ghế không được âm";
            }
            
            // Nếu không nhập số ghế, lấy số ghế thực tế từ bảng seats
            if ($seatCount == 0) {
                require_once __DIR__ . '/../models/Seat.php';
                $seatModel = new Seat();
                $seatCount = $seatModel->getCountByRoom($id);
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'cinema_id' => trim($_POST['cinema_id']),
                    'room_code' => trim($_POST['room_code']),
                    'name' => trim($_POST['name']),
                    'seat_count' => $seatCount // Sẽ được cập nhật tự động từ số ghế thực tế
                ];
                
                $result = $this->room->update($id, $data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=rooms');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật phòng. Vui lòng thử lại.';
                }
            }
        }

        render('admin/rooms/edit.php', ['room' => $room, 'errors' => $errors, 'cinemas' => $cinemas]);
    }

    // Xóa phòng (Admin)
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }

        // Kiểm tra xem phòng có đang được sử dụng không
        if ($this->room->hasShowtimes($id)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error'] = 'Không thể xóa phòng này vì đang có lịch chiếu. Vui lòng xóa lịch chiếu trước.';
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }

        $room = $this->room->find($id);
        if ($room) {
            $this->room->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=rooms');
        exit;
    }

    // Xem chi tiết phòng (Admin) - hiển thị sơ đồ ghế
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }

        $room = $this->room->find($id);
        if (!$room) {
            header('Location: ' . BASE_URL . '?act=rooms');
            exit;
        }

        render('admin/rooms/show.php', ['room' => $room]);
    }
}

?>

