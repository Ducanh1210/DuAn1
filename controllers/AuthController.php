<?php
// AUTH CONTROLLER - Xử lý logic xác thực người dùng
// Chức năng: Đăng ký, đăng nhập, đăng xuất, quên mật khẩu
class AuthController
{
    public $user; // Model User để query database

    public function __construct()
    {
        $this->user = new User(); // Khởi tạo Model User
    }

    // Đăng ký tài khoản mới
    public function register()
    {
        $errors = []; // Mảng lưu lỗi validation

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kiểm tra form đã submit chưa
            if (empty(trim($_POST['full_name'] ?? ''))) { // Kiểm tra họ tên không rỗng
                $errors['full_name'] = "Bạn vui lòng nhập họ tên";
            }

            if (empty(trim($_POST['email'] ?? ''))) { // Kiểm tra email không rỗng
                $errors['email'] = "Bạn vui lòng nhập email";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // Kiểm tra format email
                $errors['email'] = "Email không hợp lệ";
            } else {
                $existingUser = $this->user->findByEmail($_POST['email']); // Tìm email trong DB
                if ($existingUser) { // Nếu email đã tồn tại
                    $errors['email'] = "Email này đã được sử dụng";
                }
            }

            if (empty(trim($_POST['password'] ?? ''))) { // Kiểm tra mật khẩu không rỗng
                $errors['password'] = "Bạn vui lòng nhập mật khẩu";
            } elseif (strlen($_POST['password']) < 6) { // Kiểm tra mật khẩu tối thiểu 6 ký tự
                $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự";
            }

            if (empty(trim($_POST['confirm_password'] ?? ''))) { // Kiểm tra xác nhận mật khẩu
                $errors['confirm_password'] = "Bạn vui lòng xác nhận mật khẩu";
            } elseif ($_POST['password'] !== $_POST['confirm_password']) { // Kiểm tra 2 mật khẩu khớp
                $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
            }

            if (!empty($_POST['phone'])) { // Nếu có số điện thoại
                if (!preg_match('/^[0-9]{10,11}$/', $_POST['phone'])) { // Kiểm tra format 10-11 số
                    $errors['phone'] = "Số điện thoại không hợp lệ (10-11 số)";
                }
            }

            if (!empty($_POST['birth_date'])) { // Nếu có ngày sinh
                if (strtotime($_POST['birth_date']) === false) { // Kiểm tra ngày hợp lệ
                    $errors['birth_date'] = 'Ngày sinh không hợp lệ';
                } elseif (strtotime($_POST['birth_date']) > strtotime('today')) { // Không được là tương lai
                    $errors['birth_date'] = 'Ngày sinh không thể là tương lai';
                }
            }

            if (empty($errors)) { // Nếu không có lỗi
                $data = [
                    'full_name' => trim($_POST['full_name']), // Lưu họ tên vào DB
                    'email' => trim($_POST['email']), // Lưu email vào DB
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash mật khẩu trước khi lưu
                    'phone' => trim($_POST['phone'] ?? ''), // Lưu số điện thoại (nếu có)
                    'birth_date' => trim($_POST['birth_date'] ?? ''), // Lưu ngày sinh (nếu có)
                    'tier_id' => null, // Chưa có hạng thành viên
                    'role' => 'customer', // Mặc định là khách hàng
                    'total_spending' => 0.00 // Chi tiêu ban đầu = 0
                ];
                
                $userId = $this->user->insert($data); // Insert vào bảng users, trả về ID mới

                header('Location: ' . BASE_URL . '?act=dangnhap&registered=1&email=' . urlencode($data['email'])); // Chuyển về trang đăng nhập
                exit;
            }
        }

