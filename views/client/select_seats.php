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
    margin: 30px auto;
    max-width: 1400px;
    padding: 0 40px;
    width: 100%;
}

.screen {
    background: linear-gradient(to bottom, #ff8c00, #ff6b00);
    height: 60px;
    border-radius: 50px 50px 0 0;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    color: white;
    font-weight: bold;
    font-size: 18px;
    letter-spacing: 2px;
}

.room-title {
    font-size: 20px;
    font-weight: bold;
    margin-top: 15px;
    color: #fff;
    text-align: center;
}

.seats-grid {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.seat-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 12px;
    gap: 8px;
    transition: opacity 0.3s, transform 0.3s;
    width: 100%;
}

.seat-row.hidden {
    display: none;
}

.seat-row.filtered-highlight {
    transform: scale(1.02);
    opacity: 1;
}

.row-label {
    width: 40px;
    text-align: center;
    font-weight: bold;
    color: #fff;
    font-size: 18px;
    flex-shrink: 0;
}

.seat {
    width: 55px;
    height: 55px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: all 0.2s;
    border: 2px solid transparent;
    min-width: 55px;
}

/* Ghế thường - màu xám đậm như ảnh */
.seat.available {
    background: #4a4a4a;
    color: #fff;
    border-color: #555;
}

.seat.available:hover {
    background: #5a5a5a;
    transform: scale(1.1);
}

/* Ghế VIP - màu vàng */
.seat.vip {
    background: #ffc107;
    color: #000;
    border-color: #ffb300;
}

.seat.vip:hover {
    background: #ffb300;
    transform: scale(1.1);
}

/* Ghế đã chọn - màu cam như ảnh */
.seat.selected {
    background: #ff8c00;
    color: #fff;
    border-color: #ff6b00;
}

.seat.vip.selected {
    background: #ff8c00;
    color: #fff;
    border-color: #ff6b00;
}

/* Ghế đã đặt - màu đỏ */
.seat.booked {
    background: #dc3545;
    color: #fff;
    cursor: not-allowed;
    opacity: 0.9;
}

/* Ghế bảo trì - màu vàng với dấu X */
.seat.maintenance {
    background: #ffc107;
    color: transparent; /* Ẩn số ghế, chỉ hiển thị dấu X */
    cursor: not-allowed;
    opacity: 0.8;
    position: relative;
}

.seat.maintenance::after {
    content: '✕';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 20px;
    font-weight: bold;
    color: #000;
    z-index: 2;
    line-height: 1;
}

.seat-gap {
    width: 30px;
    flex-shrink: 0;
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
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
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
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    padding: 0 40px;
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
            
            <div id="adjacentSeatsSection" style="margin-bottom: 25px;">
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
        <div class="screen">MÀN HÌNH</div>
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
    </div>

    <div class="seats-grid" id="seatsGrid">
        <?php foreach ($seatsByRow as $rowLabel => $rowSeats): ?>
            <div class="seat-row" data-row-label="<?= strtoupper(htmlspecialchars($rowLabel)) ?>">
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
                    $seatStatus = $seat['status'] ?? 'available';
                    $isMaintenance = ($seatStatus === 'maintenance');
                    
                    // Chỉ hiển thị ghế normal và vip, bỏ qua disabled và couple
                    if (in_array($seatType, ['disabled', 'couple'])) {
                        continue;
                    }
                    
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
                    if ($isBooked) {
                        $title = 'title="Ghế đã được đặt"';
                    } elseif ($isMaintenance) {
                        $title = 'title="Ghế đang bảo trì"';
                    } else {
                        $onClick = 'onclick="toggleSeat(this)"';
                    }
                ?>
                    <div class="seat <?= $seatClass ?>" 
                         data-seat-id="<?= $seatId ?>"
                         data-seat-label="<?= htmlspecialchars($seatLabel) ?>"
                         data-seat-type="<?= htmlspecialchars($seatType) ?>"
                         data-seat-status="<?= htmlspecialchars($seatStatus) ?>"
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
const adultNormalPrice = <?= $normalPrice ?? 70000 ?>; // Giá ghế thường người lớn từ database
const adultVipPrice = <?= $vipPrice ?? 80000 ?>; // Giá ghế VIP người lớn từ database
const studentNormalPrice = <?= $studentNormalPrice ?? 60000 ?>; // Giá ghế thường sinh viên từ database
const studentVipPrice = <?= $studentVipPrice ?? 70000 ?>; // Giá ghế VIP sinh viên từ database

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
    
    // Kiểm tra ghế liền kề có bị đặt không (cách 1 ô)
    const adjacentError = checkAdjacentBookedSeats(seatElement);
    if (adjacentError) {
        alert(adjacentError);
        return;
    }
    
    const seatId = seatElement.getAttribute('data-seat-id');
    
    // Nếu ghế đã được chọn, bỏ chọn
    if (seatElement.classList.contains('selected')) {
        removeSeatFromGroup(seatId);
        updateSummary();
        return;
    }
    
    const totalPeople = adultCount + studentCount;
    if (totalPeople === 0) {
        alert('Vui lòng chọn số lượng người trước!');
        return;
    }
    
    // Kiểm tra số lượng ghế đã chọn
    if (selectedSeats.length >= totalPeople) {
        alert(`Bạn chỉ có thể chọn tối đa ${totalPeople} ghế!`);
        return;
    }
    
    // Nếu đã chọn ghế rồi, chỉ cho phép chọn tiếp nếu còn ghế cần chọn
    if (selectedSeats.length > 0) {
        // Đã chọn ghế rồi, cần chọn số lượng ghế liền nhau trước
        // Tính số ghế còn lại cần chọn
        remainingSeats = totalPeople - selectedSeats.length;
        
        // Kiểm tra xem đã chọn số lượng ghế liền nhau chưa
        if (selectedAdjacentCount === 0) {
            alert('Vui lòng chọn số lượng ghế liền nhau trước!');
            return;
        }
        
        // Đảm bảo số lượng ghế liền nhau không vượt quá số lượng còn lại
        const seatsToSelect = Math.min(selectedAdjacentCount, remainingSeats);
        
        // Chọn số ghế còn lại
        const groupSeats = selectAdjacentSeats(seatElement, seatsToSelect);
        if (groupSeats.length > 0) {
            selectedGroups.push({
                count: seatsToSelect,
                seats: groupSeats
            });
            selectedSeats = selectedSeats.concat(groupSeats);
            remainingSeats = totalPeople - selectedSeats.length;
            
            // Nếu còn ghế, tự động set lại số lượng ghế liền nhau dựa trên số lượng còn lại
            if (remainingSeats > 0) {
                // Tự động set selectedAdjacentCount dựa trên số lượng còn lại
                if (remainingSeats <= 3) {
                    selectedAdjacentCount = remainingSeats;
                } else {
                    selectedAdjacentCount = 2; // Mặc định 2 cho số lượng > 3
                }
            } else {
                selectedAdjacentCount = 0;
            }
        }
        
        // Cập nhật lại summary để hiển thị các tùy chọn ghế liền nhau mới
        updateSummary();
    } else {
        // Chưa chọn ghế nào, cần chọn số lượng ghế liền nhau trước
        if (selectedAdjacentCount === 0) {
            alert('Vui lòng chọn số lượng ghế liền nhau!');
            return;
        }
        
        // Tính số ghế còn lại cần chọn
        remainingSeats = totalPeople - selectedSeats.length;
        
        // Nếu chọn ghế liền nhau, cần chọn đúng số lượng
        if (remainingSeats >= selectedAdjacentCount) {
            // Chọn nhóm ghế mới
            const groupSeats = selectAdjacentSeats(seatElement, selectedAdjacentCount);
            if (groupSeats.length > 0) {
                selectedGroups.push({
                    count: selectedAdjacentCount,
                    seats: groupSeats
                });
                selectedSeats = selectedSeats.concat(groupSeats);
                remainingSeats = totalPeople - selectedSeats.length;
            }
        } else {
            // Chọn số ghế còn lại
            const groupSeats = selectAdjacentSeats(seatElement, remainingSeats);
            if (groupSeats.length > 0) {
                selectedGroups.push({
                    count: remainingSeats,
                    seats: groupSeats
                });
                selectedSeats = selectedSeats.concat(groupSeats);
                remainingSeats = 0;
            }
        }
    }
    
    updateSummary();
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
            break;
        }
    }
}

