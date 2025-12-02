<?php
class PermissionsController
{
    public $permission;

    public function __construct()
    {
        $this->permission = new Permission();
    }

    /**
     * Hiển thị danh sách phân quyền
     */
    public function list()
    {
        // Chỉ admin mới được vào trang phân quyền
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Cinema.php';
        $userModel = new User();
        $cinemaModel = new Cinema();
        
        // Lấy danh sách Manager và Staff
        $managers = $userModel->getByRole('manager');
        $staffs = $userModel->getByRole('staff');
        
        // Lấy thông tin rạp cho manager
        foreach ($managers as &$manager) {
            if (!empty($manager['cinema_id'])) {
                $cinema = $cinemaModel->find($manager['cinema_id']);
                $manager['cinema_name'] = $cinema['name'] ?? 'N/A';
            } else {
                $manager['cinema_name'] = 'Chưa gán rạp';
            }
        }
        
        // Lấy permissions của manager và staff role
        $managerPermissions = $this->permission->getPermissionIdsByRole('manager');
        $staffPermissions = $this->permission->getPermissionIdsByRole('staff');
        $managerPermissionCount = count($managerPermissions);
        $staffPermissionCount = count($staffPermissions);

        render('admin/permissions/list.php', [
            'managers' => $managers,
            'staffs' => $staffs,
            'managerPermissionCount' => $managerPermissionCount,
            'staffPermissionCount' => $staffPermissionCount
        ]);
    }

    /**
     * Phân quyền cho role (Manager hoặc Staff)
     */
    public function assign()
    {
        // Chỉ admin mới được phân quyền
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $errors = [];
        $role = $_GET['role'] ?? null;

        // Chỉ cho phép phân quyền cho manager hoặc staff
        if (!in_array($role, ['manager', 'staff'])) {
            header('Location: ' . BASE_URL . '?act=permissions&error=invalid_role');
            exit;
        }

        // Lấy tất cả permissions
        $permissionsGrouped = $this->permission->getGroupedByModule();
        
        // Lấy permissions hiện tại của role
        $currentPermissions = $this->permission->getPermissionIdsByRole($role);

        // Xử lý form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $permissionIds = $_POST['permissions'] ?? [];
            
            // Gán permissions cho role
            $this->permission->assignToRole($role, $permissionIds);
            header('Location: ' . BASE_URL . '?act=permissions&success=1');
            exit;
        }

        $roleLabels = [
            'manager' => 'Quản lý',
            'staff' => 'Nhân viên'
        ];

        render('admin/permissions/assign.php', [
            'role' => $role,
            'roleLabel' => $roleLabels[$role] ?? ucfirst($role),
            'permissionsGrouped' => $permissionsGrouped,
            'currentPermissions' => $currentPermissions,
            'errors' => $errors
        ]);
    }
}

?>

