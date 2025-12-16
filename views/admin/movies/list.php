<?php
// MOVIES/LIST.PHP - TRANG QUẢN LÝ PHIM ADMIN
// Chức năng: Hiển thị danh sách phim với bộ lọc (rạp, trạng thái, tìm kiếm)
// Biến từ controller: $data (danh sách phim), $cinemas (danh sách rạp), $cinemaFilter, $statusFilter, $searchKeyword
require_once __DIR__ . '/../../../commons/auth.php'; // Include file auth để dùng hàm isAdmin()
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và nút thêm phim mới -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý phim</h4>
      <div>
        <!-- Link đến trang tạo phim mới -->
        <a href="<?= BASE_URL ?>?act=movies-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm phim mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Hiển thị thông báo lỗi từ session -->
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Xóa thông báo lỗi sau khi hiển thị để không hiện lại -->
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
      
      <!-- Hiển thị thông báo thành công từ session -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Xóa thông báo thành công sau khi hiển thị -->
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>
      
      <!-- Form bộ lọc và tìm kiếm -->
      <div class="row mb-3">
        <div class="col-md-12">
          <form method="GET" action="" class="d-flex gap-2 flex-wrap align-items-end">
            <!-- Hidden input: giữ nguyên action hiện tại -->
            <input type="hidden" name="act" value="/">
            <?php 
            // Kiểm tra quyền admin: chỉ admin mới thấy filter theo rạp
            $isAdmin = isAdmin();
            ?>
            <!-- Dropdown lọc theo rạp: chỉ hiển thị nếu là admin và có danh sách rạp -->
            <?php if ($isAdmin && !empty($cinemas)): ?>
              <div style="min-width: 200px;">
                <label for="cinema_id" class="form-label small mb-1">Lọc theo rạp:</label>
                <select name="cinema_id" id="cinema_id" class="form-select form-select-sm">
                  <option value="">Tất cả rạp</option>
                  <!-- Vòng lặp: tạo option cho mỗi rạp -->
                  <?php foreach ($cinemas as $cinema): ?>
                    <!-- selected: đánh dấu rạp đang được chọn -->
                    <option value="<?= $cinema['id'] ?>" <?= ($cinemaFilter ?? null) == $cinema['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cinema['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <!-- Dropdown lọc theo trạng thái: active (đang chiếu) hoặc inactive (ngừng chiếu) -->
            <div style="min-width: 180px;">
              <label for="status" class="form-label small mb-1">Lọc theo trạng thái:</label>
              <select name="status" id="status" class="form-select form-select-sm">
                <option value="">Tất cả trạng thái</option>
                <!-- Option đang chiếu: selected nếu $statusFilter === 'active' -->
                <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>Đang chiếu</option>
                <!-- Option ngừng chiếu: selected nếu $statusFilter === 'inactive' -->
                <option value="inactive" <?= ($statusFilter ?? '') === 'inactive' ? 'selected' : '' ?>>Ngừng chiếu</option>
              </select>
            </div>
            <!-- Input tìm kiếm theo tên phim -->
            <div style="flex: 1; min-width: 250px;">
              <label for="search" class="form-label small mb-1">Tìm kiếm theo tên phim:</label>
              <!-- value: hiển thị từ khóa đang tìm (nếu có) -->
              <input type="text" name="search" id="search" class="form-control form-control-sm" 
                     placeholder="Nhập tên phim..." 
                     value="<?= htmlspecialchars($searchKeyword ?? '') ?>">
            </div>
            <div>
              <!-- Nút submit form tìm kiếm -->
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i> Tìm kiếm
              </button>
              <!-- Nút xóa bộ lọc: chỉ hiển thị nếu có filter đang active -->
              <?php if ($searchKeyword || $statusFilter || ($cinemaFilter ?? null)): ?>
                <a href="<?= BASE_URL ?>?act=/" class="btn btn-outline-secondary btn-sm">
                  <i class="bi bi-x-circle"></i> Xóa bộ lọc
                </a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Poster</th>
              <th>Tên phim</th>
              <?php if ($isAdmin): ?>
                <th>Rạp</th>
              <?php endif; ?>
              <th>Thể loại</th>
              <th>Thời lượng (phút)</th>
              <th>Ngày phát hành</th>
              <th>Ngày kết thúc</th>
              <th>Loại</th>
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
                <?php if ($isAdmin): ?>
                  <td>
                    <?php if (!empty($item['cinemas']) && is_array($item['cinemas'])): ?>
                      <?php foreach ($item['cinemas'] as $cinema): ?>
                        <span class="badge bg-info me-1"><?= htmlspecialchars($cinema['name']) ?></span>
                      <?php endforeach; ?>
                    <?php elseif (!empty($item['cinema_name'])): ?>
                      <span class="badge bg-info"><?= htmlspecialchars($item['cinema_name']) ?></span>
                    <?php else: ?>
                      <span class="text-muted">Chưa gán rạp</span>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>
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
                <td colspan="<?= $isAdmin ? '12' : '11' ?>" class="text-center text-muted py-4">Chưa có phim nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if (isset($pagination)): 
        // Tạo query string để giữ lại filter khi chuyển trang
        $queryParams = [];
        if (!empty($searchKeyword)) $queryParams['search'] = $searchKeyword;
        if (!empty($statusFilter)) $queryParams['status'] = $statusFilter;
        if (!empty($cinemaFilter)) $queryParams['cinema_id'] = $cinemaFilter;
        $queryString = !empty($queryParams) ? '&' . http_build_query($queryParams) : '';
      ?>
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
                    <a class="page-link" href="<?= BASE_URL ?>?act=/&page=<?= $pagination['currentPage'] - 1 ?><?= $queryString ?>">
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
                    <a class="page-link" href="<?= BASE_URL ?>?act=/&page=1<?= $queryString ?>">1</a>
                  </li>
                  <?php if ($startPage > 2): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                  <li class="page-item <?= $i == $pagination['currentPage'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>?act=/&page=<?= $i ?><?= $queryString ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                
                <?php if ($endPage < $pagination['totalPages']): ?>
                  <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                    <li class="page-item disabled">
                      <span class="page-link">...</span>
                    </li>
                  <?php endif; ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=/&page=<?= $pagination['totalPages'] ?><?= $queryString ?>"><?= $pagination['totalPages'] ?></a>
                  </li>
                <?php endif; ?>
                
                <!-- Next -->
                <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL ?>?act=/&page=<?= $pagination['currentPage'] + 1 ?><?= $queryString ?>">
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
