<?php
// SEATS CONTROLLER - Xử lý logic quản lý ghế
// Chức năng: CRUD ghế, hiển thị sơ đồ ghế, tự động tạo ghế (Admin/Manager/Staff)
class SeatsController
{
    public $seat; // Model Seat để tương tác với database
    public $room; // Model Room để lấy danh sách phòng
    public $cinema; // Model Cinema để lấy danh sách rạp

    public function __construct()
    {
        $this->seat = new Seat(); // Khởi tạo Model Seat
        $this->room = new Room(); // Khởi tạo Model Room
        $this->cinema = new Cinema(); // Khởi tạo Model Cinema
    }

    // Danh sách ghế (Admin/Manager/Staff) - hiển thị sơ đồ ghế theo phòng
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        $roomId = $_GET['room_id'] ?? null;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

        $result = $this->seat->paginate($page, 20, $roomId);

        // Lấy danh sách phòng để filter
        $rooms = $this->room->all();

        
        // Nếu là manager hoặc staff, chỉ lấy phòng của rạp mình quản lý
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $rooms = $this->room->getByCinema($cinemaId);
            } else {
                $rooms = [];
            }
        } else {
            $rooms = $this->room->all();
        }
        
        $room = null;
        $seatMap = [];
        
        // Nếu có chọn phòng, lấy sơ đồ ghế của phòng đó
        if ($roomId) {
            $room = $this->room->find($roomId);
            if ($room) {
                // Manager và Staff chỉ được xem ghế của phòng thuộc rạp mình quản lý
                if ((isManager() || isStaff()) && !canAccessCinema($room['cinema_id'])) {
                    $room = null;
                    $seatMap = [];
                } else {
                    $seatMap = $this->seat->getSeatMapByRoom($roomId);
                }
            }
        }
        
        render('admin/seats/list.php', [
            'rooms' => $rooms,
            'selectedRoomId' => $roomId,
            'room' => $room,
            'seatMap' => $seatMap
        ]);
    }

    // Hiển thị sơ đồ ghế (Admin/Manager/Staff) - layout phòng, trạng thái ghế
    public function seatMap()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
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
        
        // Manager và Staff chỉ được xem ghế của phòng thuộc rạp mình quản lý
        if ((isManager() || isStaff()) && !canAccessCinema($room['cinema_id'])) {
            header('Location: ' . BASE_URL . '?act=seats');
            exit;
        }

        // Lấy sơ đồ ghế
        $seatMap = $this->seat->getSeatMapByRoom($roomId);

        // Lấy danh sách phòng để chọn
        $rooms = $this->room->all();

        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $rooms = $this->room->getByCinema($cinemaId);
            } else {
                $rooms = [];
            }
        } else {
            $rooms = $this->room->all();
        }
        
        render('admin/seats/seatmap.php', [
            'room' => $room,
            'seatMap' => $seatMap,
            'rooms' => $rooms
        ]);
    }

    // Tạo sơ đồ ghế tự động (Admin/Manager) - nhập số hàng, số cột, tự tạo
    public function generateSeats()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrManager();
        $errors = [];
        
        // Nếu là manager, chỉ lấy phòng của rạp mình quản lý
        if (isManager()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                $rooms = $this->room->getByCinema($cinemaId);
            } else {
                $rooms = [];
            }
        } else {
            $rooms = $this->room->all();
        }
        
        // Lấy số ghế thực tế cho mỗi phòng
        foreach ($rooms as &$room) {
            $room['actual_seat_count'] = $this->seat->getCountByRoom($room['id']);
        }

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
                // Lấy thông tin phòng để kiểm tra capacity
                $room = $this->room->find($roomId);
                $roomCapacity = $room['seat_count'] ?? $room['capacity'] ?? 0;
                $totalSeatsToCreate = $rows * $seatsPerRow;

                // Kiểm tra nếu phòng có capacity và tổng số ghế tạo ra khác capacity
                if ($roomCapacity > 0 && $totalSeatsToCreate != $roomCapacity) {
                    $errors['general'] = "Phòng này có {$roomCapacity} ghế, nhưng bạn đang tạo {$totalSeatsToCreate} ghế ({$rows} hàng x {$seatsPerRow} ghế). Vui lòng điều chỉnh để khớp với số ghế của phòng.";
                }

                if (empty($errors)) {
                    // Xóa ghế cũ nếu được chọn
                    if ($clearExisting) {
                        $this->seat->deleteByRoom($roomId);
                    }

                    // Tạo ghế mới với giới hạn capacity
                    $result = $this->seat->insertBulk($roomId, $rows, $seatsPerRow, $seatType, $extraPrice, $roomCapacity);

                    if ($result !== false) {
                        // Hiển thị thông báo số ghế đã tạo
                        $_SESSION['success'] = "Đã tạo thành công {$result} ghế. Sơ đồ ghế được chia đều 2 bên (mỗi bên 6 ghế/hàng).";
                        header('Location: ' . BASE_URL . '?act=seats&room_id=' . $roomId);
                        exit;
                    } else {
                        $errors['general'] = 'Có lỗi xảy ra khi tạo ghế. Vui lòng thử lại.';
                    }
                }
            }
        }

        render('admin/seats/generate.php', ['errors' => $errors, 'rooms' => $rooms]);
    }

    // Hiển thị form tạo ghế mới (Admin) - validate, insert vào DB
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

    // Hiển thị form sửa ghế (Admin) - validate, update
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

    // Xóa ghế (Admin)
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

    // Xem chi tiết ghế (Admin)
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
