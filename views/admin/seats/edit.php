<?php
// SEATS/EDIT.PHP - TRANG SỬA GHẾ ADMIN
// Chức năng: Form sửa thông tin ghế (phòng, nhãn hàng, số ghế, loại ghế, phụ thu, trạng thái)
// Biến từ controller: $seat (thông tin ghế cần sửa), $rooms (danh sách phòng), $errors (lỗi validation)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với vị trí ghế và nút quay lại -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa ghế: <?= htmlspecialchars($seat['row_label'] ?? '') ?><?= $seat['seat_number'] ?? '' ?></h4>
      <!-- Link quay lại danh sách ghế, có room_id nếu có -->
      <a href="<?= BASE_URL ?>?act=seats<?= isset($seat['room_id']) ? '&room_id=' . $seat['room_id'] : '' ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <!-- Hiển thị lỗi validation nếu có: $errors từ controller -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Vui lòng kiểm tra lại các trường sau:</strong>
          <ul class="mb-0 mt-2">
            <!-- Vòng lặp: hiển thị từng lỗi -->
            <?php foreach ($errors as $field => $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Form sửa ghế: onsubmit gọi hàm validateSeatForm() để validate client-side -->
      <form action="" method="post" id="seatForm" onsubmit="return validateSeatForm(event)">
        <div class="row">
          <div class="col-md-8">
            <!-- Select phòng: bắt buộc (*), value lấy từ $_POST nếu có, nếu không thì lấy từ $seat -->
            <div class="mb-3">
              <label for="room_id" class="form-label">Phòng <span class="text-danger">*</span></label>
              <!-- is-invalid: thêm class nếu có lỗi để hiển thị border đỏ -->
              <select name="room_id" id="room_id" class="form-select <?= !empty($errors['room_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Chọn phòng --</option>
                <!-- Vòng lặp: hiển thị danh sách phòng từ $rooms -->
                <?php if (!empty($rooms)): ?>
                  <?php foreach ($rooms as $room): ?>
                    <!-- selected: đánh dấu phòng hiện tại của ghế -->
                    <option value="<?= $room['id'] ?>" <?= (isset($_POST['room_id']) ? $_POST['room_id'] : $seat['room_id']) == $room['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['room_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['room_id'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Input nhãn hàng: bắt buộc (*), maxlength=1 (chỉ 1 ký tự A-Z) -->
            <div class="mb-3">
              <label for="row_label" class="form-label">Nhãn hàng (A-Z) <span class="text-danger">*</span></label>
              <input type="text" 
                     name="row_label" 
                     id="row_label" 
                     class="form-control <?= !empty($errors['row_label']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['row_label'] ?? $seat['row_label'] ?? '') ?>" 
                     required
                     maxlength="1"
                     placeholder="VD: A, B, C...">
              <small class="text-muted">Nhập 1 ký tự chữ cái (A-Z)</small>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['row_label'])): ?>
                <div class="text-danger small mt-1"><?= $errors['row_label'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Input số ghế: bắt buộc (*), min=1, type=number -->
            <div class="mb-3">
              <label for="seat_number" class="form-label">Số ghế <span class="text-danger">*</span></label>
              <input type="number" 
                     name="seat_number" 
                     id="seat_number" 
                     class="form-control <?= !empty($errors['seat_number']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['seat_number'] ?? $seat['seat_number'] ?? '') ?>" 
                     min="1"
                     required
                     placeholder="VD: 1, 2, 3...">
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['seat_number'])): ?>
                <div class="text-danger small mt-1"><?= $errors['seat_number'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Select loại ghế: normal hoặc vip -->
            <div class="mb-3">
              <label for="seat_type" class="form-label">Loại ghế</label>
              <select name="seat_type" id="seat_type" class="form-select">
                <!-- selected: đánh dấu loại ghế hiện tại -->
                <option value="normal" <?= (isset($_POST['seat_type']) ? $_POST['seat_type'] : ($seat['seat_type'] ?? 'normal')) == 'normal' ? 'selected' : '' ?>>Thường</option>
                <option value="vip" <?= (isset($_POST['seat_type']) ? $_POST['seat_type'] : ($seat['seat_type'] ?? 'normal')) == 'vip' ? 'selected' : '' ?>>VIP</option>
              </select>
            </div>

            <!-- Input phụ thu: không bắt buộc, min=0, step=1000 (làm tròn 1000) -->
            <div class="mb-3">
              <label for="extra_price" class="form-label">Phụ thu (VNĐ)</label>
              <input type="number" 
                     name="extra_price" 
                     id="extra_price" 
                     class="form-control" 
                     value="<?= htmlspecialchars($_POST['extra_price'] ?? $seat['extra_price'] ?? '0') ?>" 
                     min="0"
                     step="1000"
                     placeholder="VD: 20000">
              <small class="text-muted">Giá phụ thu cho loại ghế này (0 nếu không có)</small>
            </div>

            <!-- Select trạng thái: available, booked, maintenance, reserved -->
            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select name="status" id="status" class="form-select">
                <?php
                // Lấy trạng thái hiện tại: ưu tiên $_POST, sau đó $seat, mặc định là 'available'
                $currentStatus = isset($_POST['status']) ? $_POST['status'] : ($seat['status'] ?? 'available');
                // Validate: nếu trạng thái không hợp lệ, mặc định là available
                $validStatuses = ['available', 'booked', 'maintenance', 'reserved'];
                if (!in_array($currentStatus, $validStatuses)) {
                  $currentStatus = 'available';
                }
                ?>
                <!-- selected: đánh dấu trạng thái hiện tại -->
                <option value="available" <?= $currentStatus == 'available' ? 'selected' : '' ?>>Có sẵn</option>
                <option value="booked" <?= $currentStatus == 'booked' ? 'selected' : '' ?>>Đã đặt</option>
                <option value="maintenance" <?= $currentStatus == 'maintenance' ? 'selected' : '' ?>>Bảo trì</option>
                <option value="reserved" <?= $currentStatus == 'reserved' ? 'selected' : '' ?>>Giữ chỗ</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Nút submit và hủy -->
        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật ghế
          </button>
          <a href="<?= BASE_URL ?>?act=seats<?= isset($seat['room_id']) ? '&room_id=' . $seat['room_id'] : '' ?>" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Validation form client-side: kiểm tra dữ liệu trước khi submit
  document.getElementById('seatForm').addEventListener('submit', function(e) {
    // Lấy giá trị các trường
    const room_id = document.getElementById('room_id').value;
    const row_label = document.getElementById('row_label').value.trim().toUpperCase(); // Chuyển thành chữ hoa
    const seat_number = document.getElementById('seat_number').value.trim();

    let errors = [];

    // Kiểm tra phòng: bắt buộc phải chọn
    if (!room_id) {
      errors.push('Vui lòng chọn phòng');
    }

    // Kiểm tra nhãn hàng: phải là 1 ký tự chữ cái A-Z
    if (!row_label || row_label.length !== 1 || !/^[A-Z]$/.test(row_label)) {
      errors.push('Nhãn hàng phải là 1 ký tự chữ cái (A-Z)');
    }

    // Kiểm tra số ghế: phải lớn hơn 0
    if (!seat_number || seat_number <= 0) {
      errors.push('Số ghế phải lớn hơn 0');
    }

    // Nếu có lỗi: ngăn submit và hiển thị alert
    if (errors.length > 0) {
      e.preventDefault();
      alert('Vui lòng kiểm tra lại các trường sau:\n\n' + errors.join('\n'));
      return false;
    }

    // Tự động chuyển row_label thành chữ hoa trước khi submit
    document.getElementById('row_label').value = row_label;
  });

  // Tự động chuyển row_label thành chữ hoa khi người dùng nhập (real-time)
  document.getElementById('row_label').addEventListener('input', function(e) {
    // Chuyển thành chữ hoa và loại bỏ các ký tự không phải A-Z
    this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
  });
</script>

