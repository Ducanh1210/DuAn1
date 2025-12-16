<?php
// SHOWTIMES/SHOW.PHP - TRANG CHI TIẾT LỊCH CHIẾU ADMIN
// Chức năng: Hiển thị thông tin chi tiết của một lịch chiếu (phim, rạp, phòng, ngày giờ, format, độ tuổi, ngôn ngữ)
// Biến từ controller: $showtime (thông tin lịch chiếu cần hiển thị)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và các nút thao tác -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết lịch chiếu</h4>
      <div>
        <!-- Nút sửa lịch chiếu -->
        <a href="<?= BASE_URL ?>?act=showtimes-edit&id=<?= $showtime['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa lịch chiếu
        </a>
        <!-- Link quay lại danh sách lịch chiếu -->
        <a href="<?= BASE_URL ?>?act=showtimes" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Cột trái: hiển thị poster phim -->
        <div class="col-md-4">
          <div class="text-center mb-4">
            <!-- Hiển thị ảnh poster phim nếu có -->
            <?php if (!empty($showtime['movie_image'])): ?>
              <img src="<?= BASE_URL . '/' . $showtime['movie_image'] ?>" 
                   alt="<?= htmlspecialchars($showtime['movie_title'] ?? '') ?>" 
                   class="img-fluid rounded shadow" style="max-width: 100%;">
            <?php else: ?>
              <!-- Hiển thị placeholder nếu không có ảnh -->
              <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                <span class="text-muted">Chưa có ảnh</span>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <!-- Cột phải: hiển thị thông tin chi tiết -->
        <div class="col-md-8">
          <div class="row">
            <!-- Hiển thị tên phim và thời lượng -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Phim</label>
                <h5 class="mb-0"><?= htmlspecialchars($showtime['movie_title'] ?? 'N/A') ?></h5>
                <!-- Hiển thị thời lượng phim nếu có -->
                <?php if (!empty($showtime['movie_duration'])): ?>
                  <small class="text-muted"><?= $showtime['movie_duration'] ?> phút</small>
                <?php endif; ?>
              </div>
            </div>

            <!-- Hiển thị tên rạp chiếu -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Rạp chiếu</label>
                <span class="badge bg-primary"><?= htmlspecialchars($showtime['cinema_name'] ?? 'N/A') ?></span>
              </div>
            </div>

            <!-- Hiển thị tên phòng chiếu và mã phòng: $showtime['room_name'], $showtime['room_code'] -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Phòng chiếu</label>
                <span>
                  <?= htmlspecialchars($showtime['room_name'] ?? 'N/A') ?>
                  <!-- Hiển thị mã phòng dạng badge nếu có -->
                  <?php if (!empty($showtime['room_code'])): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($showtime['room_code']) ?></span>
                  <?php endif; ?>
                </span>
              </div>
            </div>

            <!-- Hiển thị ngày chiếu: format d/m/Y -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày chiếu</label>
                <span><?= $showtime['show_date'] ? date('d/m/Y', strtotime($showtime['show_date'])) : 'N/A' ?></span>
              </div>
            </div>

            <!-- Hiển thị giờ bắt đầu: format H:i, badge màu xanh -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giờ bắt đầu</label>
                <span class="badge bg-success fs-6">
                  <?= $showtime['start_time'] ? date('H:i', strtotime($showtime['start_time'])) : 'N/A' ?>
                </span>
              </div>
            </div>

            <!-- Hiển thị giờ kết thúc: format H:i, badge màu đỏ -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giờ kết thúc</label>
                <span class="badge bg-danger fs-6">
                  <?= $showtime['end_time'] ? date('H:i', strtotime($showtime['end_time'])) : 'N/A' ?>
                </span>
              </div>
            </div>

            <!-- Hiển thị giá vé: link đến trang quản lý giá vé -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giá vé</label>
                <div>
                  <!-- Link đến trang quản lý giá vé -->
                  <a href="<?= BASE_URL ?>?act=ticket-prices" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-currency-exchange"></i> Xem bảng giá vé
                  </a>
                  <small class="text-muted d-block mt-1">Giá vé được quản lý tại Quản lý giá vé</small>
                </div>
              </div>
            </div>

            <!-- Hiển thị loại format: 2D hoặc 3D, badge màu xanh nhạt -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Loại</label>
                <span class="badge bg-info"><?= htmlspecialchars($showtime['format'] ?? '2D') ?></span>
              </div>
            </div>

            <!-- Hiển thị độ tuổi: chỉ hiển thị nếu có, badge màu vàng -->
            <?php if (!empty($showtime['movie_age_rating'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Độ tuổi</label>
                <span class="badge bg-warning text-dark"><?= htmlspecialchars($showtime['movie_age_rating']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <!-- Hiển thị ngôn ngữ gốc: chỉ hiển thị nếu có -->
            <?php if (!empty($showtime['movie_original_language'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngôn ngữ gốc</label>
                <span><?= htmlspecialchars($showtime['movie_original_language']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <!-- Hiển thị ngày phát hành phim: chỉ hiển thị nếu có, format d/m/Y -->
            <?php if (!empty($showtime['movie_release_date'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày phát hành phim</label>
                <span><?= date('d/m/Y', strtotime($showtime['movie_release_date'])) ?></span>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

