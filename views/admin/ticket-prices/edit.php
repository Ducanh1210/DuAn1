<?php
// TICKET-PRICES/EDIT.PHP - TRANG CHỈNH SỬA GIÁ VÉ ADMIN
// Chức năng: Form chỉnh sửa giá vé theo ngày (thứ 2-5: ngày thường, thứ 6-CN: cuối tuần), format (2D/3D), loại khách (sinh viên/người lớn), loại ghế (thường/VIP)
// Biến từ controller: $weekdayPrices (giá ngày thường), $weekendPrices (giá cuối tuần)
// Helper function: getPrice() - tìm giá theo format, customer_type, seat_type
?>
<?php
// Khởi tạo mảng giá nếu chưa có
$weekdayPrices = $weekdayPrices ?? [];
$weekendPrices = $weekendPrices ?? [];

// Helper function: tìm giá theo điều kiện (format, customer_type, seat_type)
function getPrice($prices, $format, $customerType, $seatType) {
    // Vòng lặp: duyệt qua mảng $prices để tìm giá phù hợp
    foreach ($prices as $price) {
        // So sánh: format, customer_type, seat_type phải khớp
        if ($price['format'] === $format && 
            $price['customer_type'] === $customerType && 
            $price['seat_type'] === $seatType) {
            return $price; // Trả về giá tìm được
        }
    }
    return null; // Không tìm thấy
}
?>

