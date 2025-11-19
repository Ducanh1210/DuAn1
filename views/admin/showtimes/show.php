<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết lịch chiếu</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=showtimes-edit&id=<?= $showtime['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa lịch chiếu
        </a>
        <a href="<?= BASE_URL ?>?act=showtimes" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="text-center mb-4">
            <?php if (!empty($showtime['movie_image'])): ?>
              <img src="<?= BASE_URL . '/' . $showtime['movie_image'] ?>" 
                   alt="<?= htmlspecialchars($showtime['movie_title'] ?? '') ?>" 
                   class="img-fluid rounded shadow" style="max-width: 100%;">
            <?php else: ?>
              <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                <span class="text-muted">Chưa có ảnh</span>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Phim</label>
                <h5 class="mb-0"><?= htmlspecialchars($showtime['movie_title'] ?? 'N/A') ?></h5>
                <?php if (!empty($showtime['movie_duration'])): ?>
                  <small class="text-muted"><?= $showtime['movie_duration'] ?> phút</small>
                <?php endif; ?>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Rạp chiếu</label>
                <span class="badge bg-primary"><?= htmlspecialchars($showtime['cinema_name'] ?? 'N/A') ?></span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Phòng chiếu</label>
                <span>
                  <?= htmlspecialchars($showtime['room_name'] ?? 'N/A') ?>
                  <?php if (!empty($showtime['room_code'])): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($showtime['room_code']) ?></span>
                  <?php endif; ?>
                </span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngày chiếu</label>
                <span><?= $showtime['show_date'] ? date('d/m/Y', strtotime($showtime['show_date'])) : 'N/A' ?></span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giờ bắt đầu</label>
                <span class="badge bg-success fs-6">
                  <?= $showtime['start_time'] ? date('H:i', strtotime($showtime['start_time'])) : 'N/A' ?>
                </span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giờ kết thúc</label>
                <span class="badge bg-danger fs-6">
                  <?= $showtime['end_time'] ? date('H:i', strtotime($showtime['end_time'])) : 'N/A' ?>
                </span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giá vé người lớn</label>
                <span class="text-success fw-bold">
                  <?= $showtime['adult_price'] ? number_format($showtime['adult_price'], 0, ',', '.') . ' đ' : 'N/A' ?>
                </span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Giá vé học sinh</label>
                <span class="text-info fw-bold">
                  <?= $showtime['student_price'] ? number_format($showtime['student_price'], 0, ',', '.') . ' đ' : 'N/A' ?>
                </span>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Định dạng</label>
                <span class="badge bg-info"><?= htmlspecialchars($showtime['format'] ?? '2D') ?></span>
              </div>
            </div>

            <?php if (!empty($showtime['movie_age_rating'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Độ tuổi</label>
                <span class="badge bg-warning text-dark"><?= htmlspecialchars($showtime['movie_age_rating']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($showtime['movie_original_language'])): ?>
            <div class="col-md-6 mb-3">
              <div class="border-bottom pb-2">
                <label class="text-muted small mb-1 d-block">Ngôn ngữ gốc</label>
                <span><?= htmlspecialchars($showtime['movie_original_language']) ?></span>
              </div>
            </div>
            <?php endif; ?>

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

