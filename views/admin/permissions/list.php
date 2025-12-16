<?php
// PERMISSIONS/LIST.PHP - TRANG QUẢN LÝ PHÂN QUYỀN ADMIN
// Chức năng: Hiển thị và quản lý phân quyền cho Manager và Staff (quyền mặc định và quyền cá nhân)
// Biến từ controller: $managers (danh sách manager), $staff (danh sách staff), $defaultManagerPermissions, $defaultStaffPermissions
?>
<div class="container-fluid">
  <div class="card shadow-sm">
    <!-- Header: tiêu đề trang -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <i class="bi bi-shield-lock"></i> Quản lý phân quyền
      </h4>
    </div>
    <div class="card-body">
      <!-- Hiển thị thông báo thành công từ URL parameter -->
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> Cập nhật phân quyền thành công!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Hiển thị thông báo lỗi từ URL parameter -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <!-- Kiểm tra loại lỗi -->
          <?php if ($_GET['error'] === 'user_not_found'): ?>
            <i class="bi bi-exclamation-triangle"></i> Không tìm thấy người dùng.
          <?php elseif ($_GET['error'] === 'invalid_role'): ?>
            <i class="bi bi-exclamation-triangle"></i> Chỉ có thể phân quyền cho Quản lý.
          <?php endif; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Thông báo hướng dẫn -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            <strong>Lưu ý:</strong> Admin có tất cả quyền và không cần phân quyền. 
            Bạn có thể phân quyền cho từng Quản lý cụ thể hoặc cấu hình quyền mặc định cho Nhân viên.
          </div>
        </div>
      </div>

      <!-- Phần Quản lý (Manager): quyền mặc định cho tất cả Manager -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-primary">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">
                <i class="bi bi-person-badge"></i> Quản lý (Manager)
                <!-- Badge hiển thị số lượng tài khoản Manager: count($managers) -->
                <span class="badge bg-light text-primary ms-2"><?= count($managers) ?> tài khoản</span>
              </h5>
            </div>
            <div class="card-body">
              <!-- Mô tả: quyền mặc định áp dụng cho tất cả Manager -->
              <p class="text-muted mb-3">
                Quyền mặc định cho tất cả Quản lý. Tất cả tài khoản có role "Quản lý" sẽ tự động có các quyền này.
              </p>
              
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <span class="badge bg-primary fs-6">
                    <i class="bi bi-shield-check"></i> <?= $managerPermissionCount ?> quyền được gán
                  </span>
                </div>
                <a href="<?= BASE_URL ?>?act=permissions-assign&role=manager" 
                   class="btn btn-primary">
                  <i class="bi bi-gear"></i> Cấu hình quyền Quản lý
                </a>
              </div>

              <?php if (empty($managers)): ?>
                <div class="alert alert-warning">
                  <i class="bi bi-exclamation-triangle"></i> Chưa có tài khoản Quản lý nào.
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 25%">Tên</th>
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Rạp được gán</th>
                        <th style="width: 20%">Quyền</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($managers as $manager): ?>
                        <tr>
                          <td><?= $manager['id'] ?></td>
                          <td>
                            <strong><?= htmlspecialchars($manager['full_name']) ?></strong>
                            <?php if ($manager['status'] === 'banned'): ?>
                              <span class="badge bg-danger ms-1">Đã khóa</span>
                            <?php endif; ?>
                          </td>
                          <td><?= htmlspecialchars($manager['email']) ?></td>
                          <td>
                            <?php if (!empty($manager['cinema_name'])): ?>
                              <span class="badge bg-info"><?= htmlspecialchars($manager['cinema_name']) ?></span>
                            <?php else: ?>
                              <span class="text-muted">Chưa gán rạp</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <span class="badge bg-primary">
                              <?= $managerPermissionCount ?> quyền mặc định
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Phần Nhân viên (Staff) -->
      <div class="row">
        <div class="col-12">
          <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
              <h5 class="mb-0">
                <i class="bi bi-people"></i> Nhân viên (Staff)
                <span class="badge bg-dark ms-2"><?= count($staffs) ?> tài khoản</span>
              </h5>
            </div>
            <div class="card-body">
              <p class="text-muted mb-3">
                Quyền mặc định cho tất cả Nhân viên. Tất cả Nhân viên sẽ có cùng quyền này.
              </p>
              
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <span class="badge bg-warning text-dark fs-6">
                    <i class="bi bi-shield-check"></i> <?= $staffPermissionCount ?> quyền được gán
                  </span>
                </div>
                <a href="<?= BASE_URL ?>?act=permissions-assign&role=staff" 
                   class="btn btn-warning">
                  <i class="bi bi-gear"></i> Cấu hình quyền Nhân viên
                </a>
              </div>

              <?php if (empty($staffs)): ?>
                <div class="alert alert-warning">
                  <i class="bi bi-exclamation-triangle"></i> Chưa có tài khoản Nhân viên nào.
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 25%">Tên</th>
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Trạng thái</th>
                        <th style="width: 20%">Quyền</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($staffs as $staff): ?>
                        <tr>
                          <td><?= $staff['id'] ?></td>
                          <td>
                            <strong><?= htmlspecialchars($staff['full_name']) ?></strong>
                          </td>
                          <td><?= htmlspecialchars($staff['email']) ?></td>
                          <td>
                            <?php if ($staff['status'] === 'active'): ?>
                              <span class="badge bg-success">Hoạt động</span>
                            <?php else: ?>
                              <span class="badge bg-danger">Đã khóa</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <span class="badge bg-warning text-dark">
                              <?= $staffPermissionCount ?> quyền mặc định
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
