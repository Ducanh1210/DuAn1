<?php
class TicketPrice
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả giá vé
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM ticket_prices ORDER BY day_type, format, customer_type, seat_type";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy giá vé theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM ticket_prices WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Lấy giá vé dựa trên ngày, định dạng, loại khách hàng và loại ghế
     */
    public function getPrice($dayType, $format, $customerType, $seatType)
    {
        try {
            $sql = "SELECT base_price FROM ticket_prices 
                    WHERE day_type = :day_type 
                    AND format = :format 
                    AND customer_type = :customer_type
                    AND seat_type = :seat_type 
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':day_type' => $dayType,
                ':format' => $format,
                ':customer_type' => $customerType,
                ':seat_type' => $seatType
            ]);
            $result = $stmt->fetch();
            return $result ? floatval($result['base_price']) : 0;
        } catch (Exception $e) {
            debug($e);
            return 0;
        }
    }

    /**
     * Tính toán day_type từ ngày
     */
    public static function getDayType($date)
    {
        $timestamp = strtotime($date);
        $dayOfWeek = date('w', $timestamp); // 0 = Chủ nhật, 1-6 = Thứ 2-7
        
        // Thứ 2-5 (1-4) = weekday
        // Thứ 6-7-CN (5, 6, 0) = weekend
        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            return 'weekday';
        } else {
            return 'weekend';
        }
    }

    /**
     * Lấy giá vé cho một suất chiếu cụ thể
     */
    public function getPriceForShowtime($showtime, $customerType, $seatType)
    {
        $dayType = self::getDayType($showtime['show_date']);
        
        // Lấy format từ showtime - đảm bảo lấy từ showtimes.format
        // Nếu không có thì mặc định 2D
        $format = isset($showtime['format']) ? $showtime['format'] : '2D';
        
        // Đảm bảo format là string và uppercase để so sánh
        $format = strtoupper(trim((string)$format));
        
        // Chuyển đổi format: IMAX và 4DX cũng tính như 3D
        // Chỉ có 2D và 3D trong bảng giá vé
        if (in_array($format, ['3D', 'IMAX', '4DX'])) {
            $format = '3D';
        } else {
            $format = '2D';
        }
        
        return $this->getPrice($dayType, $format, $customerType, $seatType);
    }

    /**
     * Cập nhật giá vé
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE ticket_prices SET 
                    base_price = :base_price,
                    updated_at = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':base_price' => $data['base_price']
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật nhiều giá vé cùng lúc
     */
    public function updateBatch($prices)
    {
        try {
            $this->conn->beginTransaction();
            
            foreach ($prices as $id => $price) {
                $sql = "UPDATE ticket_prices SET 
                        base_price = :base_price,
                        updated_at = NOW()
                        WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':id' => $id,
                    ':base_price' => $price
                ]);
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            debug($e);
            return false;
        }
    }
}
