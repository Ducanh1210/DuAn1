<?php
$vouchers = $vouchers ?? [];
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Quản lý Voucher/Khuyến mãi</h2>
        <a href="<?= BASE_URL ?>?act=vouchers-create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm voucher mới
        </a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($vouchers)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3">Chưa có voucher nào</p>
                    <a href="<?= BASE_URL ?>?act=vouchers-create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Thêm voucher đầu tiên
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Mã</th>
                                <th>Tag</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Ưu tiên</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vouchers as $voucher): ?>
                                <tr>
                                    <td><?= htmlspecialchars($voucher['id']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($voucher['title']) ?></strong>
                                        <?php if (!empty($voucher['description'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars(mb_substr($voucher['description'], 0, 50)) ?>...
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($voucher['code'])): ?>
                                            <span class="badge bg-info"><?= htmlspecialchars($voucher['code']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $tagLabels = [
                                            'general' => 'Tổng quát',
                                            'flash' => 'Flash Sale',
                                            'member' => 'Thành viên',
                                            'newuser' => 'Người dùng mới',
                                            'student' => 'Sinh viên',
                                            'combo' => 'Combo'
                                        ];
                                        $tagLabel = $tagLabels[$voucher['tag']] ?? ucfirst($voucher['tag']);
                                        ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($tagLabel) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusLabels = [
                                            'active' => 'Đang hoạt động',
                                            'ongoing' => 'Đang diễn ra',
                                            'upcoming' => 'Sắp diễn ra',
                                            'ended' => 'Đã kết thúc',
                                            'inactive' => 'Không hoạt động'
                                        ];
                                        $statusClass = match($voucher['status']) {
                                            'active', 'ongoing' => 'success',
                                            'upcoming' => 'warning',
                                            'ended', 'inactive' => 'secondary',
                                            default => 'secondary'
                                        };
                                        $statusLabel = $statusLabels[$voucher['status']] ?? ucfirst($voucher['status']);
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= htmlspecialchars($statusLabel) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($voucher['start_date']) || !empty($voucher['end_date'])): ?>
                                            <?php if (!empty($voucher['start_date'])): ?>
                                                <small><?= date('d/m/Y', strtotime($voucher['start_date'])) ?></small>
                                            <?php endif; ?>
                                            <?php if (!empty($voucher['start_date']) && !empty($voucher['end_date'])): ?>
                                                <br><small class="text-muted">→</small><br>
                                            <?php endif; ?>
                                            <?php if (!empty($voucher['end_date'])): ?>
                                                <small><?= date('d/m/Y', strtotime($voucher['end_date'])) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= htmlspecialchars($voucher['priority']) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= BASE_URL ?>?act=vouchers-edit&id=<?= $voucher['id'] ?>" 
                                               class="btn btn-outline-primary" title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>?act=vouchers-delete&id=<?= $voucher['id'] ?>" 
                                               class="btn btn-outline-danger" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?')" 
                                               title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

