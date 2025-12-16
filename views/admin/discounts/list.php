<?php
// DISCOUNTS/LIST.PHP - TRANG QUẢN LÝ MÃ KHUYẾN MẠI ADMIN
// Chức năng: Hiển thị danh sách mã khuyến mại với trạng thái (sắp diễn ra, đang hoạt động, hết hạn)
// Biến từ controller: $data (danh sách mã khuyến mại)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và nút thêm mã khuyến mại mới -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý mã khuyến mại</h4>
      <div>
        <!-- Link đến trang tạo mã khuyến mại mới -->
        <a href="<?= BASE_URL ?>?act=discounts-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm mã khuyến mại mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Bảng danh sách mã khuyến mại -->
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã khuyến mại</th>
              <th>Tiêu đề</th>
              <th>Giảm giá</th>
              <th>Lượt sử dụng</th>
              <th>Ngày bắt đầu</th>
              <th>Ngày kết thúc</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <!-- Kiểm tra nếu có dữ liệu mã khuyến mại -->
            <?php if (!empty($data)): ?>
              <!-- Vòng lặp: duyệt qua từng mã khuyến mại -->
              <?php foreach ($data as $item): ?>
              <?php
                // Lấy ngày hiện tại để so sánh
                $now = date('Y-m-d');
                $startDate = $item['start_date'] ?? null; // Ngày bắt đầu
                $endDate = $item['end_date'] ?? null; // Ngày kết thúc
                $isActive = $item['status'] === 'active'; // Kiểm tra status có phải 'active' không
                
                // Khởi tạo biến trạng thái mặc định
                $statusClass = 'secondary'; // Class màu badge mặc định
                $statusText = 'Không hoạt động'; // Text hiển thị mặc định
                
                // Logic xác định trạng thái dựa trên ngày và status
                if ($isActive) {
                  if ($startDate && $now < $startDate) {
                    // Chưa đến ngày bắt đầu -> Sắp diễn ra
                    $statusClass = 'info'; // Màu xanh dương
                    $statusText = 'Sắp diễn ra';
                  } elseif ($endDate && $now > $endDate) {
                    // Đã quá ngày kết thúc -> Hết hạn
                    $statusClass = 'warning'; // Màu vàng
                    $statusText = 'Hết hạn';
                  } else {
                    // Đang trong thời gian hiệu lực -> Đang hoạt động
                    $statusClass = 'success'; // Màu xanh lá
                    $statusText = 'Đang hoạt động';
                  }
                } else {
                  // Status = inactive -> Không hoạt động
                  $statusClass = 'secondary'; // Màu xám
                  $statusText = 'Không hoạt động';
                }
              ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><strong class="text-primary"><?= htmlspecialchars($item['code']) ?></strong></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><span class="badge bg-danger"><?= $item['discount_percent'] ?>%</span></td>
                <?php
                  $usageLimit = isset($item['usage_limit']) ? (int)$item['usage_limit'] : null;
                  $usageUsed = (int)($item['usage_used'] ?? 0);
                  $usageLabel = $usageLimit === null
                    ? $usageUsed . ' / ∞'
                    : $usageUsed . ' / ' . $usageLimit;
                  $usageClass = ($usageLimit !== null && $usageUsed >= $usageLimit) ? 'danger' : 'primary';
                ?>
                <td><span class="badge bg-<?= $usageClass ?>"><?= $usageLabel ?></span></td>
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
                <td colspan="9" class="text-center text-muted py-4">Chưa có mã khuyến mại nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

