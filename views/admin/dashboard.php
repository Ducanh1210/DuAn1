<div class="container-fluid">
  <!-- Dashboard Header -->
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div>
      <h2 class="mb-1 d-flex align-items-center gap-2">
        <i class="bi bi-speedometer2 text-primary"></i>
        <span>Dashboard</span>
      </h2>
      <p class="text-muted mb-0">
        <i class="bi bi-calendar3"></i> <?= date('d/m/Y H:i') ?> - Tổng quan hệ thống và thống kê
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary d-flex align-items-center gap-2" onclick="exportReport()">
        <i class="bi bi-download"></i>
        <span>Xuất báo cáo</span>
      </button>
    </div>
  </div>

  <!-- Statistics Cards Row 1 -->
  <div class="row g-4 mb-4">
    <!-- Card 1: Doanh thu -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card revenue-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Tổng doanh thu</p>
              <h3 class="mb-0 fw-bold text-primary"><?= number_format($totalRevenue, 0, ',', '.') ?></h3>
              <small class="text-muted">VNĐ</small>
            </div>
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-currency-exchange fs-3 text-primary"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
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
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Tổng số vé</p>
              <h3 class="mb-0 fw-bold text-success"><?= number_format($totalTickets) ?></h3>
              <small class="text-muted">vé đã bán</small>
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

    <!-- Card 3: Người dùng -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card users-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Người dùng</p>
              <h3 class="mb-0 fw-bold text-warning"><?= number_format($totalUsers) ?></h3>
              <small class="text-muted">tổng số</small>
            </div>
            <div class="p-3 rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-people fs-3 text-warning"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge bg-success-subtle text-success">
              <i class="bi bi-check-circle"></i> Hoạt động: <?= number_format($activeUsers) ?>
            </span>
            <?php if ($bannedUsers > 0): ?>
            <span class="badge bg-danger-subtle text-danger">
              <i class="bi bi-lock"></i> Đã khóa: <?= number_format($bannedUsers) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4: Phim -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm border-0 dashboard-card movies-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Phim</p>
              <h3 class="mb-0 fw-bold text-info"><?= number_format($totalMovies) ?></h3>
              <small class="text-muted">tổng số</small>
            </div>
            <div class="p-3 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 60px; min-height: 60px;">
              <i class="bi bi-film fs-3 text-info"></i>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
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
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Nhân viên</p>
              <h3 class="mb-0 fw-bold text-primary"><?= number_format($totalStaff) ?></h3>
              <small class="text-muted">tổng số</small>
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
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Phòng chiếu</p>
              <h3 class="mb-0 fw-bold text-cyan"><?= number_format($totalRooms) ?></h3>
              <small class="text-muted">tổng số</small>
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
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Rạp chiếu</p>
              <h3 class="mb-0 fw-bold text-danger"><?= number_format($totalCinemas) ?></h3>
              <small class="text-muted">tổng số</small>
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
              <p class="text-muted small mb-1 fw-semibold text-uppercase">Đặt vé</p>
              <h3 class="mb-0 fw-bold text-purple"><?= number_format($totalTickets) ?></h3>
              <small class="text-muted">tổng số</small>
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
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-graph-up text-primary"></i>
            <span>Doanh thu theo tháng (7 tháng gần nhất)</span>
          </h5>
        </div>
        <div class="card-body">
          <canvas id="revenueChart" height="100"></canvas>
        </div>
      </div>
    </div>

    <!-- Top phim bán chạy -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-trophy text-warning"></i>
            <span>Top 5 phim bán chạy</span>
          </h5>
        </div>
        <div class="card-body p-0">
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
                      <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-ticket"></i> <?= number_format($movie['booking_count'] ?? 0) ?> vé</span>
                        <span><i class="bi bi-cash"></i> <?= number_format($movie['revenue'] ?? 0, 0, ',', '.') ?> đ</span>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center text-muted py-4">
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
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
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
                <thead class="table-light">
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
                          <small class="text-muted"><?= htmlspecialchars($booking['user_email'] ?? '') ?></small>
                        </div>
                      </td>
                      <td>
                        <div class="fw-semibold"><?= htmlspecialchars($booking['movie_title'] ?? 'N/A') ?></div>
                      </td>
                      <td>
                        <?php if ($booking['show_date']): ?>
                          <div><?= date('d/m/Y', strtotime($booking['show_date'])) ?></div>
                          <small class="text-muted"><?= date('H:i', strtotime($booking['start_time'])) ?></small>
                        <?php else: ?>
                          <span class="text-muted">N/A</span>
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
                        <small class="text-muted">
                          <?= $booking['booking_date'] ? date('d/m/Y H:i', strtotime($booking['booking_date'])) : 'N/A' ?>
                        </small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center text-muted py-5">
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
// Biểu đồ doanh thu theo tháng
const monthlyData = <?= json_encode($monthlyStats) ?>;
const ctx = document.getElementById('revenueChart');
if (ctx) {
  const labels = monthlyData.map(item => {
    const [year, month] = item.month.split('-');
    const monthNames = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
    return monthNames[parseInt(month) - 1] + '/' + year;
  });
  const revenueData = monthlyData.map(item => parseFloat(item.revenue || 0));
  const bookingsData = monthlyData.map(item => parseInt(item.bookings || 0));

  new Chart(ctx, {
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
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          title: {
            display: true,
            text: 'Doanh thu (VNĐ)'
          },
          ticks: {
            callback: function(value) {
              return new Intl.NumberFormat('vi-VN').format(value);
            }
          }
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            display: true,
            text: 'Số vé'
          },
          grid: {
            drawOnChartArea: false,
          },
        }
      }
    }
  });
}

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