<div class="container-fluid py-4">
    <!-- Header: tiêu đề và nút quay lại -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Chỉnh sửa giá vé</h2>
        <a href="<?= BASE_URL ?>?act=ticket-prices" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Hiển thị thông báo thành công: từ $_SESSION['success_message'] -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); // Xóa sau khi hiển thị ?>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi: từ $_SESSION['error_message'] -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); // Xóa sau khi hiển thị ?>
    <?php endif; ?>

    <!-- Form chỉnh sửa giá vé: submit đến act=ticket-prices-update -->
    <form action="<?= BASE_URL ?>?act=ticket-prices-update" method="POST">
        <!-- Card giá ngày thường: Thứ 2-5 (giảm 5.000đ) -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week"></i> Thứ 2 - Thứ 5 (Ngày thường - Giảm 5.000 đ)
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Cột trái: Vé 2D -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 2D</h6>
                        
                        <!-- Giá vé sinh viên 2D: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        // Lấy giá vé sinh viên 2D: ghế thường và VIP từ $weekdayPrices
                        $student2DNormal = getPrice($weekdayPrices, '2D', 'student', 'normal');
                        $student2DVip = getPrice($weekdayPrices, '2D', 'student', 'vip');
                        ?>
                        <!-- Input giá ghế thường sinh viên 2D: name là prices[id], value lấy từ $student2DNormal hoặc mặc định 55000 -->
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
                        <!-- Input giá ghế VIP sinh viên 2D: name là prices[id], value lấy từ $student2DVip hoặc mặc định 65000 -->
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

                        <!-- Giá vé người lớn 2D: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        // Lấy giá vé người lớn 2D: ghế thường và VIP từ $weekdayPrices
                        $adult2DNormal = getPrice($weekdayPrices, '2D', 'adult', 'normal');
                        $adult2DVip = getPrice($weekdayPrices, '2D', 'adult', 'vip');
                        ?>
                        <!-- Input giá ghế thường người lớn 2D: name là prices[id], value lấy từ $adult2DNormal hoặc mặc định 65000 -->
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
                        <!-- Input giá ghế VIP người lớn 2D: name là prices[id], value lấy từ $adult2DVip hoặc mặc định 75000 -->
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

                    <!-- Cột phải: Vé 3D -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 3D</h6>
                        
                        <!-- Giá vé sinh viên 3D: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        // Lấy giá vé sinh viên 3D: ghế thường và VIP từ $weekdayPrices
                        $student3DNormal = getPrice($weekdayPrices, '3D', 'student', 'normal');
                        $student3DVip = getPrice($weekdayPrices, '3D', 'student', 'vip');
                        ?>
                        <!-- Input giá ghế thường sinh viên 3D: name là prices[id], value lấy từ $student3DNormal hoặc mặc định 65000 -->
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
                        <!-- Input giá ghế VIP sinh viên 3D: name là prices[id], value lấy từ $student3DVip hoặc mặc định 75000 -->
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

                        <!-- Giá vé người lớn 3D: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        // Lấy giá vé người lớn 3D: ghế thường và VIP từ $weekdayPrices
                        $adult3DNormal = getPrice($weekdayPrices, '3D', 'adult', 'normal');
                        $adult3DVip = getPrice($weekdayPrices, '3D', 'adult', 'vip');
                        ?>
                        <!-- Input giá ghế thường người lớn 3D: name là prices[id], value lấy từ $adult3DNormal hoặc mặc định 75000 -->
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
                        <!-- Input giá ghế VIP người lớn 3D: name là prices[id], value lấy từ $adult3DVip hoặc mặc định 85000 -->
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

        <!-- Card giá cuối tuần: Thứ 6-CN & ngày lễ (tăng 5.000đ) -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event"></i> Thứ 6 - Chủ nhật & Ngày lễ (Cuối tuần - Tăng 5.000 đ)
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Cột trái: Vé 2D cuối tuần -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 2D</h6>
                        
                        <!-- Giá vé sinh viên 2D cuối tuần: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        // Lấy giá vé sinh viên 2D cuối tuần: ghế thường và VIP từ $weekendPrices
                        $student2DNormalWeekend = getPrice($weekendPrices, '2D', 'student', 'normal');
                        $student2DVipWeekend = getPrice($weekendPrices, '2D', 'student', 'vip');
                        ?>
                        <!-- Input giá ghế thường sinh viên 2D cuối tuần: name là prices[id], value lấy từ $student2DNormalWeekend hoặc mặc định 65000 -->
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
                        <!-- Input giá ghế VIP sinh viên 2D cuối tuần: name là prices[id], value lấy từ $student2DVipWeekend hoặc mặc định 75000 -->
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

                        <!-- Giá vé người lớn 2D cuối tuần: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        // Lấy giá vé người lớn 2D cuối tuần: ghế thường và VIP từ $weekendPrices
                        $adult2DNormalWeekend = getPrice($weekendPrices, '2D', 'adult', 'normal');
                        $adult2DVipWeekend = getPrice($weekendPrices, '2D', 'adult', 'vip');
                        ?>
                        <!-- Input giá ghế thường người lớn 2D cuối tuần: name là prices[id], value lấy từ $adult2DNormalWeekend hoặc mặc định 75000 -->
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
                        <!-- Input giá ghế VIP người lớn 2D cuối tuần: name là prices[id], value lấy từ $adult2DVipWeekend hoặc mặc định 85000 -->
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

                    <!-- Cột phải: Vé 3D cuối tuần -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-3 border-bottom pb-2">Vé 3D</h6>
                        
                        <!-- Giá vé sinh viên 3D cuối tuần: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2">Sinh viên</h6>
                        <?php 
                        // Lấy giá vé sinh viên 3D cuối tuần: ghế thường và VIP từ $weekendPrices
                        $student3DNormalWeekend = getPrice($weekendPrices, '3D', 'student', 'normal');
                        $student3DVipWeekend = getPrice($weekendPrices, '3D', 'student', 'vip');
                        ?>
                        <!-- Input giá ghế thường sinh viên 3D cuối tuần: name là prices[id], value lấy từ $student3DNormalWeekend hoặc mặc định 75000 -->
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
                        <!-- Input giá ghế VIP sinh viên 3D cuối tuần: name là prices[id], value lấy từ $student3DVipWeekend hoặc mặc định 85000 -->
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

                        <!-- Giá vé người lớn 3D cuối tuần: ghế thường và VIP -->
                        <h6 class="small text-muted mb-2 mt-3">Người lớn</h6>
                        <?php 
                        // Lấy giá vé người lớn 3D cuối tuần: ghế thường và VIP từ $weekendPrices
                        $adult3DNormalWeekend = getPrice($weekendPrices, '3D', 'adult', 'normal');
                        $adult3DVipWeekend = getPrice($weekendPrices, '3D', 'adult', 'vip');
                        ?>
                        <!-- Input giá ghế thường người lớn 3D cuối tuần: name là prices[id], value lấy từ $adult3DNormalWeekend hoặc mặc định 85000 -->
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
                        <!-- Input giá ghế VIP người lớn 3D cuối tuần: name là prices[id], value lấy từ $adult3DVipWeekend hoặc mặc định 95000 -->
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

        <!-- Nút thao tác: hủy và lưu thay đổi -->
        <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>?act=ticket-prices" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>

