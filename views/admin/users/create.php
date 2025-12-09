<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Thêm người dùng mới</h4>
      <a href="<?= BASE_URL ?>?act=users" class="btn btn-secondary">
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

      <form action="" method="post" id="userForm" onsubmit="return validateUserForm(event)" novalidate>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="full_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" >
              <?php if (!empty($errors['full_name'])): ?>
                <div class="text-danger small mt-1"><?= $errors['full_name'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" >
              <?php if (!empty($errors['email'])): ?>
                <div class="text-danger small mt-1"><?= $errors['email'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" name="password" id="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" >
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Ẩn/Hiện mật khẩu">
                  <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
              </div>
              <?php if (!empty($errors['password'])): ?>
                <div class="text-danger small mt-1"><?= $errors['password'] ?></div>
              <?php endif; ?>
              <small class="text-muted">Tối thiểu 6 ký tự</small>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Số điện thoại</label>
              <input type="text" name="phone" id="phone" class="form-control <?= !empty($errors['phone']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="0123456789">
              <?php if (!empty($errors['phone'])): ?>
                <div class="text-danger small mt-1"><?= $errors['phone'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="birth_date" class="form-label">Ngày sinh</label>
              <input type="date" name="birth_date" id="birth_date" class="form-control <?= !empty($errors['birth_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>">
              <?php if (!empty($errors['birth_date'])): ?>
                <div class="text-danger small mt-1"><?= $errors['birth_date'] ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label for="role" class="form-label">Quyền <span class="text-danger">*</span></label>
              <select name="role" id="role" class="form-select <?= !empty($errors['role']) ? 'is-invalid' : '' ?>"  onchange="toggleCinemaField()">
                <option value="">-- Chọn quyền --</option>
                <option value="manager" <?= (isset($_POST['role']) && $_POST['role'] == 'manager') ? 'selected' : '' ?>>Quản lý</option>
                <option value="staff" <?= (isset($_POST['role']) && $_POST['role'] == 'staff') ? 'selected' : '' ?>>Nhân viên</option>
              </select>
              <?php if (!empty($errors['role'])): ?>
                <div class="text-danger small mt-1"><?= $errors['role'] ?></div>
              <?php endif; ?>
              <small class="text-muted">
                <strong>Quản lý:</strong> Quản lý rạp, phòng, ghế, lịch chiếu (sau Admin)<br>
                <strong>Nhân viên:</strong> Bán vé, xem thống kê, xem phim<br>
                <em class="text-info">Lưu ý: Chỉ có thể tạo tài khoản cho Quản lý và Nhân viên. Khách hàng tự đăng ký tài khoản.</em>
              </small>
            </div>

            <div class="mb-3" id="cinema_field" style="display: none;">
              <label for="cinema_id" class="form-label">Rạp quản lý</label>
              <select name="cinema_id" id="cinema_id" class="form-select <?= !empty($errors['cinema_id']) ? 'is-invalid' : '' ?>">
                <option value="">-- Chọn rạp (tùy chọn) --</option>
                <?php if (!empty($cinemas)): ?>
                  <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= (isset($_POST['cinema_id']) && $_POST['cinema_id'] == $cinema['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cinema['name']) ?> - <?= htmlspecialchars($cinema['address'] ?? '') ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>Chưa có rạp nào. Vui lòng tạo rạp trước!</option>
                <?php endif; ?>
              </select>
              <?php if (!empty($errors['cinema_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['cinema_id'] ?></div>
              <?php endif; ?>
              <?php if (empty($cinemas)): ?>
                <div class="text-warning small mt-1">
                  <i class="bi bi-exclamation-triangle"></i> Chưa có rạp nào. Vui lòng <a href="<?= BASE_URL ?>?act=cinemas-create">tạo rạp</a> trước khi tạo nhân viên.
                </div>
              <?php else: ?>
                <small class="text-muted">Có thể gán rạp sau khi tạo tài khoản (tùy chọn)</small>
              <?php endif; ?>
            </div>

          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Thêm người dùng
          </button>
          <a href="<?= BASE_URL ?>?act=users" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Toggle password visibility
  document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
      togglePassword.addEventListener('click', function() {
        const currentType = passwordInput.getAttribute('type');
        const newType = currentType === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', newType);
        
        // Toggle icon - khi type là text (hiện) thì icon là eye-slash, khi type là password (ẩn) thì icon là eye
        if (newType === 'text') {
          // Hiện mật khẩu - đổi icon thành eye-slash
          eyeIcon.classList.remove('bi-eye');
          eyeIcon.classList.add('bi-eye-slash');
        } else {
          // Ẩn mật khẩu - đổi icon thành eye
          eyeIcon.classList.remove('bi-eye-slash');
          eyeIcon.classList.add('bi-eye');
        }
      });
    }
  });

  // Validation function với alert
  function validateUserForm(event) {
    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;
    const phone = document.getElementById('phone').value.trim();

    if (!fullName || fullName === '') {
      alert('Vui lòng nhập họ tên!');
      document.getElementById('full_name').focus();
      return false;
    }

    if (!email || email === '') {
      alert('Vui lòng nhập email!');
      document.getElementById('email').focus();
      return false;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Vui lòng nhập email hợp lệ!');
      document.getElementById('email').focus();
      return false;
    }

    if (!password || password === '') {
      alert('Vui lòng nhập mật khẩu!');
      document.getElementById('password').focus();
      return false;
    }

    if (password.length < 6) {
      alert('Mật khẩu phải có ít nhất 6 ký tự!');
      document.getElementById('password').focus();
      return false;
    }

    if (!role || role === '') {
      alert('Vui lòng chọn quyền!');
      document.getElementById('role').focus();
      return false;
    }

    if (role === 'admin') {
      alert('Không thể tạo tài khoản Admin. Chỉ có 1 Admin duy nhất trong hệ thống!');
      document.getElementById('role').focus();
      return false;
    }

    if (!['manager', 'staff'].includes(role)) {
      alert('Quyền không hợp lệ. Chỉ có thể tạo Quản lý hoặc Nhân viên!');
      document.getElementById('role').focus();
      return false;
    }

    // Validate phone if provided
    if (phone && phone !== '') {
      const phoneRegex = /^[0-9]{10,11}$/;
      if (!phoneRegex.test(phone)) {
        alert('Số điện thoại phải có 10-11 chữ số!');
        document.getElementById('phone').focus();
        return false;
      }
    }

    // Khi tạo mới: không bắt buộc phải chọn rạp (có thể để trống)
    return true;
  }
  
  // Toggle cinema field based on role
  function toggleCinemaField() {
    const role = document.getElementById('role').value;
    const cinemaField = document.getElementById('cinema_field');
    const cinemaSelect = document.getElementById('cinema_id');
    
    if (role === 'manager' || role === 'staff') {
      cinemaField.style.display = 'block';
      // Không bắt buộc khi tạo mới
      cinemaSelect.removeAttribute('required');
    } else {
      cinemaField.style.display = 'none';
      cinemaSelect.removeAttribute('required');
      cinemaSelect.value = '';
    }
  }
  
  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    toggleCinemaField();
  });
</script>

