<?php
// Lấy dữ liệu từ controller
$success = $success ?? false;
$message = $message ?? '';
$bookingId = $bookingId ?? null;
$booking = $booking ?? null;
$transactionCode = $transactionCode ?? null;
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/thanhtoan.css">

<style>
.payment-result-container {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
}

.payment-result-card {
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(0, 0, 0, 0.03));
    border-radius: 24px;
    padding: 60px 80px;
    text-align: center;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
    max-width: 800px;
    margin: 0 auto;
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.payment-result-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.payment-result-icon svg {
    width: 64px;
    height: 64px;
}

.payment-result-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 15px;
    color: rgba(255, 255, 255, 0.95);
}

.payment-result-message {
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 40px;
    font-size: 18px;
    line-height: 1.6;
}

.payment-info-box {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 30px 40px;
    margin-bottom: 30px;
    text-align: left;
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
}

.payment-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.payment-info-row:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.payment-info-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 16px;
    font-weight: 500;
}

.payment-info-value {
    color: #fff;
    font-size: 18px;
    font-weight: 700;
}

.payment-info-value.booking-code {
    font-size: 20px;
    color: #4a9eff;
    letter-spacing: 1px;
    font-family: 'Courier New', monospace;
}

.payment-info-value.total-price {
    font-size: 28px;
    color: #ff4b4b;
    font-weight: 800;
}

.payment-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 40px;
}

.payment-btn {
    padding: 16px 40px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-width: 180px;
    justify-content: center;
}

.payment-btn-primary {
    background: linear-gradient(90deg, #ff3d57, #ff6b6b);
    color: #fff;
    box-shadow: 0 4px 15px rgba(255, 61, 87, 0.3);
}

.payment-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 61, 87, 0.4);
    background: linear-gradient(90deg, #ff2d47, #ff5b5b);
}

.payment-btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.payment-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.3);
}

.payment-result-icon.error {
    background: linear-gradient(135deg, #f44336, #d32f2f);
    box-shadow: 0 8px 25px rgba(244, 67, 54, 0.4);
}

.payment-result-title.error {
    color: #f44336;
}

.payment-info-box.error {
    border-color: rgba(244, 67, 54, 0.3);
}

/* Light Mode */
[data-theme="light"] .payment-result-card {
    background: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1) !important;
}

[data-theme="light"] .payment-result-title {
    color: #1a1a1a !important;
}

[data-theme="light"] .payment-result-title[style*="color: #4caf50"] {
    color: #4caf50 !important;
}

[data-theme="light"] .payment-result-message {
    color: #333 !important;
}

[data-theme="light"] .payment-info-box {
    background: #f8f9fa !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
}

[data-theme="light"] .payment-info-label {
    color: #666 !important;
}

[data-theme="light"] .payment-info-value {
    color: #1a1a1a !important;
}

[data-theme="light"] .payment-info-value.booking-code {
    color: #0066cc !important;
}

[data-theme="light"] .payment-info-value.total-price {
    color: #ff4b4b !important;
}

[data-theme="light"] .payment-info-row {
    border-bottom-color: rgba(0, 0, 0, 0.08) !important;
}

[data-theme="light"] .payment-btn-secondary {
    background: #f8f9fa !important;
    color: #1a1a1a !important;
    border: 1px solid rgba(0, 0, 0, 0.15) !important;
}

[data-theme="light"] .payment-btn-secondary:hover {
    background: #e9ecef !important;
    border-color: rgba(0, 0, 0, 0.2) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .payment-result-card {
        padding: 40px 30px;
        border-radius: 16px;
    }
    
    .payment-result-title {
        font-size: 24px;
    }
    
    .payment-result-message {
        font-size: 16px;
    }
    
    .payment-info-box {
        padding: 20px;
    }
    
    .payment-info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .payment-btn {
        width: 100%;
        min-width: auto;
    }
}
</style>

<div class="payment-result-container">
    <div class="payment-result-card">
        <?php if ($success): ?>
            <!-- Thành công - Thanh toán thành công -->
            <div class="payment-result-icon" style="background: linear-gradient(135deg, #4caf50, #388e3c);">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="white"/>
                </svg>
            </div>
            <h2 class="payment-result-title" style="color: #4caf50;">Thanh toán thành công</h2>
            
            <p class="payment-result-message">
                Thanh toán đã được thực hiện thành công! Đơn đặt vé của bạn đã được xác nhận.<br>
                Bạn có thể xem chi tiết đơn đặt vé trong mục "Vé của tôi".
            </p>

            <?php if ($booking): ?>
                <div class="payment-info-box">
                    <div class="payment-info-row">
                        <span class="payment-info-label">Mã đặt vé:</span>
                        <strong class="payment-info-value booking-code"><?= htmlspecialchars($booking['booking_code'] ?? $bookingId) ?></strong>
                    </div>
                    <?php if ($transactionCode): ?>
                        <div class="payment-info-row">
                            <span class="payment-info-label">Mã giao dịch:</span>
                            <strong class="payment-info-value"><?= htmlspecialchars($transactionCode) ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Tổng tiền:</span>
                        <strong class="payment-info-value total-price">
                            <?= number_format($booking['final_amount'] ?? 0, 0, ',', '.') ?>₫
                        </strong>
                    </div>
                </div>
            <?php endif; ?>

            <div class="payment-actions">
                <a href="<?= BASE_URL ?>?act=my-bookings" class="payment-btn payment-btn-primary">
                    Xem vé của tôi
                </a>
                <a href="<?= BASE_URL ?>?act=trangchu" class="payment-btn payment-btn-secondary">
                    Về trang chủ
                </a>
            </div>
        <?php else: ?>
            <!-- Thất bại -->
            <div class="payment-result-icon error">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" fill="white"/>
                </svg>
            </div>
            <h2 class="payment-result-title error">Thanh toán thất bại</h2>

            <p class="payment-result-message">
                <?= htmlspecialchars($message) ?>
            </p>

            <?php if ($booking): ?>
                <div class="payment-info-box error">
                    <div class="payment-info-row">
                        <span class="payment-info-label">Mã đặt vé:</span>
                        <strong class="payment-info-value booking-code"><?= htmlspecialchars($booking['booking_code'] ?? $bookingId) ?></strong>
                    </div>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Tổng tiền:</span>
                        <strong class="payment-info-value total-price">
                            <?= number_format($booking['final_amount'] ?? 0, 0, ',', '.') ?>₫
                        </strong>
                    </div>
                </div>
            <?php endif; ?>

            <div class="payment-actions">
                <?php if ($booking && isset($booking['id'])): ?>
                    <a href="<?= BASE_URL ?>?act=payment&booking_id=<?= htmlspecialchars($booking['id']) ?>" class="payment-btn payment-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" fill="currentColor"/>
                        </svg>
                        Thử lại thanh toán
                    </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>?act=trangchu" class="payment-btn payment-btn-secondary">
                    Về trang chủ
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

