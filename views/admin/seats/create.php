<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Thêm ghế mới</h4>
      <a href="<?= BASE_URL ?>?act=seats<?= isset($_GET['room_id']) ? '&room_id=' . $_GET['room_id'] : '' ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Vui lòng kiểm tra lại các trường sau:</strong>
          <ul class="mb-0 mt-2">
            <?php foreach ($errors as $field => $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form action="" method="post" id="seatForm" onsubmit="return validateSeatForm(event)">
        <div class="row">
          <div class="col-md-8">
            <div class="mb-3">
              <label for="room_id" class="form-label">Phòng <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select <?= !empty($errors['room_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Chọn phòng --</option>
                <?php if (!empty($rooms)): ?>
                  <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['id'] ?>" <?= (isset($_GET['room_id']) && $_GET['room_id'] == $room['id']) || (isset($_POST['room_id']) && $_POST['room_id'] == $room['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (!empty($errors['room_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['room_id'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="row_label" class="form-label">Nhãn hàng (A-Z) <span class="text-danger">*</span></label>
              <input type="text" 
                     name="row_label" 
                     id="row_label" 
                     class="form-control <?= !empty($errors['row_label']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['row_label'] ?? '') ?>" 
                     required
                     maxlength="1"
                     placeholder="VD: A, B, C...">
              <small class="text-muted">Nhập 1 ký tự chữ cái (A-Z)</small>
              <?php if (!empty($errors['row_label'])): ?>
                <div class="text-danger small mt-1"><?= $errors['row_label'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="seat_number" class="form-label">Số ghế <span class="text-danger">*</span></label>
              <input type="number" 
                     name="seat_number" 
                     id="seat_number" 
                     class="form-control <?= !empty($errors['seat_number']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['seat_number'] ?? '') ?>" 
                     min="1"
                     required
                     placeholder="VD: 1, 2, 3...">
              <?php if (!empty($errors['seat_number'])): ?>
                <div class="text-danger small mt-1"><?= $errors['seat_number'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="seat_type" class="form-label">Loại ghế</label>
              <select name="seat_type" id="seat_type" class="form-select">
                <option value="normal" <?= (isset($_POST['seat_type']) && $_POST['seat_type'] == 'normal') ? 'selected' : '' ?>>Thường</option>
                <option value="vip" <?= (isset($_POST['seat_type']) && $_POST['seat_type'] == 'vip') ? 'selected' : '' ?>>VIP</option>
                <option value="couple" <?= (isset($_POST['seat_type']) && $_POST['seat_type'] == 'couple') ? 'selected' : '' ?>>Đôi</option>
                <option value="disabled" <?= (isset($_POST['seat_type']) && $_POST['seat_type'] == 'disabled') ? 'selected' : '' ?>>Khuyết tật</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="extra_price" class="form-label">Phụ thu (VNĐ)</label>
              <input type="number" 
                     name="extra_price" 
                     id="extra_price" 
                     class="form-control" 
                     value="<?= htmlspecialchars($_POST['extra_price'] ?? '0') ?>" 
                     min="0"
                     step="1000"
                     placeholder="VD: 20000">
              <small class="text-muted">Giá phụ thu cho loại ghế này (0 nếu không có)</small>
            </div>

            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select name="status" id="status" class="form-select">
                <option value="available" <?= (isset($_POST['status']) && $_POST['status'] == 'available') ? 'selected' : '' ?>>Có sẵn</option>
                <option value="booked" <?= (isset($_POST['status']) && $_POST['status'] == 'booked') ? 'selected' : '' ?>>Đã đặt</option>
                <option value="maintenance" <?= (isset($_POST['status']) && $_POST['status'] == 'maintenance') ? 'selected' : '' ?>>Bảo trì</option>
                <option value="reserved" <?= (isset($_POST['status']) && $_POST['status'] == 'reserved') ? 'selected' : '' ?>>Giữ chỗ</option>
              </select>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Thêm ghế
          </button>
          <a href="<?= BASE_URL ?>?act=seats<?= isset($_GET['room_id']) ? '&room_id=' . $_GET['room_id'] : '' ?>" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function validateSeatForm(event) {
    const roomId = document.getElementById('room_id').value;
    const rowLabel = document.getElementById('row_label').value.trim().toUpperCase();
    const seatNumber = document.getElementById('seat_number').value;

    if (!roomId || roomId === '') {
      alert('Vui lòng chọn phòng!');
      document.getElementById('room_id').focus();
      return false;
    }

    if (!rowLabel || rowLabel === '') {
      alert('Vui lòng nhập nhãn hàng!');
      document.getElementById('row_label').focus();
      return false;
    }

    // Validate row label is a single letter A-Z
    if (!/^[A-Z]$/.test(rowLabel)) {
      alert('Nhãn hàng phải là 1 chữ cái từ A-Z!');
      document.getElementById('row_label').focus();
      return false;
    }

    if (!seatNumber || seatNumber === '' || parseInt(seatNumber) <= 0) {
      alert('Vui lòng nhập số ghế hợp lệ!');
      document.getElementById('seat_number').focus();
      return false;
    }

    return true;
  }
</script>

<script>
  // Validation form với alert
  document.getElementById('seatForm').addEventListener('submit', function(e) {
    const room_id = document.getElementById('room_id').value;
    const row_label = document.getElementById('row_label').value.trim().toUpperCase();
    const seat_number = document.getElementById('seat_number').value.trim();

    let errors = [];

    if (!room_id) {
      errors.push('Vui lòng chọn phòng');
    }

    if (!row_label || row_label.length !== 1 || !/^[A-Z]$/.test(row_label)) {
      errors.push('Nhãn hàng phải là 1 ký tự chữ cái (A-Z)');
    }

    if (!seat_number || seat_number <= 0) {
      errors.push('Số ghế phải lớn hơn 0');
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert('Vui lòng kiểm tra lại các trường sau:\n\n' + errors.join('\n'));
      return false;
    }

    // Tự động chuyển row_label thành chữ hoa
    document.getElementById('row_label').value = row_label;
  });

  // Tự động chuyển row_label thành chữ hoa khi nhập
  document.getElementById('row_label').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
  });
</script>

