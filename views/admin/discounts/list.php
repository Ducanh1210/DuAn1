<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Quản lý mã giảm giá</h4>
            <div>
                <a href="<?= BASE_URL ?>?act=discounts-create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm mã giảm giá mới
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã giảm giá</th>
                            <th>Tiêu đề</th>
                            <th>Áp dụng cho</th>
                            <th>Giảm giá</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php foreach ($data as $item): ?>
                                <?php
                                $statusLabels = [
                                    'active' => ['label' => 'Đang hoạt động', 'class' => 'success'],
                                    'upcoming' => ['label' => 'Sắp diễn ra', 'class' => 'info'],
                                    'inactive' => ['label' => 'Đã tắt', 'class' => 'secondary'],
                                    'ended' => ['label' => 'Đã kết thúc', 'class' => 'dark']
                                ];
                                $status = $statusLabels[$item['status']] ?? ['label' => $item['status'], 'class' => 'secondary'];

                                $applyToLabels = [
                                    'ticket' => 'Vé phim',
                                    'food' => 'Đồ ăn & đồ uống',
                                    'combo' => 'Combo vé + F&B'
                                ];
                                $applyToValue = $item['apply_to'] ?? 'ticket';
                                $applyTo = $applyToLabels[$applyToValue] ?? $applyToValue;

                                $startDate = !empty($item['start_date']) ? date('d/m/Y', strtotime($item['start_date'])) : '-';
                                $endDate = !empty($item['end_date']) ? date('d/m/Y', strtotime($item['end_date'])) : '-';
                                ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><strong class="text-primary"><?= htmlspecialchars($item['code']) ?></strong></td>
                                    <td><?= htmlspecialchars($item['title']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($applyTo) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= number_format($item['discount_percent'], 0) ?>%</strong>
                                        <?php if (!empty($item['max_discount'])): ?>
                                            <br><small class="text-muted">Tối đa: <?= number_format($item['max_discount'], 0) ?>đ</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="bi bi-calendar-event"></i> <?= $startDate ?><br>
                                            <i class="bi bi-calendar-x"></i> <?= $endDate ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $status['class'] ?>"><?= $status['label'] ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= BASE_URL ?>?act=discounts-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>?act=discounts-delete&id=<?= $item['id'] ?>"
                                                class="btn btn-sm btn-danger"
                                                title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Chưa có mã giảm giá nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>