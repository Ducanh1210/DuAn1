<?php
/**
 * MAIN.PHP - LAYOUT CHUNG CHO TRANG ADMIN
 * 
 * CHỨC NĂNG:
 * - Layout chung cho tất cả trang admin (sidebar, header, footer)
 * - Include view tương ứng dựa trên $GLOBALS['viewPath']
 * 
 * LUỒNG CHẠY RENDER:
 * 1. Controller gọi render('admin/movies/list.php', ['data' => $movies])
 * 2. function.php extract data: $data = $movies
 * 3. function.php set $GLOBALS['viewPath'] = 'admin/movies/list.php'
 * 4. function.php include main.php (file này)
 * 5. main.php include header, sidebar
 * 6. main.php include view từ $GLOBALS['viewPath'] (dòng cuối)
 * 7. View sử dụng biến $data để hiển thị
 * 
 * CẤU TRÚC:
 * - Header: Logo, thông tin user, thông báo
 * - Sidebar: Menu điều hướng (Dashboard, Quản lý Phim, etc.)
 * - Content: View được include từ $GLOBALS['viewPath']
 * - Footer: Thông tin footer
 */
?>
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
    <?php
      require_once __DIR__ . '/../../commons/auth.php';
      startSessionIfNotStarted();
      $currentUser = getCurrentUser();
      $userRole = $currentUser['role'] ?? 'customer';
      $isAdmin = isAdmin();
      $isManager = isManager();
      $isStaff = isStaff();
    ?>
    
    <!-- Dashboard - Tất cả đều có -->
    <a href="?act=dashboard" class="<?= ($_GET['act'] ?? '') == 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i><span class="label">Dashboard</span>
    </a>
    
    <!-- Quản lý Phim - Tất cả đều xem được -->
    <?php if ($isAdmin || $isManager || $isStaff): ?>
    <a href="?act=/" class="<?= in_array($_GET['act'] ?? '', ['/', 'movies-create', 'movies-edit', 'movies-show', 'movies-delete']) ? 'active' : '' ?>">
      <i class="bi bi-film"></i><span class="label">Quản lý Phim</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Thể loại - Chỉ Admin -->
    <?php if ($isAdmin): ?>
    <a href="?act=genres" class="<?= in_array($_GET['act'] ?? '', ['genres', 'genres-create', 'genres-edit', 'genres-delete']) ? 'active' : '' ?>">
      <i class="bi bi-tags"></i><span class="label">Quản lý Thể loại</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Lịch chiếu - Admin, Manager, Staff -->
    <?php if ($isAdmin || $isManager || $isStaff): ?>
    <a href="?act=showtimes" class="<?= in_array($_GET['act'] ?? '', ['showtimes', 'showtimes-create', 'showtimes-edit', 'showtimes-show', 'showtimes-delete']) ? 'active' : '' ?>">
      <i class="bi bi-clock-history"></i><span class="label">Quản lý Lịch chiếu</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Khuyến mãi - Chỉ Admin -->
    <?php if ($isAdmin): ?>
    <a href="?act=discounts" class="<?= in_array($_GET['act'] ?? '', ['discounts', 'discounts-create', 'discounts-edit', 'discounts-delete']) ? 'active' : '' ?>">
      <i class="bi bi-gift"></i><span class="label">Quản lý Khuyến mãi</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Người dùng - Chỉ Admin -->
    <?php if ($isAdmin): ?>
    <a href="?act=users" class="<?= in_array($_GET['act'] ?? '', ['users', 'users-create', 'users-edit', 'users-show', 'users-delete']) ? 'active' : '' ?>">
      <i class="bi bi-people"></i><span class="label">Quản lý Người dùng</span>
    </a>
    <?php endif; ?>
    
    <!-- Phân quyền - Chỉ Admin -->
    <?php if ($isAdmin): ?>
    <a href="?act=permissions" class="<?= in_array($_GET['act'] ?? '', ['permissions', 'permissions-assign']) ? 'active' : '' ?>">
      <i class="bi bi-shield-check"></i><span class="label">Phân quyền</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Rạp - Admin và Manager -->
    <?php if ($isAdmin || $isManager): ?>
    <a href="?act=cinemas" class="<?= in_array($_GET['act'] ?? '', ['cinemas', 'cinemas-create', 'cinemas-edit', 'cinemas-delete']) ? 'active' : '' ?>">
      <i class="bi bi-building"></i><span class="label">Quản lý Rạp</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Phòng Chiếu - Admin và Manager -->
    <?php if ($isAdmin || $isManager): ?>
    <a href="?act=rooms" class="<?= in_array($_GET['act'] ?? '', ['rooms', 'rooms-create', 'rooms-edit', 'rooms-show', 'rooms-delete']) ? 'active' : '' ?>">
      <i class="bi bi-camera-reels"></i><span class="label">Quản lý Phòng Chiếu</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Ghế - Admin và Manager -->
    <?php if ($isAdmin || $isManager): ?>
    <a href="?act=seats" class="<?= in_array($_GET['act'] ?? '', ['seats', 'seats-create', 'seats-edit', 'seats-show', 'seats-delete', 'seats-seatmap', 'seats-generate']) ? 'active' : '' ?>">
      <i class="bi bi-grid-3x3-gap"></i><span class="label">Quản lý Ghế</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Bình Luận - Admin và Manager -->
    <?php if ($isAdmin || $isManager): ?>
    <a href="?act=comments" class="<?= in_array($_GET['act'] ?? '', ['comments', 'comments-show', 'comments-delete']) ? 'active' : '' ?>">
      <i class="bi bi-chat-left-text"></i><span class="label">Quản lý Bình Luận</span>
    </a>
    <?php endif; ?>
    
    
    <!-- Quản lý Đặt Vé - Tất cả (Admin xem tất cả, Manager xem rạp mình, Staff xem) -->
    <?php if ($isAdmin || $isManager || $isStaff): ?>
    <a href="?act=bookings" class="<?= in_array($_GET['act'] ?? '', ['bookings', 'bookings-show', 'bookings-delete', 'bookings-update-status']) ? 'active' : '' ?>">
      <i class="bi bi-ticket-perforated"></i><span class="label">Quản lý Đặt Vé</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Giá Vé - Chỉ Admin -->
    <?php if ($isAdmin): ?>
    <a href="?act=ticket-prices" class="<?= in_array($_GET['act'] ?? '', ['ticket-prices', 'ticket-prices-update']) ? 'active' : '' ?>">
      <i class="bi bi-cash-coin"></i><span class="label">Quản lý Giá Vé</span>
    </a>
    <?php endif; ?>
    
    <!-- Quản lý Liên hệ - Admin, Manager và Staff -->
    <?php if ($isAdmin || $isManager || $isStaff): ?>
    <a href="?act=contacts" class="<?= in_array($_GET['act'] ?? '', ['contacts', 'contacts-show', 'contacts-edit', 'contacts-delete', 'contacts-update-status']) ? 'active' : '' ?>">
      <i class="bi bi-envelope"></i><span class="label">Quản lý Liên hệ</span>
    </a>
    <?php endif; ?>
    
    <!-- Thống Kê - Tất cả -->
    <?php if ($isAdmin || $isManager || $isStaff): ?>
    <a href="?act=thongke" class="<?= in_array($_GET['act'] ?? '', ['thongke', 'statistics']) ? 'active' : '' ?>">
      <i class="bi bi-bar-chart"></i><span class="label">Thống Kê</span>
    </a>
    <?php endif; ?>

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
            'discounts' => 'Quản lý Khuyến mãi',
            'discounts-create' => 'Thêm mã khuyến mại',
            'discounts-edit' => 'Sửa mã khuyến mại',
            'ticket-prices' => 'Quản lý Giá Vé',
            'contacts' => 'Quản lý Liên hệ',
            'contacts-show' => 'Chi tiết liên hệ',
            'contacts-edit' => 'Sửa liên hệ',
            'thongke' => 'Thống kê'
          ];
          echo htmlspecialchars($titles[$act] ?? ucfirst($act));
        ?>
      </h5>
    </div>

    <div class="header-right">
      <!-- Notifications -->
      <div class="position-relative me-2 notification-wrapper">
        <i class="bi bi-bell fs-5 position-relative" id="notificationIcon" style="cursor: pointer; color: var(--text-primary);"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">0</span>
        <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
          <div class="notification-header">
            <h6>Thông báo</h6>
            <button class="btn-mark-all-read" id="markAllReadBtn" style="font-size: 12px; padding: 2px 8px;">Đánh dấu tất cả đã đọc</button>
          </div>
          <div class="notification-list" id="notificationList">
            <div class="notification-empty">Không có thông báo mới</div>
          </div>
      </div>
      </div>

      <!-- Dark mode toggle -->
      <button class="dark-mode-toggle" id="darkModeToggle" title="Chuyển đổi chế độ sáng/tối">
        <i class="bi bi-moon" id="darkModeIcon"></i>
      </button>

      <!-- User dropdown -->
      <div class="user-dropdown">
        <div class="user-dropdown-toggle" id="userDropdownToggle">
          <?php
            require_once __DIR__ . '/../../commons/auth.php';
            startSessionIfNotStarted();
            $currentUser = getCurrentUser();
            $displayName = 'User';
            $displayInitials = 'U';
            
            if ($currentUser) {
              if ($currentUser['role'] === 'admin') {
                $displayName = 'Admin';
                $displayInitials = 'AD';
              } elseif ($currentUser['role'] === 'manager') {
                $displayName = htmlspecialchars($currentUser['full_name'] ?? 'Quản lý');
                // Lấy chữ cái đầu của tên
                $nameParts = explode(' ', trim($currentUser['full_name'] ?? ''));
                if (count($nameParts) >= 2) {
                  $displayInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
                } else {
                  $displayInitials = strtoupper(substr($currentUser['full_name'] ?? 'Q', 0, 2));
                }
              } else {
                $displayName = htmlspecialchars($currentUser['full_name'] ?? 'Nhân viên');
                // Lấy chữ cái đầu của tên
                $nameParts = explode(' ', trim($currentUser['full_name'] ?? ''));
                if (count($nameParts) >= 2) {
                  $displayInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
                } else {
                  $displayInitials = strtoupper(substr($currentUser['full_name'] ?? 'N', 0, 2));
                }
              }
            }
          ?>
          <span class="fw-semibold d-none d-sm-inline"><?= $displayName ?></span>
          <img src="https://ui-avatars.com/api/?name=<?= urlencode($displayName) ?>&background=random" alt="<?= htmlspecialchars($displayName) ?>" class="avatar">
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
    <!-- 
      ============================================
      PHẦN NỘI DUNG CHÍNH - INCLUDE VIEW ĐỘNG
      ============================================
      
      LUỒNG CHẠY:
      1. Controller gọi render('admin/movies/list.php', ['data' => $movies])
      2. function.php set $GLOBALS['viewPath'] = 'admin/movies/list.php'
      3. function.php extract data: $data = $movies (tạo biến $data)
      4. function.php include main.php (file này)
      5. main.php include header, sidebar (ở trên)
      6. main.php đến đây: include view từ $GLOBALS['viewPath']
      7. View (list.php) sử dụng biến $data để hiển thị danh sách phim
      
      VÍ DỤ:
      - Controller: render('admin/movies/list.php', ['movies' => $movies])
      - View path: 'admin/movies/list.php'
      - Biến trong view: $movies (đã được extract từ data array)
      - View hiển thị: Bảng danh sách phim với dữ liệu $movies
    -->
    <?php
      // Include view động nếu có biến $viewPath
      // $viewPath được set trong function.php khi Controller gọi render()
      if (isset($GLOBALS['viewPath']) && !empty($GLOBALS['viewPath'])) {
        // Tạo đường dẫn đầy đủ đến file view
        // Ví dụ: views/admin/movies/list.php
        $viewFile = __DIR__ . '/../' . $GLOBALS['viewPath'];
        
        // Kiểm tra file tồn tại trước khi include
        if (file_exists($viewFile)) {
          // Include view - tất cả biến đã được extract trong function.php
          // View có thể sử dụng các biến như $movies, $errors, etc.
          include $viewFile;
        } else {
          // Hiển thị lỗi nếu không tìm thấy view
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

      // Notifications functionality
      const notificationIcon = document.getElementById('notificationIcon');
      const notificationBadge = document.getElementById('notificationBadge');
      const notificationDropdown = document.getElementById('notificationDropdown');
      const notificationList = document.getElementById('notificationList');
      const markAllReadBtn = document.getElementById('markAllReadBtn');

      // Load notifications
      function loadNotifications() {
        fetch('<?= BASE_URL ?>?act=api-notifications&unread_only=true')
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              updateNotificationBadge(data.unread_count);
              if (data.notifications && data.notifications.length > 0) {
                renderNotifications(data.notifications);
              } else {
                notificationList.innerHTML = '<div class="notification-empty">Không có thông báo mới</div>';
              }
            }
          })
          .catch(error => {
            console.error('Error loading notifications:', error);
          });
      }

      // Update notification badge
      function updateNotificationBadge(count) {
        if (count > 0) {
          notificationBadge.textContent = count > 99 ? '99+' : count;
          notificationBadge.style.display = 'block';
        } else {
          notificationBadge.style.display = 'none';
        }
      }

      // Render notifications
      function renderNotifications(notifications) {
        notificationList.innerHTML = notifications.map(notif => `
          <div class="notification-item ${notif.is_read == 0 ? 'unread' : ''}" data-id="${notif.id}">
            <div class="notification-content">
              <div class="notification-title">${notif.title}</div>
              <div class="notification-message">${notif.message}</div>
              <div class="notification-time">${formatTime(notif.created_at)}</div>
            </div>
            ${notif.related_id ? `<a href="?act=bookings-show&id=${notif.related_id}" class="notification-link">Xem chi tiết</a>` : ''}
          </div>
        `).join('');

        // Add click handlers
        notificationList.querySelectorAll('.notification-item').forEach(item => {
          item.addEventListener('click', function() {
            const id = this.dataset.id;
            if (id && !this.classList.contains('read')) {
              markAsRead(id);
            }
          });
        });
      }

      // Mark notification as read
      function markAsRead(id) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('<?= BASE_URL ?>?act=api-notifications-mark-read', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              updateNotificationBadge(data.unread_count);
              const item = notificationList.querySelector(`[data-id="${id}"]`);
              if (item) {
                item.classList.remove('unread');
                item.classList.add('read');
              }
            }
          })
          .catch(error => {
            console.error('Error marking notification as read:', error);
          });
      }

      // Mark all as read
      if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          
          fetch('<?= BASE_URL ?>?act=api-notifications-mark-all-read', {
            method: 'POST'
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                updateNotificationBadge(0);
                // Clear all notifications from list
                notificationList.innerHTML = '<div class="notification-empty">Không có thông báo mới</div>';
              }
            })
            .catch(error => {
              console.error('Error marking all as read:', error);
            });
        });
      }

      // Toggle notification dropdown
      if (notificationIcon) {
        notificationIcon.addEventListener('click', function(e) {
          e.stopPropagation();
          if (notificationDropdown.style.display === 'none' || notificationDropdown.style.display === '') {
            notificationDropdown.style.display = 'block';
            loadNotifications();
          } else {
            notificationDropdown.style.display = 'none';
          }
        });
      }

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (notificationDropdown && notificationIcon && 
            !notificationDropdown.contains(e.target) && 
            !notificationIcon.contains(e.target)) {
          notificationDropdown.style.display = 'none';
        }
      });

      // Format time
      function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // seconds

        if (diff < 60) return 'Vừa xong';
        if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
        if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
        return date.toLocaleDateString('vi-VN');
      }

      // Load notifications on page load
      loadNotifications();
      
      // Refresh notifications every 30 seconds
      setInterval(loadNotifications, 30000);
    })();
  </script>

  <style>
    .notification-wrapper {
      position: relative;
    }

    .notification-dropdown {
      position: absolute;
      top: calc(100% + 10px);
      right: 0;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      min-width: 320px;
      max-width: 400px;
      max-height: 500px;
      overflow-y: auto;
      z-index: 1050;
    }

    .notification-header {
      padding: 12px 16px;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notification-header h6 {
      margin: 0;
      font-weight: 700;
      color: var(--text-primary);
    }

    .btn-mark-all-read {
      background: transparent;
      border: none;
      color: var(--accent);
      cursor: pointer;
      padding: 2px 8px;
      border-radius: 4px;
      transition: background 0.2s;
    }

    .btn-mark-all-read:hover {
      background: rgba(0, 0, 0, 0.05);
    }

    .notification-list {
      max-height: 400px;
      overflow-y: auto;
    }

    .notification-item {
      padding: 12px 16px;
      border-bottom: 1px solid var(--border-color);
      cursor: pointer;
      transition: background 0.2s;
    }

    .notification-item:hover {
      background: rgba(0, 0, 0, 0.03);
    }

    .notification-item.unread {
      background: rgba(13, 110, 253, 0.05);
    }

    .notification-content {
      flex: 1;
    }

    .notification-title {
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .notification-message {
      color: var(--text-secondary);
      font-size: 14px;
      margin-bottom: 4px;
    }

    .notification-time {
      color: var(--text-secondary);
      font-size: 12px;
    }

    .notification-link {
      color: var(--accent);
      text-decoration: none;
      font-size: 12px;
      margin-top: 4px;
      display: inline-block;
    }

    .notification-empty {
      padding: 40px 20px;
      text-align: center;
      color: var(--text-secondary);
    }

    .notification-dropdown::-webkit-scrollbar {
      width: 6px;
    }

    .notification-dropdown::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
    }

    .notification-dropdown::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 3px;
    }
  </style>
</body>
</html>
