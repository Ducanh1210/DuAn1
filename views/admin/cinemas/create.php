<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Thêm rạp mới</h4>
      <a href="<?= BASE_URL ?>?act=cinemas" class="btn btn-secondary">
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
              <label for="name" class="form-label">Tên rạp <span class="text-danger">*</span></label>
              <input type="text" 
                     name="name" 
                     id="name" 
                     class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                     value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                     required
                     placeholder="VD: CGV Times City, Galaxy Nguyễn Du...">
              <?php if (!empty($errors['name'])): ?>
                <div class="text-danger small mt-1"><?= $errors['name'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Địa chỉ</label>
              <textarea name="address" 
                        id="address" 
                        class="form-control <?= !empty($errors['address']) ? 'is-invalid' : '' ?>" 
                        rows="3" 
                        placeholder="Nhập địa chỉ của rạp chiếu phim..."><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
              <?php if (!empty($errors['address'])): ?>
                <div class="text-danger small mt-1"><?= $errors['address'] ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Thêm rạp
          </button>
          <a href="<?= BASE_URL ?>?act=cinemas" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Hủy
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
