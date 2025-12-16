<?php
// BOOKINGS/SHOW.PHP - TRANG CHI TIẾT ĐẶT VÉ ADMIN
// Chức năng: Hiển thị thông tin chi tiết của một đặt vé (thông tin vé, khách hàng, phim, suất chiếu, thanh toán)
// Biến từ controller: $booking (thông tin đặt vé), $bookingItems (đồ ăn/nước), $payment (thông tin thanh toán)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với ID đặt vé và nút quay lại -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết đặt vé #<?= $booking['id'] ?></h4>
      <!-- Link quay lại danh sách đặt vé -->
      <a href="<?= BASE_URL ?>?act=bookings" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Cột trái: Thông tin đặt vé -->
        <div class="col-md-6">
          <h5>Thông tin đặt vé</h5>
          <table class="table table-bordered">
            <!-- Mã đặt vé: $booking['booking_code'] từ database -->
            <tr>
              <th width="40%">Mã đặt vé:</th>
              <td><strong><?= htmlspecialchars($booking['booking_code'] ?? 'N/A') ?></strong></td>
            </tr>
            <!-- Trạng thái đặt vé: switch case để xác định class và text hiển thị -->
            <tr>
              <th>Trạng thái:</th>
              <td>
                <?php
                // Khởi tạo biến mặc định
                $statusClass = 'secondary'; // Class màu badge mặc định
                $statusText = 'N/A'; // Text hiển thị mặc định
                // Switch case: kiểm tra trạng thái và gán class/text tương ứng
                switch($booking['status'] ?? '') {
                  case 'pending': // Chờ xử lý
                    $statusClass = 'warning'; // Màu vàng
                    $statusText = 'Chờ xử lý';
                    break;
                  case 'confirmed': // Đã xác nhận
                    $statusClass = 'info'; // Màu xanh dương
                    $statusText = 'Đã xác nhận';
                    break;
                  case 'paid': // Đã thanh toán
                    $statusClass = 'success'; // Màu xanh lá
                    $statusText = 'Đã thanh toán';
                    break;
                  case 'cancelled': // Đã hủy
                    $statusClass = 'danger'; // Màu đỏ
                    $statusText = 'Đã hủy';
                    break;
                }
                ?>
                <!-- Badge hiển thị trạng thái với màu tương ứng -->
                <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
              </td>
            </tr>
            <!-- Ngày đặt vé: format từ datetime sang d/m/Y H:i:s -->
            <tr>
              <th>Ngày đặt:</th>
              <td><?= $booking['booking_date'] ? date('d/m/Y H:i:s', strtotime($booking['booking_date'])) : 'N/A' ?></td>
            </tr>
            <!-- Ghế đã đặt: danh sách ghế (ví dụ: A1, A2, B3) -->
            <tr>
              <th>Ghế đã đặt:</th>
              <td><strong><?= htmlspecialchars($booking['booked_seats'] ?? 'N/A') ?></strong></td>
            </tr>
            <tr>
              
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h5>Thông tin khách hàng</h5>
          <table class="table table-bordered">
            <tr>
              <th width="40%">Tên:</th>
              <td><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></td>
            </tr>
            <tr>
              <th>Email:</th>
              <td><?= htmlspecialchars($booking['user_email'] ?? 'N/A') ?></td>
            </tr>
            <tr>
              <th>Số điện thoại:</th>
              <td><?= htmlspecialchars($booking['user_phone'] ?? 'N/A') ?></td>
            </tr>
          </table>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-6">
          <h5>Thông tin phim</h5>
          <div class="d-flex">
            <?php if (!empty($booking['movie_image'])): ?>
              <img src="<?= BASE_URL . '/' . $booking['movie_image'] ?>" 
                   alt="<?= htmlspecialchars($booking['movie_title'] ?? '') ?>" 
                   style="width: 100px; height: 150px; object-fit: cover; border-radius: 4px; margin-right: 15px;">
            <?php endif; ?>
            <div>
              <h6><?= htmlspecialchars($booking['movie_title'] ?? 'N/A') ?></h6>
              <?php if (!empty($booking['movie_duration'])): ?>
                <p class="text-muted mb-1">Thời lượng: <?= $booking['movie_duration'] ?> phút</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h5>Thông tin suất chiếu</h5>
          <table class="table table-bordered">
            <tr>
              <th width="40%">Rạp:</th>
              <td><?= htmlspecialchars($booking['cinema_name'] ?? 'N/A') ?></td>
            </tr>
            <tr>
              <th>Phòng:</th>
              <td><?= htmlspecialchars($booking['room_name'] ?? 'N/A') ?> (<?= htmlspecialchars($booking['room_code'] ?? '') ?>)</td>
            </tr>
            <tr>
              <th>Ngày chiếu:</th>
              <td><?= $booking['show_date'] ? date('d/m/Y', strtotime($booking['show_date'])) : 'N/A' ?></td>
            </tr>
            <tr>
              <th>Giờ chiếu:</th>
              <td>
                <?= $booking['start_time'] ? date('H:i', strtotime($booking['start_time'])) : 'N/A' ?>
                <?php if (!empty($booking['end_time'])): ?>
                  - <?= date('H:i', strtotime($booking['end_time'])) ?>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <th>Loại:</th>
              <td><?= htmlspecialchars($booking['showtime_format'] ?? '2D') ?></td>
            </tr>
          </table>
        </div>
      </div>

      <?php if (!empty($bookingItems)): ?>
      <div class="row mt-4">
        <div class="col-12">
          <h5>Đồ ăn/Nước uống</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Tên</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bookingItems as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['food_name'] ?? 'N/A') ?></td>
                <td><?= $item['quantity'] ?? 0 ?></td>
                <td><?= $item['unit_price'] ? number_format($item['unit_price'], 0, ',', '.') . ' đ' : 'N/A' ?></td>
                <td><?= $item['total_price'] ? number_format($item['total_price'], 0, ',', '.') . ' đ' : 'N/A' ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif; ?>

      <div class="row mt-4">
        <div class="col-md-6 offset-md-6">
          <h5>Thông tin thanh toán</h5>
          <table class="table table-bordered">
            <tr>
              <th width="50%">Tổng tiền:</th>
              <td><?= $booking['total_amount'] ? number_format($booking['total_amount'], 0, ',', '.') . ' đ' : 'N/A' ?></td>
            </tr>
            <?php if (!empty($booking['discount_amount']) && $booking['discount_amount'] > 0): ?>
            <tr>
              <th>Giảm giá:</th>
              <td class="text-danger">-<?= number_format($booking['discount_amount'], 0, ',', '.') . ' đ' ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <th><strong>Thành tiền:</strong></th>
              <td><strong><?= $booking['final_amount'] ? number_format($booking['final_amount'], 0, ',', '.') . ' đ' : 'N/A' ?></strong></td>
            </tr>
          </table>
        </div>
      </div>

      <?php if ($payment): ?>
      <div class="row mt-4">
        <div class="col-12">
          <h5>Thông tin thanh toán</h5>
          <table class="table table-bordered">
            <tr>
              <th width="20%">Phương thức:</th>
              <td><?= htmlspecialchars($payment['method'] ?? 'N/A') ?></td>
            </tr>
            <tr>
              <th>Mã giao dịch:</th>
              <td><?= htmlspecialchars($payment['transaction_code'] ?? 'N/A') ?></td>
            </tr>
            <tr>
              <th>Ngày thanh toán:</th>
              <td><?= $payment['payment_date'] ? date('d/m/Y H:i:s', strtotime($payment['payment_date'])) : 'N/A' ?></td>
            </tr>
            <tr>
              <th>Trạng thái:</th>
              <td>
                <?php
                $paymentStatusClass = $payment['status'] === 'paid' ? 'success' : 'warning';
                $paymentStatusText = $payment['status'] === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán';
                ?>
                <span class="badge bg-<?= $paymentStatusClass ?>"><?= $paymentStatusText ?></span>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

