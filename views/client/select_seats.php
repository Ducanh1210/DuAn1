<?php
$showtime = $showtime ?? null;
$movie = $movie ?? null;
$room = $room ?? null;
$seatsByRow = $seatsByRow ?? [];
$bookedSeats = $bookedSeats ?? [];
$countdownSeconds = $countdownSeconds ?? 900;
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
    margin: 30px auto;
    max-width: 1200px;
    padding: 0 20px;
}

.screen {
    background: linear-gradient(to bottom, #ff8c00, #ff6b00);
    height: 80px;
    border-radius: 50px 50px 0 0;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
}

.room-title {
    font-size: 24px;
    font-weight: bold;
    margin-top: 20px;
    color: #fff;
}

.seats-grid {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

.seat-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
    gap: 5px;
}

.row-label {
    width: 30px;
    text-align: center;
    font-weight: bold;
    color: #fff;
    font-size: 16px;
}

.seat {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.2s;
    border: 2px solid transparent;
}

.seat.available {
    background: #4a4a4a;
    color: #fff;
    border-color: #666;
}

.seat.available:hover {
    background: #5a5a5a;
    transform: scale(1.1);
}

.seat.selected {
    background: #ff8c00;
    color: #fff;
    border-color: #ff6b00;
}

.seat.booked {
    background: #ff8c00;
    color: #fff;
    cursor: not-allowed;
    opacity: 0.7;
}

.seat.special {
    background: #28a745;
    color: #fff;
}

.seat.special.selected {
    background: #ffc107;
    color: #000;
}

.seat-gap {
    width: 20px;
}

.seat-legend {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin: 30px 0;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.legend-seat {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
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
        <div class="showtime-info">
            Giờ chiếu: <strong><?= date('H:i', strtotime($showtime['start_time'] ?? '00:00')) ?></strong>
        </div>
        <div class="countdown-timer" id="countdownTimer">
            Thời gian chọn ghế: <span id="countdown">15:00</span>
        </div>
    </div>

    <div class="screen-container">
        <div class="screen"></div>
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
            Phòng chiếu số <?= $roomNumber ?: htmlspecialchars($room['name'] ?? '') ?>
        </div>
    </div>

    <div class="seats-grid">
        <?php foreach ($seatsByRow as $rowLabel => $rowSeats): ?>
            <div class="seat-row">
                <div class="row-label"><?= htmlspecialchars($rowLabel) ?></div>
                <?php
                $prevSeatNumber = 0;
                foreach ($rowSeats as $seat):
                    $seatId = $seat['id'];
                    $seatNumber = $seat['seat_number'] ?? 0;
                    $seatLabel = ($seat['row_label'] ?? '') . $seatNumber;
                    $seatKey = $seatLabel;
                    $isBooked = in_array($seatKey, $bookedSeats);
                    $seatType = $seat['seat_type'] ?? 'normal';
                    $isSpecial = in_array($seatType, ['vip', 'couple', 'special']);
                    
                    // Thêm khoảng trống nếu cần (sau mỗi 4 ghế)
                    if ($prevSeatNumber > 0 && ($seatNumber - $prevSeatNumber) > 1) {
                        // Có khoảng trống giữa các ghế
                        for ($i = $prevSeatNumber + 1; $i < $seatNumber; $i++) {
                            // Thêm khoảng trống sau mỗi 4 ghế
                            if (($i - 1) % 4 == 0 && $i > 1) {
                                echo '<div class="seat-gap"></div>';
                            }
                        }
                    } elseif ($prevSeatNumber > 0 && ($prevSeatNumber % 4 == 0)) {
                        // Thêm khoảng trống sau mỗi 4 ghế liên tiếp
                        echo '<div class="seat-gap"></div>';
                    }
                ?>
                    <div class="seat <?= $isBooked ? 'booked' : ($isSpecial ? 'special' : 'available') ?>" 
                         data-seat-id="<?= $seatId ?>"
                         data-seat-label="<?= htmlspecialchars($seatLabel) ?>"
                         data-seat-type="<?= htmlspecialchars($seatType) ?>"
                         <?= $isBooked ? 'title="Ghế đã được đặt"' : 'onclick="toggleSeat(this)"' ?>>
                        <?= htmlspecialchars($seatLabel) ?>
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
            <div class="legend-seat available"></div>
            <span>Ghế trống</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat selected" style="background: #ff8c00;"></div>
            <span>Ghế đã chọn</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat booked" style="background: #ff8c00; opacity: 0.7;"></div>
            <span>Ghế đã đặt</span>
        </div>
        <div class="legend-item">
            <div class="legend-seat special" style="background: #28a745;"></div>
            <span>Ghế đặc biệt</span>
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
const seatPrice = 80000; // Giá mỗi ghế (có thể lấy từ showtime)

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
    const seatId = seatElement.getAttribute('data-seat-id');
    const seatLabel = seatElement.getAttribute('data-seat-label');
    const seatType = seatElement.getAttribute('data-seat-type');
    
    if (seatElement.classList.contains('selected')) {
        // Bỏ chọn
        seatElement.classList.remove('selected');
        selectedSeats = selectedSeats.filter(s => s.id !== seatId);
    } else {
        // Chọn ghế
        seatElement.classList.add('selected');
        selectedSeats.push({
            id: seatId,
            label: seatLabel,
            type: seatType
        });
    }
    
    updateSummary();
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
        `<span class="selected-seat-badge">${seat.label}</span>`
    ).join('');
    
    const total = selectedSeats.length * seatPrice;
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
</script>

