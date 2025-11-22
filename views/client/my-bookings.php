<div class="page">
  <header class="page-header" role="banner">
    <h2><span class="dot" aria-hidden="true"></span> Lịch sử đặt vé</h2>
  </header>

  <section class="bookings-list">
    <?php if (!empty($bookings)): ?>
      <div class="table-container">
        <table class="bookings-table">
          <thead>
            <tr>
              <th>Mã đặt vé</th>
              <th>Tên phim</th>
              <th>Rạp</th>
              <th>Phòng</th>
              <th>Ngày chiếu</th>
              <th>Giờ chiếu</th>
              <th>Ghế</th>
              <th>Định dạng</th>
              <th>Ngày đặt</th>
              <th>Trạng thái</th>
              <th>Tổng tiền</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $booking): ?>
              <tr>
                <td class="booking-code-cell">
                  <strong><?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></strong>
                </td>
                <td class="movie-title-cell">
                  <div class="movie-info">
                    <?php if (!empty($booking['movie_image'])): ?>
                      <img src="<?= BASE_URL . '/' . $booking['movie_image'] ?>" 
                           alt="<?= htmlspecialchars($booking['movie_title'] ?? '') ?>"
                           class="movie-poster-small">
                    <?php endif; ?>
                    <span><?= htmlspecialchars($booking['movie_title'] ?? 'N/A') ?></span>
                  </div>
                </td>
                <td><?= htmlspecialchars($booking['cinema_name'] ?? 'N/A') ?></td>
                <td>
                  <?= htmlspecialchars($booking['room_name'] ?? 'N/A') ?>
                  <?php if (!empty($booking['room_code'])): ?>
                    <br><small class="room-code">(<?= htmlspecialchars($booking['room_code']) ?>)</small>
                  <?php endif; ?>
                </td>
                <td><?= !empty($booking['show_date']) ? date('d/m/Y', strtotime($booking['show_date'])) : 'N/A' ?></td>
                <td>
                  <?= !empty($booking['start_time']) ? date('H:i', strtotime($booking['start_time'])) : 'N/A' ?>
                  <?php if (!empty($booking['end_time'])): ?>
                    <br><small>- <?= date('H:i', strtotime($booking['end_time'])) ?></small>
                  <?php endif; ?>
                </td>
                <td class="seats-cell">
                  <strong><?= htmlspecialchars($booking['booked_seats'] ?? 'N/A') ?></strong>
                </td>
                <td><?= htmlspecialchars($booking['showtime_format'] ?? '2D') ?></td>
                <td><?= !empty($booking['booking_date']) ? date('d/m/Y H:i', strtotime($booking['booking_date'])) : 'N/A' ?></td>
                <td>
                  <?php
                  $statusClass = 'secondary';
                  $statusText = 'N/A';
                  $currentStatus = $booking['status'] ?? '';
                  switch($currentStatus) {
                    case 'pending':
                      $statusClass = 'warning';
                      $statusText = 'Chờ xử lý';
                      break;
                    case 'confirmed':
                      $statusClass = 'info';
                      $statusText = 'Đã xác nhận';
                      break;
                    case 'paid':
                      $statusClass = 'success';
                      $statusText = 'Đã thanh toán';
                      break;
                    case 'cancelled':
                      $statusClass = 'danger';
                      $statusText = 'Đã hủy';
                      break;
                    default:
                      $statusClass = 'secondary';
                      $statusText = $currentStatus ? htmlspecialchars($currentStatus) : 'N/A';
                      break;
                  }
                  ?>
                  <span class="status-badge status-<?= $statusClass ?>"><?= $statusText ?></span>
                </td>
                <td class="price-cell">
                  <strong><?= !empty($booking['final_amount']) ? number_format($booking['final_amount'], 0, ',', '.') . ' đ' : 'N/A' ?></strong>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <nav class="pagination-nav">
          <ul class="pagination">
            <?php if ($pagination['currentPage'] > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?act=my-bookings&page=<?= $pagination['currentPage'] - 1 ?>">Trước</a>
              </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
              <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                <a class="page-link" href="?act=my-bookings&page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            
            <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
              <li class="page-item">
                <a class="page-link" href="?act=my-bookings&page=<?= $pagination['currentPage'] + 1 ?>">Sau</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      <?php endif; ?>
    <?php else: ?>
      <div class="empty-state">
        <p>Bạn chưa có đặt vé nào.</p>
        <a href="<?= BASE_URL ?>?act=lichchieu" class="btn-booking">Đặt vé ngay</a>
      </div>
    <?php endif; ?>
  </section>
</div>

<style>
.bookings-list {
  max-width: 100%;
  margin: 0 auto;
  padding: 20px;
  overflow-x: auto;
}

.table-container {
  width: 100%;
  overflow-x: auto;
  background: rgba(255, 255, 255, 0.02);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 20px;
}

.bookings-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1400px;
  background: transparent;
}

