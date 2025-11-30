<?php
class TicketPriceController
{
    public $ticketPrice;

    public function __construct()
    {
        require_once __DIR__ . '/../models/TicketPrice.php';
        $this->ticketPrice = new TicketPrice();
    }

    /**
     * Hiển thị trang giá vé cho client
     */
    public function index()
    {
        $prices = $this->ticketPrice->all();
        
        // Nhóm giá theo day_type, format, customer_type và seat_type
        $groupedPrices = [];
        foreach ($prices as $price) {
            $key = $price['day_type'] . '_' . $price['format'] . '_' . $price['customer_type'];
            if (!isset($groupedPrices[$key])) {
                $groupedPrices[$key] = [
                    'day_type' => $price['day_type'],
                    'format' => $price['format'],
                    'customer_type' => $price['customer_type'],
                    'normal' => 0,
                    'vip' => 0
                ];
            }
            if ($price['seat_type'] === 'normal') {
                $groupedPrices[$key]['normal'] = $price['base_price'];
            } else {
                $groupedPrices[$key]['vip'] = $price['base_price'];
            }
        }

        renderClient('client/giave.php', [
            'prices' => $prices,
            'groupedPrices' => $groupedPrices
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
