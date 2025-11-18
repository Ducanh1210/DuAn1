<?php
class ProfileController
{
    public $user;
    public $booking;
    public $conn;

    public function __construct()
    {
        $this->user = new User();
        $this->booking = new Booking();
        $this->conn = connectDB();
    }

    /**
     * Hiển thị trang thông tin cá nhân với tabs
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?act=dangnhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->user->find($userId);
        
        if (!$user) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy thông tin tier
        $tier = null;
        if ($user['tier_id']) {
            $tier = $this->getTier($user['tier_id']);
        }

        // Lấy tất cả tiers để hiển thị thông tin
        $allTiers = $this->getAllTiers();

        // Tính điểm thưởng từ total_spending (1 điểm = 1000 VNĐ)
        $rewardPoints = floor($user['total_spending'] / 1000);
        
        // Tính điểm cần để lên hạng tiếp theo
        $nextTier = $this->getNextTier($user['total_spending']);
        $pointsToNextTier = $nextTier ? ($nextTier['spending_min'] - $user['total_spending']) / 1000 : 0;

        // Lấy lịch sử điểm thưởng (từ bookings)
        $rewardHistory = $this->getRewardHistory($userId);

        // Lấy lịch sử mua vé
        $bookingResult = $this->booking->getByUser($userId);
        $bookingHistory = $bookingResult['data'] ?? [];

        // Tab hiện tại
        $tab = $_GET['tab'] ?? 'account';

        renderClient('client/profile.php', [
            'user' => $user,
            'tier' => $tier,
            'allTiers' => $allTiers,
            'rewardPoints' => $rewardPoints,
            'nextTier' => $nextTier,
            'pointsToNextTier' => $pointsToNextTier,
            'rewardHistory' => $rewardHistory,
            'bookingHistory' => $bookingHistory,
            'tab' => $tab
        ], 'Thông tin cá nhân');
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?act=dangnhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation
            if (empty(trim($_POST['full_name'] ?? ''))) {
                $errors['full_name'] = "Vui lòng nhập họ tên";
            }

            // Lấy full_name từ form
            $fullName = trim($_POST['full_name'] ?? '');

            if (empty(trim($_POST['phone'] ?? ''))) {
                $errors['phone'] = "Vui lòng nhập số điện thoại";
            } elseif (!preg_match('/^[0-9]{10,11}$/', $_POST['phone'])) {
                $errors['phone'] = "Số điện thoại không hợp lệ";
            }

            if (empty($errors)) {
                // Cập nhật thông tin
                $data = [
                    'full_name' => $fullName,
                    'phone' => $_POST['phone'] ?? null,
                    'address' => $_POST['address'] ?? null,
                    'birth_date' => !empty($_POST['birth_date']) ? $_POST['birth_date'] : null
                ];

                $this->user->update($userId, $data);
                
                // Cập nhật session
                $_SESSION['user_name'] = $fullName;
                
                header('Location: ' . BASE_URL . '?act=profile&tab=account&success=1');
                exit;
            }
        }

        // Nếu có lỗi, quay lại trang profile
        header('Location: ' . BASE_URL . '?act=profile&tab=account');
        exit;
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?act=dangnhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Lấy thông tin user
            $user = $this->user->find($userId);

            if (empty($currentPassword)) {
                $errors['current_password'] = "Vui lòng nhập mật khẩu hiện tại";
            } elseif (!password_verify($currentPassword, $user['password'])) {
                $errors['current_password'] = "Mật khẩu hiện tại không đúng";
            }

            if (empty($newPassword)) {
                $errors['new_password'] = "Vui lòng nhập mật khẩu mới";
            } elseif (strlen($newPassword) < 6) {
                $errors['new_password'] = "Mật khẩu mới phải có ít nhất 6 ký tự";
            }

            if (empty($confirmPassword)) {
                $errors['confirm_password'] = "Vui lòng xác nhận mật khẩu mới";
            } elseif ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
            }

            if (empty($errors)) {
                // Cập nhật mật khẩu
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->user->update($userId, ['password' => $hashedPassword]);
                
                header('Location: ' . BASE_URL . '?act=profile&tab=account&password_success=1');
                exit;
            }
        }

        header('Location: ' . BASE_URL . '?act=profile&tab=account');
        exit;
    }

    /**
     * Lấy thông tin tier
     */
    private function getTier($tierId)
    {
        try {
            $sql = "SELECT * FROM customer_tiers WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $tierId]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Lấy tất cả tiers
     */
    private function getAllTiers()
    {
        try {
            $sql = "SELECT * FROM customer_tiers ORDER BY spending_min ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy tier tiếp theo dựa trên total_spending
     */
    private function getNextTier($totalSpending)
    {
        try {
            $sql = "SELECT * FROM customer_tiers 
                    WHERE spending_min > :spending 
                    ORDER BY spending_min ASC 
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':spending' => $totalSpending]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Lấy lịch sử điểm thưởng từ bookings
     */
    private function getRewardHistory($userId)
    {
        try {
            $sql = "SELECT 
                        b.id,
                        b.booking_code,
                        b.booking_date,
                        b.final_amount,
                        m.title AS movie_title,
                        m.image AS movie_image,
                        FLOOR(b.final_amount / 1000) AS points_earned
                    FROM bookings b
                    LEFT JOIN showtimes s ON b.showtime_id = s.id
                    LEFT JOIN movies m ON s.movie_id = m.id
                    WHERE b.user_id = :user_id 
                    AND b.status IN ('paid', 'confirmed')
                    ORDER BY b.booking_date DESC
                    LIMIT 50";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

?>

