<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý phân quyền</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=permissions-assign&role=staff" class="btn btn-warning btn-sm">
          <i class="bi bi-person-badge"></i> Phân quyền cho Nhân viên
        </a>
      </div>
    </div>
    <div class="card-body">
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> Cập nhật phân quyền thành công!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <?php if ($_GET['error'] === 'admin_cannot_edit'): ?>
            <i class="bi bi-exclamation-triangle"></i> <strong>Lưu ý:</strong> Admin có tất cả quyền và không thể chỉnh sửa.
          <?php elseif ($_GET['error'] === 'customer_no_permissions'): ?>
            <i class="bi bi-exclamation-triangle"></i> <strong>Lưu ý:</strong> Khách hàng chỉ được mua hàng, không có quyền quản trị.
          <?php endif; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <div class="row">
        <?php 
          require_once __DIR__ . '/../../../models/Permission.php';
          $permissionModel = new Permission();
          foreach (['admin' => 'danger', 'staff' => 'warning', 'customer' => 'success'] as $role => $color): 
            $rolePermissions = $permissionModel->getByRole($role);
            $roleLabels = ['admin' => 'Admin', 'staff' => 'Staff', 'customer' => 'Customer'];
        ?>
          <div class="col-md-4 mb-4">
            <div class="card border-<?= $color ?>">
              <div class="card-header bg-<?= $color ?> text-white">
                <h5 class="mb-0">
                  <i class="bi bi-shield-check"></i> <?= $roleLabels[$role] ?>
                </h5>
              </div>
              <div class="card-body">
                <p class="text-muted mb-3">
                  <strong><?= count($rolePermissions) ?></strong> quyền được gán
                </p>
                <?php if ($role === 'admin'): ?>
                  <button class="btn btn-outline-<?= $color ?> btn-sm" disabled title="Admin có tất cả quyền và không thể chỉnh sửa">
                    <i class="bi bi-shield-check"></i> Quyền cao nhất (Không thể chỉnh sửa)
                  </button>
                <?php elseif ($role === 'customer'): ?>
                  <button class="btn btn-outline-<?= $color ?> btn-sm" disabled title="Khách hàng chỉ được mua hàng">
                    <i class="bi bi-cart"></i> Chỉ mua hàng (Không có quyền quản trị)
                  </button>
                <?php else: ?>
                  <a href="<?= BASE_URL ?>?act=permissions-assign&role=<?= $role ?>" class="btn btn-outline-<?= $color ?> btn-sm">
                    <i class="bi bi-pencil"></i> Chỉnh sửa phân quyền
                  </a>
                <?php endif; ?>
                <div class="mt-3">
                  <small class="text-muted">Các quyền hiện tại:</small>
                  <div class="mt-2">
                    <?php if (!empty($rolePermissions)): ?>
                      <?php
                        $grouped = [];
                        foreach ($rolePermissions as $perm) {
                          $module = $perm['module'] ?? 'other';
                          if (!isset($grouped[$module])) {
                            $grouped[$module] = [];
                          }
                          $grouped[$module][] = $perm;
                        }
                      ?>
                      <?php foreach ($grouped as $module => $perms): ?>
                        <div class="mb-2">
                          <strong class="text-<?= $color ?>"><?= ucfirst($module) ?>:</strong>
                          <div class="ms-3">
                            <?php foreach ($perms as $perm): ?>
                              <span class="badge bg-secondary me-1 mb-1"><?= htmlspecialchars($perm['display_name']) ?></span>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <span class="text-muted">Chưa có quyền nào</span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <hr class="my-4">

      <h5 class="mb-3">Danh sách tất cả quyền trong hệ thống</h5>
      <div class="accordion" id="permissionsAccordion">
        <?php 
          $moduleIndex = 0;
          foreach ($permissionsGrouped as $module => $permissions): 
            $moduleIndex++;
        ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?= $moduleIndex ?>">
              <button class="accordion-button <?= $moduleIndex > 1 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $moduleIndex ?>">
                <strong><?= ucfirst($module) ?></strong> 
                <span class="badge bg-primary ms-2"><?= count($permissions) ?> quyền</span>
              </button>
            </h2>
            <div id="collapse<?= $moduleIndex ?>" class="accordion-collapse collapse <?= $moduleIndex === 1 ? 'show' : '' ?>" data-bs-parent="#permissionsAccordion">
              <div class="accordion-body">
                <div class="table-responsive">
                  <table class="table table-sm table-hover">
                    <thead>
                      <tr>
                        <th>Tên quyền</th>
                        <th>Mô tả</th>
                        <th>Admin</th>
                        <th>Staff</th>
                        <th>Customer</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($permissions as $perm): ?>
                        <tr>
                          <td>
                            <code><?= htmlspecialchars($perm['name']) ?></code>
                            <br>
                            <small class="text-muted"><?= htmlspecialchars($perm['display_name']) ?></small>
                          </td>
                          <td><?= htmlspecialchars($perm['description'] ?? '—') ?></td>
                          <td>
                            <?php if (in_array($perm['id'], $adminPermissions)): ?>
                              <span class="badge bg-danger"><i class="bi bi-check"></i></span>
                            <?php else: ?>
                              <span class="text-muted">—</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php if (in_array($perm['id'], $staffPermissions)): ?>
                              <span class="badge bg-warning"><i class="bi bi-check"></i></span>
                            <?php else: ?>
                              <span class="text-muted">—</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php if (in_array($perm['id'], $customerPermissions)): ?>
                              <span class="badge bg-success"><i class="bi bi-check"></i></span>
                            <?php else: ?>
                              <span class="text-muted">—</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

