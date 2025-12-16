<?php
// MOVIES/SHOW.PHP - TRANG CHI TIẾT PHIM ADMIN
// Chức năng: Hiển thị thông tin chi tiết của một phim (ảnh, thông tin phim, mô tả, lịch chiếu)
// Biến từ controller: $movie (thông tin phim), $showtimes (danh sách lịch chiếu của phim)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và các nút thao tác -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết phim</h4>
      <div>
        <!-- Link sửa phim -->
        <a href="<?= BASE_URL ?>?act=movies-edit&id=<?= $movie['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa phim
        </a>
        <!-- Link quay lại danh sách phim -->
        <a href="<?= BASE_URL ?>?act=/" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <!-- Cột trái: Ảnh phim -->
        <div class="col-md-4">
          <div class="text-center mb-4">
            <!-- Kiểm tra nếu có ảnh phim -->
            <?php if (!empty($movie['image'])): ?>
              <!-- Hiển thị ảnh phim -->
              <img src="<?= BASE_URL . '/' . $movie['image'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="img-fluid rounded shadow" style="max-width: 100%;">
            <?php else: ?>
              <!-- Hiển thị placeholder nếu không có ảnh -->
              <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                <span class="text-muted">Chưa có ảnh</span>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <!-- Cột phải: Thông tin phim -->
        <div class="col-md-8">
          <div class="row">
            <!-- Tên phim -->
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Tên phim</label>
                <h5 class="mb-0"><?= htmlspecialchars($movie['title']) ?></h5>
              </div>
            </div>

            <!-- Thể loại: hiển thị nếu có -->
            <?php if (!empty($movie['genre_name'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Thể loại</label>
                <!-- Badge hiển thị tên thể loại -->
                <span class="badge bg-info"><?= htmlspecialchars($movie['genre_name']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['description'])): ?>
            <div class="col-md-12 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Mô tả</label>
                <p class="mb-0"><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['duration'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Thời lượng</label>
                <span><?= $movie['duration'] ?> phút</span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['release_date'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày phát hành</label>
                <span><?= date('d/m/Y', strtotime($movie['release_date'])) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['end_date'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày kết thúc</label>
                <span><?= date('d/m/Y', strtotime($movie['end_date'])) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['format'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Loại</label>
                <span class="badge bg-secondary"><?= htmlspecialchars($movie['format']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['original_language'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngôn ngữ gốc</label>
                <span><?= htmlspecialchars($movie['original_language']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['subtitle_or_dub'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Phụ đề/Lồng tiếng</label>
                <span><?= htmlspecialchars($movie['subtitle_or_dub']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['age_rating'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Độ tuổi</label>
                <span class="badge bg-warning text-dark"><?= htmlspecialchars($movie['age_rating']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['producer'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Nhà sản xuất</label>
                <span><?= htmlspecialchars($movie['producer']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Trạng thái</label>
                <?php if ($movie['status'] == 'active'): ?>
                  <span class="badge bg-success">Đang chiếu</span>
                <?php else: ?>
                  <span class="badge bg-danger">Ngừng chiếu</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="col-md-12 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Trailer</label>
                <?php if (!empty($movie['trailer'])): ?>
                  <a href="<?= htmlspecialchars($movie['trailer']) ?>" target="_blank" class="btn btn-sm btn-danger">
                    <i class="bi bi-play-circle"></i> Xem trailer trên YouTube
                  </a>
                <?php else: ?>
                  <span class="text-muted">Chưa có trailer</span>
                <?php endif; ?>
              </div>
            </div>

            <?php if (!empty($movie['created_at'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày tạo</label>
                <small><?= date('d/m/Y H:i:s', strtotime($movie['created_at'])) ?></small>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($movie['updated_at'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày cập nhật</label>
                <small><?= date('d/m/Y H:i:s', strtotime($movie['updated_at'])) ?></small>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
