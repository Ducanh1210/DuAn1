<?php
// SEATS/SHOW.PHP - TRANG CHI TIẾT GHẾ ADMIN
// Chức năng: Hiển thị thông tin chi tiết của một ghế (ID, rạp, phòng, vị trí, loại ghế, phụ thu, trạng thái)
// Biến từ controller: $seat (thông tin ghế cần hiển thị)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với vị trí ghế và các nút thao tác -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết ghế: <?= htmlspecialchars($seat['row_label'] ?? '') ?><?= $seat['seat_number'] ?? '' ?></h4>
      <div>
        <!-- Nút sửa ghế -->
        <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $seat['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa
        </a>
        <!-- Link quay lại danh sách ghế, có room_id nếu có -->
        <a href="<?= BASE_URL ?>?act=seats<?= isset($seat['room_id']) ? '&room_id=' . $seat['room_id'] : '' ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <!-- Hiển thị ID ghế -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">ID</label>
            <p class="mb-0"><?= $seat['id'] ?></p>
          </div>

          <!-- Hiển thị tên rạp: $seat['cinema_name'] từ JOIN với bảng cinemas -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Rạp</label>
            <p class="mb-0"><strong><?= htmlspecialchars($seat['cinema_name'] ?? 'N/A') ?></strong></p>
          </div>

          <!-- Hiển thị tên phòng và mã phòng -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Phòng</label>
            <p class="mb-0">
              <strong><?= htmlspecialchars($seat['room_name'] ?? 'N/A') ?></strong>
              <!-- Hiển thị mã phòng nếu có -->
              <?php if (!empty($seat['room_code'])): ?>
                <br><small class="text-muted">(<?= htmlspecialchars($seat['room_code']) ?>)</small>
              <?php endif; ?>
            </p>
          </div>

          <!-- Hiển thị vị trí ghế: row_label + seat_number (VD: A1, B5) -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Vị trí ghế</label>
            <p class="mb-0">
              <span class="badge bg-primary fs-6"><?= htmlspecialchars($seat['row_label'] ?? '') ?><?= $seat['seat_number'] ?? '' ?></span>
            </p>
          </div>

          <!-- Hiển thị loại ghế: normal (Thường) hoặc vip (VIP) với badge màu -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Loại ghế</label>
            <p class="mb-0">
              <?php
              // Mảng ánh xạ loại ghế sang nhãn tiếng Việt
              $seatTypeLabels = [
                'normal' => 'Thường',
                'vip' => 'VIP'
              ];
              // Mảng ánh xạ loại ghế sang class màu Bootstrap
              $seatTypeClass = [
                'normal' => 'secondary',
                'vip' => 'warning'
              ];
              // Lấy loại ghế hiện tại, mặc định là 'normal'
              $type = $seat['seat_type'] ?? 'normal';
              // Validate: nếu loại ghế không hợp lệ, mặc định là 'normal'
              if (!isset($seatTypeLabels[$type])) {
                $type = 'normal';
              }
              ?>
              <!-- Badge hiển thị loại ghế với màu tương ứng -->
              <span class="badge bg-<?= $seatTypeClass[$type] ?? 'secondary' ?>">
                <?= $seatTypeLabels[$type] ?? 'Thường' ?>
              </span>
            </p>
          </div>

          <!-- Hiển thị phụ thu: format số với dấu phẩy ngăn cách hàng nghìn -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Phụ thu</label>
            <p class="mb-0">
              <strong class="text-success"><?= number_format($seat['extra_price'] ?? 0, 0, ',', '.') ?> đ</strong>
            </p>
          </div>

          <!-- Hiển thị trạng thái: available, booked, maintenance, reserved với badge màu -->
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Trạng thái</label>
            <p class="mb-0">
              <?php
              // Mảng ánh xạ trạng thái sang nhãn tiếng Việt
              $statusLabels = [
                'available' => 'Có sẵn',
                'booked' => 'Đã đặt',
                'maintenance' => 'Bảo trì',
                'reserved' => 'Giữ chỗ'
              ];
              // Mảng ánh xạ trạng thái sang class màu Bootstrap
              $statusClass = [
                'available' => 'success',
                'booked' => 'danger',
                'maintenance' => 'warning',
                'reserved' => 'info'
              ];
              // Lấy trạng thái hiện tại, mặc định là 'available'
              $status = $seat['status'] ?? 'available';
              // Validate: nếu trạng thái không hợp lệ, mặc định là 'available'
              if (!isset($statusLabels[$status])) {
                $status = 'available';
              }
              ?>
              <!-- Badge hiển thị trạng thái với màu tương ứng -->
              <span class="badge bg-<?= $statusClass[$status] ?? 'success' ?>">
                <?= $statusLabels[$status] ?? 'Có sẵn' ?>
              </span>
            </p>
          </div>
        </div>
      </div>

      <!-- Các nút thao tác: sửa, xóa, xem sơ đồ, quay lại -->
      <div class="mt-4">
        <!-- Nút sửa ghế -->
        <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $seat['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa ghế
        </a>
        <!-- Nút xóa ghế: có confirm dialog để xác nhận -->
        <a href="<?= BASE_URL ?>?act=seats-delete&id=<?= $seat['id'] ?>&room_id=<?= $seat['room_id'] ?>" 
           class="btn btn-danger"
           onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?')">
          <i class="bi bi-trash"></i> Xóa ghế
        </a>
        <!-- Link xem sơ đồ ghế của phòng -->
        <a href="<?= BASE_URL ?>?act=seats-seatmap&room_id=<?= $seat['room_id'] ?>" class="btn btn-info">
          <i class="bi bi-grid"></i> Xem sơ đồ ghế
        </a>
        <!-- Link quay lại danh sách ghế, có room_id nếu có -->
        <a href="<?= BASE_URL ?>?act=seats<?= isset($seat['room_id']) ? '&room_id=' . $seat['room_id'] : '' ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
      </div>
    </div>
  </div>
</div>

