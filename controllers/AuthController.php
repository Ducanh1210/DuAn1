<?php
/**
 * AUTH CONTROLLER - XỬ LÝ XÁC THỰC NGƯỜI DÙNG
 * 
 * CHỨC NĂNG:
 * - Đăng ký tài khoản mới (customer)
 * - Đăng nhập (kiểm tra email/password, tạo session)
 * - Đăng xuất (xóa session)
 * 
 * LUỒNG CHẠY ĐĂNG NHẬP:
 * 1. User nhập email/password
 * 2. Kiểm tra email có tồn tại trong DB không
 * 3. Verify password với password_hash trong DB
 * 4. Kiểm tra tài khoản có bị khóa không
 * 5. Tạo session (user_id, user_role, user_name, etc.)
 * 6. Redirect theo role:
 *    - Admin/Manager -> dashboard
 *    - Staff -> trang bán vé
 *    - Customer -> trang chủ hoặc return_url
 */
class AuthController
{
    public $user; // Model User để tương tác với database

    public function __construct()
    {
        // Khởi tạo Model User để có thể query database
        $this->user = new User();
    }

    /**
     * ĐĂNG KÝ TÀI KHOẢN MỚI
     * 
     * LUỒNG CHẠY:
     * 1. Hiển thị form đăng ký (GET request)
     * 2. Nhận dữ liệu từ form (POST request)
     * 3. Validate dữ liệu (email, password, phone, etc.)
     * 4. Kiểm tra email đã tồn tại chưa
     * 5. Hash password bằng password_hash()
     * 6. Insert vào database với role = 'customer'
     * 7. Redirect về trang đăng nhập
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_POST: full_name, email, password, phone, birth_date
     * - Từ Model User: kiểm tra email đã tồn tại
     * - Lưu vào database: users table
     */
    public function register()
    {
        $errors = []; // Mảng lưu lỗi validation

        // Kiểm tra nếu là POST request (form đã submit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ============================================
            // VALIDATION - KIỂM TRA DỮ LIỆU ĐẦU VÀO
            // ============================================
            if (empty(trim($_POST['full_name'] ?? ''))) {
                $errors['full_name'] = "Bạn vui lòng nhập họ tên";
            }

            if (empty(trim($_POST['email'] ?? ''))) {
                $errors['email'] = "Bạn vui lòng nhập email";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ";
            } else {
                // Kiểm tra email đã tồn tại chưa
                $existingUser = $this->user->findByEmail($_POST['email']);
                if ($existingUser) {
                    $errors['email'] = "Email này đã được sử dụng";
                }
            }

            if (empty(trim($_POST['password'] ?? ''))) {
                $errors['password'] = "Bạn vui lòng nhập mật khẩu";
            } elseif (strlen($_POST['password']) < 6) {
                $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự";
            }

            if (empty(trim($_POST['confirm_password'] ?? ''))) {
                $errors['confirm_password'] = "Bạn vui lòng xác nhận mật khẩu";
            } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
            }

            // Kiểm tra phone nếu có
            if (!empty($_POST['phone'])) {
                if (!preg_match('/^[0-9]{10,11}$/', $_POST['phone'])) {
                    $errors['phone'] = "Số điện thoại không hợp lệ (10-11 số)";
                }
            }

            // Kiểm tra birth_date nếu có
            if (!empty($_POST['birth_date'])) {
                if (strtotime($_POST['birth_date']) === false) {
                    $errors['birth_date'] = 'Ngày sinh không hợp lệ';
                } elseif (strtotime($_POST['birth_date']) > strtotime('today')) {
                    $errors['birth_date'] = 'Ngày sinh không thể là tương lai';
                }
            }

