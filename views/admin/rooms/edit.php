<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa phòng: <?= htmlspecialchars($room['name']) ?></h4>
      <a href="<?= BASE_URL ?>?act=rooms" class="btn btn-secondary">
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

      <form action="" method="post" id="roomForm" onsubmit="return validateRoomForm(event)">
        <div class="row">
          <div class="col-md-8">
            <div class="mb-3">
              <label for="cinema_id" class="form-label">Rạp <span class="text-danger">*</span></label>
              <select name="cinema_id" id="cinema_id" class="form-select <?= !empty($errors['cinema_id']) ? 'is-invalid' : '' ?>" >
                <option value="">-- Chọn rạp --</option>
                <?php if (!empty($cinemas)): ?>
                  <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= (isset($_POST['cinema_id']) ? $_POST['cinema_id'] : $room['cinema_id']) == $cinema['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cinema['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
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
              <label for="seat_count" class="form-label">Số ghế <span class="text-danger">*</span></label>
              <input type="number" 
                     name="seat_count" 
                     id="seat_count" 
                     class="form-control <?= !empty($errors['seat_count']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['seat_count'] ?? $room['seat_count'] ?? '') ?>" 
                     min="1"
                     
                     placeholder="VD: 150, 200...">
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

    if (!seat_count || seat_count <= 0) {
      errors.push('Vui lòng nhập số ghế hợp lệ (lớn hơn 0)');
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert('Vui lòng kiểm tra lại các trường sau:\n\n' + errors.join('\n'));
      return false;
    }
  });
</script>

