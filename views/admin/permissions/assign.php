<?php
// PERMISSIONS/ASSIGN.PHP - TRANG CẤU HÌNH QUYỀN MẶC ĐỊNH ADMIN
// Chức năng: Form cấu hình quyền mặc định cho role (Manager hoặc Staff), hiển thị danh sách quyền theo module (accordion)
// Biến từ controller: $role (manager hoặc staff), $roleLabel (nhãn role), $permissionsGrouped (quyền nhóm theo module), $currentPermissions (quyền hiện tại), $errors (lỗi validation)
// JavaScript: toggleAll() - chọn/bỏ chọn tất cả, updateBadges() - cập nhật số quyền đã chọn
?>
<div class="container-fluid">
  <div class="card shadow-sm">
    <!-- Header: tiêu đề với role và nút quay lại, màu khác nhau cho manager (primary) và staff (warning) -->
    <div class="card-header bg-<?= $role === 'manager' ? 'primary' : 'warning' ?> text-<?= $role === 'manager' ? 'white' : 'dark' ?> d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <i class="bi bi-<?= $role === 'manager' ? 'person-badge' : 'people' ?>"></i> Cấu hình quyền mặc định cho: 
        <strong><?= $roleLabel ?></strong>
      </h4>
      <a href="<?= BASE_URL ?>?act=permissions" class="btn btn-light">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <!-- Hướng dẫn sử dụng -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> <strong>Hướng dẫn:</strong> 
            Chọn các quyền mặc định cho tất cả <strong><?= $roleLabel ?></strong>. Tất cả tài khoản có role "<?= $roleLabel ?>" sẽ tự động có các quyền này.
            Bạn có thể chọn từng quyền cụ thể cho từng chức năng (xem, thêm, sửa, xóa).
          </div>
        </div>
      </div>

      <!-- Hiển thị lỗi validation nếu có: $errors từ controller -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Vui lòng kiểm tra lại:</strong>
          <ul class="mb-0 mt-2">
            <!-- Vòng lặp: hiển thị từng lỗi -->
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Form cấu hình quyền: submit về cùng trang -->
      <form method="POST" action="">
        <!-- Checkbox chọn tất cả: onchange gọi toggleAll() -->
        <div class="mb-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAll(this)">
            <label class="form-check-label" for="selectAll">
              <strong>Chọn tất cả / Bỏ chọn tất cả</strong>
            </label>
          </div>
        </div>

        <!-- Accordion hiển thị quyền theo module: Bootstrap accordion -->
        <div class="accordion" id="permissionsAccordion">
          <?php 
            $moduleIndex = 0;
            // Vòng lặp: duyệt qua từng module trong $permissionsGrouped
            foreach ($permissionsGrouped as $module => $permissions): 
              $moduleIndex++;
              // Đếm số quyền đã chọn trong module này: so sánh với $currentPermissions
              $checkedCount = 0;
              foreach ($permissions as $perm) {
                if (in_array($perm['id'], $currentPermissions)) {
                  $checkedCount++;
                }
              }
          ?>
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading<?= $moduleIndex ?>">
                <button class="accordion-button <?= $moduleIndex > 1 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $moduleIndex ?>">
                  <strong><?= ucfirst($module) ?></strong>
                  <span class="badge bg-primary ms-2">
                    <?= $checkedCount ?>/<?= count($permissions) ?> đã chọn
                  </span>
                </button>
              </h2>
              <div id="collapse<?= $moduleIndex ?>" class="accordion-collapse collapse <?= $moduleIndex === 1 ? 'show' : '' ?>" data-bs-parent="#permissionsAccordion">
                <div class="accordion-body">
                  <div class="row">
                    <?php foreach ($permissions as $perm): ?>
                      <div class="col-md-6 mb-3">
                        <div class="form-check">
                          <input 
                            class="form-check-input permission-checkbox" 
                            type="checkbox" 
                            name="permissions[]" 
                            value="<?= $perm['id'] ?>" 
                            id="perm_<?= $perm['id'] ?>"
                            <?= in_array($perm['id'], $currentPermissions) ? 'checked' : '' ?>
                          >
                          <label class="form-check-label" for="perm_<?= $perm['id'] ?>">
                            <strong><?= htmlspecialchars($perm['display_name']) ?></strong>
                            <br>
                            <small class="text-muted">
                              <code><?= htmlspecialchars($perm['name']) ?></code>
                              <?php if (!empty($perm['description'])): ?>
                                <br><?= htmlspecialchars($perm['description']) ?>
                              <?php endif; ?>
                            </small>
                          </label>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Lưu phân quyền
          </button>
          <a href="<?= BASE_URL ?>?act=permissions" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Hàm chọn/bỏ chọn tất cả quyền: gọi khi checkbox "Chọn tất cả" thay đổi
  function toggleAll(checkbox) {
    // Lấy tất cả checkbox quyền
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    // Vòng lặp: set checked cho tất cả checkbox theo trạng thái của checkbox "Chọn tất cả"
    checkboxes.forEach(cb => {
      cb.checked = checkbox.checked;
    });
    // Cập nhật badge số quyền đã chọn
    updateBadges();
  }

  // Hàm cập nhật badge số quyền đã chọn: đếm số checkbox checked trong mỗi module
  function updateBadges() {
    // Vòng lặp: duyệt qua từng accordion-item (module)
    document.querySelectorAll('.accordion-item').forEach(item => {
      const badge = item.querySelector('.badge');
      const total = item.querySelectorAll('.permission-checkbox').length; // Tổng số quyền
      const checked = item.querySelectorAll('.permission-checkbox:checked').length; // Số quyền đã chọn
      // Cập nhật text badge: "đã chọn/tổng"
      badge.textContent = checked + '/' + total + ' đã chọn';
    });
  }

  // Cập nhật badge khi checkbox thay đổi: gắn event listener cho mỗi checkbox quyền
  document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      updateBadges(); // Gọi updateBadges() mỗi khi checkbox thay đổi
    });
  });
</script>

