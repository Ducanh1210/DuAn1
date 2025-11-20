<?php
require_once __DIR__ . '/../models/Voucher.php';
require_once __DIR__ . '/../models/DiscountCode.php';

class VoucherController
{
    private $voucher;
    private $discountCode;

    public function __construct()
    {
        $this->voucher = new Voucher();
        require_once __DIR__ . '/../models/DiscountCode.php';
        $this->discountCode = new DiscountCode();
    }

    /**
     * Hiển thị trang khuyến mãi cho client
     */
    public function index()
    {
        $vouchers = $this->voucher->getActiveForClient();
        
        // Dữ liệu mẫu cho các phần khác
        $membershipBenefits = [
            ['icon' => 'bi-star-fill', 'title' => 'Giảm giá tự động', 'desc' => 'Giảm giá tự động cho mọi đơn hàng theo hạng thành viên'],
            ['icon' => 'bi-gift-fill', 'title' => 'Tích điểm thưởng', 'desc' => 'Tích điểm và đổi quà hấp dẫn'],
            ['icon' => 'bi-ticket-perforated', 'title' => 'Ưu tiên đặt vé', 'desc' => 'Đặt vé trước khi mở bán cho công chúng'],
            ['icon' => 'bi-calendar-event', 'title' => 'Sinh nhật đặc biệt', 'desc' => 'Nhận voucher sinh nhật độc quyền']
        ];

        $faqs = [
            [
                'question' => 'Làm thế nào để sử dụng mã giảm giá?',
                'answer' => 'Bạn chỉ cần nhập mã giảm giá ở bước thanh toán khi đặt vé. Mã sẽ được áp dụng tự động và giảm giá sẽ được tính vào tổng tiền.'
            ],
            [
                'question' => 'Mã giảm giá có thể kết hợp với ưu đãi khác không?',
                'answer' => 'Tùy thuộc vào từng chương trình khuyến mãi. Một số mã có thể kết hợp, một số không. Vui lòng xem điều khoản của từng chương trình.'
            ],
            [
                'question' => 'Mã giảm giá có thời hạn sử dụng không?',
                'answer' => 'Có, mỗi mã giảm giá đều có thời hạn sử dụng. Vui lòng kiểm tra thông tin chi tiết của từng mã.'
            ],
            [
                'question' => 'Làm sao để nhận thông báo về khuyến mãi mới?',
                'answer' => 'Bạn có thể đăng ký nhận email thông báo ở phần cuối trang khuyến mãi. Chúng tôi sẽ gửi tối đa 2 email/tuần với các ưu đãi độc quyền.'
            ]
        ];

        $heroStats = [
            ['value' => '50+', 'label' => 'Mã giảm giá'],
            ['value' => '10K+', 'label' => 'Người dùng'],
            ['value' => '500K+', 'label' => 'Đã tiết kiệm']
        ];

        renderClient('client/khuyenmai.php', [
            'promotions' => $vouchers,
            'membershipBenefits' => $membershipBenefits,
            'faqs' => $faqs,
            'heroStats' => $heroStats
        ], 'Khuyến Mãi');
    }

    /**
     * Danh sách voucher (Admin)
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $vouchers = $this->voucher->all();
        
        render('admin/vouchers/list.php', [
            'vouchers' => $vouchers
        ]);
    }

    /**
     * Form tạo voucher mới (Admin)
     */
    public function create()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $errors = [];
        $discountCodes = $this->discountCode->all();
        
        if (!empty($_POST)) {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'tag' => $_POST['tag'] ?? 'general',
                'code' => $_POST['code'] ?? null,
                'discount_code_id' => !empty($_POST['discount_code_id']) ? (int)$_POST['discount_code_id'] : null,
                'benefits' => isset($_POST['benefits']) ? array_filter($_POST['benefits']) : [],
                'period' => $_POST['period'] ?? null,
                'image' => $_POST['image'] ?? null,
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'status' => $_POST['status'] ?? 'active',
                'cta' => $_POST['cta'] ?? 'Đặt vé ngay',
                'cta_link' => $_POST['cta_link'] ?? null,
                'priority' => isset($_POST['priority']) ? (int)$_POST['priority'] : 0
            ];
            
