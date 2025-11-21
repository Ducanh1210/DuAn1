<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý ghế</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=seats-generate" class="btn btn-success me-2">
          <i class="bi bi-grid-3x3-gap"></i> Tạo sơ đồ ghế tự động
        </a>
        <a href="<?= BASE_URL ?>?act=seats-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm ghế mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- Filter by room -->
      <form method="GET" class="mb-3">
        <input type="hidden" name="act" value="seats">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Lọc theo phòng:</label>
            <select name="room_id" class="form-select" onchange="this.form.submit()">
              <option value="">Tất cả phòng</option>
              <?php foreach ($rooms as $room): ?>
                <option value="<?= $room['id'] ?>" <?= $selectedRoomId == $room['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if ($selectedRoomId): ?>
            <div class="col-md-4 d-flex align-items-end">
              <a href="<?= BASE_URL ?>?act=seats-seatmap&room_id=<?= $selectedRoomId ?>" class="btn btn-info">
                <i class="bi bi-grid"></i> Xem sơ đồ ghế
              </a>
            </div>
          <?php endif; ?>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Rạp</th>
              <th>Phòng</th>
              <th>Vị trí ghế</th>
              <th>Loại ghế</th>
              <th>Phụ thu</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['cinema_name'] ?? 'N/A') ?></td>
                <td>
                  <strong><?= htmlspecialchars($item['room_name'] ?? 'N/A') ?></strong>
                  <br><small class="text-muted"><?= htmlspecialchars($item['room_code'] ?? '') ?></small>
                </td>
                <td>
                  <span class="badge bg-primary"><?= htmlspecialchars($item['row_label'] ?? '') ?><?= $item['seat_number'] ?? '' ?></span>
                </td>
                <td>
                  <?php
                  $seatTypeLabels = [
                    'normal' => 'Thường',
                    'vip' => 'VIP'
                  ];
                  $seatTypeClass = [
                    'normal' => 'secondary',
                    'vip' => 'warning'
                  ];
                  $type = $item['seat_type'] ?? 'normal';
                  // Nếu là loại ghế không hợp lệ, hiển thị là "Thường"
                  if (!isset($seatTypeLabels[$type])) {
                    $type = 'normal';
                  }
                  ?>
                  <span class="badge bg-<?= $seatTypeClass[$type] ?? 'secondary' ?>">
                    <?= $seatTypeLabels[$type] ?? 'Thường' ?>
                  </span>
                </td>
                <td><?= number_format($item['extra_price'] ?? 0, 0, ',', '.') ?> đ</td>
                <td>
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
                  $status = $item['status'] ?? 'available';
                  // Nếu trạng thái không hợp lệ, mặc định là available
                  if (!isset($statusLabels[$status])) {
                    $status = 'available';
                  }
                  ?>
                  <span class="badge bg-<?= $statusClass[$status] ?? 'success' ?>">
                    <?= $statusLabels[$status] ?? 'Có sẵn' ?>
                  </span>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=seats-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=seats-delete&id=<?= $item['id'] ?>&room_id=<?= $item['room_id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa ghế này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">Chưa có ghế nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị <?= count($data) ?> / <?= $pagination['total'] ?> ghế (Trang <?= $pagination['currentPage'] ?> / <?= $pagination['totalPages'] ?>)
          </div>
          
          <nav aria-label="Phân trang">
            <ul class="pagination mb-0">
              <!-- Previous -->
              <?php if ($pagination['currentPage'] > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=seats&page=<?= $pagination['currentPage'] - 1 ?><?= $selectedRoomId ? '&room_id=' . $selectedRoomId : '' ?>">
                    <i class="bi bi-chevron-left"></i> Trước
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <span class="page-link"><i class="bi bi-chevron-left"></i> Trước</span>
                </li>
              <?php endif; ?>
              
              <!-- Page numbers -->
              <?php
              $startPage = max(1, $pagination['currentPage'] - 2);
              $endPage = min($pagination['totalPages'], $pagination['currentPage'] + 2);
              
              if ($startPage > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=seats&page=1<?= $selectedRoomId ? '&room_id=' . $selectedRoomId : '' ?>">1</a>
                </li>
                <?php if ($startPage > 2): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
              
              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                  <a class="page-link" href="<?= BASE_URL ?>?act=seats&page=<?= $i ?><?= $selectedRoomId ? '&room_id=' . $selectedRoomId : '' ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              
              <?php if ($endPage < $pagination['totalPages']): ?>
                <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=seats&page=<?= $pagination['totalPages'] ?><?= $selectedRoomId ? '&room_id=' . $selectedRoomId : '' ?>"><?= $pagination['totalPages'] ?></a>
                </li>
              <?php endif; ?>
              
              <!-- Next -->
              <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=seats&page=<?= $pagination['currentPage'] + 1 ?><?= $selectedRoomId ? '&room_id=' . $selectedRoomId : '' ?>">
                    Sau <i class="bi bi-chevron-right"></i>
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <span class="page-link">Sau <i class="bi bi-chevron-right"></i></span>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

