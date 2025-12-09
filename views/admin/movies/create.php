<?php require_once __DIR__ . '/../../../commons/auth.php'; ?>
<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Thêm phim mới</h4>
      <a href="<?= BASE_URL ?>?act=/" class="btn btn-secondary">
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

      <form action="" method="post" enctype="multipart/form-data" id="movieForm" onsubmit="return validateMovieForm(event)">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="title" class="form-label">Tên phim <span class="text-danger">*</span></label>
              <input type="text" name="title" id="title" class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" >
              <?php if (!empty($errors['title'])): ?>
                <div class="text-danger small mt-1"><?= $errors['title'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
              <textarea name="description" id="description" class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
              <?php if (!empty($errors['description'])): ?>
                <div class="text-danger small mt-1"><?= $errors['description'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="image" class="form-label">Hình ảnh <span class="text-danger">*</span></label>
              <?php if (!empty($uploaded_image)): ?>
                <div class="mb-2">
                  <p class="mb-1">Ảnh đã chọn:</p>
                  <img src="<?= BASE_URL . '/' . $uploaded_image ?>" alt="Preview" id="imagePreview" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                </div>
              <?php else: ?>
                <div class="mb-2" id="imagePreviewContainer" style="display: none;">
                  <p class="mb-1">Ảnh đã chọn:</p>
                  <img src="" alt="Preview" id="imagePreview" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                </div>
              <?php endif; ?>
              <input type="file" name="image" id="image" class="form-control <?= !empty($errors['image']) ? 'is-invalid' : '' ?>" accept="image/*" >
              <?php if (!empty($errors['image'])): ?>
                <div class="text-danger small mt-1"><?= $errors['image'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="trailer" class="form-label">Link trailer (YouTube)</label>
              <input type="url" name="trailer" id="trailer" class="form-control <?= !empty($errors['trailer']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['trailer'] ?? '') ?>" placeholder="https://youtube.com/...">
              <?php if (!empty($errors['trailer'])): ?>
                <div class="text-danger small mt-1"><?= $errors['trailer'] ?></div>
              <?php endif; ?>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="genre_id" class="form-label">Thể loại <span class="text-danger">*</span></label>
                  <select name="genre_id" id="genre_id" class="form-select <?= !empty($errors['genre_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Chọn thể loại --</option>
                    <?php if (!empty($genres)): ?>
                      <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre['id'] ?>" <?= (isset($_POST['genre_id']) && $_POST['genre_id'] == $genre['id']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($genre['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <?php if (!empty($errors['genre_id'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['genre_id'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Rạp <span class="text-danger">*</span></label>
                  <div class="border rounded p-3 <?= !empty($errors['cinema_ids']) ? 'border-danger' : '' ?>" style="max-height: 200px; overflow-y: auto;">
                    <?php if (!empty($cinemas)): ?>
                      <?php 
                      $isAdmin = isAdmin();
                      $selectedCinemas = isset($_POST['cinema_ids']) ? $_POST['cinema_ids'] : [];
                      if (isManager() && count($cinemas) == 1) {
                        $selectedCinemas = [$cinemas[0]['id']];
                      }
                      ?>
                      <?php foreach ($cinemas as $cinema): ?>
                        <div class="form-check">
                          <input class="form-check-input cinema-checkbox" 
                                 type="checkbox" 
                                 name="cinema_ids[]" 
                                 value="<?= $cinema['id'] ?>" 
                                 id="cinema_<?= $cinema['id'] ?>"
                                 <?= in_array($cinema['id'], $selectedCinemas) ? 'checked' : '' ?>
                                 <?= (isManager() && count($cinemas) == 1) ? 'disabled' : '' ?>
                                 onchange="checkCinemaLimit()">
                          <label class="form-check-label" for="cinema_<?= $cinema['id'] ?>">
                            <?= htmlspecialchars($cinema['name']) ?>
                          </label>
                        </div>
                      <?php endforeach; ?>
                      <?php if (isManager() && count($cinemas) == 1): ?>
                        <input type="hidden" name="cinema_ids[]" value="<?= $cinemas[0]['id'] ?>">
                      <?php endif; ?>
                    <?php else: ?>
                      <p class="text-muted mb-0">Chưa có rạp nào</p>
                    <?php endif; ?>
                  </div>
                  <small class="text-muted"><?= isAdmin() ? 'Chọn tối đa 3 rạp' : 'Rạp của bạn' ?></small>
                  <?php if (!empty($errors['cinema_ids'])): ?>
                    <div class="text-danger small mt-1"><?= $errors['cinema_ids'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="duration" class="form-label">Thời lượng (phút) <span class="text-danger">*</span></label>
              <input type="number" name="duration" id="duration" class="form-control <?= !empty($errors['duration']) ? 'is-invalid' : '' ?>" min="1" value="<?= htmlspecialchars($_POST['duration'] ?? '') ?>">
              <?php if (!empty($errors['duration'])): ?>
                <div class="text-danger small mt-1"><?= $errors['duration'] ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label for="release_date" class="form-label">Ngày phát hành <span class="text-danger">*</span></label>
              <input type="date" name="release_date" id="release_date" class="form-control <?= !empty($errors['release_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['release_date'] ?? '') ?>">
              <?php if (!empty($errors['release_date'])): ?>
                <div class="text-danger small mt-1"><?= $errors['release_date'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="end_date" class="form-label">Ngày kết thúc</label>
              <input type="date" name="end_date" id="end_date" class="form-control <?= !empty($errors['end_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
              <?php if (!empty($errors['end_date'])): ?>
                <div class="text-danger small mt-1"><?= $errors['end_date'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="format" class="form-label">Loại <span class="text-danger">*</span></label>
              <select name="format" id="format" class="form-select <?= !empty($errors['format']) ? 'is-invalid' : '' ?>">
                <option value="">-- Chọn loại --</option>
                <option value="2D" <?= (isset($_POST['format']) && $_POST['format'] == '2D') ? 'selected' : '' ?>>2D</option>
                <option value="3D" <?= (isset($_POST['format']) && $_POST['format'] == '3D') ? 'selected' : '' ?>>3D</option>
              </select>
              <?php if (!empty($errors['format'])): ?>
                <div class="text-danger small mt-1"><?= $errors['format'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="original_language" class="form-label">Ngôn ngữ gốc</label>
              <input type="text" name="original_language" id="original_language" class="form-control <?= !empty($errors['original_language']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['original_language'] ?? '') ?>" placeholder="VD: Tiếng Anh, Tiếng Việt">
              <?php if (!empty($errors['original_language'])): ?>
                <div class="text-danger small mt-1"><?= $errors['original_language'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="subtitle_or_dub" class="form-label">Phụ đề/Lồng tiếng</label>
              <input type="text" name="subtitle_or_dub" id="subtitle_or_dub" class="form-control <?= !empty($errors['subtitle_or_dub']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['subtitle_or_dub'] ?? '') ?>" placeholder="VD: Phụ đề, Lồng tiếng">
              <?php if (!empty($errors['subtitle_or_dub'])): ?>
                <div class="text-danger small mt-1"><?= $errors['subtitle_or_dub'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="age_rating" class="form-label">Độ tuổi</label>
              <select name="age_rating" id="age_rating" class="form-select <?= !empty($errors['age_rating']) ? 'is-invalid' : '' ?>">
                <option value="">-- Chọn độ tuổi --</option>
                <option value="P" <?= (isset($_POST['age_rating']) && $_POST['age_rating'] == 'P') ? 'selected' : '' ?>>P - Phổ biến</option>
                <option value="C13" <?= (isset($_POST['age_rating']) && $_POST['age_rating'] == 'C13') ? 'selected' : '' ?>>C13 - 13 tuổi</option>
                <option value="C16" <?= (isset($_POST['age_rating']) && $_POST['age_rating'] == 'C16') ? 'selected' : '' ?>>C16 - 16 tuổi</option>
                <option value="C18" <?= (isset($_POST['age_rating']) && $_POST['age_rating'] == 'C18') ? 'selected' : '' ?>>C18 - 18 tuổi</option>
              </select>
              <?php if (!empty($errors['age_rating'])): ?>
                <div class="text-danger small mt-1"><?= $errors['age_rating'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="producer" class="form-label">Nhà sản xuất</label>
              <input type="text" name="producer" id="producer" class="form-control <?= !empty($errors['producer']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_POST['producer'] ?? '') ?>">
              <?php if (!empty($errors['producer'])): ?>
                <div class="text-danger small mt-1"><?= $errors['producer'] ?></div>
              <?php endif; ?>
            </div>

          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Thêm phim
          </button>
          <a href="<?= BASE_URL ?>?act=/" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Preview ảnh khi chọn file
  document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        if (previewContainer) {
          previewContainer.style.display = 'block';
          preview.src = e.target.result;
        } else {
          // Nếu đã có ảnh từ server, cập nhật preview
          const existingPreview = document.getElementById('imagePreview');
          if (existingPreview) {
            existingPreview.src = e.target.result;
          }
        }
      };
      reader.readAsDataURL(file);
    }
  });

  // Kiểm tra giới hạn chọn rạp (tối đa 3)
  function checkCinemaLimit() {
    const checkboxes = document.querySelectorAll('.cinema-checkbox:not(:disabled)');
    const checked = document.querySelectorAll('.cinema-checkbox:not(:disabled):checked');
    
    if (checked.length >= 3) {
      checkboxes.forEach(cb => {
        if (!cb.checked) {
          cb.disabled = true;
        }
      });
    } else {
      checkboxes.forEach(cb => {
        cb.disabled = false;
      });
    }
  }

  // Validation function
  function validateMovieForm(event) {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const image = document.getElementById('image').files[0];
    const genreId = document.getElementById('genre_id').value;
    const duration = document.getElementById('duration').value;
    const releaseDate = document.getElementById('release_date').value;
    const producer = document.getElementById('producer').value.trim();
    const ageRating = document.getElementById('age_rating').value;
    const format = document.getElementById('format').value;
    const originalLanguage = document.getElementById('original_language').value.trim();
    
    // Kiểm tra rạp đã chọn
    const cinemaCheckboxes = document.querySelectorAll('input[name="cinema_ids[]"]:checked');
    if (cinemaCheckboxes.length === 0) {
      alert('Vui lòng chọn ít nhất một rạp!');
      return false;
    }
    
    if (cinemaCheckboxes.length > 3) {
      alert('Chỉ được chọn tối đa 3 rạp!');
      return false;
    }

    if (!title || title === '') {
      alert('Vui lòng nhập tên phim!');
      document.getElementById('title').focus();
      return false;
    }

    if (!description || description === '') {
      alert('Vui lòng nhập mô tả phim!');
      document.getElementById('description').focus();
      return false;
    }

    if (!image) {
      alert('Vui lòng chọn hình ảnh!');
      document.getElementById('image').focus();
      return false;
    }

    // Kiểm tra định dạng file ảnh
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(image.type)) {
      alert('Vui lòng chọn file ảnh hợp lệ (JPG, PNG, GIF, WEBP)!');
      document.getElementById('image').focus();
      return false;
    }

    if (!genreId || genreId === '') {
      alert('Vui lòng chọn thể loại!');
      document.getElementById('genre_id').focus();
      return false;
    }

    if (!duration || duration === '' || parseInt(duration) <= 0) {
      alert('Vui lòng nhập thời lượng phim hợp lệ!');
      document.getElementById('duration').focus();
      return false;
    }

    if (!releaseDate || releaseDate === '') {
      alert('Vui lòng chọn ngày khởi chiếu!');
      document.getElementById('release_date').focus();
      return false;
    }

    if (!producer || producer === '') {
      alert('Vui lòng nhập nhà xuất bản!');
      document.getElementById('producer').focus();
      return false;
    }

    if (!ageRating || ageRating === '') {
      alert('Vui lòng chọn độ tuổi!');
      document.getElementById('age_rating').focus();
      return false;
    }

    if (!format || format === '') {
      alert('Vui lòng chọn loại!');
      document.getElementById('format').focus();
      return false;
    }

    if (!originalLanguage || originalLanguage === '') {
      alert('Vui lòng nhập ngôn ngữ gốc!');
      document.getElementById('original_language').focus();
      return false;
    }

    return true;
  }
  
  // Khởi tạo khi trang load
  document.addEventListener('DOMContentLoaded', function() {
    checkCinemaLimit();
  });
</script>
