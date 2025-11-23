<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý lịch chiếu</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=showtimes-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm lịch chiếu mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Filter form -->
      <div class="mb-3">
        <form method="get" action="" class="row g-3">
          <input type="hidden" name="act" value="showtimes">
          <div class="col-md-3">
            <label for="date" class="form-label">Lọc theo ngày:</label>
            <input type="date" name="date" id="date" class="form-control" value="<?= htmlspecialchars($selectedDate ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label for="status" class="form-label">Lọc theo trạng thái:</label>
            <select name="status" id="status" class="form-select">
              <option value="">-- Tất cả --</option>
              <option value="upcoming" <?= ($selectedStatus ?? '') === 'upcoming' ? 'selected' : '' ?>>Sắp chiếu</option>
              <option value="showing" <?= ($selectedStatus ?? '') === 'showing' ? 'selected' : '' ?>>Đang chiếu</option>
              <option value="ended" <?= ($selectedStatus ?? '') === 'ended' ? 'selected' : '' ?>>Dừng</option>
            </select>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary me-2">
              <i class="bi bi-funnel"></i> Lọc
            </button>
            <a href="<?= BASE_URL ?>?act=showtimes" class="btn btn-outline-secondary">
              <i class="bi bi-x-circle"></i> Xóa bộ lọc
            </a>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Phim</th>
              <th>Rạp</th>
              <th>Phòng</th>
              <th>Ngày chiếu</th>
              <th>Giờ bắt đầu</th>
              <th>Giờ kết thúc</th>
              <th>Định dạng</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td>
                  <div class="d-flex align-items-center">
                    <?php if (!empty($item['movie_image'])): ?>
                      <img src="<?= BASE_URL . '/' . $item['movie_image'] ?>" 
                           alt="<?= htmlspecialchars($item['movie_title'] ?? '') ?>" 
                           style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                    <?php endif; ?>
                    <div>
                      <strong><?= htmlspecialchars($item['movie_title'] ?? 'N/A') ?></strong>
                      <?php if (!empty($item['movie_duration'])): ?>
                        <br><small class="text-muted"><?= $item['movie_duration'] ?> phút</small>
                      <?php endif; ?>
                    </div>
                  </div>
                </td>
                <td><?= htmlspecialchars($item['cinema_name'] ?? 'N/A') ?></td>
                <td>
                  <?= htmlspecialchars($item['room_name'] ?? 'N/A') ?>
                  <?php if (!empty($item['room_code'])): ?>
                    <br><small class="text-muted">(<?= htmlspecialchars($item['room_code']) ?>)</small>
                  <?php endif; ?>
                </td>
                <td><?= $item['show_date'] ? date('d/m/Y', strtotime($item['show_date'])) : 'N/A' ?></td>
                <td><?= $item['start_time'] ? date('H:i', strtotime($item['start_time'])) : 'N/A' ?></td>
                <td><?= $item['end_time'] ? date('H:i', strtotime($item['end_time'])) : 'N/A' ?></td>
                <td>
                  <span class="badge bg-info"><?= htmlspecialchars($item['format'] ?? '2D') ?></span>
                </td>
                <td>
                  <?php
                  $status = $item['status'] ?? 'ended';
                  $statusText = '';
                  $statusClass = '';
                  switch ($status) {
                    case 'upcoming':
                      $statusText = 'Sắp chiếu';
                      $statusClass = 'bg-primary';
                      break;
                    case 'showing':
                      $statusText = 'Đang chiếu';
                      $statusClass = 'bg-success';
                      break;
                    case 'ended':
                      $statusText = 'Dừng';
                      $statusClass = 'bg-secondary';
                      break;
                    default:
                      $statusText = 'Dừng';
                      $statusClass = 'bg-secondary';
                  }
                  ?>
                  <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=showtimes-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=showtimes-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=showtimes-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa lịch chiếu này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center text-muted py-4">
                  <i class="bi bi-calendar-x" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                  Chưa có lịch chiếu nào
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination)): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị <?= count($data) ?> / <?= $pagination['total'] ?> lịch chiếu (Trang <?= $pagination['currentPage'] ?> / <?= $pagination['totalPages'] ?>)
          </div>
          
          <?php if ($pagination['totalPages'] > 1): ?>
            <nav aria-label="Phân trang">
              <ul class="pagination mb-0">
                <!-- Previous -->
                <?php if ($pagination['currentPage'] > 1): ?>
                  <li class="page-item">
                    <?php
                    $prevUrl = BASE_URL . '?act=showtimes&page=' . ($pagination['currentPage'] - 1);
                    if (!empty($selectedDate)) {
                      $prevUrl .= '&date=' . urlencode($selectedDate);
                    }
                    if (!empty($selectedStatus)) {
                      $prevUrl .= '&status=' . urlencode($selectedStatus);
                    }
                    ?>
                    <a class="page-link" href="<?= $prevUrl ?>">
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
                  <?php
                  $firstUrl = BASE_URL . '?act=showtimes&page=1';
                  if (!empty($selectedDate)) {
                    $firstUrl .= '&date=' . urlencode($selectedDate);
                  }
                  if (!empty($selectedStatus)) {
                    $firstUrl .= '&status=' . urlencode($selectedStatus);
                  }
                  ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $firstUrl ?>">1</a>
                  </li>
                  <?php if ($startPage > 2): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                  <?php
                  $pageUrl = BASE_URL . '?act=showtimes&page=' . $i;
                  if (!empty($selectedDate)) {
                    $pageUrl .= '&date=' . urlencode($selectedDate);
                  }
                  if (!empty($selectedStatus)) {
                    $pageUrl .= '&status=' . urlencode($selectedStatus);
                  }
                  ?>
                  <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $pageUrl ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                
                <?php if ($endPage < $pagination['totalPages']): ?>
                  <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                  <?php
                  $lastUrl = BASE_URL . '?act=showtimes&page=' . $pagination['totalPages'];
                  if (!empty($selectedDate)) {
                    $lastUrl .= '&date=' . urlencode($selectedDate);
                  }
                  if (!empty($selectedStatus)) {
                    $lastUrl .= '&status=' . urlencode($selectedStatus);
                  }
                  ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $lastUrl ?>"><?= $pagination['totalPages'] ?></a>
                  </li>
                <?php endif; ?>
                
                <!-- Next -->
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                  <?php
                  $nextUrl = BASE_URL . '?act=showtimes&page=' . ($pagination['currentPage'] + 1);
                  if (!empty($selectedDate)) {
                    $nextUrl .= '&date=' . urlencode($selectedDate);
                  }
                  if (!empty($selectedStatus)) {
                    $nextUrl .= '&status=' . urlencode($selectedStatus);
                  }
                  ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $nextUrl ?>">
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
          <?php else: ?>
            <div class="text-muted">
              <span class="badge bg-secondary">Trang 1 / 1</span>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

