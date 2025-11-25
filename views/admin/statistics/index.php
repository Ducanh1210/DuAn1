<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h4 class="mb-0"><i class="bi bi-bar-chart"></i> Thống Kê</h4>
    </div>
    <div class="card-body">
      <!-- Tổng quan -->
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <h6 class="card-title">Tổng đặt vé</h6>
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

      <!-- Top phim bán chạy -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Top 10 Phim Bán Chạy</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Phim</th>
                      <th>Số vé</th>
                      <th>Doanh thu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($topMovies)): ?>
                      <?php foreach ($topMovies as $movie): ?>
                      <tr>
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
                    <?php if (!empty($topCinemas)): ?>
                      <?php foreach ($topCinemas as $cinema): ?>
                      <tr>
                        <td><?= htmlspecialchars($cinema['name']) ?></td>
                        <td><?= number_format($cinema['booking_count'], 0, ',', '.') ?></td>
                        <td><strong><?= number_format($cinema['revenue'] ?? 0, 0, ',', '.') ?> đ</strong></td>
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

