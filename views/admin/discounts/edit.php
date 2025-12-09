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

      <?php
        $movies = $movies ?? [];
        $selectedMovieId = $_POST['movie_id'] ?? ($discount['movie_id'] ?? '');
      ?>

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
                     
                     placeholder="VD: Giảm giá cuối tuần 25%">
              <?php if (!empty($errors['title'])): ?>
                <div class="text-danger small mt-1"><?= $errors['title'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="movie_id" class="form-label">Áp dụng cho phim (tùy chọn)</label>
              <select name="movie_id" 
                      id="movie_id" 
                      class="form-select <?= !empty($errors['movie_id']) ? 'is-invalid' : '' ?>">
                <option value=""><?= empty($movies) ? 'Hiện chưa có phim để gắn' : 'Không giới hạn phim (áp dụng toàn bộ)'; ?></option>
                <?php foreach ($movies as $movie): ?>
                  <?php
                    $movieId = (int)($movie['id'] ?? 0);
                    $isSelected = $selectedMovieId !== '' && (int)$selectedMovieId === $movieId;
                    $movieStatus = $movie['status'] ?? '';
                    $statusLabel = $movieStatus === 'active' ? 'Đang chiếu' : ($movieStatus === 'upcoming' ? 'Sắp chiếu' : 'Không hoạt động');
                  ?>
                  <option value="<?= $movieId ?>"
                          data-title="<?= htmlspecialchars($movie['title'] ?? '') ?>"
                          data-image="<?= htmlspecialchars($movie['image'] ?? '') ?>"
                          data-status="<?= htmlspecialchars($movieStatus) ?>"
                          <?= $isSelected ? 'selected' : '' ?>>
                    <?= htmlspecialchars($movie['title'] ?? 'Tên phim') ?> (<?= $statusLabel ?>)
                  </option>
                <?php endforeach; ?>
              </select>
              <small class="form-text text-muted">Chọn phim để biến mã giảm giá thành ưu đãi dành riêng cho phim đó.</small>
              <?php if (!empty($errors['movie_id'])): ?>
                <div class="text-danger small mt-1"><?= $errors['movie_id'] ?></div>
              <?php endif; ?>
            </div>

            <div id="movie-preview-card" class="card border-info mb-3 d-none">
              <div class="card-body d-flex align-items-center">
                <img id="movie-preview-image" src="" alt="Poster phim" class="rounded me-3" style="width: 80px; height: 120px; object-fit: cover; display: none;">
                <div>
                  <h6 class="mb-1" id="movie-preview-title">Tên phim sẽ hiển thị tại đây</h6>
                  <p class="mb-0 text-muted small">Poster phim sẽ được gắn vào thẻ mã giảm giá trên trang Khuyến mãi.</p>
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
                         
                         min="0"
                         max="85"
                         placeholder="VD: 25">
                  <small class="form-text text-muted">Tối đa 85% (không được giảm giá 100%)</small>
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
                         >
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
                         >
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
  // Đợi DOM load xong
  document.addEventListener('DOMContentLoaded', function() {
    // Tự động chuyển mã thành chữ in hoa
    const codeInput = document.getElementById('code');
    if (codeInput) {
      codeInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
      });
    }

    // Validation phần trăm giảm giá
    const discountPercentInput = document.getElementById('discount_percent');
    if (discountPercentInput) {
      // Validation khi đang nhập
      discountPercentInput.addEventListener('input', function(e) {
        let value = parseFloat(e.target.value);
        if (isNaN(value) || e.target.value === '') {
          return;
        }
        if (value < 0) {
          e.target.value = 0;
          alert('Phần trăm giảm giá không được nhỏ hơn 0%');
        } else if (value >= 100) {
          e.target.value = 85;
          alert('Không được giảm giá 100% hoặc lớn hơn. Tối đa chỉ được 85%');
        } else if (value > 85) {
          e.target.value = 85;
          alert('Phần trăm giảm giá không được vượt quá 85%');
        }
      });

      // Validation khi blur (rời khỏi input)
      discountPercentInput.addEventListener('blur', function(e) {
        let value = parseFloat(e.target.value);
        if (isNaN(value) || e.target.value === '') {
          return;
        }
        if (value < 0) {
          e.target.value = 0;
          alert('Phần trăm giảm giá không được nhỏ hơn 0%');
        } else if (value >= 100) {
          e.target.value = 85;
          alert('Không được giảm giá 100% hoặc lớn hơn. Tối đa chỉ được 85%');
        } else if (value > 85) {
          e.target.value = 85;
          alert('Phần trăm giảm giá không được vượt quá 85%');
        }
      });
    }

    // Validation khi submit form
    const form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', function(e) {
        const discountPercent = parseFloat(document.getElementById('discount_percent').value);
        if (isNaN(discountPercent) || document.getElementById('discount_percent').value === '') {
          e.preventDefault();
          alert('Vui lòng nhập phần trăm giảm giá hợp lệ');
          return false;
        }
        if (discountPercent < 0) {
          e.preventDefault();
          alert('Phần trăm giảm giá không được nhỏ hơn 0%');
          return false;
        }
        if (discountPercent >= 100) {
          e.preventDefault();
          alert('Không được giảm giá 100% hoặc lớn hơn. Tối đa chỉ được 85%');
          return false;
        }
        if (discountPercent > 85) {
          e.preventDefault();
          alert('Phần trăm giảm giá không được vượt quá 85%');
          return false;
        }
      });
    }

    // Xem trước phim gắn với mã giảm giá
    const movieSelect = document.getElementById('movie_id');
    const moviePreviewCard = document.getElementById('movie-preview-card');
    const moviePreviewImage = document.getElementById('movie-preview-image');
    const moviePreviewTitle = document.getElementById('movie-preview-title');
    const baseUrl = '<?= BASE_URL ?>';
    const fallbackImage = baseUrl + '/image/logo.png';

    function updateMoviePreview() {
      if (!movieSelect || !moviePreviewCard) {
        return;
      }

      const selectedOption = movieSelect.options[movieSelect.selectedIndex];
      if (!selectedOption || !selectedOption.value) {
        moviePreviewCard.classList.add('d-none');
        moviePreviewImage.style.display = 'none';
        moviePreviewTitle.textContent = 'Tên phim sẽ hiển thị tại đây';
        return;
      }

      const movieTitle = selectedOption.dataset.title || selectedOption.text;
      const movieImagePath = selectedOption.dataset.image || '';
      moviePreviewTitle.textContent = movieTitle || 'Tên phim sẽ hiển thị tại đây';

      if (movieImagePath) {
        const normalizedPath = movieImagePath.startsWith('http')
          ? movieImagePath
          : baseUrl.replace(/\/+$/, '') + '/' + movieImagePath.replace(/^\/+/, '');
        moviePreviewImage.src = normalizedPath;
        moviePreviewImage.style.display = 'block';
      } else {
        moviePreviewImage.src = fallbackImage;
        moviePreviewImage.style.display = 'block';
      }

      moviePreviewCard.classList.remove('d-none');
    }

    if (movieSelect) {
      movieSelect.addEventListener('change', updateMoviePreview);
      updateMoviePreview();
    }
  });
</script>

