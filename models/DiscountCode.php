<?php
// DISCOUNT CODE MODEL - Tương tác với bảng discount_codes
// Chức năng: CRUD mã giảm giá, validate mã, lấy mã available
class DiscountCode
{
    private $conn; // Kết nối database (PDO)

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    /**
     * Lấy tất cả discount codes (có thông tin phim nếu có)
     */
    public function all()
    {
        try {
            $sql = "SELECT dc.*, 
                    m.id AS movie_id, 
                    m.title AS movie_title, 
                    m.image AS movie_image
                    FROM discount_codes dc
                    LEFT JOIN movies m ON dc.movie_id = m.id
                    ORDER BY dc.created_at DESC";
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
     * Lấy discount code theo code (không filter status, để validateDiscountCode xử lý)
     */
    public function findByCode($code)
    {
        try {
            $sql = "SELECT * FROM discount_codes WHERE code = :code";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => strtoupper(trim($code))]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Error in DiscountCode->findByCode(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate discount code với total amount, movie_id (nếu có) và số vé dùng mã
     * Trả về null nếu không hợp lệ, hoặc array với thông tin discount nếu hợp lệ
     * Có thể trả về error message trong trường hợp đặc biệt
     */
    public function validateDiscountCode($code, $totalAmount = 0, $movieId = null, $seatCount = 1)
    {
        try {
            $seatCount = max(1, (int)$seatCount);
            // Tìm mã giảm giá (không cần status = 'active' ở đây vì sẽ check riêng)
            $sql = "SELECT * FROM discount_codes WHERE code = :code";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => strtoupper(trim($code))]);
            $discount = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$discount) {
                return ['error' => 'Mã giảm giá không tồn tại'];
            }

            // Kiểm tra status
            if ($discount['status'] !== 'active') {
                return ['error' => 'Mã giảm giá không hoạt động'];
            }

            // Kiểm tra lượt sử dụng còn lại (mỗi ghế sử dụng 1 lượt)
            $usageLimit = isset($discount['usage_limit']) ? (int)$discount['usage_limit'] : null;
            $usageUsed = isset($discount['usage_used']) ? (int)$discount['usage_used'] : 0;
            if ($usageLimit !== null) {
                $remaining = $usageLimit - $usageUsed;
                if ($remaining <= 0) {
                    return ['error' => 'Mã giảm giá đã hết lượt sử dụng'];
                }
                if ($seatCount > $remaining) {
                    return ['error' => "Mã giảm giá chỉ còn {$remaining} lượt, không đủ cho số ghế đã chọn"];
                }
            }

            // Kiểm tra thời gian hiệu lực
            $now = date('Y-m-d');
            if ($discount['start_date'] && $now < $discount['start_date']) {
                return ['error' => 'Mã giảm giá chưa đến thời gian áp dụng'];
            }
            if ($discount['end_date'] && $now > $discount['end_date']) {
                return ['error' => 'Mã giảm giá đã hết hạn'];
            }

            // Kiểm tra nếu mã giảm giá chỉ áp dụng cho phim cụ thể
            if (!empty($discount['movie_id'])) {
                $discountMovieId = (int)$discount['movie_id'];
                $providedMovieId = $movieId ? (int)$movieId : null;
                if (empty($providedMovieId)) {
                    return ['error' => 'Mã giảm giá này chỉ áp dụng cho phim cụ thể. Vui lòng chọn phim phù hợp.'];
                }
                if ($discountMovieId !== $providedMovieId) {
                    return ['error' => 'Mã giảm giá này chỉ áp dụng cho phim cụ thể. Vui lòng kiểm tra lại phim bạn đang đặt vé.'];
                }
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
                'end_date' => $discount['end_date'],
                'movie_id' => $discount['movie_id'] ?? null,
                'usage_limit' => $usageLimit,
                'usage_used' => $usageUsed
            ];
        } catch (Exception $e) {
            error_log('Error in DiscountCode->validateDiscountCode(): ' . $e->getMessage());
            return ['error' => 'Có lỗi xảy ra khi kiểm tra mã giảm giá'];
        }
    }

    /**
     * Tạo discount code mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO discount_codes (code, title, discount_percent, start_date, end_date, movie_id, description, benefits, status, cta, usage_limit, usage_used) 
                    VALUES (:code, :title, :discount_percent, :start_date, :end_date, :movie_id, :description, :benefits, :status, :cta, :usage_limit, :usage_used)";
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
                ':movie_id' => !empty($data['movie_id']) ? (int)$data['movie_id'] : null,
                ':description' => $data['description'] ?? null,
                ':benefits' => $benefits,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? null,
                ':usage_limit' => isset($data['usage_limit']) && $data['usage_limit'] !== '' ? (int)$data['usage_limit'] : null,
                ':usage_used' => 0
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
                        start_date = :start_date, end_date = :end_date, movie_id = :movie_id, 
                        description = :description, benefits = :benefits, status = :status, cta = :cta, usage_limit = :usage_limit, updated_at = NOW()
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
                ':movie_id' => !empty($data['movie_id']) ? (int)$data['movie_id'] : null,
                ':description' => $data['description'] ?? null,
                ':benefits' => $benefits,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? null,
                ':usage_limit' => isset($data['usage_limit']) && $data['usage_limit'] !== '' ? (int)$data['usage_limit'] : null
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

    /**
     * Lấy danh sách mã khuyến mãi đang active (có thể áp dụng)
     * @param int|null $movieId Nếu có, chỉ lấy mã áp dụng cho phim đó hoặc mã tổng quát
     * @param int $limit Số lượng mã tối đa
     * @param bool $includeMovieSpecific Nếu true, bao gồm cả mã áp dụng cho phim cụ thể
     */
    public function getAvailableCodes($movieId = null, $limit = 5, $includeMovieSpecific = false)
    {
        try {
            $now = date('Y-m-d'); // Lấy ngày hiện tại
            $sql = "SELECT dc.*, 
                    m.title AS movie_title, 
                    m.image AS movie_image
                    FROM discount_codes dc
                    LEFT JOIN movies m ON dc.movie_id = m.id
                    WHERE dc.status = 'active'"; // Chỉ lấy mã active
            
            $params = [];
            
            if (!$includeMovieSpecific) { // Nếu từ thanh toán (không phải modal)
                $sql .= " AND (dc.usage_limit IS NULL OR dc.usage_used < dc.usage_limit)"; // Chưa hết lượt sử dụng
                $sql .= " AND (dc.start_date IS NULL OR dc.start_date <= :now)"; // Đã đến ngày bắt đầu
                $sql .= " AND (dc.end_date IS NULL OR dc.end_date >= :now)"; // Chưa đến ngày kết thúc
                $params[':now'] = $now; // Thêm tham số ngày hiện tại
            }
            
            if ($includeMovieSpecific) { // Nếu từ modal -> lấy tất cả mã
                // Không filter movie_id
            } elseif ($movieId) { // Nếu có movie_id
                $sql .= " AND (dc.movie_id IS NULL OR dc.movie_id = :movie_id)"; // Mã cho phim này hoặc tất cả phim
                $params[':movie_id'] = $movieId; // Thêm tham số movie_id
            } else { // Không có movie_id
                $sql .= " AND dc.movie_id IS NULL"; // Chỉ lấy mã cho tất cả phim
            }
            
            $sql .= " ORDER BY dc.discount_percent DESC, dc.created_at DESC LIMIT :limit"; // Sắp xếp giảm dần theo % và giới hạn số lượng
            
            $stmt = $this->conn->prepare($sql); // Chuẩn bị câu lệnh SQL
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value); // Gán giá trị cho tham số
            }
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT); // Gán limit
            $stmt->execute(); // Thực thi query
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy kết quả dạng mảng
            
