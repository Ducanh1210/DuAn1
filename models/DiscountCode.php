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
     * Thống kê số lượng mã theo phạm vi áp dụng
     */
    public function getStats()
    {
        try {
            $sql = "SELECT apply_to, COUNT(*) AS total
                    FROM discount_codes
                    WHERE status IN ('active', 'upcoming')
                    GROUP BY apply_to";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            $stats = ['ticket' => 0, 'food' => 0, 'combo' => 0];
            foreach ($rows as $row) {
                $stats[$row['apply_to']] = (int)$row['total'];
            }
            $stats['total'] = array_sum($stats);
            return $stats;
        } catch (Exception $e) {
            return ['ticket' => 0, 'food' => 0, 'combo' => 0, 'total' => 0];
        }
    }
}
