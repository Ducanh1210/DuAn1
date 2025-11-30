<?php
// Tách họ và tên
$fullName = $user['full_name'] ?? '';
$nameParts = explode(' ', $fullName, 2);
$lastName = $nameParts[0] ?? '';
$firstName = isset($nameParts[1]) ? $nameParts[1] : '';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/profile.css">

<div class="profile-page">
  <div class="profile-container">
    <h1 class="profile-title">Thông tin cá nhân</h1>
    
    <!-- Tabs Navigation -->
    <div class="profile-tabs">
      <a href="<?= BASE_URL ?>?act=profile&tab=account" 
         class="tab-item <?= $tab === 'account' ? 'active' : '' ?>">
        Tài khoản của tôi
      </a>
      <a href="<?= BASE_URL ?>?act=profile&tab=membership" 
         class="tab-item <?= $tab === 'membership' ? 'active' : '' ?>">
        Thông tin thẻ thành viên U22
      </a>
      <a href="<?= BASE_URL ?>?act=profile&tab=bookings" 
         class="tab-item <?= $tab === 'bookings' ? 'active' : '' ?>">
        Lịch sử mua vé
      </a>
      <a href="<?= BASE_URL ?>?act=profile&tab=rewards" 
         class="tab-item <?= $tab === 'rewards' ? 'active' : '' ?>">
        Lịch sử điểm thưởng
      </a>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
      <?php if ($tab === 'account'): ?>
        <!-- Tab: Tài khoản của tôi -->
        <div class="account-tab">
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
              Cập nhật thông tin thành công!
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['password_success'])): ?>
            <div class="alert alert-success">
              Đổi mật khẩu thành công!
            </div>
          <?php endif; ?>

          <form action="<?= BASE_URL ?>?act=profile-update" method="POST" class="profile-form">
            <div class="form-row">
              <div class="form-group">
                <label for="last_name">Họ <span class="required">*</span></label>
                <input type="text" 
                       id="last_name" 
                       name="last_name" 
                       class="form-control" 
                       value="<?= htmlspecialchars($lastName) ?>" 
                       required>
              </div>
              
              <div class="form-group">
                <label for="first_name">Tên <span class="required">*</span></label>
                <input type="text" 
                       id="first_name" 
                       name="first_name" 
                       class="form-control" 
                       value="<?= htmlspecialchars($firstName) ?>" 
                       required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="phone">Số điện thoại <span class="required">*</span></label>
                <input type="text" 
                       id="phone" 
                       name="phone" 
                       class="form-control" 
                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                       required>
              </div>
              
              <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" 
                       id="address" 
                       name="address" 
                       class="form-control" 
                       value="<?= htmlspecialchars($user['address'] ?? '') ?>" 
                       placeholder="Địa chỉ">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control" 
                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                       disabled 
                       placeholder="Tên đăng nhập">
              </div>
              
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                       disabled>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" class="btn btn-secondary" onclick="showChangePasswordModal()">
                Đổi mật khẩu
              </button>
              <button type="submit" class="btn btn-primary">
                Lưu thông tin
              </button>
            </div>
          </form>
        </div>

      <?php elseif ($tab === 'membership'): ?>
        <!-- Tab: Thông tin thẻ thành viên -->
        <div class="membership-tab">
          <div class="membership-card">
            <div class="card-header">
              <h3>Thẻ thành viên</h3>
              <?php if ($tier): ?>
                <span class="tier-badge tier-<?= strtolower($tier['name']) ?>">
                  <?= htmlspecialchars($tier['name']) ?>
                </span>
              <?php else: ?>
                <span class="tier-badge tier-bronze">Thành viên</span>
              <?php endif; ?>
            </div>
            
            <div class="card-body">
              <div class="membership-info">
                <div class="info-item">
                  <span class="label">Họ tên:</span>
                  <span class="value"><?= htmlspecialchars($user['full_name'] ?? '') ?></span>
                </div>
                <div class="info-item">
                  <span class="label">Email:</span>
                  <span class="value"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                </div>
                <div class="info-item">
                  <span class="label">Tổng chi tiêu:</span>
                  <span class="value"><?= number_format($user['total_spending'] ?? 0, 0, ',', '.') ?> đ</span>
                </div>
                <div class="info-item">
                  <span class="label">Điểm thưởng:</span>
                  <span class="value points"><?= number_format($rewardPoints, 0, ',', '.') ?> điểm</span>
                </div>
              </div>

              <?php if ($nextTier): ?>
                <div class="next-tier-info">
                  <p>Để lên hạng <strong><?= htmlspecialchars($nextTier['name']) ?></strong>, bạn cần chi tiêu thêm:</p>
                  <p class="amount-needed"><?= number_format($pointsToNextTier * 1000, 0, ',', '.') ?> đ</p>
                  <p class="points-needed">(<?= number_format($pointsToNextTier, 0, ',', '.') ?> điểm)</p>
                </div>
              <?php endif; ?>

              <div class="tiers-list">
                <h4>Các hạng thành viên</h4>
                <?php foreach ($allTiers as $t): ?>
                  <div class="tier-item <?= ($tier && $t['id'] == $tier['id']) ? 'current' : '' ?>">
                    <span class="tier-name"><?= htmlspecialchars($t['name']) ?></span>
                    <span class="tier-requirement">
                      Từ <?= number_format($t['spending_min'], 0, ',', '.') ?> đ
                      <?php if ($t['spending_max']): ?>
                        đến <?= number_format($t['spending_max'], 0, ',', '.') ?> đ
                      <?php else: ?>
                        trở lên
                      <?php endif; ?>
                    </span>
                    <?php if ($t['discount_percent'] > 0): ?>
                      <span class="tier-discount">Giảm <?= $t['discount_percent'] ?>%</span>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>

      <?php elseif ($tab === 'bookings'): ?>
        <!-- Tab: Lịch sử đặt vé -->
        <div class="bookings-tab">
          <h2 class="bookings-title">
            <span class="dot" aria-hidden="true"></span> Lịch sử đặt vé
          </h2>
          
          <?php if (!empty($bookingHistory)): ?>
            <div class="bookings-table-wrapper">
              <table class="bookings-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Mã đặt vé</th>
                    <th>Phim</th>
                    <th>Rạp/Phòng chiếu</th>
                    <th>Ngày chiếu</th>
                    <th>Giờ chiếu</th>
                    <th>Ghế</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Bình luận/Đánh giá</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($bookingHistory as $index => $booking): ?>
                    <?php
                    // Format dữ liệu
                    $movieTitle = htmlspecialchars($booking['movie_title'] ?? 'N/A');
                    $bookingCode = htmlspecialchars($booking['booking_code'] ?? 'N/A');
                    $movieImage = !empty($booking['movie_image']) ? BASE_URL . '/' . $booking['movie_image'] : BASE_URL . '/image/logo.png';
                    $cinemaName = htmlspecialchars($booking['cinema_name'] ?? 'N/A');
                    $roomName = htmlspecialchars($booking['room_name'] ?? 'N/A');
                    $roomCode = htmlspecialchars($booking['room_code'] ?? '');
                    $showDate = $booking['show_date'] ? date('d/m/Y', strtotime($booking['show_date'])) : 'N/A';
                    $startTime = $booking['start_time'] ? date('H:i', strtotime($booking['start_time'])) : 'N/A';
                    $bookedSeats = htmlspecialchars($booking['booked_seats'] ?? 'N/A');
                    $bookingDate = $booking['booking_date'] ? date('d/m/Y', strtotime($booking['booking_date'])) : 'N/A';
                    $bookingTime = $booking['booking_date'] ? date('H:i', strtotime($booking['booking_date'])) : '';
                    $totalAmount = isset($booking['final_amount']) && $booking['final_amount'] ? number_format($booking['final_amount'], 0, ',', '.') : '0';
                    
                    // Status badge
                    $statusClass = 'pending';
                    $statusText = 'Chờ xử lý';
                    switch($booking['status'] ?? '') {
                      case 'paid':
                        $statusClass = 'paid';
                        $statusText = 'Đã thanh toán';
                        break;
                      case 'confirmed':
                        $statusClass = 'confirmed';
                        $statusText = 'Đã xác nhận';
                        break;
                      case 'cancelled':
                        $statusClass = 'cancelled';
                        $statusText = 'Đã hủy';
                        break;
                    }
                    
                    // Kiểm tra xem đã bình luận chưa
                    $movieId = $booking['movie_id'] ?? null;
                    $hasCommented = $booking['has_commented'] ?? false;
                    ?>
                    <tr>
                      <td><?= $booking['id'] ?? ($index + 1) ?></td>
                      <td><strong class="booking-code-cell"><?= $bookingCode ?></strong></td>
                      <td>
                        <div class="movie-cell">
                          <img src="<?= $movieImage ?>" alt="<?= $movieTitle ?>" class="movie-poster-small" />
                          <span class="movie-title-cell"><?= strtoupper($movieTitle) ?></span>
                        </div>
                      </td>
                      <td>
                        <div class="cinema-cell">
                          <span class="cinema-name"><?= $cinemaName ?></span>
                          <span class="room-name"><?= $roomName ?><?= $roomCode ? ' (' . $roomCode . ')' : '' ?></span>
                        </div>
                      </td>
                      <td><?= $showDate ?></td>
                      <td><?= $startTime ?></td>
                      <td class="seats-cell"><?= $bookedSeats ?></td>
                      <td><strong class="total-amount-cell"><?= $totalAmount ?> đ</strong></td>
                      <td>
                        <span class="booking-status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                      </td>
                      <td>
                        <div class="booking-date-cell">
                          <span><?= $bookingDate ?></span>
                          <?php if ($bookingTime): ?>
                            <span class="booking-time"><?= $bookingTime ?></span>
                          <?php endif; ?>
                        </div>
                      </td>
                      <td>
                        <?php if ($movieId && ($statusClass === 'paid' || $statusClass === 'confirmed')): ?>
                          <?php if ($hasCommented): ?>
                            <span class="review-status reviewed">
                              <i class="bi bi-check-circle"></i> Đã đánh giá
                            </span>
                          <?php else: ?>
                            <a href="<?= BASE_URL ?>?act=review-movie&booking_id=<?= $booking['id'] ?>&movie_id=<?= $movieId ?>" 
                               class="review-btn">
                              <i class="bi bi-star"></i> Đánh giá
                            </a>
                          <?php endif; ?>
                        <?php else: ?>
                          <span class="review-status disabled">-</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <i class="bi bi-ticket-perforated" style="font-size: 48px; margin-bottom: 16px; display: block; color: rgba(255, 255, 255, 0.3);"></i>
              <p>Bạn chưa có lịch sử đặt vé nào.</p>
            </div>
          <?php endif; ?>
        </div>

      <?php elseif ($tab === 'rewards'): ?>
        <!-- Tab: Lịch sử điểm thưởng -->
        <div class="rewards-tab">
          <div class="rewards-summary">
            <div class="summary-card">
              <h3>Tổng điểm hiện tại</h3>
              <p class="total-points"><?= number_format($rewardPoints, 0, ',', '.') ?> điểm</p>
            </div>
            <?php if ($nextTier): ?>
              <div class="summary-card">
                <h3>Điểm cần để lên hạng</h3>
                <p class="points-needed"><?= number_format($pointsToNextTier, 0, ',', '.') ?> điểm</p>
                <p class="tier-name">Hạng <?= htmlspecialchars($nextTier['name']) ?></p>
              </div>
            <?php endif; ?>
          </div>

          <?php if (!empty($rewardHistory)): ?>
            <div class="rewards-list">
              <h3>Lịch sử tích điểm</h3>
              <table class="rewards-table">
                <thead>
                  <tr>
                    <th>Ngày</th>
                    <th>Mã đặt vé</th>
                    <th>Phim</th>
                    <th>Số tiền</th>
                    <th>Điểm tích</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rewardHistory as $reward): ?>
                    <tr>
                      <td><?= $reward['booking_date'] ? date('d/m/Y H:i', strtotime($reward['booking_date'])) : 'N/A' ?></td>
                      <td><?= htmlspecialchars($reward['booking_code'] ?? 'N/A') ?></td>
                      <td><?= htmlspecialchars($reward['movie_title'] ?? 'N/A') ?></td>
                      <td><?= isset($reward['final_amount']) && $reward['final_amount'] ? number_format($reward['final_amount'], 0, ',', '.') . ' đ' : '0 đ' ?></td>
                      <td class="points-earned">+<?= number_format($reward['points_earned'] ?? 0, 0, ',', '.') ?> điểm</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <p>Bạn chưa có lịch sử tích điểm nào.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal đổi mật khẩu -->
