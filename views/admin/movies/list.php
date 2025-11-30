<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý phim</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=movies-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm phim mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Poster</th>
              <th>Tên phim</th>
              <th>Thể loại</th>
              <th>Thời lượng (phút)</th>
              <th>Ngày phát hành</th>
              <th>Ngày kết thúc</th>
              <th>Định dạng</th>
              <th>Trailer</th>
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
                  <?php if (!empty($item['image'])): ?>
                    <img src="<?= BASE_URL . '/' . $item['image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>" style="max-width: 100px; height: auto; border-radius: 4px;">
                  <?php else: ?>
                    <span class="text-muted">Chưa có ảnh</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['genre_name'] ?? 'N/A') ?></td>
                <td><?= $item['duration'] ?? 'N/A' ?></td>
                <td><?= $item['release_date'] ? date('d/m/Y', strtotime($item['release_date'])) : 'N/A' ?></td>
                <td><?= $item['end_date'] ? date('d/m/Y', strtotime($item['end_date'])) : 'N/A' ?></td>
                <td><?= htmlspecialchars($item['format'] ?? 'N/A') ?></td>
                <td>
                  <?php if (!empty($item['trailer'])): ?>
                    <a href="<?= htmlspecialchars($item['trailer']) ?>" target="_blank" class="btn btn-sm btn-danger" title="Xem trailer">
                      <i class="bi bi-play-circle"></i> Trailer
                    </a>
                  <?php else: ?>
                    <span class="text-muted">Chưa có</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php
                  $today = date('Y-m-d');
                  $releaseDate = $item['release_date'] ? date('Y-m-d', strtotime($item['release_date'])) : null;
                  $endDate = $item['end_date'] ? date('Y-m-d', strtotime($item['end_date'])) : null;
                  
                  // Kiểm tra trạng thái dựa trên ngày
                  if ($releaseDate && $today < $releaseDate) {
                    // Chưa đến ngày phát hành
                    echo '<span class="badge bg-warning text-dark">Sắp chiếu</span>';
                  } elseif ($item['status'] == 'active' && $releaseDate && $today >= $releaseDate && ($endDate === null || $today <= $endDate)) {
                    // Đang trong thời gian chiếu
                    echo '<span class="badge bg-success">Đang chiếu</span>';
                  } else {
                    // Đã hết hạn hoặc ngừng chiếu
                    echo '<span class="badge bg-danger">Ngừng chiếu</span>';
                  }
                  ?>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=movies-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=movies-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=movies-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa phim này?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="11" class="text-center text-muted py-4">Chưa có phim nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination)): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị <?= count($data) ?> / <?= $pagination['total'] ?> phim (Trang <?= $pagination['currentPage'] ?> / <?= $pagination['totalPages'] ?>)
          </div>
          
          <?php if ($pagination['totalPages'] > 1): ?>
            <nav aria-label="Phân trang">
              <ul class="pagination mb-0">
                <!-- Previous -->
                <?php if ($pagination['currentPage'] > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=movies-list&page=<?= $pagination['currentPage'] - 1 ?>">
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
                    <a class="page-link" href="<?= BASE_URL ?>?act=movies-list&page=1">1</a>
                  </li>
                  <?php if ($startPage > 2): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                  <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>?act=movies-list&page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                
                <?php if ($endPage < $pagination['totalPages']): ?>
                  <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=movies-list&page=<?= $pagination['totalPages'] ?>"><?= $pagination['totalPages'] ?></a>
                  </li>
                <?php endif; ?>
                
                <!-- Next -->
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=movies-list&page=<?= $pagination['currentPage'] + 1 ?>">
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
