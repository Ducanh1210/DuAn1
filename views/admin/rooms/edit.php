<?php
// ROOMS/EDIT.PHP - TRANG SỬA PHÒNG CHIẾU ADMIN
// Chức năng: Form sửa thông tin phòng chiếu (rạp, tên phòng, mã phòng, số hàng, số cột)
// Biến từ controller: $room (thông tin phòng cần sửa), $errors (lỗi validation), $cinemas (danh sách rạp)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với tên phòng và nút quay lại -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa phòng: <?= htmlspecialchars($room['name']) ?></h4>
      <!-- Link quay lại danh sách phòng -->
      <a href="<?= BASE_URL ?>?act=rooms" class="btn btn-secondary">
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

      <!-- Form sửa phòng: onsubmit gọi hàm validateRoomForm() để validate client-side -->
      <form action="" method="post" id="roomForm" onsubmit="return validateRoomForm(event)">
        <div class="row">
          <div class="col-md-8">
            <!-- Dropdown chọn rạp: bắt buộc (*) -->
            <div class="mb-3">
              <label for="cinema_id" class="form-label">Rạp <span class="text-danger">*</span></label>
              <select name="cinema_id" id="cinema_id" class="form-select <?= !empty($errors['cinema_id']) ? 'is-invalid' : '' ?>" >
                <option value="">-- Chọn rạp --</option>
                <!-- Vòng lặp: tạo option cho mỗi rạp -->
                <?php if (!empty($cinemas)): ?>
                  <?php foreach ($cinemas as $cinema): ?>
                    <!-- selected: đánh dấu rạp đang được chọn (từ $_POST nếu đã submit, nếu không thì từ $room) -->
                    <option value="<?= $cinema['id'] ?>" <?= (isset($_POST['cinema_id']) ? $_POST['cinema_id'] : $room['cinema_id']) == $cinema['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cinema['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['cinema_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['cinema_id'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="room_code" class="form-label">Mã phòng <span class="text-danger">*</span></label>
              <input type="text" 
                     name="room_code" 
                     id="room_code" 
                     class="form-control <?= !empty($errors['room_code']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['room_code'] ?? $room['room_code'] ?? '') ?>" 
                     
                     placeholder="VD: R1, R2, R3...">
              <?php if (!empty($errors['room_code'])): ?>
                <div class="text-danger small mt-1"><?= $errors['room_code'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">Tên phòng <span class="text-danger">*</span></label>
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['name'] ?? $room['name'] ?? '') ?>" 
                     
                     placeholder="VD: Phòng Chiếu 1, Phòng Chiếu 2...">
              <?php if (!empty($errors['name'])): ?>
                <div class="text-danger small mt-1"><?= $errors['name'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="seat_count" class="form-label">Số ghế (tùy chọn)</label>
              <?php
              // Lấy số ghế thực tế từ bảng seats
              require_once __DIR__ . '/../../../models/Seat.php';
              $seatModel = new Seat();
              $actualSeatCount = $seatModel->getCountByRoom($room['id']);
              ?>
              <input type="number" 
                     name="seat_count" 
                     id="seat_count" 
                     class="form-control <?= !empty($errors['seat_count']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['seat_count'] ?? $actualSeatCount ?? '0') ?>" 
                     min="0"
                     
                     placeholder="Mặc định: 0 (sẽ tạo ghế ở quản lý ghế)">
              <small class="text-muted">
                Số ghế thực tế hiện tại: <strong><?= number_format($actualSeatCount, 0, ',', '.') ?></strong> ghế. 
                Số ghế sẽ được cập nhật tự động khi bạn tạo/xóa ghế ở quản lý ghế.
              </small>
              <?php if (!empty($errors['seat_count'])): ?>
                <div class="text-danger small mt-1"><?= $errors['seat_count'] ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật phòng
          </button>
          <a href="<?= BASE_URL ?>?act=rooms" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Validation form với alert
  document.getElementById('roomForm').addEventListener('submit', function(e) {
    const cinema_id = document.getElementById('cinema_id').value;
    const room_code = document.getElementById('room_code').value.trim();
    const name = document.getElementById('name').value.trim();
    const seat_count = document.getElementById('seat_count').value.trim();

    let errors = [];

    if (!cinema_id) {
      errors.push('Vui lòng chọn rạp');
    }

    if (!room_code) {
      errors.push('Vui lòng nhập mã phòng');
    }

    if (!name) {
      errors.push('Vui lòng nhập tên phòng');
    }

    // Số ghế không bắt buộc, mặc định là 0
    if (seat_count && parseInt(seat_count) < 0) {
      errors.push('Số ghế không được âm');
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert('Vui lòng kiểm tra lại các trường sau:\n\n' + errors.join('\n'));
      return false;
    }
  });
</script>

