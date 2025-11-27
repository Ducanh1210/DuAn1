<?php
/**
 * GENRE MODEL - TƯƠNG TÁC VỚI BẢNG MOVIE_GENRES
 * 
 * CHỨC NĂNG:
 * - CRUD thể loại: all(), find(), insert(), update(), delete()
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi method của Model
 * 2. Model thực hiện SQL query
 * 3. Trả về dữ liệu dạng array cho Controller
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: movie_genres
 */
class Genre
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        // Kết nối database khi khởi tạo Model
        $this->conn = connectDB();
    }

    /**
     * LẤY TẤT CẢ THỂ LOẠI PHIM
     * 
     * Mục đích: Lấy danh sách tất cả thể loại
     * Cách hoạt động: Query SQL đơn giản
     * 
     * @return array Danh sách tất cả thể loại
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM movie_genres ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(); // Trả về mảng tất cả thể loại
        } catch (Exception $e) {
            debug($e);
            return []; // Trả về mảng rỗng nếu có lỗi
        }
    }

    /**
     * LẤY THỂ LOẠI THEO ID
     * 
     * Mục đích: Lấy thông tin chi tiết của 1 thể loại
     * 
     * @param int $id ID của thể loại
     * @return array|null Thông tin thể loại hoặc null nếu không tìm thấy
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM movie_genres WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(); // Trả về 1 thể loại hoặc null
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Thêm thể loại mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO movie_genres (name, description) VALUES (:name, :description)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật thể loại
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE movie_genres SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa thể loại
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM movie_genres WHERE id = :id";
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