.bookings-table thead {
  background: rgba(255, 255, 255, 0.05);
  border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.bookings-table th {
  padding: 15px 12px;
  text-align: left;
  color: #fff;
  font-weight: 600;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  white-space: nowrap;
  border-right: 1px solid rgba(255, 255, 255, 0.05);
}

.bookings-table th:last-child {
  border-right: none;
}

.bookings-table tbody tr {
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  transition: all 0.3s ease;
}

.bookings-table tbody tr:hover {
  background: rgba(255, 255, 255, 0.05);
  transform: scale(1.01);
}

.bookings-table tbody tr:last-child {
  border-bottom: none;
}

.bookings-table td {
  padding: 15px 12px;
  color: rgba(255, 255, 255, 0.9);
  font-size: 14px;
  vertical-align: middle;
  border-right: 1px solid rgba(255, 255, 255, 0.03);
}

.bookings-table td:last-child {
  border-right: none;
}

.booking-code-cell {
  font-family: 'Courier New', monospace;
  color: #4a9eff !important;
  font-weight: 600;
}

.booking-code-cell strong {
  font-size: 15px;
  letter-spacing: 1px;
}

.movie-title-cell {
  min-width: 200px;
}

.movie-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.movie-poster-small {
  width: 50px;
  height: 70px;
  object-fit: cover;
  border-radius: 6px;
  flex-shrink: 0;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.movie-info span {
  flex: 1;
  font-weight: 500;
  line-height: 1.4;
}

.room-code {
  color: rgba(255, 255, 255, 0.6);
  font-size: 12px;
}

.seats-cell {
  font-weight: 600;
  color: #ffd700 !important;
}

.price-cell {
  color: #ff6978 !important;
  font-size: 15px;
  text-align: right;
  font-weight: 700;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
  text-align: center;
  min-width: 90px;
}

.status-warning {
  background: #ffc107;
  color: #000;
}

.status-info {
  background: #0dcaf0;
  color: #000;
}

.status-success {
  background: #198754;
  color: #fff;
}

.status-danger {
  background: #dc3545;
  color: #fff;
}

.status-secondary {
  background: #6c757d;
  color: #fff;
}

.pagination-nav {
  margin-top: 30px;
  display: flex;
  justify-content: center;
}

.pagination {
  display: flex;
  gap: 8px;
  list-style: none;
  padding: 0;
}

.page-item {
  margin: 0;
}

.page-link {
  padding: 8px 16px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 6px;
  color: #fff;
  text-decoration: none;
  transition: all 0.2s;
}

.page-link:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.2);
}

.page-item.active .page-link {
  background: #ff6978;
  border-color: #ff6978;
  color: #fff;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: rgba(255, 255, 255, 0.6);
}

.empty-state p {
  font-size: 18px;
  margin-bottom: 20px;
}

.btn-booking {
  display: inline-block;
  padding: 12px 24px;
  background: #ff6978;
  color: #fff;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-booking:hover {
  background: #ff5067;
  transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 1200px) {
  .bookings-table {
    min-width: 1200px;
  }
  
  .bookings-table th,
  .bookings-table td {
    padding: 12px 8px;
    font-size: 13px;
  }
}

@media (max-width: 768px) {
  .table-container {
    padding: 10px;
  }
  
  .bookings-table {
    min-width: 1000px;
  }
  
  .bookings-table th,
  .bookings-table td {
    padding: 10px 6px;
    font-size: 12px;
  }
  
  .movie-poster-small {
    width: 40px;
    height: 56px;
  }
  
  .status-badge {
    font-size: 11px;
    padding: 4px 8px;
    min-width: 70px;
  }
}
</style>

<script>
// Auto-refresh trang mỗi 30 giây để cập nhật trạng thái mới nhất
(function() {
    let refreshInterval = null;
    
    function refreshPage() {
        // Chỉ refresh nếu không có thao tác nào đang diễn ra
        if (!document.hidden) {
            window.location.reload();
        }
    }
    
    // Bắt đầu auto-refresh sau 15 giây (giảm từ 30 giây để cập nhật nhanh hơn)
    refreshInterval = setInterval(refreshPage, 15000);
    
    // Clear interval khi user rời khỏi trang
    window.addEventListener('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
    
    // Pause refresh khi tab không active
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        } else {
            refreshInterval = setInterval(refreshPage, 30000);
        }
    });
})();
</script>

