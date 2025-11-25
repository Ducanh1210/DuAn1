<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý phòng chiếu</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=rooms-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm phòng mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Rạp</th>
              <th>Mã phòng</th>
              <th>Tên phòng</th>
              <th>Số ghế</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><strong><?= htmlspecialchars($item['cinema_name'] ?? 'N/A') ?></strong></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($item['room_code'] ?? 'N/A') ?></span></td>
                <td><?= htmlspecialchars($item['name'] ?? 'N/A') ?></td>
                <td>
                  <?php 
                  // Hiển thị số ghế thực tế từ bảng seats
                  $actualSeatCount = $item['actual_seat_count'] ?? 0;
                  $seatCount = $item['seat_count'] ?? 0;
                  
                  // Nếu số ghế thực tế khác với số ghế trong phòng, hiển thị cả 2
                  if ($actualSeatCount != $seatCount) {
                      echo '<span class="text-primary fw-bold">' . number_format($actualSeatCount, 0, ',', '.') . '</span>';
                      if ($seatCount > 0) {
                          echo ' <small class="text-muted">(phòng: ' . number_format($seatCount, 0, ',', '.') . ')</small>';
                      }
                  } else {
                      echo number_format($actualSeatCount, 0, ',', '.');
                  }
                  ?> ghế
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=rooms-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=rooms-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=rooms-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này? Lưu ý: Nếu có lịch chiếu thuộc phòng này, bạn cần xóa lịch chiếu trước.')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">Chưa có phòng nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị <?= count($data) ?> / <?= $pagination['total'] ?> phòng (Trang <?= $pagination['currentPage'] ?> / <?= $pagination['totalPages'] ?>)
          </div>
          
          <nav aria-label="Phân trang">
            <ul class="pagination mb-0">
              <!-- Previous -->
              <?php if ($pagination['currentPage'] > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=rooms&page=<?= $pagination['currentPage'] - 1 ?>">
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
                  <a class="page-link" href="<?= BASE_URL ?>?act=rooms&page=1">1</a>
                </li>
                <?php if ($startPage > 2): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
              
              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                  <a class="page-link" href="<?= BASE_URL ?>?act=rooms&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              
              <?php if ($endPage < $pagination['totalPages']): ?>
                <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                <?php endif; ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=rooms&page=<?= $pagination['totalPages'] ?>"><?= $pagination['totalPages'] ?></a>
                </li>
              <?php endif; ?>
              
              <!-- Next -->
              <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                <li class="page-item">
                  <a class="page-link" href="<?= BASE_URL ?>?act=rooms&page=<?= $pagination['currentPage'] + 1 ?>">
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

