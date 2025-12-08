<?php
/**
 * AUTH.PHP - FILE HELPER XÁC THỰC VÀ PHÂN QUYỀN
 * 
 * CHỨC NĂNG:
 * - Kiểm tra đăng nhập: isLoggedIn()
 * - Lấy thông tin user: getCurrentUser()
 * - Kiểm tra role: isAdmin(), isManager(), isStaff(), isCustomer()
 * - Yêu cầu đăng nhập: requireLogin()
 * - Yêu cầu quyền: requireAdmin(), requireAdminOrManager(), etc.
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi hàm helper (ví dụ: isAdmin())
 * 2. Hàm kiểm tra session có user_role tương ứng không
 * 3. Trả về true/false hoặc redirect nếu không có quyền
 * 
 * DỮ LIỆU:
 * - Lấy từ $_SESSION: user_id, user_role, user_email, user_name, cinema_id
 * - Query database: getCurrentUser() -> lấy thông tin đầy đủ từ bảng users
 */

/**
 * KHỞI ĐỘNG SESSION (CHỈ START MỘT LẦN)
 * 
 * Mục đích: Tránh lỗi "session already started" khi gọi session_start() nhiều lần
 * Cách hoạt động: Kiểm tra session_status() trước khi start
 */
function startSessionIfNotStarted()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Chỉ start nếu chưa start
    }
}

/**
 * KIỂM TRA ĐĂNG NHẬP
 * 
 * Mục đích: Kiểm tra user đã đăng nhập chưa
 * Cách hoạt động: Kiểm tra $_SESSION có user_id không
 * Trả về: true nếu đã đăng nhập, false nếu chưa
 */
function isLoggedIn()
{
    startSessionIfNotStarted();
    return isset($_SESSION['user_id']); // Kiểm tra session có user_id
}

/**
 * LẤY THÔNG TIN USER HIỆN TẠI
 * 
 * Mục đích: Lấy thông tin đầy đủ của user từ database
 * Cách hoạt động: 
 * 1. Kiểm tra session có user_id không
 * 2. Query database lấy thông tin user
 * 3. Trả về array thông tin user hoặc null
 * 
 * Dữ liệu trả về: Tất cả thông tin từ bảng users (id, email, full_name, role, etc.)
 */
function getCurrentUser()
{
    startSessionIfNotStarted();
    if (!isset($_SESSION['user_id'])) {
        return null; // Chưa đăng nhập -> trả về null
    }
    
    // Load Model User và query database
    require_once __DIR__ . '/../models/User.php';
    $userModel = new User();
    return $userModel->find($_SESSION['user_id']); // Trả về thông tin user từ database
}

/**
 * KIỂM TRA ROLE
 * 
 * Mục đích: Kiểm tra user có role cụ thể không
 * Cách hoạt động: So sánh $_SESSION['user_role'] với role truyền vào
 * Trả về: true nếu đúng role, false nếu không
 */
