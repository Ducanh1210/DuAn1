<?php
class User
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả users với thông tin tier
     */
    public function all($role = null)
    {
        try {
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id";
            
            if ($role) {
                $sql .= " WHERE users.role = :role";
            }
            
            $sql .= " ORDER BY users.id DESC";
            
            $stmt = $this->conn->prepare($sql);
            if ($role) {
                $stmt->execute([':role' => $role]);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy user theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id
                    WHERE users.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Tìm user theo email
     */
    public function findByEmail($email)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Tìm kiếm users theo tên hoặc email
     */
    public function search($keyword)
    {
        try {
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id
                    WHERE users.full_name LIKE :keyword 
                    OR users.email LIKE :keyword
                    ORDER BY users.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => '%' . $keyword . '%']);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lọc users theo role
     */
    public function getByRole($role)
    {
        try {
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id
                    WHERE users.role = :role
                    ORDER BY users.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':role' => $role]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Thêm user mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO users (
                full_name,
                email,
                password,
                phone,
                birth_date,
                tier_id,
                role,
                total_spending
            ) VALUES (
                :full_name,
                :email,
                :password,
                :phone,
                :birth_date,
                :tier_id,
                :role,
                :total_spending
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':full_name' => $data['full_name'],
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':phone' => $data['phone'] ?? null,
                ':birth_date' => $data['birth_date'] ?? null,
                ':tier_id' => $data['tier_id'] ?? null,
                ':role' => $data['role'] ?? 'customer',
                ':total_spending' => $data['total_spending'] ?? 0.00
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Cập nhật user
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE users SET 
                full_name = :full_name,
                email = :email,
                phone = :phone,
                birth_date = :birth_date,
                tier_id = :tier_id,
                role = :role,
                total_spending = :total_spending";
            
            // Chỉ cập nhật password nếu có
            if (!empty($data['password'])) {
                $sql .= ", password = :password";
            }
            
            $sql .= " WHERE id = :id";
            
            $params = [
                ':id' => $id,
                ':full_name' => $data['full_name'],
                ':email' => $data['email'],
                ':phone' => $data['phone'] ?? null,
                ':birth_date' => $data['birth_date'] ?? null,
                ':tier_id' => $data['tier_id'] ?? null,
                ':role' => $data['role'] ?? 'customer',
                ':total_spending' => $data['total_spending'] ?? 0.00
            ];
            
            if (!empty($data['password'])) {
                $params[':password'] = $data['password'];
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Xóa user
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Lấy danh sách customer tiers
     */
    public function getTiers()
    {
        try {
            $sql = "SELECT * FROM customer_tiers ORDER BY name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
        }
    }

    /**
     * Đếm số lượng users theo role
     */
    public function countByRole($role = null)
    {
        try {
            if ($role) {
                $sql = "SELECT COUNT(*) as count FROM users WHERE role = :role";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':role' => $role]);
            } else {
                $sql = "SELECT COUNT(*) as count FROM users";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
            }
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}

?>

