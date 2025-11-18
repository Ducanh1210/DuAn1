<?php
class SeatsController
{
    public $seat;
    public $room;
    public $cinema;

    public function __construct()
    {
        $this->seat = new Seat();
        $this->room = new Room();
        $this->cinema = new Cinema();
    }

    /**
     * Hiển thị danh sách ghế (Admin)
     */
    public function list()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $roomId = $_GET['room_id'] ?? null;
        
        $result = $this->seat->paginate($page, 20, $roomId);
        
        // Lấy danh sách phòng để filter
        $rooms = $this->room->all();
        
        render('admin/seats/list.php', [
            'data' => $result['data'],
            'rooms' => $rooms,
            'selectedRoomId' => $roomId,
            'pagination' => [
                'currentPage' => $result['page'],
                'totalPages' => $result['totalPages'],
                'total' => $result['total'],
                'perPage' => $result['perPage']
            ]
        ]);
    }

    /**
     * Hiển thị sơ đồ ghế (Admin)
     */
    public function seatMap()
    {
        $roomId = $_GET['room_id'] ?? null;
        
        if (!$roomId) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        $room = $this->room->find($roomId);
        if (!$room) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        // Lấy sơ đồ ghế
        $seatMap = $this->seat->getSeatMapByRoom($roomId);
        
        // Lấy danh sách phòng để chọn
        $rooms = $this->room->all();
        
        render('admin/seats/seatmap.php', [
            'room' => $room,
            'seatMap' => $seatMap,
            'rooms' => $rooms
        ]);
    }

    /**
     * Tạo sơ đồ ghế tự động (Admin)
     */
    public function generateSeats()
    {
        $errors = [];
        $rooms = $this->room->all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId = $_POST['room_id'] ?? null;
            $rows = (int)($_POST['rows'] ?? 0);
            $seatsPerRow = (int)($_POST['seats_per_row'] ?? 0);
            $seatType = $_POST['seat_type'] ?? 'normal';
            $extraPrice = (float)($_POST['extra_price'] ?? 0);
            $clearExisting = isset($_POST['clear_existing']) && $_POST['clear_existing'] == '1';

            // Validation
            if (empty($roomId)) {
                $errors['room_id'] = "Bạn vui lòng chọn phòng";
            }

            if ($rows <= 0 || $rows > 26) {
                $errors['rows'] = "Số hàng phải từ 1 đến 26";
            }

            if ($seatsPerRow <= 0 || $seatsPerRow > 50) {
                $errors['seats_per_row'] = "Số ghế mỗi hàng phải từ 1 đến 50";
            }

            if (empty($errors)) {
                // Xóa ghế cũ nếu được chọn
                if ($clearExisting) {
                    $this->seat->deleteByRoom($roomId);
                }

                // Tạo ghế mới
                $result = $this->seat->insertBulk($roomId, $rows, $seatsPerRow, $seatType, $extraPrice);
                
                if ($result !== false) {
                    header('Location: ' . BASE_URL . '?act=seats-seatmap&room_id=' . $roomId);
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi tạo ghế. Vui lòng thử lại.';
                }
            }
        }

        render('admin/seats/generate.php', ['errors' => $errors, 'rooms' => $rooms]);
    }

    /**
     * Hiển thị form tạo ghế mới (Admin)
     */
    public function create()
    {
        $errors = [];
        $rooms = $this->room->all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId = $_POST['room_id'] ?? null;
            $rowLabel = strtoupper(trim($_POST['row_label'] ?? ''));
            $seatNumber = (int)($_POST['seat_number'] ?? 0);
            $seatType = $_POST['seat_type'] ?? 'normal';
            $extraPrice = (float)($_POST['extra_price'] ?? 0);
            $status = $_POST['status'] ?? 'available';

            // Validation
            if (empty($roomId)) {
                $errors['room_id'] = "Bạn vui lòng chọn phòng";
            }

            if (empty($rowLabel) || strlen($rowLabel) > 1) {
                $errors['row_label'] = "Nhãn hàng phải là 1 ký tự (A-Z)";
            }

            if ($seatNumber <= 0) {
                $errors['seat_number'] = "Số ghế phải lớn hơn 0";
            }

            // Kiểm tra trùng
            if (empty($errors) && $this->seat->checkDuplicate($roomId, $rowLabel, $seatNumber)) {
                $errors['duplicate'] = "Ghế này đã tồn tại trong phòng";
            }

            if (empty($errors)) {
                $data = [
                    'room_id' => $roomId,
                    'row_label' => $rowLabel,
                    'seat_number' => $seatNumber,
                    'seat_type' => $seatType,
                    'extra_price' => $extraPrice,
                    'status' => $status
                ];
                
                $result = $this->seat->insert($data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=seats-seatmap&room_id=' . $roomId);
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm ghế. Vui lòng thử lại.';
                }
            }
        }

        render('admin/seats/create.php', ['errors' => $errors, 'rooms' => $rooms]);
    }

    /**
     * Hiển thị form sửa ghế (Admin)
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        $seat = $this->seat->find($id);
        if (!$seat) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        $errors = [];
        $rooms = $this->room->all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId = $_POST['room_id'] ?? null;
            $rowLabel = strtoupper(trim($_POST['row_label'] ?? ''));
            $seatNumber = (int)($_POST['seat_number'] ?? 0);
            $seatType = $_POST['seat_type'] ?? 'normal';
            $extraPrice = (float)($_POST['extra_price'] ?? 0);
            $status = $_POST['status'] ?? 'available';

            // Validation
            if (empty($roomId)) {
                $errors['room_id'] = "Bạn vui lòng chọn phòng";
            }

            if (empty($rowLabel) || strlen($rowLabel) > 1) {
                $errors['row_label'] = "Nhãn hàng phải là 1 ký tự (A-Z)";
            }

            if ($seatNumber <= 0) {
                $errors['seat_number'] = "Số ghế phải lớn hơn 0";
            }

            // Kiểm tra trùng (trừ ghế hiện tại)
            if (empty($errors) && $this->seat->checkDuplicate($roomId, $rowLabel, $seatNumber, $id)) {
                $errors['duplicate'] = "Ghế này đã tồn tại trong phòng";
            }

            if (empty($errors)) {
                $data = [
                    'room_id' => $roomId,
                    'row_label' => $rowLabel,
                    'seat_number' => $seatNumber,
                    'seat_type' => $seatType,
                    'extra_price' => $extraPrice,
                    'status' => $status
                ];
                
                $result = $this->seat->update($id, $data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=seats-seatmap&room_id=' . $roomId);
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật ghế. Vui lòng thử lại.';
                }
            }
        }

        render('admin/seats/edit.php', ['seat' => $seat, 'errors' => $errors, 'rooms' => $rooms]);
    }

    /**
     * Xóa ghế (Admin)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $roomId = $_GET['room_id'] ?? null;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        $seat = $this->seat->find($id);
        if ($seat) {
            $this->seat->delete($id);
        }

        if ($roomId) {
            header('Location: ' . BASE_URL . '?act=seats-seatmap&room_id=' . $roomId);
        } else {
            header('Location: ' . BASE_URL . '?act=seats');
        }
        exit;
    }

    /**
     * Xem chi tiết ghế (Admin)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        $seat = $this->seat->find($id);
        if (!$seat) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        render('admin/seats/show.php', ['seat' => $seat]);
    }
}

?>

