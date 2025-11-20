<?php
$errors = $errors ?? [];
$discountCodes = $discountCodes ?? [];
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Thêm voucher mới</h2>
        <a href="<?= BASE_URL ?>?act=vouchers" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>?act=vouchers-create">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                            <?php if (!empty($errors['title'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" 
                                      rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            <?php if (!empty($errors['description'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tag</label>
                                <select name="tag" class="form-select">
                                    <option value="general" <?= ($_POST['tag'] ?? 'general') === 'general' ? 'selected' : '' ?>>General</option>
                                    <option value="flash" <?= ($_POST['tag'] ?? '') === 'flash' ? 'selected' : '' ?>>Flash</option>
                                    <option value="member" <?= ($_POST['tag'] ?? '') === 'member' ? 'selected' : '' ?>>Member</option>
                                    <option value="newuser" <?= ($_POST['tag'] ?? '') === 'newuser' ? 'selected' : '' ?>>New User</option>
                                    <option value="student" <?= ($_POST['tag'] ?? '') === 'student' ? 'selected' : '' ?>>Student</option>
                                    <option value="combo" <?= ($_POST['tag'] ?? '') === 'combo' ? 'selected' : '' ?>>Combo</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="ongoing" <?= ($_POST['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                    <option value="upcoming" <?= ($_POST['status'] ?? '') === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                                    <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mã voucher (hiển thị)</label>
                            <input type="text" name="code" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['code'] ?? '') ?>" 
                                   placeholder="VD: WELCOME20">
                            <small class="text-muted">Mã này sẽ hiển thị cho người dùng</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Liên kết với mã giảm giá</label>
                            <select name="discount_code_id" class="form-select">
                                <option value="">-- Không liên kết --</option>
                                <?php foreach ($discountCodes as $dc): ?>
                                    <option value="<?= $dc['id'] ?>" <?= ($_POST['discount_code_id'] ?? '') == $dc['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dc['code']) ?> (<?= $dc['discount_percent'] ?>%)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Chọn mã giảm giá thực tế trong hệ thống</small>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Lợi ích</h5>
                    </div>
                    <div class="card-body">
                        <div id="benefits-container">
                            <?php
                            $benefits = $_POST['benefits'] ?? ['', '', '', ''];
                            foreach ($benefits as $index => $benefit):
                            ?>
                                <div class="mb-2">
                                    <input type="text" name="benefits[]" class="form-control" 
                                           value="<?= htmlspecialchars($benefit) ?>" 
                                           placeholder="Lợi ích <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addBenefit()">
                            <i class="bi bi-plus"></i> Thêm lợi ích
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thời gian & Cài đặt</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Chuỗi hiển thị thời gian</label>
                            <input type="text" name="period" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['period'] ?? '') ?>" 
                                   placeholder="VD: 01/11/2025 - 31/12/2025">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
                            <?php if (!empty($errors['end_date'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['end_date']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh (URL)</label>
                            <input type="text" name="image" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['image'] ?? '') ?>" 
                                   placeholder="https://...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Độ ưu tiên</label>
                            <input type="number" name="priority" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['priority'] ?? 0) ?>" 
                                   min="0" step="1">
                            <small class="text-muted">Số càng cao càng hiển thị trước</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CTA Text</label>
                            <input type="text" name="cta" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['cta'] ?? 'Đặt vé ngay') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CTA Link</label>
                            <input type="text" name="cta_link" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['cta_link'] ?? '') ?>" 
                                   placeholder="Để trống sẽ dùng trang đặt vé mặc định">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>?act=vouchers" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Tạo voucher
            </button>
        </div>
    </form>
</div>

<script>
function addBenefit() {
    const container = document.getElementById('benefits-container');
    const count = container.children.length;
    const input = document.createElement('div');
    input.className = 'mb-2';
    input.innerHTML = `
        <input type="text" name="benefits[]" class="form-control" 
               placeholder="Lợi ích ${count + 1}">
    `;
    container.appendChild(input);
}
</script>

