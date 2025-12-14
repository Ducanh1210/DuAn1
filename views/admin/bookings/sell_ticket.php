<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h4 class="mb-0">
        <i class="bi bi-cart-plus"></i> Bán Vé
      </h4>
    </div>
    <div class="card-body">
      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($errors['general']) ?>
        </div>
      <?php else: ?>
        
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-info">
              <i class="bi bi-info-circle"></i> 
              <strong>Hướng dẫn:</strong> Chọn lịch chiếu để bán vé cho khách hàng. Bạn chỉ có thể bán vé cho các suất chiếu của rạp được gán.
            </div>
          </div>
        </div>

        <?php if (empty($showtimes)): ?>
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> 
            Hiện tại không có lịch chiếu nào sẵn sàng để bán vé. Vui lòng liên hệ quản lý để thêm lịch chiếu.
          </div>
        <?php else: ?>
          
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Phim</th>
                  <th>Rạp</th>
                  <th>Phòng</th>
                  <th>Ngày chiếu</th>
                  <th>Giờ bắt đầu</th>
                  <th>Giờ kết thúc</th>
                  <th>Loại</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($showtimes as $showtime): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <?php if (!empty($showtime['movie_image'])): ?>
                          <img src="<?= BASE_URL . '/' . $showtime['movie_image'] ?>" 
                               alt="<?= htmlspecialchars($showtime['movie_title'] ?? '') ?>" 
                               style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                        <?php endif; ?>
                        <div>
                          <strong><?= htmlspecialchars($showtime['movie_title'] ?? 'N/A') ?></strong>
                          <?php if (!empty($showtime['movie_duration'])): ?>
                            <br><small class="text-muted"><?= $showtime['movie_duration'] ?> phút</small>
                          <?php endif; ?>
                        </div>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($showtime['cinema_name'] ?? 'N/A') ?></td>
                    <td>
                      <?= htmlspecialchars($showtime['room_name'] ?? 'N/A') ?>
                      <?php if (!empty($showtime['room_code'])): ?>
                        <br><small class="text-muted">(<?= htmlspecialchars($showtime['room_code']) ?>)</small>
                      <?php endif; ?>
                    </td>
                    <td><?= $showtime['show_date'] ? date('d/m/Y', strtotime($showtime['show_date'])) : 'N/A' ?></td>
                    <td><?= $showtime['start_time'] ? date('H:i', strtotime($showtime['start_time'])) : 'N/A' ?></td>
                    <td><?= $showtime['end_time'] ? date('H:i', strtotime($showtime['end_time'])) : 'N/A' ?></td>
                    <td>
                      <span class="badge bg-info"><?= htmlspecialchars($showtime['format'] ?? '2D') ?></span>
                    </td>
                    <td>
                      <?php
                      $status = $showtime['status'] ?? 'ended';
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
                      <a href="<?= BASE_URL ?>?act=datve&showtime_id=<?= $showtime['id'] ?>" 
                         class="btn btn-sm btn-primary">
                        <i class="bi bi-cart-plus"></i> Bán Vé
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>
</div>

