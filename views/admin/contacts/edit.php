<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Sửa liên hệ #<?= $contact['id'] ?></h4>
      <a href="<?= BASE_URL ?>?act=contacts-show&id=<?= $contact['id'] ?>" class="btn btn-light btn-sm">
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

      <form action="" method="post" id="contactForm">
        <div class="row">
          <!-- Cột trái: Thông tin khách hàng -->
          <div class="col-md-6">
            <div class="card mb-4 border-primary">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Thông tin khách hàng</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="name" class="form-label fw-bold">
                    <i class="bi bi-person"></i> Họ và tên <span class="text-danger">*</span>
                  </label>
                  <input type="text" 
                         name="name" 
                         id="name" 
                         class="form-control form-control-lg <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                         value="<?= htmlspecialchars($_POST['name'] ?? $contact['name'] ?? '') ?>" 
                         placeholder="Nhập họ và tên"
                         required>
                  <?php if (!empty($errors['name'])): ?>
                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                  <?php endif; ?>
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label fw-bold">
                    <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                  </label>
                  <input type="email" 
                         name="email" 
                         id="email" 
                         class="form-control form-control-lg <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" 
                         value="<?= htmlspecialchars($_POST['email'] ?? $contact['email'] ?? '') ?>" 
                         placeholder="example@email.com"
                         required>
                  <?php if (!empty($errors['email'])): ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                  <?php endif; ?>
                </div>

                <div class="mb-3">
                  <label for="phone" class="form-label fw-bold">
                    <i class="bi bi-telephone"></i> Số điện thoại
                  </label>
                  <input type="tel" 
                         name="phone" 
                         id="phone" 
                         class="form-control form-control-lg" 
                         value="<?= htmlspecialchars($_POST['phone'] ?? $contact['phone'] ?? '') ?>"
                         placeholder="0123456789">
                  <small class="text-muted">Không bắt buộc</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Cột phải: Nội dung và trạng thái -->
          <div class="col-md-6">
            <div class="card mb-4 border-info">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Nội dung và trạng thái</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="subject" class="form-label fw-bold">
                    <i class="bi bi-tag"></i> Chủ đề <span class="text-danger">*</span>
                  </label>
                  <input type="text" 
                         name="subject" 
                         id="subject" 
                         class="form-control form-control-lg <?= !empty($errors['subject']) ? 'is-invalid' : '' ?>" 
                         value="<?= htmlspecialchars($_POST['subject'] ?? $contact['subject'] ?? '') ?>" 
                         placeholder="Nhập chủ đề liên hệ"
                         required>
                  <?php if (!empty($errors['subject'])): ?>
                    <div class="invalid-feedback"><?= $errors['subject'] ?></div>
                  <?php endif; ?>
                </div>

                <div class="mb-3">
                  <label for="status" class="form-label fw-bold">
                    <i class="bi bi-gear"></i> Trạng thái <span class="text-danger">*</span>
                  </label>
                  <select name="status" 
                          id="status" 
                          class="form-select form-select-lg <?= !empty($errors['status']) ? 'is-invalid' : '' ?>" 
                          required>
                    <option value="pending" <?= ($_POST['status'] ?? $contact['status'] ?? '') == 'pending' ? 'selected' : '' ?>>⏳ Chờ xử lý</option>
                    <option value="processing" <?= ($_POST['status'] ?? $contact['status'] ?? '') == 'processing' ? 'selected' : '' ?>>🔄 Đang xử lý</option>
                    <option value="resolved" <?= ($_POST['status'] ?? $contact['status'] ?? '') == 'resolved' ? 'selected' : '' ?>>✅ Đã xử lý</option>
                    <option value="closed" <?= ($_POST['status'] ?? $contact['status'] ?? '') == 'closed' ? 'selected' : '' ?>>🔒 Đã đóng</option>
                  </select>
                  <?php if (!empty($errors['status'])): ?>
                    <div class="invalid-feedback"><?= $errors['status'] ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Nội dung tin nhắn - Full width -->
        <div class="card mb-4 border-warning">
          <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Nội dung tin nhắn <span class="text-danger">*</span></h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <textarea name="message" 
                        id="message" 
                        class="form-control <?= !empty($errors['message']) ? 'is-invalid' : '' ?>" 
                        rows="8" 
                        placeholder="Nhập nội dung tin nhắn..."
                        required><?= htmlspecialchars($_POST['message'] ?? $contact['message'] ?? '') ?></textarea>
              <?php if (!empty($errors['message'])): ?>
                <div class="invalid-feedback"><?= $errors['message'] ?></div>
              <?php endif; ?>
              <small class="text-muted">Nội dung tin nhắn từ khách hàng</small>
            </div>
          </div>
        </div>

        <!-- Action buttons -->
        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
          <div>
            <a href="<?= BASE_URL ?>?act=contacts-show&id=<?= $contact['id'] ?>" class="btn btn-secondary">
              <i class="bi bi-x-circle"></i> Hủy
            </a>
          </div>
          <div>
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-check-circle"></i> Cập nhật liên hệ
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .form-control-lg, .form-select-lg {
    font-size: 1rem;
    padding: 0.75rem 1rem;
  }
  
  .card {
    transition: transform 0.2s, box-shadow 0.2s;
  }
  
  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
  }
  
  .form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
  }
</style>