            foreach ($results as &$result) { // Duyệt qua từng kết quả
                if (isset($result['movie_id']) && ($result['movie_id'] === 'null' || $result['movie_id'] === '')) {
                    $result['movie_id'] = null; // Chuyển string "null" thành null
                }
            }
            unset($result);
            
            return $results; // Trả về danh sách mã giảm giá
        } catch (Exception $e) {
            error_log('Error in DiscountCode->getAvailableCodes(): ' . $e->getMessage()); // Ghi log lỗi
            return []; // Trả về mảng rỗng nếu lỗi
        }
    }

    /**
     * Trừ lượt sử dụng mã theo số ghế. Trả về true nếu cập nhật thành công.
     */
    public function consumeUsage($discountId, $seatCount = 1, $externalConn = null)
    {
        try {
            $seatCount = max(1, (int)$seatCount);
            $conn = $externalConn ?? $this->conn;

            $sql = "UPDATE discount_codes
                    SET usage_used = usage_used + :seat_count,
                        status = CASE 
                            WHEN usage_limit IS NOT NULL AND usage_used + :seat_count >= usage_limit THEN 'inactive' 
                            ELSE status 
                        END,
                        updated_at = NOW()
                    WHERE id = :id
                      AND (usage_limit IS NULL OR usage_used + :seat_count <= usage_limit)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':seat_count' => $seatCount,
                ':id' => $discountId
            ]);

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log('Error in DiscountCode->consumeUsage(): ' . $e->getMessage());
            return false;
        }
    }
}
