<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa mã giảm giá: <?= htmlspecialchars($discount['code']) ?></h4>
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
              <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
              <input type="text"
                name="code"
                id="code"
                class="form-control <?= !empty($errors['code']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($_POST['code'] ?? $discount['code']) ?>"
                required
                placeholder="VD: MOVIEWEEK30"
                style="text-transform: uppercase;">
              <small class="form-text text-muted">Mã sẽ được chuyển thành chữ in hoa tự động</small>
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
                placeholder="VD: Movie Week 30%">
              <?php if (!empty($errors['title'])): ?>
                <div class="text-danger small mt-1"><?= $errors['title'] ?></div>
              <?php endif; ?>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                  <select name="status"
                    id="status"
                    class="form-select <?= !empty($errors['status']) ? 'is-invalid' : '' ?>"
                    required>
                    <option value="active" <?= ($_POST['status'] ?? $discount['status']) === 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                    <option value="upcoming" <?= ($_POST['status'] ?? $discount['status']) === 'upcoming' ? 'selected' : '' ?>>Sắp diễn ra</option>
                    <option value="inactive" <?= ($_POST['status'] ?? $discount['status']) === 'inactive' ? 'selected' : '' ?>>Đã tắt</option>
                  </select>
                  <?php if (!empty($errors['status'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['status'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
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
                    step="0.01"
                    placeholder="VD: 30">
                  <?php if (!empty($errors['discount_percent'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['discount_percent'] ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="max_discount" class="form-label">Giảm tối đa (VNĐ)</label>
                  <input type="number"
                    name="max_discount"
                    id="max_discount"
                    class="form-control <?= !empty($errors['max_discount']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($_POST['max_discount'] ?? $discount['max_discount'] ?? '') ?>"
                    min="0"
                    step="1000"
                    placeholder="VD: 60000">
                  <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                  <?php if (!empty($errors['max_discount'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['max_discount'] ?></div>
                  <?php endif; ?>
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
                placeholder="Mô tả về mã giảm giá này..."><?= htmlspecialchars($_POST['description'] ?? $discount['description'] ?? '') ?></textarea>
              <?php if (!empty($errors['description'])): ?>
                <div class="text-danger small mt-1"><?= $errors['description'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="benefits" class="form-label">Lợi ích (mỗi dòng một lợi ích)</label>
              <textarea name="benefits"
                id="benefits"
                class="form-control <?= !empty($errors['benefits']) ? 'is-invalid' : '' ?>"
                rows="4"
                placeholder="VD:&#10;Áp dụng tối đa 04 vé/đơn&#10;Thời gian: 10h - 22h&#10;Không áp dụng cho ghế Sweetbox">
                <?php
                $benefitsValue = '';
                if (isset($_POST['benefits'])) {
                  if (is_array($_POST['benefits'])) {
                    $benefitsValue = implode("\n", $_POST['benefits']);
                  } else {
                    $benefitsValue = $_POST['benefits'];
                  }
                } elseif (isset($discount['benefits'])) {
                  if (is_array($discount['benefits'])) {
                    $benefitsValue = implode("\n", $discount['benefits']);
                  } else {
                    $benefitsValue = $discount['benefits'];
                  }
                }
                echo htmlspecialchars($benefitsValue);
                ?></textarea>
              <small class="form-text text-muted">Mỗi lợi ích trên một dòng</small>
              <?php if (!empty($errors['benefits'])): ?>
                <div class="text-danger small mt-1"><?= $errors['benefits'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="cta" class="form-label">Nút kêu gọi hành động (CTA)</label>
              <input type="text"
                name="cta"
                id="cta"
                class="form-control <?= !empty($errors['cta']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($_POST['cta'] ?? $discount['cta'] ?? '') ?>"
                placeholder="VD: Đặt vé giảm 30%">
              <small class="form-text text-muted">Văn bản hiển thị trên nút hành động</small>
              <?php if (!empty($errors['cta'])): ?>
                <div class="text-danger small mt-1"><?= $errors['cta'] ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật mã giảm giá
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
  // Auto uppercase code field
  document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
  });
</script>