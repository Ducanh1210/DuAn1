<?php
$showtime = $showtime ?? null;
$movie = $movie ?? null;
$room = $room ?? null;
$seatsByRow = $seatsByRow ?? [];
$bookedSeats = $bookedSeats ?? [];
$countdownSeconds = $countdownSeconds ?? 900;

// TẤT CẢ PHÒNG ĐỀU HIỂN THỊ 12 CỘT (2 khối, mỗi khối 6 cột)
$maxColumns = 12;
?>

<style>
    .seat-selection-container {
        background: #2a2a2a;
        min-height: 100vh;
        padding: 20px 0;
        color: #fff;
    }

    .seat-selection-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: #1a1a1a;
        margin-bottom: 20px;
        gap: 15px;
    }

    .back-button {
        background: #4a4a4a;
        color: #fff;
        border: 1px solid #666;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .back-button:hover {
        background: #5a5a5a;
        border-color: #777;
        transform: translateY(-1px);
    }

    .showtime-info {
        font-size: 16px;
        font-weight: 500;
    }

    .countdown-timer {
        background: #dc3545;
        border: 2px solid #dc3545;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
    }

    .screen-container {
        text-align: center;
        margin: 20px auto;
        max-width: 1400px;
        padding: 0 20px;
    }

    .screen {
        background: linear-gradient(to bottom, #ff8c00, #ff6b00);
        height: 50px;
        border-radius: 50px 50px 0 0;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
        color: white;
        font-weight: bold;
        font-size: 16px;
        letter-spacing: 2px;
    }

    .room-title {
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
        color: #fff;
        text-align: center;
    }

    .seat-selection-container .seats-grid {
        max-width: 1400px !important;
        margin: 20px auto !important;
        padding: 0 20px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        position: relative !important;
    }

    .seat-selection-container .seat-row {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin-bottom: 6px !important;
        gap: 4px !important;
        transition: opacity 0.3s, transform 0.3s;
        flex-wrap: nowrap !important;
        width: 100% !important;
        min-height: auto !important;
        padding: 0 25px 0 55px !important;
        position: relative !important;
        margin-left: auto !important;
        margin-right: auto !important;
        box-sizing: border-box !important;
    }

    .seat-selection-container .seat-row::after {
        content: '' !important;
        width: 28px !important;
        flex-shrink: 0 !important;
        order: 999 !important;
    }

    .seat-selection-container .seat-row.hidden {
        display: none !important;
    }

    .seat-selection-container .seat-row.filtered-highlight {
        transform: scale(1.02);
        opacity: 1;
    }

    .seat-selection-container .row-label {
        width: 28px !important;
        min-width: 28px !important;
        text-align: center !important;
        font-weight: bold !important;
        color: #fff !important;
        font-size: 13px !important;
        flex-shrink: 0 !important;
        position: absolute !important;
        left: 0 !important;
        transform: translateX(-45px) !important;
        z-index: 1 !important;
    }

    .seat-selection-container .seat {
        width: 28px !important;
        height: 28px !important;
        min-width: 28px !important;
        min-height: 28px !important;
        border-radius: 3px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        font-size: 9px !important;
        font-weight: bold !important;
        transition: all 0.2s !important;
        border: 1px solid transparent !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }

    /* Ghế thường - màu xám đậm như ảnh */
    .seat-selection-container .seat.available {
        background: #4a4a4a !important;
        color: #fff !important;
        border-color: #555 !important;
    }

    .seat-selection-container .seat.available:hover {
        background: #5a5a5a !important;
        transform: scale(1.15) !important;
    }

    /* Ghế VIP - màu vàng */
    .seat-selection-container .seat.vip {
        background: #ffc107 !important;
        color: #000 !important;
        border-color: #ffb300 !important;
    }

    .seat-selection-container .seat.vip:hover {
        background: #ffb300 !important;
        transform: scale(1.15) !important;
    }

    /* Ghế đã chọn - màu cam như ảnh */
    .seat-selection-container .seat.selected {
        background: #ff8c00 !important;
        color: #fff !important;
        border-color: #ff6b00 !important;
    }

    .seat-selection-container .seat.vip.selected {
        background: #ff8c00 !important;
        color: #fff !important;
        border-color: #ff6b00 !important;
    }

    /* Ghế đã đặt - màu đỏ */
    .seat-selection-container .seat.booked {
        background: #dc3545 !important;
        color: #fff !important;
        cursor: not-allowed !important;
        opacity: 0.9 !important;
    }

    /* Ghế bảo trì - màu vàng với dấu X */
    .seat-selection-container .seat.maintenance {
        background: #ffc107 !important;
        color: transparent !important;
        /* Ẩn số ghế, chỉ hiển thị dấu X */
        cursor: not-allowed !important;
        opacity: 0.8 !important;
        position: relative !important;
    }

    .seat-selection-container .seat.maintenance::after {
        content: '✕' !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        font-size: 16px !important;
        font-weight: bold !important;
        color: #000 !important;
        z-index: 2 !important;
        line-height: 1 !important;
    }

    .seat-selection-container .seat-gap {
        width: 60px !important;
        min-width: 60px !important;
        flex-shrink: 0 !important;
        background: rgba(255, 255, 255, 0.05) !important;
        border-left: 3px dashed rgba(255, 255, 255, 0.3) !important;
        border-right: 3px dashed rgba(255, 255, 255, 0.3) !important;
        margin: 0 10px !important;
        position: relative !important;
    }

    .seat-selection-container .seat-gap::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 2px !important;
        height: 100% !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .seat-selection-container .seat.disabled-column {
        opacity: 0.3 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        background: #4a4a4a !important;
        color: transparent !important;
        position: relative !important;
    }

    .seat-selection-container .seat.disabled-column::after {
        content: '✕' !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        font-size: 16px !important;
        font-weight: bold !important;
        color: #fff !important;
        z-index: 2 !important;
        line-height: 1 !important;
    }


    .seat-legend {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin: 25px 0;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .legend-seat {
        width: 22px;
        height: 22px;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
    }

    .selected-seats-summary {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #1a1a1a;
        padding: 20px;
        border-top: 2px solid #ff8c00;
        z-index: 1000;
    }

    .summary-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .selected-seats-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .selected-seat-badge {
        background: #ff8c00;
        color: #fff;
        padding: 8px 15px;
        border-radius: 6px;
        font-weight: bold;
    }

    /* Styles cho phần chọn ghế liền nhau */
    .adjacent-option {
        display: flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .adjacent-option-radio {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        background: transparent;
        position: relative;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .adjacent-option.active .adjacent-option-radio {
        border-color: #ff8c00;
        background: #ff8c00;
    }

    .adjacent-option.active .adjacent-option-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fff;
    }

    .adjacent-option-seats {
        display: flex;
        gap: 2px;
        align-items: center;
    }

    .adjacent-seat-box {
        width: 12px;
        height: 12px;
        background: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 2px;
        flex-shrink: 0;
    }

    .adjacent-option:hover .adjacent-option-radio {
        border-color: #ff8c00;
    }

    .total-price {
        font-size: 24px;
        font-weight: bold;
        color: #ff8c00;
    }

    .continue-btn {
        background: #ff8c00;
        color: #fff;
        border: none;
        padding: 12px 40px;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .continue-btn:hover {
        background: #ff6b00;
        transform: translateY(-2px);
    }

    .continue-btn:disabled {
        background: #666;
        cursor: not-allowed;
        transform: none;
    }
</style>

<div class="seat-selection-container">
    <div class="seat-selection-header">
        <div class="showtime-info" style="color: #fff;">
            Giờ chiếu: <strong><?= date('H:i', strtotime($showtime['start_time'] ?? '00:00')) ?></strong>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="<?= !empty($movie['id']) ? BASE_URL . '?act=movies&id=' . $movie['id'] : BASE_URL . '?act=trangchu' ?>" class="back-button">
                ← Quay lại
            </a>
            <div class="countdown-timer" id="countdownTimer">
                Thời gian chọn ghế: <span id="countdown">15:00</span>
            </div>
        </div>
    </div>

    <!-- Phần chọn số lượng người -->
    <div class="ticket-selection-panel" style="max-width: 1400px; margin: 20px auto; padding: 0 40px;">
        <div style="background: rgba(255, 255, 255, 0.05); padding: 25px; border-radius: 10px; margin-bottom: 30px; border: 1px solid rgba(255, 255, 255, 0.1);">
            <h3 style="color: #fff; margin-bottom: 20px; font-size: 24px;">Chọn ghế</h3>

            <div style="display: flex; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="color: #fff; font-weight: 500; font-size: 14px;">Người lớn:</label>
                    <select id="adultQuantity" onchange="validateAndUpdateQuantity(event)" style="padding: 10px 15px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.3); background: #fff; color: #000; font-size: 16px; cursor: pointer; min-width: 100px;">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="color: #fff; font-weight: 500; font-size: 14px;">Sinh viên:</label>
                    <select id="studentQuantity" onchange="validateAndUpdateQuantity(event)" style="padding: 10px 15px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.3); background: #fff; color: #000; font-size: 16px; cursor: pointer; min-width: 100px;">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 15px;">
                    <label style="color: #fff; font-weight: 500; font-size: 14px;">Chọn ghế liền nhau</label>
                    <span style="width: 18px; height: 18px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: inline-flex; align-items: center; justify-content: center; font-size: 12px; cursor: help;" title="Chọn số lượng ghế liền nhau bạn muốn">ℹ️</span>
                </div>
                <div id="adjacentOptions" style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
                    <!-- Sẽ được render động -->
                </div>
                <div id="remainingSeatsInfo" style="color: rgba(255, 255, 255, 0.6); font-size: 13px; margin-top: 10px;">
                    Có thể chọn tối đa 8 người. (Max:8)
                </div>
            </div>
        </div>
    </div>

    <div class="screen-container">
        <div class="room-title">
            <?php
            // Hiển thị số phòng từ name hoặc room_code
            $roomNumber = '';
            if (!empty($room['room_code'])) {
                // Lấy số từ room_code (VD: R10 -> 10)
                preg_match('/\d+/', $room['room_code'], $matches);
                $roomNumber = !empty($matches) ? $matches[0] : '';
            }
            if (empty($roomNumber) && !empty($room['name'])) {
                // Lấy số từ name (VD: Phòng Chiếu 10 -> 10)
                preg_match('/\d+/', $room['name'], $matches);
                $roomNumber = !empty($matches) ? $matches[0] : '';
            }
            ?>
            <?php
            // Hiển thị "Phòng chiếu số X" như ảnh
            if (!empty($roomNumber)) {
                echo "Phòng chiếu số " . $roomNumber;
            } elseif (!empty($room['room_code'])) {
                echo "Phòng " . htmlspecialchars($room['room_code']);
            } else {
                echo "Phòng " . htmlspecialchars($room['name'] ?? '');
            }
            ?>
        </div>
        <div class="room-title" style="font-size: 14px; opacity: 0.8; margin-top: 4px;">
            <?= htmlspecialchars($room['cinema_name'] ?? ($room['room_code'] ?? '')) ?>
        </div>
        <div class="screen">MÀN HÌNH</div>
    </div>

    <div class="seats-grid" id="seatsGrid">
        <?php foreach ($seatsByRow as $rowLabel => $rowSeats): ?>
            <div class="seat-row" data-row-label="<?= strtoupper(htmlspecialchars($rowLabel)) ?>">
                <div class="row-label"><?= htmlspecialchars($rowLabel) ?></div>
                <?php
                // Sắp xếp ghế theo seat_number để đảm bảo hiển thị đúng thứ tự
                usort($rowSeats, function ($a, $b) {
                    return ($a['seat_number'] ?? 0) - ($b['seat_number'] ?? 0);
                });

                $prevSeatNumber = 0;
                foreach ($rowSeats as $seat):
                    $seatId = $seat['id'];
                    $seatNumber = $seat['seat_number'] ?? 0;

                    // CHỈ HIỂN THỊ SỐ CỘT TỐI ĐA CHO PHÒNG NÀY
                    if ($seatNumber > $maxColumns) {
                        continue;
                    }

                    $seatLabel = ($seat['row_label'] ?? '') . $seatNumber;
                    $seatKey = $seatLabel;
                    $isBooked = in_array($seatKey, $bookedSeats);
                    $seatType = $seat['seat_type'] ?? 'normal';
                    $seatStatus = $seat['status'] ?? 'available';
                    $isMaintenance = ($seatStatus === 'maintenance');

                    // Chỉ hiển thị ghế normal và vip, bỏ qua disabled và couple
                    if (in_array($seatType, ['disabled', 'couple'])) {
                        continue;
                    }

                    // Thêm khoảng trống lớn sau cột 6 (chia 2 bên, mỗi bên 6 cột)
                    // Block 1: Cột 1-6 | Gap | Block 2: Cột 7-12
                    if ($prevSeatNumber == 6 && $seatNumber == 7) {
                        echo '<div class="seat-gap"></div>';
                    }

                    // Xác định class CSS cho ghế
                    $seatClass = 'available';
                    if ($isBooked) {
                        $seatClass = 'booked';
                    } elseif ($isMaintenance) {
                        $seatClass = 'maintenance';
                    } elseif ($seatType === 'vip') {
                        $seatClass = 'vip';
                    }

                    $onClick = '';
                    $title = '';
                    $disabledColumn = false;
                    if ($isBooked) {
                        $title = 'title="Ghế đã được đặt"';
                    } elseif ($isMaintenance) {
                        $title = 'title="Ghế đang bảo trì"';
                    } else {
                        // Thêm data-seat-column để kiểm tra cột
                        $onClick = 'onclick="toggleSeat(this)"';
                        $disabledColumn = false; // Sẽ được kiểm tra bằng JavaScript
                    }
                ?>
                    <div class="seat <?= $seatClass ?>"
                        data-seat-id="<?= $seatId ?>"
                        data-seat-label="<?= htmlspecialchars($seatLabel) ?>"
                        data-seat-row="<?= strtoupper(htmlspecialchars($rowLabel)) ?>"
                        data-seat-type="<?= htmlspecialchars($seatType) ?>"
                        data-seat-status="<?= htmlspecialchars($seatStatus) ?>"
                        data-seat-column="<?= $seatNumber ?>"
                        <?= $title ?> <?= $onClick ?>>
                        <?= htmlspecialchars($seatNumber) ?>
                    </div>
                <?php
                    $prevSeatNumber = $seatNumber;
                endforeach;
                ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="seat-legend">
        <div class="legend-item">
            <div class="legend-seat" style="background: #6c757d;"></div>
            <span>Thường</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat" style="background: #ffc107;"></div>
            <span>VIP</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat" style="background: #ff8c00;"></div>
            <span>Ghế bạn chọn</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat" style="background: #dc3545;"></div>
            <span>Đã đặt</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat" style="background: #ffc107; position: relative;">
                <span style="position: absolute; font-size: 14px; font-weight: bold; color: #000;">✕</span>
            </div>
            <span>Bảo trì</span>
        </div>
    </div>

    <div class="selected-seats-summary" id="selectedSeatsSummary" style="display: none;">
        <div class="summary-content">
            <div>
                <div style="margin-bottom: 10px; font-weight: bold;">Ghế đã chọn:</div>
                <div class="selected-seats-list" id="selectedSeatsList"></div>
            </div>
            <div style="text-align: right;">
                <div style="margin-bottom: 10px;">Tổng tiền:</div>
                <div class="total-price" id="totalPrice">0 đ</div>
            </div>
            <button class="continue-btn" id="continueBtn" onclick="continueBooking()" disabled>
                Tiếp tục
            </button>
        </div>
    </div>
</div>

<script>
    let selectedSeats = [];
    let selectedGroups = [];
    let remainingSeats = 0;
    let adultCount = 0;
    let studentCount = 0;
    let selectedAdjacentCount = 0;
    let lastAdultCount = 0;
    let lastStudentCount = 0;
    const adultNormalPrice = <?= $normalPrice ?? 70000 ?>; // Giá ghế thường người lớn từ database
    const adultVipPrice = <?= $vipPrice ?? 80000 ?>; // Giá ghế VIP người lớn từ database
    const studentNormalPrice = <?= $studentNormalPrice ?? 60000 ?>; // Giá ghế thường sinh viên từ database
    const studentVipPrice = <?= $studentVipPrice ?? 70000 ?>; // Giá ghế VIP sinh viên từ database
    const maxColumns = 12; // TẤT CẢ PHÒNG ĐỀU 12 CỘT (2 khối, mỗi khối 6 cột)

    // Countdown timer
    let countdown = <?= $countdownSeconds ?>;
    const countdownElement = document.getElementById('countdown');
    const countdownInterval = setInterval(() => {
        countdown--;
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            alert('Hết thời gian chọn ghế!');
            window.location.href = '<?= BASE_URL ?>?act=trangchu';
            return;
        }
        const minutes = Math.floor(countdown / 60);
        const seconds = countdown % 60;
        countdownElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }, 1000);

    function toggleSeat(seatElement) {
        // Kiểm tra ghế có thể chọn không
        if (seatElement.classList.contains('booked') ||
            seatElement.classList.contains('maintenance')) {
            return; // Không cho phép chọn ghế đã đặt hoặc bảo trì
        }

        const totalPeople = adultCount + studentCount;
        if (totalPeople === 0) {
            alert('Vui lòng chọn số lượng người trước!');
            return;
        }

        if (selectedAdjacentCount === 0) {
            alert('Vui lòng chọn số lượng ghế liền nhau!');
            return;
        }

        const seatId = seatElement.getAttribute('data-seat-id');
        const seatLabel = seatElement.getAttribute('data-seat-label');
        const seatType = seatElement.getAttribute('data-seat-type');
        const seatStatus = seatElement.getAttribute('data-seat-status');

        if (seatElement.classList.contains('selected')) {
            // Bỏ chọn ghế - xóa cả nhóm nếu là ghế trong nhóm
            removeSeatFromGroup(seatId);
        } else {
            // Kiểm tra số lượng ghế đã chọn
            if (selectedSeats.length >= totalPeople) {
                alert(`Bạn chỉ có thể chọn tối đa ${totalPeople} ghế!`);
                return;
            }

            // Tính số ghế còn lại cần chọn
            remainingSeats = totalPeople - selectedSeats.length;

            // Xác định số ghế sẽ chọn: lấy min giữa số ghế còn lại và số ghế liền nhau đã chọn
            let seatsToSelect = Math.min(remainingSeats, selectedAdjacentCount);

            // Kiểm tra trường hợp đặc biệt: sót lại 1 ghế và tất cả ghế cùng 1 hàng
            const row = seatElement.closest('.seat-row');
            const rowLabel = row ? (row.getAttribute('data-row-label') || '').toUpperCase() : '';
            const allSeatsInSameRow = selectedSeats.length > 0 && selectedSeats.every(seat => seat.row === rowLabel);
            const allowLastSingleSeat = remainingSeats === 1 && allSeatsInSameRow && selectedAdjacentCount === 1;

            // Kiểm tra nếu chọn 1 vé đơn lẻ
            if (selectedAdjacentCount === 1) {
                if (!allowLastSingleSeat) {
                    const seatColumn = parseInt(seatElement.getAttribute('data-seat-column')) || 0;
                    if (!ALLOWED_SINGLE_COLUMNS.includes(seatColumn)) {
                        alert('Ghế đơn chỉ được chọn ở các cột: 1, 3, 4, 6, 7, 9, 10, 12');
                        return;
                    }
                }
            }

            const groupSeats = selectAdjacentSeatsSmart(seatElement, seatsToSelect, allowLastSingleSeat);
            if (groupSeats.length > 0) {
                selectedGroups.push({
                    count: seatsToSelect,
                    seats: groupSeats
                });
                selectedSeats = selectedSeats.concat(groupSeats);
                remainingSeats = totalPeople - selectedSeats.length;
            }
        }

        updateSummary();
        updateDisabledColumns(); // Cập nhật lại disabled columns sau khi chọn ghế
    }

    function resetAllSelections() {
        if (selectedSeats.length > 0) {
            selectedSeats.forEach(seat => {
                const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                if (seatEl) {
                    seatEl.classList.remove('selected');
                    if (seat.type === 'vip') {
                        seatEl.classList.add('vip');
                    } else {
                        seatEl.classList.add('available');
                    }
                }
            });
        }
        selectedSeats = [];
        selectedGroups = [];
        remainingSeats = adultCount + studentCount;
        updateSummary();
        updateDisabledColumns();
    }

    function removeSeatFromGroup(seatId) {
        // Tìm và xóa ghế khỏi nhóm
        for (let i = selectedGroups.length - 1; i >= 0; i--) {
            const group = selectedGroups[i];
            const seatIndex = group.seats.findIndex(s => s.id === seatId);
            if (seatIndex !== -1) {
                // Xóa tất cả ghế trong nhóm này
                group.seats.forEach(seat => {
                    const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                    if (seatEl) {
                        seatEl.classList.remove('selected');
                        // Khôi phục màu gốc
                        if (seat.type === 'vip') {
                            seatEl.classList.add('vip');
                        } else {
                            seatEl.classList.add('available');
                        }
                    }
                });
                // Xóa khỏi selectedSeats
                selectedSeats = selectedSeats.filter(s => !group.seats.some(gs => gs.id === s.id));
                // Xóa nhóm
                selectedGroups.splice(i, 1);
                remainingSeats = (adultCount + studentCount) - selectedSeats.length;
                updateDisabledColumns(); // Cập nhật lại disabled columns sau khi bỏ chọn
                break;
            }
        }
    }

    function selectAdjacentSeats(startSeatElement, count) {
        // Tìm các ghế liền nhau từ ghế bắt đầu
        const row = startSeatElement.closest('.seat-row');
        if (!row) return [];
        const rowLabel = (row.getAttribute('data-row-label') || '').toUpperCase();

        const allSeats = Array.from(row.querySelectorAll('.seat:not(.booked):not(.maintenance):not(.selected)'));
        const startIndex = allSeats.indexOf(startSeatElement);

        if (startIndex === -1) return [];

        // Kiểm tra xem có đủ ghế liền nhau không
        if (startIndex + count > allSeats.length) {
            alert(`Không đủ ${count} ghế liền nhau từ vị trí này!`);
            return [];
        }

        // Chọn ghế liền nhau
        const seatsToSelect = [];
        for (let i = 0; i < count; i++) {
            const seat = allSeats[startIndex + i];
            const seatId = seat.getAttribute('data-seat-id');
            const seatLabel = seat.getAttribute('data-seat-label');
            const seatType = seat.getAttribute('data-seat-type');
            const seatStatus = seat.getAttribute('data-seat-status');
            const seatColumn = parseInt(seat.getAttribute('data-seat-column')) || 0;

            // Kiểm tra ghế đã được chọn chưa
            if (seat.classList.contains('selected')) {
                continue;
            }

            seat.classList.add('selected');
            seat.classList.remove('vip', 'available');
            seatsToSelect.push({
                id: seatId,
                label: seatLabel,
                type: seatType,
                status: seatStatus,
                row: rowLabel,
                column: seatColumn
            });
        }

        return seatsToSelect;
    }

    const ALLOWED_SINGLE_COLUMNS = [1, 3, 4, 6, 7, 9, 10, 12];

    function getBlockForColumn(col) {
        if (col >= 1 && col <= 6) return 'left';
        if (col >= 7 && col <= 12) return 'right';
        return null;
    }

    function isInSameBlock(startCol, endCol) {
        const startBlock = getBlockForColumn(startCol);
        const endBlock = getBlockForColumn(endCol);
        return startBlock !== null && startBlock === endBlock;
    }

    function countIsolatedSeats(selectedCols, newCols) {
        const allCols = [...selectedCols, ...newCols].sort((a, b) => a - b);
        let isolatedCount = 0;

        for (let i = 0; i < allCols.length; i++) {
            const col = allCols[i];
            const hasLeftNeighbor = i > 0 && allCols[i - 1] === col - 1;
            const hasRightNeighbor = i < allCols.length - 1 && allCols[i + 1] === col + 1;

            if (!hasLeftNeighbor && !hasRightNeighbor) {
                isolatedCount++;
            }
        }

        return isolatedCount;
    }

    function selectAdjacentSeatsSmart(startSeatElement, count, allowLastSingleSeat = false) {
        const row = startSeatElement.closest('.seat-row');
        if (!row) return [];

        const rowLabel = (row.getAttribute('data-row-label') || '').toUpperCase();
        const startColumn = parseInt(startSeatElement.getAttribute('data-seat-column')) || 0;
        if (!startColumn) {
            alert('Không xác định được vị trí ghế, vui lòng chọn lại!');
            return [];
        }

        // Kiểm tra nếu chọn 1 ghế đơn lẻ
        if (count === 1) {
            // Nếu cho phép ghế lẻ cuối cùng trong cùng hàng, bỏ qua kiểm tra
            if (!allowLastSingleSeat && !ALLOWED_SINGLE_COLUMNS.includes(startColumn)) {
                alert('Ghế đơn chỉ được chọn ở các cột: 1, 3, 4, 6, 7, 9, 10, 12');
                return [];
            }
        }

        const seatMap = {};
        row.querySelectorAll('.seat').forEach(seat => {
            const col = parseInt(seat.getAttribute('data-seat-column')) || 0;
            if (col > 0) {
                seatMap[col] = seat;
            }
        });

        const isSeatSelectable = seat =>
            seat &&
            !seat.classList.contains('booked') &&
            !seat.classList.contains('maintenance') &&
            !seat.classList.contains('selected');

        if (!isSeatSelectable(seatMap[startColumn])) {
            alert('Ghế này không thể chọn, vui lòng chọn ghế khác!');
            return [];
        }

        // Lấy các ghế đã chọn trong cùng hàng
        const sameRowSelectedCols = selectedSeats
            .filter(seat => seat.row === rowLabel)
            .map(seat => seat.column || 0)
            .filter(col => col > 0)
            .sort((a, b) => a - b);

        // Xác định block của ghế bắt đầu
        const startBlock = getBlockForColumn(startColumn);
        if (!startBlock) {
            alert('Vị trí ghế không hợp lệ!');
            return [];
        }

        const candidates = [];

        // Tạo các candidate ranges trong cùng block
        for (let offset = 0; offset < count; offset++) {
            const blockStart = startColumn - offset;
            const blockEnd = blockStart + count - 1;

            // Kiểm tra không được tràn sang block khác
            if (!isInSameBlock(blockStart, blockEnd)) {
                continue;
            }

            if (blockStart < 1 || blockEnd > maxColumns) {
                continue;
            }

            const seatsList = [];
            let isValidBlock = true;

            for (let col = blockStart; col <= blockEnd; col++) {
                const seat = seatMap[col];
                if (!isSeatSelectable(seat)) {
                    isValidBlock = false;
                    break;
                }
                seatsList.push(seat);
            }

            if (!isValidBlock) {
                continue;
            }

            // Tính toán số ghế đơn lẻ sẽ tạo ra
            const newCols = seatsList.map(s => parseInt(s.getAttribute('data-seat-column')) || 0);
            const isolatedCount = countIsolatedSeats(sameRowSelectedCols, newCols);

            // Tìm ghế gần nhất bên trái và phải
            const nearestLeft = (() => {
                for (let i = sameRowSelectedCols.length - 1; i >= 0; i--) {
                    if (sameRowSelectedCols[i] < blockStart) {
                        return sameRowSelectedCols[i];
                    }
                }
                return null;
            })();

            const nearestRight = (() => {
                for (let i = 0; i < sameRowSelectedCols.length; i++) {
                    if (sameRowSelectedCols[i] > blockEnd) {
                        return sameRowSelectedCols[i];
                    }
                }
                return null;
            })();

            const gapLeft = nearestLeft !== null ? blockStart - nearestLeft - 1 : 99;
            const gapRight = nearestRight !== null ? nearestRight - blockEnd - 1 : 99;

            // Ưu tiên: 1. Ghép với ghế đã chọn (gap = 0), 2. Ít ghế đơn lẻ, 3. Ít gap
            const touchesLeft = gapLeft === 0 ? 0 : 1;
            const touchesRight = gapRight === 0 ? 0 : 1;
            const touchesBoth = touchesLeft + touchesRight;

            // Logic đặc biệt: nếu click D5 và đã có D1,D2,D3, chọn D5,D4 thay vì D5,D6
            let preferLeft = false;
            if (nearestLeft !== null && gapLeft === 1 && gapRight >= 1) {
                preferLeft = true;
            }

            candidates.push({
                seats: seatsList,
                rowLabel,
                newCols,
                priority: {
                    isolatedCount, // Số ghế đơn lẻ (ưu tiên thấp hơn = tốt hơn)
                    touchesBoth, // Số ghế đã chọn được ghép (ưu tiên thấp hơn = tốt hơn)
                    preferLeft: preferLeft ? 0 : 1, // Ưu tiên chọn về bên trái
                    gapLeft,
                    gapRight,
                    centerDistance: Math.abs(startColumn - ((blockStart + blockEnd) / 2)),
                    leanOffset: offset,
                    blockStart
                }
            });
        }

        if (candidates.length === 0) {
            alert(`Không đủ ${count} ghế liền nhau trong cùng block từ vị trí này!`);
            return [];
        }

        // Sắp xếp: ưu tiên ít ghế đơn lẻ, ghép với ghế đã chọn, chọn về bên trái
        candidates.sort((a, b) => {
            const keys = ['isolatedCount', 'touchesBoth', 'preferLeft', 'gapLeft', 'gapRight', 'centerDistance', 'leanOffset', 'blockStart'];
            for (const key of keys) {
                const diff = a.priority[key] - b.priority[key];
                if (Math.abs(diff) > 0.0001) {
                    return diff;
                }
            }
            return 0;
        });

        const best = candidates[0];
        return best.seats.map(seat => {
            seat.classList.add('selected');
            seat.classList.remove('vip', 'available');
            return {
                id: seat.getAttribute('data-seat-id'),
                label: seat.getAttribute('data-seat-label'),
                type: seat.getAttribute('data-seat-type'),
                status: seat.getAttribute('data-seat-status'),
                row: best.rowLabel,
                column: parseInt(seat.getAttribute('data-seat-column')) || 0
            };
        });
    }

    function updateSummary() {
        const summaryElement = document.getElementById('selectedSeatsSummary');
        const seatsListElement = document.getElementById('selectedSeatsList');
        const totalPriceElement = document.getElementById('totalPrice');
        const continueBtn = document.getElementById('continueBtn');

        if (selectedSeats.length === 0) {
            summaryElement.style.display = 'none';
            continueBtn.disabled = true;
            return;
        }

        summaryElement.style.display = 'block';
        seatsListElement.innerHTML = selectedSeats.map(seat =>
            `<span class="selected-seat-badge">${seat.label}${seat.type === 'vip' ? ' (VIP)' : ''}</span>`
        ).join('');

        // Tính tổng tiền sử dụng giá từ database theo loại khách hàng
        let total = 0;
        selectedSeats.forEach((seat, index) => {
            // Xác định loại khách hàng: số ghế đầu = người lớn, số ghế sau = sinh viên
            const isAdult = index < adultCount;
            let price = 0;

            if (seat.type === 'vip') {
                price = isAdult ? adultVipPrice : studentVipPrice;
            } else {
                price = isAdult ? adultNormalPrice : studentNormalPrice;
            }

            total += price;
        });

        totalPriceElement.textContent = total.toLocaleString('vi-VN') + ' đ';

        continueBtn.disabled = false;
    }

    function continueBooking() {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ít nhất một ghế!');
            return;
        }

        // Lưu ghế đã chọn vào session hoặc localStorage
        const seatIds = selectedSeats.map(s => s.id).join(',');
        const seatLabels = selectedSeats.map(s => s.label).join(',');

        // Chuyển đến trang thanh toán
        window.location.href = `<?= BASE_URL ?>?act=payment&showtime_id=<?= $showtime['id'] ?>&seats=${seatIds}&seat_labels=${encodeURIComponent(seatLabels)}`;
    }

    // Keyboard navigation để lọc hàng ghế
    let currentFilter = null;

    function filterSeatRows(filterKey) {
        const seatRows = document.querySelectorAll('.seat-row');

        if (!filterKey) {
            // Bỏ filter - hiển thị tất cả
            currentFilter = null;
            seatRows.forEach(row => {
                row.classList.remove('hidden', 'filtered-highlight');
            });
            return;
        }

        // Filter hàng mới
        currentFilter = filterKey;
        let foundRow = false;
        seatRows.forEach(row => {
            const rowLabel = row.getAttribute('data-row-label');
            if (rowLabel === filterKey) {
                row.classList.remove('hidden');
                row.classList.add('filtered-highlight');
                // Scroll đến hàng được chọn
                row.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                foundRow = true;
            } else {
                row.classList.add('hidden');
                row.classList.remove('filtered-highlight');
            }
        });

        // Nếu không tìm thấy hàng, bỏ filter
        if (!foundRow) {
            currentFilter = null;
            seatRows.forEach(row => {
                row.classList.remove('hidden', 'filtered-highlight');
            });
        }
    }

    document.addEventListener('keydown', function(e) {
        // Chỉ xử lý khi không đang nhập vào input/textarea
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
            return;
        }

        // Lấy phím được nhấn (chuyển thành chữ hoa)
        const key = e.key.toUpperCase();

        // Kiểm tra nếu là chữ cái A-Z
        if (key.length === 1 && key >= 'A' && key <= 'Z') {
            e.preventDefault();

            // Nếu đã filter cùng hàng, bỏ filter. Nếu khác hàng, filter hàng mới
            if (currentFilter === key) {
                filterSeatRows(null); // Bỏ filter
            } else {
                filterSeatRows(key); // Filter hàng mới
            }
        } else if (e.key === 'Escape') {
            // Nhấn ESC để bỏ filter
            e.preventDefault();
            filterSeatRows(null);
        }
    });

    function validateAndUpdateQuantity(event) {
        const adultSelect = document.getElementById('adultQuantity');
        const studentSelect = document.getElementById('studentQuantity');

        let adultValue = parseInt(adultSelect.value) || 0;
        let studentValue = parseInt(studentSelect.value) || 0;
        let total = adultValue + studentValue;

        // Kiểm tra tổng số không vượt quá 8
        if (total > 8) {
            const changedSelect = event ? event.target : null;

            if (changedSelect && changedSelect.id === 'adultQuantity') {
                studentValue = Math.max(0, 8 - adultValue);
                studentSelect.value = studentValue;
            } else if (changedSelect && changedSelect.id === 'studentQuantity') {
                adultValue = Math.max(0, 8 - studentValue);
                adultSelect.value = adultValue;
            } else {
                studentValue = Math.max(0, 8 - adultValue);
                studentSelect.value = studentValue;
            }

            total = adultValue + studentValue;
            alert(`Tổng số người không được vượt quá 8 người! Đã tự động điều chỉnh.`);
        }

        updateTicketSelection();
    }

    function updateTicketSelection() {
        const adultSelect = document.getElementById('adultQuantity');
        const studentSelect = document.getElementById('studentQuantity');

        if (!adultSelect || !studentSelect) {
            return;
        }

        const prevAdult = lastAdultCount;
        const prevStudent = lastStudentCount;

        adultCount = parseInt(adultSelect.value) || 0;
        studentCount = parseInt(studentSelect.value) || 0;
        const totalPeople = adultCount + studentCount;

        if (adultCount !== prevAdult || studentCount !== prevStudent) {
            resetAllSelections();
        } else {
            remainingSeats = totalPeople - selectedSeats.length;
        }

        lastAdultCount = adultCount;
        lastStudentCount = studentCount;

        const adjacentOptions = document.getElementById('adjacentOptions');
        if (!adjacentOptions) {
            updateDisabledColumns();
            return;
        }

        adjacentOptions.innerHTML = '';
        selectedAdjacentCount = 0;

        if (totalPeople === 0) {
            remainingSeats = 0;
            updateDisabledColumns();
            return;
        }

        remainingSeats = totalPeople - selectedSeats.length;

        let availableOptions = [];

        if (totalPeople === 1) {
            availableOptions = [1];
            selectedAdjacentCount = 1;
        } else if (totalPeople === 2) {
            availableOptions = [2];
            selectedAdjacentCount = 2;
        } else if (totalPeople === 3) {
            availableOptions = [3];
            selectedAdjacentCount = 3;
        } else if (totalPeople === 4) {
            availableOptions = [2, 4];
        } else if (totalPeople === 5) {
            availableOptions = [2, 3];
        } else if (totalPeople === 6) {
            availableOptions = [2, 3, 4];
        } else if (totalPeople === 7) {
            availableOptions = [2, 3, 4];
        } else if (totalPeople === 8) {
            availableOptions = [2, 3, 4];
        }

        updateDisabledColumns();

        availableOptions.forEach(count => {
            const option = document.createElement('div');
            option.className = 'adjacent-option';
            option.setAttribute('data-count', count);

            const radio = document.createElement('div');
            radio.className = 'adjacent-option-radio';

            const seatsContainer = document.createElement('div');
            seatsContainer.className = 'adjacent-option-seats';
            for (let i = 0; i < count; i++) {
                const seatBox = document.createElement('div');
                seatBox.className = 'adjacent-seat-box';
                seatsContainer.appendChild(seatBox);
            }

            option.appendChild(radio);
            option.appendChild(seatsContainer);

            if (totalPeople <= 3 && count === totalPeople) {
                option.classList.add('active');
                selectedAdjacentCount = count;
            }

            option.onclick = function() {
                document.querySelectorAll('.adjacent-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                const newAdjacentCount = parseInt(this.getAttribute('data-count'), 10);
                selectedAdjacentCount = newAdjacentCount;
                remainingSeats = totalPeople - selectedSeats.length;
                updateDisabledColumns();
                updateSummary();
            };

            adjacentOptions.appendChild(option);
        });

        setTimeout(updateDisabledColumns, 100);
    }

    function updateDisabledColumns() {
        const totalPeople = adultCount + studentCount;

        document.querySelectorAll('.seat').forEach(seat => {
            const col = parseInt(seat.getAttribute('data-seat-column')) || 0;

            if (totalPeople === 1 && selectedAdjacentCount === 1) {
                // Khi chọn 1 vé, chỉ cho phép các cột được chỉ định
                if (col > 0 && !ALLOWED_SINGLE_COLUMNS.includes(col) &&
                    !seat.classList.contains('booked') &&
                    !seat.classList.contains('maintenance') &&
                    !seat.classList.contains('selected')) {
                    seat.classList.add('disabled-column');
                    // Remove other classes to ensure proper styling
                    seat.classList.remove('vip', 'available');
                } else {
                    seat.classList.remove('disabled-column');
                    // Restore original class if not booked/maintenance/selected
                    if (!seat.classList.contains('booked') && 
                        !seat.classList.contains('maintenance') && 
                        !seat.classList.contains('selected')) {
                        const seatType = seat.getAttribute('data-seat-type');
                        if (seatType === 'vip') {
                            seat.classList.add('vip');
                        } else {
                            seat.classList.add('available');
                        }
                    }
                }
            } else {
                // Bỏ disabled khi không phải chọn 1 vé
                seat.classList.remove('disabled-column');
                // Restore original class if not booked/maintenance/selected
                if (!seat.classList.contains('booked') && 
                    !seat.classList.contains('maintenance') && 
                    !seat.classList.contains('selected')) {
                    const seatType = seat.getAttribute('data-seat-type');
                    if (seatType === 'vip') {
                        seat.classList.add('vip');
                    } else {
                        seat.classList.add('available');
                    }
                }
            }
        });
    }

    // Ẩn tất cả ghế có seat_number > 12
    function hideSeatsOver12() {
        document.querySelectorAll('.seat-selection-container .seat[data-seat-column]').forEach(seat => {
            const col = parseInt(seat.getAttribute('data-seat-column')) || 0;
            if (col > 12) {
                seat.style.display = 'none';
                seat.style.visibility = 'hidden';
            }
        });
    }

    // Gọi updateDisabledColumns và hideSeatsOver12 khi trang tải xong
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            hideSeatsOver12();
            updateDisabledColumns();
        });
    } else {
        // DOM đã tải xong
        hideSeatsOver12();
        updateDisabledColumns();
    }
</script>