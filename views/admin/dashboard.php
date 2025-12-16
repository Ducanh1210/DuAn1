<?php
// DASHBOARD.PHP - TRANG TỔNG QUAN ADMIN
// Chức năng: Hiển thị thống kê tổng quan (doanh thu, vé bán, người dùng, phim, biểu đồ)
// Biến từ controller: $totalRevenue, $totalTickets, $totalUsers, $totalMovies, $revenueByMonth, $topMovies, $topCinemas, $bookingStats
?>
<div class="container-fluid">
  <!-- Header trang dashboard: tiêu đề và bộ lọc -->
  <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
      <h2 class="mb-1 d-flex align-items-center gap-2">
        <i class="bi bi-speedometer2 text-primary"></i>
        <span>Dashboard</span>
      </h2>
      <p class="text-secondary mb-0">
        <!-- Hiển thị thời gian hiện tại -->
        <i class="bi bi-calendar3"></i> <?= date('d/m/Y H:i') ?> - Tổng quan hệ thống và thống kê
      </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <!-- Bộ lọc thống kê theo năm/tháng -->
      <div class="d-flex gap-2 align-items-center">
        <!-- Dropdown chọn năm: hiển thị từ năm hiện tại về trước 5 năm -->
        <select id="filterYearGlobal" class="form-select form-select-sm" style="width: auto;" onchange="applyGlobalFilter()">
          <option value="">Tất cả năm</option>
          <?php
          $currentYear = date('Y'); // Lấy năm hiện tại
          // Tạo option cho 6 năm (năm hiện tại + 5 năm trước)
          for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            // Đánh dấu selected nếu năm này đang được chọn
            $selected = (isset($filterYear) && $filterYear == $i) ? ' selected' : '';
            echo "<option value='$i'$selected>$i</option>";
          }
          ?>
        </select>
        <!-- Dropdown chọn tháng: hiển thị 12 tháng -->
        <select id="filterMonthGlobal" class="form-select form-select-sm" style="width: auto;" onchange="applyGlobalFilter()">
          <option value="">Tất cả tháng</option>
          <?php
          // Mảng tên tháng bằng tiếng Việt
          $monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
          // Tạo option cho 12 tháng
          for ($i = 1; $i <= 12; $i++) {
            // Đánh dấu selected nếu tháng này đang được chọn
            $selected = (isset($filterMonth) && $filterMonth == $i) ? ' selected' : '';
            echo "<option value='$i'$selected>{$monthNames[$i-1]}</option>";
          }
          ?>
        </select>
        <!-- Nút reset bộ lọc về mặc định -->
        <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()">
          <i class="bi bi-arrow-counterclockwise"></i> Reset
        </button>
      </div>
      <!-- Nút xuất báo cáo Excel -->
      <button class="btn btn-outline-primary d-flex align-items-center gap-2" onclick="exportReport()">
        <i class="bi bi-download"></i>
        <span>Xuất báo cáo</span>
      </button>
    </div>
  </div>

  <!-- Hàng thẻ thống kê đầu tiên: Doanh thu, Vé bán, Người dùng, Phim -->
  <div class="row g-4 mb-4">
    <!-- Card 1: Tổng doanh thu -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card revenue-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Tổng doanh thu</p>
              <!-- Hiển thị tổng doanh thu: $totalRevenue từ controller, format số với dấu phẩy -->
              <h3 class="mb-0 fw-bold text-primary"><?= number_format($totalRevenue, 0, ',', '.') ?></h3>
              <small class="text-secondary">VNĐ</small>
            </div>
            <!-- Icon biểu tượng doanh thu -->
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-currency-exchange fs-3 text-primary"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <!-- Badge hiển thị doanh thu hôm nay: $todayRevenue từ controller -->
            <span class="badge bg-success-subtle text-success">
              <i class="bi bi-calendar-day"></i> Hôm nay: <?= number_format($todayRevenue, 0, ',', '.') ?> đ
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Xuất vé -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card tickets-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Tổng số vé</p>
              <h3 class="mb-0 fw-bold text-success"><?= number_format($totalTickets) ?></h3>
              <small class="text-secondary">vé đã bán</small>
            </div>
            <div class="p-3 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-ticket-perforated fs-3 text-success"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info-subtle text-info">
              <i class="bi bi-calendar-day"></i> Hôm nay: <?= number_format($todayBookings) ?> vé
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3: Tổng số người dùng - $totalUsers từ controller -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card users-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Người dùng</p>
              <!-- Hiển thị tổng số người dùng -->
              <h3 class="mb-0 fw-bold text-warning"><?= number_format($totalUsers) ?></h3>
              <small class="text-secondary">tổng số</small>
            </div>
            <!-- Icon người dùng -->
            <div class="p-3 rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-people fs-3 text-warning"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Badge số người dùng hoạt động: $activeUsers từ controller -->
            <span class="badge bg-success-subtle text-success">
              <i class="bi bi-check-circle"></i> Hoạt động: <?= number_format($activeUsers) ?>
            </span>
            <!-- Badge số người dùng bị khóa: chỉ hiển thị nếu > 0 -->
            <?php if ($bannedUsers > 0): ?>
            <span class="badge bg-danger-subtle text-danger">
              <i class="bi bi-lock"></i> Đã khóa: <?= number_format($bannedUsers) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4: Tổng số phim - $totalMovies từ controller -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card movies-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Phim</p>
              <!-- Hiển thị tổng số phim -->
              <h3 class="mb-0 fw-bold text-info"><?= number_format($totalMovies) ?></h3>
              <small class="text-secondary">tổng số</small>
            </div>
            <!-- Icon phim -->
            <div class="p-3 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-film fs-3 text-info"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <!-- Badge số phim đang chiếu: $activeMovies từ controller -->
            <span class="badge bg-success-subtle text-success">
              <i class="bi bi-play-circle"></i> Đang chiếu: <?= number_format($activeMovies) ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards Row 2 -->
  <div class="row g-4 mb-4">
    <!-- Card 5: Nhân viên -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card staff-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Nhân viên</p>
              <h3 class="mb-0 fw-bold text-primary"><?= number_format($totalStaff) ?></h3>
              <small class="text-secondary">tổng số</small>
            </div>
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-person-badge fs-3 text-primary"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary">Quản lý hệ thống</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 6: Phòng chiếu -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card rooms-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Phòng chiếu</p>
              <h3 class="mb-0 fw-bold text-cyan"><?= number_format($totalRooms) ?></h3>
              <small class="text-secondary">tổng số</small>
            </div>
            <div class="p-3 rounded-circle bg-cyan bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-door-open fs-3 text-cyan"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info-subtle text-info">Đang hoạt động</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 7: Rạp -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card cinemas-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Rạp chiếu</p>
              <h3 class="mb-0 fw-bold text-danger"><?= number_format($totalCinemas) ?></h3>
              <small class="text-secondary">tổng số</small>
            </div>
            <div class="p-3 rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-building fs-3 text-danger"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger-subtle text-danger">Đang hoạt động</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 8: Trạng thái đặt vé -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card status-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-secondary small mb-1 fw-semibold text-uppercase">Đặt vé</p>
              <h3 class="mb-0 fw-bold text-purple"><?= number_format($totalTickets) ?></h3>
              <small class="text-secondary">tổng số</small>
            </div>
            <div class="p-3 rounded-circle bg-purple bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-ticket-perforated fs-3 text-purple"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <?php 
            $statusLabels = [
              'confirmed' => 'Đã xác nhận',
              'completed' => 'Hoàn thành',
              'pending' => 'Chờ xử lý',
              'cancelled' => 'Đã hủy'
            ];
            $statusColors = [
              'confirmed' => 'success',
              'completed' => 'primary',
              'pending' => 'warning',
              'cancelled' => 'danger'
            ];
            foreach ($bookingStatusStats as $stat): 
              if ($stat['count'] > 0):
            ?>
              <span class="badge bg-<?= $statusColors[$stat['status']] ?? 'secondary' ?>-subtle text-<?= $statusColors[$stat['status']] ?? 'secondary' ?>">
                <?= $statusLabels[$stat['status']] ?? $stat['status'] ?>: <?= $stat['count'] ?>
              </span>
            <?php 
              endif;
            endforeach; 
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts and Tables Row -->
  <div class="row g-4 mb-4">
    <!-- Biểu đồ doanh thu theo tháng -->
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header border-bottom">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-graph-up text-primary"></i>
              <span>Doanh thu theo tháng</span>
            </h5>
            <div class="d-flex gap-2 flex-wrap">
              <select id="filterYear" class="form-select form-select-sm" style="width: auto;">
                <option value="">Tất cả năm</option>
                <?php
                $currentYear = date('Y');
                for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                  echo "<option value='$i'" . (isset($_GET['year']) && $_GET['year'] == $i ? ' selected' : '') . ">$i</option>";
                }
                ?>
              </select>
              <select id="filterPeriod" class="form-select form-select-sm" style="width: auto;">
                <option value="1">1 tháng gần nhất</option>
                <option value="3">3 tháng gần nhất</option>
                <option value="7" selected>7 tháng gần nhất</option>
                <option value="12">12 tháng gần nhất</option>
                <option value="24">24 tháng gần nhất</option>
                <option value="all">Tất cả</option>
              </select>
              <button class="btn btn-sm btn-primary" onclick="updateChart()">
                <i class="bi bi-arrow-clockwise"></i> Cập nhật
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <canvas id="revenueChart" height="100"></canvas>
        </div>
      </div>
    </div>

    <!-- Top phim bán chạy -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header border-bottom">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-trophy text-warning"></i>
              <span>Top 5 phim bán chạy</span>
            </h5>
            <button class="btn btn-sm btn-outline-primary" onclick="refreshTopMovies()">
              <i class="bi bi-arrow-clockwise"></i>
            </button>
          </div>
        </div>
        <div class="card-body p-0" id="topMoviesContainer">
          <?php if (!empty($topMovies)): ?>
            <div class="list-group list-group-flush">
              <?php foreach ($topMovies as $index => $movie): ?>
                <div class="list-group-item border-0">
                  <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                      <span class="badge bg-warning text-dark position-absolute top-0 start-0 rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                        <?= $index + 1 ?>
                      </span>
                      <?php if ($movie['image']): ?>
                        <img src="<?= BASE_URL . $movie['image'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" 
                             class="rounded" style="width: 60px; height: 80px; object-fit: cover;">
                      <?php else: ?>
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 80px;">
                          <i class="bi bi-film text-white"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 fw-bold"><?= htmlspecialchars($movie['title']) ?></h6>
                      <div class="d-flex gap-3 text-secondary small">
                        <span><i class="bi bi-ticket"></i> <?= number_format($movie['booking_count'] ?? 0) ?> vé</span>
                        <span><i class="bi bi-cash"></i> <?= number_format($movie['revenue'] ?? 0, 0, ',', '.') ?> đ</span>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center text-secondary py-4">
              <i class="bi bi-inbox fs-1"></i>
              <p class="mb-0 mt-2">Chưa có dữ liệu</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Đặt vé gần đây -->
  <div class="row g-4">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
          <h5 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-info"></i>
            <span>Đặt vé gần đây</span>
          </h5>
          <a href="<?= BASE_URL ?>?act=bookings" class="btn btn-sm btn-outline-primary">
            Xem tất cả <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div class="card-body p-0">
          <?php if (!empty($recentBookings)): ?>
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>Mã đặt vé</th>
                    <th>Khách hàng</th>
                    <th>Phim</th>
                    <th>Ngày chiếu</th>
                    <th>Ghế</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recentBookings as $booking): ?>
                    <tr>
                      <td>
                        <code class="text-primary"><?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></code>
                      </td>
                      <td>
                        <div>
                          <div class="fw-semibold"><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></div>
                          <small class="text-secondary"><?= htmlspecialchars($booking['user_email'] ?? '') ?></small>
                        </div>
                      </td>
                      <td>
                        <div class="fw-semibold"><?= htmlspecialchars($booking['movie_title'] ?? 'N/A') ?></div>
                      </td>
                      <td>
                        <?php if ($booking['show_date']): ?>
                          <div><?= date('d/m/Y', strtotime($booking['show_date'])) ?></div>
                          <small class="text-secondary"><?= date('H:i', strtotime($booking['start_time'])) ?></small>
                        <?php else: ?>
                          <span class="text-secondary">N/A</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge bg-info-subtle text-info">
                          <?= htmlspecialchars($booking['booked_seats'] ?? 'N/A') ?>
                        </span>
                      </td>
                      <td>
                        <span class="fw-semibold text-success">
                          <?= number_format($booking['final_amount'] ?? 0, 0, ',', '.') ?> đ
                        </span>
                      </td>
                      <td>
                        <?php
                        $status = $booking['status'] ?? 'pending';
                        $statusClass = [
                          'confirmed' => 'success',
                          'completed' => 'primary',
                          'pending' => 'warning',
                          'cancelled' => 'danger'
                        ][$status] ?? 'secondary';
                        $statusText = [
                          'confirmed' => 'Đã xác nhận',
                          'completed' => 'Hoàn thành',
                          'pending' => 'Chờ xử lý',
                          'cancelled' => 'Đã hủy'
                        ][$status] ?? $status;
                        ?>
                        <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                      </td>
                      <td>
                        <small class="text-secondary">
                          <?= $booking['booking_date'] ? date('d/m/Y H:i', strtotime($booking['booking_date'])) : 'N/A' ?>
                        </small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center text-secondary py-5">
              <i class="bi bi-inbox fs-1"></i>
              <p class="mb-0 mt-2">Chưa có đặt vé nào</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.dashboard-card {
  transition: all 0.3s ease;
  border-radius: 12px;
  overflow: hidden;
  position: relative;
}