            $errors = $this->voucher->validate($data);
            
            if (empty($errors)) {
                $id = $this->voucher->insert($data);
                if ($id) {
                    header('Location: ' . BASE_URL . '?act=vouchers');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi tạo voucher';
                }
            }
        }
        
        render('admin/vouchers/create.php', [
            'errors' => $errors,
            'discountCodes' => $discountCodes
        ]);
    }

    /**
     * Form sửa voucher (Admin)
     */
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=vouchers');
            exit;
        }
        
        $voucher = $this->voucher->find($id);
        if (!$voucher) {
            header('Location: ' . BASE_URL . '?act=vouchers');
            exit;
        }
        
        $errors = [];
        $discountCodes = $this->discountCode->all();
        
        if (!empty($_POST)) {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'tag' => $_POST['tag'] ?? 'general',
                'code' => $_POST['code'] ?? null,
                'discount_code_id' => !empty($_POST['discount_code_id']) ? (int)$_POST['discount_code_id'] : null,
                'benefits' => isset($_POST['benefits']) ? array_filter($_POST['benefits']) : [],
                'period' => $_POST['period'] ?? null,
                'image' => $_POST['image'] ?? null,
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'status' => $_POST['status'] ?? 'active',
                'cta' => $_POST['cta'] ?? 'Đặt vé ngay',
                'cta_link' => $_POST['cta_link'] ?? null,
                'priority' => isset($_POST['priority']) ? (int)$_POST['priority'] : 0
            ];
            
            $errors = $this->voucher->validate($data, true);
            
            if (empty($errors)) {
                if ($this->voucher->update($id, $data)) {
                    header('Location: ' . BASE_URL . '?act=vouchers');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật voucher';
                }
            }
        }
        
        render('admin/vouchers/edit.php', [
            'voucher' => $voucher,
            'errors' => $errors,
            'discountCodes' => $discountCodes
        ]);
    }

    /**
     * Xóa voucher (Admin)
     */
    public function delete()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->voucher->delete($id);
        }
        
        header('Location: ' . BASE_URL . '?act=vouchers');
        exit;
    }

    /**
     * Kiểm tra voucher code (API)
     */
    public function checkVoucher()
    {
        header('Content-Type: application/json');
        
        // Lấy code từ GET hoặc POST
        $code = $_GET['code'] ?? $_POST['code'] ?? '';
        $totalAmount = isset($_GET['total_amount']) ? floatval($_GET['total_amount']) : (isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0);
        
        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã voucher']);
            exit;
        }
        
        // Kiểm tra voucher có tồn tại không
        $voucher = $this->voucher->findByCode($code);
        if (!$voucher) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã voucher không tồn tại hoặc đã hết hạn'
            ]);
            exit;
        }
        
        // Lấy discount code
        $discountCode = $this->voucher->getDiscountCodeByVoucherCode($code);
        
        if ($discountCode && isset($discountCode['discount_percent']) && $discountCode['discount_percent'] > 0) {
            // Tính toán discount amount
            $discountAmount = ($totalAmount * $discountCode['discount_percent']) / 100;
            
            // Trả về format giống như JavaScript đang mong đợi
            echo json_encode([
                'success' => true,
                'voucher' => [
                    'id' => $discountCode['id'],
                    'code' => $discountCode['code'],
                    'discount_percent' => $discountCode['discount_percent'],
                    'discount_amount' => $discountAmount
                ],
                'discount_code' => $discountCode, // Giữ lại để tương thích
                'message' => 'Áp dụng thành công! Giảm ' . $discountCode['discount_percent'] . '%'
            ]);
        } else {
            // Nếu voucher tồn tại nhưng không có discount code, thông báo cần liên kết
            echo json_encode([
                'success' => false,
                'message' => 'Voucher này chưa được liên kết với mã giảm giá. Vui lòng liên hệ admin.'
            ]);
        }
        exit;
    }
}

