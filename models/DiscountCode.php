<?php
class DiscountCode
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả discount codes
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM discount_codes ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Error in DiscountCode->all(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy discount code theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM discount_codes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Error in DiscountCode->find(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy discount code theo code
     */
    public function findByCode($code)
    {
        try {
            $sql = "SELECT * FROM discount_codes WHERE code = :code AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => $code]);
            $discount = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($discount) {
                // Kiểm tra thời gian hiệu lực
                $now = date('Y-m-d');
                if ($discount['start_date'] && $now < $discount['start_date']) {
                    return null;
                }
                if ($discount['end_date'] && $now > $discount['end_date']) {
                    return null;
                }
            }
            
            return $discount;
        } catch (Exception $e) {
            error_log('Error in DiscountCode->findByCode(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate discount code với total amount
     */
    public function validateDiscountCode($code, $totalAmount = 0)
    {
        try {
            $discount = $this->findByCode($code);
            if (!$discount) {
                return null;
            }
            
            // Kiểm tra thời gian hiệu lực
            $now = date('Y-m-d');
            if ($discount['start_date'] && $now < $discount['start_date']) {
                return null; // Chưa đến thời gian
            }
            if ($discount['end_date'] && $now > $discount['end_date']) {
                return null; // Đã hết hạn
            }
            
            // Tính toán discount amount
            $discountAmount = 0;
            if ($discount['discount_percent'] > 0 && $totalAmount > 0) {
                $discountAmount = ($totalAmount * $discount['discount_percent']) / 100;
            }
            
            return [
                'id' => $discount['id'],
                'code' => $discount['code'],
                'discount_percent' => $discount['discount_percent'],
                'discount_amount' => $discountAmount,
                'start_date' => $discount['start_date'],
                'end_date' => $discount['end_date']
            ];
        } catch (Exception $e) {
            error_log('Error in DiscountCode->validateDiscountCode(): ' . $e->getMessage());
            return null;
        }
    }
}

