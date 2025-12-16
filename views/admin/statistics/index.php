<?php
// STATISTICS/INDEX.PHP - TRANG THỐNG KÊ ADMIN
// Chức năng: Hiển thị thống kê chi tiết (tổng đặt vé, doanh thu, top phim, biểu đồ) với bộ lọc năm/tháng
// Biến từ controller: $stats (thống kê tổng quan), $topMovies (top phim bán chạy), $revenueByMonth (doanh thu theo tháng), $filterYear, $filterMonth, $cinemaName
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và bộ lọc năm/tháng -->
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h4 class="mb-0">
          <i class="bi bi-bar-chart"></i> Thống Kê
          <!-- Badge hiển thị tên rạp nếu đang lọc theo rạp -->
          <?php if (!empty($cinemaName)): ?>
            <span class="badge bg-primary ms-2"><?= htmlspecialchars($cinemaName) ?></span>
          <?php endif; ?>
        </h4>
        <div class="d-flex gap-2 align-items-center flex-wrap">
          <!-- Dropdown chọn năm: hiển thị từ năm hiện tại về trước 5 năm -->
          <select id="filterYear" class="form-select form-select-sm" style="width: auto;">
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
          <select id="filterMonth" class="form-select form-select-sm" style="width: auto;">
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
          <!-- Nút apply filter: gọi hàm JavaScript applyFilter() -->
          <button class="btn btn-sm btn-primary" onclick="applyFilter()">
            <i class="bi bi-funnel"></i> Lọc
          </button>
          <!-- Nút reset filter: gọi hàm JavaScript resetFilter() -->
          <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
          </button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Hàng thẻ thống kê tổng quan -->
      <div class="row mb-4">
        <!-- Card tổng đặt vé: $stats['totalBookings'] từ controller -->
        <div class="col-md-3 mb-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <h6 class="card-title">Tổng đặt vé</h6>
              <!-- Hiển thị tổng số đặt vé: format số với dấu phẩy -->
              <h3 class="mb-0"><?= number_format($stats['totalBookings'], 0, ',', '.') ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <h6 class="card-title">Tổng doanh thu</h6>
              <h3 class="mb-0"><?= number_format($stats['totalRevenue'], 0, ',', '.') ?> đ</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card bg-info text-white">
            <div class="card-body">
              <h6 class="card-title">Hôm nay</h6>
              <h5 class="mb-0"><?= number_format($stats['todayBookings'], 0, ',', '.') ?> vé</h5>
              <small><?= number_format($stats['todayRevenue'], 0, ',', '.') ?> đ</small>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card bg-warning text-white">
            <div class="card-body">
              <h6 class="card-title">Tháng này</h6>
              <h5 class="mb-0"><?= number_format($stats['thisMonthBookings'], 0, ',', '.') ?> vé</h5>
              <small><?= number_format($stats['thisMonthRevenue'], 0, ',', '.') ?> đ</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Thống kê khác -->
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <h6 class="text-muted">Tổng khách hàng</h6>
              <h4 class="mb-0"><?= number_format($stats['totalUsers'], 0, ',', '.') ?></h4>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <h6 class="text-muted">Tổng phim</h6>
              <h4 class="mb-0"><?= number_format($stats['totalMovies'], 0, ',', '.') ?></h4>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <h6 class="text-muted">Tổng rạp</h6>
              <h4 class="mb-0"><?= number_format($stats['totalCinemas'], 0, ',', '.') ?></h4>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <h6 class="text-muted">Tổng phòng</h6>
              <h4 class="mb-0"><?= number_format($stats['totalRooms'], 0, ',', '.') ?></h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Biểu đồ thống kê phim theo tháng -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="bi bi-graph-up text-primary"></i>
            Thống kê phim theo tháng
            <?php if (isset($filterYear) || isset($filterMonth)): ?>
              <small class="text-muted">
                <?php if (isset($filterYear)): ?>
                  - Năm <?= $filterYear ?>
                <?php endif; ?>
                <?php if (isset($filterMonth)): ?>
                  - <?= $monthNames[$filterMonth - 1] ?? "Tháng $filterMonth" ?>
                <?php endif; ?>
              </small>
            <?php endif; ?>
          </h5>
        </div>
        <div class="card-body">
          <canvas id="movieStatsChart" height="100"></canvas>
        </div>
      </div>

      <!-- Thống kê theo tháng -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Thống kê theo tháng (12 tháng gần nhất)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Tháng</th>
                  <th>Số đặt vé</th>
                  <th>Doanh thu</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($monthlyStats)): ?>
                  <?php foreach ($monthlyStats as $stat): ?>
                  <tr>
                    <td><?= htmlspecialchars($stat['month_label']) ?></td>
                    <td><?= number_format($stat['bookings'], 0, ',', '.') ?></td>
                    <td><strong><?= number_format($stat['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" class="text-center text-muted">Chưa có dữ liệu</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Top phim bán chạy và phim hot -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bi bi-trophy"></i> Top 5 Phim Bán Chạy Nhất
                <?php if (!empty($cinemaName)): ?>
                  <small class="text-muted">(<?= htmlspecialchars($cinemaName) ?>)</small>
                <?php endif; ?>
                <?php if (isset($filterYear) || isset($filterMonth)): ?>
                  <br><small class="text-muted">
                    <?php if (isset($filterYear)): ?>
                      Năm <?= $filterYear ?>
                    <?php endif; ?>
                    <?php if (isset($filterMonth)): ?>
                      - <?= $monthNames[$filterMonth - 1] ?? "Tháng $filterMonth" ?>
                    <?php endif; ?>
                  </small>
                <?php endif; ?>
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Phim</th>
                      <th>Số vé</th>
                      <th>Doanh thu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($topMovies)): ?>
                      <?php $rank = 1; ?>
                      <?php foreach ($topMovies as $movie): ?>
                      <tr>
                        <td>
                          <span class="badge bg-<?= $rank <= 3 ? 'warning' : 'secondary' ?>"><?= $rank ?></span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <?php if (!empty($movie['image'])): ?>
                              <img src="<?= BASE_URL . '/' . $movie['image'] ?>" 
                                   alt="<?= htmlspecialchars($movie['title']) ?>" 
                                   style="width: 30px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 8px;">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($movie['title']) ?></span>
                          </div>
                        </td>
                        <td><?= number_format($movie['booking_count'], 0, ',', '.') ?></td>
                        <td><strong><?= number_format($movie['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                      </tr>
                      <?php $rank++; ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bi bi-fire"></i> Top 5 Phim Hot Nhất
                <?php if (isset($filterYear) || isset($filterMonth)): ?>
                  <?php if (isset($filterYear)): ?>
                    (Năm <?= $filterYear ?>
                    <?php if (isset($filterMonth)): ?>
                      - <?= $monthNames[$filterMonth - 1] ?? "Tháng $filterMonth" ?>
                    <?php endif; ?>)
                  <?php elseif (isset($filterMonth)): ?>
                    (<?= $monthNames[$filterMonth - 1] ?? "Tháng $filterMonth" ?>)
                  <?php endif; ?>
                <?php else: ?>
                  (30 ngày gần đây)
                <?php endif; ?>
                <?php if (!empty($cinemaName)): ?>
                  <small class="text-muted">(<?= htmlspecialchars($cinemaName) ?>)</small>
                <?php endif; ?>
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Phim</th>
                      <th>Số vé</th>
                      <th>Doanh thu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($hotMovies)): ?>
                      <?php $rank = 1; ?>
                      <?php foreach ($hotMovies as $movie): ?>
                      <tr>
                        <td>
                          <span class="badge bg-<?= $rank <= 3 ? 'danger' : 'secondary' ?>"><?= $rank ?></span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <?php if (!empty($movie['image'])): ?>
                              <img src="<?= BASE_URL . '/' . $movie['image'] ?>" 
                                   alt="<?= htmlspecialchars($movie['title']) ?>" 
                                   style="width: 30px; height: 45px; object-fit: cover; border-radius: 4px; margin-right: 8px;">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($movie['title']) ?></span>
                          </div>
                        </td>
                        <td><?= number_format($movie['booking_count'], 0, ',', '.') ?></td>
                        <td><strong><?= number_format($movie['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                      </tr>
                      <?php $rank++; ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top rạp doanh thu cao (chỉ admin) -->
      <?php if (!empty($topCinemas)): ?>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Top 10 Rạp Doanh Thu Cao</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Rạp</th>
                      <th>Số vé</th>
                      <th>Doanh thu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($topCinemas as $cinema): ?>
                    <tr>
                      <td><?= htmlspecialchars($cinema['name']) ?></td>
                      <td><?= number_format($cinema['booking_count'], 0, ',', '.') ?></td>
                      <td><strong><?= number_format($cinema['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Thống kê theo trạng thái -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Thống kê theo Trạng thái Đặt vé</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Trạng thái</th>
                      <th>Số lượng</th>
                      <th>Doanh thu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($bookingStatusStats)): ?>
                      <?php foreach ($bookingStatusStats as $stat): ?>
                      <tr>
                        <td>
                          <?php
                          $statusLabels = [
                            'pending' => 'Chờ xử lý',
                            'paid' => 'Đã thanh toán',
                            'confirmed' => 'Đã xác nhận',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy'
                          ];
                          echo htmlspecialchars($statusLabels[$stat['status']] ?? ucfirst($stat['status']));
                          ?>
                        </td>
                        <td><?= number_format($stat['count'], 0, ',', '.') ?></td>
                        <td><strong><?= number_format($stat['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                      </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3" class="text-center text-muted">Chưa có dữ liệu</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Thống kê theo Phương thức Thanh toán</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Phương thức</th>
                      <th>Số lượng</th>
                      <th>Doanh thu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($paymentMethodStats)): ?>
                      <?php foreach ($paymentMethodStats as $stat): ?>
                      <tr>
                        <td><?= htmlspecialchars(ucfirst($stat['method'] ?? 'N/A')) ?></td>
                        <td><?= number_format($stat['count'], 0, ',', '.') ?></td>
                        <td><strong><?= number_format($stat['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                      </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3" class="text-center text-muted">Chưa có dữ liệu</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Biến toàn cục cho chart
let movieStatsChart = null;
const movieStatsData = <?= json_encode($movieStats ?? []) ?>;

// Hàm tạo/cập nhật biểu đồ
function createMovieChart(data) {
  const ctx = document.getElementById('movieStatsChart');
  if (!ctx) return;

  // Hủy chart cũ nếu có
  if (movieStatsChart) {
    movieStatsChart.destroy();
  }

  // Lấy top 10 phim
  const topMovies = data.slice(0, 10);
  const labels = topMovies.map(movie => movie.title || 'N/A');
  const bookingData = topMovies.map(movie => parseInt(movie.booking_count || 0));
  const revenueData = topMovies.map(movie => parseFloat(movie.revenue || 0));

  // Lấy màu text từ theme
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const textColor = isDark ? '#e9ecef' : '#212529';
  const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

  movieStatsChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Số vé bán',
          data: bookingData,
          backgroundColor: 'rgba(13, 110, 253, 0.6)',
          borderColor: 'rgb(13, 110, 253)',
          borderWidth: 1,
          yAxisID: 'y'
        },
        {
          label: 'Doanh thu (VNĐ)',
          data: revenueData,
          backgroundColor: 'rgba(25, 135, 84, 0.6)',
          borderColor: 'rgb(25, 135, 84)',
          borderWidth: 1,
          yAxisID: 'y1',
          type: 'line'
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
                return 'Số vé: ' + context.parsed.y;
              } else {
                return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
              }
            }
          }
        }
      },
      scales: {
        x: {
          ticks: {
            color: textColor,
            maxRotation: 45,
            minRotation: 45
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
            text: 'Số vé',
            color: textColor
          },
          ticks: {
            color: textColor,
            stepSize: 1
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
            text: 'Doanh thu (VNĐ)',
            color: textColor
          },
          ticks: {
            color: textColor,
            callback: function(value) {
              return new Intl.NumberFormat('vi-VN').format(value);
            }
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
if (document.getElementById('movieStatsChart') && movieStatsData.length > 0) {
  createMovieChart(movieStatsData);
}

// Hàm áp dụng bộ lọc
function applyFilter() {
  const year = document.getElementById('filterYear').value;
  const month = document.getElementById('filterMonth').value;
  
  const params = new URLSearchParams(window.location.search);
  params.set('act', 'statistics');
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
  window.location.search = '?act=statistics';
}

// Lắng nghe sự kiện thay đổi theme để cập nhật màu biểu đồ
const observer = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
      if (movieStatsChart && movieStatsData.length > 0) {
        createMovieChart(movieStatsData);
      }
    }
  });
});

observer.observe(document.documentElement, {
  attributes: true,
  attributeFilter: ['data-theme']
});
</script>

<style>
/* Dark mode support cho statistics - Đồng nhất nền tối và chữ trắng */
[data-theme="dark"] .container-fluid {
  background-color: var(--bg-body) !important;
}

[data-theme="dark"] .card {
  background-color: var(--bg-card) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card-header {
  background-color: var(--bg-card) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card-header h4,
[data-theme="dark"] .card-header h5,
[data-theme="dark"] .card-header h6,
[data-theme="dark"] .card-header span,
[data-theme="dark"] .card-header small {
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card-body {
  background-color: var(--bg-card) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card-title {
  color: var(--text-primary) !important;
}

/* Cards thống kê tổng quan */
[data-theme="dark"] .card.bg-primary,
[data-theme="dark"] .card.bg-success,
[data-theme="dark"] .card.bg-info,
[data-theme="dark"] .card.bg-warning {
  background-color: var(--bg-card) !important;
  color: var(--text-primary) !important;
  border: 1px solid var(--border-color) !important;
}

[data-theme="dark"] .card.bg-primary .card-title,
[data-theme="dark"] .card.bg-success .card-title,
[data-theme="dark"] .card.bg-info .card-title,
[data-theme="dark"] .card.bg-warning .card-title {
  color: rgba(255, 255, 255, 0.8) !important;
}

[data-theme="dark"] .card.bg-primary h3,
[data-theme="dark"] .card.bg-primary h5,
[data-theme="dark"] .card.bg-success h3,
[data-theme="dark"] .card.bg-success h5,
[data-theme="dark"] .card.bg-info h3,
[data-theme="dark"] .card.bg-info h5,
[data-theme="dark"] .card.bg-warning h3,
[data-theme="dark"] .card.bg-warning h5 {
  color: var(--text-primary) !important;
}

[data-theme="dark"] .card.bg-primary small,
[data-theme="dark"] .card.bg-success small,
[data-theme="dark"] .card.bg-info small,
[data-theme="dark"] .card.bg-warning small {
  color: rgba(255, 255, 255, 0.7) !important;
}

/* Tables */
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

[data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255, 255, 255, 0.02) !important;
}

[data-theme="dark"] .table-striped tbody tr:nth-of-type(odd):hover {
  background-color: rgba(255, 255, 255, 0.05) !important;
}

[data-theme="dark"] .table tbody tr:nth-of-type(even) {
  background-color: var(--bg-card) !important;
}

[data-theme="dark"] .table tbody tr:nth-of-type(even):hover {
  background-color: rgba(255, 255, 255, 0.05) !important;
}

[data-theme="dark"] .table tbody strong {
  color: var(--text-primary) !important;
}

/* Text colors */
[data-theme="dark"] .text-muted {
  color: rgba(255, 255, 255, 0.6) !important;
}

[data-theme="dark"] .text-secondary {
  color: rgba(255, 255, 255, 0.7) !important;
}

[data-theme="dark"] h4,
[data-theme="dark"] h5,
[data-theme="dark"] h6 {
  color: var(--text-primary) !important;
}

/* Badges */
[data-theme="dark"] .badge {
  color: var(--text-primary) !important;
}

[data-theme="dark"] .badge.bg-primary {
  background-color: rgba(13, 110, 253, 0.3) !important;
  color: #6ea8fe !important;
}

[data-theme="dark"] .badge.bg-warning {
  background-color: rgba(255, 193, 7, 0.3) !important;
  color: #ffc107 !important;
}

[data-theme="dark"] .badge.bg-secondary {
  background-color: rgba(108, 117, 125, 0.3) !important;
  color: #adb5bd !important;
}

[data-theme="dark"] .badge.bg-danger {
  background-color: rgba(220, 53, 69, 0.3) !important;
  color: #f1aeb5 !important;
}

/* Form controls */
[data-theme="dark"] .form-select {
  background-color: var(--bg-card) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .form-select:focus {
  background-color: var(--bg-card) !important;
  border-color: var(--accent) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .btn-outline-secondary {
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

[data-theme="dark"] .btn-outline-secondary:hover {
  background-color: rgba(255, 255, 255, 0.1) !important;
  border-color: var(--border-color) !important;
  color: var(--text-primary) !important;
}

/* Images trong table */
[data-theme="dark"] .table img {
  border: 1px solid var(--border-color) !important;
}
</style>

