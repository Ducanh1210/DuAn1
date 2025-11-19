<?php
// controllers/StatsController.php

class StatsController
{
    private $statsModel;

    public function __construct()
    {
        // Try a few common model paths so this file is portable between project layouts
        $tried = [];

        $candidates = [
            __DIR__ . '/../../models/models/Stats.php', // controllers/controllers -> models/models
            __DIR__ . '/../../models/Stats.php',        // controllers/controllers -> models
            __DIR__ . '/../models/Stats.php',           // controllers -> models
            __DIR__ . '/../../Models/Stats.php',        // case-insensitive variations
            './models/Stats.php'
        ];

        foreach ($candidates as $p) {
            if (file_exists($p)) {
                require_once $p;
                $tried[] = $p;
                break;
            } else {
                $tried[] = $p;
            }
        }

        if (!class_exists('Stats')) {
            // last attempt: try relative simple path (may throw)
            @require_once './models/Stats.php';
        }

        $this->statsModel = new Stats();
    }

    // Dashboard summary (JSON or view)
    public function index()
    {
        $from = $_GET['from'] ?? date('Y-m-d', strtotime('-6 days'));
        $to   = $_GET['to'] ?? date('Y-m-d');
        $from = date('Y-m-d', strtotime($from));
        $to   = date('Y-m-d', strtotime($to));
        $statuses = ['confirmed','completed'];

        $totalBookings = $this->statsModel->totalBookings($from, $to, $statuses);
        $totalRevenue  = $this->statsModel->totalRevenue($from, $to, $statuses);
        $revenueByRaw  = $this->statsModel->revenueByDay($from, $to, $statuses);
        $topMovies = $this->statsModel->topMoviesByRevenue($from, $to, 10, $statuses);
        $totalTickets = $this->statsModel->totalTicketsSold($from, $to, $statuses);
        $revenueByCinema = $this->statsModel->revenueByCinema($from, $to, $statuses);

        // convert revenueByRaw to simpler maps for some views
        $revenueByDay = [];
        $bookingsCountByDay = [];
        foreach ($revenueByRaw as $d => $v) {
            $revenueByDay[$d] = $v['revenue'];
            $bookingsCountByDay[$d] = $v['bookings'];
        }

        $data = [
            'from' => $from,
            'to' => $to,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'revenueByDay' => $revenueByDay,
            'bookingsCountByDay' => $bookingsCountByDay,
            'topMovies' => $topMovies,
            'totalTickets' => $totalTickets,
            'revenueByCinema' => $revenueByCinema
        ];

        // JSON API support
        if ((isset($_GET['format']) && $_GET['format'] === 'json') ||
            (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            return;
        }

        // Prepare variables and render using main layout
        extract($data);

        // set the view path that main.php expects (main.php reads $GLOBALS['viewPath'])
        $GLOBALS['viewPath'] = 'admin/dashboard.php';

        // Find main layout path (try common locations)
        $layoutCandidates = [
            __DIR__ . '/../../views/views/layout/main.php',
            __DIR__ . '/../../views/layout/main.php',
            __DIR__ . '/../views/layout/main.php',
            __DIR__ . '/../../views/views/layout/main.php'
        ];
        $layoutPath = null;
        foreach ($layoutCandidates as $lp) {
            if (file_exists($lp)) {
                $layoutPath = $lp;
                break;
            }
        }

        if ($layoutPath) {
            require $layoutPath;
            return;
        }

        // fallback: directly include view (no layout)
        require __DIR__ . '/../../views/views/admin/dashboard.php';
    }

    // API endpoint for chart data
    public function data()
    {
        $from = $_GET['from'] ?? date('Y-m-d', strtotime('-6 days'));
        $to   = $_GET['to'] ?? date('Y-m-d');
        $from = date('Y-m-d', strtotime($from));
        $to = date('Y-m-d', strtotime($to));
        $statuses = ['confirmed','completed'];

        $revenueByRaw = $this->statsModel->revenueByDay($from, $to, $statuses);
        $revenueByDay = [];
        foreach ($revenueByRaw as $d => $v) {
            $revenueByDay[$d] = $v['revenue'];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'from' => $from,
            'to' => $to,
            'revenueByDay' => $revenueByDay,
            'topMovies' => $this->statsModel->topMoviesByRevenue($from, $to, 10, $statuses)
        ], JSON_UNESCAPED_UNICODE);
    }

