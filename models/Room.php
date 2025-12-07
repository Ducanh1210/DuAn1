<?php
/**
 * ROOM MODEL - TƯƠNG TÁC VỚI BẢNG ROOMS
 * 
 * CHỨC NĂNG:
 * - CRUD phòng: all(), find(), insert(), update(), delete()
 * - Phân trang: paginate(), paginateByCinema()
 * - Lọc phòng: theo cinema_id
 * 
 * LUỒNG CHẠY:
 * 1. Controller gọi method của Model
 * 2. Model thực hiện SQL query với JOIN để lấy thông tin rạp
 * 3. Trả về dữ liệu dạng array cho Controller
 * 
 * DỮ LIỆU:
 * - Lấy từ bảng: rooms
 * - JOIN với: cinemas (để lấy tên rạp)
 */
class Room
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        // Kết nối database khi khởi tạo Model
        $this->conn = connectDB();
    }

    /**
     * LẤY TẤT CẢ PHÒNG VỚI THÔNG TIN RẠP
     * 
     * Mục đích: Lấy danh sách tất cả phòng chiếu
     * Cách hoạt động: Query SQL với LEFT JOIN để lấy tên rạp
     * 
     * DỮ LIỆU TRẢ VỀ:
     * - Tất cả cột từ bảng rooms
     * - cinema_name từ bảng cinemas (LEFT JOIN)
     */
    public function all()
    {
        try {
            // SQL query với LEFT JOIN để lấy tên rạp
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    ORDER BY rooms.cinema_id ASC, rooms.id ASC"; // Sắp xếp theo rạp, sau đó theo ID
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(); // Trả về mảng tất cả phòng
        } catch (Exception $e) {
            debug($e);
            return []; // Trả về mảng rỗng nếu có lỗi
        }
    }

    /**
     * Lấy phòng với phân trang, tìm kiếm và lọc
     * 
     * @param int $page Số trang
     * @param int $perPage Số phòng mỗi trang
     * @param int|null $cinemaId Lọc theo rạp (null = tất cả)
     * @param string|null $searchKeyword Tìm kiếm theo tên phòng, mã phòng (null = không tìm)
     * @return array Dữ liệu phân trang
     */
    public function paginate($page = 1, $perPage = 10, $cinemaId = null, $searchKeyword = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereConditions = [];
            $params = [];
            
            if ($cinemaId) {
                $whereConditions[] = "rooms.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            if ($searchKeyword) {
                $whereConditions[] = "(rooms.name LIKE :search OR rooms.room_code LIKE :search OR cinemas.name LIKE :search)";
                $params[':search'] = '%' . $searchKeyword . '%';
            }
            
            $whereClause = "";
            if (!empty($whereConditions)) {
                $whereClause = "WHERE " . implode(" AND ", $whereConditions);
            }
            
                // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total 
                        FROM rooms
                        LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                        " . $whereClause;
            $countStmt = $this->conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    " . $whereClause . "
                    ORDER BY rooms.cinema_id ASC, rooms.id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
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
     * Lấy phòng với phân trang theo cinema_id, có hỗ trợ tìm kiếm
     * 
     * @param int $cinemaId ID của rạp
     * @param int $page Số trang
     * @param int $perPage Số phòng mỗi trang
     * @param string|null $searchKeyword Tìm kiếm theo tên phòng, mã phòng (null = không tìm)
     * @return array Dữ liệu phân trang
     */
    public function paginateByCinema($cinemaId, $page = 1, $perPage = 10, $searchKeyword = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereConditions = ["rooms.cinema_id = :cinema_id"];
            $params = [':cinema_id' => $cinemaId];
            
            if ($searchKeyword) {
                $whereConditions[] = "(rooms.name LIKE :search OR rooms.room_code LIKE :search)";
                $params[':search'] = '%' . $searchKeyword . '%';
            }
            
            $whereClause = "WHERE " . implode(" AND ", $whereConditions);
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total 
                        FROM rooms
                        LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                        " . $whereClause;
            $countStmt = $this->conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT rooms.*, 
                    cinemas.name AS cinema_name
                    FROM rooms
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    " . $whereClause . "
                    ORDER BY rooms.id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
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

    /**
     * Cập nhật số ghế của phòng dựa trên số ghế thực tế trong bảng seats
     */
    public function updateSeatCount($room_id)
    {
        try {
            // Đếm số ghế thực tế trong bảng seats
            $sql = "SELECT COUNT(*) as total FROM seats WHERE room_id = :room_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':room_id' => $room_id]);
            $result = $stmt->fetch();
            $actualSeatCount = $result['total'] ?? 0;
            
            // Cập nhật seat_count trong bảng rooms
            $updateSql = "UPDATE rooms SET seat_count = :seat_count WHERE id = :room_id";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->execute([
                ':seat_count' => $actualSeatCount,
                ':room_id' => $room_id
            ]);
            
            return $actualSeatCount;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Lấy số ghế thực tế từ bảng seats
     */
    public function getActualSeatCount($room_id)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM seats WHERE room_id = :room_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':room_id' => $room_id]);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            debug($e);
            return 0;
        }
    }
}

?>

