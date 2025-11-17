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
                <div class="dates-wrap" aria-label="Chọn ngày và suất chiếu">
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
}

.screen {
    background: linear-gradient(to bottom, #ff8c00, #ff6b00);
    height: 60px;
    border-radius: 50px 50px 0 0;
    margin-bottom: 10px;
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
}

.room-title {
    font-size: 24px;
    font-weight: bold;
    margin-top: 15px;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.seats-grid {
    max-width: 1200px;
    margin: 20px auto;
}

.seat-row {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 3px;
    gap: 0px;
}

.seat-block {
    display: flex;
    gap: 0px;
    align-items: center;
}

.row-label {
    width: 25px;
    text-align: center;
    font-weight: bold;
    color: #fff;
    font-size: 14px;
}

.seat {
    width: 28px;
    height: 28px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 9px;
    font-weight: bold;
    transition: all 0.2s;
    border: 1px solid transparent;
    margin: 0;
}

/* Ghế thường - có thể chọn */
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
    background: #dc3545;
    color: #fff;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Ghế VIP */
.seat.vip {
    background: #9c27b0;
    color: #fff;
    border-color: #7b1fa2;
    font-weight: bold;
}

.seat.vip:hover {
    background: #ab47bc;
    transform: scale(1.05);
}

.seat.vip.selected {
    background: #ff8c00;
    color: #fff;
    border-color: #ff6b00;
}

/* Ghế bảo trì */
.seat.maintenance {
    background: #6c757d;
    color: transparent !important; /* Ẩn số ghế, chỉ hiển thị X */
    border-color: #5a6268;
    cursor: not-allowed;
    opacity: 0.7;
    position: relative;
}

.seat.maintenance::before {
    content: 'X';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 20px;
    color: #dc3545;
    font-weight: bold;
    z-index: 1;
    line-height: 1;
}

.seat-gap {
    width: 16px;
    margin: 0;
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
    width: 25px;
    height: 25px;
    border-radius: 5px;
}

.selected-seats-summary {
    background: #1a1a1a;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
    border-top: 2px solid #ff8c00;
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
    background: #ff8c00;
    color: #fff;
    padding: 6px 12px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 14px;
}

.total-price {
    font-size: 20px;
    font-weight: bold;
    color: #ff8c00;
}

.continue-btn {
    background: #ff8c00;
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
    background: #ff6b00;
    transform: translateY(-2px);
}

.continue-btn:disabled {
    background: #666;
    cursor: not-allowed;
    transform: none;
}
</style>

<script>
let selectedSeats = [];
let currentShowtimeId = null;
let countdownInterval = null;
let countdown = 900; // 15 phút

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
    
    // Lấy số phòng
    let roomNumber = '';
    if (room.room_code) {
        const matches = room.room_code.match(/\d+/);
        roomNumber = matches ? matches[0] : '';
    }
    if (!roomNumber && room.name) {
        const matches = room.name.match(/\d+/);
        roomNumber = matches ? matches[0] : '';
    }
    
    let html = `
        <div class="seat-selection-wrapper">
            <div class="seat-selection-header">
                <div class="showtime-info">
                    Giờ chiếu: <strong>${showtimeTime}</strong>
                </div>
                <div class="countdown-timer">
                    Thời gian chọn ghế: <span id="countdown">15:00</span>
                </div>
            </div>
            
            <div class="screen-container">
                <div class="screen"></div>
                <div class="room-title">Phòng chiếu số ${roomNumber || room.name || ''}</div>
            </div>
            
            <div class="seats-grid">
    `;
    
    // Sắp xếp hàng từ A đến Z
    const sortedRows = Object.keys(seatsByRow).sort();
    
    sortedRows.forEach(rowLabel => {
        const rowSeats = seatsByRow[rowLabel];
        // Sắp xếp ghế theo số từ lớn đến nhỏ (từ phải sang trái như ảnh)
        rowSeats.sort((a, b) => (b.seat_number || 0) - (a.seat_number || 0));
        
        html += `
            <div class="seat-row">
                <div class="row-label">${rowLabel}</div>
        `;
        
        // Nhóm ghế thành các khối dựa trên số ghế
        // Khối 1: ghế 17-16, Khối 2: ghế 15-8, Khối 3: ghế 7-1
        const blocks = {
            block1: [], // 17-16
            block2: [], // 15-8
            block3: []  // 7-1
        };
        
        rowSeats.forEach(seat => {
            const seatNumber = seat.seat_number || 0;
            if (seatNumber >= 17) {
                blocks.block1.push(seat);
            } else if (seatNumber >= 8) {
                blocks.block2.push(seat);
            } else {
                blocks.block3.push(seat);
            }
        });
        
        // Hiển thị từng khối - chụm lại thành 1 khối
        [blocks.block1, blocks.block2, blocks.block3].forEach((block, blockIndex) => {
            if (block.length === 0) return;
            
            // Bắt đầu một khối ghế
            html += '<div class="seat-block">';
            
            block.forEach((seat, index) => {
                const seatNumber = seat.seat_number || 0;
                const seatLabel = (seat.row_label || '') + seatNumber;
                const seatKey = seatLabel;
                const isBooked = bookedSeats.includes(seatKey);
                const seatType = (seat.seat_type || 'normal').toLowerCase();
                const seatStatus = (seat.status || 'available').toLowerCase();
                
                // Xác định loại ghế và trạng thái
                let seatClass = 'available';
                let onClick = `onclick="toggleSeat(this)"`;
                let title = '';
                
                // Kiểm tra trạng thái bảo trì trước
                if (seatStatus === 'maintenance' || seatStatus === 'maintain') {
                    seatClass = 'maintenance';
                    onClick = '';
                    title = 'title="Ghế đang bảo trì"';
                }
                // Kiểm tra ghế đã đặt
                else if (isBooked) {
                    seatClass = 'booked';
                    onClick = '';
                    title = 'title="Ghế đã được đặt"';
                }
                // Kiểm tra loại ghế
                else if (seatType === 'vip') {
                    seatClass = 'vip';
                    title = 'title="Ghế VIP"';
                }
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
                        ${seatLabel}
                    </div>
                `;
            });
            
            // Đóng khối ghế
            html += '</div>';
            
            // Thêm khoảng trống giữa các khối (trừ khối cuối)
            if (blockIndex < 2) {
                const hasNextBlock = (blockIndex === 0 && (blocks.block2.length > 0 || blocks.block3.length > 0)) ||
                                    (blockIndex === 1 && blocks.block3.length > 0);
                if (hasNextBlock) {
                    html += '<div class="seat-gap"></div>';
                }
            }
        });
        
        html += `
            </div>
        `;
    });
    
    html += `
            </div>
            
            <div class="seat-legend">
                <div class="legend-item">
                    <div class="legend-seat available" style="background: #4a4a4a;"></div>
                    <span>Ghế thường</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat selected" style="background: #ff8c00;"></div>
                    <span>Ghế đã chọn</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat booked" style="background: #dc3545; opacity: 0.8;"></div>
                    <span>Ghế đã đặt</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat vip" style="background: #9c27b0;"></div>
                    <span>Ghế VIP</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat maintenance" style="background: #6c757d; opacity: 0.7; position: relative;">
                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #dc3545; font-weight: bold; font-size: 14px;">X</span>
                    </div>
                    <span>Ghế bảo trì</span>
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
    
    const seatId = seatElement.getAttribute('data-seat-id');
    const seatLabel = seatElement.getAttribute('data-seat-label');
    const seatType = seatElement.getAttribute('data-seat-type');
    const seatStatus = seatElement.getAttribute('data-seat-status');
    
    if (seatElement.classList.contains('selected')) {
        seatElement.classList.remove('selected');
        selectedSeats = selectedSeats.filter(s => s.id !== seatId);
    } else {
        seatElement.classList.add('selected');
        selectedSeats.push({
            id: seatId,
            label: seatLabel,
            type: seatType,
            status: seatStatus
        });
    }
    
    updateSummary();
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
</script>
