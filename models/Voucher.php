<?php
class Voucher
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả voucher
     */
    public function all($status = null)
    {
        try {
            $sql = "SELECT * FROM vouchers";
            $params = [];
            
            if ($status) {
                $sql .= " WHERE status = :status";
                $params[':status'] = $status;
            }
            
            $sql .= " ORDER BY priority DESC, created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Xử lý benefits từ string sang array
            foreach ($results as &$voucher) {
                if (!empty($voucher['benefits'])) {
                    $voucher['benefits'] = explode('|', $voucher['benefits']);
                } else {
                    $voucher['benefits'] = [];
                }
            }
            
            return $results;
        } catch (Exception $e) {
            error_log('Error in Voucher->all(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy voucher theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM vouchers WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($voucher && !empty($voucher['benefits'])) {
                $voucher['benefits'] = explode('|', $voucher['benefits']);
            } else if ($voucher) {
                $voucher['benefits'] = [];
            }
            
            return $voucher;
        } catch (Exception $e) {
            error_log('Error in Voucher->find(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy voucher theo code
     */
    public function findByCode($code)
    {
        try {
            $sql = "SELECT * FROM vouchers WHERE code = :code AND status IN ('active', 'ongoing')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => $code]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($voucher) {
                // Kiểm tra thời gian hiệu lực
                $now = date('Y-m-d H:i:s');
                if ($voucher['start_date'] && $now < $voucher['start_date']) {
                    return null; // Chưa đến thời gian
                }
                if ($voucher['end_date'] && $now > $voucher['end_date']) {
                    return null; // Đã hết hạn
                }
                
                if (!empty($voucher['benefits'])) {
                    $voucher['benefits'] = explode('|', $voucher['benefits']);
                } else {
                    $voucher['benefits'] = [];
                }
            }
            
            return $voucher;
        } catch (Exception $e) {
            error_log('Error in Voucher->findByCode(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy voucher đang hoạt động cho client
     */
    public function getActiveForClient()
    {
        try {
            $now = date('Y-m-d H:i:s');
            $sql = "SELECT * FROM vouchers 
                    WHERE status IN ('active', 'ongoing') 
                    AND (start_date IS NULL OR start_date <= :now)
                    AND (end_date IS NULL OR end_date >= :now)
                    ORDER BY priority DESC, created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':now' => $now]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Xử lý benefits và xác định status hiển thị
            foreach ($results as &$voucher) {
                if (!empty($voucher['benefits'])) {
                    $voucher['benefits'] = explode('|', $voucher['benefits']);
                } else {
                    $voucher['benefits'] = [];
                }
                
                // Xác định status hiển thị
                if ($voucher['start_date'] && $now < $voucher['start_date']) {
                    $voucher['display_status'] = 'upcoming';
                } elseif ($voucher['end_date'] && $now > $voucher['end_date']) {
                    $voucher['display_status'] = 'ended';
                } else {
                    $voucher['display_status'] = 'ongoing';
                }
                
                // Format period nếu chưa có
                if (empty($voucher['period'])) {
                    $start = $voucher['start_date'] ? date('d/m/Y', strtotime($voucher['start_date'])) : '';
                    $end = $voucher['end_date'] ? date('d/m/Y', strtotime($voucher['end_date'])) : '';
                    if ($start && $end) {
                        $voucher['period'] = "$start - $end";
                    } elseif ($start) {
                        $voucher['period'] = "Từ $start";
                    } elseif ($end) {
                        $voucher['period'] = "Đến $end";
                    } else {
                        $voucher['period'] = 'Áp dụng thường xuyên';
                    }
                }
            }
            
            return $results;
        } catch (Exception $e) {
            error_log('Error in Voucher->getActiveForClient(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Tạo voucher mới
     */
    public function insert($data)
    {
        try {
            // Xử lý benefits từ array sang string
            if (isset($data['benefits']) && is_array($data['benefits'])) {
                $data['benefits'] = implode('|', $data['benefits']);
            }
            
            $sql = "INSERT INTO vouchers (
                title, description, tag, code, discount_code_id, benefits, period,
                image, start_date, end_date, status, cta, cta_link, priority
            ) VALUES (
                :title, :description, :tag, :code, :discount_code_id, :benefits, :period,
                :image, :start_date, :end_date, :status, :cta, :cta_link, :priority
            )";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'] ?? '',
                ':description' => $data['description'] ?? '',
                ':tag' => $data['tag'] ?? 'general',
                ':code' => $data['code'] ?? null,
                ':discount_code_id' => $data['discount_code_id'] ?? null,
                ':benefits' => $data['benefits'] ?? null,
                ':period' => $data['period'] ?? null,
                ':image' => $data['image'] ?? null,
                ':start_date' => $data['start_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? 'Đặt vé ngay',
                ':cta_link' => $data['cta_link'] ?? null,
                ':priority' => $data['priority'] ?? 0
            ]);
            
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            error_log('Error in Voucher->insert(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật voucher
     */
    public function update($id, $data)
    {
        try {
            // Xử lý benefits từ array sang string
            if (isset($data['benefits']) && is_array($data['benefits'])) {
                $data['benefits'] = implode('|', $data['benefits']);
            }
            
            $sql = "UPDATE vouchers SET
                title = :title,
                description = :description,
                tag = :tag,
                code = :code,
                discount_code_id = :discount_code_id,
                benefits = :benefits,
                period = :period,
                image = :image,
                start_date = :start_date,
                end_date = :end_date,
                status = :status,
                cta = :cta,
                cta_link = :cta_link,
                priority = :priority
                WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'] ?? '',
                ':description' => $data['description'] ?? '',
                ':tag' => $data['tag'] ?? 'general',
                ':code' => $data['code'] ?? null,
                ':discount_code_id' => $data['discount_code_id'] ?? null,
                ':benefits' => $data['benefits'] ?? null,
                ':period' => $data['period'] ?? null,
                ':image' => $data['image'] ?? null,
                ':start_date' => $data['start_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? 'Đặt vé ngay',
                ':cta_link' => $data['cta_link'] ?? null,
                ':priority' => $data['priority'] ?? 0
            ]);
        } catch (Exception $e) {
            error_log('Error in Voucher->update(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa voucher
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM vouchers WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log('Error in Voucher->delete(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate dữ liệu
     */
    public function validate($data, $isUpdate = false)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Tiêu đề không được để trống';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Mô tả không được để trống';
        }

        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
                $errors['end_date'] = 'Ngày kết thúc phải sau ngày bắt đầu';
            }
        }

        return $errors;
    }
    
    /**
     * Lấy discount code từ voucher code
     */
    public function getDiscountCodeByVoucherCode($voucherCode)
    {
        try {
            $voucher = $this->findByCode($voucherCode);
            if (!$voucher) {
                return null;
            }
            
            require_once __DIR__ . '/DiscountCode.php';
            $discountCodeModel = new DiscountCode();
            
            // Nếu voucher có discount_code_id, lấy từ đó
            if (!empty($voucher['discount_code_id'])) {
                $discountCode = $discountCodeModel->find($voucher['discount_code_id']);
                if ($discountCode) {
                    return $discountCode;
                }
            }
            
            // Nếu không có discount_code_id, thử tìm discount code trực tiếp bằng code
            if (!empty($voucher['code'])) {
                $discountCode = $discountCodeModel->findByCode($voucher['code']);
                if ($discountCode) {
                    return $discountCode;
                }
            }
            
            return null;
        } catch (Exception $e) {
            error_log('Error in Voucher->getDiscountCodeByVoucherCode(): ' . $e->getMessage());
            return null;
        }
    }
}

