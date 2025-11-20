<?php
$errors = $errors ?? [];
$discountCodes = $discountCodes ?? [];
?>

<?php
$voucher = $voucher ?? [];
$errors = $errors ?? [];
$discountCodes = $discountCodes ?? [];
$benefits = $voucher['benefits'] ?? [];
if (is_string($benefits)) {
    $benefits = explode('|', $benefits);
}
if (empty($benefits)) {
    $benefits = ['', '', '', ''];
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Sửa voucher</h2>
        <a href="<?= BASE_URL ?>?act=vouchers" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>?act=vouchers-edit&id=<?= $voucher['id'] ?? '' ?>">
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
                                   value="<?= htmlspecialchars($_POST['title'] ?? $voucher['title'] ?? '') ?>" required>
                            <?php if (!empty($errors['title'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" 
                                      rows="4" required><?= htmlspecialchars($_POST['description'] ?? $voucher['description'] ?? '') ?></textarea>
                            <?php if (!empty($errors['description'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tag</label>
                                <select name="tag" class="form-select">
                                    <?php $currentTag = $_POST['tag'] ?? $voucher['tag'] ?? 'general'; ?>
                                    <option value="general" <?= $currentTag === 'general' ? 'selected' : '' ?>>General</option>
                                    <option value="flash" <?= $currentTag === 'flash' ? 'selected' : '' ?>>Flash</option>
                                    <option value="member" <?= $currentTag === 'member' ? 'selected' : '' ?>>Member</option>
                                    <option value="newuser" <?= $currentTag === 'newuser' ? 'selected' : '' ?>>New User</option>
                                    <option value="student" <?= $currentTag === 'student' ? 'selected' : '' ?>>Student</option>
                                    <option value="combo" <?= $currentTag === 'combo' ? 'selected' : '' ?>>Combo</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <?php $currentStatus = $_POST['status'] ?? $voucher['status'] ?? 'active'; ?>
                                    <option value="active" <?= $currentStatus === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="ongoing" <?= $currentStatus === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                    <option value="upcoming" <?= $currentStatus === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                                    <option value="inactive" <?= $currentStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mã voucher (hiển thị)</label>
                            <input type="text" name="code" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['code'] ?? $voucher['code'] ?? '') ?>" 
                                   placeholder="VD: WELCOME20">
                            <small class="text-muted">Mã này sẽ hiển thị cho người dùng</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Liên kết với mã giảm giá</label>
                            <select name="discount_code_id" class="form-select">
                                <option value="">-- Không liên kết --</option>
                                <?php 
                                $currentDiscountId = $_POST['discount_code_id'] ?? $voucher['discount_code_id'] ?? '';
                                foreach ($discountCodes as $dc): 
                                ?>
                                    <option value="<?= $dc['id'] ?>" <?= $currentDiscountId == $dc['id'] ? 'selected' : '' ?>>
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
                            $editBenefits = $_POST['benefits'] ?? $benefits;
                            if (empty($editBenefits)) {
                                $editBenefits = ['', '', '', ''];
                            }
                            foreach ($editBenefits as $index => $benefit):
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
                                   value="<?= htmlspecialchars($_POST['period'] ?? $voucher['period'] ?? '') ?>" 
                                   placeholder="VD: 01/11/2025 - 31/12/2025">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" class="form-control" 
                                   value="<?= !empty($voucher['start_date']) ? date('Y-m-d\TH:i', strtotime($voucher['start_date'])) : (htmlspecialchars($_POST['start_date'] ?? '')) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" class="form-control" 
                                   value="<?= !empty($voucher['end_date']) ? date('Y-m-d\TH:i', strtotime($voucher['end_date'])) : (htmlspecialchars($_POST['end_date'] ?? '')) ?>">
                            <?php if (!empty($errors['end_date'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['end_date']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh (URL)</label>
                            <input type="text" name="image" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['image'] ?? $voucher['image'] ?? '') ?>" 
                                   placeholder="https://...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Độ ưu tiên</label>
                            <input type="number" name="priority" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['priority'] ?? $voucher['priority'] ?? 0) ?>" 
                                   min="0" step="1">
                            <small class="text-muted">Số càng cao càng hiển thị trước</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CTA Text</label>
                            <input type="text" name="cta" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['cta'] ?? $voucher['cta'] ?? 'Đặt vé ngay') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CTA Link</label>
                            <input type="text" name="cta_link" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['cta_link'] ?? $voucher['cta_link'] ?? '') ?>" 
                                   placeholder="Để trống sẽ dùng trang đặt vé mặc định">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>?act=vouchers" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Cập nhật voucher
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

