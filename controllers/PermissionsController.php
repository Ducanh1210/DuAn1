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
        
        // Lấy tất cả permissions được nhóm theo module
        $permissionsGrouped = $this->permission->getGroupedByModule();
        
        // Lấy permissions của từng role
        $adminPermissions = $this->permission->getPermissionIdsByRole('admin');
        $staffPermissions = $this->permission->getPermissionIdsByRole('staff');
        $customerPermissions = $this->permission->getPermissionIdsByRole('customer');

        render('admin/permissions/list.php', [
            'permissionsGrouped' => $permissionsGrouped,
            'adminPermissions' => $adminPermissions,
            'staffPermissions' => $staffPermissions,
            'customerPermissions' => $customerPermissions
        ]);
    }

    /**
     * Phân quyền cho role
     */
    public function assign()
    {
        // Chỉ admin mới được phân quyền
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $errors = [];
        $role = $_GET['role'] ?? null;

        if (!in_array($role, ['admin', 'staff', 'customer'])) {
            header('Location: ' . BASE_URL . '?act=permissions');
            exit;
        }

        // Không cho phép chỉnh sửa quyền của admin (admin luôn có tất cả quyền)
        if ($role === 'admin') {
            header('Location: ' . BASE_URL . '?act=permissions&error=admin_cannot_edit');
            exit;
        }

        // Không cho phép phân quyền cho customer (customer chỉ mua hàng)
        if ($role === 'customer') {
            header('Location: ' . BASE_URL . '?act=permissions&error=customer_no_permissions');
            exit;
        }

        // Lấy tất cả permissions
        $permissionsGrouped = $this->permission->getGroupedByModule();
        
        // Lấy permissions hiện tại của role
        $currentPermissions = $this->permission->getPermissionIdsByRole($role);

        // Xử lý form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $permissionIds = $_POST['permissions'] ?? [];
            
            // Gán permissions cho staff
            $this->permission->assignToRole($role, $permissionIds);
            header('Location: ' . BASE_URL . '?act=permissions&success=1');
            exit;
        }

        $roleLabels = [
            'admin' => 'Admin',
            'staff' => 'Nhân viên',
            'customer' => 'Khách hàng'
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

