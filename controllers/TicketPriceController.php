<?php
// TICKET PRICE CONTROLLER - Xử lý quản lý giá vé
// Chức năng: Hiển thị bảng giá vé (client), quản lý giá vé (admin), nhóm giá theo ngày/format/khách hàng/ghế
class TicketPriceController
{
    public $ticketPrice; // Model TicketPrice để tương tác với database

    public function __construct()
    {
        require_once __DIR__ . '/../models/TicketPrice.php';
        $this->ticketPrice = new TicketPrice(); // Khởi tạo Model TicketPrice
    }

    // Trang bảng giá vé (Client) - nhóm giá theo ngày/format/khách hàng/ghế
    public function index()
    {
        // Lấy tất cả giá vé từ database
        $prices = $this->ticketPrice->all();
        
        // ============================================
        // NHÓM GIÁ THEO CÁC TIÊU CHÍ
        // ============================================
        // Nhóm giá theo day_type, format, customer_type và seat_type
        $groupedPrices = [];
        foreach ($prices as $price) {
            // Tạo key duy nhất cho mỗi nhóm (day_type_format_customer_type)
            $key = $price['day_type'] . '_' . $price['format'] . '_' . $price['customer_type'];
            if (!isset($groupedPrices[$key])) {
                // Tạo nhóm mới
                $groupedPrices[$key] = [
                    'day_type' => $price['day_type'], // weekday hoặc weekend
                    'format' => $price['format'], // 2D hoặc 3D
                    'customer_type' => $price['customer_type'], // adult, child, student
                    'normal' => 0, // Giá ghế thường
                    'vip' => 0 // Giá ghế VIP
                ];
            }
            // Gán giá theo loại ghế
            if ($price['seat_type'] === 'normal') {
                $groupedPrices[$key]['normal'] = $price['base_price'];
            } else {
                $groupedPrices[$key]['vip'] = $price['base_price'];
            }
        }

        // ============================================
        // RENDER VIEW
        // ============================================
        renderClient('client/giave.php', [
            'prices' => $prices, // Tất cả giá vé (raw data)
            'groupedPrices' => $groupedPrices // Giá đã nhóm (để hiển thị bảng)
        ], 'Bảng giá vé');
    }

    // Danh sách giá vé (Admin) - nhóm theo weekday/weekend
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();

        $prices = $this->ticketPrice->all();
        
        // Nhóm giá theo day_type
        $weekdayPrices = [];
        $weekendPrices = [];
        
        foreach ($prices as $price) {
            if ($price['day_type'] === 'weekday') {
                $weekdayPrices[] = $price;
            } else {
                $weekendPrices[] = $price;
            }
        }

        render('admin/ticket-prices/list.php', [
            'weekdayPrices' => $weekdayPrices,
            'weekendPrices' => $weekendPrices
        ]);
    }

    // Form chỉnh sửa giá vé (Admin) - nhóm theo weekday/weekend
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();

        $prices = $this->ticketPrice->all();
        
        // Nhóm giá theo day_type
        $weekdayPrices = [];
        $weekendPrices = [];
        
        foreach ($prices as $price) {
            if ($price['day_type'] === 'weekday') {
                $weekdayPrices[] = $price;
            } else {
                $weekendPrices[] = $price;
            }
        }

        render('admin/ticket-prices/edit.php', [
            'weekdayPrices' => $weekdayPrices,
            'weekendPrices' => $weekendPrices
        ]);
    }

    // Cập nhật giá vé (Admin) - update batch nhiều giá cùng lúc
    public function update()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prices = $_POST['prices'] ?? [];
            
            if ($this->ticketPrice->updateBatch($prices)) {
                $_SESSION['success_message'] = 'Cập nhật giá vé thành công!';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật giá vé!';
            }
            
            header('Location: ' . BASE_URL . '?act=ticket-prices');
            exit;
        }
    }
}
