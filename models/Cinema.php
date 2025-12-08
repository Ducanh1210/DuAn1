<?php
/**
 * CINEMA MODEL - TƯƠNG TÁC VỚI BẢNG CINEMAS
 * 
 * CHỨC NĂNG:
 * - CRUD rạp: all(), find(), insert(), update(), delete()
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi method của Model
 * 2. Model thực hiện SQL query
 * 3. Trả về dữ liệu dạng array cho Controller
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: cinemas
 */
class Cinema
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        // Kết nối database khi khởi tạo Model
        $this->conn = connectDB();
    }

    /**
     * LẤY TẤT CẢ RẠP
     * 
     * Mục đích: Lấy danh sách tất cả rạp
     * Cách hoạt động: Query SQL đơn giản
     * 
     * @return array Danh sách tất cả rạp
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM cinemas ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(); // Trả về mảng tất cả rạp
        } catch (Exception $e) {
            debug($e);
            return []; // Trả về mảng rỗng nếu có lỗi
        }
    }

    /**
     * LẤY RẠP THEO ID
     * 
     * Mục đích: Lấy thông tin chi tiết của 1 rạp
     * 
     * @param int $id ID của rạp
     * @return array|null Thông tin rạp hoặc null nếu không tìm thấy
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM cinemas WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(); // Trả về 1 rạp hoặc null
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Thêm rạp mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO cinemas (name, address) VALUES (:name, :address)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':address' => $data['address'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật rạp
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE cinemas SET name = :name, address = :address WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':address' => $data['address'] ?? null
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa rạp
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM cinemas WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }
}

?>
