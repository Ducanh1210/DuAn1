<?php
// STATISTICS CONTROLLER - Xử lý trang thống kê
// Chức năng: Thống kê doanh thu, số vé, phim bán chạy, biểu đồ theo tháng/ngày (Admin/Manager/Staff)
class StatisticsController
{
    public $conn; // Kết nối database để query trực tiếp

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database
    }

    // Trang thống kê - doanh thu, số vé, phim bán chạy, biểu đồ (có thể lọc theo năm/tháng)
    public function index()
    {
        // Kiểm tra quyền admin hoặc staff
        require_once __DIR__ . '/../commons/auth.php';
        requireAdminOrStaff();
        
        // Xử lý AJAX request cho biểu đồ
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            header('Content-Type: application/json');
            $year = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : null;
            $month = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : null;
            
            $cinemaId = null;
            if (isManager() || isStaff()) {
                $cinemaId = getCurrentCinemaId();
            }
            
            $movieStats = $this->getMoviesByMonth($year, $month, $cinemaId);
            echo json_encode([
                'success' => true,
                'movieStats' => $movieStats
            ]);
            exit;
        }
        
        // Lấy cinema_id nếu là manager hoặc staff
        $cinemaId = null;
        $cinemaName = null;
        if (isManager() || isStaff()) {
            $cinemaId = getCurrentCinemaId();
            if ($cinemaId) {
                require_once __DIR__ . '/../models/Cinema.php';
                $cinemaModel = new Cinema();
                $cinema = $cinemaModel->find($cinemaId);
                $cinemaName = $cinema['name'] ?? null;
            }
        }
        
        // Lấy tham số bộ lọc
        $filterYear = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : null;
        $filterMonth = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : null;
        
        // Thống kê tổng quan
        $stats = [
            'totalBookings' => $this->getTotalBookings($cinemaId),
            'totalRevenue' => $this->getTotalRevenue($cinemaId),
            'todayBookings' => $this->getTodayBookings($cinemaId),
            'todayRevenue' => $this->getTodayRevenue($cinemaId),
            'thisMonthBookings' => $this->getThisMonthBookings($cinemaId),
            'thisMonthRevenue' => $this->getThisMonthRevenue($cinemaId),
            'totalUsers' => $this->getTotalUsers(),
            'totalMovies' => $this->getTotalMovies(),
            'totalCinemas' => $this->getTotalCinemas(),
            'totalRooms' => $this->getTotalRooms($cinemaId)
        ];
        
        // Thống kê theo tháng (12 tháng gần nhất)
        $monthlyStats = $this->getMonthlyStats(12, $cinemaId);
        
        // Thống kê theo ngày (30 ngày gần nhất)
        $dailyStats = $this->getDailyStats(30, $cinemaId);
        
        // Top phim bán chạy (theo rạp nếu có, có thể lọc theo tháng/năm)
        $topMovies = $this->getTopMovies(5, $cinemaId, $filterYear, $filterMonth);
        
        // Top phim hot nhất (theo rạp nếu có, có thể lọc theo tháng/năm)
        $hotMovies = $this->getHotMovies(5, $cinemaId, $filterYear, $filterMonth);
        
        // Top rạp doanh thu cao (chỉ admin mới thấy)
        $topCinemas = null;
        if (isAdmin()) {
            $topCinemas = $this->getTopCinemas(10);
        }
        
        // Thống kê theo trạng thái đặt vé
        $bookingStatusStats = $this->getBookingStatusStats($cinemaId);
        
        // Thống kê theo phương thức thanh toán
        $paymentMethodStats = $this->getPaymentMethodStats($cinemaId);
        
        // Thống kê phim theo tháng/năm được chọn
        $movieStats = $this->getMoviesByMonth($filterYear, $filterMonth, $cinemaId);
        
        render('admin/statistics/index.php', [
            'stats' => $stats,
            'monthlyStats' => $monthlyStats,
            'dailyStats' => $dailyStats,
            'topMovies' => $topMovies,
            'hotMovies' => $hotMovies,
            'topCinemas' => $topCinemas,
            'bookingStatusStats' => $bookingStatusStats,
            'paymentMethodStats' => $paymentMethodStats,
            'movieStats' => $movieStats,
            'cinemaId' => $cinemaId,
            'cinemaName' => $cinemaName,
            'filterYear' => $filterYear,
            'filterMonth' => $filterMonth
        ]);
    }

    private function getTotalBookings($cinemaId = null)
{
    try {
        $whereClause = "WHERE status = 'paid'";
        $params = [];
        
        if ($cinemaId) {
            $whereClause .= " AND cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        // Đếm số vé thay vì số booking
        $sql = "SELECT SUM(
                    CASE 
                        WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                        ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                    END
                ) as total FROM bookings $whereClause";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

    private function getTotalRevenue($cinemaId = null)
    {
        try {
            $whereClause = "WHERE status = 'paid'";
            $params = [];
            
            if ($cinemaId) {
                $whereClause .= " AND cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT SUM(final_amount) as total FROM bookings $whereClause";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTodayBookings($cinemaId = null)
{
    try {
        $whereClause = "WHERE DATE(booking_date) = CURDATE() AND status = 'paid'";
        $params = [];
        
        if ($cinemaId) {
            $whereClause .= " AND cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        $sql = "SELECT SUM(
                    CASE 
                        WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                        ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                    END
                ) as total FROM bookings $whereClause";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

    private function getTodayRevenue($cinemaId = null)
    {
        try {
            $whereClause = "WHERE DATE(booking_date) = CURDATE() AND status = 'paid'";
            $params = [];
            
            if ($cinemaId) {
                $whereClause .= " AND cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT SUM(final_amount) as total FROM bookings $whereClause";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getThisMonthBookings($cinemaId = null)
{
    try {
        $whereClause = "WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE()) AND status = 'paid'";
        $params = [];
        
        if ($cinemaId) {
            $whereClause .= " AND cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        // Đếm số vé thay vì số booking
        $sql = "SELECT SUM(
                    CASE 
                        WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                        ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                    END
                ) as total FROM bookings $whereClause";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

    private function getThisMonthRevenue($cinemaId = null)
    {
        try {
            $whereClause = "WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE()) AND status = 'paid'";
            $params = [];
            
            if ($cinemaId) {
                $whereClause .= " AND cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT SUM(final_amount) as total FROM bookings $whereClause";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
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

    private function getTotalRooms($cinemaId = null)
    {
        try {
            $whereClause = "";
            $params = [];
            
            if ($cinemaId) {
                $whereClause = "WHERE cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT COUNT(*) as total FROM rooms $whereClause";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getMonthlyStats($months = 12, $cinemaId = null)
{
    try {
        $whereClause = "WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                AND status = 'paid'";
        $params = [':months' => $months];
        
        if ($cinemaId) {
            $whereClause .= " AND cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        $sql = "SELECT 
                    DATE_FORMAT(booking_date, '%Y-%m') as month,
                    DATE_FORMAT(booking_date, '%m/%Y') as month_label,
                    SUM(
                        CASE 
                            WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                            ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                        END
                    ) as bookings,
                    SUM(final_amount) as revenue
                FROM bookings 
                $whereClause
                GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                ORDER BY month ASC";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            if ($key === ':months') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

    private function getDailyStats($days = 30, $cinemaId = null)
{
    try {
        $whereClause = "WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                AND status = 'paid'";
        $params = [':days' => $days];
        
        if ($cinemaId) {
            $whereClause .= " AND cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        $sql = "SELECT 
                    DATE(booking_date) as date,
                    DATE_FORMAT(booking_date, '%d/%m/%Y') as date_label,
                    SUM(
                        CASE 
                            WHEN booked_seats IS NULL OR booked_seats = '' THEN 0
                            ELSE (LENGTH(booked_seats) - LENGTH(REPLACE(booked_seats, ',', '')) + 1)
                        END
                    ) as bookings,
                    SUM(final_amount) as revenue
                FROM bookings 
                $whereClause
                GROUP BY DATE(booking_date)
                ORDER BY date ASC";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            if ($key === ':days') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}


    private function getTopMovies($limit = 10, $cinemaId = null, $year = null, $month = null)
{
    try {
        $joinClause = "";
        $whereClause = "WHERE b.status = 'paid'";
        $params = [];
        
        // Lọc theo năm
        if ($year !== null && $year > 0) {
            $whereClause .= " AND YEAR(b.booking_date) = :year";
            $params[':year'] = $year;
        }
        
        // Lọc theo tháng
        if ($month !== null && $month > 0 && $month <= 12) {
            $whereClause .= " AND MONTH(b.booking_date) = :month";
            $params[':month'] = $month;
        }
        
        // Lọc theo rạp
        if ($cinemaId) {
            $joinClause = "LEFT JOIN rooms r ON st.room_id = r.id";
            $whereClause .= " AND r.cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        $sql = "SELECT 
                    m.id,
                    m.title,
                    m.image,
                    SUM(
                        CASE 
                            WHEN b.booked_seats IS NULL OR b.booked_seats = '' THEN 0
                            ELSE (LENGTH(b.booked_seats) - LENGTH(REPLACE(b.booked_seats, ',', '')) + 1)
                        END
                    ) as booking_count,
                    COALESCE(SUM(b.final_amount), 0) as revenue
                FROM movies m
                LEFT JOIN showtimes st ON m.id = st.movie_id
                $joinClause
                LEFT JOIN bookings b ON st.id = b.showtime_id $whereClause
                GROUP BY m.id, m.title, m.image
                HAVING booking_count > 0
                ORDER BY booking_count DESC, revenue DESC
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}


    private function getHotMovies($limit = 10, $cinemaId = null, $year = null, $month = null)
{
    try {
        $joinClause = "";
        $whereClause = "WHERE b.status = 'paid'";
        $params = [];
        
        if ($year !== null && $year > 0) {
            $whereClause .= " AND YEAR(b.booking_date) = :year";
            $params[':year'] = $year;
        }
        if ($month !== null && $month > 0 && $month <= 12) {
            $whereClause .= " AND MONTH(b.booking_date) = :month";
            $params[':month'] = $month;
        } else if ($year === null && $month === null) {
            $whereClause .= " AND b.booking_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        }
        
        if ($cinemaId) {
            $joinClause = "LEFT JOIN rooms r ON st.room_id = r.id";
            $whereClause .= " AND r.cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        $sql = "SELECT 
                    m.id,
                    m.title,
                    m.image,
                    SUM(
                        CASE 
                            WHEN b.booked_seats IS NULL OR b.booked_seats = '' THEN 0
                            ELSE (LENGTH(b.booked_seats) - LENGTH(REPLACE(b.booked_seats, ',', '')) + 1)
                        END
                    ) as booking_count,
                    COALESCE(SUM(b.final_amount), 0) as revenue
                FROM movies m
                LEFT JOIN showtimes st ON m.id = st.movie_id
                $joinClause
                LEFT JOIN bookings b ON st.id = b.showtime_id $whereClause
                GROUP BY m.id, m.title, m.image
                HAVING booking_count > 0
                ORDER BY revenue DESC, booking_count DESC
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
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
                    SUM(
                        CASE 
                            WHEN b.booked_seats IS NULL OR b.booked_seats = '' THEN 0
                            ELSE (LENGTH(b.booked_seats) - LENGTH(REPLACE(b.booked_seats, ',', '')) + 1)
                        END
                    ) as booking_count,
                    SUM(b.final_amount) as revenue
                FROM cinemas c
                LEFT JOIN rooms r ON c.id = r.cinema_id
                LEFT JOIN bookings b ON r.id = b.room_id AND b.status = 'paid'
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

    private function getBookingStatusStats($cinemaId = null)
    {
        try {
            $whereClause = "";
            $params = [];
            
            if ($cinemaId) {
                $whereClause = "WHERE cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT 
                        status,
                        COUNT(*) as count,
                        SUM(final_amount) as revenue
                    FROM bookings
                    $whereClause
                    GROUP BY status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getPaymentMethodStats($cinemaId = null)
    {
        try {
            $joinClause = "";
            $whereClause = "WHERE p.status = 'paid'";
            $params = [];
            
            if ($cinemaId) {
                $joinClause = "LEFT JOIN bookings b ON p.booking_id = b.id";
                $whereClause .= " AND b.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            $sql = "SELECT 
                        p.method,
                        COUNT(*) as count,
                        SUM(p.final_amount) as revenue
                    FROM payments p
                    $joinClause
                    $whereClause
                    GROUP BY p.method";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

   
    private function getMoviesByMonth($year = null, $month = null, $cinemaId = null)
{
    try {
        $joinClause = "";
        $whereClause = "WHERE b.status = 'paid'";
        $params = [];
        
        if ($year !== null && $year > 0) {
            $whereClause .= " AND YEAR(b.booking_date) = :year";
            $params[':year'] = $year;
        }
        
        if ($month !== null && $month > 0 && $month <= 12) {
            $whereClause .= " AND MONTH(b.booking_date) = :month";
            $params[':month'] = $month;
        }
        
      
        if ($cinemaId) {
            $joinClause = "LEFT JOIN rooms r ON st.room_id = r.id";
            $whereClause .= " AND r.cinema_id = :cinema_id";
            $params[':cinema_id'] = $cinemaId;
        }
        
        $sql = "SELECT 
                    m.id,
                    m.title,
                    m.image,
                    SUM(
                        CASE 
                            WHEN b.booked_seats IS NULL OR b.booked_seats = '' THEN 0
                            ELSE (LENGTH(b.booked_seats) - LENGTH(REPLACE(b.booked_seats, ',', '')) + 1)
                        END
                    ) as booking_count,
                    COALESCE(SUM(b.final_amount), 0) as revenue
                FROM movies m
                LEFT JOIN showtimes st ON m.id = st.movie_id
                $joinClause
                LEFT JOIN bookings b ON st.id = b.showtime_id $whereClause
                GROUP BY m.id, m.title, m.image
                HAVING booking_count > 0
                ORDER BY booking_count DESC, revenue DESC
                LIMIT 20";
        
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
}

?>

