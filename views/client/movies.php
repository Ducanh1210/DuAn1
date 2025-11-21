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
    padding: 30px;
    border-radius: 15px;
    margin-top: 30px;
}

.seat-selection-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: #1a1a1a;
    border-radius: 10px;
    margin-bottom: 30px;
}

.showtime-info {
    font-size: 20px;
    font-weight: 600;
    color: #fff;
}

.countdown-timer {
    background: #dc3545;
    border: 2px solid #dc3545;
    border-radius: 10px;
    padding: 15px 30px;
    font-size: 18px;
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
    height: 80px;
    border-radius: 50px 50px 0 0;
    margin-bottom: 20px;
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
    font-size: 24px;
    letter-spacing: 3px;
}

.room-title {
    font-size: 28px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 30px;
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
    margin-bottom: 15px;
    gap: 10px;
}

.seat-block {
    display: flex;
    gap: 4px;
    align-items: center;
    flex-wrap: nowrap;
}

.row-label {
    width: 40px;
    text-align: center;
    font-weight: bold;
    color: #fff;
    font-size: 20px;
}

.seat {
    width: 55px;
    height: 55px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: all 0.2s;
    border: 2px solid transparent;
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
    background: #ff8c00;
    color: #fff;
    border-color: #ff6b00;
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
    background: #ff8c00;
    color: #fff;
    border-color: #ff6b00;
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
    gap: 12px;
    font-size: 16px;
}

.legend-seat {
    width: 40px;
    height: 40px;
    border-radius: 8px;
}

.selected-seats-summary {
    background: #1a1a1a;
    padding: 25px;
    border-radius: 10px;
    margin-top: 30px;
    border-top: 3px solid #ff8c00;
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
    font-size: 28px;
    font-weight: bold;
    color: #ff8c00;
}

