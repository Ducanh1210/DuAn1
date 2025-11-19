<?php
// models/Stats.php
// Model thống kê dùng connectDB() như các model khác trong project

class Stats
{
    public $conn;

    public function __construct()
    {
        // connectDB() được định nghĩa trong commons/env.php của project
        $this->conn = connectDB();
    }

    // Helper: return single scalar
    public function scalar($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $res = $stmt->fetchColumn();
            return $res === false ? 0 : $res;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Tổng số booking
    public function totalBookings($start = null, $end = null, $statuses = null)
    {
        $sql = "SELECT COUNT(*) FROM bookings WHERE 1=1";
        $params = [];
        if ($start !== null && $end !== null) {
            $sql .= " AND DATE(booking_date) BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }
        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = [];
            foreach ($statuses as $i => $s) {
                $ph = ":st{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $s;
            }
            $sql .= " AND status IN (" . implode(',', $placeholders) . ")";
        }
        return (int)$this->scalar($sql, $params);
    }

    // Tổng doanh thu (bookings.final_amount)
    public function totalRevenue($start = null, $end = null, $statuses = ['confirmed','completed'])
    {
        $sql = "SELECT COALESCE(SUM(final_amount),0) FROM bookings WHERE 1=1";
        $params = [];
        if ($start !== null && $end !== null) {
            $sql .= " AND DATE(booking_date) BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }
        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = [];
            foreach ($statuses as $i => $s) {
                $ph = ":st{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $s;
            }
            $sql .= " AND status IN (" . implode(',', $placeholders) . ")";
        }
        return (float)$this->scalar($sql, $params);
    }

    // Doanh thu theo ngày (normalize days). Trả về mảng: 'YYYY-MM-DD' => ['revenue'=>..., 'bookings'=>...]
    public function revenueByDay($start, $end, $statuses = ['confirmed','completed'])
    {
        $sql = "SELECT DATE(booking_date) AS day, COALESCE(SUM(final_amount),0) AS revenue, COUNT(*) AS bookings_count
                FROM bookings
                WHERE DATE(booking_date) BETWEEN :start AND :end";
        $params = [':start' => $start, ':end' => $end];

        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = [];
            foreach ($statuses as $i => $s) {
                $ph = ":st{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $s;
            }
            $sql .= " AND status IN (" . implode(',', $placeholders) . ")";
        }

        $sql .= " GROUP BY DATE(booking_date) ORDER BY DATE(booking_date)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Normalize to ensure every date in range exists
        $period = [];
        $current = strtotime($start);
        $endTs = strtotime($end);
        while ($current <= $endTs) {
            $d = date('Y-m-d', $current);
            $period[$d] = ['revenue' => 0.0, 'bookings' => 0];
            $current = strtotime('+1 day', $current);
        }
        foreach ($rows as $r) {
            $period[$r['day']] = ['revenue' => (float)$r['revenue'], 'bookings' => (int)$r['bookings_count']];
        }
        return $period;
    }

    // Top movies by revenue (join via showtimes)
    public function topMoviesByRevenue($start = null, $end = null, $limit = 10, $statuses = ['confirmed','completed'])
    {
        $sql = "SELECT m.id, m.title, COALESCE(SUM(b.final_amount),0) AS revenue, COUNT(b.id) AS bookings_count
                FROM bookings b
                LEFT JOIN showtimes s ON b.showtime_id = s.id
                LEFT JOIN movies m ON s.movie_id = m.id
                WHERE 1=1";
        $params = [];
        if ($start !== null && $end !== null) {
            $sql .= " AND DATE(b.booking_date) BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }
        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = [];
            foreach ($statuses as $i => $s) {
                $ph = ":st{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $s;
            }
            $sql .= " AND b.status IN (" . implode(',', $placeholders) . ")";
        }
        $sql .= " GROUP BY m.id, m.title ORDER BY revenue DESC LIMIT :lim";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tổng vé bán (ước lượng từ booked_seats 'A1,A2,...')
    public function totalTicketsSold($start = null, $end = null, $statuses = ['confirmed','completed'])
    {
        $sql = "SELECT COALESCE(SUM(
                    CASE
                      WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                      ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                    END
                  ),0) AS tickets
                FROM bookings
                WHERE 1=1";
        $params = [];
        if ($start !== null && $end !== null) {
            $sql .= " AND DATE(booking_date) BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }
        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = [];
            foreach ($statuses as $i => $s) {
                $ph = ":st{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $s;
            }
            $sql .= " AND status IN (" . implode(',', $placeholders) . ")";
        }
        return (int)$this->scalar($sql, $params);
    }

    // Doanh thu theo rạp
    public function revenueByCinema($start = null, $end = null, $statuses = ['confirmed','completed'])
    {
        $sql = "SELECT c.id, c.name, COALESCE(SUM(b.final_amount),0) AS revenue
                FROM bookings b
                LEFT JOIN cinemas c ON b.cinema_id = c.id
                WHERE 1=1";
        $params = [];
        if ($start !== null && $end !== null) {
            $sql .= " AND DATE(b.booking_date) BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }
        if (is_array($statuses) && count($statuses) > 0) {
            $placeholders = [];
            foreach ($statuses as $i => $s) {
                $ph = ":st{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $s;
            }
            $sql .= " AND b.status IN (" . implode(',', $placeholders) . ")";
        }
        $sql .= " GROUP BY c.id, c.name ORDER BY revenue DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Occupancy for a showtime
    public function occupancyForShowtime($showtimeId)
    {
        $sqlSold = "SELECT COALESCE(SUM(
                        CASE
                          WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                          ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                        END
                     ),0) AS sold
                   FROM bookings
                   WHERE showtime_id = :sid AND status IN ('confirmed','completed')";
        $stmt = $this->conn->prepare($sqlSold);
        $stmt->execute([':sid' => $showtimeId]);
        $sold = (int)$stmt->fetchColumn();

        $sqlSeatCount = "SELECT r.seat_count
                         FROM showtimes s
                         LEFT JOIN rooms r ON s.room_id = r.id
                         WHERE s.id = :sid
                         LIMIT 1";
        $stmt2 = $this->conn->prepare($sqlSeatCount);
        $stmt2->execute([':sid' => $showtimeId]);
        $seatCount = (int)$stmt2->fetchColumn();

        $occupancy = $seatCount > 0 ? ($sold / $seatCount) * 100 : 0;
        return [
            'seats_sold' => $sold,
            'seat_count' => $seatCount,
            'occupancy_percent' => round($occupancy, 2)
        ];
    }

    // Get bookings list with pagination
    public function getBookingsList($start = null, $end = null, $status = null, $page = 1, $perPage = 30)
    {
        $where = "WHERE 1=1";
        $params = [];
        if ($start !== null && $end !== null) {
            $where .= " AND DATE(b.booking_date) BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }
        if ($status) {
            $where .= " AND b.status = :st";
            $params[':st'] = $status;
        }

        // count total
        $countSql = "SELECT COUNT(*) FROM bookings b $where";
        $stmt = $this->conn->prepare($countSql);
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $sql = "SELECT b.*, u.full_name, u.email AS user_email, c.name AS cinema_name, r.name AS room_name
                FROM bookings b
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN cinemas c ON b.cinema_id = c.id
                LEFT JOIN rooms r ON b.room_id = r.id
                $where
                ORDER BY b.booking_date DESC
                LIMIT :lim OFFSET :off";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':lim', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':off', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => (int)ceil($total / $perPage)
        ];
    }
}
?>
