<?php
// DASHBOARD CONTROLLER - Xử lý trang chủ admin
// Chức năng: Hiển thị dashboard với thống kê tổng quan (số vé, doanh thu, phim, user, rạp, phòng)
class DashboardController
{
    public $conn; // Kết nối database (PDO) để query trực tiếp

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database để query thống kê
    }

    // Dashboard - Trang chủ admin (hiển thị thống kê tổng quan, top phim, đặt vé gần đây)
    public function index()
    {
        // Kiểm tra quyền admin, manager hoặc staff
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        // Xử lý AJAX request cho biểu đồ và thống kê
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            header('Content-Type: application/json');
            $year = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : null;
            $month = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : null;
            $period = isset($_GET['period']) ? $_GET['period'] : '7';
            
            $monthlyStats = $this->getMonthlyStats($year, $period);
            $totalRevenue = $this->getTotalRevenue($year, $month);
            $totalTickets = $this->getTotalTickets($year, $month);
            $topMovies = $this->getTopMovies(5, $year, $month);
            
            echo json_encode([
                'success' => true,
                'monthlyStats' => $monthlyStats,
                'totalRevenue' => $totalRevenue,
                'totalTickets' => $totalTickets,
                'topMovies' => $topMovies
            ]);
            exit;
        }
        
        // ============================================
        // LẤY THAM SỐ BỘ LỌC
        // ============================================
        $year = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : null;
        $month = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : null;
        $period = isset($_GET['period']) ? $_GET['period'] : '7';
        
        // ============================================
        // THỐNG KÊ TỔNG QUAN (có bộ lọc)
        // ============================================
        $totalStaff = $this->getTotalStaff(); // Tổng số nhân viên
        $totalTickets = $this->getTotalTickets($year, $month); // Tổng số vé đã bán (status = paid)
        $totalRevenue = $this->getTotalRevenue($year, $month); // Tổng doanh thu (từ bookings đã thanh toán)
        $totalRooms = $this->getTotalRooms(); // Tổng số phòng chiếu
        $totalMovies = $this->getTotalMovies(); // Tổng số phim
        $activeMovies = $this->getActiveMovies(); // Số phim đang hoạt động (status = active)
        $totalUsers = $this->getTotalUsers(); // Tổng số người dùng
        $activeUsers = $this->getActiveUsers(); // Số user đang hoạt động
        $bannedUsers = $this->getBannedUsers(); // Số user bị khóa
        $totalCinemas = $this->getTotalCinemas(); // Tổng số rạp
        
        // ============================================
        // THỐNG KÊ HÔM NAY
        // ============================================
        $todayBookings = $this->getTodayBookings(); // Số đặt vé hôm nay
        $todayRevenue = $this->getTodayRevenue(); // Doanh thu hôm nay
        
        // ============================================
        // THỐNG KÊ CHI TIẾT
        // ============================================
        $monthlyStats = $this->getMonthlyStats($year, $period); // Thống kê theo tháng
        $topMovies = $this->getTopMovies(5, $year, $month); // Top 5 phim bán chạy nhất
        $recentBookings = $this->getRecentBookings(); // 10 đặt vé gần đây nhất
        $bookingStatusStats = $this->getBookingStatusStats(); // Thống kê theo trạng thái (pending, paid, cancelled)

        // ============================================
        // RENDER VIEW
        // ============================================
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
            'bookingStatusStats' => $bookingStatusStats,
            'filterYear' => $year,
            'filterMonth' => $month,
            'filterPeriod' => $period
        ]);
    }

    // Lấy tổng số vé đã bán (có thể lọc theo năm/tháng, private helper)
    private function getTotalTickets($year = null, $month = null)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE status = 'paid'";
            
            if ($year !== null && $year > 0) {
                $sql .= " AND YEAR(booking_date) = :year";
            }
            if ($month !== null && $month > 0 && $month <= 12) {
                $sql .= " AND MONTH(booking_date) = :month";
            }
            
            $stmt = $this->conn->prepare($sql);
            
            if ($year !== null && $year > 0) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }
            if ($month !== null && $month > 0 && $month <= 12) {
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Lấy tổng doanh thu (có thể lọc theo năm/tháng, private helper)
    private function getTotalRevenue($year = null, $month = null)
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE status = 'paid'";
            
            if ($year !== null && $year > 0) {
                $sql .= " AND YEAR(booking_date) = :year";
            }
            if ($month !== null && $month > 0 && $month <= 12) {
                $sql .= " AND MONTH(booking_date) = :month";
            }
            
            $stmt = $this->conn->prepare($sql);
            
            if ($year !== null && $year > 0) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }
            if ($month !== null && $month > 0 && $month <= 12) {
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Lấy tổng số phòng chiếu (private helper)
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

    // Lấy tổng số phim (private helper)
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

    // Lấy tổng số người dùng (private helper)
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

    // Lấy tổng số rạp (private helper)
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

    // Lấy số đặt vé hôm nay (private helper)
    private function getTodayBookings()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM bookings WHERE DATE(booking_date) = CURDATE() AND status = 'paid'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Lấy doanh thu hôm nay (private helper)
    private function getTodayRevenue()
    {
        try {
            $sql = "SELECT SUM(final_amount) as total FROM bookings WHERE DATE(booking_date) = CURDATE() AND status = 'paid'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Lấy tổng số nhân viên (private helper)
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

    // Lấy số phim đang hoạt động (private helper)
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

    // Lấy số người dùng đang hoạt động (private helper)
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

    // Lấy số người dùng bị khóa (private helper)
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

    // Lấy thống kê theo tháng với bộ lọc (có thể lọc theo năm, period='7'/'12'/'24'/'all', private helper)
    private function getMonthlyStats($year = null, $period = '7')
    {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(booking_date, '%Y-%m') as month,
                        COUNT(*) as bookings,
                        COALESCE(SUM(final_amount), 0) as revenue
                    FROM bookings 
                    WHERE status = 'paid'";
            
            // Thêm điều kiện lọc theo năm
            if ($year !== null && $year > 0) {
                $sql .= " AND YEAR(booking_date) = :year";
            }
            
            // Thêm điều kiện lọc theo khoảng thời gian
            if ($period !== 'all') {
                $months = intval($period);
                if ($months > 0) {
                    $sql .= " AND booking_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)";
                }
            }
            
            $sql .= " GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                    ORDER BY month ASC";
            
            $stmt = $this->conn->prepare($sql);
            
            // Bind parameters
            if ($year !== null && $year > 0) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }
            if ($period !== 'all') {
                $months = intval($period);
                if ($months > 0) {
                    $stmt->bindValue(':months', $months - 1, PDO::PARAM_INT);
                }
            }
            
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            // Đảm bảo có dữ liệu cho tất cả các tháng trong khoảng thời gian
            if (empty($results) && $period !== 'all') {
                $months = intval($period);
                $results = [];
                for ($i = $months - 1; $i >= 0; $i--) {
                    $date = date('Y-m', strtotime("-$i months"));
                    $results[] = [
                        'month' => $date,
                        'bookings' => 0,
                        'revenue' => 0
                    ];
                }
            }
            
            return $results;
        } catch (Exception $e) {
            return [];
        }
    }

    // Lấy top phim bán chạy (có thể lọc theo năm/tháng, private helper)
    private function getTopMovies($limit = 5, $year = null, $month = null)
    {
        try {
            $sql = "SELECT 
                        m.id,
                        m.title,
                        m.image,
                        COUNT(b.id) as booking_count,
                        COALESCE(SUM(b.final_amount), 0) as revenue
                    FROM movies m
                    LEFT JOIN showtimes st ON m.id = st.movie_id
                    LEFT JOIN bookings b ON st.id = b.showtime_id AND b.status = 'paid'";
            
            // Thêm điều kiện lọc theo năm/tháng
            if ($year !== null && $year > 0) {
                $sql .= " AND YEAR(b.booking_date) = :year";
            }
            if ($month !== null && $month > 0 && $month <= 12) {
                $sql .= " AND MONTH(b.booking_date) = :month";
            }
            
            $sql .= " GROUP BY m.id, m.title, m.image
                    HAVING booking_count > 0
                    ORDER BY booking_count DESC, revenue DESC
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            
            if ($year !== null && $year > 0) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }
            if ($month !== null && $month > 0 && $month <= 12) {
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // Lấy đặt vé gần đây (private helper)
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

    // Lấy thống kê theo trạng thái đặt vé (private helper)
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

