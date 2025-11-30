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

    /**
     * Tạo discount code mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO discount_codes (code, title, discount_percent, start_date, end_date, description, benefits, status, cta) 
                    VALUES (:code, :title, :discount_percent, :start_date, :end_date, :description, :benefits, :status, :cta)";
            $stmt = $this->conn->prepare($sql);
            
            // Xử lý benefits JSON
            $benefits = null;
            if (!empty($data['benefits'])) {
                if (is_array($data['benefits'])) {
                    $benefits = json_encode($data['benefits'], JSON_UNESCAPED_UNICODE);
                } else {
                    $benefits = $data['benefits'];
                }
            }
            
            return $stmt->execute([
                ':code' => strtoupper(trim($data['code'])),
                ':title' => trim($data['title']),
                ':discount_percent' => (int)($data['discount_percent'] ?? 0),
                ':start_date' => $data['start_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':description' => $data['description'] ?? null,
                ':benefits' => $benefits,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? null
            ]);
        } catch (Exception $e) {
            error_log('Error in DiscountCode->insert(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật discount code
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE discount_codes 
                    SET code = :code, title = :title, discount_percent = :discount_percent, 
                        start_date = :start_date, end_date = :end_date, description = :description, 
                        benefits = :benefits, status = :status, cta = :cta, updated_at = NOW()
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            
            // Xử lý benefits JSON
            $benefits = null;
            if (!empty($data['benefits'])) {
                if (is_array($data['benefits'])) {
                    $benefits = json_encode($data['benefits'], JSON_UNESCAPED_UNICODE);
                } else {
                    $benefits = $data['benefits'];
                }
            }
            
            return $stmt->execute([
                ':id' => $id,
                ':code' => strtoupper(trim($data['code'])),
                ':title' => trim($data['title']),
                ':discount_percent' => (int)($data['discount_percent'] ?? 0),
                ':start_date' => $data['start_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':description' => $data['description'] ?? null,
                ':benefits' => $benefits,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? null
            ]);
        } catch (Exception $e) {
            error_log('Error in DiscountCode->update(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa discount code
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM discount_codes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log('Error in DiscountCode->delete(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra code đã tồn tại chưa (trừ id hiện tại)
     */
    public function codeExists($code, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) FROM discount_codes WHERE code = :code";
            $params = [':code' => strtoupper(trim($code))];
            
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log('Error in DiscountCode->codeExists(): ' . $e->getMessage());
            return false;
        }
    }
}

