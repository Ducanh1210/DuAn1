<?php
// TICKET-PRICES/LIST.PHP - TRANG QUẢN LÝ GIÁ VÉ ADMIN
// Chức năng: Hiển thị bảng giá vé theo các tiêu chí (ngày trong tuần/cuối tuần, loại phim 2D/3D, loại khách hàng, loại ghế)
// Biến từ controller: $weekdayPrices (giá vé ngày thường), $weekendPrices (giá vé cuối tuần)

// Khởi tạo mảng giá nếu không có
$weekdayPrices = $weekdayPrices ?? [];
$weekendPrices = $weekendPrices ?? [];

// Hàm helper: format giá tiền với dấu phẩy và đơn vị "đ"
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

// Hàm helper: lấy label cho loại ngày (weekday hoặc weekend)
function getDayTypeLabel($dayType) {
    return $dayType === 'weekday' ? 'Thứ 2-5' : 'Thứ 6-CN & Ngày lễ';
}

// Hàm helper: lấy label cho loại phim (2D hoặc 3D)
function getFormatLabel($format) {
    return $format === '2D' ? '2D' : '3D';
}

// Hàm helper: lấy label cho loại khách hàng (adult hoặc student)
function getCustomerTypeLabel($customerType) {
    return $customerType === 'adult' ? 'Người lớn' : 'Sinh viên';
}

// Hàm helper: lấy label cho loại ghế (vip hoặc normal)
function getSeatTypeLabel($seatType) {
    return $seatType === 'vip' ? 'VIP' : 'Thường';
}
?>

<div class="container-fluid py-4">
    <!-- Header: tiêu đề và nút chỉnh sửa giá vé -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Quản lý giá vé</h2>
        <!-- Link đến trang chỉnh sửa giá vé -->
        <a href="<?= BASE_URL ?>?act=ticket-prices-edit" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Chỉnh sửa giá vé
        </a>
    </div>

    <!-- Hiển thị thông báo thành công từ session -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <!-- Xóa thông báo sau khi hiển thị -->
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi từ session -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <!-- Xóa thông báo sau khi hiển thị -->
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Thứ 2-5 -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar-week"></i> Thứ 2 - Thứ 5 (Ngày thường )
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Loại</th>
                            <th>Loại khách hàng</th>
                            <th>Loại ghế</th>
                            <th class="text-end">Giá vé (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($weekdayPrices)): ?>
                            <?php foreach ($weekdayPrices as $price): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-info"><?= getFormatLabel($price['format']) ?></span>
                                </td>
                                <td><?= getCustomerTypeLabel($price['customer_type']) ?></td>
                                <td>
                                    <?php if ($price['seat_type'] === 'vip'): ?>
                                        <span class="badge bg-warning text-dark">VIP</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Thường</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <strong><?= formatPrice($price['base_price']) ?></strong>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Chưa có dữ liệu giá vé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Thứ 6-7-CN và ngày lễ -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar-event"></i> Thứ 6 - Chủ nhật & Ngày lễ (Cuối tuần - Tăng 10.000 đ)
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Loại</th>
                            <th>Loại khách hàng</th>
                            <th>Loại ghế</th>
                            <th class="text-end">Giá vé (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($weekendPrices)): ?>
                            <?php foreach ($weekendPrices as $price): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-info"><?= getFormatLabel($price['format']) ?></span>
                                </td>
                                <td><?= getCustomerTypeLabel($price['customer_type']) ?></td>
                                <td>
                                    <?php if ($price['seat_type'] === 'vip'): ?>
                                        <span class="badge bg-warning text-dark">VIP</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Thường</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <strong><?= formatPrice($price['base_price']) ?></strong>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Chưa có dữ liệu giá vé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
