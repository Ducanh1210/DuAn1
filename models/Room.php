<?php
class Room
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả phòng với thông tin rạp
     */
    public function all()
    {
        try {
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    ORDER BY rooms.cinema_id ASC, rooms.id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy phòng với phân trang
     */
    public function paginate($page = 1, $perPage = 10)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM rooms";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    ORDER BY rooms.cinema_id ASC, rooms.id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (Exception $e) {
            debug($e);
            return [
                'data' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0
            ];
        }
    }

    /**
     * Lấy phòng theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE rooms.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Lấy phòng theo rạp
     */
    public function getByCinema($cinema_id)
    {
        try {
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE rooms.cinema_id = :cinema_id
                    ORDER BY rooms.id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':cinema_id' => $cinema_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Thêm phòng mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO rooms (cinema_id, room_code, name, seat_count) 
                    VALUES (:cinema_id, :room_code, :name, :seat_count)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':room_code' => $data['room_code'] ?? null,
                ':name' => $data['name'] ?? null,
                ':seat_count' => $data['seat_count'] ?? 0
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật phòng
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE rooms SET 
                    cinema_id = :cinema_id,
                    room_code = :room_code,
                    name = :name,
                    seat_count = :seat_count
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':room_code' => $data['room_code'] ?? null,
                ':name' => $data['name'] ?? null,
                ':seat_count' => $data['seat_count'] ?? 0
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa phòng
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM rooms WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Kiểm tra phòng có đang được sử dụng trong showtimes không
     */
    public function hasShowtimes($id)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM showtimes WHERE room_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }
}

?>

