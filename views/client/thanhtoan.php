<?php
// Lấy dữ liệu từ controller
$showtime = $showtime ?? null;
$movie = $movie ?? null;
$room = $room ?? null;
$cinema = $cinema ?? null;
$selectedSeats = $selectedSeats ?? [];
$seatIds = $seatIds ?? '';
$seatLabels = $seatLabels ?? '';
$totalPrice = $totalPrice ?? 0;
$vipExtraPrice = $vipExtraPrice ?? 10000;

// Format ngày giờ
$showDate = $showtime['show_date'] ?? date('Y-m-d');
$showTime = $showtime['start_time'] ?? '';
$formattedDate = date('d/m/Y', strtotime($showDate));
$formattedTime = $showTime ? date('H:i', strtotime($showTime)) : '';

// Format giá tiền
function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/thanhtoan.css">

<div class="wrap">
    <!-- LEFT COLUMN -->
    <div>
        <!-- Movie info card -->
        <div class="card">
            <h3 class="section-title">Thông tin phim</h3>
            <div class="movie-info">
                <div class="movie-poster">
                    <?php if ($movie && !empty($movie['image'])): ?>
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>"
                            alt="<?= htmlspecialchars($movie['title']) ?>" />
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/image/logo.png" alt="Poster phim" />
                    <?php endif; ?>
                </div>
                <div class="movie-meta">
                    <div class="title">
                        <?= htmlspecialchars($movie['title'] ?? 'N/A') ?>
                    </div>

                    <div style="height: 12px"></div>

                    <!-- Thông tin chi tiết đặt vé -->
                    <div class="booking-details-section">
                        <div class="detail-row">
                            <span class="detail-label">Rạp chiếu:</span>
                            <span class="detail-value"><?= htmlspecialchars($cinema['name'] ?? 'N/A') ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phòng chiếu:</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($room['name'] ?? 'N/A') ?>
                                <?php if (!empty($room['room_code'])): ?>
                                    <span class="room-code">(<?= htmlspecialchars($room['room_code']) ?>)</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Ngày chiếu:</span>
                            <span class="detail-value"><?= $formattedDate ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Giờ chiếu:</span>
                            <span class="detail-value highlight-time">
                                <?= $formattedTime ?>
                                <?php if (!empty($showtime['end_time'])): ?>
                                    - <?= date('H:i', strtotime($showtime['end_time'])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Ghế đã chọn:</span>
                            <span class="detail-value seats-highlight">
                                <?php
                                $seatDisplay = [];
                                foreach ($selectedSeats as $seat) {
                                    $seatTypeName = ($seat['type'] === 'vip') ? 'VIP' : 'Thường';
                                    $seatDisplay[] = htmlspecialchars($seat['label']) . ' ' . $seatTypeName;
                                }
                                echo implode(', ', $seatDisplay);
                                ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Loại:</span>
                            <span class="detail-value format-badge">
                                <?= htmlspecialchars($showtime['format'] ?? '2D') ?>
                            </span>
                        </div>
                        <?php if (isset($adultCount) && isset($studentCount)): ?>
                            <div class="detail-row">
                                <span class="detail-label">Số lượng vé:</span>
                                <span class="detail-value">
                                    <?php if ($adultCount > 0): ?>
                                        <span class="ticket-type"><?= $adultCount ?> Người lớn</span>
                                    <?php endif; ?>
                                    <?php if ($studentCount > 0): ?>
                                        <?php if ($adultCount > 0): ?>, <?php endif; ?>
                                        <span class="ticket-type"><?= $studentCount ?> Sinh viên</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment items -->
        <div class="card">
            <h3 class="section-title">Thông tin thanh toán</h3>

            <table class="payment-table" aria-label="thông tin thanh toán">
                <thead>
                    <tr>
                        <th>Danh mục</th>
                        <th style="width: 90px; text-align: center">Số lượng</th>
                        <th style="width: 140px; text-align: right">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectedSeats as $seat): ?>
                        <tr class="table-row">
                            <td class="seat-info-cell">
                                <div class="seat-info">
                                    <span class="seat-label">Ghế <?= htmlspecialchars($seat['label']) ?></span>
                                    <div class="seat-tags">
                                        <?php if ($seat['type'] === 'vip'): ?>
                                            <span class="seat-tag vip-tag">VIP</span>
                                        <?php endif; ?>
                                        <?php if (isset($seat['customer_type'])): ?>
                                            <span
                                                class="seat-tag <?= $seat['customer_type'] === 'adult' ? 'adult-tag' : 'student-tag' ?>">
                                                <?= $seat['customer_type'] === 'adult' ? 'Người lớn' : 'Sinh viên' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center">1</td>
                            <td style="text-align: right" class="price-cell"><?= formatPrice($seat['price']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <aside>
        <div class="side-card">
            <h3 class="section-title">Phương thức thanh toán</h3>

            <div class="methods" id="methods">

                <!-- Phương thức thanh toán -->
                <!-- VNPAY -->
                <label class="method" data-method="vnpay" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/vnpay.png" alt="VNPAY" />
                    </div>
                    <div class="txt">VNPAY</div>
                    <input type="radio" name="pay" value="vnpay" style="display: none" />
                </label>

                <label class="method" data-method="vietqr" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/vietqr.png" alt="VietQR" />
                    </div>
                    <div class="txt">VietQR</div>
                    <input type="radio" name="pay" value="vietqr" style="display: none" />
                </label>

                <label class="method" data-method="viettel" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/viettel.png" alt="Viettel Money" />
                    </div>
                    <div class="txt">Viettel Money</div>
                    <input type="radio" name="pay" value="viettel" style="display: none" />
                </label>

                <label class="method" data-method="momo" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/momo.png" alt="MoMo" />
                    </div>
                    <div class="txt">MoMo</div>
                    <input type="radio" name="pay" value="momo" style="display: none" />
                </label>
            </div>

            <!-- Voucher code section -->
            <div class="voucher-section"
                style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <label for="voucherCode" style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">
                    Mã giảm giá / Voucher
                </label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="voucherCode" name="voucher_code" placeholder="Nhập mã voucher"
                        style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <button type="button" id="applyVoucherBtn"
                        style="padding: 10px 20px; background: #ff6978; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                        Áp dụng
                    </button>
                </div>
                <div id="voucherMessage" style="margin-top: 8px; font-size: 13px;"></div>
            </div>

            <div class="costs" id="costs">
                <div class="cost-row">
                    <div>Tổng tiền</div>
                    <div id="subtotal"><?= formatPrice($totalPrice) ?></div>
                </div>
                <div class="cost-row" id="discountRow" style="display: none;">
                    <div>Giảm giá</div>
                    <div id="discountAmount" style="color: #28a745;">-0đ</div>
                </div>
                <div class="cost-row">
                    <div>Phí</div>
                    <div id="fee">0đ</div>
                </div>
                <div class="cost-row total-row">
                    <div>Tổng cộng</div>
                    <div id="grandTotal"><?= formatPrice($totalPrice) ?></div>
                </div>
            </div>

            <label class="confirm">
                <input type="checkbox" id="agree" />
                <div>
                    Tôi xác nhận các thông tin đã chính xác và đồng ý với các
                    <a href="#" style="color: #9ad7ff; text-decoration: underline">điều khoản & chính sách</a>
                </div>
            </label>

            <button class="pay-btn" id="payBtn" disabled>Thanh toán</button>
            <a class="back-link" href="<?= BASE_URL ?>?act=datve&showtime_id=<?= $showtime['id'] ?? '' ?>">Quay lại</a>
        </div>
    </aside>
</div>

<script>
    // Payment processing
    const methods = document.querySelectorAll(".method");
    const agree = document.getElementById("agree");
    const payBtn = document.getElementById("payBtn");
    const subtotalEl = document.getElementById("subtotal");
    const feeEl = document.getElementById("fee");
    const grandEl = document.getElementById("grandTotal");

    // Numeric values (VND)
    let originalTotal = <?= $totalPrice ?>;
    let subtotal = originalTotal;
    let fee = 0;
    let appliedVoucher = null;

    function fmt(v) {
        return new Intl.NumberFormat("vi-VN").format(v) + "đ";
    }

    function updateTotals() {
        subtotal = originalTotal;
        const discount = appliedVoucher ? (subtotal * appliedVoucher.discount_percent / 100) : 0;
        fee = 0;
        const grandTotal = subtotal - discount + fee;

        subtotalEl.textContent = fmt(subtotal);
        feeEl.textContent = fmt(fee);

        if (discount > 0) {
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountAmount').textContent = '-' + fmt(discount);
        } else {
            document.getElementById('discountRow').style.display = 'none';
        }

        grandEl.textContent = fmt(grandTotal);
    }

    updateTotals();

    // Voucher code handler
    document.getElementById('applyVoucherBtn').addEventListener('click', function () {
        const voucherCode = document.getElementById('voucherCode').value.trim();
        const messageEl = document.getElementById('voucherMessage');

        if (!voucherCode) {
            messageEl.innerHTML = '<span style="color: #dc3545;">Vui lòng nhập mã voucher</span>';
            return;
        }

        messageEl.innerHTML = '<span style="color: #ffc107;">Đang kiểm tra mã voucher...</span>';

        // Kiểm tra discount code qua API
        const url = '<?= BASE_URL ?>?act=check-voucher&code=' + encodeURIComponent(voucherCode) + '&total_amount=' + originalTotal;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.discount_code) {
                    // Sử dụng data.discount_code
                    appliedVoucher = data.discount_code;
                    messageEl.innerHTML = '<span style="color: #28a745;">✓ ' + (data.message || 'Áp dụng thành công! Giảm ' + appliedVoucher.discount_percent + '%') + '</span>';
                    updateTotals();
                } else {
                    appliedVoucher = null;
                    messageEl.innerHTML = '<span style="color: #dc3545;">' + (data.message || 'Mã voucher không hợp lệ') + '</span>';
                    updateTotals();
                }
            })
            .catch(error => {
                console.error('Error checking voucher:', error);
                appliedVoucher = null;
                messageEl.innerHTML = '<span style="color: #dc3545;">Có lỗi xảy ra khi kiểm tra voucher. Vui lòng thử lại.</span>';
                updateTotals();
            });
    });

    let selectedMethod = null;
    methods.forEach((m) => {
        m.addEventListener("click", () => selectMethod(m));
        m.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") selectMethod(m);
        });
    });

    function selectMethod(el) {
        methods.forEach((x) => x.classList.remove("active"));
        el.classList.add("active");
        selectedMethod = el.dataset.method;
        // Adjust fee by method (if needed)
        if (selectedMethod === "vnpay") fee = 0;
        else if (selectedMethod === "vietqr") fee = 0;
        else if (selectedMethod === "momo") fee = 0;
        else fee = 0;
        feeEl.textContent = fmt(fee);
        grandEl.textContent = fmt(subtotal + fee);
        updatePayState();
    }

    function updatePayState() {
        payBtn.disabled = !(selectedMethod && agree.checked);
    }

    agree.addEventListener("change", updatePayState);

    payBtn.addEventListener("click", () => {
        if (!selectedMethod) {
            alert("Vui lòng chọn phương thức thanh toán.");
            return;
        }
        if (!agree.checked) {
            alert("Vui lòng đồng ý điều khoản.");
            return;
        }

        // Submit payment
        payBtn.disabled = true;
        payBtn.textContent = "Đang xử lý...";

        const formData = new FormData();
        formData.append('showtime_id', '<?= $showtime['id'] ?? '' ?>');
        formData.append('seats', '<?= htmlspecialchars($seatIds) ?>');
        formData.append('seat_labels', '<?= htmlspecialchars($seatLabels) ?>');
        formData.append('payment_method', selectedMethod);
        formData.append('adult_count', '<?= $adultCount ?? 0 ?>');
        formData.append('student_count', '<?= $studentCount ?? 0 ?>');

        // Thêm voucher code nếu có
        const voucherCode = document.getElementById('voucherCode').value.trim();
        if (voucherCode) {
            formData.append('voucher_code', voucherCode);
        }

        fetch('<?= BASE_URL ?>?act=payment-process', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.payment_method === 'vnpay' && data.payment_url) {
                        // Chuyển hướng đến VNPay
                        window.location.href = data.payment_url;
                    } else {
                        // Các phương thức thanh toán khác
                        alert(data.message || 'Thanh toán thành công!');
                        window.location.href = '<?= BASE_URL ?>?act=my-bookings';
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thanh toán.');
                    payBtn.disabled = false;
                    payBtn.textContent = "Thanh toán";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thanh toán.');
                payBtn.disabled = false;
                payBtn.textContent = "Thanh toán";
            });
    });

    // Preselect first method
    if (methods.length > 0) {
        selectMethod(methods[0]);
    }
</script>