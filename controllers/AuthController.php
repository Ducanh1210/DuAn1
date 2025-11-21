<?php
class AuthController
{
    public $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Hiển thị form đăng ký (Client)
     */
    public function register()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation
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

            // Nếu không có lỗi, tạo tài khoản với role = 'customer'
            if (empty($errors)) {
                $data = [
                    'full_name' => trim($_POST['full_name']),
                    'email' => trim($_POST['email']),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'birth_date' => trim($_POST['birth_date'] ?? ''),
                    'tier_id' => null,
                    'role' => 'customer', // Luôn là customer khi đăng ký
                    'total_spending' => 0.00
                ];
                $userId = $this->user->insert($data);

                // Sau khi đăng ký thành công, chuyển về trang đăng nhập
                header('Location: ' . BASE_URL . '?act=dangnhap&registered=1&email=' . urlencode($data['email']));
                exit;
            }
        }

        // Render view đăng ký (client)
        require_once __DIR__ . '/../views/client/dangky.php';
        exit;
    }

    /**
     * Hiển thị form đăng nhập (Client)
     */
    public function login()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email)) {
                $errors['email'] = "Bạn vui lòng nhập email";
            }

            if (empty($password)) {
                $errors['password'] = "Bạn vui lòng nhập mật khẩu";
            }

            if (empty($errors)) {
                $user = $this->user->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    // Kiểm tra trạng thái tài khoản
                    $status = $user['status'] ?? 'active';
                    if ($status === 'banned') {
                        $errors['login'] = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.";
                    } else {
                        // Đăng nhập thành công
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_name'] = $user['full_name'];
                        $_SESSION['user_role'] = $user['role'] ?? 'customer';
                        
                        // Kiểm tra return_url nếu có (từ trang chọn ghế, thanh toán, v.v.)
                        if (isset($_SESSION['return_url']) && !empty($_SESSION['return_url'])) {
                            $returnUrl = trim($_SESSION['return_url']);
                            // Xóa return_url khỏi session trước khi redirect
                            unset($_SESSION['return_url']);
                            // Redirect về URL đã lưu
                            header('Location: ' . $returnUrl);
                            exit;
                        }
                        
                        // Nếu không có return_url, redirect theo role
                        if (in_array($_SESSION['user_role'], ['admin', 'staff'])) {
                            header('Location: ' . BASE_URL . '?act=dashboard');
                        } else {
                            header('Location: ' . BASE_URL . '?act=trangchu');
                        }
                        exit;
                    }
                } else {
                    $errors['login'] = "Email hoặc mật khẩu không đúng";
                }
            }
        }

        // Render view đăng nhập (client)
        require_once __DIR__ . '/../views/client/dangnhap.php';
        exit;
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ' . BASE_URL . '?act=trangchu');
        exit;
    }
}
