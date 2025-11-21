<?php

class DiscountCode
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy danh sách mã giảm giá hiển thị cho trang khuyến mãi
     */
    public function getClientDiscounts()
    {
        try {
            $sql = "SELECT *
                    FROM discount_codes
                    WHERE status IN ('active', 'upcoming')
                    ORDER BY FIELD(status, 'active', 'upcoming', 'inactive'), start_date ASC, id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            return array_map(function ($row) {
                $row['benefits'] = !empty($row['benefits'])
                    ? json_decode($row['benefits'], true)
                    : [];
                return $row;
            }, $rows);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Thống kê số lượng mã giảm giá
     */
    public function getStats()
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM discount_codes
                    WHERE status IN ('active', 'upcoming')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();

            return ['total' => (int)($result['total'] ?? 0)];
        } catch (Exception $e) {
            return ['total' => 0];
        }
    }

    /**
     * Lấy mã giảm giá theo code
     */
    public function findByCode($code)
    {
        try {
            $sql = "SELECT * FROM discount_codes WHERE code = :code";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => strtoupper(trim($code))]);
            $row = $stmt->fetch();

            if ($row) {
                $row['benefits'] = !empty($row['benefits'])
                    ? json_decode($row['benefits'], true)
                    : [];
            }

            return $row;
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Kiểm tra mã giảm giá có hợp lệ không dựa trên điều kiện
     * @param string $code Mã giảm giá
     * @param int $seatCount Số lượng ghế đã chọn
     * @param string $showDate Ngày chiếu (Y-m-d)
     * @param int|null $movieId ID phim (cho special screening)
     * @return array ['valid' => bool, 'message' => string, 'discount' => array|null]
     */
    public function validateDiscountCode($code, $seatCount, $showDate, $movieId = null)
    {
        $discount = $this->findByCode($code);

        if (!$discount) {
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại', 'discount' => null];
        }

        // Kiểm tra trạng thái
        if ($discount['status'] !== 'active') {
            return ['valid' => false, 'message' => 'Mã giảm giá không còn hiệu lực', 'discount' => null];
        }

        // Kiểm tra ngày hiệu lực
        $today = date('Y-m-d');
        if ($discount['start_date'] && $today < $discount['start_date']) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa có hiệu lực', 'discount' => null];
        }
        if ($discount['end_date'] && $today > $discount['end_date']) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn', 'discount' => null];
        }

        // Kiểm tra điều kiện theo code
        $codeUpper = strtoupper($discount['code']);

        // COUPLE20 - cần ít nhất 2 ghế
        if (strpos($codeUpper, 'COUPLE') !== false) {
            if ($seatCount < 2) {
                return ['valid' => false, 'message' => 'Mã này chỉ áp dụng khi mua từ 2 vé trở lên', 'discount' => null];
            }
        }

        // FAMILY35 - cần ít nhất 3 ghế
        if (strpos($codeUpper, 'FAMILY') !== false) {
            if ($seatCount < 3) {
                return ['valid' => false, 'message' => 'Mã này chỉ áp dụng khi mua từ 3 vé trở lên', 'discount' => null];
            }
        }

        // WEEKEND25 - chỉ áp dụng cuối tuần (Thứ 6, 7, CN)
        if (strpos($codeUpper, 'WEEKEND') !== false) {
            $dayOfWeek = date('w', strtotime($showDate)); // 0 = CN, 6 = Thứ 7
            if ($dayOfWeek != 0 && $dayOfWeek != 5 && $dayOfWeek != 6) {
                return ['valid' => false, 'message' => 'Mã này chỉ áp dụng cho suất chiếu cuối tuần', 'discount' => null];
            }
        }

        // HOLIDAY30 - kiểm tra trong khoảng ngày lễ
        if (strpos($codeUpper, 'HOLIDAY') !== false) {
            if ($discount['start_date'] && $showDate < $discount['start_date']) {
                return ['valid' => false, 'message' => 'Mã này chỉ áp dụng trong dịp lễ', 'discount' => null];
            }
            if ($discount['end_date'] && $showDate > $discount['end_date']) {
                return ['valid' => false, 'message' => 'Mã này chỉ áp dụng trong dịp lễ', 'discount' => null];
            }
        }

        // PREMIERE40 - có thể kiểm tra thêm movieId nếu cần
        // Hiện tại chỉ kiểm tra ngày hiệu lực

        return ['valid' => true, 'message' => 'Mã giảm giá hợp lệ', 'discount' => $discount];
    }

    /**
     * Lấy tất cả mã giảm giá (Admin)
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM discount_codes ORDER BY created_at DESC, id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            return array_map(function ($row) {
                $row['benefits'] = !empty($row['benefits'])
                    ? json_decode($row['benefits'], true)
                    : [];
                return $row;
            }, $rows);
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy mã giảm giá theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM discount_codes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            if ($row) {
                $row['benefits'] = !empty($row['benefits'])
                    ? json_decode($row['benefits'], true)
                    : [];
            }

            return $row;
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Thêm mã giảm giá mới
     */
    public function insert($data)
    {
        try {
            $benefits = !empty($data['benefits']) && is_array($data['benefits'])
                ? json_encode($data['benefits'], JSON_UNESCAPED_UNICODE)
                : null;

            $sql = "INSERT INTO discount_codes 
                    (code, title, discount_percent, start_date, end_date, description, benefits, status, cta) 
                    VALUES 
                    (:code, :title, :discount_percent, :start_date, :end_date, :description, :benefits, :status, :cta)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':code' => $data['code'],
                ':title' => $data['title'],
                ':discount_percent' => $data['discount_percent'] ?? 0,
                ':start_date' => $data['start_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':description' => $data['description'] ?? null,
                ':benefits' => $benefits,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? null
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Handle primary key violation (AUTO_INCREMENT issue)
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'PRIMARY') !== false) {
                // Fix AUTO_INCREMENT by resetting it to the next available ID
                try {
                    $maxIdSql = "SELECT COALESCE(MAX(id), 0) + 1 as next_id FROM discount_codes";
                    $maxIdStmt = $this->conn->prepare($maxIdSql);
                    $maxIdStmt->execute();
                    $result = $maxIdStmt->fetch();
                    $nextId = $result['next_id'] ?? 1;

                    $resetSql = "ALTER TABLE discount_codes AUTO_INCREMENT = :next_id";
                    $resetStmt = $this->conn->prepare($resetSql);
                    $resetStmt->execute([':next_id' => $nextId]);

                    // Retry the insert
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([
                        ':code' => $data['code'],
                        ':title' => $data['title'],
                        ':discount_percent' => $data['discount_percent'] ?? 0,
                        ':start_date' => $data['start_date'] ?? null,
                        ':end_date' => $data['end_date'] ?? null,
                        ':description' => $data['description'] ?? null,
                        ':benefits' => $benefits,
                        ':status' => $data['status'] ?? 'active',
                        ':cta' => $data['cta'] ?? null
                    ]);

                    return $this->conn->lastInsertId();
                } catch (Exception $retryException) {
                    debug($retryException);
                    return false;
                }
            }
            debug($e);
            return false;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update($id, $data)
    {
        try {
            $benefits = !empty($data['benefits']) && is_array($data['benefits'])
                ? json_encode($data['benefits'], JSON_UNESCAPED_UNICODE)
                : null;

            $sql = "UPDATE discount_codes SET 
                    code = :code,
                    title = :title,
                    discount_percent = :discount_percent,
                    start_date = :start_date,
                    end_date = :end_date,
                    description = :description,
                    benefits = :benefits,
                    status = :status,
                    cta = :cta
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':code' => $data['code'],
                ':title' => $data['title'],
                ':discount_percent' => $data['discount_percent'] ?? 0,
                ':start_date' => $data['start_date'] ?? null,
                ':end_date' => $data['end_date'] ?? null,
                ':description' => $data['description'] ?? null,
                ':benefits' => $benefits,
                ':status' => $data['status'] ?? 'active',
                ':cta' => $data['cta'] ?? null
            ]);

            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa mã giảm giá
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM discount_codes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Kiểm tra mã code đã tồn tại chưa (trừ ID hiện tại nếu đang edit)
     */
    public function codeExists($code, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM discount_codes WHERE code = :code";
            $params = [':code' => $code];

            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();

            return $result['count'] > 0;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }
}