.continue-btn {
    background: #ff8c00;
    color: #fff;
    border: none;
    padding: 15px 50px;
    border-radius: 10px;
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

.adjacent-option.disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.max-seats-info {
    color: rgba(255, 255, 255, 0.6);
    font-size: 13px;
    margin-top: 10px;
}

/* Keyboard navigation */
.seat-row.hidden {
    display: none !important;
}

.seat-row.filtered-highlight {
    background: rgba(255, 140, 0, 0.1);
    padding: 5px;
    border-radius: 5px;
    border: 2px solid #ff8c00;
}

/* Time pill active state */
.time-pill.active {
    background: #ff8c00 !important;
    color: #fff !important;
    border-color: #ff6b00 !important;
}

</style>

<script>
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

// Biến toàn cục cho chọn ghế
let selectedSeats = [];
let selectedGroups = [];
let remainingSeats = 0;
let adultCount = 0;
let studentCount = 0;
let selectedAdjacentCount = 0;
let currentShowtimeId = null;
let countdownInterval = null;
let countdown = 900;
let adultPrice = 70000; // Giá vé người lớn ghế thường (sẽ được cập nhật từ API)
let studentPrice = 60000; // Giá vé sinh viên ghế thường (sẽ được cập nhật từ API)
let adultVipPrice = 80000; // Giá vé người lớn ghế VIP (sẽ được cập nhật từ API)
let studentVipPrice = 70000; // Giá vé sinh viên ghế VIP (sẽ được cập nhật từ API)

function showSeatSelection(button) {
    const showtimeId = button.getAttribute('data-showtime-id');
    const showtimeTime = button.getAttribute('data-showtime-time');
    
    // Đánh dấu button đang active
    document.querySelectorAll('.time-pill').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');
    
    // Hiển thị container chọn ghế
    const container = document.getElementById('seatSelectionContainer');
    container.style.display = 'block';
    
    // Scroll đến phần chọn ghế
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // Load dữ liệu ghế
    loadSeatData(showtimeId, showtimeTime);
}

function loadSeatData(showtimeId, showtimeTime) {
    currentShowtimeId = showtimeId;
    selectedSeats = [];
    selectedGroups = [];
    adultCount = 0;
    studentCount = 0;
    selectedAdjacentCount = 0;
    
    // Reset countdown
    countdown = 900;
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    
    fetch(`<?= BASE_URL ?>?act=api-seats&showtime_id=${showtimeId}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || 'Có lỗi xảy ra khi tải dữ liệu ghế');
                return;
            }
            
            // Cập nhật giá từ ticket_prices
            if (data.prices) {
                adultPrice = parseFloat(data.prices.adult_normal) || 70000;
                studentPrice = parseFloat(data.prices.student_normal) || 60000;
                adultVipPrice = parseFloat(data.prices.adult_vip) || 80000;
                studentVipPrice = parseFloat(data.prices.student_vip) || 70000;
                
                // Debug: Log giá và format để kiểm tra
                console.log('Format showtime:', data.format);
                console.log('Giá vé:', {
                    'Người lớn thường': adultPrice,
                    'Sinh viên thường': studentPrice,
                    'Người lớn VIP': adultVipPrice,
                    'Sinh viên VIP': studentVipPrice
                });
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
    const { showtime, room, seatsByRow, bookedSeats } = data;
    
    // Lấy thông tin phòng
    let roomDisplay = 'Phòng chiếu';
    if (room) {
        if (room.room_code) {
            const roomNumber = room.room_code.match(/\d+/);
            if (roomNumber) {
                roomDisplay = `Phòng chiếu số ${roomNumber[0]}`;
            } else {
                roomDisplay = `Phòng ${room.room_code}`;
            }
        } else if (room.name) {
            const matches = room.name.match(/\d+/);
            if (matches) {
                roomDisplay = `Phòng chiếu số ${matches[0]}`;
            } else {
                roomDisplay = room.name;
            }
        }
    }
    
    let html = `
        <div class="seat-selection-wrapper">
            <div class="seat-selection-header">
                <div class="showtime-info" style="color: #fff;">
                    Giờ chiếu: <strong>${showtimeTime}</strong>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button onclick="goBackToShowtimes()" class="back-button" style="background: #4a4a4a; color: #fff; border: 2px solid #666; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; transition: all 0.2s;">
                        ← Quay lại
                    </button>
                    <div class="countdown-timer" style="background: #dc3545; border: 2px solid #dc3545; border-radius: 10px; padding: 15px 30px; font-size: 18px; font-weight: bold; color: #fff;">
                        Thời gian chọn ghế: <span id="countdown">15:00</span>
                    </div>
                </div>
            </div>

            <!-- Phần chọn số lượng người -->
            <div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
                <div style="background: rgba(255, 255, 255, 0.05); padding: 25px; border-radius: 10px; margin-bottom: 30px; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <h3 style="color: #fff; margin-bottom: 25px; font-size: 28px; font-weight: bold;">Chọn ghế</h3>
                    
                    <div style="display: flex; gap: 40px; margin-bottom: 30px; flex-wrap: wrap;">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <label style="color: #fff; font-weight: 600; font-size: 18px;">Người lớn:</label>
                            <select id="adultQuantity" onchange="validateAndUpdateQuantity(event)" style="padding: 15px 20px; border-radius: 8px; border: 2px solid rgba(255, 255, 255, 0.3); background: #fff; color: #000; font-size: 18px; cursor: pointer; min-width: 150px; font-weight: 500;">
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
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <label style="color: #fff; font-weight: 600; font-size: 18px;">Sinh viên:</label>
                            <select id="studentQuantity" onchange="validateAndUpdateQuantity(event)" style="padding: 15px 20px; border-radius: 8px; border: 2px solid rgba(255, 255, 255, 0.3); background: #fff; color: #000; font-size: 18px; cursor: pointer; min-width: 150px; font-weight: 500;">
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
                    
                    <div style="margin-bottom: 30px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <label style="color: #fff; font-weight: 600; font-size: 18px;">Chọn ghế liền nhau</label>
                            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: inline-flex; align-items: center; justify-content: center; font-size: 14px; cursor: help;" title="Chọn số lượng ghế liền nhau bạn muốn">ℹ️</span>
                        </div>
                        <div id="adjacentOptions" style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
                            <!-- Sẽ được render động -->
                        </div>
                        <div style="color: rgba(255, 255, 255, 0.6); font-size: 13px; margin-top: 10px;">
                            Có thể chọn tối đa 8 người. (Max:8)
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="screen-container" style="text-align: center; margin: 30px auto; max-width: 1200px; padding: 0 20px;">
                <div class="screen" style="background: linear-gradient(to bottom, #ff8c00, #ff6b00); height: 80px; border-radius: 50px 50px 0 0; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3); color: white; font-weight: bold; font-size: 24px; letter-spacing: 3px;">MÀN HÌNH</div>
                <div class="room-title" style="font-size: 28px; font-weight: bold; margin-top: 20px; margin-bottom: 30px; color: #fff; text-align: center;">${roomDisplay}</div>
            </div>
            
            <div class="seats-grid" id="seatsGrid" style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
    `;
    
    // Sắp xếp hàng từ A đến Z
    const sortedRows = Object.keys(seatsByRow).sort();
    
    sortedRows.forEach(rowLabel => {
        const rowSeats = seatsByRow[rowLabel];
        
        html += `
            <div class="seat-row" data-row-label="${rowLabel.toUpperCase()}" style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px; gap: 10px;">
                <div class="row-label" style="width: 40px; text-align: center; font-weight: bold; color: #fff; font-size: 20px;">${rowLabel}</div>
        `;
        
        // Sắp xếp ghế theo số
        const sortedSeats = [...rowSeats].sort((a, b) => (a.seat_number || 0) - (b.seat_number || 0));
        
        let prevSeatNumber = 0;
        sortedSeats.forEach(seat => {
            const seatNumber = seat.seat_number || 0;
            const seatLabel = (seat.row_label || rowLabel) + seatNumber;
            const seatKey = seatLabel;
            const isBooked = bookedSeats.includes(seatKey);
            const seatType = (seat.seat_type || 'normal').toLowerCase();
            const seatStatus = (seat.status || 'available').toLowerCase();
            
            // Bỏ qua ghế disabled và couple
            if (['disabled', 'couple'].includes(seatType)) {
                return;
            }
            
            // Thêm khoảng trống nếu cần
            if (prevSeatNumber > 0 && (seatNumber - prevSeatNumber) > 1) {
                if ((prevSeatNumber % 4 == 0)) {
                    html += '<div class="seat-gap" style="width: 20px;"></div>';
                }
            }
            
            // Xác định class CSS cho ghế
            let seatClass = 'available';
            let onClick = `onclick="toggleSeat(this)"`;
            let title = '';
            
            if (seatStatus === 'maintenance') {
                seatClass = 'maintenance';
                onClick = '';
                title = 'title="Ghế đang bảo trì"';
            } else if (isBooked) {
                seatClass = 'booked';
                onClick = '';
                title = 'title="Ghế đã được đặt"';
            } else if (seatType === 'vip') {
                seatClass = 'vip';
                title = 'title="Ghế VIP"';
            }
            
            html += `
                <div class="seat ${seatClass}" 
                     data-seat-id="${seat.id}"
                     data-seat-label="${seatLabel}"
                     data-seat-type="${seatType}"
                     data-seat-status="${seatStatus}"
                     ${onClick}
                     ${title}
                     style="width: 55px; height: 55px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; font-weight: bold; transition: all 0.2s; border: 2px solid transparent;">
                    ${seatNumber}
                </div>
            `;
            
            prevSeatNumber = seatNumber;
        });
        
        html += `
            </div>
        `;
    });
    
    html += `
            </div>
            
            <div class="seat-legend" style="display: flex; justify-content: center; gap: 40px; margin: 40px 0; flex-wrap: wrap;">
                <div class="legend-item" style="display: flex; align-items: center; gap: 12px;">
                    <div class="legend-seat" style="width: 40px; height: 40px; border-radius: 8px; background: #4a4a4a;"></div>
                    <span style="color: #fff; font-size: 16px; font-weight: 500;">Thường</span>
                </div>
                <div class="legend-item" style="display: flex; align-items: center; gap: 12px;">
                    <div class="legend-seat" style="width: 40px; height: 40px; border-radius: 8px; background: #ffc107;"></div>
                    <span style="color: #fff; font-size: 16px; font-weight: 500;">VIP</span>
                </div>
                <div class="legend-item" style="display: flex; align-items: center; gap: 12px;">
                    <div class="legend-seat" style="width: 40px; height: 40px; border-radius: 8px; background: #ff8c00;"></div>
                    <span style="color: #fff; font-size: 16px; font-weight: 500;">Ghế bạn chọn</span>
                </div>
                <div class="legend-item" style="display: flex; align-items: center; gap: 12px;">
                    <div class="legend-seat" style="width: 40px; height: 40px; border-radius: 8px; background: #dc3545;"></div>
                    <span style="color: #fff; font-size: 16px; font-weight: 500;">Đã đặt</span>
                </div>
                <div class="legend-item" style="display: flex; align-items: center; gap: 12px;">
                    <div class="legend-seat" style="width: 40px; height: 40px; border-radius: 8px; background: #ffc107; position: relative;">
                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 18px; font-weight: bold; color: #000;">✕</span>
                    </div>
                    <span style="color: #fff; font-size: 16px; font-weight: 500;">Bảo trì</span>
                </div>
            </div>
            
            <div class="selected-seats-summary" id="selectedSeatsSummary" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #1a1a1a; padding: 20px; border-top: 2px solid #ff8c00; z-index: 1000;">
                <div class="summary-content" style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <div style="margin-bottom: 15px; font-weight: bold; color: #fff; font-size: 18px;">Ghế đã chọn:</div>
                        <div class="selected-seats-list" id="selectedSeatsList" style="display: flex; gap: 12px; flex-wrap: wrap;"></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="margin-bottom: 15px; color: #fff; font-size: 18px; font-weight: 500;">Tổng tiền:</div>
                        <div class="total-price" id="totalPrice" style="font-size: 32px; font-weight: bold; color: #ff8c00;">0 đ</div>
                    </div>
                    <button class="continue-btn" id="continueBtn" onclick="continueBooking()" disabled style="background: #ff8c00; color: #fff; border: none; padding: 15px 50px; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; transition: all 0.3s;">
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
    if (seatElement.classList.contains('booked') || 
        seatElement.classList.contains('maintenance')) {
        return;
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
    
    if (seatElement.classList.contains('selected')) {
        removeSeatFromGroup(seatId);
    } else {
        if (selectedSeats.length >= totalPeople) {
            alert(`Bạn chỉ có thể chọn tối đa ${totalPeople} ghế!`);
            return;
        }
        
        remainingSeats = totalPeople - selectedSeats.length;
        
        if (remainingSeats >= selectedAdjacentCount) {
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
    for (let i = selectedGroups.length - 1; i >= 0; i--) {
        const group = selectedGroups[i];
        const seatIndex = group.seats.findIndex(s => s.id === seatId);
        if (seatIndex !== -1) {
            group.seats.forEach(seat => {
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
            selectedSeats = selectedSeats.filter(s => !group.seats.some(gs => gs.id === s.id));
            selectedGroups.splice(i, 1);
            remainingSeats = (adultCount + studentCount) - selectedSeats.length;
            break;
        }
    }
}

function selectAdjacentSeats(startSeatElement, count) {
    const row = startSeatElement.closest('.seat-row');
    if (!row) return [];
    
    const allSeats = Array.from(row.querySelectorAll('.seat:not(.booked):not(.maintenance):not(.selected)'));
    const startIndex = allSeats.indexOf(startSeatElement);
    
    if (startIndex === -1) return [];
    
    if (startIndex + count > allSeats.length) {
        alert(`Không đủ ${count} ghế liền nhau từ vị trí này!`);
        return [];
    }
    
    const seatsToSelect = [];
    for (let i = 0; i < count; i++) {
        const seat = allSeats[startIndex + i];
        const seatId = seat.getAttribute('data-seat-id');
        const seatLabel = seat.getAttribute('data-seat-label');
        const seatType = seat.getAttribute('data-seat-type');
        
        if (seat.classList.contains('selected')) {
            continue;
        }
        
        seat.classList.add('selected');
        seat.classList.remove('vip', 'available');
        seatsToSelect.push({
            id: seatId,
            label: seatLabel,
            type: seatType
        });
    }
    
    return seatsToSelect;
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
    
    // Hiển thị ghế với phân loại người lớn/sinh viên
    let seatLabels = [];
    selectedSeats.forEach((seat, index) => {
        let label = seat.label;
        if (seat.type === 'vip') {
            label += ' (VIP)';
        }
        // Phân bổ: số ghế đầu = người lớn, số ghế sau = sinh viên
        if (index < adultCount) {
            label += ' - NL';
        } else {
            label += ' - SV';
        }
        seatLabels.push(`<span style="background: #ff8c00; color: #fff; padding: 12px 20px; border-radius: 8px; font-weight: bold; font-size: 16px;">${label}</span>`);
    });
    seatsListElement.innerHTML = seatLabels.join('');
    
    // Tính tổng tiền: phân biệt giá người lớn và sinh viên, ghế thường và VIP
    let total = 0;
    selectedSeats.forEach((seat, index) => {
        // Xác định loại khách hàng: số ghế đầu = người lớn, số ghế sau = sinh viên
        const isAdult = index < adultCount;
        
        // Lấy giá theo loại khách hàng và loại ghế
        if (seat.type === 'vip') {
            total += isAdult ? adultVipPrice : studentVipPrice;
        } else {
            total += isAdult ? adultPrice : studentPrice;
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
    
    // Truyền thêm thông tin số lượng người lớn và sinh viên
    window.location.href = `<?= BASE_URL ?>?act=payment&showtime_id=${currentShowtimeId}&seats=${seatIds}&seat_labels=${encodeURIComponent(seatLabels)}&adult_count=${adultCount}&student_count=${studentCount}`;
}

function goBackToShowtimes() {
    const container = document.getElementById('seatSelectionContainer');
    if (container) {
        container.style.display = 'none';
    }
    
    document.querySelectorAll('.time-pill').forEach(btn => {
        btn.classList.remove('active');
    });
    
    selectedSeats = [];
    selectedGroups = [];
    
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
}

function validateAndUpdateQuantity(event) {
    const adultSelect = document.getElementById('adultQuantity');
    const studentSelect = document.getElementById('studentQuantity');
    
    if (!adultSelect || !studentSelect) return;
    
    let adultValue = parseInt(adultSelect.value) || 0;
    let studentValue = parseInt(studentSelect.value) || 0;
    let total = adultValue + studentValue;
    
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
    
    if (!adultSelect || !studentSelect) return;
    
    adultCount = parseInt(adultSelect.value) || 0;
    studentCount = parseInt(studentSelect.value) || 0;
    const totalPeople = adultCount + studentCount;
    
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
        updateSummary();
    }
    
    const adjacentOptions = document.getElementById('adjacentOptions');
    if (!adjacentOptions) return;
    
    adjacentOptions.innerHTML = '';
    selectedAdjacentCount = 0;
    remainingSeats = totalPeople;
    
    if (totalPeople === 0) {
        return;
    }
    
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
    
    availableOptions.forEach(count => {
        const option = document.createElement('div');
        option.className = 'adjacent-option';
        option.setAttribute('data-count', count);
        option.style.cssText = 'display: flex; align-items: center; gap: 4px; cursor: pointer; transition: all 0.2s;';
        
        const radio = document.createElement('div');
        radio.className = 'adjacent-option-radio';
        radio.style.cssText = 'width: 24px; height: 24px; border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 50%; background: transparent; position: relative; flex-shrink: 0; transition: all 0.2s;';
        
        const seatsContainer = document.createElement('div');
        seatsContainer.className = 'adjacent-option-seats';
        seatsContainer.style.cssText = 'display: flex; gap: 4px; align-items: center;';
        for (let i = 0; i < count; i++) {
            const seatBox = document.createElement('div');
            seatBox.className = 'adjacent-seat-box';
            seatBox.style.cssText = 'width: 16px; height: 16px; background: #fff; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 3px; flex-shrink: 0;';
            seatsContainer.appendChild(seatBox);
        }
        
        option.appendChild(radio);
        option.appendChild(seatsContainer);
        
        if (totalPeople <= 3 && count === totalPeople) {
            option.classList.add('active');
            selectedAdjacentCount = count;
            radio.style.cssText += 'border-color: #ff8c00; background: #ff8c00;';
            radio.innerHTML = '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 10px; height: 10px; border-radius: 50%; background: #fff;"></div>';
        }
        
        option.onclick = function() {
            document.querySelectorAll('.adjacent-option').forEach(opt => {
                opt.classList.remove('active');
                const optRadio = opt.querySelector('.adjacent-option-radio');
                optRadio.style.cssText = 'width: 24px; height: 24px; border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 50%; background: transparent; position: relative; flex-shrink: 0; transition: all 0.2s;';
                optRadio.innerHTML = '';
            });
            this.classList.add('active');
            const thisRadio = this.querySelector('.adjacent-option-radio');
            thisRadio.style.cssText += 'border-color: #ff8c00; background: #ff8c00;';
            thisRadio.innerHTML = '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 10px; height: 10px; border-radius: 50%; background: #fff;"></div>';
            selectedAdjacentCount = count;
            remainingSeats = totalPeople;
            selectedGroups = [];
            
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
}

// Keyboard navigation để lọc hàng ghế
let currentFilter = null;

function filterSeatRows(filterKey) {
    const seatRows = document.querySelectorAll('.seat-row');
    
    if (!filterKey) {
        currentFilter = null;
        seatRows.forEach(row => {
            row.classList.remove('hidden', 'filtered-highlight');
        });
        return;
    }
    
    currentFilter = filterKey;
    let foundRow = false;
    seatRows.forEach(row => {
        const rowLabel = row.getAttribute('data-row-label');
        if (rowLabel === filterKey) {
            row.classList.remove('hidden');
            row.classList.add('filtered-highlight');
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            foundRow = true;
        } else {
            row.classList.add('hidden');
            row.classList.remove('filtered-highlight');
        }
    });
    
    if (!foundRow) {
        currentFilter = null;
        seatRows.forEach(row => {
            row.classList.remove('hidden', 'filtered-highlight');
        });
    }
}

document.addEventListener('keydown', function(e) {
    // Chỉ xử lý keyboard navigation khi phần chọn ghế đang hiển thị
    const seatContainer = document.getElementById('seatSelectionContainer');
    if (!seatContainer || seatContainer.style.display === 'none') {
        return;
    }
    
    // Kiểm tra xem có đang focus vào input/textarea không
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
        return;
    }
    
    const key = e.key.toUpperCase();
    
    // Chỉ xử lý khi phần chọn ghế đang hiển thị và có ghế
    const seatsGrid = document.getElementById('seatsGrid');
    if (!seatsGrid) {
        return;
    }
    
    if (key.length === 1 && key >= 'A' && key <= 'Z') {
        e.preventDefault();
        
        if (currentFilter === key) {
            filterSeatRows(null);
        } else {
            filterSeatRows(key);
        }
    } else if (e.key === 'Escape') {
        e.preventDefault();
        filterSeatRows(null);
    }
});
</script>
