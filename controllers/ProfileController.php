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

        // Tính lại total_spending từ các booking đã thanh toán (để đảm bảo dữ liệu chính xác)
        $actualSpending = $this->calculateTotalSpending($userId);
        if ($actualSpending !== floatval($user['total_spending'] ?? 0)) {
            // Cập nhật total_spending và tier_id nếu khác
            $tierId = $this->getTierBySpending($actualSpending);
            $this->user->update($userId, [
                'total_spending' => $actualSpending,
                'tier_id' => $tierId
            ]);
            // Lấy lại thông tin user sau khi cập nhật
            $user = $this->user->find($userId);
        }

        // Lấy thông tin tier
        $tier = null;
        if ($user['tier_id']) {
            $tier = $this->getTier($user['tier_id']);
        }

        // Lấy tất cả tiers để hiển thị thông tin
        $allTiers = $this->getAllTiers();

        // Lấy lịch sử điểm thưởng (từ bookings)
        $rewardHistory = $this->getRewardHistory($userId);
        
        // Tính tổng điểm từ lịch sử tích điểm (tổng tất cả points_earned)
        $rewardPoints = 0;
        foreach ($rewardHistory as $reward) {
            $rewardPoints += (int)($reward['points_earned'] ?? 0);
        }
        
        // Tính điểm cần để lên hạng tiếp theo dựa trên total_spending
        // (vì tier dựa trên tổng chi tiêu, không phải điểm)
        $nextTier = $this->getNextTier($user['total_spending']);
        $pointsToNextTier = $nextTier ? ($nextTier['spending_min'] - $user['total_spending']) / 1000 : 0;

        // Lấy lịch sử mua vé
        $bookingResult = $this->booking->getByUser($userId);
        $bookingHistory = $bookingResult['data'] ?? [];

        // Kiểm tra đã bình luận cho từng booking
        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $userComments = $commentModel->getByUser($userId);
        $commentedMovies = [];
        foreach ($userComments as $comment) {
            $commentedMovies[$comment['movie_id']] = true;
        }

        // Thêm thông tin đã bình luận vào booking history
        foreach ($bookingHistory as &$booking) {
            $booking['has_commented'] = isset($commentedMovies[$booking['movie_id'] ?? null]);
        }
        unset($booking);

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
                // Lấy thông tin user hiện tại để giữ nguyên email và các trường khác
                $currentUser = $this->user->find($userId);
                
                if (!$currentUser) {
                    header('Location: ' . BASE_URL . '?act=profile&tab=account');
                    exit;
                }
                
                // Đảm bảo email không rỗng
                $email = $currentUser['email'] ?? '';
                if (empty($email)) {
                    $errors['email'] = "Email không được để trống";
                    header('Location: ' . BASE_URL . '?act=profile&tab=account');
                    exit;
                }
                
                // Cập nhật thông tin (giữ nguyên email và các trường không thay đổi)
                $data = [
                    'full_name' => $fullName,
                    'email' => $email, // Giữ nguyên email từ database
                    'phone' => $_POST['phone'] ?? null,
                    'birth_date' => !empty($_POST['birth_date']) ? $_POST['birth_date'] : null,
                    'tier_id' => $currentUser['tier_id'] ?? null, // Giữ nguyên tier_id
                    'role' => $currentUser['role'] ?? 'customer', // Giữ nguyên role
                    'total_spending' => $currentUser['total_spending'] ?? 0.00, // Giữ nguyên total_spending
                    'status' => $currentUser['status'] ?? 'active' // Giữ nguyên status
                ];

                $result = $this->user->update($userId, $data);
                
                if (!$result) {
                    header('Location: ' . BASE_URL . '?act=profile&tab=account&error=1');
                    exit;
                }
                
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

    /**
     * Tính tổng chi tiêu từ các booking đã thanh toán
     */
    private function calculateTotalSpending($userId)
    {
        try {
            $sql = "SELECT SUM(final_amount) as total 
                    FROM bookings 
                    WHERE user_id = :user_id 
                    AND status IN ('paid', 'confirmed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch();
            return floatval($result['total'] ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tier_id phù hợp dựa trên total_spending
     */
    private function getTierBySpending($totalSpending)
    {
        try {
            // Lấy tier cao nhất mà user đủ điều kiện
            $sql = "SELECT id FROM customer_tiers 
                    WHERE spending_min <= :spending 
                    AND (spending_max IS NULL OR spending_max >= :spending)
                    ORDER BY spending_min DESC 
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':spending' => $totalSpending]);
            $tier = $stmt->fetch();
            
            return $tier ? $tier['id'] : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Hiển thị trang bình luận/đánh giá phim
     */
    public function reviewMovie()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?act=dangnhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $bookingId = $_GET['booking_id'] ?? null;
        $movieId = $_GET['movie_id'] ?? null;

        if (!$bookingId || !$movieId) {
            header('Location: ' . BASE_URL . '?act=profile&tab=bookings');
            exit;
        }

        // Kiểm tra booking thuộc về user này
        $booking = $this->booking->find($bookingId);
        if (!$booking || $booking['user_id'] != $userId) {
            header('Location: ' . BASE_URL . '?act=profile&tab=bookings');
            exit;
        }

        // Kiểm tra booking đã thanh toán chưa
        if (!in_array($booking['status'] ?? '', ['paid', 'confirmed'])) {
            header('Location: ' . BASE_URL . '?act=profile&tab=bookings');
            exit;
        }

        // Lấy thông tin phim
        require_once __DIR__ . '/../models/Movie.php';
        $movieModel = new Movie();
        $movie = $movieModel->find($movieId);

        if (!$movie) {
            header('Location: ' . BASE_URL . '?act=profile&tab=bookings');
            exit;
        }

        // Kiểm tra đã bình luận chưa
        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $existingComment = null;
        $userComments = $commentModel->getByUser($userId);
        foreach ($userComments as $comment) {
            if ($comment['movie_id'] == $movieId) {
                $existingComment = $comment;
                break;
            }
        }

        renderClient('client/review.php', [
            'booking' => $booking,
            'movie' => $movie,
            'existingComment' => $existingComment
        ], 'Đánh giá phim');
    }

    /**
     * Xử lý submit bình luận/đánh giá
     */
    public function submitReview()
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
            $bookingId = $_POST['booking_id'] ?? null;
            $movieId = $_POST['movie_id'] ?? null;
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
            $content = trim($_POST['content'] ?? '');

            // Validation
            if (!$bookingId || !$movieId) {
                $errors[] = 'Thông tin không hợp lệ';
            }

            // Kiểm tra booking thuộc về user này
            $booking = $this->booking->find($bookingId);
            if (!$booking || $booking['user_id'] != $userId) {
                $errors[] = 'Bạn không có quyền đánh giá đơn hàng này';
            }

            if ($rating === null || $rating < 1 || $rating > 5) {
                $errors[] = 'Vui lòng chọn đánh giá từ 1 đến 5 sao';
            }

            if (empty($content)) {
                $errors[] = 'Vui lòng nhập nội dung bình luận';
            } elseif (strlen($content) < 10) {
                $errors[] = 'Nội dung bình luận phải có ít nhất 10 ký tự';
            }

            if (empty($errors)) {
                require_once __DIR__ . '/../models/Comment.php';
                $commentModel = new Comment();

                // Kiểm tra đã bình luận chưa
                $userComments = $commentModel->getByUser($userId);
                $existingComment = null;
                foreach ($userComments as $comment) {
                    if ($comment['movie_id'] == $movieId) {
                        $existingComment = $comment;
                        break;
                    }
                }

                if ($existingComment) {
                    // Cập nhật bình luận cũ
                    $commentModel->update($existingComment['id'], [
                        'rating' => $rating,
                        'content' => $content
                    ]);
                } else {
                    // Tạo bình luận mới
                    $commentModel->insert([
                        'user_id' => $userId,
                        'movie_id' => $movieId,
                        'rating' => $rating,
                        'content' => $content
                    ]);
                }

                header('Location: ' . BASE_URL . '?act=profile&tab=bookings&review_success=1');
                exit;
            }
        }

        // Nếu có lỗi, quay lại trang bình luận
        $movieId = $_GET['movie_id'] ?? $_POST['movie_id'] ?? null;
        $bookingId = $_GET['booking_id'] ?? $_POST['booking_id'] ?? null;
        if ($movieId && $bookingId) {
            header('Location: ' . BASE_URL . '?act=review-movie&booking_id=' . $bookingId . '&movie_id=' . $movieId . '&error=' . urlencode(implode(', ', $errors)));
        } else {
            header('Location: ' . BASE_URL . '?act=profile&tab=bookings');
        }
        exit;
    }
}

?>