        require_once __DIR__ . '/../views/client/dangky.php'; // Hiển thị form đăng ký
        exit;
    }

    // Đăng nhập
    public function login()
    {
        $errors = []; // Mảng lưu lỗi

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kiểm tra form đã submit chưa
            $email = trim($_POST['email'] ?? ''); // Lấy email từ form
            $password = $_POST['password'] ?? ''; // Lấy mật khẩu từ form

            if (empty($email)) { // Kiểm tra email không rỗng
                $errors['login'] = "Vui lòng nhập email";
            }
            if (empty($password)) { // Kiểm tra mật khẩu không rỗng
                $errors['login'] = empty($errors) ? "Vui lòng nhập mật khẩu" : "Vui lòng nhập email và mật khẩu";
            }

            if (empty($errors)) { // Nếu không có lỗi
                $user = $this->user->findByEmail($email); // Tìm user trong DB theo email

                if ($user && password_verify($password, $user['password'])) { // Kiểm tra user tồn tại và mật khẩu đúng
                    $status = $user['status'] ?? 'active'; // Lấy trạng thái tài khoản
                    if ($status === 'banned') { // Nếu tài khoản bị khóa
                        $errors['login'] = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.";
                    } else {
                        if (session_status() === PHP_SESSION_NONE) { // Kiểm tra session chưa khởi động
                            session_start(); // Khởi động session
                        }
                        
                        $_SESSION['user_id'] = $user['id']; // Lưu ID user vào session
                        $_SESSION['user_email'] = $user['email']; // Lưu email vào session
                        $_SESSION['user_name'] = $user['full_name']; // Lưu tên vào session
                        $_SESSION['user_role'] = $user['role'] ?? 'customer'; // Lưu role vào session
                        $_SESSION['cinema_id'] = $user['cinema_id'] ?? null; // Lưu cinema_id vào session
                        
                        if (in_array($_SESSION['user_role'], ['admin', 'manager', 'staff'])) { // Nếu là admin/manager/staff
                            if (isset($_SESSION['return_url'])) { // Xóa return_url nếu có
                                unset($_SESSION['return_url']);
                            }
                            header('Location: ' . BASE_URL . '?act=dashboard'); // Chuyển về dashboard
                            exit;
                        }
                        
                        if (isset($_SESSION['return_url']) && !empty($_SESSION['return_url'])) { // Nếu có return_url (đang đặt vé)
                            $returnUrl = trim($_SESSION['return_url']);
                            unset($_SESSION['return_url']); // Xóa return_url
                            header('Location: ' . $returnUrl); // Quay lại trang đặt vé
                            exit;
                        }
                        
                        header('Location: ' . BASE_URL . '?act=trangchu'); // Chuyển về trang chủ
                        exit;
                    }
                } else {
                    $errors['login'] = "Email hoặc mật khẩu không đúng";
                }
            }
        }

        require_once __DIR__ . '/../views/client/dangnhap.php'; // Hiển thị form đăng nhập
        exit;
    }

    // Quên mật khẩu
    public function forgotPassword()
    {
        $errors = []; // Mảng lưu lỗi

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kiểm tra form đã submit chưa
            if (empty(trim($_POST['email'] ?? ''))) { // Kiểm tra email không rỗng
                $errors['login'] = "Vui lòng nhập email";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // Kiểm tra format email
                $errors['login'] = "Email không hợp lệ";
            } else {
                $user = $this->user->findByEmail($_POST['email']); // Tìm email trong DB
                if (!$user) { // Nếu email không tồn tại
                    $errors['login'] = "Email này không tồn tại trong hệ thống";
                }
            }

            if (empty(trim($_POST['password'] ?? ''))) { // Kiểm tra mật khẩu mới không rỗng
                $errors['login'] = empty($errors) ? "Vui lòng nhập mật khẩu mới" : "Vui lòng nhập email và mật khẩu mới";
            } elseif (strlen($_POST['password']) < 6) { // Kiểm tra mật khẩu tối thiểu 6 ký tự
                if (empty($errors)) {
                    $errors['login'] = "Mật khẩu phải có ít nhất 6 ký tự";
                }
            }

            if (empty(trim($_POST['confirm_password'] ?? ''))) { // Kiểm tra xác nhận mật khẩu
                if (empty($errors)) {
                    $errors['login'] = "Vui lòng xác nhận mật khẩu";
                }
            } elseif (isset($_POST['password']) && $_POST['password'] !== $_POST['confirm_password']) { // Kiểm tra 2 mật khẩu khớp
                $errors['login'] = empty($errors) ? "Mật khẩu xác nhận không khớp" : $errors['login'] . ". Mật khẩu xác nhận không khớp";
            }

            if (empty($errors)) { // Nếu không có lỗi
                $user = $this->user->findByEmail($_POST['email']); // Tìm user
                if ($user) {
                    $data = [
                        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT) // Hash mật khẩu mới
                    ];
                    $this->user->update($user['id'], $data); // Cập nhật mật khẩu vào DB
                    
                    header('Location: ' . BASE_URL . '?act=dangnhap&reset=1&email=' . urlencode($_POST['email'])); // Chuyển về trang đăng nhập
                    exit;
                }
            }
        }

        require_once __DIR__ . '/../views/client/quenmatkhau.php'; // Hiển thị form quên mật khẩu
        exit;
    }

    // Đăng xuất
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) { // Kiểm tra session chưa khởi động
            session_start(); // Khởi động session
        }
        
        session_destroy(); // Xóa toàn bộ session (user_id, user_role, etc.)
        
        header('Location: ' . BASE_URL . '?act=trangchu'); // Chuyển về trang chủ
        exit;
    }
}
