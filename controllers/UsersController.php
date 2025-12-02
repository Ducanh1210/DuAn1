<?php
/**
 * USERS CONTROLLER - XỬ LÝ LOGIC QUẢN LÝ NGƯỜI DÙNG
 * 
 * CHỨC NĂNG:
 * - CRUD người dùng: danh sách, tạo, sửa, xem chi tiết
 * - Khóa/Mở khóa tài khoản: ban(), unban()
 * - Lọc và tìm kiếm: theo role, theo tên/email
 * - Thống kê: số lượng user theo từng role
 * 
 * LUỒNG CHẠY TỔNG QUÁT:
 * 1. Kiểm tra quyền admin (chỉ admin mới quản lý user)
 * 2. Lọc/tìm kiếm dữ liệu
 * 3. Lấy dữ liệu từ Model User
 * 4. Render View với dữ liệu
 */
class UsersController
{
    public $user; // Model User để tương tác với database

    public function __construct()
    {
        // Khởi tạo Model User
        $this->user = new User();
    }

    /**
     * DANH SÁCH NGƯỜI DÙNG (ADMIN)
     * 
     * LUỒNG CHẠY:
     * 1. Kiểm tra quyền admin (requireAdmin)
     * 2. Lấy tham số lọc từ URL (role, search)
     * 3. Gọi Model để lấy dữ liệu:
     *    - Nếu có search -> search() (tìm theo tên/email)
     *    - Nếu có role -> getByRole() (lọc theo role)
     *    - Nếu không -> all() (lấy tất cả)
     * 4. Lấy thống kê số lượng user theo role
     * 5. Render view với dữ liệu
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_GET: role (admin, manager, staff, customer), search (từ khóa tìm kiếm)
     * - Từ Model User: all(), search(), getByRole() -> danh sách users
     * - Từ Model User: countByRole() -> thống kê số lượng theo role
     */
    public function list()
    {
        // Kiểm tra quyền admin (chỉ admin mới quản lý user)
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        // ============================================
        // LẤY THAM SỐ LỌC TỪ URL
        // ============================================
        $roleFilter = $_GET['role'] ?? null; // Lọc theo role (admin, manager, staff, customer)
        $searchKeyword = $_GET['search'] ?? ''; // Từ khóa tìm kiếm (tên hoặc email)

        // ============================================
        // LẤY DỮ LIỆU THEO ĐIỀU KIỆN
        // ============================================
        if (!empty($searchKeyword)) {
            // Tìm kiếm theo tên hoặc email
            $data = $this->user->search($searchKeyword);
        } elseif ($roleFilter) {
            // Lọc theo role
            $data = $this->user->getByRole($roleFilter);
        } else {
            // Lấy tất cả users
            $data = $this->user->all();
        }

        // ============================================
        // THỐNG KÊ SỐ LƯỢNG USER THEO ROLE
        // ============================================
        $stats = [
            'total' => $this->user->countByRole(), // Tổng số user
            'admin' => $this->user->countByRole('admin'), // Số admin
            'manager' => $this->user->countByRole('manager'), // Số manager
            'staff' => $this->user->countByRole('staff'), // Số staff
            'customer' => $this->user->countByRole('customer') // Số customer
        ];

        // ============================================
        // RENDER VIEW
        // ============================================
        render('admin/users/list.php', [
            'data' => $data, // Danh sách users
            'stats' => $stats, // Thống kê
            'roleFilter' => $roleFilter, // Role đã chọn (để giữ giá trị trong form)
            'searchKeyword' => $searchKeyword // Từ khóa đã nhập (để giữ giá trị trong form)
        ]);
    }

    /**
     * Hiển thị form tạo user mới (Admin)
     */
    public function create()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $errors = [];
        $tiers = $this->user->getTiers();
        
        // Lấy danh sách rạp để gán cho staff
        require_once __DIR__ . '/../models/Cinema.php';
        $cinemaModel = new Cinema();
        $cinemas = $cinemaModel->all();

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
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

            if (empty(trim($_POST['role'] ?? ''))) {
                $errors['role'] = "Bạn vui lòng chọn quyền";
            } elseif ($_POST['role'] === 'admin') {
                $errors['role'] = "Không thể tạo tài khoản Admin. Chỉ có 1 Admin duy nhất trong hệ thống";
            } elseif ($_POST['role'] === 'customer') {
                $errors['role'] = "Không thể tạo tài khoản Khách hàng. Khách hàng tự đăng ký tài khoản.";
            } elseif (!in_array($_POST['role'], ['manager', 'staff'])) {
                $errors['role'] = "Quyền không hợp lệ. Chỉ có thể tạo Quản lý hoặc Nhân viên";
            }
            
