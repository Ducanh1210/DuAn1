<?php
/**
 * TICKET PRICE CONTROLLER - XỬ LÝ QUẢN LÝ GIÁ VÉ
 * 
 * CHỨC NĂNG:
 * - Hiển thị bảng giá vé (client): index()
 * - Quản lý giá vé (admin): list(), edit(), update()
 * - Nhóm giá theo: day_type (weekday/weekend), format (2D/3D), customer_type, seat_type
 * 
 * LUỒNG CHẠY:
 * 1. Lấy tất cả giá vé từ database
 * 2. Nhóm giá theo các tiêu chí (ngày, format, loại khách hàng, loại ghế)
 * 3. Render view với dữ liệu đã nhóm
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: ticket_prices
 * - Nhóm theo: day_type, format, customer_type, seat_type
 */
class TicketPriceController
{
    public $ticketPrice; // Model TicketPrice để tương tác với database

    public function __construct()
    {
        // Load Model TicketPrice
        require_once __DIR__ . '/../models/TicketPrice.php';
        $this->ticketPrice = new TicketPrice();
    }

    /**
     * TRANG BẢNG GIÁ VÉ (CLIENT)
     * 
     * LUỒNG CHẠY:
     * 1. Lấy tất cả giá vé từ database
     * 2. Nhóm giá theo day_type, format, customer_type, seat_type
     * 3. Render view với dữ liệu đã nhóm
     * 
     * DỮ LIỆU LẤY:
     * - Từ Model TicketPrice: all() -> tất cả giá vé
     * - Nhóm theo: weekday/weekend, 2D/3D, adult/child/student, normal/vip
     * - Hiển thị: bảng giá vé dễ đọc cho khách hàng
     */
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

    /**
     * Hiển thị danh sách giá vé (Admin)
     */
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

    /**
     * Hiển thị form chỉnh sửa giá vé (Admin)
     */
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

    /**
     * Cập nhật giá vé (Admin)
     */
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
