<?php
// Lấy thông tin phim từ biến $movie (sẽ được truyền từ controller)
$movie = $movie ?? null;
$showtimes = $showtimes ?? [];
$dates = $dates ?? [];
$selectedDate = $selectedDate ?? date('Y-m-d');
?>

<!-- phần nội dung -->
<section class="movie-hero" aria-label="Chi tiết phim" 
    <?php if ($movie && !empty($movie['image'])): ?>
        style="background-image: url('<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>');"
    <?php endif; ?>>
    <div class="container">
        <div class="hero-row">
            <div class="poster" role="img" aria-label="Poster phim">
                <?php if ($movie && !empty($movie['image'])): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <?php else: ?>
                    <img src="<?= BASE_URL ?>/image/logo.png" alt="Poster phim">
                <?php endif; ?>
            </div>

            <div class="hero-meta">
                <div class="title-section">
                    <?php if ($movie && !empty($movie['format'])): ?>
                        <span class="badge-2d"><?= htmlspecialchars($movie['format']) ?></span>
                    <?php endif; ?>
                    
                    <h1 class="title">
                        <?= $movie ? strtoupper(htmlspecialchars($movie['title'])) : 'Tên phim' ?>
                        <?php if ($movie && !empty($movie['age_rating'])): ?>
                            -<?= htmlspecialchars($movie['age_rating']) ?>
                        <?php endif; ?>
                    </h1>
                </div>

                <div class="meta-line">
                    <?php if ($movie): ?>
                        <?= !empty($movie['genre_name']) ? htmlspecialchars($movie['genre_name']) : '—' ?>
                        <?php if (!empty($movie['original_language'])): ?>
                            &nbsp;&nbsp; • &nbsp;&nbsp; <?= htmlspecialchars($movie['original_language']) ?>
                        <?php endif; ?>
                        <?php if (!empty($movie['duration'])): ?>
                            &nbsp;&nbsp; • &nbsp;&nbsp; <?= htmlspecialchars($movie['duration']) ?> phút
                        <?php endif; ?>
                    <?php else: ?>
                        Thông tin phim
                    <?php endif; ?>
                </div>

                <?php if ($movie && !empty($movie['producer'])): ?>
                    <div class="info-line">
                        <strong>Nhà xuất bản:</strong> <?= htmlspecialchars($movie['producer']) ?>
                    </div>
                <?php endif; ?>

                <?php if ($movie && !empty($movie['release_date'])): ?>
                    <div class="info-line">
                        <strong>Khởi chiếu:</strong> <?= date('d/m/Y', strtotime($movie['release_date'])) ?>
                    </div>
                <?php endif; ?>

                <p class="desc">
                    <?= $movie && !empty($movie['description']) ? nl2br(htmlspecialchars($movie['description'])) : 'Mô tả phim...' ?>
                </p>

                <?php if ($movie && !empty($movie['age_rating'])): ?>
                    <div class="warning">
                        Kiểm duyệt: <?= htmlspecialchars($movie['age_rating']) ?> - Phim được phổ biến đến người xem từ đủ <?= str_replace(['T', 'C', 'P'], '', $movie['age_rating']) ?> tuổi trở lên (<?= htmlspecialchars($movie['age_rating']) ?>).
                    </div>
                <?php endif; ?>

                <div class="hero-actions">
                    <a class="details-link" href="#">Chi tiết nội dung</a>
                    <?php if ($movie && !empty($movie['trailer'])): ?>
                        <button class="btn btn-outline" id="watchTrailer" data-trailer="<?= htmlspecialchars($movie['trailer']) ?>">
                            <span>▶</span> Xem trailer
                        </button>
                    <?php endif; ?>
                </div>

                <!-- dates & times -->
                <div class="dates-wrap" id="datesWrap" aria-label="Chọn ngày và suất chiếu">
                    <div class="date-tabs" id="dateTabs">
                        <?php if (!empty($dates)): ?>
                            <?php foreach ($dates as $date): ?>
                                <a href="<?= BASE_URL ?>?act=movies&id=<?= $movie['id'] ?? '' ?>&date=<?= $date['date'] ?>" 
                                   class="date-tab <?= $selectedDate == $date['date'] ? 'active' : '' ?>">
                                    <span class="dayname">Th. <?= $date['month'] ?? date('m', strtotime($date['date'])) ?> <?= $date['daynum'] ?? date('d', strtotime($date['date'])) ?> <?= $date['dayname'] ?? '' ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="times-row" id="timesRow">
                        <?php if (!empty($showtimes)): ?>
                            <?php foreach ($showtimes as $showtime): ?>
                                <button type="button" 
                                        class="time-pill" 
                                        data-showtime-id="<?= $showtime['id'] ?>"
                                        data-showtime-time="<?= date('H:i', strtotime($showtime['start_time'])) ?>"
                                        onclick="showSeatSelection(this)">
                                    <?= date('H:i', strtotime($showtime['start_time'])) ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted" style="width: 100%; text-align: center; padding: 20px;">
                                Chưa có suất chiếu cho ngày này
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Container cho sơ đồ ghế -->
<div id="seatSelectionContainer" style="display: none; background: #2a2a2a; padding: 20px 0; margin-top: 40px;">
    <div class="container">
        <div id="seatSelectionContent"></div>
    </div>