            // ============================================
            // TẠO TÀI KHOẢN - NẾU KHÔNG CÓ LỖI
            // ============================================
            if (empty($errors)) {
                // Chuẩn bị dữ liệu để insert vào database
                $data = [
                    'full_name' => trim($_POST['full_name']), // Tên đầy đủ
                    'email' => trim($_POST['email']), // Email (đã validate)
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash password để bảo mật
                    'phone' => trim($_POST['phone'] ?? ''), // Số điện thoại (optional)
                    'birth_date' => trim($_POST['birth_date'] ?? ''), // Ngày sinh (optional)
                    'tier_id' => null, // Chưa có hạng thành viên
                    'role' => 'customer', // Luôn là customer khi đăng ký (admin/manager/staff tạo từ admin panel)
                    'total_spending' => 0.00 // Tổng chi tiêu ban đầu = 0
                ];
                
                // Insert vào database, trả về user_id mới tạo
                $userId = $this->user->insert($data);

                // Sau khi đăng ký thành công, chuyển về trang đăng nhập
                // Truyền tham số registered=1 và email để hiển thị thông báo thành công
                header('Location: ' . BASE_URL . '?act=dangnhap&registered=1&email=' . urlencode($data['email']));
                exit;
            }
        }

        // ============================================
        // HIỂN THỊ FORM ĐĂNG KÝ (GET REQUEST)
        // ============================================
        // Render view đăng ký (client) - hiển thị form và lỗi nếu có
        require_once __DIR__ . '/../views/client/dangky.php';
        exit;
    }

    /**
     * ĐĂNG NHẬP
     * 
     * LUỒNG CHẠY:
     * 1. Hiển thị form đăng nhập (GET request)
     * 2. Nhận email/password từ form (POST request)
     * 3. Validate email/password không rỗng
     * 4. Tìm user trong database theo email
     * 5. Verify password với password_hash trong DB (dùng password_verify)
     * 6. Kiểm tra tài khoản có bị khóa không (status = 'banned')
     * 7. Tạo session lưu thông tin user
     * 8. Redirect theo role:
     *    - Admin -> dashboard
     *    - Manager -> dashboard
     *    - Staff -> trang bán vé
     *    - Customer -> trang chủ hoặc return_url (nếu đang ở giữa quá trình đặt vé)
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_POST: email, password
     * - Từ Model User: findByEmail() -> lấy thông tin user từ database
     * - Lưu vào Session: user_id, user_email, user_name, user_role, cinema_id
     */
    public function login()
    {
        $errors = []; // Mảng lưu lỗi

        // Kiểm tra nếu là POST request (form đã submit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // ============================================
            // VALIDATION - KIỂM TRA DỮ LIỆU ĐẦU VÀO
            // ============================================
            if (empty($email)) {
                $errors['email'] = "Bạn vui lòng nhập email";
            }

            if (empty($password)) {
                $errors['password'] = "Bạn vui lòng nhập mật khẩu";
            }

            // ============================================
            // XÁC THỰC NGƯỜI DÙNG
            // ============================================
            if (empty($errors)) {
                // Tìm user trong database theo email
                $user = $this->user->findByEmail($email);

                // Kiểm tra user tồn tại và password đúng
                // password_verify() so sánh password plain text với password_hash trong DB
                if ($user && password_verify($password, $user['password'])) {
                    // Kiểm tra trạng thái tài khoản (active, banned)
                    $status = $user['status'] ?? 'active';
                    if ($status === 'banned') {
                        $errors['login'] = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.";
                    } else {
                        // ============================================
                        // ĐĂNG NHẬP THÀNH CÔNG - TẠO SESSION
                        // ============================================
                        // Khởi động session nếu chưa có
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        
                        // Lưu thông tin user vào session để sử dụng ở các trang khác
                        $_SESSION['user_id'] = $user['id']; // ID user để query database
                        $_SESSION['user_email'] = $user['email']; // Email để hiển thị
                        $_SESSION['user_name'] = $user['full_name']; // Tên để hiển thị
                        $_SESSION['user_role'] = $user['role'] ?? 'customer'; // Role để phân quyền (admin, manager, staff, customer)
                        $_SESSION['cinema_id'] = $user['cinema_id'] ?? null; // Cinema ID của staff/manager để lọc dữ liệu
                        
                        // ============================================
                        // REDIRECT THEO ROLE
                        // ============================================
                        // Nếu là admin, manager hoặc staff, redirect về trang quản lý tương ứng
                        if (in_array($_SESSION['user_role'], ['admin', 'manager', 'staff'])) {
                            // Xóa return_url nếu có (vì manager/staff không cần quay lại trang client)
                            if (isset($_SESSION['return_url'])) {
                                unset($_SESSION['return_url']);
                            }
                            
                            // Redirect theo role
                            if ($_SESSION['user_role'] === 'admin') {
                                // Admin vào dashboard để quản lý toàn hệ thống
                                header('Location: ' . BASE_URL . '?act=dashboard');
                            } elseif ($_SESSION['user_role'] === 'manager') {
                                // Manager vào dashboard để quản lý rạp được gán
                                header('Location: ' . BASE_URL . '?act=dashboard');
                            } elseif ($_SESSION['user_role'] === 'staff') {
                                // Staff vào trang bán vé (chức năng chính)
                                header('Location: ' . BASE_URL . '?act=banve');
                            }
                            exit;
                        }
                        
                        // ============================================
                        // REDIRECT CHO CUSTOMER
                        // ============================================
                        // Nếu là customer, kiểm tra return_url nếu có
                        // return_url được lưu khi user chưa đăng nhập nhưng đang ở giữa quá trình đặt vé
                        if (isset($_SESSION['return_url']) && !empty($_SESSION['return_url'])) {
                            $returnUrl = trim($_SESSION['return_url']);
                            // Xóa return_url khỏi session trước khi redirect
                            unset($_SESSION['return_url']);
                            // Redirect về URL đã lưu (ví dụ: trang chọn ghế, thanh toán)
                            header('Location: ' . $returnUrl);
                            exit;
                        }
                        
                        // Nếu không có return_url, redirect về trang chủ
                        header('Location: ' . BASE_URL . '?act=trangchu');
                        exit;
                    }
                } else {
                    // Email hoặc password không đúng
                    $errors['login'] = "Email hoặc mật khẩu không đúng";
                }
            }
        }

        // ============================================
        // HIỂN THỊ FORM ĐĂNG NHẬP (GET REQUEST)
        // ============================================
        // Render view đăng nhập (client) - hiển thị form và lỗi nếu có
        require_once __DIR__ . '/../views/client/dangnhap.php';
        exit;
    }

    /**
     * ĐĂNG XUẤT
     * 
     * LUỒNG CHẠY:
     * 1. Khởi động session nếu chưa có
     * 2. Xóa toàn bộ session (session_destroy)
     * 3. Redirect về trang chủ
     * 
     * DỮ LIỆU:
     * - Xóa tất cả dữ liệu trong session (user_id, user_role, etc.)
     */
    public function logout()
    {
        // Khởi động session nếu chưa có (để có thể destroy)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Xóa toàn bộ session - user sẽ bị đăng xuất
        session_destroy();
        
        // Chuyển về trang chủ
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}
