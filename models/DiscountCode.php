<?php
// DISCOUNT CODE MODEL - Tương tác với bảng discount_codes
// Chức năng: CRUD mã giảm giá, validate mã, lấy mã available
class DiscountCode
{
    private $conn; // Kết nối database (PDO)
    private $lastError = null; // Lưu lỗi cuối cùng để hiển thị/log

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
            if (!$stmt) {
                $info = $this->conn->errorInfo();
                $this->lastError = 'Prepare failed (insert): ' . json_encode($info);
                error_log($this->lastError);
                return false;
            }
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
            if (!$stmt) {
                $info = $this->conn->errorInfo();
                $this->lastError = 'Prepare failed (update): ' . json_encode($info);
                error_log($this->lastError);
                return false;
            }
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
    public function validateDiscountCode($code, $totalAmount = 0, $movieId = null, $seatCount = 1, $userId = null)
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

            // Kiểm tra lượt sử dụng còn lại (mỗi ghế sử dụng 1 lượt) THEO TÀI KHOẢN
            $usageLimit = isset($discount['usage_limit']) ? (int)$discount['usage_limit'] : null;
            $usageUsed = $this->getUserUsageFromBookings($discount['id'], $userId);
            $remainingUses = $usageLimit !== null ? max(0, $usageLimit - $usageUsed) : null;
            if ($usageLimit !== null) {
                if ($remainingUses <= 0) {
                    return ['error' => 'Bạn đã hết lượt sử dụng mã này'];
                }
                if ($seatCount > $remainingUses) {
                    return ['error' => "Bạn chỉ còn {$remainingUses} lượt, không đủ cho số ghế đã chọn"];
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
                'usage_used' => $usageUsed,
                'remaining_uses' => $remainingUses,
                'usage_label' => $usageLimit !== null
                    ? "Bạn còn {$remainingUses}/{$usageLimit} lượt"
                    : 'Không giới hạn lượt'
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

            $ok = $stmt->execute([
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
                ':usage_used' => isset($data['usage_used']) ? (int)$data['usage_used'] : 0
            ]);
            if (!$ok) {
                $info = $stmt->errorInfo();
                $this->lastError = 'Insert failed: ' . json_encode($info);
                error_log('Error in DiscountCode->insert(): ' . $this->lastError);
            }
            return $ok;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            if (isset($stmt)) {
                $info = $stmt->errorInfo();
                $this->lastError .= ' | PDO: ' . json_encode($info);
            }
            error_log('Error in DiscountCode->insert(): ' . $this->lastError);
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
                        description = :description, benefits = :benefits, status = :status, cta = :cta, 
                        usage_limit = :usage_limit, usage_used = :usage_used, updated_at = NOW()
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

            $ok = $stmt->execute([
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
                ':usage_limit' => isset($data['usage_limit']) && $data['usage_limit'] !== '' ? (int)$data['usage_limit'] : null,
                ':usage_used' => isset($data['usage_used']) ? (int)$data['usage_used'] : 0
            ]);
            if (!$ok) {
                $info = $stmt->errorInfo();
                $this->lastError = 'Update failed: ' . json_encode($info);
                error_log('Error in DiscountCode->update(): ' . $this->lastError);
            }
            return $ok;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            if (isset($stmt)) {
                $info = $stmt->errorInfo();
                $this->lastError .= ' | PDO: ' . json_encode($info);
            }
            error_log('Error in DiscountCode->update(): ' . $this->lastError);
            return false;
        }
    }

    /**
     * Lấy mô tả lỗi cuối cùng (để controller hiển thị thân thiện hơn).
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
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
            $normalizedCode = strtoupper(trim($code));
            $stmt = $this->conn->prepare(
                "SELECT id FROM discount_codes WHERE code = :code LIMIT 1"
            );
            $stmt->execute([':code' => $normalizedCode]);
            $foundId = $stmt->fetchColumn();

            if ($foundId === false) {
                return false; // Không tìm thấy mã
            }

            // Nếu tìm thấy chính bản ghi đang sửa thì không tính là trùng
            if ($excludeId !== null && (int)$foundId === (int)$excludeId) {
                return false;
            }

            return true;
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
     * @param int|null $userId ID người dùng để kiểm tra lượt sử dụng còn lại
     */
    public function getAvailableCodes($movieId = null, $limit = 5, $includeMovieSpecific = false, $userId = null)
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

            // Luôn lọc theo hạn sử dụng (ngày bắt đầu và kết thúc)
            $sql .= " AND (dc.start_date IS NULL OR dc.start_date <= :now)"; // Đã đến ngày bắt đầu
            $sql .= " AND (dc.end_date IS NULL OR dc.end_date >= :now)"; // Chưa đến ngày kết thúc
            $params[':now'] = $now; // Thêm tham số ngày hiện tại

            if ($includeMovieSpecific) { // Nếu từ modal -> lấy cả mã phim cụ thể
                // Không filter movie_id, lấy tất cả
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

            // Lọc theo lượt sử dụng còn lại của user
            $filteredResults = [];
            foreach ($results as $result) {
                if (isset($result['movie_id']) && ($result['movie_id'] === 'null' || $result['movie_id'] === '')) {
                    $result['movie_id'] = null; // Chuyển string "null" thành null
                }
                $usageLimit = isset($result['usage_limit']) ? (int)$result['usage_limit'] : null;
                $usageUsed = $this->getUserUsageFromBookings($result['id'], $userId);
                $remainingUses = $usageLimit !== null ? max(0, $usageLimit - $usageUsed) : null;
                
                // Chỉ thêm mã nếu còn lượt sử dụng (hoặc không giới hạn)
                if ($usageLimit === null || $remainingUses > 0) {
                    $result['remaining_uses'] = $remainingUses;
                    $result['usage_used'] = $usageUsed;
                    $result['usage_label'] = $usageLimit !== null
                        ? "Bạn còn {$remainingUses}/{$usageLimit} lượt"
                        : 'Không giới hạn lượt';
                    $filteredResults[] = $result;
                }
            }

            return $filteredResults; // Trả về danh sách mã giảm giá đã lọc
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
            // Không trừ lượt toàn hệ thống; lượt được tính theo tài khoản qua booking.
            return true;
        } catch (Exception $e) {
            error_log('Error in DiscountCode->consumeUsage(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Đếm lượt sử dụng mã của một user dựa trên bookings (tính theo số ghế).
     */
    public function getUserUsageFromBookings($discountId, $userId = null)
    {
        try {
            if (empty($userId)) {
                return 0;
            }
            $sql = "SELECT booked_seats FROM bookings 
                    WHERE discount_id = :discount_id 
                      AND user_id = :user_id
                      AND status IN ('pending', 'confirmed', 'paid', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':discount_id' => $discountId,
                ':user_id' => $userId
            ]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = 0;
            foreach ($rows as $row) {
                if (!empty($row['booked_seats'])) {
                    $parts = array_filter(array_map('trim', explode(',', $row['booked_seats'])));
                    $count += count($parts);
                }
            }
            return $count;
        } catch (Exception $e) {
            error_log('Error in DiscountCode->getUserUsageFromBookings(): ' . $e->getMessage());
            return 0;
        }
    }
}
