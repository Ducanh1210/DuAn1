<?php
// COMMENTS/LIST.PHP - TRANG QUẢN LÝ BÌNH LUẬN ĐÁNH GIÁ ADMIN
// Chức năng: Hiển thị danh sách bình luận đánh giá phim với bộ lọc (rạp, phim, tìm kiếm)
// Biến từ controller: $data (danh sách bình luận), $stats (thống kê), $cinemas (danh sách rạp), $movies (danh sách phim), $cinemaFilter, $movieFilter, $searchKeyword
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề trang -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý bình luận đánh giá</h4>
    </div>
    <div class="card-body">
      <!-- Card thống kê: tổng số bình luận -->
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <h5 class="card-title">Tổng số bình luận</h5>
              <!-- Hiển thị tổng số bình luận: $stats['total'] từ controller -->
              <h3><?= $stats['total'] ?></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Form bộ lọc và tìm kiếm -->
      <div class="row mb-3">
        <div class="col-md-12">
          <form method="GET" action="" class="d-flex gap-2 flex-wrap">
            <!-- Hidden input: giữ nguyên action -->
            <input type="hidden" name="act" value="comments">
            <!-- Dropdown lọc theo rạp: chỉ hiển thị nếu là admin và có danh sách rạp -->
            <?php if (isset($isAdmin) && $isAdmin && !empty($cinemas)): ?>
              <select name="cinema_id" class="form-select" style="max-width: 200px;">
                <option value="">Tất cả rạp</option>
                <!-- Vòng lặp: tạo option cho mỗi rạp -->
                <?php foreach ($cinemas as $cinema): ?>
                  <!-- selected: đánh dấu rạp đang được chọn -->
                  <option value="<?= $cinema['id'] ?>" <?= ($cinemaFilter ?? null) == $cinema['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cinema['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
            <!-- Dropdown lọc theo phim -->
            <select name="movie_id" class="form-select" style="max-width: 250px;">
              <option value="">Tất cả phim</option>
              <!-- Vòng lặp: tạo option cho mỗi phim -->
              <?php if (!empty($movies)): ?>
                <?php foreach ($movies as $movie): ?>
                  <!-- selected: đánh dấu phim đang được chọn -->
                  <option value="<?= $movie['id'] ?>" <?= $movieFilter == $movie['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($movie['title']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <!-- Input tìm kiếm: tìm theo nội dung, tên người dùng hoặc tên phim -->
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo nội dung, tên người dùng hoặc tên phim..." value="<?= htmlspecialchars($searchKeyword) ?>">
            <!-- Nút submit form tìm kiếm -->
            <button type="submit" class="btn btn-outline-primary">
              <i class="bi bi-search"></i> Tìm
            </button>
            <!-- Nút xóa bộ lọc: chỉ hiển thị nếu có filter đang active -->
            <?php if ($movieFilter || $searchKeyword || ($cinemaFilter ?? null)): ?>
              <a href="<?= BASE_URL ?>?act=comments" class="btn btn-outline-secondary">
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
              <th>Người dùng</th>
              <th>Phim</th>
              <?php if (isset($isAdmin) && $isAdmin): ?>
                <th>Rạp</th>
              <?php endif; ?>
              <th>Đánh giá</th>
              <th>Nội dung</th>
              <th>Ngày tạo</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td>
                  <div>
                    <strong><?= htmlspecialchars($item['user_name'] ?? 'Người dùng đã xóa') ?></strong>
                    <?php if (!empty($item['user_email'])): ?>
                      <br><small class="text-muted"><?= htmlspecialchars($item['user_email']) ?></small>
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <?php if (!empty($item['movie_title'])): ?>
                    <?= htmlspecialchars($item['movie_title']) ?>
                  <?php else: ?>
                    <span class="text-muted">Phim đã xóa</span>
                  <?php endif; ?>
                </td>
                <?php if (isset($isAdmin) && $isAdmin): ?>
                  <td>
                    <?php if (!empty($item['cinema_name'])): ?>
                      <span class="badge bg-info"><?= htmlspecialchars($item['cinema_name']) ?></span>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>
                <td>
                  <?php if (!empty($item['rating'])): ?>
                    <div class="d-flex align-items-center">
                      <span class="text-warning me-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                          <?php if ($i <= $item['rating']): ?>
                            <i class="bi bi-star-fill"></i>
                          <?php else: ?>
                            <i class="bi bi-star"></i>
                          <?php endif; ?>
                        <?php endfor; ?>
                      </span>
                      <span class="ms-1">(<?= $item['rating'] ?>/5)</span>
                    </div>
                  <?php else: ?>
                    <span class="text-muted">Chưa đánh giá</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($item['content'] ?? '') ?>">
                    <?= htmlspecialchars($item['content'] ?? 'Không có nội dung') ?>
                  </div>
                </td>
                <td><?= $item['created_at'] ? date('d/m/Y H:i', strtotime($item['created_at'])) : 'N/A' ?></td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <?php if (!empty($item['status']) && $item['status'] === 'hidden'): ?>
                      <span class="badge bg-secondary" title="Bình luận đã bị ẩn">
                        <i class="bi bi-eye-slash"></i> Đã ẩn
                      </span>
                    <?php endif; ?>
                    <div class="btn-group" role="group">
                      <a href="<?= BASE_URL ?>?act=comments-show&id=<?= $item['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="<?= BASE_URL ?>?act=comments-delete&id=<?= $item['id'] ?>" 
                         class="btn btn-sm <?= (!empty($item['status']) && $item['status'] === 'hidden') ? 'btn-success' : 'btn-warning' ?>" 
                         title="<?= (!empty($item['status']) && $item['status'] === 'hidden') ? 'Hiện lại' : 'Ẩn' ?>"
                         onclick="return confirm('<?= (!empty($item['status']) && $item['status'] === 'hidden') ? 'Bạn có chắc chắn muốn hiện lại bình luận này?' : 'Bạn có chắc chắn muốn ẩn bình luận này?' ?>')">
                        <i class="bi <?= (!empty($item['status']) && $item['status'] === 'hidden') ? 'bi-eye' : 'bi-eye-slash' ?>"></i>
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="<?= (isset($isAdmin) && $isAdmin) ? '8' : '7' ?>" class="text-center text-muted py-4">Chưa có bình luận nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination)): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị <?= count($data) ?> / <?= $pagination['total'] ?> bình luận (Trang <?= $pagination['currentPage'] ?> / <?= $pagination['totalPages'] ?>)
          </div>
          
          <?php if ($pagination['totalPages'] > 1): ?>
            <nav aria-label="Phân trang">
              <ul class="pagination mb-0">
                <!-- Previous -->
                <?php if ($pagination['currentPage'] > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=comments&page=<?= $pagination['currentPage'] - 1 ?>">
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
                    <a class="page-link" href="<?= BASE_URL ?>?act=comments&page=1">1</a>
                  </li>
                  <?php if ($startPage > 2): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                  <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>?act=comments&page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                
                <?php if ($endPage < $pagination['totalPages']): ?>
                  <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=comments&page=<?= $pagination['totalPages'] ?>"><?= $pagination['totalPages'] ?></a>
                  </li>
                <?php endif; ?>
                
                <!-- Next -->
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=comments&page=<?= $pagination['currentPage'] + 1 ?>">
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

