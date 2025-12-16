<?php
// CINEMA MODEL - Tương tác với bảng cinemas
// Chức năng: CRUD rạp
class Cinema
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    // Lấy tất cả rạp
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

    // Lấy rạp theo ID
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

    // Thêm rạp mới vào database
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

    // Cập nhật thông tin rạp
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

    // Kiểm tra rạp có phòng chiếu không
    public function hasRooms($id)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM rooms WHERE cinema_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    // Kiểm tra rạp có lịch chiếu không (qua phòng chiếu)
    public function hasShowtimes($id)
    {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM showtimes s
                    INNER JOIN rooms r ON s.room_id = r.id
                    WHERE r.cinema_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    // Kiểm tra rạp có phim đang chiếu không (qua lịch chiếu)
    public function hasActiveMovies($id)
    {
        try {
            $today = date('Y-m-d');
            $sql = "SELECT COUNT(*) as count 
                    FROM showtimes s
                    INNER JOIN rooms r ON s.room_id = r.id
                    INNER JOIN movies m ON s.movie_id = m.id
                    WHERE r.cinema_id = :id 
                    AND s.show_date >= :today
                    AND m.status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':today' => $today
            ]);
            $result = $stmt->fetch();
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    // Xóa rạp
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