            // Nếu là manager hoặc staff, bắt buộc phải chọn rạp
            if (in_array($_POST['role'], ['manager', 'staff'])) {
                if (empty(trim($_POST['cinema_id'] ?? ''))) {
                    $errors['cinema_id'] = "Quản lý/Nhân viên phải được gán cho một rạp";
                }
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

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'full_name' => trim($_POST['full_name']),
                    'email' => trim($_POST['email']),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'birth_date' => trim($_POST['birth_date'] ?? ''),
                    'tier_id' => !empty($_POST['tier_id']) ? trim($_POST['tier_id']) : null,
                    'role' => trim($_POST['role']),
                    'status' => 'active',
                    'total_spending' => 0.00,
                    'cinema_id' => ($_POST['role'] === 'staff' && !empty($_POST['cinema_id'])) ? trim($_POST['cinema_id']) : null
                ];
                $this->user->insert($data);
                header('Location: ' . BASE_URL . '?act=users');
                exit;
            }
        }

        render('admin/users/create.php', ['errors' => $errors, 'tiers' => $tiers, 'cinemas' => $cinemas]);
    }

    /**
     * Hiển thị form sửa user (Admin)
     */
    public function edit()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=users');
            exit;
        }

        $user = $this->user->find($id);
        if (!$user) {
            header('Location: ' . BASE_URL . '?act=users');
            exit;
        }

        $errors = [];
        $tiers = $this->user->getTiers();
        
        // Lấy danh sách rạp để gán cho staff
        require_once __DIR__ . '/../models/Cinema.php';
        $cinemaModel = new Cinema();
        $cinemas = $cinemaModel->all();

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['full_name'] ?? ''))) {
                $errors['full_name'] = "Bạn vui lòng nhập họ tên";
            }

            if (empty(trim($_POST['email'] ?? ''))) {
                $errors['email'] = "Bạn vui lòng nhập email";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ";
            } else {
                // Kiểm tra email đã tồn tại chưa (trừ user hiện tại)
                $existingUser = $this->user->findByEmail($_POST['email']);
                if ($existingUser && $existingUser['id'] != $id) {
                    $errors['email'] = "Email này đã được sử dụng";
                }
            }

            // Kiểm tra password nếu có thay đổi
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 6) {
                    $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự";
                }
            }

            // Giữ nguyên role hiện tại, không cho phép thay đổi
            $currentRole = $user['role'] ?? '';
            
            // Nếu là manager hoặc staff, kiểm tra cinema_id
            if (in_array($currentRole, ['manager', 'staff'])) {
                if (empty(trim($_POST['cinema_id'] ?? ''))) {
                    $errors['cinema_id'] = "Quản lý/Nhân viên phải được gán cho một rạp";
                }
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

            // Nếu không có lỗi, cập nhật user trong database
            if (empty($errors)) {
                $currentRole = $user['role'] ?? '';
                $data = [
                    'full_name' => trim($_POST['full_name']),
                    'email' => trim($_POST['email']),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'birth_date' => trim($_POST['birth_date'] ?? ''),
                    'tier_id' => !empty($_POST['tier_id']) ? trim($_POST['tier_id']) : null,
                    'role' => $currentRole, // Giữ nguyên role hiện tại
                    'total_spending' => floatval($_POST['total_spending'] ?? $user['total_spending']),
                    'cinema_id' => (in_array($currentRole, ['manager', 'staff']) && !empty($_POST['cinema_id'])) ? trim($_POST['cinema_id']) : null
                ];

                // Chỉ cập nhật password nếu có thay đổi
                if (!empty($_POST['password'])) {
                    $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $this->user->update($id, $data);
                header('Location: ' . BASE_URL . '?act=users');
                exit;
            }
        }

        render('admin/users/edit.php', ['user' => $user, 'errors' => $errors, 'tiers' => $tiers, 'cinemas' => $cinemas]);
    }

    /**
     * Ban user (Khóa tài khoản)
     */
    public function ban()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=users');
            exit;
        }

        $user = $this->user->find($id);
        if ($user) {
            // Không cho phép ban chính mình
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                header('Location: ' . BASE_URL . '?act=users&error=cannot_ban_self');
                exit;
            }
            
            $this->user->ban($id);
        }

        header('Location: ' . BASE_URL . '?act=users&success=banned');
        exit;
    }

    /**
     * Unban user (Mở khóa tài khoản)
     */
    public function unban()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=users');
            exit;
        }

        $user = $this->user->find($id);
        if ($user) {
            $this->user->unban($id);
        }

        header('Location: ' . BASE_URL . '?act=users&success=unbanned');
        exit;
    }

    /**
     * Xem chi tiết user (Admin)
     */
    public function show()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=users');
            exit;
        }

        $user = $this->user->find($id);
        if (!$user) {
            header('Location: ' . BASE_URL . '?act=users');
            exit;
        }

        render('admin/users/show.php', ['user' => $user]);
    }
}

?>