// Kiểm tra ghế liền kề có bị đặt không
function checkAdjacentBookedSeats(seatElement) {
    const row = seatElement.closest('.seat-row');
    if (!row) return null;
    
    // Lấy số ghế hiện tại từ label (ví dụ: "A3" -> số 3)
    const currentSeatLabel = seatElement.getAttribute('data-seat-label') || '';
    const currentRow = currentSeatLabel.charAt(0);
    const currentNumber = parseInt(currentSeatLabel.substring(1)) || 0;
    
    if (currentNumber === 0) return null;
    
    // Lấy tất cả ghế trong hàng cùng row
    const allSeatsInRow = Array.from(row.querySelectorAll('.seat'));
    
    // Tìm ghế bên trái (số nhỏ hơn 1) và ghế bên phải (số lớn hơn 1)
    for (let seat of allSeatsInRow) {
        const seatLabel = seat.getAttribute('data-seat-label') || '';
        const seatRow = seatLabel.charAt(0);
        const seatNumber = parseInt(seatLabel.substring(1)) || 0;
        
        // Chỉ kiểm tra ghế cùng hàng
        if (seatRow !== currentRow) continue;
        
        // Kiểm tra ghế bên trái (số nhỏ hơn đúng 1)
        if (seatNumber === currentNumber - 1 && seat.classList.contains('booked')) {
            return `Không thể chọn ghế này vì ghế ${seatLabel} đã được đặt. Vui lòng chọn ghế khác (không được cách 1 ô với ghế đã đặt).`;
        }
        
        // Kiểm tra ghế bên phải (số lớn hơn đúng 1)
        if (seatNumber === currentNumber + 1 && seat.classList.contains('booked')) {
            return `Không thể chọn ghế này vì ghế ${seatLabel} đã được đặt. Vui lòng chọn ghế khác (không được cách 1 ô với ghế đã đặt).`;
        }
    }
    
    return null;
}

