<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý Liên hệ</h4>
    </div>
    <div class="card-body">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['success']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <!-- Filter by status -->
      <div class="mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <a href="?act=contacts" class="btn btn-sm <?= !$selectedStatus ? 'btn-primary' : 'btn-outline-primary' ?>">
            Tất cả (<?= array_sum($statusCounts) ?>)
          </a>
          <a href="?act=contacts&status=pending" class="btn btn-sm <?= $selectedStatus == 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">
            Chờ xử lý (<?= $statusCounts['pending'] ?>)
          </a>
          <a href="?act=contacts&status=processing" class="btn btn-sm <?= $selectedStatus == 'processing' ? 'btn-info' : 'btn-outline-info' ?>">
            Đang xử lý (<?= $statusCounts['processing'] ?>)
          </a>
          <a href="?act=contacts&status=resolved" class="btn btn-sm <?= $selectedStatus == 'resolved' ? 'btn-success' : 'btn-outline-success' ?>">
            Đã xử lý (<?= $statusCounts['resolved'] ?>)
          </a>
          <a href="?act=contacts&status=closed" class="btn btn-sm <?= $selectedStatus == 'closed' ? 'btn-secondary' : 'btn-outline-secondary' ?>">
            Đã đóng (<?= $statusCounts['closed'] ?>)
          </a>
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
              <th>Chủ đề</th>
              <th>Trạng thái</th>
              <th>Ngày gửi</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><strong><?= htmlspecialchars($item['name'] ?? 'N/A') ?></strong></td>
                <td><?= htmlspecialchars($item['email'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($item['phone'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($item['subject'] ?? 'N/A') ?></td>
                <td>
                  <?php
                  $statusColors = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'resolved' => 'success',
                    'closed' => 'secondary'
                  ];
                  $statusLabels = [
                    'pending' => 'Chờ xử lý',
                    'processing' => 'Đang xử lý',
                    'resolved' => 'Đã xử lý',
                    'closed' => 'Đã đóng'
                  ];
                  $status = $item['status'] ?? 'pending';
                  $color = $statusColors[$status] ?? 'secondary';
                  $label = $statusLabels[$status] ?? ucfirst($status);
                  ?>
                  <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=contacts-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=contacts-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=contacts-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">Chưa có liên hệ nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị <?= count($data) ?> / <?= $pagination['total'] ?> liên hệ (Trang <?= $pagination['currentPage'] ?> / <?= $pagination['totalPages'] ?>)
          </div>
          
          <nav aria-label="Phân trang">
            <ul class="pagination mb-0">
              <!-- Previous -->
              <?php if ($pagination['currentPage'] > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $pagination['currentPage'] - 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                    <i class="bi bi-chevron-left"></i> Trước
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <span class="page-link"><i class="bi bi-chevron-left"></i> Trước</span>
                </li>
              <?php endif; ?>
              
              <!-- Page numbers -->
              <?php
              $startPage = max(1, $pagination['currentPage'] - 2);
              $endPage = min($pagination['totalPages'], $pagination['currentPage'] + 2);
              
              if ($startPage > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=1<?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">1</a>
                </li>
                <?php if ($startPage > 2): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
              
              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $i ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              
              <?php if ($endPage < $pagination['totalPages']): ?>
                <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $pagination['totalPages'] ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>"><?= $pagination['totalPages'] ?></a>
                </li>
              <?php endif; ?>
              
              <!-- Next -->
              <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $pagination['currentPage'] + 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?>">
                    Sau <i class="bi bi-chevron-right"></i>
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <span class="page-link">Sau <i class="bi bi-chevron-right"></i></span>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

