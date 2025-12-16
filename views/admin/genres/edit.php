<?php
// GENRES/EDIT.PHP - TRANG SỬA THỂ LOẠI PHIM ADMIN
// Chức năng: Form sửa thể loại phim (tên thể loại, mô tả)
// Biến từ controller: $genre (thông tin thể loại cần sửa), $errors (lỗi validation)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với tên thể loại và nút quay lại -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa thể loại: <?= htmlspecialchars($genre['name']) ?></h4>
      <!-- Link quay lại danh sách thể loại -->
      <a href="<?= BASE_URL ?>?act=genres" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <!-- Hiển thị lỗi validation nếu có: $errors từ controller -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Vui lòng kiểm tra lại các trường sau:</strong>
          <ul class="mb-0 mt-2">
            <!-- Vòng lặp: hiển thị từng lỗi -->
            <?php foreach ($errors as $field => $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Form sửa thể loại: onsubmit gọi hàm validateGenreForm() để validate client-side -->
      <form action="" method="post" id="genreForm" onsubmit="return validateGenreForm(event)">
        <div class="row">
          <div class="col-md-8">
            <!-- Input tên thể loại: bắt buộc (*), value lấy từ $_POST nếu có (sau submit), nếu không thì lấy từ $genre -->
            <div class="mb-3">
              <label for="name" class="form-label">Tên thể loại <span class="text-danger">*</span></label>
              <!-- is-invalid: thêm class nếu có lỗi để hiển thị border đỏ -->
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['name'] ?? $genre['name']) ?>" 
                     required
                     placeholder="VD: Hành động, Tình cảm, Kinh dị...">
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['name'])): ?>
                <div class="text-danger small mt-1"><?= $errors['name'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Textarea mô tả: không bắt buộc, value lấy từ $_POST nếu có, nếu không thì lấy từ $genre -->
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả</label>
              <textarea name="description" 
                        id="description" 
                        class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" 
                        rows="5" 
                        placeholder="Mô tả về thể loại phim này..."><?= htmlspecialchars($_POST['description'] ?? $genre['description'] ?? '') ?></textarea>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['description'])): ?>
                <div class="text-danger small mt-1"><?= $errors['description'] ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Nút submit và hủy -->
        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật thể loại
          </button>
          <a href="<?= BASE_URL ?>?act=genres" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Validation form client-side: kiểm tra tên thể loại trước khi submit
  function validateGenreForm(event) {
    // Lấy giá trị tên thể loại và loại bỏ khoảng trắng đầu cuối
    const name = document.getElementById('name').value.trim();

    // Kiểm tra: tên thể loại không được rỗng
    if (!name || name === '') {
      alert('Vui lòng nhập tên thể loại!');
      document.getElementById('name').focus(); // Focus vào input tên
      return false; // Ngăn submit
    }

    return true; // Cho phép submit
  }
</script>
