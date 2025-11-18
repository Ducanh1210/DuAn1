<div class="page">
  <header class="page-header" role="banner">
    <h2><span class="dot" aria-hidden="true"></span> Lịch sử đặt vé</h2>
  </header>

  <section class="bookings-list">
    <?php if (!empty($data)): ?>
      <?php foreach ($data as $booking): ?>
        <div class="booking-card">
          <div class="booking-header">
            <div class="booking-info">
              <h3><?= htmlspecialchars($booking['movie_title'] ?? 'N/A') ?></h3>
              <p class="booking-code">Mã đặt vé: <strong><?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></strong></p>
            </div>
            <div class="booking-status">
              <?php
              $statusClass = 'secondary';
              $statusText = 'N/A';
              switch($booking['status'] ?? '') {
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
              }
              ?>
              <span class="status-badge status-<?= $statusClass ?>"><?= $statusText ?></span>
            </div>
          </div>

          <div class="booking-body">
            <div class="booking-poster">
              <?php if (!empty($booking['movie_image'])): ?>
                <img src="<?= BASE_URL . '/' . $booking['movie_image'] ?>" 
                     alt="<?= htmlspecialchars($booking['movie_title'] ?? '') ?>">
              <?php else: ?>
                <img src="<?= BASE_URL ?>/image/logo.png" alt="Poster">
              <?php endif; ?>
            </div>

            <div class="booking-details">
              <div class="detail-row">
                <span class="label">Rạp:</span>
                <span class="value"><?= htmlspecialchars($booking['cinema_name'] ?? 'N/A') ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Phòng:</span>
                <span class="value"><?= htmlspecialchars($booking['room_name'] ?? 'N/A') ?> (<?= htmlspecialchars($booking['room_code'] ?? '') ?>)</span>
              </div>
              <div class="detail-row">
                <span class="label">Ngày chiếu:</span>
                <span class="value"><?= $booking['show_date'] ? date('d/m/Y', strtotime($booking['show_date'])) : 'N/A' ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Giờ chiếu:</span>
                <span class="value">
                  <?= $booking['start_time'] ? date('H:i', strtotime($booking['start_time'])) : 'N/A' ?>
                  <?php if (!empty($booking['end_time'])): ?>
                    - <?= date('H:i', strtotime($booking['end_time'])) ?>
                  <?php endif; ?>
                </span>
              </div>
              <div class="detail-row">
                <span class="label">Ghế:</span>
                <span class="value"><strong><?= htmlspecialchars($booking['booked_seats'] ?? 'N/A') ?></strong></span>
              </div>
              <div class="detail-row">
                <span class="label">Định dạng:</span>
                <span class="value"><?= htmlspecialchars($booking['showtime_format'] ?? '2D') ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Ngày đặt:</span>
                <span class="value"><?= $booking['booking_date'] ? date('d/m/Y H:i', strtotime($booking['booking_date'])) : 'N/A' ?></span>
              </div>
            </div>

            <div class="booking-price">
              <div class="price-label">Tổng tiền</div>
              <div class="price-value"><?= $booking['final_amount'] ? number_format($booking['final_amount'], 0, ',', '.') . ' đ' : 'N/A' ?></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

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
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.booking-card {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
}

.booking-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.booking-info h3 {
  color: #fff;
  font-size: 20px;
  margin: 0 0 8px 0;
}

.booking-code {
  color: rgba(255, 255, 255, 0.7);
  font-size: 14px;
  margin: 0;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
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

.booking-body {
  display: grid;
  grid-template-columns: 120px 1fr auto;
  gap: 20px;
  align-items: start;
}

.booking-poster img {
  width: 100%;
  height: auto;
  border-radius: 8px;
  object-fit: cover;
}

.booking-details {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.detail-row {
  display: flex;
  gap: 10px;
}

.detail-row .label {
  color: rgba(255, 255, 255, 0.6);
  min-width: 100px;
}

.detail-row .value {
  color: #fff;
}

.booking-price {
  text-align: right;
}

.price-label {
  color: rgba(255, 255, 255, 0.6);
  font-size: 14px;
  margin-bottom: 5px;
}

.price-value {
  color: #ff6978;
  font-size: 24px;
  font-weight: bold;
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

@media (max-width: 768px) {
  .booking-body {
    grid-template-columns: 1fr;
  }
  
  .booking-price {
    text-align: left;
    margin-top: 15px;
  }
}
</style>

