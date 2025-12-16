<?php
// GIAVE.PHP - TRANG BẢNG GIÁ VÉ CLIENT
// Chức năng: Hiển thị bảng giá vé theo ngày, format, loại khách, loại ghế
// Biến từ controller: $groupedPrices (mảng giá đã group theo weekday/weekend, format, customer_type, seat_type)
$groupedPrices = $groupedPrices ?? [];
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/giave.css">

<div class="price-page">
    <div class="price-container">
        <h1 class="price-title">Bảng giá vé</h1>
        <p class="price-subtitle">Giá vé có thể thay đổi tùy theo ngày, loại phim và loại khách hàng</p>

        <div class="price-tables">
            <!-- Section 1: Thứ 2-5 (Ngày thường - Giảm 5.000đ) -->
            <div class="price-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="bi bi-calendar-week"></i>
                        Thứ 2 - Thứ 5
                    </h2>
                    <span class="section-badge weekday-badge">Ngày thường </span>
                </div>

                <div class="price-cards">
                    <!-- Vé 2D - Sinh viên -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 2D - Sinh viên</h3>
                            <span class="format-icon">🎓</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekday_2D_student']['normal'] ?? 55000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekday_2D_student']['vip'] ?? 65000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vé 2D - Người lớn -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 2D - Người lớn</h3>
                            <span class="format-icon">👤</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekday_2D_adult']['normal'] ?? 65000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekday_2D_adult']['vip'] ?? 75000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vé 3D - Sinh viên -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 3D - Sinh viên</h3>
                            <span class="format-icon">🎥</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekday_3D_student']['normal'] ?? 65000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekday_3D_student']['vip'] ?? 75000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vé 3D - Người lớn -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 3D - Người lớn</h3>
                            <span class="format-icon">🎬</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekday_3D_adult']['normal'] ?? 75000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekday_3D_adult']['vip'] ?? 85000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thứ 6-7-CN và ngày lễ (Weekend) -->
            <div class="price-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="bi bi-calendar-event"></i>
                        Thứ 6 - Chủ nhật & Ngày lễ
                    </h2>
                    <span class="section-badge weekend-badge">Cuối tuần (Tăng 10.000 đ)</span>
                </div>

                <div class="price-cards">
                    <!-- Vé 2D - Sinh viên -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 2D - Sinh viên</h3>
                            <span class="format-icon">🎓</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekend_2D_student']['normal'] ?? 65000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekend_2D_student']['vip'] ?? 75000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vé 2D - Người lớn -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 2D - Người lớn</h3>
                            <span class="format-icon">👤</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekend_2D_adult']['normal'] ?? 75000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekend_2D_adult']['vip'] ?? 85000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vé 3D - Sinh viên -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 3D - Sinh viên</h3>
                            <span class="format-icon">🎥</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekend_3D_student']['normal'] ?? 75000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekend_3D_student']['vip'] ?? 85000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vé 3D - Người lớn -->
                    <div class="price-card">
                        <div class="card-header">
                            <h3 class="format-title">Vé 3D - Người lớn</h3>
                            <span class="format-icon">🎬</span>
                        </div>
                        <div class="card-body">
                            <div class="price-item">
                                <span class="seat-type">
                                    <i class="bi bi-circle-fill"></i>
                                    Ghế thường
                                </span>
                                <span class="price-value">
                                    <?= number_format($groupedPrices['weekend_3D_adult']['normal'] ?? 85000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                            <div class="price-item vip-item">
                                <span class="seat-type">
                                    <i class="bi bi-star-fill"></i>
                                    Ghế VIP
                                </span>
                                <span class="price-value vip-price">
                                    <?= number_format($groupedPrices['weekend_3D_adult']['vip'] ?? 95000, 0, ',', '.') ?> đ
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lưu ý -->
        <div class="price-note">
            <div class="note-card">
                <i class="bi bi-info-circle"></i>
                <div class="note-content">
                    <h4>Lưu ý</h4>
                    <ul>
                        <li><strong>Giá cơ bản:</strong> Sinh viên 60.000 đ, Người lớn 70.000 đ (ghế thường)</li>
                        <li><strong>Phụ thu VIP:</strong> +10.000 đ</li>
                        <li><strong>Phụ thu 3D:</strong> +10.000 đ</li>
                        <li><strong>Thứ 2-5:</strong> Giảm 5.000 đ so với giá cơ bản</li>
                        <li><strong>Thứ 6-7-CN và ngày lễ:</strong> Tăng 5.000 đ so với giá cơ bản</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