// Kiểm tra các ghế trong nhóm có ghế nào liền kề với ghế đã đặt không
function checkGroupAdjacentBookedSeats(seatElements) {
    for (let seatEl of seatElements) {
        const error = checkAdjacentBookedSeats(seatEl);
        if (error) {
            return error;
        }
    }
    return null;
}

function selectAdjacentSeats(startSeatElement, count) {
    // Tìm các ghế liền nhau từ ghế bắt đầu
    const row = startSeatElement.closest('.seat-row');
    if (!row) return [];
    
    // Lấy số ghế bắt đầu
    const startSeatLabel = startSeatElement.getAttribute('data-seat-label') || '';
    const startRow = startSeatLabel.charAt(0);
    const startNumber = parseInt(startSeatLabel.substring(1)) || 0;
    
    if (startNumber === 0) return [];
    
    // Lấy tất cả ghế trong hàng
    const allSeatsInRow = Array.from(row.querySelectorAll('.seat'));
    
    // Tìm các ghế sẽ được chọn (dựa trên số ghế liền nhau)
    const seatsToSelect = [];
    const seatsToSelectElements = [];
    
    // Tìm các ghế từ startNumber đến startNumber + count - 1
    for (let num = startNumber; num < startNumber + count; num++) {
        const targetLabel = startRow + num;
        
        // Tìm ghế có label tương ứng
        const targetSeat = allSeatsInRow.find(seat => {
            return seat.getAttribute('data-seat-label') === targetLabel;
        });
        
        if (!targetSeat) {
            alert(`Không tìm thấy ghế ${targetLabel}!`);
            return [];
        }
        
        // Kiểm tra ghế đã đặt hoặc bảo trì
        if (targetSeat.classList.contains('booked') || targetSeat.classList.contains('maintenance')) {
            alert(`Không thể chọn ghế ${targetLabel} vì ghế này đã được đặt hoặc đang bảo trì!`);
            return [];
        }
        
        seatsToSelectElements.push(targetSeat);
    }
    
    // Kiểm tra ghế liền kề bên trái của ghế đầu tiên (startNumber - 1)
    const leftSeatNumber = startNumber - 1;
    if (leftSeatNumber > 0) {
        const leftSeatLabel = startRow + leftSeatNumber;
        const leftSeat = allSeatsInRow.find(seat => {
            return seat.getAttribute('data-seat-label') === leftSeatLabel;
        });
        
        if (leftSeat && leftSeat.classList.contains('booked')) {
            alert(`Không thể chọn ghế này vì ghế ${leftSeatLabel} đã được đặt. Vui lòng chọn ghế khác (không được cách 1 ô với ghế đã đặt).`);
            return [];
        }
    }
    
    // Kiểm tra ghế liền kề bên phải của ghế cuối cùng (startNumber + count)
    const rightSeatNumber = startNumber + count;
    const rightSeatLabel = startRow + rightSeatNumber;
    const rightSeat = allSeatsInRow.find(seat => {
        return seat.getAttribute('data-seat-label') === rightSeatLabel;
    });
    
    if (rightSeat && rightSeat.classList.contains('booked')) {
        alert(`Không thể chọn ${count} ghế từ vị trí này vì ghế ${rightSeatLabel} đã được đặt. Vui lòng chọn ghế khác (không được cách 1 ô với ghế đã đặt).`);
        return [];
    }
    
    // Kiểm tra từng ghế trong nhóm xem có liền kề với ghế booked không
    for (let seatEl of seatsToSelectElements) {
        const error = checkAdjacentBookedSeats(seatEl);
        if (error) {
            alert(error);
            return [];
        }
    }
    
    // Nếu tất cả đều OK, chọn các ghế
    for (let seatEl of seatsToSelectElements) {
        // Bỏ qua nếu đã được chọn
        if (seatEl.classList.contains('selected')) {
            continue;
        }
        
        const seatId = seatEl.getAttribute('data-seat-id');
        const seatLabel = seatEl.getAttribute('data-seat-label');
        const seatType = seatEl.getAttribute('data-seat-type');
        const seatStatus = seatEl.getAttribute('data-seat-status');
        
        seatEl.classList.add('selected');
        seatEl.classList.remove('vip', 'available');
        seatsToSelect.push({
            id: seatId,
            label: seatLabel,
            type: seatType,
            status: seatStatus
        });
    }
    
    return seatsToSelect;
}

