<?php
// NOTIFICATION CONTROLLER - Xử lý API thông báo
// Chức năng: Lấy danh sách thông báo, đếm chưa đọc, đánh dấu đã đọc (Admin hoặc Client)
class NotificationController
{
    private $notification; // Model Notification để tương tác với database

    public function __construct()
    {
        require_once __DIR__ . '/../models/Notification.php';
        require_once __DIR__ . '/../commons/auth.php';
        $this->notification = new Notification(); // Khởi tạo Model Notification
    }

    // API lấy danh sách thông báo (AJAX) - Admin hoặc Client
    public function getNotifications()
    {
        header('Content-Type: application/json');
        
        // Kiểm tra quyền: admin hoặc user đã đăng nhập
        require_once __DIR__ . '/../commons/auth.php';
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        $isLoggedIn = isset($_SESSION['user_id']);
        
        if (!$isAdmin && !$isLoggedIn) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $userId = $isAdmin ? null : $_SESSION['user_id'];
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';
        
        if ($unreadOnly) {
            $notifications = $this->notification->getUnread($limit, $userId);
            $unreadCount = $this->notification->countUnread($userId);
        } else {
            $notifications = $this->notification->all($limit, $userId);
            $unreadCount = $this->notification->countUnread($userId);
        }
        
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
        exit;
    }

    // API lấy số thông báo chưa đọc (AJAX) - Admin hoặc Client
    public function getUnreadCount()
    {
        header('Content-Type: application/json');
        
        // Kiểm tra quyền: admin hoặc user đã đăng nhập
        require_once __DIR__ . '/../commons/auth.php';
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        $isLoggedIn = isset($_SESSION['user_id']);
        
        if (!$isAdmin && !$isLoggedIn) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $userId = $isAdmin ? null : $_SESSION['user_id'];
        $count = $this->notification->countUnread($userId);
        
        echo json_encode([
            'success' => true,
            'count' => $count
        ]);
        exit;
    }

    // API đánh dấu đã đọc 1 thông báo (AJAX) - Admin hoặc Client
    public function markAsRead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit;
        }
        
        // Kiểm tra quyền: admin hoặc user đã đăng nhập
        require_once __DIR__ . '/../commons/auth.php';
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        $isLoggedIn = isset($_SESSION['user_id']);
        
        if (!$isAdmin && !$isLoggedIn) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID notification']);
            exit;
        }
        
        $userId = $isAdmin ? null : $_SESSION['user_id'];
        $result = $this->notification->markAsRead($id, $userId);
        
        if ($result) {
            $unreadCount = $this->notification->countUnread($userId);
            echo json_encode([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể đánh dấu là đã đọc']);
        }
        exit;
    }

    /**
     * API endpoint: Đánh dấu tất cả notifications là đã đọc (Admin hoặc Client)
     */
    public function markAllAsRead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit;
        }
        
        // Kiểm tra quyền: admin hoặc user đã đăng nhập
        require_once __DIR__ . '/../commons/auth.php';
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        $isLoggedIn = isset($_SESSION['user_id']);
        
        if (!$isAdmin && !$isLoggedIn) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $userId = $isAdmin ? null : $_SESSION['user_id'];
        $result = $this->notification->markAllAsRead($userId);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể đánh dấu tất cả là đã đọc']);
        }
        exit;
    }
}

