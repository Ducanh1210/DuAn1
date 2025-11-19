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
}
?>