function updateSummary() {
    const summaryElement = document.getElementById('selectedSeatsSummary');
    const seatsListElement = document.getElementById('selectedSeatsList');
    const totalPriceElement = document.getElementById('totalPrice');
    const continueBtn = document.getElementById('continueBtn');
    const adjacentSeatsSection = document.getElementById('adjacentSeatsSection');
    const remainingSeatsInfo = document.getElementById('remainingSeatsInfo');
    
    const totalPeople = adultCount + studentCount;
    remainingSeats = totalPeople - selectedSeats.length;
    
    // Hiển thị/ẩn phần chọn ghế liền nhau dựa trên số lượng còn lại
    if (remainingSeats > 0 && selectedSeats.length > 0) {
        // Đã chọn ghế nhưng còn ghế cần chọn, hiển thị lại phần chọn ghế liền nhau
        if (adjacentSeatsSection) {
            adjacentSeatsSection.style.display = 'block';
        }
        // Cập nhật lại các tùy chọn ghế liền nhau dựa trên số lượng còn lại
        updateAdjacentOptionsForRemaining();
    } else if (selectedSeats.length === 0) {
        // Chưa chọn ghế nào, hiển thị phần chọn ghế liền nhau
        if (adjacentSeatsSection) {
            adjacentSeatsSection.style.display = 'block';
        }
    } else {
        // Đã chọn đủ ghế, ẩn phần chọn ghế liền nhau
        if (adjacentSeatsSection) {
            adjacentSeatsSection.style.display = 'none';
        }
    }
    
    // Cập nhật thông tin số lượng còn lại
    if (remainingSeatsInfo) {
        if (selectedSeats.length > 0 && remainingSeats > 0) {
            remainingSeatsInfo.textContent = `Còn lại: ${remainingSeats} ghế cần chọn. (Tổng: ${totalPeople} người)`;
            remainingSeatsInfo.style.color = '#ff8c00';
            remainingSeatsInfo.style.fontWeight = 'bold';
        } else if (selectedSeats.length === 0) {
            remainingSeatsInfo.textContent = `Có thể chọn tối đa 8 người. (Max:8)`;
            remainingSeatsInfo.style.color = 'rgba(255, 255, 255, 0.6)';
            remainingSeatsInfo.style.fontWeight = 'normal';
        } else {
            remainingSeatsInfo.textContent = `Đã chọn đủ ${totalPeople} ghế.`;
            remainingSeatsInfo.style.color = '#28a745';
            remainingSeatsInfo.style.fontWeight = 'bold';
        }
    }
    
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
    
    continueBtn.disabled = remainingSeats === 0;
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
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

function updateAdjacentOptionsForRemaining() {
    const adjacentOptions = document.getElementById('adjacentOptions');
    if (!adjacentOptions) return;
    
    adjacentOptions.innerHTML = '';
    
    // Tính số lượng còn lại
    const totalPeople = adultCount + studentCount;
    remainingSeats = totalPeople - selectedSeats.length;
    
    if (remainingSeats === 0) {
        selectedAdjacentCount = 0;
        return;
    }
    
    // Logic chọn ghế liền nhau dựa trên số lượng còn lại
    let availableOptions = [];
    
    if (remainingSeats === 1) {
        availableOptions = [1];
    } else if (remainingSeats === 2) {
        availableOptions = [2];
    } else if (remainingSeats === 3) {
        availableOptions = [3];
    } else if (remainingSeats === 4) {
        availableOptions = [2, 3, 4];
    } else if (remainingSeats === 5) {
        availableOptions = [2, 3, 4];
    } else if (remainingSeats === 6) {
        availableOptions = [2, 3, 4];
    } else if (remainingSeats === 7) {
        availableOptions = [2, 3, 4];
    } else if (remainingSeats === 8) {
        availableOptions = [2, 3, 4];
    }
    
    // Tự động set selectedAdjacentCount dựa trên số lượng còn lại
    // Nếu số lượng còn lại <= 3, tự động set bằng số lượng còn lại
    // Nếu số lượng còn lại > 3, mặc định set 2
    // Luôn reset và set lại để đảm bảo đúng
    if (remainingSeats <= 3) {
        selectedAdjacentCount = remainingSeats;
    } else {
        // Luôn set mặc định 2 cho số lượng > 3
        selectedAdjacentCount = 2;
    }
    
    // Render các ô chọn
    availableOptions.forEach(count => {
        // Chỉ hiển thị các tùy chọn không vượt quá số lượng còn lại
        if (count > remainingSeats) {
            return;
        }
        
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
        
        // Tự động active cho tùy chọn phù hợp với selectedAdjacentCount
        if (count === selectedAdjacentCount) {
            option.classList.add('active');
        }
        
        option.onclick = function() {
            document.querySelectorAll('.adjacent-option').forEach(opt => {
                opt.classList.remove('active');
            });
            this.classList.add('active');
            selectedAdjacentCount = count;
            // Cập nhật lại summary để hiển thị thông tin mới nhất
            updateSummary();
        };
        adjacentOptions.appendChild(option);
    });
}

function updateTicketSelection() {
    adultCount = parseInt(document.getElementById('adultQuantity').value) || 0;
    studentCount = parseInt(document.getElementById('studentQuantity').value) || 0;
    const totalPeople = adultCount + studentCount;
    
    // Xóa các ghế đã chọn nếu thay đổi số lượng
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
        selectedSeats = [];
        selectedGroups = [];
        remainingSeats = totalPeople;
    }
    
    // Cập nhật các ô chọn ghế liền nhau
    const adjacentOptions = document.getElementById('adjacentOptions');
    const adjacentSeatsSection = document.getElementById('adjacentSeatsSection');
    
    if (!adjacentOptions) return;
    
    // Hiển thị phần chọn ghế liền nhau
    if (adjacentSeatsSection) {
        adjacentSeatsSection.style.display = 'block';
    }
    
    adjacentOptions.innerHTML = '';
    selectedAdjacentCount = 0;
    remainingSeats = totalPeople;
    
    if (totalPeople === 0) {
        updateSummary();
        return;
    }
    
    // Logic chọn ghế liền nhau dựa trên số lượng người
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
    
    // Render các ô chọn
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
        
        // Tự động active cho 1, 2, 3
        if (totalPeople <= 3 && count === totalPeople) {
            option.classList.add('active');
            selectedAdjacentCount = count;
        }
        
        option.onclick = function() {
            document.querySelectorAll('.adjacent-option').forEach(opt => {
                opt.classList.remove('active');
            });
            this.classList.add('active');
            selectedAdjacentCount = count;
            remainingSeats = totalPeople;
            selectedGroups = [];
            
            // Xóa các ghế đã chọn trước đó
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
            selectedSeats = [];
            updateSummary();
        };
        adjacentOptions.appendChild(option);
    });
    
    updateSummary();
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', function() {
    updateTicketSelection();
});
</script>

