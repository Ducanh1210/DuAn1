<?php
class StatisticsController
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Hiển thị trang thống kê
     */
    public function index()
    {
        // Kiểm tra quyền admin hoặc staff
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        // Thống kê tổng quan
        $stats = [
            'totalBookings' => $this->getTotalBookings(),
            'totalRevenue' => $this->getTotalRevenue(),
            'todayBookings' => $this->getTodayBookings(),
            'todayRevenue' => $this->getTodayRevenue(),
            'thisMonthBookings' => $this->getThisMonthBookings(),
            'thisMonthRevenue' => $this->getThisMonthRevenue(),
            'totalUsers' => $this->getTotalUsers(),
            'totalMovies' => $this->getTotalMovies(),
            'totalCinemas' => $this->getTotalCinemas(),
            'totalRooms' => $this->getTotalRooms()
        ];
        
        // Thống kê theo tháng (12 tháng gần nhất)
        $monthlyStats = $this->getMonthlyStats(12);
        
        // Thống kê theo ngày (30 ngày gần nhất)
        $dailyStats = $this->getDailyStats(30);
        
        // Top phim bán chạy
        $topMovies = $this->getTopMovies(10);
        
        // Top rạp doanh thu cao
        $topCinemas = $this->getTopCinemas(10);
        
        // Thống kê theo trạng thái đặt vé
        $bookingStatusStats = $this->getBookingStatusStats();
        
        // Thống kê theo phương thức thanh toán
        $paymentMethodStats = $this->getPaymentMethodStats();
        
        render('admin/statistics/index.php', [
            'stats' => $stats,
            'monthlyStats' => $monthlyStats,
            'dailyStats' => $dailyStats,
            'topMovies' => $topMovies,
            'topCinemas' => $topCinemas,
            'bookingStatusStats' => $bookingStatusStats,
            'paymentMethodStats' => $paymentMethodStats
        ]);
    }

    private function getTotalBookings()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE status IN ('paid', 'confirmed', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalRevenue()
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE status IN ('paid', 'confirmed', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTodayBookings()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE DATE(booking_date) = CURDATE() AND status IN ('paid', 'confirmed', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTodayRevenue()
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE DATE(booking_date) = CURDATE() AND status IN ('paid', 'confirmed', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getThisMonthBookings()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE()) AND status IN ('paid', 'confirmed', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getThisMonthRevenue()
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE()) AND status IN ('paid', 'confirmed', 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalUsers()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalMovies()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM movies";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalCinemas()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM cinemas";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalRooms()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM rooms";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getMonthlyStats($months = 12)
    {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(booking_date, '%Y-%m') as month,
                        DATE_FORMAT(booking_date, '%m/%Y') as month_label,
                        COUNT(*) as bookings,
                        SUM(final_amount) as revenue
                    FROM bookings 
                    WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                    AND status IN ('paid', 'confirmed', 'completed')
                    GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                    ORDER BY month ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':months', $months, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getDailyStats($days = 30)
    {
        try {
            $sql = "SELECT 
                        DATE(booking_date) as date,
                        DATE_FORMAT(booking_date, '%d/%m/%Y') as date_label,
                        COUNT(*) as bookings,
                        SUM(final_amount) as revenue
                    FROM bookings 
                    WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                    AND status IN ('paid', 'confirmed', 'completed')
                    GROUP BY DATE(booking_date)
                    ORDER BY date ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getTopMovies($limit = 10)
    {
        try {
            $sql = "SELECT 
                        m.id,
                        m.title,
                        m.image,
                        COUNT(b.id) as booking_count,
                        SUM(b.final_amount) as revenue
                    FROM movies m
                    LEFT JOIN showtimes st ON m.id = st.movie_id
                    LEFT JOIN bookings b ON st.id = b.showtime_id AND b.status IN ('paid', 'confirmed', 'completed')
                    GROUP BY m.id, m.title, m.image
                    HAVING booking_count > 0
                    ORDER BY booking_count DESC, revenue DESC
                    LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getTopCinemas($limit = 10)
    {
        try {
            $sql = "SELECT 
                        c.id,
                        c.name,
                        COUNT(b.id) as booking_count,
                        SUM(b.final_amount) as revenue
                    FROM cinemas c
                    LEFT JOIN rooms r ON c.id = r.cinema_id
                    LEFT JOIN bookings b ON r.id = b.room_id AND b.status IN ('paid', 'confirmed', 'completed')
                    GROUP BY c.id, c.name
                    HAVING booking_count > 0
                    ORDER BY revenue DESC, booking_count DESC
                    LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getBookingStatusStats()
    {
        try {
            $sql = "SELECT 
                        status,
                        COUNT(*) as count,
                        SUM(final_amount) as revenue
                    FROM bookings
                    GROUP BY status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getPaymentMethodStats()
    {
        try {
            $sql = "SELECT 
                        p.method,
                        COUNT(*) as count,
                        SUM(p.final_amount) as revenue
                    FROM payments p
                    WHERE p.status = 'paid'
                    GROUP BY p.method";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

?>

