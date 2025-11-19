<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý đặt vé</h4>
    </div>
    <div class="card-body">
      <!-- Filter form -->
      <div class="mb-3">
        <form method="get" action="" class="row g-3">
          <input type="hidden" name="act" value="bookings">
          <div class="col-md-3">
            <label for="status" class="form-label">Lọc theo trạng thái:</label>
            <select name="status" id="status" class="form-select">
              <option value="">Tất cả</option>
              <option value="pending" <?= ($selectedStatus ?? '') === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
              <option value="confirmed" <?= ($selectedStatus ?? '') === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
              <option value="paid" <?= ($selectedStatus ?? '') === 'paid' ? 'selected' : '' ?>>Đã thanh toán</option>
              <option value="cancelled" <?= ($selectedStatus ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="date" class="form-label">Lọc theo ngày đặt:</label>
            <input type="date" name="date" id="date" class="form-control" value="<?= htmlspecialchars($selectedDate ?? '') ?>">
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary me-2">
              <i class="bi bi-funnel"></i> Lọc
            </button>
            <a href="<?= BASE_URL ?>?act=bookings" class="btn btn-outline-secondary">
              <i class="bi bi-x-circle"></i> Xóa bộ lọc
            </a>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã đặt vé</th>
              <th>Khách hàng</th>
              <th>Phim</th>
              <th>Rạp/Phòng</th>
              <th>Ngày chiếu</th>
              <th>Giờ chiếu</th>
              <th>Ghế</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th>Ngày đặt</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><strong><?= htmlspecialchars($item['booking_code'] ?? 'N/A') ?></strong></td>
                <td>
                  <div>
                    <strong><?= htmlspecialchars($item['user_name'] ?? 'N/A') ?></strong>
                    <?php if (!empty($item['user_email'])): ?>
                      <br><small class="text-muted"><?= htmlspecialchars($item['user_email']) ?></small>
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <?php if (!empty($item['movie_image'])): ?>
                      <img src="<?= BASE_URL . '/' . $item['movie_image'] ?>" 
                           alt="<?= htmlspecialchars($item['movie_title'] ?? '') ?>" 
                           style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 8px;">
                    <?php endif; ?>
                    <div>
                      <strong><?= htmlspecialchars($item['movie_title'] ?? 'N/A') ?></strong>
                    </div>
                  </div>
                </td>
                <td>
                  <?= htmlspecialchars($item['cinema_name'] ?? 'N/A') ?>
                  <br><small class="text-muted"><?= htmlspecialchars($item['room_name'] ?? 'N/A') ?> (<?= htmlspecialchars($item['room_code'] ?? '') ?>)</small>
                </td>
                <td><?= $item['show_date'] ? date('d/m/Y', strtotime($item['show_date'])) : 'N/A' ?></td>
                <td><?= $item['start_time'] ? date('H:i', strtotime($item['start_time'])) : 'N/A' ?></td>
                <td><?= htmlspecialchars($item['booked_seats'] ?? 'N/A') ?></td>
                <td><strong><?= $item['final_amount'] ? number_format($item['final_amount'], 0, ',', '.') . ' đ' : 'N/A' ?></strong></td>
                <td>
                  <?php
                  $statusClass = 'secondary';
                  $statusText = 'N/A';
                  switch($item['status'] ?? '') {
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
                  <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                </td>
                <td><?= $item['booking_date'] ? date('d/m/Y H:i', strtotime($item['booking_date'])) : 'N/A' ?></td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=bookings-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-warning" title="Cập nhật trạng thái" onclick="showStatusModal(<?= $item['id'] ?>, '<?= htmlspecialchars($item['status'] ?? '') ?>')">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="<?= BASE_URL ?>?act=bookings-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa đặt vé này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="12" class="text-center">Không có dữ liệu</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
            <?php if ($pagination['currentPage'] > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?act=bookings&page=<?= $pagination['currentPage'] - 1 ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?><?= $selectedDate ? '&date=' . urlencode($selectedDate) : '' ?>">Trước</a>
              </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
              <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                <a class="page-link" href="?act=bookings&page=<?= $i ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?><?= $selectedDate ? '&date=' . urlencode($selectedDate) : '' ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            
            <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
              <li class="page-item">
                <a class="page-link" href="?act=bookings&page=<?= $pagination['currentPage'] + 1 ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?><?= $selectedDate ? '&date=' . urlencode($selectedDate) : '' ?>">Sau</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cập nhật trạng thái đặt vé</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="statusForm">
        <div class="modal-body">
          <input type="hidden" name="id" id="bookingId">
          <div class="mb-3">
            <label for="statusSelect" class="form-label">Trạng thái:</label>
            <select name="status" id="statusSelect" class="form-select" required>
              <option value="pending">Chờ xử lý</option>
              <option value="confirmed">Đã xác nhận</option>
              <option value="paid">Đã thanh toán</option>
              <option value="cancelled">Đã hủy</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function showStatusModal(id, currentStatus) {
  document.getElementById('bookingId').value = id;
  document.getElementById('statusSelect').value = currentStatus;
  new bootstrap.Modal(document.getElementById('statusModal')).show();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  
  fetch('<?= BASE_URL ?>?act=bookings-update-status', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      location.reload();
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Có lỗi xảy ra');
  });
});
</script>

