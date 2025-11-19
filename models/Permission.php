<?php
class Permission
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả permissions
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM permissions ORDER BY module, name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy permission theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM permissions WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy permissions theo module
     */
    public function getByModule($module)
    {
        try {
            $sql = "SELECT * FROM permissions WHERE module = :module ORDER BY name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':module' => $module]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy tất cả permissions được nhóm theo module
     */
    public function getGroupedByModule()
    {
        try {
            $sql = "SELECT * FROM permissions ORDER BY module, name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $permissions = $stmt->fetchAll();
            
            $grouped = [];
            foreach ($permissions as $perm) {
                $module = $perm['module'] ?? 'other';
                if (!isset($grouped[$module])) {
                    $grouped[$module] = [];
                }
                $grouped[$module][] = $perm;
            }
            
            return $grouped;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy permissions của một role
     */
    public function getByRole($role)
    {
        try {
            $sql = "SELECT p.* 
                    FROM permissions p
                    INNER JOIN role_permissions rp ON p.id = rp.permission_id
                    WHERE rp.role = :role
                    ORDER BY p.module, p.name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':role' => $role]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy danh sách permission IDs của một role
     */
    public function getPermissionIdsByRole($role)
    {
        try {
            $sql = "SELECT permission_id FROM role_permissions WHERE role = :role";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':role' => $role]);
            $result = $stmt->fetchAll();
            return array_column($result, 'permission_id');
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Gán permissions cho role
     */
    public function assignToRole($role, $permissionIds)
    {
        try {
            // Xóa tất cả permissions cũ của role
            $sql = "DELETE FROM role_permissions WHERE role = :role";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':role' => $role]);

            // Thêm permissions mới
            if (!empty($permissionIds)) {
                $sql = "INSERT INTO role_permissions (role, permission_id) VALUES (:role, :permission_id)";
                $stmt = $this->conn->prepare($sql);
                foreach ($permissionIds as $permId) {
                    $stmt->execute([
                        ':role' => $role,
                        ':permission_id' => $permId
                    ]);
                }
            }
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Kiểm tra role có permission không
     */
    public function hasPermission($role, $permissionName)
    {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM role_permissions rp
                    INNER JOIN permissions p ON rp.permission_id = p.id
                    WHERE rp.role = :role AND p.name = :permission_name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':role' => $role,
                ':permission_name' => $permissionName
            ]);
            $result = $stmt->fetch();
            return ($result['count'] > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Lấy danh sách modules
     */
    public function getModules()
    {
        try {
            $sql = "SELECT DISTINCT module FROM permissions WHERE module IS NOT NULL ORDER BY module ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
}

?>