    // List view (thống kê danh sách). Uses layout/main.php by setting $GLOBALS['viewPath'].
    public function list()
    {
        $from = $_GET['from'] ?? date('Y-m-d', strtotime('-6 days'));
        $to   = $_GET['to'] ?? date('Y-m-d');
        $from = date('Y-m-d', strtotime($from));
        $to = date('Y-m-d', strtotime($to));
        $mode = $_GET['mode'] ?? 'days';
        $status = $_GET['status'] ?? null;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 20;

        if ($mode === 'bookings') {
            $res = $this->statsModel->getBookingsList($from, $to, $status, $page, $perPage);
            $bookingsList = $res['data'];
            $pagination = [
                'current' => $res['page'],
                'perPage' => $res['perPage'],
                'total' => $res['total'],
                'total_pages' => $res['totalPages']
            ];
            $data = [
                'from' => $from,
                'to' => $to,
                'mode' => 'bookings',
                'bookingsList' => $bookingsList,
                'pagination' => $pagination
            ];
        } else {
            $revenueByRaw = $this->statsModel->revenueByDay($from, $to);
            $revenueByDay = [];
            $bookingsCountByDay = [];
            foreach ($revenueByRaw as $d => $v) {
                $revenueByDay[$d] = $v['revenue'];
                $bookingsCountByDay[$d] = $v['bookings'];
            }
            $data = [
                'from' => $from,
                'to' => $to,
                'mode' => 'days',
                'revenueByDay' => $revenueByDay,
                'bookingsCountByDay' => $bookingsCountByDay
            ];
        }

        // prepare view variables
        extract($data);

        // set viewPath for main.php to include the correct view
        $GLOBALS['viewPath'] = 'admin/thongke/list.php';

        // require layout (same logic as index)
        $layoutCandidates = [
            __DIR__ . '/../../views/views/layout/main.php',
            __DIR__ . '/../../views/layout/main.php',
            __DIR__ . '/../views/layout/main.php',
            __DIR__ . '/../../views/views/layout/main.php'
        ];
        $layoutPath = null;
        foreach ($layoutCandidates as $lp) {
            if (file_exists($lp)) {
                $layoutPath = $lp;
                break;
            }
        }

        if ($layoutPath) {
            require $layoutPath;
            return;
        }

        // fallback: require view directly (no layout)
        require __DIR__ . '/../../views/views/admin/thongke/list.php';
    }
    public function show()
{
    $date = $_GET['date'] ?? null;
    if (!$date) {
        // nếu không có ngày thì quay lại list hoặc hiển thị thông báo
        header('Location: ?act=thongke');
        exit;
    }

    // Lấy danh sách booking theo ngày (không phân trang, hiển thị toàn bộ)
    $res = $this->statsModel->getBookingsList($date, $date, null, 1, 99999);
    $bookingsList = $res['data'] ?? [];

    // Truyền vào view
    $data = [
        'date' => $date,
        'bookingsList' => $bookingsList
    ];

    // Biến sẽ dùng trong view
    extract($data);

    // Set viewPath để main.php include đúng view (main.php dùng $GLOBALS['viewPath'])
    $GLOBALS['viewPath'] = 'admin/thongke/show.php';

    // Tìm file layout main.php ở các vị trí phổ biến (tương đối với thư mục controllers)
    $layoutCandidates = [
        // controllers/ -> ../views/layout/main.php
        __DIR__ . '/../views/layout/main.php',
        // controllers/ -> ../../views/layout/main.php (nếu controllers nằm ở controllers/controllers)
        __DIR__ . '/../../views/layout/main.php',
        // controllers/ -> ../views/views/layout/main.php (nếu có thư mục views/views)
        __DIR__ . '/../views/views/layout/main.php',
        // controllers/ -> ../../views/views/layout/main.php
        __DIR__ . '/../../views/views/layout/main.php',
        // fallback, relative to project root
        __DIR__ . '/../../views/layout/main.php'
    ];

    $layoutPath = null;
    foreach ($layoutCandidates as $lp) {
        if (file_exists($lp)) {
            $layoutPath = $lp;
            break;
        }
    }

    if ($layoutPath) {
        require $layoutPath;
        return;
    }

    // Nếu không tìm thấy layout, thử include view trực tiếp (fallback)
    $viewDirect = __DIR__ . '/../views/admin/thongke/show.php';
    if (!file_exists($viewDirect)) {
        // Thử alternative path (nếu cấu trúc khác)
        $viewDirect = __DIR__ . '/../../views/views/admin/thongke/show.php';
    }

    if (file_exists($viewDirect)) {
        require $viewDirect;
        return;
    }

    // Nếu vẫn không tìm thấy, hiển thị lỗi có ích để debug (không phải stacktrace)
    echo "<h3>Không tìm thấy layout hoặc view cho trang chi tiết thống kê</h3>";
    echo "<p>Tìm các đường dẫn sau nhưng không tồn tại:</p><ul>";
    foreach ($layoutCandidates as $c) {
        echo "<li>" . htmlspecialchars($c) . "</li>";
    }
    echo "</ul>";
    echo "<p>và các view thử: " . htmlspecialchars(__DIR__ . '/../views/admin/thongke/show.php') . " hoặc " . htmlspecialchars(__DIR__ . '/../../views/views/admin/thongke/show.php') . "</p>";
}


}
?>