</div>

<style>
.seat-selection-wrapper {
    background: #2a2a2a;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
}

.seat-selection-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #1a1a1a;
    border-radius: 8px;
    margin-bottom: 20px;
}

.showtime-info {
    font-size: 16px;
    font-weight: 500;
    color: #fff;
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
    position: relative;
}

.screen {
    background: linear-gradient(to bottom, #ff8c00, #ff6b00);
    height: 60px;
    border-radius: 50px 50px 0 0;
    margin-bottom: 10px;
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    position: relative;
}

.screen::after {
    content: 'MÀN HÌNH';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
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
    max-width: 1200px;
    margin: 20px auto;
}

.seat-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 8px;
    gap: 8px;
}

.seat-block {
    display: flex;
    gap: 4px;
    align-items: center;
    flex-wrap: nowrap;
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
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.2s;
    border: 1px solid transparent;
    margin: 0;
}

/* Ghế thường - có thể chọn (màu xám đậm như trong hình) */
.seat.available {
    background: #4a4a4a;
    color: #fff;
    border-color: #555;
}

.seat.available:hover {
    background: #5a5a5a;
    transform: scale(1.1);
}

/* Ghế đã chọn */
.seat.selected {
    background: #00bcd4;
    color: #fff;
    border-color: #0097a7;
}

/* Ghế đã đặt */
.seat.booked {
    background: #f44336;
    color: #fff;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Ghế VIP */
.seat.vip {
    background: #ffeb3b;
    color: #000;
    border-color: #fdd835;
    font-weight: bold;
}

.seat.vip:hover {
    background: #fff176;
    transform: scale(1.05);
}

.seat.vip.selected {
    background: #00bcd4;
    color: #fff;
    border-color: #0097a7;
}

/* Ghế đôi */
.seat.couple {
    background: #03a9f4;
    color: #fff;
    border-color: #0288d1;
    font-weight: bold;
}

.seat.couple:hover {
    background: #29b6f6;
    transform: scale(1.05);
}

.seat.couple.selected {
    background: #00bcd4;
    color: #fff;
    border-color: #0097a7;
}

/* Ghế khuyết tật */
.seat.disabled {
    background: #000;
    color: #fff;
    border-color: #333;
    cursor: not-allowed;
}

.seat.disabled:hover {
    background: #1a1a1a;
    transform: scale(1);
}

/* Ghế bảo trì */
.seat.maintenance {
    background: #ffeb3b;
    color: transparent !important; /* Ẩn số ghế, chỉ hiển thị X */
    border-color: #fdd835;
    cursor: not-allowed;
    opacity: 0.9;
    position: relative;
}

.seat.maintenance::before {
    content: 'X';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px;
    color: #f44336;
    font-weight: bold;
    z-index: 1;
    line-height: 1;
}

.seat-gap {
    width: 30px;
    margin: 0;
    flex-shrink: 0;
}

.seat-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin: 20px 0;
    flex-wrap: wrap;
    color: #fff;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.legend-seat {
    width: 30px;
    height: 30px;
    border-radius: 5px;
}

