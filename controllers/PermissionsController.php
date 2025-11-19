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
        $errors = [];
        $role = $_GET['role'] ?? null;

        if (!in_array($role, ['admin', 'staff', 'customer'])) {
            header('Location: ' . BASE_URL . '?act=permissions');
            exit;
        }

        // Lấy tất cả permissions
        $permissionsGrouped = $this->permission->getGroupedByModule();
        
        // Lấy permissions hiện tại của role
        $currentPermissions = $this->permission->getPermissionIdsByRole($role);

        // Xử lý form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $permissionIds = $_POST['permissions'] ?? [];
            
            // Validate
            if (empty($permissionIds) && $role === 'admin') {
                $errors['permissions'] = "Admin phải có ít nhất một quyền";
            }

            if (empty($errors)) {
                // Gán permissions
                $this->permission->assignToRole($role, $permissionIds);
                header('Location: ' . BASE_URL . '?act=permissions&success=1');
                exit;
            }
        }

        $roleLabels = [
            'admin' => 'Admin',
            'staff' => 'Staff',
            'customer' => 'Customer'
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

