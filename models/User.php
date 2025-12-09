<?php
/**
 * USER MODEL - TƯƠNG TÁC VỚI BẢNG USERS
 * 
 * CHỨC NĂNG:
 * - CRUD user: all(), find(), insert(), update(), delete()
 * - Tìm kiếm: search() (theo tên/email), findByEmail()
 * - Lọc: getByRole() (theo role)
 * - Thống kê: countByRole()
 * - Quản lý hạng thành viên: getTiers()
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi method của Model
 * 2. Model thực hiện SQL query
 * 3. Trả về dữ liệu dạng array cho Controller
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: users
 * - JOIN với: customer_tiers (để lấy tên hạng thành viên)
 */
class User
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        // Kết nối database khi khởi tạo Model
        $this->conn = connectDB();
    }

    /**
     * LẤY TẤT CẢ USERS VỚI THÔNG TIN TIER
     * 
     * Mục đích: Lấy danh sách tất cả users hoặc lọc theo role
     * Cách hoạt động:
     * 1. Query SQL với LEFT JOIN để lấy tên hạng thành viên
     * 2. Nếu có role -> thêm WHERE để lọc theo role
     * 3. Sắp xếp theo ID giảm dần (mới nhất trước)
     * 
     * @param string|null $role Role cần lọc (admin, manager, staff, customer) hoặc null để lấy tất cả
     * @return array Danh sách users
     */
    public function all($role = null)
    {
        try {
            // SQL query với LEFT JOIN để lấy tên hạng thành viên
            $sql = "SELECT users.*, 
                    customer_tiers.name AS tier_name
                    FROM users
                    LEFT JOIN customer_tiers ON users.tier_id = customer_tiers.id";

            // Thêm điều kiện lọc theo role nếu có
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
            return $stmt->fetchAll(); // Trả về mảng tất cả users
        } catch (Exception $e) {
            debug($e); // Hiển thị lỗi nếu có
        }
    }

    /**
     * LẤY USER THEO ID
     * 
     * Mục đích: Lấy thông tin chi tiết của 1 user
     * Cách hoạt động: Query với WHERE id = :id
     * 
     * @param int $id ID của user
     * @return array|null Thông tin user hoặc null nếu không tìm thấy
     */
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

    /**
     * TÌM USER THEO EMAIL
     * 
     * Mục đích: Tìm user bằng email (dùng cho đăng nhập)
     * Cách hoạt động: Query với WHERE email = :email
     * 
     * @param string $email Email của user
     * @return array|null Thông tin user hoặc null nếu không tìm thấy
     */
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

    /**
     * TÌM KIẾM USERS THEO TÊN HOẶC EMAIL
     * 
     * Mục đích: Tìm kiếm user theo từ khóa (tên hoặc email)
     * Cách hoạt động: Query với LIKE để tìm kiếm gần đúng
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @return array Danh sách users khớp với từ khóa
     */
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

    /**
     * LỌC USERS THEO ROLE
     * 
     * Mục đích: Lấy danh sách users có role cụ thể
     * Cách hoạt động: Query với WHERE role = :role
     * 
     * @param string $role Role cần lọc (admin, manager, staff, customer)
     * @return array Danh sách users có role đó
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
     * THÊM USER MỚI VÀO DATABASE
     * 
     * Mục đích: Insert user mới vào bảng users
     * Cách hoạt động:
     * 1. Nhận data array từ Controller
     * 2. Thực hiện SQL INSERT với các trường cần thiết
     * 3. Trả về ID của user vừa tạo (lastInsertId)
     * 
     * Luồng chạy:
     * Controller -> User->insert(['full_name' => '...', 'email' => '...', ...])
     * -> SQL INSERT INTO users (...)
     * -> Trả về user_id mới tạo
     * 
     * @param array $data Dữ liệu user (full_name, email, password, role, etc.)
     * @return int|false ID của user vừa tạo hoặc false nếu lỗi
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

    /**
     * CẬP NHẬT THÔNG TIN USER
     * 
     * Mục đích: Update thông tin user trong database
     * Cách hoạt động:
     * 1. Lấy thông tin user hiện tại từ database
     * 2. Merge dữ liệu mới với dữ liệu cũ (giữ lại dữ liệu cũ nếu không có mới)
     * 3. Thực hiện SQL UPDATE
     * 4. Trả về true/false
     * 
     * Luồng chạy:
     * Controller -> User->update($id, ['full_name' => '...', 'email' => '...'])
     * -> SQL UPDATE users SET ... WHERE id = :id
     * -> Trả về true nếu thành công
     * 
     * @param int $id ID của user cần update
     * @param array $data Dữ liệu mới (chỉ cần truyền các trường muốn thay đổi)
     * @return bool true nếu thành công, false nếu lỗi
     */
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

    /**
     * Ban user (khóa tài khoản)
     */
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

    /**
     * Unban user (mở khóa tài khoản)
     */
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

    /**
     * Xóa user (DEPRECATED - không sử dụng nữa)
     */
    public function delete($id)
    {
        // Không cho phép xóa user, chỉ ban/unban
        return false;
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
