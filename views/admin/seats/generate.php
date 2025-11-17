<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h4 class="mb-0">Tạo sơ đồ ghế tự động</h4>
    </div>
    <div class="card-body">
      <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($errors['general']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>?act=seats-generate" onsubmit="return validateForm()">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Chọn phòng <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select" required>
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $room): ?>
                  <option value="<?= $room['id'] ?>" <?= (isset($_GET['room_id']) && $_GET['room_id'] == $room['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>)
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['room_id'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['room_id']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Số hàng ghế (A-Z) <span class="text-danger">*</span></label>
              <input type="number" name="rows" id="rows" class="form-control" min="1" max="26" value="10" required>
              <small class="text-muted">Tối đa 26 hàng (A đến Z)</small>
              <?php if (isset($errors['rows'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['rows']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Số ghế mỗi hàng <span class="text-danger">*</span></label>
              <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" max="50" value="15" required>
              <small class="text-muted">Tối đa 50 ghế mỗi hàng</small>
              <?php if (isset($errors['seats_per_row'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['seats_per_row']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Loại ghế</label>
              <select name="seat_type" id="seat_type" class="form-select">
                <option value="normal">Thường</option>
                <option value="vip">VIP</option>
                <option value="couple">Đôi</option>
                <option value="disabled">Khuyết tật</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Phụ thu (VNĐ)</label>
              <input type="number" name="extra_price" id="extra_price" class="form-control" min="0" step="1000" value="0">
              <small class="text-muted">Giá phụ thu cho loại ghế này (0 nếu không có)</small>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="clear_existing" id="clear_existing" value="1">
                <label class="form-check-label" for="clear_existing">
                  Xóa tất cả ghế hiện có trong phòng trước khi tạo mới
                </label>
                <small class="text-muted d-block">Lưu ý: Hành động này không thể hoàn tác!</small>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Tạo sơ đồ ghế
              </button>
              <a href="<?= BASE_URL ?>?act=seats" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
              </a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="alert alert-info">
              <h6><i class="bi bi-info-circle"></i> Hướng dẫn:</h6>
              <ul class="mb-0">
                <li>Chọn phòng chiếu cần tạo sơ đồ ghế</li>
                <li>Nhập số hàng ghế (ví dụ: 10 hàng = A, B, C... J)</li>
                <li>Nhập số ghế mỗi hàng (ví dụ: 15 ghế = 1, 2, 3... 15)</li>
                <li>Chọn loại ghế mặc định cho tất cả ghế</li>
                <li>Nhập phụ thu nếu có (ví dụ: VIP +20,000đ)</li>
                <li>Nếu phòng đã có ghế, bạn có thể chọn xóa ghế cũ trước khi tạo mới</li>
              </ul>
            </div>
            <div class="alert alert-warning">
              <strong>Lưu ý:</strong> Sau khi tạo, bạn có thể chỉnh sửa từng ghế riêng lẻ nếu cần thay đổi loại ghế hoặc trạng thái.
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function validateForm() {
  const roomId = document.getElementById('room_id').value;
  const rows = parseInt(document.getElementById('rows').value);
  const seatsPerRow = parseInt(document.getElementById('seats_per_row').value);
  const clearExisting = document.getElementById('clear_existing').checked;

  if (!roomId) {
    alert('Vui lòng chọn phòng');
    return false;
  }

  if (rows <= 0 || rows > 26) {
    alert('Số hàng phải từ 1 đến 26');
    return false;
  }

  if (seatsPerRow <= 0 || seatsPerRow > 50) {
    alert('Số ghế mỗi hàng phải từ 1 đến 50');
    return false;
  }

  if (clearExisting) {
    if (!confirm('Bạn có chắc chắn muốn xóa tất cả ghế hiện có? Hành động này không thể hoàn tác!')) {
      return false;
    }
  }

  const totalSeats = rows * seatsPerRow;
  if (!confirm(`Bạn sẽ tạo ${totalSeats} ghế (${rows} hàng x ${seatsPerRow} ghế). Tiếp tục?`)) {
    return false;
  }

  return true;
}
</script>

