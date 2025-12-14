<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết Liên hệ #<?= $contact['id'] ?></h4>
      <a href="<?= BASE_URL ?>?act=contacts" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>
    <div class="card-body">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['success']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <div class="row">
        <div class="col-md-8">
          <div class="card mb-3">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-person"></i> Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
              <table class="table table-borderless">
                <tr>
                  <th width="150">Họ và tên:</th>
                  <td><strong><?= htmlspecialchars($contact['name'] ?? 'N/A') ?></strong></td>
                </tr>
                <tr>
                  <th>Email:</th>
                  <td>
                    <a href="mailto:<?= htmlspecialchars($contact['email'] ?? '') ?>">
                      <?= htmlspecialchars($contact['email'] ?? 'N/A') ?>
                    </a>
                  </td>
                </tr>
                <tr>
                  <th>Số điện thoại:</th>
                  <td>
                    <a href="tel:<?= htmlspecialchars($contact['phone'] ?? '') ?>">
                      <?= htmlspecialchars($contact['phone'] ?? 'N/A') ?>
                    </a>
                  </td>
                </tr>
                <?php if (!empty($contact['cinema_id']) && !empty($contact['cinema_name'])): ?>
                <tr>
                  <th>Rạp:</th>
                  <td>
                    <span class="badge bg-secondary"><?= htmlspecialchars($contact['cinema_name']) ?></span>
                  </td>
                </tr>
                <?php endif; ?>
                <tr>
                  <th>Chủ đề:</th>
                  <td><strong><?= htmlspecialchars($contact['subject'] ?? 'N/A') ?></strong></td>
                </tr>
                <tr>
                  <th>Trạng thái:</th>
                  <td>
                    <?php
                    $statusColors = [
                      'pending' => 'warning',
                      'processing' => 'info',
                      'resolved' => 'success',
                      'closed' => 'secondary'
                    ];
                    $statusLabels = [
                      'pending' => 'Chờ xử lý',
                      'processing' => 'Đang xử lý',
                      'resolved' => 'Đã xử lý',
                      'closed' => 'Đã đóng'
                    ];
                    $status = $contact['status'] ?? 'pending';
                    $color = $statusColors[$status] ?? 'secondary';
                    $label = $statusLabels[$status] ?? ucfirst($status);
                    ?>
                    <span class="badge bg-<?= $color ?> fs-6"><?= $label ?></span>
                  </td>
                </tr>
                <tr>
                  <th>Ngày gửi:</th>
                  <td><?= date('d/m/Y H:i:s', strtotime($contact['created_at'])) ?></td>
                </tr>
                <tr>
                  <th>Ngày cập nhật:</th>
                  <td><?= date('d/m/Y H:i:s', strtotime($contact['updated_at'])) ?></td>
                </tr>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Nội dung tin nhắn</h5>
            </div>
            <div class="card-body">
              <div class="p-3 bg-light rounded">
                <?= nl2br(htmlspecialchars($contact['message'] ?? 'N/A')) ?>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin bổ sung</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label text-muted">ID Liên hệ:</label>
                <div class="fw-bold">#<?= $contact['id'] ?></div>
              </div>
              <div class="mb-3">
                <label class="form-label text-muted">Thời gian:</label>
                <div>
                  <small class="d-block text-muted">Gửi: <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></small>
                  <small class="d-block text-muted">Cập nhật: <?= date('d/m/Y H:i', strtotime($contact['updated_at'])) ?></small>
                </div>
              </div>
            </div>
          </div>

          <div class="card mt-3">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-tools"></i> Thao tác</h5>
            </div>
            <div class="card-body">
              <a href="<?= BASE_URL ?>?act=contacts-edit&id=<?= $contact['id'] ?>" 
                 class="btn btn-warning w-100 mb-2">
                <i class="bi bi-pencil"></i> <?= (isset($isStaff) && $isStaff) ? 'Cập nhật trạng thái' : 'Sửa liên hệ' ?>
              </a>
              <?php if (isset($isAdmin) && $isAdmin): ?>
              <a href="<?= BASE_URL ?>?act=contacts-delete&id=<?= $contact['id'] ?>" 
                 class="btn btn-danger w-100"
                 onclick="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?')">
                <i class="bi bi-trash"></i> Xóa liên hệ
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

