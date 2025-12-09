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
                <!-- Phương thức thanh toán - Chỉ VNPAY -->
                <label class="method" data-method="vnpay" tabindex="0">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>/image/vnpay.png" alt="VNPAY" />
                    </div>
                    <div class="txt">VNPAY</div>
                    <input type="radio" name="pay" value="vnpay" style="display: none" checked />
                </label>
            </div>

            <!-- Voucher code section -->
            <div class="voucher-section"
                style="margin: 20px 0; padding: 15px; background: rgba(255, 255, 255, 0.02); border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
                <label for="voucherCode" style="display: block; margin-bottom: 12px; font-weight: 600; font-size: 14px; color: rgba(255, 255, 255, 0.95);">
                    Mã giảm giá / Voucher
                </label>
                <div style="display: flex; gap: 8px; margin-bottom: 12px;">
                    <input type="text" id="voucherCode" name="voucher_code" placeholder="Nhập mã voucher"
                        style="flex: 1; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 6px; font-size: 14px; background: rgba(255, 255, 255, 0.05); color: rgba(255, 255, 255, 0.95); outline: none;">
                    <button type="button" id="applyVoucherBtn"
                        style="padding: 10px 20px; background: #ff6978; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.2s;">
                        Áp dụng
                    </button>
                </div>
                <div id="voucherMessage" style="margin-top: 8px; font-size: 13px;"></div>
                
                <!-- Nút mở modal khuyến mãi -->
                <button type="button" id="openVoucherModalBtn" 
                    style="width: 100%; margin-top: 12px; padding: 12px; background: linear-gradient(90deg, rgba(255, 75, 75, 0.2), rgba(255, 75, 75, 0.1)); border: 1px solid rgba(255, 75, 75, 0.4); border-radius: 8px; color: rgba(255, 255, 255, 0.95); font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    Xem mã khuyến mãi
                </button>
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
            <a class="back-link" href="#" onclick="goBackToSeatSelection(event)">Quay lại</a>
        </div>
    </aside>
</div>