.selected-seats-summary {
    background: #1a1a1a;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
    border-top: 2px solid #00bcd4;
}

.summary-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.selected-seats-list {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.selected-seat-badge {
    background: #00bcd4;
    color: #fff;
    padding: 6px 12px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 14px;
}

.total-price {
    font-size: 20px;
    font-weight: bold;
    color: #00bcd4;
}

.continue-btn {
    background: #00bcd4;
    color: #fff;
    border: none;
    padding: 10px 30px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.continue-btn:hover {
    background: #0097a7;
    transform: translateY(-2px);
}

.continue-btn:disabled {
    background: #666;
    cursor: not-allowed;
    transform: none;
}

.back-btn:hover {
    background: #5a5a5a !important;
    border-color: #777 !important;
    transform: translateY(-1px);
}

/* Ticket Selection Panel */
.ticket-selection-panel {
    background: rgba(255, 255, 255, 0.05);
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.ticket-quantity-section {
    display: flex;
    gap: 30px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.quantity-selector {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.quantity-selector label {
    color: #fff;
    font-weight: 500;
    font-size: 14px;
}

.quantity-select {
    padding: 10px 15px;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: #fff;
    color: #000;
    font-size: 16px;
    cursor: pointer;
    min-width: 100px;
}

.quantity-select:focus {
    outline: none;
    border-color: #00bcd4;
    box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.2);
}

.quantity-select option {
    background: #fff;
    color: #000;
    padding: 8px;
}

.adjacent-seats-section {
    margin-bottom: 25px;
}

.adjacent-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

.adjacent-label label {
    color: #fff;
    font-weight: 500;
    font-size: 14px;
}

.info-icon {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: help;
}

.adjacent-options {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

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
    border-color: #00bcd4;
    background: #00bcd4;
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
    border-color: #00bcd4;
}

.adjacent-option.disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.max-seats-info {
    color: rgba(255, 255, 255, 0.6);
    font-size: 13px;
    margin-top: 10px;
}

</style>

<script>
let selectedSeats = [];
let currentShowtimeId = null;
let countdownInterval = null;
let countdown = 900; // 15 phút
let selectedAdjacentCount = 0; // Số lượng ghế liền nhau đã chọn
let adultCount = 0;
let studentCount = 0;

// Xử lý xem trailer
document.addEventListener('DOMContentLoaded', function() {
    const watchTrailerBtn = document.getElementById('watchTrailer');
    if (watchTrailerBtn) {
        watchTrailerBtn.addEventListener('click', function() {
            const trailerUrl = this.getAttribute('data-trailer');
            if (trailerUrl) {
                window.open(trailerUrl, '_blank');
            }
        });
    }
});

function showSeatSelection(button) {
    const showtimeId = button.getAttribute('data-showtime-id');
    const showtimeTime = button.getAttribute('data-showtime-time');
    
    // Đóng các sơ đồ ghế khác nếu đang mở
    document.querySelectorAll('.time-pill').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');
    
    // Ẩn phần chọn ngày và giờ chiếu
    const datesWrap = document.getElementById('datesWrap');
    if (datesWrap) {
        datesWrap.style.display = 'none';
    }
    
    // Scroll đến phần chọn ghế
    const container = document.getElementById('seatSelectionContainer');
    container.style.display = 'block';
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // Load dữ liệu ghế
    loadSeatData(showtimeId, showtimeTime);
}

function loadSeatData(showtimeId, showtimeTime) {
    currentShowtimeId = showtimeId;
    selectedSeats = [];
    
    // Reset countdown
    countdown = 900;
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    
    fetch(`<?= BASE_URL ?>?act=api-seats&showtime_id=${showtimeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            renderSeatSelection(data, showtimeTime);
            startCountdown();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu ghế');
        });
}

function renderSeatSelection(data, showtimeTime) {
    const content = document.getElementById('seatSelectionContent');
    const { showtime, movie, room, seatsByRow, bookedSeats } = data;
    
    // Lấy thông tin phòng - ưu tiên room_code, sau đó name
    let roomDisplay = '';
    if (room.room_code) {
        // Nếu có room_code, hiển thị cả mã và tên
        const roomNumber = room.room_code.match(/\d+/);
        if (roomNumber) {
            roomDisplay = `Phòng ${room.room_code}`;
        } else {
            roomDisplay = room.room_code;
        }
        // Thêm tên phòng nếu có
        if (room.name && room.name !== room.room_code) {
            roomDisplay += ` (${room.name})`;
        }
    } else if (room.name) {
        // Nếu chỉ có name, lấy số từ name
        const matches = room.name.match(/\d+/);
        if (matches) {
            roomDisplay = `Phòng chiếu số ${matches[0]}`;
        } else {
            roomDisplay = room.name;
        }
    } else {
        roomDisplay = 'Phòng chiếu';
    }
    
    let html = `
        <div class="seat-selection-wrapper">
            <div class="seat-selection-header">
                <div class="showtime-info">
                    Giờ chiếu: <strong>${showtimeTime}</strong>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button onclick="goBackToShowtimes()" class="back-btn" style="background: #4a4a4a; color: #fff; border: 1px solid #666; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.2s;">
                        ← Quay lại
                    </button>
                    <div class="countdown-timer">
                        Thời gian chọn ghế: <span id="countdown">15:00</span>
                    </div>
                </div>
            </div>
            
            <!-- Phần chọn số lượng người -->
            <div class="ticket-selection-panel">
                <h3 style="color: #fff; margin-bottom: 20px; font-size: 24px;">Chọn ghế</h3>
                
                <div class="ticket-quantity-section">
                    <div class="quantity-selector">
                        <label>Người lớn:</label>
                        <select id="adultQuantity" onchange="updateTicketSelection()" class="quantity-select">
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
                    <div class="quantity-selector">
                        <label>Sinh viên:</label>
                        <select id="studentQuantity" onchange="updateTicketSelection()" class="quantity-select">
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
                
                <div class="adjacent-seats-section">
                    <div class="adjacent-label">
                        <label>Chọn ghế liền nhau</label>
                        <span class="info-icon" title="Chọn số lượng ghế liền nhau bạn muốn">ℹ️</span>
                    </div>
                    <div class="adjacent-options" id="adjacentOptions">
                        <!-- Sẽ được render động -->
                    </div>
                    <div class="max-seats-info">
                        Có thể chọn tối đa 8 người. (Max:8)
                    </div>
                </div>
                
            </div>
            
            <div class="screen-container">
                <div class="screen"></div>
                <div class="room-title">${roomDisplay}</div>
            </div>
            
            <div class="seats-grid">
    `;
    
    // Sắp xếp hàng từ A đến Z
    const sortedRows = Object.keys(seatsByRow).sort();
    
    sortedRows.forEach(rowLabel => {
        const rowSeats = seatsByRow[rowLabel];
        // Sắp xếp ghế theo số từ lớn đến nhỏ (từ phải sang trái như ảnh)
        rowSeats.sort((a, b) => (b.seat_number || 0) - (a.seat_number || 0));
        
        // Tìm số ghế lớn nhất và nhỏ nhất trong hàng
        let maxSeatNumber = 0;
        rowSeats.forEach(seat => {
            maxSeatNumber = Math.max(maxSeatNumber, seat.seat_number || 0);
        });
        
        html += `
            <div class="seat-row">
                <div class="row-label">${rowLabel}</div>
                <div class="seat-block">
        `;
        
        // Hiển thị ghế từ lớn đến nhỏ (từ phải sang trái)
        let prevSeatNumber = maxSeatNumber + 1;
        rowSeats.forEach((seat, index) => {
            const seatNumber = seat.seat_number || 0;
            const seatLabel = (seat.row_label || '') + seatNumber;
            const seatKey = seatLabel;
            const isBooked = bookedSeats.includes(seatKey);
            const seatType = (seat.seat_type || 'normal').toLowerCase();
            const seatStatus = (seat.status || 'available').toLowerCase();
            
            // Thêm khoảng trống nếu có khoảng cách lớn giữa các ghế (gap tự động)
            if (prevSeatNumber - seatNumber > 1) {
                // Có khoảng trống giữa các ghế, thêm gap nếu cần
                const gap = prevSeatNumber - seatNumber - 1;
                // Thêm gap nếu khoảng cách >= 2
                if (gap >= 2) {
                    html += '<div class="seat-gap"></div>';
                }
            }
            
            // Xác định loại ghế và trạng thái
            // Ưu tiên: trạng thái (maintenance/booked) > loại ghế (vip/couple/disabled) > thường
            let seatClass = '';
            let onClick = `onclick="toggleSeat(this)"`;
            let title = '';
            
            // Kiểm tra trạng thái bảo trì trước (ưu tiên cao nhất)
            if (seatStatus === 'maintenance' || seatStatus === 'maintain') {
                seatClass = 'maintenance';
                onClick = '';
                title = 'title="Ghế đang bảo trì"';
            }
            // Kiểm tra ghế đã đặt (ưu tiên cao thứ 2)
            else if (isBooked) {
                seatClass = 'booked';
                onClick = '';
                title = 'title="Ghế đã được đặt"';
            }
            // Kiểm tra loại ghế đặc biệt (nếu không bị đặt)
            else if (seatType === 'vip') {
                seatClass = 'vip';
                title = 'title="Ghế VIP"';
            }
            else if (seatType === 'couple' || seatType === 'double') {
                seatClass = 'couple';
                title = 'title="Ghế đôi"';
            }
            else if (seatType === 'disabled') {
                seatClass = 'disabled';
                title = 'title="Ghế khuyết tật"';
            }
            // Ghế thường (normal) - màu xám đậm
            else {
                seatClass = 'available';
                title = 'title="Ghế thường"';
            }
            
            html += `
                <div class="seat ${seatClass}" 
                     data-seat-id="${seat.id}"
                     data-seat-label="${seatLabel}"
                     data-seat-type="${seatType}"
                     data-seat-status="${seatStatus}"
                     ${onClick}
                     ${title}>
                    ${seatNumber}
                </div>
            `;
            
            prevSeatNumber = seatNumber;
        });
        
        html += `
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
            
            <div class="seat-legend">
                <div class="legend-item">
                    <div class="legend-seat available" style="background: #4a4a4a;"></div>
                    <span>Thường</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat vip" style="background: #ffeb3b; border: 1px solid #fdd835;"></div>
                    <span>VIP</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat selected" style="background: #00bcd4; border: 1px solid #0097a7;"></div>
                    <span>Ghế bạn chọn</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat booked" style="background: #f44336; opacity: 0.8;"></div>
                    <span>Đã đặt</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat maintenance" style="background: #ffeb3b; opacity: 0.9; position: relative;">
                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #f44336; font-weight: bold; font-size: 12px;">X</span>
                    </div>
                    <span>Bảo trì</span>
                </div>
            </div>
            
            <div class="selected-seats-summary" id="selectedSeatsSummary" style="display: none;">
                <div class="summary-content">
                    <div>
                        <div style="margin-bottom: 10px; font-weight: bold; color: #fff;">Ghế đã chọn:</div>
                        <div class="selected-seats-list" id="selectedSeatsList"></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="margin-bottom: 10px; color: #fff;">Tổng tiền:</div>
                        <div class="total-price" id="totalPrice">0 đ</div>
                    </div>
                    <button class="continue-btn" id="continueBtn" onclick="continueBooking()" disabled>
                        Tiếp tục
                    </button>
                </div>
            </div>
        </div>
    `;
    
    content.innerHTML = html;
    
    // Khởi tạo phần chọn ghế liền nhau
    updateTicketSelection();
}

function startCountdown() {
    const countdownElement = document.getElementById('countdown');
    if (!countdownElement) return;
    
    countdownInterval = setInterval(() => {
        countdown--;
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            alert('Hết thời gian chọn ghế!');
            document.getElementById('seatSelectionContainer').style.display = 'none';
            return;
        }
        const minutes = Math.floor(countdown / 60);
        const seconds = countdown % 60;
        countdownElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }, 1000);
}

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
                
                // Nếu còn ghế và đang chọn nhóm 2, tự động chọn tiếp
                if (remainingSeats > 0 && selectedAdjacentCount === 2 && totalPeople >= 4) {
                    // Tự động chọn nhóm tiếp theo nếu còn đủ
                    // Người dùng sẽ chọn tiếp
                }
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

function selectAdjacentSeats(startSeatElement, count) {
    // Tìm các ghế liền nhau từ ghế bắt đầu
    const row = startSeatElement.closest('.seat-row');
    if (!row) return [];
    
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
        
        // Kiểm tra ghế đã được chọn chưa
        if (seat.classList.contains('selected')) {
            continue;
        }
        
        seat.classList.add('selected');
        seatsToSelect.push({
            id: seatId,
            label: seatLabel,
            type: seatType,
            status: seatStatus
        });
    }
    
    return seatsToSelect;
}

function isAdjacentSeat(seat1, seat2) {
    // Kiểm tra xem 2 ghế có liền nhau không
    const row1 = seat1.closest('.seat-row');
    const row2 = seat2.closest('.seat-row');
    
    if (row1 !== row2) return false; // Khác hàng
    
    const allSeats = Array.from(row1.querySelectorAll('.seat'));
    const index1 = allSeats.indexOf(seat1);
    const index2 = allSeats.indexOf(seat2);
    
    return Math.abs(index1 - index2) === 1; // Liền kề
}

function updateSummary() {
    const summaryElement = document.getElementById('selectedSeatsSummary');
    const seatsListElement = document.getElementById('selectedSeatsList');
    const totalPriceElement = document.getElementById('totalPrice');
    const continueBtn = document.getElementById('continueBtn');
    
    if (!summaryElement || !seatsListElement || !totalPriceElement || !continueBtn) return;
    
    if (selectedSeats.length === 0) {
        summaryElement.style.display = 'none';
        continueBtn.disabled = true;
        return;
    }
    
    summaryElement.style.display = 'block';
    seatsListElement.innerHTML = selectedSeats.map(seat => 
        `<span class="selected-seat-badge">${seat.label}${seat.type === 'vip' ? ' (VIP)' : ''}</span>`
    ).join('');
    
    // Tính giá theo loại ghế
    const basePrice = 80000; // Giá ghế thường
    const vipPrice = 120000; // Giá ghế VIP
    
    let total = 0;
    selectedSeats.forEach(seat => {
        if (seat.type === 'vip') {
            total += vipPrice;
        } else {
            total += basePrice;
        }
    });
    
    totalPriceElement.textContent = total.toLocaleString('vi-VN') + ' đ';
    
    continueBtn.disabled = false;
}

function continueBooking() {
    if (selectedSeats.length === 0) {
        alert('Vui lòng chọn ít nhất một ghế!');
        return;
    }
    
    const seatIds = selectedSeats.map(s => s.id).join(',');
    const seatLabels = selectedSeats.map(s => s.label).join(',');
    
    window.location.href = `<?= BASE_URL ?>?act=payment&showtime_id=${currentShowtimeId}&seats=${seatIds}&seat_labels=${encodeURIComponent(seatLabels)}`;
}

let selectedGroups = []; // Mảng các nhóm ghế đã chọn
let remainingSeats = 0; // Số ghế còn lại cần chọn

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
            }
        });
        selectedSeats = [];
        selectedGroups = [];
        remainingSeats = totalPeople;
        updateSummary();
    }
    
    // Cập nhật các ô chọn ghế liền nhau
    const adjacentOptions = document.getElementById('adjacentOptions');
    if (!adjacentOptions) return;
    
    adjacentOptions.innerHTML = '';
    selectedAdjacentCount = 0;
    remainingSeats = totalPeople;
    
    if (totalPeople === 0) {
        // Không hiển thị gì nếu chưa chọn số lượng
        return;
    }
    
    // Logic chọn ghế liền nhau dựa trên số lượng người
    // Tối đa chỉ có 4 ghế liền nhau
    let availableOptions = [];
    
    if (totalPeople === 1) {
        // 1 người: tự động chọn ô 1
        availableOptions = [1];
        selectedAdjacentCount = 1;
    } else if (totalPeople === 2) {
        // 2 người: tự động chọn ô 2 (ghế đôi)
        availableOptions = [2];
        selectedAdjacentCount = 2;
    } else if (totalPeople === 3) {
        // 3 người: tự động chọn ô 3
        availableOptions = [3];
        selectedAdjacentCount = 3;
    } else if (totalPeople === 4) {
        // 4 người: có thể chọn 2 hoặc 4
        availableOptions = [2, 4];
    } else if (totalPeople === 5) {
        // 5 người: chỉ có thể chọn 2 hoặc 3 (tối đa 4)
        availableOptions = [2, 3];
    } else if (totalPeople === 6) {
        // 6 người: có thể chọn 2, 3, hoặc 4 (tối đa 4)
        availableOptions = [2, 3, 4];
    } else if (totalPeople === 7) {
        // 7 người: có thể chọn 2, 3, hoặc 4 (tối đa 4)
        availableOptions = [2, 3, 4];
    } else if (totalPeople === 8) {
        // 8 người: có thể chọn 2, 3, hoặc 4 (tối đa 4)
        availableOptions = [2, 3, 4];
    }
    
    // Render các ô chọn
    availableOptions.forEach(count => {
        const option = document.createElement('div');
        option.className = 'adjacent-option';
        option.setAttribute('data-count', count);
        
        // Tạo radio button
        const radio = document.createElement('div');
        radio.className = 'adjacent-option-radio';
        
        // Tạo các ô vuông đen biểu thị số lượng ghế
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
            // Xóa active của các option khác
            document.querySelectorAll('.adjacent-option').forEach(opt => {
                opt.classList.remove('active');
            });
            // Thêm active cho option được chọn
            this.classList.add('active');
            selectedAdjacentCount = count;
            remainingSeats = totalPeople;
            selectedGroups = [];
            
            // Xóa các ghế đã chọn trước đó
            selectedSeats.forEach(seat => {
                const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                if (seatEl) {
                    seatEl.classList.remove('selected');
                }
            });
            selectedSeats = [];
            updateSummary();
        };
        adjacentOptions.appendChild(option);
    });
}

function selectTicketType() {
    // Có thể mở modal chọn loại vé nếu cần
    alert('Chức năng chọn loại vé đang được phát triển');
}

function resetSelection() {
    // Reset tất cả
    document.getElementById('adultQuantity').value = '0';
    document.getElementById('studentQuantity').value = '0';
    adultCount = 0;
    studentCount = 0;
    selectedAdjacentCount = 0;
    
    // Xóa các ghế đã chọn
    selectedSeats.forEach(seat => {
        const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
        if (seatEl) {
            seatEl.classList.remove('selected');
        }
    });
    selectedSeats = [];
    
    // Reset UI
    updateTicketSelection();
    updateSummary();
}

function goBackToShowtimes() {
    // Hiển thị lại phần chọn ngày và giờ chiếu
    const datesWrap = document.getElementById('datesWrap');
    if (datesWrap) {
        datesWrap.style.display = 'block';
    }
    
    // Ẩn sơ đồ ghế
    const container = document.getElementById('seatSelectionContainer');
    if (container) {
        container.style.display = 'none';
    }
    
    // Xóa active state của các time pill
    document.querySelectorAll('.time-pill').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Scroll lên phần chọn ngày/giờ
    if (datesWrap) {
        datesWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Reset selected seats
    selectedSeats = [];
    
    // Clear countdown
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
}
</script>
