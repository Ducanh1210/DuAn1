<?php
class Seat
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả ghế với thông tin phòng
     */
    public function all()
    {
        try {
            $sql = "SELECT seats.*, 
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM seats
                    LEFT JOIN rooms ON seats.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    ORDER BY seats.room_id ASC, seats.row_label ASC, seats.seat_number ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy ghế với phân trang
     */
    public function paginate($page = 1, $perPage = 20, $roomId = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereClause = "";
            $params = [];
            
            if ($roomId) {
                $whereClause = "WHERE seats.room_id = :room_id";
                $params[':room_id'] = $roomId;
            }
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM seats $whereClause";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang
            $sql = "SELECT seats.*, 
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM seats
                    LEFT JOIN rooms ON seats.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    $whereClause
                    ORDER BY seats.room_id ASC, seats.row_label ASC, seats.seat_number ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            
            // Bind params
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
     * Lấy ghế theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT seats.*, 
                    rooms.name AS room_name,
                    rooms.room_code AS room_code,
                    cinemas.name AS cinema_name
                    FROM seats
                    LEFT JOIN rooms ON seats.room_id = rooms.id
                    LEFT JOIN cinemas ON rooms.cinema_id = cinemas.id
                    WHERE seats.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Lấy ghế theo phòng
     */
    public function getByRoom($room_id)
    {
        try {
            $sql = "SELECT seats.* 
                    FROM seats
                    WHERE seats.room_id = :room_id
                    ORDER BY seats.row_label ASC, seats.seat_number ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':room_id' => $room_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy sơ đồ ghế theo phòng (nhóm theo hàng)
     */
    public function getSeatMapByRoom($room_id)
    {
        try {
            $seats = $this->getByRoom($room_id);
            
            // Nhóm ghế theo hàng
            $seatMap = [];
            foreach ($seats as $seat) {
                $row = $seat['row_label'];
                if (!isset($seatMap[$row])) {
                    $seatMap[$row] = [];
                }
                $seatMap[$row][] = $seat;
            }
            
            // Sắp xếp các hàng
            ksort($seatMap);
            
            // Sắp xếp ghế trong mỗi hàng theo seat_number để đảm bảo thứ tự đúng
            foreach ($seatMap as $row => $rowSeats) {
                usort($seatMap[$row], function($a, $b) {
                    return ($a['seat_number'] ?? 0) - ($b['seat_number'] ?? 0);
                });
            }
            
            return $seatMap;
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Thêm ghế mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO seats (room_id, row_label, seat_number, seat_type, extra_price, status) 
                    VALUES (:room_id, :row_label, :seat_number, :seat_type, :extra_price, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':room_id' => $data['room_id'] ?? null,
                ':row_label' => $data['row_label'] ?? null,
                ':seat_number' => $data['seat_number'] ?? null,
                ':seat_type' => $data['seat_type'] ?? 'normal',
                ':extra_price' => $data['extra_price'] ?? 0,
                ':status' => $data['status'] ?? 'available'
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Thêm nhiều ghế cùng lúc (tự động tạo sơ đồ ghế)
     */
    public function insertBulk($room_id, $rows, $seatsPerRow, $seatType = 'normal', $extraPrice = 0, $maxCapacity = null)
    {
        try {
            $this->conn->beginTransaction();
            
            $rowLabels = range('A', 'Z');
            $inserted = 0;
            $targetSeats = $rows * $seatsPerRow;
            
            // Nếu có maxCapacity, đảm bảo không tạo quá capacity
            if ($maxCapacity !== null && $maxCapacity > 0) {
                $targetSeats = min($targetSeats, $maxCapacity);
            }
            
            for ($i = 0; $i < $rows && $inserted < $targetSeats; $i++) {
                $rowLabel = $rowLabels[$i] ?? chr(65 + $i); // A, B, C...
                
                // Tính số ghế cần tạo trong hàng này
                $seatsInThisRow = min($seatsPerRow, $targetSeats - $inserted);
                
                for ($j = 1; $j <= $seatsInThisRow && $inserted < $targetSeats; $j++) {
                    $sql = "INSERT INTO seats (room_id, row_label, seat_number, seat_type, extra_price, status) 
                            VALUES (:room_id, :row_label, :seat_number, :seat_type, :extra_price, :status)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([
                        ':room_id' => $room_id,
                        ':row_label' => $rowLabel,
                        ':seat_number' => $j,
                        ':seat_type' => $seatType,
                        ':extra_price' => $extraPrice,
                        ':status' => 'available'
                    ]);
                    $inserted++;
                }
            }
            
            $this->conn->commit();
            return $inserted;
        } catch (Exception $e) {
            $this->conn->rollBack();
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật ghế
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE seats SET 
                    room_id = :room_id,
                    row_label = :row_label,
                    seat_number = :seat_number,
                    seat_type = :seat_type,
                    extra_price = :extra_price,
                    status = :status
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':room_id' => $data['room_id'] ?? null,
                ':row_label' => $data['row_label'] ?? null,
                ':seat_number' => $data['seat_number'] ?? null,
                ':seat_type' => $data['seat_type'] ?? 'normal',
                ':extra_price' => $data['extra_price'] ?? 0,
                ':status' => $data['status'] ?? 'available'
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa ghế
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM seats WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa tất cả ghế của phòng
     */
    public function deleteByRoom($room_id)
    {
        try {
            $sql = "DELETE FROM seats WHERE room_id = :room_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':room_id' => $room_id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Kiểm tra ghế có trùng không
     */
    public function checkDuplicate($room_id, $row_label, $seat_number, $exclude_id = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM seats 
                    WHERE room_id = :room_id 
                    AND row_label = :row_label 
                    AND seat_number = :seat_number";
            
            if ($exclude_id) {
                $sql .= " AND id != :exclude_id";
            }
            
            $stmt = $this->conn->prepare($sql);
            $params = [
                ':room_id' => $room_id,
                ':row_label' => $row_label,
                ':seat_number' => $seat_number
            ];
            
            if ($exclude_id) {
                $params[':exclude_id'] = $exclude_id;
            }
            
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }
}

?>