<!-- Modal Khuyến mãi -->
<div id="voucherModal" class="voucher-modal">
    <div class="voucher-modal-content">
        <div class="voucher-modal-header">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: rgba(255, 255, 255, 0.95);">Mã khuyến mãi</h2>
            <span class="voucher-modal-close" onclick="closeVoucherModal()">&times;</span>
        </div>
        
        <!-- Tabs -->
        <div class="voucher-modal-tabs">
            <button class="voucher-tab-btn active" data-tab="all" onclick="switchVoucherTab('all')">
                Tất cả mã
            </button>
            <button class="voucher-tab-btn" data-tab="movie" onclick="switchVoucherTab('movie')">
                Mã khuyến mãi phim
            </button>
        </div>
        
        <div class="voucher-modal-body">
            <!-- Tab: Tất cả mã -->
            <div id="voucherTabAll" class="voucher-tab-content active">
                <div id="voucherModalListAll" style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="padding: 20px; text-align: center; color: rgba(255, 255, 255, 0.6);">
                        <div style="margin-bottom: 8px;">Đang tải mã khuyến mãi...</div>
                    </div>
                </div>
            </div>
            
            <!-- Tab: Mã khuyến mãi phim -->
            <div id="voucherTabMovie" class="voucher-tab-content">
                <div id="voucherModalListMovie" style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="padding: 20px; text-align: center; color: rgba(255, 255, 255, 0.6);">
                        <div style="margin-bottom: 8px;">Đang tải mã khuyến mãi...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    document.getElementById('applyVoucherBtn').addEventListener('click', function() {
        const voucherCode = document.getElementById('voucherCode').value.trim();
        const messageEl = document.getElementById('voucherMessage');

        if (!voucherCode) {
            messageEl.innerHTML = '<span style="color: #dc3545;">Vui lòng nhập mã voucher</span>';
            return;
        }

        messageEl.innerHTML = '<span style="color: #ffc107;">Đang kiểm tra mã voucher...</span>';

        // Kiểm tra discount code qua API
        const movieId = '<?= $movie['id'] ?? '' ?>';
        const url = '<?= BASE_URL ?>?act=check-voucher&code=' + encodeURIComponent(voucherCode) + '&total_amount=' + originalTotal + '&movie_id=' + movieId;
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
        // VNPay không có phí
        fee = 0;
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
            .then(response => {
                // Kiểm tra Content-Type header
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Expected JSON but got:', contentType, text.substring(0, 200));
                        throw new Error('Server returned non-JSON response');
                    });
                }
                return response.json();
            })
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

    // Preselect VNPay (chỉ có 1 method)
    if (methods.length > 0) {
        selectMethod(methods[0]);
    }

    // Modal functions
    function openVoucherModal() {
        document.getElementById('voucherModal').style.display = 'block';
        loadVoucherModalContent();
    }

    function closeVoucherModal() {
        document.getElementById('voucherModal').style.display = 'none';
    }

    // Switch tab
    function switchVoucherTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.voucher-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`.voucher-tab-btn[data-tab="${tab}"]`).classList.add('active');
        
        // Update tab content
        document.querySelectorAll('.voucher-tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(`voucherTab${tab === 'all' ? 'All' : 'Movie'}`).classList.add('active');
        
        // Load content for the selected tab
        loadVoucherModalContent(tab);
    }

    // Load danh sách mã khuyến mãi trong modal
    function loadVoucherModalContent(tab = 'all') {
        const movieId = '<?= $movie['id'] ?? '' ?>';
        const currentMovieId = movieId ? parseInt(movieId) : null;
        
        // Lấy tất cả mã (bao gồm cả mã phim cụ thể)
        const url = '<?= BASE_URL ?>?act=get-available-vouchers&limit=50&include_movie_specific=true';
        const voucherModalListEl = tab === 'all' 
            ? document.getElementById('voucherModalListAll')
            : document.getElementById('voucherModalListMovie');
        
        voucherModalListEl.innerHTML = '<div style="padding: 20px; text-align: center; color: rgba(255, 255, 255, 0.6);">Đang tải mã khuyến mãi...</div>';
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.codes && data.codes.length > 0) {
                    // Phân loại mã
                    let codesToShow = [];
                    if (tab === 'all') {
                        // Tab "Tất cả mã": hiển thị mã tổng quát (movie_id = null)
                        codesToShow = data.codes.filter(code => !code.movie_id || code.movie_id === null);
                    } else {
                        // Tab "Mã khuyến mãi phim": hiển thị mã áp dụng cho phim cụ thể
                        codesToShow = data.codes.filter(code => code.movie_id && code.movie_id !== null);
                    }
                    
                    if (codesToShow.length === 0) {
                        voucherModalListEl.innerHTML = '<div style="padding: 40px; text-align: center; color: rgba(255, 255, 255, 0.5);"><div style="font-size: 48px; margin-bottom: 16px;">🎫</div><div style="font-size: 16px;">Hiện không có mã khuyến mãi nào</div></div>';
                        return;
                    }
                    
                    voucherModalListEl.innerHTML = '';
                    codesToShow.forEach(code => {
                        const voucherCard = document.createElement('div');
                        voucherCard.className = 'voucher-modal-card';
                        voucherCard.style.cssText = 'padding: 16px; background: linear-gradient(135deg, rgba(255, 75, 75, 0.15), rgba(255, 75, 75, 0.05)); border: 1px solid rgba(255, 75, 75, 0.3); border-radius: 12px; transition: all 0.3s; cursor: pointer;';
                        
                        const isMovieSpecific = code.movie_id && code.movie_id !== null;
                        const isCurrentMovie = isMovieSpecific && code.movie_id == currentMovieId;
                        
                        voucherCard.innerHTML = `
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: rgba(255, 255, 255, 0.95); font-size: 16px; margin-bottom: 8px;">${code.title || 'Mã giảm giá'}</div>
                                    ${code.description ? `<div style="font-size: 13px; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; line-height: 1.5;">${code.description}</div>` : ''}
                                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px;">
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255, 75, 75, 0.2); border-radius: 6px; font-size: 13px; font-weight: 600; color: #ff4b4b;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                            </svg>
                                            Giảm ${code.discount_percent}%
                                        </span>
                                        ${isMovieSpecific 
                                            ? `<span style="font-size: 12px; color: ${isCurrentMovie ? '#28a745' : '#ffc107'}; font-weight: 600;">
                                                ${isCurrentMovie ? '✓ ' : '⚠ '}Áp dụng cho: ${code.movie_title || 'Phim cụ thể'}
                                            </span>`
                                            : '<span style="font-size: 12px; color: rgba(255, 255, 255, 0.6);">Áp dụng cho tất cả phim</span>'
                                        }
                                    </div>
                                </div>
                                <button type="button" class="apply-voucher-btn" data-code="${code.code}" data-movie-id="${code.movie_id || ''}" 
                                    style="padding: 10px 20px; background: linear-gradient(90deg, #ff4b4b, #ff6978); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 700; white-space: nowrap; transition: all 0.2s; box-shadow: 0 4px 12px rgba(255, 75, 75, 0.3);">
                                    Áp dụng
                                </button>
                            </div>
                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: space-between;">
                                <div style="font-size: 12px; color: rgba(255, 255, 255, 0.6);">
                                    Mã: <strong style="color: rgba(255, 255, 255, 0.9); font-family: monospace; font-size: 13px;">${code.code}</strong>
                                    ${isMovieSpecific && code.movie_title ? ` | Phim: <strong style="color: rgba(255, 255, 255, 0.9);">${code.movie_title}</strong>` : ''}
                                </div>
                                <button type="button" class="copy-code-btn" data-code="${code.code}"
                                    style="padding: 6px 12px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 6px; color: rgba(255, 255, 255, 0.9); cursor: pointer; font-size: 12px; transition: all 0.2s;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    Sao chép
                                </button>
                            </div>
                        `;
                        
                        voucherCard.addEventListener('mouseenter', function() {
                            this.style.transform = 'translateY(-4px)';
                            this.style.boxShadow = '0 8px 24px rgba(255, 75, 75, 0.2)';
                            this.style.borderColor = 'rgba(255, 75, 75, 0.5)';
                        });
                        voucherCard.addEventListener('mouseleave', function() {
                            this.style.transform = 'translateY(0)';
                            this.style.boxShadow = 'none';
                            this.style.borderColor = 'rgba(255, 75, 75, 0.3)';
                        });
                        
                        voucherModalListEl.appendChild(voucherCard);
                    });
                    
                    // Xử lý click nút "Áp dụng"
                    document.querySelectorAll('.apply-voucher-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const code = this.getAttribute('data-code');
                            const codeMovieId = this.getAttribute('data-movie-id');
                            
                            // Kiểm tra nếu mã áp dụng cho phim cụ thể
                            if (codeMovieId && codeMovieId !== '' && codeMovieId != currentMovieId) {
                                alert('Mã khuyến mãi này chỉ áp dụng cho phim cụ thể. Vui lòng kiểm tra lại phim bạn đang đặt vé.');
                                return;
                            }
                            
                            document.getElementById('voucherCode').value = code;
                            closeVoucherModal();
                            // Tự động áp dụng mã
                            setTimeout(() => {
                                document.getElementById('applyVoucherBtn').click();
                            }, 300);
                        });
                    });
                    
                    // Xử lý click nút "Sao chép"
                    document.querySelectorAll('.copy-code-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const code = this.getAttribute('data-code');
                            navigator.clipboard.writeText(code).then(() => {
                                const originalText = this.innerHTML;
                                this.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg>Đã sao chép';
                                this.style.background = 'rgba(40, 167, 69, 0.2)';
                                this.style.borderColor = 'rgba(40, 167, 69, 0.4)';
                                setTimeout(() => {
                                    this.innerHTML = originalText;
                                    this.style.background = 'rgba(255, 255, 255, 0.1)';
                                    this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                                }, 2000);
                            });
                        });
                    });
                } else {
                    voucherModalListEl.innerHTML = '<div style="padding: 40px; text-align: center; color: rgba(255, 255, 255, 0.5);"><div style="font-size: 48px; margin-bottom: 16px;">🎫</div><div style="font-size: 16px;">Hiện không có mã khuyến mãi nào</div></div>';
                }
            })
            .catch(error => {
                console.error('Error loading vouchers:', error);
                voucherModalListEl.innerHTML = '<div style="padding: 40px; text-align: center; color: rgba(255, 255, 255, 0.5);"><div style="font-size: 48px; margin-bottom: 16px;">⚠️</div><div style="font-size: 16px;">Không thể tải mã khuyến mãi</div></div>';
            });
    }

    // Mở modal khi click nút
    document.getElementById('openVoucherModalBtn').addEventListener('click', function() {
        openVoucherModal();
        // Reset về tab "Tất cả mã" khi mở modal
        switchVoucherTab('all');
    });
    
    // Đóng modal khi click bên ngoài
    window.onclick = function(event) {
        const modal = document.getElementById('voucherModal');
        if (event.target == modal) {
            closeVoucherModal();
        }
    }
    
    // Hover effect cho nút mở modal
    const openBtn = document.getElementById('openVoucherModalBtn');
    openBtn.addEventListener('mouseenter', function() {
        this.style.background = 'linear-gradient(90deg, rgba(255, 75, 75, 0.3), rgba(255, 75, 75, 0.2))';
        this.style.borderColor = 'rgba(255, 75, 75, 0.6)';
        this.style.transform = 'translateY(-2px)';
    });
    openBtn.addEventListener('mouseleave', function() {
        this.style.background = 'linear-gradient(90deg, rgba(255, 75, 75, 0.2), rgba(255, 75, 75, 0.1))';
        this.style.borderColor = 'rgba(255, 75, 75, 0.4)';
        this.style.transform = 'translateY(0)';
    });

    // Quay lại trang chọn ghế và reset viewport
    function goBackToSeatSelection(event) {
        event.preventDefault();

        // Reset tất cả zoom/transform trước khi chuyển trang
        document.body.style.zoom = '';
        document.body.style.transform = '';
        document.documentElement.style.zoom = '';

        // Reset viewport meta tag
        const viewport = document.querySelector('meta[name="viewport"]');
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
        }

        // Kiểm tra xem đến từ đâu
        const urlParams = new URLSearchParams(window.location.search);
        const from = urlParams.get('from');
        const showtimeId = '<?= $showtime['id'] ?? '' ?>';

        let baseUrl;

        // Nếu đến từ movies.php, quay lại movies.php với showtime đã chọn
        if (from === 'movies') {
            const movieId = urlParams.get('movie_id') || '';
            const cinemaId = urlParams.get('cinema') || '';
            const date = urlParams.get('date') || '';

            baseUrl = '<?= BASE_URL ?>?act=movies&id=' + movieId;
            if (date) {
                baseUrl += '&date=' + date;
            }
            if (cinemaId) {
                baseUrl += '&cinema=' + cinemaId;
            }
            // Chỉ thêm showtime_id và _reset_zoom, không thêm _nocache để tránh reload nhiều lần
            baseUrl += '&showtime_id=' + showtimeId + '&_reset_zoom=1';
        } else {
            // Nếu đến từ select_seats.php, quay lại select_seats.php
            baseUrl = '<?= BASE_URL ?>?act=datve&showtime_id=' + showtimeId + '&_reset_zoom=1';
        }

        // Chuyển trang ngay, không cần clear cache hay reload
        window.location.href = baseUrl;
    }
</script>