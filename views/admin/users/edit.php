<?php
// USERS/EDIT.PHP - TRANG SỬA THÔNG TIN NGƯỜI DÙNG ADMIN
// Chức năng: Form sửa thông tin người dùng (admin, manager, staff, customer)
// Biến từ controller: $user (thông tin người dùng cần sửa), $errors (lỗi validation), $cinemas (danh sách rạp), $roles (danh sách quyền)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và nút quay lại -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa thông tin người dùng</h4>
      <!-- Link quay lại danh sách người dùng -->
      <a href="<?= BASE_URL ?>?act=users" class="btn btn-secondary">
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

      <!-- Form sửa người dùng: onsubmit gọi hàm validateUserForm() để validate client-side -->
      <form action="" method="post" id="userForm" onsubmit="return validateUserForm(event)" novalidate>
        <div class="row">
          <div class="col-md-6">
            <!-- Input họ tên: bắt buộc (*), value lấy từ $_POST nếu có (sau submit), nếu không thì lấy từ $user -->
            <div class="mb-3">
              <label for="full_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
              <!-- is-invalid: thêm class nếu có lỗi để hiển thị border đỏ -->
              <input type="text" name="full_name" id="full_name" class="form-control <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['full_name'] ?? $user['full_name']) ?>" required>
              <!-- Hiển thị lỗi validation nếu có -->
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
            <input type="hidden" name="role" value="<?= htmlspecialchars($user['role']) ?>">
            
            <div class="mb-3" id="cinema_field" style="display: <?= in_array($user['role'], ['manager', 'staff']) ? 'block' : 'none' ?>;">
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

            <?php if (($user['role'] ?? '') === 'customer'): ?>
            <div class="mb-3">
              <label for="total_spending" class="form-label">Tổng chi tiêu (đ)</label>
              <input type="number" name="total_spending" id="total_spending" class="form-control" value="<?= htmlspecialchars($user['total_spending'] ?? 0) ?>" step="0.01" min="0" readonly style="background-color: #e9ecef; cursor: not-allowed;">
              <small class="text-muted">Tổng số tiền người dùng đã chi tiêu (chỉ tự động cập nhật khi khách hàng mua vé, không thể sửa thủ công)</small>
            </div>
            <?php endif; ?>
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
    const phone = document.getElementById('phone').value.trim();
    const roleInput = document.querySelector('input[name="role"]');
    const role = roleInput ? roleInput.value : '';

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

    // Validate phone if provided
    if (phone && phone !== '') {
      const phoneRegex = /^[0-9]{10,11}$/;
      if (!phoneRegex.test(phone)) {
        alert('Số điện thoại phải có 10-11 chữ số!');
        document.getElementById('phone').focus();
        return false;
      }
    }

    // Kiểm tra cinema_id nếu role là manager hoặc staff (bắt buộc khi sửa)
    if (role === 'manager' || role === 'staff') {
      const cinemaId = document.getElementById('cinema_id');
      if (cinemaId && (!cinemaId.value || cinemaId.value === '')) {
        alert('Vui lòng chọn rạp quản lý!');
        cinemaId.focus();
        return false;
      }
    }

    return true;
  }
</script>