function hasRole($role)
{
    startSessionIfNotStarted();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

/**
 * KIỂM TRA LÀ ADMIN
 * 
 * Mục đích: Kiểm tra user có phải admin không
 * Cách hoạt động: Gọi hasRole('admin')
 * Sử dụng: Kiểm tra quyền truy cập trang admin, menu admin
 */
function isAdmin()
{
    return hasRole('admin');
}

/**
 * KIỂM TRA LÀ MANAGER (QUẢN LÝ)
 * 
 * Mục đích: Kiểm tra user có phải manager không
 * Cách hoạt động: Gọi hasRole('manager')
 * Sử dụng: Kiểm tra quyền quản lý rạp được gán
 */
function isManager()
{
    return hasRole('manager');
}

/**
 * KIỂM TRA LÀ STAFF (NHÂN VIÊN)
 * 
 * Mục đích: Kiểm tra user có phải staff không
 * Cách hoạt động: Gọi hasRole('staff')
 * Sử dụng: Kiểm tra quyền bán vé
 */
function isStaff()
{
    return hasRole('staff');
}

/**
 * KIỂM TRA LÀ CUSTOMER
 * 
 * Mục đích: Kiểm tra user có phải customer không
 * Cách hoạt động: Gọi hasRole('customer')
 * Sử dụng: Kiểm tra quyền truy cập trang client
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
 * YÊU CẦU QUYỀN ADMIN
 * 
 * Mục đích: Bảo vệ trang chỉ dành cho admin
 * Cách hoạt động:
 * 1. Kiểm tra đăng nhập (requireLogin)
 * 2. Kiểm tra có phải admin không
 * 3. Nếu không phải admin -> redirect về trang chủ
 * 
 * Sử dụng: Đặt ở đầu method Controller chỉ dành cho admin
 * Ví dụ: requireAdmin(); // Chỉ admin mới vào được
 */
function requireAdmin()
{
    requireLogin(); // Phải đăng nhập trước
    if (!isAdmin()) {
        // Không phải admin -> redirect về trang chủ
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

/**
 * YÊU CẦU QUYỀN ADMIN HOẶC MANAGER HOẶC STAFF
 * 
 * Mục đích: Bảo vệ trang dành cho admin/manager/staff
 * Cách hoạt động: Kiểm tra có ít nhất 1 trong 3 role không
 * 
 * Sử dụng: Trang quản lý chung (dashboard, danh sách phim, etc.)
 */
function requireAdminOrStaff()
{
    requireLogin();
    // Nếu không phải admin, manager, hoặc staff -> redirect
    if (!isAdmin() && !isManager() && !isStaff()) {
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}

/**
 * YÊU CẦU QUYỀN ADMIN HOẶC MANAGER
 * 
 * Mục đích: Bảo vệ trang chỉ dành cho admin và manager
 * Cách hoạt động: Kiểm tra có phải admin hoặc manager không
 * 
 * Sử dụng: Trang quản lý rạp, phòng, lịch chiếu (staff không có quyền)
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
 * YÊU CẦU QUYỀN STAFF
 * 
 * Mục đích: Bảo vệ trang chỉ dành cho staff
 * Cách hoạt động: Kiểm tra có phải staff không
 * 
 * Sử dụng: Trang bán vé (chỉ staff mới bán được)
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
 * LẤY CINEMA_ID CỦA USER HIỆN TẠI
 * 
 * Mục đích: Lấy ID rạp mà manager/staff được gán
 * Cách hoạt động:
 * 1. Kiểm tra có phải manager hoặc staff không
 * 2. Lấy cinema_id từ session
 * 3. Admin trả về null (không bị giới hạn)
 * 
 * Sử dụng: Lọc dữ liệu theo rạp (manager/staff chỉ thấy dữ liệu của rạp mình)
 * 
 * Dữ liệu: Lấy từ $_SESSION['cinema_id'] (được set khi đăng nhập)
 */
function getCurrentCinemaId()
{
    startSessionIfNotStarted();
    if (isManager() || isStaff()) {
        // Manager và Staff có cinema_id được gán
        return $_SESSION['cinema_id'] ?? null;
    }
    return null; // Admin không bị giới hạn bởi cinema_id (có thể xem tất cả rạp)
}

/**
 * KIỂM TRA QUYỀN TRUY CẬP CINEMA
 * 
 * Mục đích: Kiểm tra user có quyền xem/sửa dữ liệu của rạp này không
 * Cách hoạt động:
 * 1. Admin -> có quyền truy cập tất cả (return true)
 * 2. Manager/Staff -> chỉ truy cập được rạp được gán (cinema_id khớp)
 * 3. Customer -> không có quyền (return false)
 * 
 * @param int $cinemaId ID rạp cần kiểm tra
 * @return bool true nếu có quyền, false nếu không
 */
function canAccessCinema($cinemaId)
{
    if (isAdmin()) {
        return true; // Admin có quyền truy cập tất cả
    }
    if (isManager() || isStaff()) {
        // Manager/Staff chỉ truy cập được rạp được gán
        $currentCinemaId = getCurrentCinemaId();
        return $currentCinemaId && $currentCinemaId == $cinemaId;
    }
    return false; // Customer không có quyền
}

/**
 * KIỂM TRA QUYỀN THEO CẤP BẬC
 * 
 * Mục đích: Kiểm tra user có quyền cụ thể không (theo hệ thống phân quyền)
 * Cách hoạt động:
 * 1. Admin -> có tất cả quyền (return true)
 * 2. Manager/Staff -> kiểm tra permission từ database (chưa implement)
 * 
 * @param string $permission Tên quyền cần kiểm tra (ví dụ: 'manage_movies')
 * @return bool true nếu có quyền, false nếu không
 * 
 * TODO: Implement permission checking từ database (bảng permissions)
 */
function hasPermission($permission)
{
    if (isAdmin()) {
        return true; // Admin có tất cả quyền
    }
    
    // Manager và Staff cần kiểm tra permission cụ thể từ database
    // TODO: Implement permission checking từ database
    return false;
}

?>

