<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý người dùng</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=users-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm người dùng mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Thống kê -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <h5 class="card-title">Tổng số</h5>
              <h3><?= $stats['total'] ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-danger text-white">
            <div class="card-body">
              <h5 class="card-title">Admin</h5>
              <h3><?= $stats['admin'] ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-warning text-white">
            <div class="card-body">
              <h5 class="card-title">Staff</h5>
              <h3><?= $stats['staff'] ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <h5 class="card-title">Customer</h5>
              <h3><?= $stats['customer'] ?></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter và Search -->
      <div class="row mb-3">
        <div class="col-md-6">
          <form method="GET" action="" class="d-flex gap-2">
            <input type="hidden" name="act" value="users">
            <select name="role" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
              <option value="">Tất cả quyền</option>
              <option value="admin" <?= $roleFilter == 'admin' ? 'selected' : '' ?>>Admin</option>
              <option value="staff" <?= $roleFilter == 'staff' ? 'selected' : '' ?>>Staff</option>
              <option value="customer" <?= $roleFilter == 'customer' ? 'selected' : '' ?>>Customer</option>
            </select>
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc email..." value="<?= htmlspecialchars($searchKeyword) ?>">
            <button type="submit" class="btn btn-outline-primary">
              <i class="bi bi-search"></i> Tìm
            </button>
            <?php if ($roleFilter || $searchKeyword): ?>
              <a href="<?= BASE_URL ?>?act=users" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Xóa bộ lọc
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Ngày sinh</th>
              <th>Hạng thành viên</th>
              <th>Tổng chi tiêu</th>
              <th>Quyền</th>
              <th>Ngày tạo</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['full_name']) ?></td>
                <td><?= htmlspecialchars($item['email']) ?></td>
                <td><?= htmlspecialchars($item['phone'] ?? 'N/A') ?></td>
                <td><?= $item['birth_date'] ? date('d/m/Y', strtotime($item['birth_date'])) : 'N/A' ?></td>
                <td>
                  <?php if ($item['tier_name']): ?>
                    <span class="badge bg-info"><?= htmlspecialchars($item['tier_name']) ?></span>
                  <?php else: ?>
                    <span class="text-muted">Chưa có</span>
                  <?php endif; ?>
                </td>
                <td><?= number_format($item['total_spending'] ?? 0, 0, ',', '.') ?> đ</td>
                <td>
                  <?php
                    $roleColors = [
                      'admin' => 'danger',
                      'staff' => 'warning',
                      'customer' => 'success'
                    ];
                    $roleLabels = [
                      'admin' => 'Admin',
                      'staff' => 'Staff',
                      'customer' => 'Customer'
                    ];
                    $role = $item['role'] ?? 'customer';
                    $color = $roleColors[$role] ?? 'secondary';
                    $label = $roleLabels[$role] ?? ucfirst($role);
                  ?>
                  <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                </td>
                <td><?= $item['created_at'] ? date('d/m/Y H:i', strtotime($item['created_at'])) : 'N/A' ?></td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=users-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=users-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=users-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center text-muted py-4">Chưa có người dùng nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

