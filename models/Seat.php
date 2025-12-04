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
            // Đảm bảo $page là số nguyên dương
            $page = max(1, intval($page));
            $perPage = max(1, intval($perPage));
            $offset = ($page - 1) * $perPage;
            
            // Đảm bảo offset không âm
            if ($offset < 0) {
                $offset = 0;
            }
            
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
            
            $seatId = $this->conn->lastInsertId();
            
            // Tự động cập nhật số ghế của phòng
            if ($seatId && !empty($data['room_id'])) {
                require_once __DIR__ . '/Room.php';
                $roomModel = new Room();
                $roomModel->updateSeatCount($data['room_id']);
            }
            
            return $seatId;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Thêm nhiều ghế cùng lúc (tự động tạo sơ đồ ghế)
     * Chia đều 2 bên: mỗi bên 6 ghế/hàng (tổng 12 ghế/hàng)
     * Tối ưu hóa bằng batch insert để tăng tốc độ
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
            
            // Tính lại số hàng dựa trên targetSeats và chia đều 2 bên (mỗi bên 6 ghế/hàng = 12 ghế/hàng)
            // Số hàng thực tế = ceil(targetSeats / 12)
            $actualRows = ceil($targetSeats / 12);
            
            // Thu thập tất cả dữ liệu ghế vào mảng để batch insert
            $seatsData = [];
            
            for ($i = 0; $i < $actualRows && $inserted < $targetSeats; $i++) {
                $rowLabel = $rowLabels[$i] ?? chr(65 + $i); // A, B, C...
                
                // Tính số ghế còn lại cần tạo
                $remainingSeats = $targetSeats - $inserted;
                
                // Tạo ghế bên trái (1-6) - ưu tiên tạo đủ 6 ghế bên trái trước
                $leftSeatsToCreate = min(6, $remainingSeats);
                for ($j = 1; $j <= $leftSeatsToCreate && $inserted < $targetSeats; $j++) {
                    $seatsData[] = [
                        'room_id' => $room_id,
                        'row_label' => $rowLabel,
                        'seat_number' => $j,
                        'seat_type' => $seatType,
                        'extra_price' => $extraPrice,
                        'status' => 'available'
                    ];
                    $inserted++;
                }
                
                // Tính lại số ghế còn lại sau khi tạo bên trái
                $remainingSeats = $targetSeats - $inserted;
                
                // Tạo ghế bên phải (7-12) - chỉ tạo nếu còn ghế cần tạo
                if ($remainingSeats > 0) {
                    $rightSeatsToCreate = min(6, $remainingSeats);
                    for ($j = 7; $j <= (7 + $rightSeatsToCreate - 1) && $inserted < $targetSeats; $j++) {
                        $seatsData[] = [
                            'room_id' => $room_id,
                            'row_label' => $rowLabel,
                            'seat_number' => $j,
                            'seat_type' => $seatType,
                            'extra_price' => $extraPrice,
                            'status' => 'available'
                        ];
                        $inserted++;
                    }
                }
            }
            
            // Batch insert: chia thành các batch 500 ghế/batch để tránh query quá dài
            $batchSize = 500;
            $totalBatches = ceil(count($seatsData) / $batchSize);
            
            for ($batch = 0; $batch < $totalBatches; $batch++) {
                $batchData = array_slice($seatsData, $batch * $batchSize, $batchSize);
                
                if (empty($batchData)) {
                    continue;
                }
                
                // Tạo câu INSERT với nhiều VALUES
                $values = [];
                $params = [];
                $paramIndex = 0;
                
                foreach ($batchData as $seat) {
                    $values[] = "(:room_id_{$paramIndex}, :row_label_{$paramIndex}, :seat_number_{$paramIndex}, :seat_type_{$paramIndex}, :extra_price_{$paramIndex}, :status_{$paramIndex})";
                    $params[":room_id_{$paramIndex}"] = $seat['room_id'];
                    $params[":row_label_{$paramIndex}"] = $seat['row_label'];
                    $params[":seat_number_{$paramIndex}"] = $seat['seat_number'];
                    $params[":seat_type_{$paramIndex}"] = $seat['seat_type'];
                    $params[":extra_price_{$paramIndex}"] = $seat['extra_price'];
                    $params[":status_{$paramIndex}"] = $seat['status'];
                    $paramIndex++;
                }
                
                $sql = "INSERT INTO seats (room_id, row_label, seat_number, seat_type, extra_price, status) 
                        VALUES " . implode(', ', $values);
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($params);
            }
            
            // Commit transaction trước khi cập nhật số ghế để tránh lock timeout
            $this->conn->commit();
            
            // Tự động cập nhật số ghế của phòng sau khi commit (ngoài transaction)
            // Sử dụng try-catch để không ảnh hưởng đến kết quả tạo ghế nếu cập nhật thất bại
            try {
                require_once __DIR__ . '/Room.php';
                $roomModel = new Room();
                $roomModel->updateSeatCount($room_id);
            } catch (Exception $e) {
                // Log lỗi nhưng không throw để không ảnh hưởng đến kết quả tạo ghế
                // Số ghế sẽ được cập nhật đúng khi hiển thị (lấy từ bảng seats)
                error_log("Failed to update seat count for room {$room_id}: " . $e->getMessage());
            }
            
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
            // Lấy thông tin phòng trước khi xóa
            $seat = $this->find($id);
            $roomId = $seat['room_id'] ?? null;
            
            $sql = "DELETE FROM seats WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            // Tự động cập nhật số ghế của phòng sau khi xóa
            if ($roomId) {
                require_once __DIR__ . '/Room.php';
                $roomModel = new Room();
                $roomModel->updateSeatCount($roomId);
            }
            
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
            
            // Tự động cập nhật số ghế của phòng về 0
            require_once __DIR__ . '/Room.php';
            $roomModel = new Room();
            $roomModel->updateSeatCount($room_id);
            
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

    /**
     * Đếm số ghế của phòng
     */
    public function getCountByRoom($room_id)
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

