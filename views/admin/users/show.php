<?php
// USERS/SHOW.PHP - TRANG CHI TIẾT NGƯỜI DÙNG ADMIN
// Chức năng: Hiển thị thông tin chi tiết của một người dùng (thông tin cá nhân, quyền, rạp, tổng chi tiêu)
// Biến từ controller: $user (thông tin người dùng)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và các nút thao tác -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết người dùng</h4>
      <div>
        <!-- Link sửa người dùng -->
        <a href="<?= BASE_URL ?>?act=users-edit&id=<?= $user['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa
        </a>
        <!-- Link quay lại danh sách người dùng -->
        <a href="<?= BASE_URL ?>?act=users" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Cột trái: Thông tin cá nhân -->
        <div class="col-md-6">
          <table class="table table-bordered">
            <tbody>
              <!-- ID người dùng -->
              <tr>
                <th width="40%">ID</th>
                <td><?= $user['id'] ?></td>
              </tr>
              <!-- Họ tên -->
              <tr>
                <th>Họ tên</th>
                <td><?= htmlspecialchars($user['full_name']) ?></td>
              </tr>
              <!-- Email -->
              <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($user['email']) ?></td>
              </tr>
              <!-- Số điện thoại: hiển thị "N/A" nếu không có -->
              <tr>
                <th>Số điện thoại</th>
                <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
              </tr>
              <!-- Ngày sinh: format d/m/Y -->
              <tr>
                <th>Ngày sinh</th>
                <td><?= $user['birth_date'] ? date('d/m/Y', strtotime($user['birth_date'])) : 'N/A' ?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th width="40%">Quyền</th>
                <td>
                  <?php
                    $roleColors = [
                      'admin' => 'danger',
                      'manager' => 'info',
                      'staff' => 'warning',
                      'customer' => 'success'
                    ];
                    $roleLabels = [
                      'admin' => 'Admin',
                      'manager' => 'Manager',
                      'staff' => 'Staff',
                      'customer' => 'Customer'
                    ];
                    $role = $user['role'] ?? 'customer';
                    $color = $roleColors[$role] ?? 'secondary';
                    $label = $roleLabels[$role] ?? ucfirst($role);
                  ?>
                  <span class="badge bg-<?= $color ?> fs-6"><?= $label ?></span>
                </td>
              </tr>
              <?php if (($user['role'] ?? '') === 'customer'): ?>
                <tr>
                  <th>Tổng chi tiêu</th>
                  <td><strong><?= number_format($user['total_spending'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                </tr>
              <?php endif; ?>
              <tr>
                <th>Ngày tạo</th>
                <td><?= $user['created_at'] ? date('d/m/Y H:i:s', strtotime($user['created_at'])) : 'N/A' ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="mt-4">
        <h5>Mô tả quyền:</h5>
        <div class="alert alert-info">
          <?php if ($user['role'] == 'admin'): ?>
            <strong>Admin:</strong> Có toàn quyền quản trị hệ thống, bao gồm quản lý phim, lịch chiếu, người dùng, và tất cả các chức năng khác.
          <?php elseif ($user['role'] == 'manager'): ?>
            <strong>Manager:</strong> Quản lý rạp được gán, có quyền quản lý phim, lịch chiếu, phòng chiếu của rạp mình, nhưng không có quyền quản lý người dùng và cài đặt hệ thống.
          <?php elseif ($user['role'] == 'staff'): ?>
            <strong>Staff:</strong> Nhân viên, có quyền quản lý một số chức năng nhất định như quản lý lịch chiếu, đặt vé, nhưng không có quyền quản lý người dùng và cài đặt hệ thống.
          <?php else: ?>
            <strong>Customer:</strong> Khách hàng, chỉ có quyền xem phim, đặt vé và quản lý thông tin cá nhân của mình.
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

