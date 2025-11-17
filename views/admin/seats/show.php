<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết ghế: <?= htmlspecialchars($seat['row_label'] ?? '') ?><?= $seat['seat_number'] ?? '' ?></h4>
      <div>
        <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $seat['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa
        </a>
        <a href="<?= BASE_URL ?>?act=seats<?= isset($seat['room_id']) ? '&room_id=' . $seat['room_id'] : '' ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">ID</label>
            <p class="mb-0"><?= $seat['id'] ?></p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Rạp</label>
            <p class="mb-0"><strong><?= htmlspecialchars($seat['cinema_name'] ?? 'N/A') ?></strong></p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Phòng</label>
            <p class="mb-0">
              <strong><?= htmlspecialchars($seat['room_name'] ?? 'N/A') ?></strong>
              <?php if (!empty($seat['room_code'])): ?>
                <br><small class="text-muted">(<?= htmlspecialchars($seat['room_code']) ?>)</small>
              <?php endif; ?>
            </p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Vị trí ghế</label>
            <p class="mb-0">
              <span class="badge bg-primary fs-6"><?= htmlspecialchars($seat['row_label'] ?? '') ?><?= $seat['seat_number'] ?? '' ?></span>
            </p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Loại ghế</label>
            <p class="mb-0">
              <?php
              $seatTypeLabels = [
                'normal' => 'Thường',
                'vip' => 'VIP',
                'couple' => 'Đôi',
                'disabled' => 'Khuyết tật'
              ];
              $seatTypeClass = [
                'normal' => 'secondary',
                'vip' => 'warning',
                'couple' => 'info',
                'disabled' => 'dark'
              ];
              $type = $seat['seat_type'] ?? 'normal';
              ?>
              <span class="badge bg-<?= $seatTypeClass[$type] ?? 'secondary' ?>">
                <?= $seatTypeLabels[$type] ?? ucfirst($type) ?>
              </span>
            </p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Phụ thu</label>
            <p class="mb-0">
              <strong class="text-success"><?= number_format($seat['extra_price'] ?? 0, 0, ',', '.') ?> đ</strong>
            </p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Trạng thái</label>
            <p class="mb-0">
              <?php
              $statusLabels = [
                'available' => 'Có sẵn',
                'booked' => 'Đã đặt',
                'maintenance' => 'Bảo trì',
                'reserved' => 'Giữ chỗ'
              ];
              $statusClass = [
                'available' => 'success',
                'booked' => 'danger',
                'maintenance' => 'warning',
                'reserved' => 'info'
              ];
              $status = $seat['status'] ?? 'available';
              ?>
              <span class="badge bg-<?= $statusClass[$status] ?? 'secondary' ?>">
                <?= $statusLabels[$status] ?? ucfirst($status) ?>
              </span>
            </p>
          </div>
        </div>
      </div>

      <div class="mt-4">
        <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $seat['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa ghế
        </a>
        <a href="<?= BASE_URL ?>?act=seats-delete&id=<?= $seat['id'] ?>&room_id=<?= $seat['room_id'] ?>" 
           class="btn btn-danger"
           onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?')">
          <i class="bi bi-trash"></i> Xóa ghế
        </a>
        <a href="<?= BASE_URL ?>?act=seats-seatmap&room_id=<?= $seat['room_id'] ?>" class="btn btn-info">
          <i class="bi bi-grid"></i> Xem sơ đồ ghế
        </a>
        <a href="<?= BASE_URL ?>?act=seats<?= isset($seat['room_id']) ? '&room_id=' . $seat['room_id'] : '' ?>" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
      </div>
    </div>
  </div>
</div>