.dashboard-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  transition: width 0.3s ease;
}

.dashboard-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}

.dashboard-card:hover::before {
  width: 6px;
}

.revenue-card::before { background: linear-gradient(180deg, #0d6efd, #0a58ca); }
.tickets-card::before { background: linear-gradient(180deg, #198754, #146c43); }
.users-card::before { background: linear-gradient(180deg, #ffc107, #ffb300); }
.movies-card::before { background: linear-gradient(180deg, #0dcaf0, #0aa2c0); }
.staff-card::before { background: linear-gradient(180deg, #0d6efd, #0a58ca); }
.rooms-card::before { background: linear-gradient(180deg, #0dcaf0, #0aa2c0); }
.cinemas-card::before { background: linear-gradient(180deg, #dc3545, #b02a37); }
.status-card::before { background: linear-gradient(180deg, #6f42c1, #5a32a3); }

.text-cyan { color: #0dcaf0 !important; }
.bg-cyan { background-color: #0dcaf0 !important; }
.text-purple { color: #6f42c1 !important; }
.bg-purple { background-color: #6f42c1 !important; }

/* Dark mode support */
[data-theme="dark"] .card-header {
  background-color: var(--bg-card) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card-header h5,
[data-theme="dark"] .card-header span {
  color: var(--text-primary) !important;
}

/* Phần trên - Cards thống kê - chữ trắng trong dark mode */
[data-theme="dark"] .dashboard-card,
[data-theme="dark"] .dashboard-card .card-body {
  background-color: var(--bg-card) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .dashboard-card .text-secondary {
  color: rgba(255, 255, 255, 0.7) !important;
}

[data-theme="dark"] .dashboard-card h3,
[data-theme="dark"] .dashboard-card h2,
[data-theme="dark"] .dashboard-card h5,
[data-theme="dark"] .dashboard-card h6 {
  color: var(--text-primary) !important;
}

[data-theme="dark"] .dashboard-card small {
  color: rgba(255, 255, 255, 0.6) !important;
}

[data-theme="dark"] .dashboard-card p {
  color: rgba(255, 255, 255, 0.8) !important;
}

/* Phần dưới - Bảng đặt vé - nền tối chữ trắng trong dark mode */
[data-theme="dark"] .table {
  background-color: var(--bg-card) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .table thead {
  background-color: rgba(255, 255, 255, 0.05) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .table thead th {
  color: var(--text-primary) !important;
  border-color: var(--border-color) !important;
  background-color: rgba(255, 255, 255, 0.05) !important;
}

[data-theme="dark"] .table tbody {
  background-color: var(--bg-card) !important;
}

[data-theme="dark"] .table tbody td {
  color: var(--text-primary) !important;
  border-color: var(--border-color) !important;
  background-color: var(--bg-card) !important;
}

[data-theme="dark"] .table tbody tr {
  background-color: var(--bg-card) !important;
}

[data-theme="dark"] .table tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.05) !important;
}

[data-theme="dark"] .table tbody tr:nth-of-type(even) {
  background-color: rgba(255, 255, 255, 0.02) !important;
}

[data-theme="dark"] .table tbody tr:nth-of-type(even):hover {
  background-color: rgba(255, 255, 255, 0.05) !important;
}

[data-theme="dark"] .table tbody .text-secondary {
  color: rgba(255, 255, 255, 0.7) !important;
}

[data-theme="dark"] .table tbody .fw-semibold {
  color: var(--text-primary) !important;
}

[data-theme="dark"] .list-group-item {
  background-color: var(--bg-card) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] code {
  background-color: rgba(255, 255, 255, 0.1) !important;
  color: var(--text-primary) !important;
}

/* Đảm bảo các phần tử trong card đều có màu phù hợp */
[data-theme="dark"] .card {
  background-color: var(--bg-card) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card-body {
  background-color: var(--bg-card) !important;
  color: var(--text-primary) !important;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dashboard-card {
  animation: fadeInUp 0.5s ease-out;
}

.dashboard-card:nth-child(1) { animation-delay: 0.1s; }
.dashboard-card:nth-child(2) { animation-delay: 0.2s; }
.dashboard-card:nth-child(3) { animation-delay: 0.3s; }
.dashboard-card:nth-child(4) { animation-delay: 0.4s; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Biến toàn cục cho chart
let revenueChart = null;
const monthlyData = <?= json_encode($monthlyStats) ?>;

// Hàm tạo/cập nhật biểu đồ
function createChart(data) {
  const ctx = document.getElementById('revenueChart');
  if (!ctx) return;

  // Hủy chart cũ nếu có
  if (revenueChart) {
    revenueChart.destroy();
  }

  const labels = data.map(item => {
    if (!item.month) return '';
    const [year, month] = item.month.split('-');
    const monthNames = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
    return monthNames[parseInt(month) - 1] + '/' + year;
  }).filter(label => label !== '');
  const revenueData = data.map(item => parseFloat(item.revenue || 0));
  const bookingsData = data.map(item => parseInt(item.bookings || 0));
  
  // Đảm bảo có dữ liệu để hiển thị
  if (labels.length === 0) {
    labels.push('Chưa có dữ liệu');
    revenueData.push(0);
    bookingsData.push(0);
  }

  // Lấy màu text từ theme
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const textColor = isDark ? '#e9ecef' : '#212529';
  const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

  revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Doanh thu (VNĐ)',
          data: revenueData,
          borderColor: 'rgb(13, 110, 253)',
          backgroundColor: 'rgba(13, 110, 253, 0.1)',
          tension: 0.4,
          yAxisID: 'y',
          fill: true
        },
        {
          label: 'Số vé',
          data: bookingsData,
          borderColor: 'rgb(25, 135, 84)',
          backgroundColor: 'rgba(25, 135, 84, 0.1)',
          tension: 0.4,
          yAxisID: 'y1',
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: textColor
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              if (context.datasetIndex === 0) {
                return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
              } else {
                return 'Số vé: ' + context.parsed.y;
              }
            }
          }
        }
      },
      scales: {
        x: {
          ticks: {
            color: textColor
          },
          grid: {
            color: gridColor
          }
        },
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          title: {
            display: true,
            text: 'Doanh thu (VNĐ)',
            color: textColor
          },
          ticks: {
            callback: function(value) {
              return new Intl.NumberFormat('vi-VN').format(value);
            },
            color: textColor
          },
          grid: {
            color: gridColor
          }
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            display: true,
            text: 'Số vé',
            color: textColor
          },
          ticks: {
            color: textColor
          },
          grid: {
            drawOnChartArea: false,
          },
        }
      }
    }
  });
}

// Khởi tạo biểu đồ lần đầu
if (document.getElementById('revenueChart')) {
  createChart(monthlyData);
}

// Hàm cập nhật biểu đồ
function updateChart() {
  const year = document.getElementById('filterYear').value;
  const period = document.getElementById('filterPeriod').value;
  const yearGlobal = document.getElementById('filterYearGlobal')?.value || '';
  const monthGlobal = document.getElementById('filterMonthGlobal')?.value || '';
  
  // Tạo URL với tham số
  const params = new URLSearchParams();
  params.append('act', 'dashboard');
  params.append('ajax', '1');
  if (year) params.append('year', year);
  if (period) params.append('period', period);
  if (yearGlobal) params.append('year', yearGlobal);
  if (monthGlobal) params.append('month', monthGlobal);
  
  // Gửi request AJAX để lấy dữ liệu mới
  const url = window.location.pathname + '?' + params.toString();
  fetch(url)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.success && data.monthlyStats) {
        createChart(data.monthlyStats);
        if (data.topMovies) {
          updateTopMovies(data.topMovies);
        }
        if (data.totalRevenue !== undefined) {
          updateStatsCards(data.totalRevenue, data.totalTickets);
        }
      } else {
        alert('Không thể tải dữ liệu. Vui lòng thử lại.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Có lỗi xảy ra khi tải dữ liệu.');
    });
}

// Hàm cập nhật top movies
function updateTopMovies(movies) {
  const container = document.getElementById('topMoviesContainer');
  if (!container) return;
  
  if (!movies || movies.length === 0) {
    container.innerHTML = `
      <div class="text-center text-secondary py-4">
        <i class="bi bi-inbox fs-1"></i>
        <p class="mb-0 mt-2">Chưa có dữ liệu</p>
      </div>
    `;
    return;
  }
  
  let html = '<div class="list-group list-group-flush">';
  movies.forEach((movie, index) => {
    const imageUrl = movie.image ? '<?= BASE_URL ?>' + movie.image : '';
    const imageHtml = imageUrl 
      ? `<img src="${imageUrl}" alt="${movie.title}" class="rounded" style="width: 60px; height: 80px; object-fit: cover;">`
      : `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 80px;"><i class="bi bi-film text-white"></i></div>`;
    
    html += `
      <div class="list-group-item border-0">
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <span class="badge bg-warning text-dark position-absolute top-0 start-0 rounded-circle" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
              ${index + 1}
            </span>
            ${imageHtml}
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-1 fw-bold">${movie.title || 'N/A'}</h6>
            <div class="d-flex gap-3 text-secondary small">
              <span><i class="bi bi-ticket"></i> ${parseInt(movie.booking_count || 0).toLocaleString('vi-VN')} vé</span>
              <span><i class="bi bi-cash"></i> ${parseFloat(movie.revenue || 0).toLocaleString('vi-VN')} đ</span>
            </div>
          </div>
        </div>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
}

// Hàm cập nhật thẻ thống kê
function updateStatsCards(revenue, tickets) {
  // Cập nhật tổng doanh thu
  const revenueElement = document.querySelector('.revenue-card h3');
  if (revenueElement) {
    revenueElement.textContent = parseFloat(revenue || 0).toLocaleString('vi-VN');
  }
  
  // Cập nhật tổng số vé
  const ticketsElements = document.querySelectorAll('.tickets-card h3, .status-card h3');
  ticketsElements.forEach(el => {
    if (el) {
      el.textContent = parseInt(tickets || 0).toLocaleString('vi-VN');
    }
  });
}

// Hàm áp dụng bộ lọc toàn cục
function applyGlobalFilter() {
  const year = document.getElementById('filterYearGlobal')?.value || '';
  const month = document.getElementById('filterMonthGlobal')?.value || '';
  
  // Cập nhật URL và reload
  const params = new URLSearchParams(window.location.search);
  if (year) {
    params.set('year', year);
  } else {
    params.delete('year');
  }
  if (month) {
    params.set('month', month);
  } else {
    params.delete('month');
  }
  
  // Reload trang với tham số mới
  window.location.search = params.toString();
}

// Hàm reset bộ lọc
function resetFilter() {
  window.location.search = '';
}

// Hàm refresh top movies
function refreshTopMovies() {
  const year = document.getElementById('filterYearGlobal')?.value || '';
  const month = document.getElementById('filterMonthGlobal')?.value || '';
  
  const params = new URLSearchParams();
  params.append('act', 'dashboard');
  params.append('ajax', '1');
  if (year) params.append('year', year);
  if (month) params.append('month', month);
  
  fetch(window.location.pathname + '?' + params.toString())
    .then(response => response.json())
    .then(data => {
      if (data.success && data.topMovies) {
        updateTopMovies(data.topMovies);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

// Lắng nghe sự kiện thay đổi theme để cập nhật màu biểu đồ
const observer = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
      if (revenueChart) {
        updateChart();
      }
    }
  });
});

observer.observe(document.documentElement, {
  attributes: true,
  attributeFilter: ['data-theme']
});

function exportReport() {
  alert('Chức năng xuất báo cáo sẽ được triển khai sau');
}

function shareReport() {
  if (navigator.share) {
    navigator.share({
      title: 'Báo cáo Dashboard',
      text: 'Xem báo cáo thống kê hệ thống',
      url: window.location.href
    });
  } else {
    navigator.clipboard.writeText(window.location.href);
    alert('Đã sao chép link vào clipboard!');
  }
}
</script>
