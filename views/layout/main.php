<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - <?= htmlspecialchars(ucfirst($_GET['act'] ?? 'Dashboard')) ?></title>

  <!-- Bootstrap CSS (optional, giúp layout nhanh và đẹp) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/main.css">

</head>
<body>

  <!-- SIDEBAR -->
  <nav id="sidebar" class="sidebar" aria-label="Sidebar menu">
    <div class="sidebar-header text-center">
      <div class="brand">
        <i class="bi bi-shield-lock icon-only"></i>
        <span class="icon-only">T</span>
        <span class="full">Admin TicketHub</span>
      </div>
    </div>

    <!-- Links (PHP logic for active) -->
    <a href="?act=dashboard" class="<?= ($_GET['act'] ?? '') == 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i><span class="label">Dashboard</span>
    </a>
    <a href="?act=/" class="<?= in_array($_GET['act'] ?? '', ['/', 'movies-create', 'movies-edit', 'movies-show', 'movies-delete']) ? 'active' : '' ?>">
      <i class="bi bi-film"></i><span class="label">Quản lý Phim</span>
    </a>
    <a href="?act=genres" class="<?= in_array($_GET['act'] ?? '', ['genres', 'genres-create', 'genres-edit', 'genres-delete']) ? 'active' : '' ?>">
      <i class="bi bi-tags"></i><span class="label">Quản lý Thể loại</span>
    </a>
    <a href="?act=showtimes" class="<?= in_array($_GET['act'] ?? '', ['showtimes', 'showtimes-create', 'showtimes-edit', 'showtimes-show', 'showtimes-delete']) ? 'active' : '' ?>">
      <i class="bi bi-clock-history"></i><span class="label">Quản lý Lịch chiếu</span>
    </a>
    <a href="?act=users" class="<?= in_array($_GET['act'] ?? '', ['users', 'users-create', 'users-edit', 'users-show', 'users-delete']) ? 'active' : '' ?>">
      <i class="bi bi-people"></i><span class="label">Quản lý Người dùng</span>
    </a>
    <a href="?act=permissions" class="<?= in_array($_GET['act'] ?? '', ['permissions', 'permissions-assign']) ? 'active' : '' ?>">
      <i class="bi bi-shield-check"></i><span class="label">Phân quyền</span>
    </a>
    <a href="?act=cinemas" class="<?= in_array($_GET['act'] ?? '', ['cinemas', 'cinemas-create', 'cinemas-edit', 'cinemas-delete']) ? 'active' : '' ?>">
      <i class="bi bi-building"></i><span class="label">Quản lý Rạp</span>
    </a>
    <a href="?act=rooms" class="<?= in_array($_GET['act'] ?? '', ['rooms', 'rooms-create', 'rooms-edit', 'rooms-show', 'rooms-delete']) ? 'active' : '' ?>">
      <i class="bi bi-camera-reels"></i><span class="label">Quản lý Phòng Chiếu</span>
    </a>
    <a href="?act=seats" class="<?= in_array($_GET['act'] ?? '', ['seats', 'seats-create', 'seats-edit', 'seats-show', 'seats-delete', 'seats-seatmap', 'seats-generate']) ? 'active' : '' ?>">
      <i class="bi bi-grid-3x3-gap"></i><span class="label">Quản lý Ghế</span>
    </a>
    <a href="?act=comments" class="<?= in_array($_GET['act'] ?? '', ['comments', 'comments-show', 'comments-delete']) ? 'active' : '' ?>">
      <i class="bi bi-chat-left-text"></i><span class="label">Quản lý Bình Luận</span>
    </a>
    <a href="?act=bookings" class="<?= in_array($_GET['act'] ?? '', ['bookings', 'bookings-show', 'bookings-delete', 'bookings-update-status']) ? 'active' : '' ?>">
      <i class="bi bi-ticket-perforated"></i><span class="label">Quản lý Đặt Vé</span>
    </a>
    <a href="?act=thongke" class="<?= ($_GET['act'] ?? '') == 'thongke' ? 'active' : '' ?>">
      <i class="bi bi-bar-chart"></i><span class="label">Thống Kê</span>
    </a>

    <hr>
    <a href="?act=dangxuat" class="text-danger">
      <i class="bi bi-box-arrow-right"></i><span class="label">Đăng xuất</span>
    </a>
  </nav>

  <!-- HEADER -->
  <header id="header" class="header">
    <div class="d-flex align-items-center gap-3 header-left">
      <!-- Toggle cho desktop + mobile -->
      <button id="btn-toggle" class="btn btn-sm btn-outline-secondary" title="Thu / Mở sidebar">
        <i class="bi bi-list"></i>
      </button>
      <h5 class="fw-bold mb-0">
        <?php
          $act = $_GET['act'] ?? 'Dashboard';
          $titles = [
            '/' => 'Danh sách phim',
            'movies-create' => 'Thêm phim mới',
            'movies-edit' => 'Sửa phim',
            'movies-show' => 'Chi tiết phim',
            'dashboard' => 'Dashboard',
            'phim' => 'Quản lý Phim',
            'theloai' => 'Quản lý Thể loại',
            'genres' => 'Quản lý Thể loại',
            'genres-create' => 'Thêm thể loại mới',
            'genres-edit' => 'Sửa thể loại',
            'showtimes' => 'Quản lý Lịch chiếu',
            'showtimes-create' => 'Thêm lịch chiếu',
            'showtimes-edit' => 'Sửa lịch chiếu',
            'showtimes-show' => 'Chi tiết lịch chiếu',
            'suatchieu' => 'Quản lý Suất chiếu',
            'users' => 'Quản lý Người dùng',
            'users-create' => 'Thêm người dùng',
            'users-edit' => 'Sửa người dùng',
            'users-show' => 'Chi tiết người dùng',
            'permissions' => 'Quản lý Phân quyền',
            'permissions-assign' => 'Phân quyền',
            'nguoidung' => 'Quản lý Người dùng',
            'rap' => 'Quản lý Rạp',
            'cinemas' => 'Quản lý Rạp',
            'cinemas-create' => 'Thêm rạp mới',
            'cinemas-edit' => 'Sửa rạp',
            'rooms' => 'Quản lý Phòng Chiếu',
            'rooms-create' => 'Thêm phòng mới',
            'rooms-edit' => 'Sửa phòng',
            'rooms-show' => 'Chi tiết phòng',
            'phongphim' => 'Quản lý phòng phim',
            'seats' => 'Quản lý Ghế',
            'seats-create' => 'Thêm ghế mới',
            'seats-edit' => 'Sửa ghế',
            'seats-show' => 'Chi tiết ghế',
            'seats-seatmap' => 'Sơ đồ ghế',
            'seats-generate' => 'Tạo sơ đồ ghế tự động',
            'comments' => 'Quản lý bình luận',
            'comments-show' => 'Chi tiết bình luận',
            'binhluan' => 'Quản lý bình luận',
            'bookings' => 'Quản lý đặt vé',
            'bookings-show' => 'Chi tiết đặt vé',
            'datve' => 'Quản lý đặt vé',
            'thongke' => 'Thống kê'
          ];
          echo htmlspecialchars($titles[$act] ?? ucfirst($act));
        ?>
      </h5>
    </div>

    <div class="header-right">
      <div class="position-relative me-2">
        <i class="bi bi-bell fs-5 position-relative" style="cursor: pointer;"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span>
      </div>
      <div class="position-relative me-2">
        <i class="bi bi-envelope fs-5 position-relative" style="cursor: pointer;"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">5</span>
      </div>

      <!-- Dark mode toggle -->
      <button class="dark-mode-toggle" id="darkModeToggle" title="Chuyển đổi chế độ sáng/tối">
        <i class="bi bi-moon" id="darkModeIcon"></i>
      </button>

      <!-- User dropdown -->
      <div class="user-dropdown">
        <div class="user-dropdown-toggle" id="userDropdownToggle">
          <span class="fw-semibold d-none d-sm-inline">Admin</span>
          <img src="https://ui-avatars.com/api/?name=Admin" alt="admin" class="avatar">
          <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
        </div>
        <div class="user-dropdown-menu" id="userDropdownMenu">
          <a href="?act=profile">
            <i class="bi bi-person"></i>
            <span>Hồ sơ</span>
          </a>
          <a href="?act=dangxuat" class="logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Đăng xuất</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- MAIN -->
  <main id="main" class="main">
    <!-- Bắt đầu nội dung chính của trang -->
    <?php
      // Include view động nếu có biến $viewPath
      if (isset($GLOBALS['viewPath']) && !empty($GLOBALS['viewPath'])) {
        $viewFile = __DIR__ . '/../' . $GLOBALS['viewPath'];
        if (file_exists($viewFile)) {
          include $viewFile;
        } else {
          echo '<div class="container-fluid"><div class="alert alert-danger">Không tìm thấy view: ' . htmlspecialchars($GLOBALS['viewPath']) . '</div></div>';
        }
      } else {
        // Nếu không có view, hiển thị nội dung mặc định
        echo '<div class="container-fluid">
          <div class="card p-3">
            <h4>Chào mừng đến trang quản trị</h4>
            <p>Đặt nội dung của bạn ở đây. Đây là khu vực chính (main content).</p>
          </div>
        </div>';
      }
    ?>
    <!-- Kết thúc nội dung chính -->
  </main>

  <!-- Optional Bootstrap JS (cho modal / dropdown nếu cần) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function(){
      const sidebar = document.getElementById('sidebar');
      const header = document.getElementById('header');
      const main = document.getElementById('main');
      const btn = document.getElementById('btn-toggle');
      const userDropdownToggle = document.getElementById('userDropdownToggle');
      const userDropdownMenu = document.getElementById('userDropdownMenu');
      const darkModeToggle = document.getElementById('darkModeToggle');
      const darkModeIcon = document.getElementById('darkModeIcon');
      const html = document.documentElement;

      // Load dark mode preference from localStorage
      function loadTheme() {
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') {
          html.setAttribute('data-theme', 'dark');
          darkModeIcon.classList.remove('bi-moon');
          darkModeIcon.classList.add('bi-sun');
        } else {
          html.setAttribute('data-theme', 'light');
          darkModeIcon.classList.remove('bi-sun');
          darkModeIcon.classList.add('bi-moon');
        }
      }

      // Toggle dark mode
      darkModeToggle.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-theme');
        if (currentTheme === 'dark') {
          html.setAttribute('data-theme', 'light');
          localStorage.setItem('theme', 'light');
          darkModeIcon.classList.remove('bi-sun');
          darkModeIcon.classList.add('bi-moon');
        } else {
          html.setAttribute('data-theme', 'dark');
          localStorage.setItem('theme', 'dark');
          darkModeIcon.classList.remove('bi-moon');
          darkModeIcon.classList.add('bi-sun');
        }
      });

      // Load theme on page load
      loadTheme();

      // Toggle user dropdown
      userDropdownToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdownMenu.classList.toggle('show');
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', (e) => {
        if (!userDropdownToggle.contains(e.target) && !userDropdownMenu.contains(e.target)) {
          userDropdownMenu.classList.remove('show');
        }
        
        // Close mobile sidebar when clicking outside
        if (window.matchMedia("(max-width: 991px)").matches) {
          if (!sidebar.contains(e.target) && !btn.contains(e.target) && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
          }
        }
      });

      // Toggle collapsed on desktop
      btn.addEventListener('click', () => {
        const isMobile = window.matchMedia("(max-width: 991px)").matches;
        if (isMobile) {
          // open/close overlay sidebar on mobile
          sidebar.classList.toggle('open');
        } else {
          sidebar.classList.toggle('collapsed');
          header.classList.toggle('collapsed');
          main.classList.toggle('collapsed');
        }
      });

      // Keep correct state on window resize (remove mobile overlay when desktop)
      window.addEventListener('resize', () => {
        if (!window.matchMedia("(max-width: 991px)").matches) {
          sidebar.classList.remove('open');
        }
      });
    })();
  </script>
</body>
</html>
