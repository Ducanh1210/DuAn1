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
 * Hàm kiểm tra là staff
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
 * Hàm yêu cầu quyền admin hoặc staff
 */
function requireAdminOrStaff()
{
    requireLogin();
    if (!isAdmin() && !isStaff()) {
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

?>

