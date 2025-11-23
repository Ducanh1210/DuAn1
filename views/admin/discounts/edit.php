<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa mã khuyến mại: <?= htmlspecialchars($discount['code']) ?></h4>
      <a href="<?= BASE_URL ?>?act=discounts" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Vui lòng kiểm tra lại các trường sau:</strong>
          <ul class="mb-0 mt-2">
            <?php foreach ($errors as $field => $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <div class="row">
          <div class="col-md-8">
            <div class="mb-3">
              <label for="code" class="form-label">Mã khuyến mại <span class="text-danger">*</span></label>
              <input type="text" 
                     name="code" 
                     id="code" 
                     class="form-control <?= !empty($errors['code']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['code'] ?? $discount['code']) ?>" 
                     required
                     placeholder="VD: WEEKEND25, HOLIDAY30..."
                     style="text-transform: uppercase;">
              <small class="form-text text-muted">Mã sẽ được tự động chuyển thành chữ in hoa</small>
              <?php if (!empty($errors['code'])): ?>
                <div class="text-danger small mt-1"><?= $errors['code'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
              <input type="text" 
                     name="title" 
                     id="title" 
                     class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['title'] ?? $discount['title']) ?>" 
                     required
                     placeholder="VD: Giảm giá cuối tuần 25%">
              <?php if (!empty($errors['title'])): ?>
                <div class="text-danger small mt-1"><?= $errors['title'] ?></div>
              <?php endif; ?>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="discount_percent" class="form-label">Phần trăm giảm giá (%) <span class="text-danger">*</span></label>
                  <input type="number" 
                         name="discount_percent" 
                         id="discount_percent" 
                         class="form-control <?= !empty($errors['discount_percent']) ? 'is-invalid' : '' ?>" 
                         value="<?= htmlspecialchars($_POST['discount_percent'] ?? $discount['discount_percent']) ?>" 
                         required
                         min="0"
                         max="100"
                         placeholder="VD: 25">
                  <?php if (!empty($errors['discount_percent'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['discount_percent'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="status" class="form-label">Trạng thái</label>
                  <select name="status" id="status" class="form-select">
                    <option value="active" <?= ($_POST['status'] ?? $discount['status']) === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="inactive" <?= ($_POST['status'] ?? $discount['status']) === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                  <input type="date" 
                         name="start_date" 
                         id="start_date" 
                         class="form-control <?= !empty($errors['start_date']) ? 'is-invalid' : '' ?>" 
                         value="<?= htmlspecialchars($_POST['start_date'] ?? $discount['start_date']) ?>" 
                         required>
                  <?php if (!empty($errors['start_date'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['start_date'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                  <input type="date" 
                         name="end_date" 
                         id="end_date" 
                         class="form-control <?= !empty($errors['end_date']) ? 'is-invalid' : '' ?>" 
                         value="<?= htmlspecialchars($_POST['end_date'] ?? $discount['end_date']) ?>" 
                         required>
                  <?php if (!empty($errors['end_date'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['end_date'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Mô tả</label>
              <textarea name="description" 
                        id="description" 
                        class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" 
                        rows="4" 
                        placeholder="Mô tả về mã khuyến mại này..."><?= htmlspecialchars($_POST['description'] ?? $discount['description'] ?? '') ?></textarea>
              <?php if (!empty($errors['description'])): ?>
                <div class="text-danger small mt-1"><?= $errors['description'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="benefits" class="form-label">Lợi ích (mỗi lợi ích một dòng)</label>
              <textarea name="benefits" 
                        id="benefits" 
                        class="form-control" 
                        rows="4" 
                        placeholder="VD:&#10;Áp dụng cho suất chiếu cuối tuần&#10;Giảm 25% cho mọi vé&#10;Không giới hạn số lượng vé"><?= htmlspecialchars($_POST['benefits'] ?? (is_array($discount['benefits']) ? implode("\n", $discount['benefits']) : '')) ?></textarea>
              <small class="form-text text-muted">Mỗi lợi ích trên một dòng riêng</small>
            </div>

            <div class="mb-3">
              <label for="cta" class="form-label">Call to Action (CTA)</label>
              <input type="text" 
                     name="cta" 
                     id="cta" 
                     class="form-control" 
                     value="<?= htmlspecialchars($_POST['cta'] ?? $discount['cta'] ?? '') ?>" 
                     placeholder="VD: Đặt vé cuối tuần">
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật mã khuyến mại
          </button>
          <a href="<?= BASE_URL ?>?act=discounts" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Tự động chuyển mã thành chữ in hoa
  document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
  });
</script>

