<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa thông tin người dùng</h4>
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

      <form action="" method="post" id="userForm" onsubmit="return validateUserForm(event)">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="full_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['full_name'] ?? $user['full_name']) ?>" required>
              <?php if (!empty($errors['full_name'])): ?>
                <div class="text-danger small mt-1"><?= $errors['full_name'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
              <?php if (!empty($errors['email'])): ?>
                <div class="text-danger small mt-1"><?= $errors['email'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Mật khẩu mới</label>
              <div class="input-group">
                <input type="password" name="password" id="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" placeholder="Để trống nếu không đổi">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Ẩn/Hiện mật khẩu">
                  <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
              </div>
              <?php if (!empty($errors['password'])): ?>
                <div class="text-danger small mt-1"><?= $errors['password'] ?></div>
              <?php endif; ?>
              <small class="text-muted">Chỉ nhập nếu muốn đổi mật khẩu (tối thiểu 6 ký tự)</small>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Số điện thoại</label>
              <input type="text" name="phone" id="phone" class="form-control <?= !empty($errors['phone']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone'] ?? '') ?>" placeholder="0123456789">
              <?php if (!empty($errors['phone'])): ?>
                <div class="text-danger small mt-1"><?= $errors['phone'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="birth_date" class="form-label">Ngày sinh</label>
              <input type="date" name="birth_date" id="birth_date" class="form-control <?= !empty($errors['birth_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['birth_date'] ?? $user['birth_date'] ?? '') ?>">
              <?php if (!empty($errors['birth_date'])): ?>
                <div class="text-danger small mt-1"><?= $errors['birth_date'] ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label for="role" class="form-label">Quyền <span class="text-danger">*</span></label>
              <?php if ($user['role'] === 'admin'): ?>
                <!-- Admin không thể đổi role -->
                <input type="text" class="form-control" value="Admin" readonly style="background-color: #f8f9fa;">
                <input type="hidden" name="role" value="admin">
                <small class="text-muted">
                  <em class="text-warning">Không thể thay đổi quyền của Admin</em>
                </small>
              <?php else: ?>
                <!-- Manager, Staff và Customer có thể chuyển đổi -->
                <select name="role" id="role" class="form-select <?= !empty($errors['role']) ? 'is-invalid' : '' ?>" required onchange="toggleCinemaField()">
                  <option value="manager" <?= (isset($_POST['role']) ? $_POST['role'] : $user['role']) == 'manager' ? 'selected' : '' ?>>Quản lý</option>
                  <option value="staff" <?= (isset($_POST['role']) ? $_POST['role'] : $user['role']) == 'staff' ? 'selected' : '' ?>>Nhân viên</option>
                  <option value="customer" <?= (isset($_POST['role']) ? $_POST['role'] : $user['role']) == 'customer' ? 'selected' : '' ?>>Khách hàng</option>
                </select>
                <?php if (!empty($errors['role'])): ?>
                  <div class="text-danger small mt-1"><?= $errors['role'] ?></div>
                <?php endif; ?>
                <small class="text-muted">
                  <strong>Quản lý:</strong> Quản lý rạp, phòng, ghế, lịch chiếu<br>
                  <strong>Nhân viên:</strong> Bán vé, xem thống kê<br>
                  <strong>Khách hàng:</strong> Khách hàng<br>
                  <em class="text-info">Admin có thể chuyển đổi giữa các quyền</em>
                </small>
              <?php endif; ?>
            </div>

            <div class="mb-3" id="cinema_field" style="display: <?= in_array(isset($_POST['role']) ? $_POST['role'] : $user['role'], ['manager', 'staff']) ? 'block' : 'none' ?>;">
              <label for="cinema_id" class="form-label">Rạp quản lý <span class="text-danger">*</span></label>
              <select name="cinema_id" id="cinema_id" class="form-select <?= !empty($errors['cinema_id']) ? 'is-invalid' : '' ?>">
                <option value="">-- Chọn rạp --</option>
                <?php if (!empty($cinemas)): ?>
                  <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= (isset($_POST['cinema_id']) ? $_POST['cinema_id'] : $user['cinema_id']) == $cinema['id'] ? 'selected' : '' ?>>
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
                  <i class="bi bi-exclamation-triangle"></i> Chưa có rạp nào. Vui lòng <a href="<?= BASE_URL ?>?act=cinemas-create">tạo rạp</a> trước khi gán cho nhân viên.
                </div>
              <?php else: ?>
                <small class="text-muted">Chọn rạp mà nhân viên này sẽ quản lý</small>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="tier_id" class="form-label">Hạng thành viên</label>
              <select name="tier_id" id="tier_id" class="form-select">
                <option value="">-- Chọn hạng thành viên --</option>
                <?php if (!empty($tiers)): ?>
                  <?php foreach ($tiers as $tier): ?>
                    <option value="<?= $tier['id'] ?>" <?= (isset($_POST['tier_id']) ? $_POST['tier_id'] : $user['tier_id']) == $tier['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($tier['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="total_spending" class="form-label">Tổng chi tiêu (đ)</label>
              <input type="number" name="total_spending" id="total_spending" class="form-control" value="<?= htmlspecialchars($_POST['total_spending'] ?? $user['total_spending'] ?? 0) ?>" step="0.01" min="0">
              <small class="text-muted">Tổng số tiền người dùng đã chi tiêu</small>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật
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
      togglePassword.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission if button is inside form
        const currentType = passwordInput.getAttribute('type');
        const newType = currentType === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', newType);
        
        // Toggle icon
        if (newType === 'text') {
          eyeIcon.classList.remove('bi-eye');
          eyeIcon.classList.add('bi-eye-slash');
        } else {
          eyeIcon.classList.remove('bi-eye-slash');
          eyeIcon.classList.add('bi-eye');
        }
      });
    }
  });

  // Validation function
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

    // Password is optional in edit, but if provided, must be at least 6 characters
    if (password && password !== '' && password.length < 6) {
      alert('Mật khẩu phải có ít nhất 6 ký tự!');
      document.getElementById('password').focus();
      return false;
    }

    if (!role || role === '') {
      alert('Vui lòng chọn quyền!');
      const roleField = document.getElementById('role');
      if (roleField) {
        roleField.focus();
      }
      return false;
    }

    // Kiểm tra nếu cố gắng chọn admin
    if (role === 'admin') {
      alert('Không thể chuyển tài khoản sang Admin. Chỉ có 1 Admin duy nhất trong hệ thống!');
      const roleField = document.getElementById('role');
      if (roleField) {
        roleField.focus();
      }
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

    // Kiểm tra cinema_id nếu role là manager hoặc staff
    const role = document.getElementById('role').value;
    if (role === 'manager' || role === 'staff') {
      const cinemaId = document.getElementById('cinema_id').value;
      if (!cinemaId || cinemaId === '') {
        alert('Vui lòng chọn rạp quản lý!');
        document.getElementById('cinema_id').focus();
        return false;
      }
    }

    return true;
  }
  
  // Toggle cinema field based on role
  function toggleCinemaField() {
    const role = document.getElementById('role').value;
    const cinemaField = document.getElementById('cinema_field');
    const cinemaSelect = document.getElementById('cinema_id');
    
    if (role === 'manager' || role === 'staff') {
      cinemaField.style.display = 'block';
      cinemaSelect.setAttribute('required', 'required');
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

