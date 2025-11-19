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
        // Thống kê nhân viên (giả sử 4 nhân viên, có thể lấy từ database sau)
        $totalStaff = 4; // Có thể query từ bảng users WHERE role = 'staff'
        
        // Thống kê số vé đã bán
        $totalTickets = $this->getTotalTickets();
        
        // Thống kê doanh thu (bán vé)
        $totalRevenue = $this->getTotalRevenue();
        
        // Thống kê số phòng chiếu
        $totalRooms = $this->getTotalRooms();
        
        // Thống kê số phim
        $totalMovies = $this->getTotalMovies();
        
        // Thống kê số người dùng
        $totalUsers = $this->getTotalUsers();
        
        // Thống kê số rạp
        $totalCinemas = $this->getTotalCinemas();
        
        // Thống kê số đặt vé hôm nay
        $todayBookings = $this->getTodayBookings();
        
        // Doanh thu hôm nay
        $todayRevenue = $this->getTodayRevenue();

        render('admin/dashboard.php', [
            'totalStaff' => $totalStaff,
            'totalTickets' => $totalTickets,
            'totalRevenue' => $totalRevenue,
            'totalRooms' => $totalRooms,
            'totalMovies' => $totalMovies,
            'totalUsers' => $totalUsers,
            'totalCinemas' => $totalCinemas,
            'todayBookings' => $todayBookings,
            'todayRevenue' => $todayRevenue
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
}

?>

