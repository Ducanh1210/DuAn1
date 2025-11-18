<?php
class DashboardController
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Hiển thị dashboard với thống kê
     */
    public function index()
    {
        // Kiểm tra quyền admin hoặc staff
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        // Thống kê nhân viên
        $totalStaff = $this->getTotalStaff();
        
        // Thống kê số vé đã bán
        $totalTickets = $this->getTotalTickets();
        
        // Thống kê doanh thu (bán vé)
        $totalRevenue = $this->getTotalRevenue();
        
        // Thống kê số phòng chiếu
        $totalRooms = $this->getTotalRooms();
        
        // Thống kê số phim
        $totalMovies = $this->getTotalMovies();
        $activeMovies = $this->getActiveMovies();
        
        // Thống kê số người dùng
        $totalUsers = $this->getTotalUsers();
        $activeUsers = $this->getActiveUsers();
        $bannedUsers = $this->getBannedUsers();
        
        // Thống kê số rạp
        $totalCinemas = $this->getTotalCinemas();
        
        // Thống kê số đặt vé hôm nay
        $todayBookings = $this->getTodayBookings();
        
        // Doanh thu hôm nay
        $todayRevenue = $this->getTodayRevenue();
        
        // Thống kê theo tháng (7 tháng gần nhất)
        $monthlyStats = $this->getMonthlyStats();
        
        // Top phim bán chạy
        $topMovies = $this->getTopMovies();
        
        // Đặt vé gần đây
        $recentBookings = $this->getRecentBookings();
        
        // Thống kê theo trạng thái đặt vé
        $bookingStatusStats = $this->getBookingStatusStats();

        render('admin/dashboard.php', [
            'totalStaff' => $totalStaff,
            'totalTickets' => $totalTickets,
            'totalRevenue' => $totalRevenue,
            'totalRooms' => $totalRooms,
            'totalMovies' => $totalMovies,
            'activeMovies' => $activeMovies,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'bannedUsers' => $bannedUsers,
            'totalCinemas' => $totalCinemas,
            'todayBookings' => $todayBookings,
            'todayRevenue' => $todayRevenue,
            'monthlyStats' => $monthlyStats,
            'topMovies' => $topMovies,
            'recentBookings' => $recentBookings,
            'bookingStatusStats' => $bookingStatusStats
        ]);
    }

    /**
     * Lấy tổng số vé đã bán
     */
    private function getTotalTickets()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE status = 'confirmed' OR status = 'completed'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng doanh thu
     */
    private function getTotalRevenue()
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE status = 'confirmed' OR status = 'completed'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng số phòng chiếu
     */
    private function getTotalRooms()
    {
        try {
            // Thử query từ bảng rooms trước
            $sql = "SELECT COUNT(*) as total FROM rooms";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            // Nếu không có bảng rooms, thử cinema_rooms
            try {
                $sql = "SELECT COUNT(*) as total FROM cinema_rooms";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch();
                return $result['total'] ?? 0;
            } catch (Exception $e2) {
                return 0;
            }
        }
    }

    /**
     * Lấy tổng số phim
     */
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

    /**
     * Lấy tổng số người dùng
     */
    private function getTotalUsers()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng số rạp
     */
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

    /**
     * Lấy số đặt vé hôm nay
     */
    private function getTodayBookings()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE DATE(booking_date) = CURDATE()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy doanh thu hôm nay
     */
    private function getTodayRevenue()
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE DATE(booking_date) = CURDATE() AND (status = 'confirmed' OR status = 'completed')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng số nhân viên
     */
    private function getTotalStaff()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'staff'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy số phim đang hoạt động
     */
    private function getActiveMovies()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM movies WHERE status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy số người dùng đang hoạt động
     */
    private function getActiveUsers()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE status = 'active' OR status IS NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy số người dùng bị khóa
     */
    private function getBannedUsers()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE status = 'banned'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy thống kê theo tháng (7 tháng gần nhất)
     */
    private function getMonthlyStats()
    {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(booking_date, '%Y-%m') as month,
                        COUNT(*) as bookings,
                        SUM(final_amount) as revenue
                    FROM bookings 
                    WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                    AND (status = 'confirmed' OR status = 'completed')
                    GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                    ORDER BY month ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy top phim bán chạy
     */
    private function getTopMovies($limit = 5)
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
                    LEFT JOIN bookings b ON st.id = b.showtime_id
                    WHERE b.status IN ('confirmed', 'completed') OR b.id IS NULL
                    GROUP BY m.id, m.title, m.image
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

    /**
     * Lấy đặt vé gần đây
     */
    private function getRecentBookings($limit = 10)
    {
        try {
            $sql = "SELECT 
                        b.*,
                        u.full_name as user_name,
                        u.email as user_email,
                        m.title as movie_title,
                        st.show_date,
                        st.start_time
                    FROM bookings b
                    LEFT JOIN users u ON b.user_id = u.id
                    LEFT JOIN showtimes st ON b.showtime_id = st.id
                    LEFT JOIN movies m ON st.movie_id = m.id
                    ORDER BY b.booking_date DESC
                    LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy thống kê theo trạng thái đặt vé
     */
    private function getBookingStatusStats()
    {
        try {
            $sql = "SELECT 
                        status,
                        COUNT(*) as count
                    FROM bookings
                    GROUP BY status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

?>

