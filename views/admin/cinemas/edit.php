<?php
// CINEMAS/EDIT.PHP - TRANG SỬA RẠP ADMIN
// Chức năng: Form sửa thông tin rạp (tên rạp, địa chỉ)
// Biến từ controller: $cinema (thông tin rạp cần sửa), $errors (lỗi validation)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với tên rạp và nút quay lại -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sửa rạp: <?= htmlspecialchars($cinema['name']) ?></h4>
      <!-- Link quay lại danh sách rạp -->
      <a href="<?= BASE_URL ?>?act=cinemas" class="btn btn-secondary">
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

      <!-- Form sửa rạp: onsubmit gọi hàm validateCinemaForm() để validate client-side -->
      <form action="" method="post" id="cinemaForm" onsubmit="return validateCinemaForm(event)">
        <div class="row">
          <div class="col-md-8">
            <!-- Input tên rạp: bắt buộc (*), value lấy từ $_POST nếu có (sau submit), nếu không thì lấy từ $cinema -->
            <div class="mb-3">
              <label for="name" class="form-label">Tên rạp <span class="text-danger">*</span></label>
              <!-- is-invalid: thêm class nếu có lỗi để hiển thị border đỏ -->
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['name'] ?? $cinema['name']) ?>" 
                     
                     placeholder="VD: CGV Times City, Galaxy Nguyễn Du...">
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (!empty($errors['name'])): ?>
                <div class="text-danger small mt-1"><?= $errors['name'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Input địa chỉ: không bắt buộc -->
            <div class="mb-3">
              <label for="address" class="form-label">Địa chỉ</label>
              <textarea name="address" 
                        id="address" 
                        class="form-control <?= !empty($errors['address']) ? 'is-invalid' : '' ?>" 
                        rows="3" 
                        placeholder="Nhập địa chỉ của rạp chiếu phim..."><?= htmlspecialchars($_POST['address'] ?? $cinema['address'] ?? '') ?></textarea>
              <?php if (!empty($errors['address'])): ?>
                <div class="text-danger small mt-1"><?= $errors['address'] ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Cập nhật rạp
          </button>
          <a href="<?= BASE_URL ?>?act=cinemas" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function validateCinemaForm(event) {
    const name = document.getElementById('name').value.trim();
    const address = document.getElementById('address').value.trim();
    const phone = document.getElementById('phone') ? document.getElementById('phone').value.trim() : '';

    if (!name || name === '') {
      alert('Vui lòng nhập tên rạp!');
      document.getElementById('name').focus();
      return false;
    }

    if (!address || address === '') {
      alert('Vui lòng nhập địa chỉ!');
      document.getElementById('address').focus();
      return false;
    }

    if (phone && phone !== '') {
      const phoneRegex = /^[0-9]{10,11}$/;
      if (!phoneRegex.test(phone)) {
        alert('Số điện thoại phải có 10-11 chữ số!');
        document.getElementById('phone').focus();
        return false;
      }
    }

    return true;
  }
</script>
