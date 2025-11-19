<?php
class UsersController
{
    public $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Hiển thị danh sách users (Admin)
     */
    public function list()
    {
        // Kiểm tra quyền admin
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        // Lọc theo role nếu có
        $roleFilter = $_GET['role'] ?? null;
        $searchKeyword = $_GET['search'] ?? '';

        if (!empty($searchKeyword)) {
            $data = $this->user->search($searchKeyword);
        } elseif ($roleFilter) {
            $data = $this->user->getByRole($roleFilter);
        } else {
            $data = $this->user->all();
        }

        // Thống kê
        $stats = [
            'total' => $this->user->countByRole(),
            'admin' => $this->user->countByRole('admin'),
            'staff' => $this->user->countByRole('staff'),
            'customer' => $this->user->countByRole('customer')
        ];

        render('admin/users/list.php', [
            'data' => $data,
            'stats' => $stats,
            'roleFilter' => $roleFilter,
            'searchKeyword' => $searchKeyword
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
                    'total_spending' => 0.00
                ];
                $this->user->insert($data);
                header('Location: ' . BASE_URL . '?act=users');
                exit;
            }
        }

        render('admin/users/create.php', ['errors' => $errors, 'tiers' => $tiers]);
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

            if (empty(trim($_POST['role'] ?? ''))) {
                $errors['role'] = "Bạn vui lòng chọn quyền";
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
                $data = [
                    'full_name' => trim($_POST['full_name']),
                    'email' => trim($_POST['email']),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'birth_date' => trim($_POST['birth_date'] ?? ''),
                    'tier_id' => !empty($_POST['tier_id']) ? trim($_POST['tier_id']) : null,
                    'role' => trim($_POST['role']),
                    'total_spending' => floatval($_POST['total_spending'] ?? $user['total_spending'])
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

        render('admin/users/edit.php', ['user' => $user, 'errors' => $errors, 'tiers' => $tiers]);
    }

    /**
     * Xóa user (Admin)
     */
    public function delete()
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
            // Không cho phép xóa chính mình (nếu có session)
            // Có thể thêm logic kiểm tra ở đây
            $this->user->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=users');
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

