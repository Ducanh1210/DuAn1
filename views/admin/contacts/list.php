<?php
// CONTACTS/LIST.PHP - TRANG QUẢN LÝ LIÊN HỆ ADMIN
// Chức năng: Hiển thị danh sách liên hệ từ khách hàng với bộ lọc (trạng thái, rạp)
// Biến từ controller: $data (danh sách liên hệ), $statusCounts (số lượng theo trạng thái), $selectedStatus, $cinemaFilter, $currentCinemaName
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và tên rạp (nếu là staff/manager) -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Quản lý Liên hệ</h4>
        <!-- Hiển thị tên rạp nếu là staff hoặc manager -->
        <?php if ((isset($isStaff) && $isStaff || isset($isManager) && $isManager) && !empty($currentCinemaName)): ?>
          <small class="text-muted">
            <i class="bi bi-building"></i> Rạp: <strong><?= htmlspecialchars($currentCinemaName) ?></strong>
          </small>
        <?php endif; ?>
      </div>
    </div>
    <div class="card-body">
      <!-- Hiển thị lỗi từ session -->
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Xóa lỗi sau khi hiển thị -->
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
      
      <!-- Hiển thị thông báo thành công từ session -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['success']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Xóa thông báo sau khi hiển thị -->
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <!-- Card bộ lọc: lọc theo trạng thái và rạp -->
      <div class="card mb-4 shadow-sm border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="card-body p-3">
          <div class="row g-3 align-items-end">
            <!-- Bộ lọc theo trạng thái: các nút filter dạng badge -->
            <div class="col-12">
              <label class="form-label fw-bold text-muted mb-2" style="font-size: 0.875rem;">
                <i class="bi bi-funnel"></i> Lọc theo trạng thái:
              </label>
              <div class="d-flex flex-wrap gap-2">
                <!-- Nút "Tất cả": active nếu không có $selectedStatus -->
                <a href="?act=contacts<?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>" 
                   class="btn btn-sm <?= !$selectedStatus ? 'btn-primary shadow-sm' : 'btn-outline-primary' ?>"
                   style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">
                  <i class="bi bi-list-ul"></i> Tất cả (<?= array_sum($statusCounts) ?>) <!-- array_sum: tổng số lượng tất cả trạng thái -->
                </a>
                <!-- Nút "Chờ xử lý": active nếu $selectedStatus === 'pending' -->
                <a href="?act=contacts&status=pending<?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>" 
                   class="btn btn-sm <?= $selectedStatus == 'pending' ? 'btn-warning shadow-sm' : 'btn-outline-warning' ?>"
                   style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">
                  <i class="bi bi-clock"></i> Chờ xử lý (<?= $statusCounts['pending'] ?>) <!-- Số lượng liên hệ chờ xử lý -->
                </a>
                <!-- Nút "Đang xử lý": active nếu $selectedStatus === 'processing' -->
                <a href="?act=contacts&status=processing<?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>" 
                   class="btn btn-sm <?= $selectedStatus == 'processing' ? 'btn-info shadow-sm' : 'btn-outline-info' ?>"
                   style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">
                  <i class="bi bi-arrow-repeat"></i> Đang xử lý (<?= $statusCounts['processing'] ?>)
                </a>
                <a href="?act=contacts&status=resolved<?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>" 
                   class="btn btn-sm <?= $selectedStatus == 'resolved' ? 'btn-success shadow-sm' : 'btn-outline-success' ?>"
                   style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">
                  <i class="bi bi-check-circle"></i> Đã xử lý (<?= $statusCounts['resolved'] ?>)
                </a>
                <a href="?act=contacts&status=closed<?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>" 
                   class="btn btn-sm <?= $selectedStatus == 'closed' ? 'btn-secondary shadow-sm' : 'btn-outline-secondary' ?>"
                   style="border-radius: 20px; padding: 6px 16px; font-weight: 500;">
                  <i class="bi bi-lock"></i> Đã đóng (<?= $statusCounts['closed'] ?>)
                </a>
              </div>
            </div>
            
            <!-- Cinema Filter (chỉ admin) -->
            <?php if (isset($isAdmin) && $isAdmin && !empty($cinemas)): ?>
            <div class="col-12 col-md-auto">
              <form method="GET" action="" class="d-flex gap-2 align-items-end">
                <input type="hidden" name="act" value="contacts">
                <?php if ($selectedStatus): ?>
                  <input type="hidden" name="status" value="<?= htmlspecialchars($selectedStatus) ?>">
                <?php endif; ?>
                <div class="flex-grow-1" style="min-width: 200px;">
                  <label for="cinema_filter" class="form-label fw-bold text-muted mb-2" style="font-size: 0.875rem;">
                    <i class="bi bi-building"></i> Lọc theo rạp:
                  </label>
                  <select name="cinema_id" 
                          id="cinema_filter" 
                          class="form-select form-select-sm shadow-sm" 
                          style="border-radius: 8px; border: 1px solid #dee2e6;"
                          onchange="this.form.submit()">
                    <option value="">Tất cả rạp</option>
                    <?php foreach ($cinemas as $cinema): ?>
                      <option value="<?= $cinema['id'] ?>" <?= ($cinemaFilter ?? null) == $cinema['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cinema['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </form>
            </div>
            <?php endif; ?>
          </div>
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
              <?php if (isset($isAdmin) && $isAdmin): ?>
              <th>Rạp</th>
              <?php endif; ?>
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
                <?php if (isset($isAdmin) && $isAdmin): ?>
                <td>
                  <?php if (!empty($item['cinema_id']) && !empty($item['cinema_name'])): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($item['cinema_name']) ?></span>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <?php endif; ?>
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
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                    <a href="<?= BASE_URL ?>?act=contacts-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="<?= (isset($isAdmin) && $isAdmin) ? '9' : '8' ?>" class="text-center text-muted py-4">Chưa có liên hệ nào</td>
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
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $pagination['currentPage'] - 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?><?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>">
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
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=1<?= $selectedStatus ? '&status=' . $selectedStatus : '' ?><?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>">1</a>
                </li>
                <?php if ($startPage > 2): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
              
              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $i ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?><?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              
              <?php if ($endPage < $pagination['totalPages']): ?>
                <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $pagination['totalPages'] ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?><?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>"><?= $pagination['totalPages'] ?></a>
                </li>
              <?php endif; ?>
              
              <!-- Next -->
              <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=contacts&page=<?= $pagination['currentPage'] + 1 ?><?= $selectedStatus ? '&status=' . $selectedStatus : '' ?><?= $cinemaFilter ? '&cinema_id=' . $cinemaFilter : '' ?>">
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

