<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết bình luận</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=comments" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <div class="card mb-4">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Thông tin bình luận</h5>
            </div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="text-muted small mb-1 d-block">ID</label>
                  <strong>#<?= $comment['id'] ?></strong>
                </div>
                <div class="col-md-6">
                  <label class="text-muted small mb-1 d-block">Ngày tạo</label>
                  <span><?= $comment['created_at'] ? date('d/m/Y H:i:s', strtotime($comment['created_at'])) : 'N/A' ?></span>
                </div>
              </div>

              <div class="mb-3">
                <label class="text-muted small mb-1 d-block">Đánh giá</label>
                <?php if (!empty($comment['rating'])): ?>
                  <div class="d-flex align-items-center">
                    <span class="text-warning me-2" style="font-size: 1.5rem;">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $comment['rating']): ?>
                          <i class="bi bi-star-fill"></i>
                        <?php else: ?>
                          <i class="bi bi-star"></i>
                        <?php endif; ?>
                      <?php endfor; ?>
                    </span>
                    <span class="badge bg-warning text-dark" style="font-size: 1rem;"><?= $comment['rating'] ?>/5</span>
                  </div>
                <?php else: ?>
                  <span class="text-muted">Chưa đánh giá</span>
                <?php endif; ?>
              </div>

              <div class="mb-3">
                <label class="text-muted small mb-1 d-block">Nội dung bình luận</label>
                <div class="border rounded p-3 bg-light">
                  <?php if (!empty($comment['content'])): ?>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                  <?php else: ?>
                    <span class="text-muted">Không có nội dung</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-4">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0">Thông tin người dùng</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="text-muted small mb-1 d-block">Tên người dùng</label>
                  <strong><?= htmlspecialchars($comment['user_name'] ?? 'Người dùng đã xóa') ?></strong>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="text-muted small mb-1 d-block">Email</label>
                  <span><?= htmlspecialchars($comment['user_email'] ?? 'N/A') ?></span>
                </div>
                <?php if (!empty($comment['user_id'])): ?>
                <div class="col-md-12">
                  <a href="<?= BASE_URL ?>?act=users-show&id=<?= $comment['user_id'] ?>" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-person"></i> Xem thông tin người dùng
                  </a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0">Thông tin phim</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="text-muted small mb-1 d-block">Tên phim</label>
                  <?php if (!empty($comment['movie_title'])): ?>
                    <strong><?= htmlspecialchars($comment['movie_title']) ?></strong>
                  <?php else: ?>
                    <span class="text-muted">Phim đã xóa</span>
                  <?php endif; ?>
                </div>
                <?php if (!empty($comment['movie_id'])): ?>
                <div class="col-md-12">
                  <a href="<?= BASE_URL ?>?act=movies-show&id=<?= $comment['movie_id'] ?>" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-film"></i> Xem thông tin phim
                  </a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header bg-danger text-white">
              <h5 class="mb-0">Thao tác</h5>
            </div>
            <div class="card-body">
              <?php if (!empty($comment['status']) && $comment['status'] === 'hidden'): ?>
                <div class="alert alert-warning mb-3">
                  <i class="bi bi-eye-slash"></i> Bình luận này đã bị ẩn
                </div>
              <?php endif; ?>
              <div class="d-grid gap-2">
                <a href="<?= BASE_URL ?>?act=comments-delete&id=<?= $comment['id'] ?>" 
                   class="btn <?= (!empty($comment['status']) && $comment['status'] === 'hidden') ? 'btn-success' : 'btn-warning' ?>"
                   onclick="return confirm('<?= (!empty($comment['status']) && $comment['status'] === 'hidden') ? 'Bạn có chắc chắn muốn hiện lại bình luận này?' : 'Bạn có chắc chắn muốn ẩn bình luận này?' ?>')">
                  <i class="bi <?= (!empty($comment['status']) && $comment['status'] === 'hidden') ? 'bi-eye' : 'bi-eye-slash' ?>"></i> 
                  <?= (!empty($comment['status']) && $comment['status'] === 'hidden') ? 'Hiện lại bình luận' : 'Ẩn bình luận' ?>
                </a>
                <a href="<?= BASE_URL ?>?act=comments" class="btn btn-secondary">
                  <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
              </div>
            </div>
          </div>

          <?php if (!empty($comment['movie_image'])): ?>
          <div class="card mt-3">
            <div class="card-header">
              <h5 class="mb-0">Poster phim</h5>
            </div>
            <div class="card-body text-center">
              <img src="<?= BASE_URL . '/' . $comment['movie_image'] ?>" alt="<?= htmlspecialchars($comment['movie_title'] ?? '') ?>" class="img-fluid rounded">
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

