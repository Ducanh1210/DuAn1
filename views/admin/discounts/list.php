<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý mã khuyến mại</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=discounts-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm mã khuyến mại mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã khuyến mại</th>
              <th>Tiêu đề</th>
              <th>Giảm giá</th>
              <th>Ngày bắt đầu</th>
              <th>Ngày kết thúc</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <?php
                $now = date('Y-m-d');
                $startDate = $item['start_date'] ?? null;
                $endDate = $item['end_date'] ?? null;
                $isActive = $item['status'] === 'active';
                $isValid = true;
                
                if ($startDate && $now < $startDate) {
                  $isValid = false; // Chưa đến ngày
                }
                if ($endDate && $now > $endDate) {
                  $isValid = false; // Đã hết hạn
                }
                
                $statusClass = 'secondary';
                $statusText = 'Không hoạt động';
                if ($isActive && $isValid) {
                  $statusClass = 'success';
                  $statusText = 'Đang hoạt động';
                } elseif (!$isValid) {
                  $statusClass = 'warning';
                  $statusText = 'Hết hạn';
                }
              ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><strong class="text-primary"><?= htmlspecialchars($item['code']) ?></strong></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><span class="badge bg-danger"><?= $item['discount_percent'] ?>%</span></td>
                <td><?= $item['start_date'] ? date('d/m/Y', strtotime($item['start_date'])) : '-' ?></td>
                <td><?= $item['end_date'] ? date('d/m/Y', strtotime($item['end_date'])) : '-' ?></td>
                <td>
                  <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=discounts-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=discounts-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa mã khuyến mại này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">Chưa có mã khuyến mại nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

