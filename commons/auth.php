<?php
/**
 * Hàm khởi động session (chỉ start một lần)
 */
function startSessionIfNotStarted()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Hàm kiểm tra đăng nhập
 */
function isLoggedIn()
{
    startSessionIfNotStarted();
    return isset($_SESSION['user_id']);
}

/**
 * Hàm lấy thông tin user hiện tại
 */
function getCurrentUser()
{
    startSessionIfNotStarted();
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    require_once __DIR__ . '/../models/User.php';
    $userModel = new User();
    return $userModel->find($_SESSION['user_id']);
}

/**
 * Hàm kiểm tra role
 */
function hasRole($role)
{
    startSessionIfNotStarted();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

/**
 * Hàm kiểm tra là admin
 */
function isAdmin()
{
    return hasRole('admin');
}

/**
 * Hàm kiểm tra là manager (quản lý)
 */
function isManager()
{
    return hasRole('manager');
}

/**
 * Hàm kiểm tra là staff (nhân viên)
 */
function isStaff()
{
    return hasRole('staff');
}

/**
 * Hàm kiểm tra là customer
 */
function isCustomer()
{
    return hasRole('customer');
}

/**
 * Hàm yêu cầu đăng nhập
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        // Lưu URL hiện tại để quay lại sau khi đăng nhập
        // Khởi động session trước
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Lấy query string từ $_GET để đảm bảo có đầy đủ tham số
        // Đây là cách đáng tin cậy nhất vì $_GET đã được PHP parse sẵn
        $queryParams = $_GET ?? [];
        
        // Xây dựng lại URL đầy đủ từ $_GET
        if (!empty($queryParams)) {
            $queryString = http_build_query($queryParams);
            $returnUrl = BASE_URL . '?' . $queryString;
        } else {
            // Nếu không có $_GET, lấy từ REQUEST_URI
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            if (!empty($requestUri)) {
                // Parse REQUEST_URI để lấy query string
                $parsed = parse_url($requestUri);
                if (isset($parsed['query']) && !empty($parsed['query'])) {
                    $returnUrl = BASE_URL . '?' . $parsed['query'];
                } else {
                    // Nếu không có query string trong REQUEST_URI, dùng BASE_URL
                    $returnUrl = BASE_URL;
                }
            } else {
                $returnUrl = BASE_URL;
            }
        }
        
        // Lưu vào session - đảm bảo chỉ lưu khi có URL hợp lệ
        if (!empty($returnUrl)) {
            $_SESSION['return_url'] = $returnUrl;
        }
        
        header('Location: ' . BASE_URL . '?act=dangnhap');
        exit;
    }
}

/**
 * Hàm yêu cầu quyền admin
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

/**
 * Hàm yêu cầu quyền admin hoặc manager hoặc staff
 */
function requireAdminOrStaff()
{
    requireLogin();
    if (!isAdmin() && !isManager() && !isStaff()) {
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

/**
 * Hàm yêu cầu quyền admin hoặc manager
 */
function requireAdminOrManager()
{
    requireLogin();
    if (!isAdmin() && !isManager()) {
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

/**
 * Hàm yêu cầu quyền staff
 */
function requireStaff()
{
    requireLogin();
    if (!isStaff()) {
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

/**
 * Lấy cinema_id của user hiện tại (áp dụng cho manager và staff)
 */
function getCurrentCinemaId()
{
    startSessionIfNotStarted();
    if (isManager() || isStaff()) {
        return $_SESSION['cinema_id'] ?? null;
    }
    return null; // Admin không bị giới hạn bởi cinema_id
}

/**
 * Kiểm tra xem user có quyền truy cập cinema này không
 */
function canAccessCinema($cinemaId)
{
    if (isAdmin()) {
        return true; // Admin có quyền truy cập tất cả
    }
    if (isManager() || isStaff()) {
        $currentCinemaId = getCurrentCinemaId();
        return $currentCinemaId && $currentCinemaId == $cinemaId;
    }
    return false;
}

/**
 * Kiểm tra quyền theo cấp bậc
 * Admin > Manager > Staff
 */
function hasPermission($permission)
{
    if (isAdmin()) {
        return true; // Admin có tất cả quyền
    }
    
    // Manager và Staff cần kiểm tra permission cụ thể
    // TODO: Implement permission checking từ database
    return false;
}

?>