<div id="changePasswordModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeChangePasswordModal()">&times;</span>
    <h2>Đổi mật khẩu</h2>
    <form action="<?= BASE_URL ?>?act=profile-change-password" method="POST">
      <div class="form-group">
        <label for="current_password">Mật khẩu hiện tại <span class="required">*</span></label>
        <input type="password" id="current_password" name="current_password" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="new_password">Mật khẩu mới <span class="required">*</span></label>
        <input type="password" id="new_password" name="new_password" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">Xác nhận mật khẩu mới <span class="required">*</span></label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-secondary" onclick="closeChangePasswordModal()">Hủy</button>
        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
      </div>
    </form>
  </div>
</div>

<script>
function showChangePasswordModal() {
  document.getElementById('changePasswordModal').style.display = 'block';
}

function closeChangePasswordModal() {
  document.getElementById('changePasswordModal').style.display = 'none';
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
  const modal = document.getElementById('changePasswordModal');
  if (event.target == modal) {
    modal.style.display = 'none';
  }
}

// Tự động gộp họ và tên khi submit
document.querySelector('.profile-form').addEventListener('submit', function(e) {
  const lastName = document.getElementById('last_name').value.trim();
  const firstName = document.getElementById('first_name').value.trim();
  const fullName = (lastName + ' ' + firstName).trim();
  
  // Tạo input ẩn để gửi full_name
  const hiddenInput = document.createElement('input');
  hiddenInput.type = 'hidden';
  hiddenInput.name = 'full_name';
  hiddenInput.value = fullName;
  this.appendChild(hiddenInput);
});
</script>

