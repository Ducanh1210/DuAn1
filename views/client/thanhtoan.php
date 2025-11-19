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
function formatPrice($price) {
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
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>" />
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/image/logo.png" alt="Poster phim" />
                    <?php endif; ?>
                </div>
                <div class="movie-meta">
                    <div class="title">
                        <?= htmlspecialchars($movie['title'] ?? 'N/A') ?>
                    </div>

                    <div style="height: 8px"></div>
                    <div class="meta-grid">
                        <div>
                            <span class="k">Ngày giờ chiếu</span><br />
                            <strong style="color: var(--accent)"><?= $formattedTime ?></strong>
                            · <?= $formattedDate ?>
                        </div>
                        <div>
                            <span class="k">Ghế</span><br />
                            <?= htmlspecialchars($seatLabels) ?>
                        </div>
                        <div>
                            <span class="k">Định dạng</span><br />
                            <?= htmlspecialchars($showtime['format'] ?? '2D') ?>
                        </div>
                        <div>
                            <span class="k">Phòng chiếu</span><br />
                            <?= htmlspecialchars($room['room_code'] ?? $room['name'] ?? 'N/A') ?>
                        </div>
                    </div>

                    <p class="desc">
                        <?= htmlspecialchars(substr($movie['description'] ?? '', 0, 150)) ?><?= strlen($movie['description'] ?? '') > 150 ? '...' : '' ?>
                    </p>
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
                        <td>Ghế (<?= htmlspecialchars($seat['label']) ?>)<?= $seat['type'] === 'vip' ? ' - VIP' : '' ?></td>
                        <td style="text-align: center">1</td>
                        <td style="text-align: right"><?= formatPrice($seat['price']) ?></td>
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
                <label class="method" data-method="vietqr" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/vietqr.png" alt="VietQR" style="height: 24px" />
                    </div>
                    <div class="txt">VietQR</div>
                    <input type="radio" name="pay" value="vietqr" style="display: none" />
                </label>

                <label class="method" data-method="vnpay" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/vnpay.png" alt="VNPAY" style="height: 22px" />
                    </div>
                    <div class="txt">VNPAY</div>
                    <input type="radio" name="pay" value="vnpay" style="display: none" />
                </label>

                <label class="method" data-method="viettel" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/viettel.png" alt="Viettel Money" style="height: 22px" />
                    </div>
                    <div class="txt">Viettel Money</div>
                    <input type="radio" name="pay" value="viettel" style="display: none" />
                </label>

                <label class="method" data-method="momo" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/momo.png" alt="MoMo" style="height: 20px" />
                    </div>
                    <div class="txt">MoMo</div>
                    <input type="radio" name="pay" value="momo" style="display: none" />
                </label>
            </div>

            <div class="costs" id="costs">
                <div class="cost-row">
                    <div>Thanh toán</div>
                    <div id="subtotal"><?= formatPrice($totalPrice) ?></div>
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
    let subtotal = <?= $totalPrice ?>;
    let fee = 0;
    
    function fmt(v) {
        return new Intl.NumberFormat("vi-VN").format(v) + "đ";
    }
    
    subtotalEl.textContent = fmt(subtotal);
    feeEl.textContent = fmt(fee);
    grandEl.textContent = fmt(subtotal + fee);

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
        
        fetch('<?= BASE_URL ?>?act=payment-process', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Thanh toán thành công!');
                window.location.href = '<?= BASE_URL ?>?act=my-bookings';
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
