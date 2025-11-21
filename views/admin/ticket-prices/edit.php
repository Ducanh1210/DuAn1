<?php
$weekdayPrices = $weekdayPrices ?? [];
$weekendPrices = $weekendPrices ?? [];

// Helper function để lấy giá theo điều kiện
function getPrice($prices, $format, $customerType, $seatType) {
    foreach ($prices as $price) {
        if ($price['format'] === $format && 
            $price['customer_type'] === $customerType && 
            $price['seat_type'] === $seatType) {
            return $price;
        }
    }
    return null;
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Chỉnh sửa giá vé</h2>
        <a href="<?= BASE_URL ?>?act=ticket-prices" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
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

    <form action="<?= BASE_URL ?>?act=ticket-prices-update" method="POST">
        <!-- Thứ 2-5 -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week"></i> Thứ 2 - Thứ 5 (Ngày thường - Giảm 5.000 đ)
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Vé 2D -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 2D</h6>
                        
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        $student2DNormal = getPrice($weekdayPrices, '2D', 'student', 'normal');
                        $student2DVip = getPrice($weekdayPrices, '2D', 'student', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student2DNormal['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student2DNormal['base_price'] ?? 55000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student2DVip['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student2DVip['base_price'] ?? 65000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>

                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        $adult2DNormal = getPrice($weekdayPrices, '2D', 'adult', 'normal');
                        $adult2DVip = getPrice($weekdayPrices, '2D', 'adult', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult2DNormal['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult2DNormal['base_price'] ?? 65000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult2DVip['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult2DVip['base_price'] ?? 75000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                    </div>

                    <!-- Vé 3D -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 3D</h6>
                        
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        $student3DNormal = getPrice($weekdayPrices, '3D', 'student', 'normal');
                        $student3DVip = getPrice($weekdayPrices, '3D', 'student', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student3DNormal['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student3DNormal['base_price'] ?? 65000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student3DVip['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student3DVip['base_price'] ?? 75000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>

                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        $adult3DNormal = getPrice($weekdayPrices, '3D', 'adult', 'normal');
                        $adult3DVip = getPrice($weekdayPrices, '3D', 'adult', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult3DNormal['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult3DNormal['base_price'] ?? 75000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult3DVip['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult3DVip['base_price'] ?? 85000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thứ 6-7-CN và ngày lễ -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event"></i> Thứ 6 - Chủ nhật & Ngày lễ (Cuối tuần - Tăng 5.000 đ)
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Vé 2D -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 2D</h6>
                        
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        $student2DNormalWeekend = getPrice($weekendPrices, '2D', 'student', 'normal');
                        $student2DVipWeekend = getPrice($weekendPrices, '2D', 'student', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student2DNormalWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student2DNormalWeekend['base_price'] ?? 65000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student2DVipWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student2DVipWeekend['base_price'] ?? 75000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>

                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        $adult2DNormalWeekend = getPrice($weekendPrices, '2D', 'adult', 'normal');
                        $adult2DVipWeekend = getPrice($weekendPrices, '2D', 'adult', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult2DNormalWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult2DNormalWeekend['base_price'] ?? 75000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult2DVipWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult2DVipWeekend['base_price'] ?? 85000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                    </div>

                    <!-- Vé 3D -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 3D</h6>
                        
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        $student3DNormalWeekend = getPrice($weekendPrices, '3D', 'student', 'normal');
                        $student3DVipWeekend = getPrice($weekendPrices, '3D', 'student', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student3DNormalWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student3DNormalWeekend['base_price'] ?? 75000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $student3DVipWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($student3DVipWeekend['base_price'] ?? 85000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>

                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        $adult3DNormalWeekend = getPrice($weekendPrices, '3D', 'adult', 'normal');
                        $adult3DVipWeekend = getPrice($weekendPrices, '3D', 'adult', 'vip');
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Ghế thường (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult3DNormalWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult3DNormalWeekend['base_price'] ?? 85000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghế VIP (VNĐ)</label>
                            <input type="number" 
                                   name="prices[<?= $adult3DVipWeekend['id'] ?? '' ?>]" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($adult3DVipWeekend['base_price'] ?? 95000) ?>" 
                                   min="0" 
                                   step="1000" 
                                   required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>?act=ticket-prices" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>

