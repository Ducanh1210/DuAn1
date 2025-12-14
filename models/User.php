<?php
// USER MODEL - Tương tác với bảng users
// Chức năng: CRUD user, tìm kiếm, lọc theo role, quản lý hạng thành viên
class User
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    // Lấy tất cả users với thông tin tier (có thể lọc theo role)
    public function all($role = null)
    {
        try {
            // Query SQL với LEFT JOIN để lấy tên hạng thành viên
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id";

            if ($role) {
                $sql .= " WHERE users.role = :role";
            }

            $sql .= " ORDER BY users.id DESC"; // Sắp xếp mới nhất trước

            $stmt = $this->conn->prepare($sql);
            if ($role) {
                $stmt->execute([':role' => $role]);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e); // Hiển thị lỗi nếu có
        }
    }

    // Lấy user theo ID
    public function find($id)
    {
        try {
            // SQL query với LEFT JOIN để lấy tên hạng thành viên
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id
                    WHERE users.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(); // Trả về 1 user hoặc null
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Tìm user theo email (dùng cho đăng nhập)
    public function findByEmail($email)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(); // Trả về 1 user hoặc null
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Tìm kiếm users theo tên hoặc email (LIKE)
    public function search($keyword)
    {
        try {
            // SQL query với LIKE để tìm kiếm trong tên hoặc email
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id
                    WHERE users.full_name LIKE :keyword 
                    OR users.email LIKE :keyword
                    ORDER BY users.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => '%' . $keyword . '%']); // %keyword% để tìm kiếm gần đúng
            return $stmt->fetchAll(); // Trả về mảng users khớp
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Lọc users theo role
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

    // Thêm user mới vào database, trả về ID của user vừa tạo
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
                status,
                total_spending,
                cinema_id
            ) VALUES (
                :full_name,
                :email,
                :password,
                :phone,
                :birth_date,
                :tier_id,
                :role,
                :status,
                :total_spending,
                :cinema_id
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
                ':status' => $data['status'] ?? 'active',
                ':total_spending' => $data['total_spending'] ?? 0.00,
                ':cinema_id' => $data['cinema_id'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Cập nhật thông tin user (merge dữ liệu mới với dữ liệu cũ)
    public function update($id, $data)
    {
        try {
            // Lấy thông tin user hiện tại để đảm bảo không mất dữ liệu
            $currentUser = $this->find($id);
            if (!$currentUser) {
                return false;
            }

            // Đảm bảo email không rỗng
            $email = $data['email'] ?? $currentUser['email'] ?? '';
            if (empty($email)) {
                throw new Exception("Email không được để trống");
            }

            // Kiểm tra xem cột address có tồn tại không
            $hasAddressColumn = false;
            try {
                $checkColumn = $this->conn->query("SHOW COLUMNS FROM users LIKE 'address'");
                $hasAddressColumn = ($checkColumn && $checkColumn->rowCount() > 0);
            } catch (Exception $e) {
                $hasAddressColumn = false;
            }

            $sql = "UPDATE users SET 
                full_name = :full_name,
                email = :email,
                phone = :phone,
                birth_date = :birth_date,
                tier_id = :tier_id,
                role = :role,
                total_spending = :total_spending,
                cinema_id = :cinema_id";

            // Cập nhật address nếu có trong data và cột tồn tại
            if (isset($data['address']) && $hasAddressColumn) {
                $sql .= ", address = :address";
            }

            // Cập nhật status nếu có
            if (isset($data['status'])) {
                $sql .= ", status = :status";
            }

            // Chỉ cập nhật password nếu có
            if (!empty($data['password'])) {
                $sql .= ", password = :password";
            }

            $sql .= " WHERE id = :id";

            $params = [
                ':id' => $id,
                ':full_name' => $data['full_name'] ?? $currentUser['full_name'],
                ':email' => $email,
                ':phone' => $data['phone'] ?? $currentUser['phone'] ?? null,
                ':birth_date' => $data['birth_date'] ?? $currentUser['birth_date'] ?? null,
                ':tier_id' => $data['tier_id'] ?? $currentUser['tier_id'] ?? null,
                ':role' => $data['role'] ?? $currentUser['role'] ?? 'customer',
                ':total_spending' => $data['total_spending'] ?? $currentUser['total_spending'] ?? 0.00,
                ':cinema_id' => $data['cinema_id'] ?? $currentUser['cinema_id'] ?? null
            ];

            // Chỉ thêm address nếu cột tồn tại
            if (isset($data['address']) && $hasAddressColumn) {
                $params[':address'] = $data['address'];
            }

            if (isset($data['status'])) {
                $params[':status'] = $data['status'];
            }

            if (!empty($data['password'])) {
                $params[':password'] = $data['password'];
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    // Khóa tài khoản user (set status = 'banned')
    public function ban($id)
    {
        try {
            $sql = "UPDATE users SET status = 'banned' WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Mở khóa tài khoản user (set status = 'active')
    public function unban($id)
    {
        try {
            $sql = "UPDATE users SET status = 'active' WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
        }
    }

    // Xóa user (DEPRECATED - không sử dụng, chỉ ban/unban)
    public function delete($id)
    {
        // Không cho phép xóa user, chỉ ban/unban
        return false;
    }

    // Lấy danh sách customer tiers
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

    // Đếm số lượng users theo role (null = đếm tất cả)
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
