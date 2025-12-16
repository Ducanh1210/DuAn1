<?php
// BOOKINGS/SELL_TICKET.PHP - TRANG BÁN VÉ CHO NHÂN VIÊN/STAFF
// Chức năng: Hiển thị danh sách lịch chiếu để nhân viên bán vé cho khách hàng tại quầy
// Biến từ controller: $showtimes (danh sách lịch chiếu của rạp được gán), $errors (lỗi nếu có)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề trang bán vé -->
    <div class="card-header">
      <h4 class="mb-0">
        <i class="bi bi-cart-plus"></i> Bán Vé
      </h4>
    </div>
    <div class="card-body">
      <!-- Hiển thị lỗi nếu có: $errors['general'] từ controller -->
      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($errors['general']) ?>
        </div>
      <?php else: ?>
        
        <!-- Thông báo hướng dẫn cho nhân viên -->
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-info">
              <i class="bi bi-info-circle"></i> 
              <strong>Hướng dẫn:</strong> Chọn lịch chiếu để bán vé cho khách hàng. Bạn chỉ có thể bán vé cho các suất chiếu của rạp được gán.
            </div>
          </div>
        </div>

        <!-- Kiểm tra nếu không có lịch chiếu nào -->
        <?php if (empty($showtimes)): ?>
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> 
            Hiện tại không có lịch chiếu nào sẵn sàng để bán vé. Vui lòng liên hệ quản lý để thêm lịch chiếu.
          </div>
        <?php else: ?>
          
          <!-- Bảng danh sách lịch chiếu -->
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
                <!-- Vòng lặp: duyệt qua từng lịch chiếu -->
                <?php foreach ($showtimes as $showtime): ?>
                  <tr>
                    <!-- Cột phim: hiển thị ảnh và tên phim -->
                    <td>
                      <div class="d-flex align-items-center">
                        <!-- Ảnh phim: nếu có thì hiển thị -->
                        <?php if (!empty($showtime['movie_image'])): ?>
                          <img src="<?= BASE_URL . '/' . $showtime['movie_image'] ?>" 
                               alt="<?= htmlspecialchars($showtime['movie_title'] ?? '') ?>" 
                               style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                        <?php endif; ?>
                        <div>
                          <!-- Tên phim -->
                          <strong><?= htmlspecialchars($showtime['movie_title'] ?? 'N/A') ?></strong>
                          <!-- Thời lượng phim: hiển thị nếu có -->
                          <?php if (!empty($showtime['movie_duration'])): ?>
                            <br><small class="text-muted"><?= $showtime['movie_duration'] ?> phút</small>
                          <?php endif; ?>
                        </div>
                      </div>
                    </td>
                    <!-- Tên rạp -->
                    <td><?= htmlspecialchars($showtime['cinema_name'] ?? 'N/A') ?></td>
                    <!-- Tên phòng và mã phòng -->
                    <td>
                      <?= htmlspecialchars($showtime['room_name'] ?? 'N/A') ?>
                      <!-- Mã phòng: hiển thị trong ngoặc nếu có -->
                      <?php if (!empty($showtime['room_code'])): ?>
                        <br><small class="text-muted">(<?= htmlspecialchars($showtime['room_code']) ?>)</small>
                      <?php endif; ?>
                    </td>
                    <!-- Ngày chiếu: format d/m/Y -->
                    <td><?= $showtime['show_date'] ? date('d/m/Y', strtotime($showtime['show_date'])) : 'N/A' ?></td>
                    <!-- Giờ bắt đầu: format H:i -->
                    <td><?= $showtime['start_time'] ? date('H:i', strtotime($showtime['start_time'])) : 'N/A' ?></td>
                    <!-- Giờ kết thúc: format H:i -->
                    <td><?= $showtime['end_time'] ? date('H:i', strtotime($showtime['end_time'])) : 'N/A' ?></td>
                    <!-- Loại phim: 2D, 3D, IMAX -->
                    <td>
                      <span class="badge bg-info"><?= htmlspecialchars($showtime['format'] ?? '2D') ?></span>
                    </td>
                    <!-- Trạng thái suất chiếu: switch case để xác định class và text -->
                    <td>
                      <?php
                      // Lấy trạng thái, mặc định là 'ended'
                      $status = $showtime['status'] ?? 'ended';
                      $statusText = '';
                      $statusClass = '';
                      // Switch case: xác định text và class màu badge
                      switch ($status) {
                        case 'upcoming': // Sắp chiếu
                          $statusText = 'Sắp chiếu';
                          $statusClass = 'bg-primary'; // Màu xanh dương
                          break;
                        case 'showing': // Đang chiếu
                          $statusText = 'Đang chiếu';
                          $statusClass = 'bg-success'; // Màu xanh lá
                          break;
                        case 'ended': // Dừng
                          $statusText = 'Dừng';
                          $statusClass = 'bg-secondary'; // Màu xám
                          break;
                        default:
                          $statusText = 'Dừng';
                          $statusClass = 'bg-secondary';
                      }
                      ?>
                      <!-- Badge hiển thị trạng thái -->
                      <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </td>
                    <!-- Nút bán vé: link đến trang đặt vé với showtime_id -->
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

