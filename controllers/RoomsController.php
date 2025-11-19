<?php
class RoomsController
{
    public $room;
    public $cinema;

    public function __construct()
    {
        $this->room = new Room();
        $this->cinema = new Cinema();
    }

    /**
     * Hiển thị danh sách phòng (Admin)
     */
    public function list()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $result = $this->room->paginate($page, 10);
        
        render('admin/rooms/list.php', [
            'data' => $result['data'],
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ]
        ]);
    }

    /**
     * Hiển thị form tạo phòng mới (Admin)
     */
    public function create()
    {
        $errors = [];
        $cinemas = $this->cinema->all();

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

            if (empty(trim($_POST['seat_count'] ?? '')) || (int)$_POST['seat_count'] <= 0) {
                $errors['seat_count'] = "Bạn vui lòng nhập số ghế hợp lệ (lớn hơn 0)";
            }

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'cinema_id' => trim($_POST['cinema_id']),
                    'room_code' => trim($_POST['room_code']),
                    'name' => trim($_POST['name']),
                    'seat_count' => (int)trim($_POST['seat_count'])
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

    /**
     * Hiển thị form sửa phòng (Admin)
     */
    public function edit()
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

        $errors = [];
        $cinemas = $this->cinema->all();

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

            if (empty(trim($_POST['seat_count'] ?? '')) || (int)$_POST['seat_count'] <= 0) {
                $errors['seat_count'] = "Bạn vui lòng nhập số ghế hợp lệ (lớn hơn 0)";
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'cinema_id' => trim($_POST['cinema_id']),
                    'room_code' => trim($_POST['room_code']),
                    'name' => trim($_POST['name']),
                    'seat_count' => (int)trim($_POST['seat_count'])
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

    /**
     * Xóa phòng (Admin)
     */
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

    /**
     * Xem chi tiết phòng (Admin)
     */
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

