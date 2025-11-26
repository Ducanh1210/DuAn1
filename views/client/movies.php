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
let selectedSide = null; // Lưu dãy đã chọn: 'left' (1-6) hoặc 'right' (7-12)
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
                <div class="showtime-info">
                    Giờ chiếu: <strong>${showtimeTime}</strong>
                </div>
                <div class="header-actions">
                    <button onclick="goBackToShowtimes()" class="back-button">
                        ← Quay lại
                    </button>
                    <div class="countdown-timer">
                        Thời gian chọn ghế: <span id="countdown">15:00</span>
                    </div>
                </div>
            </div>
            
            <!-- Phần chọn số lượng người -->
            <div class="ticket-selection-panel">
                <div class="ticket-panel-content">
                    <h3 class="ticket-panel-title">Chọn ghế</h3>
                    
                    <div class="quantity-section">
                        <div class="quantity-wrapper">
                            <label class="quantity-label">Người lớn:</label>
                            <select id="adultQuantity" onchange="validateAndUpdateQuantity(event)" class="quantity-select">
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
                        <div class="quantity-wrapper">
                            <label class="quantity-label">Sinh viên:</label>
                            <select id="studentQuantity" onchange="validateAndUpdateQuantity(event)" class="quantity-select">
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
                    
                    <div class="adjacent-section">
                        <div class="adjacent-header">
                            <label class="adjacent-header-label">Chọn ghế liền nhau</label>
                            <span class="info-icon" title="Chọn số lượng ghế liền nhau bạn muốn">ℹ️</span>
                        </div>
                        <div id="adjacentOptions" class="adjacent-options-container">
                            <!-- Sẽ được render động -->
                        </div>
                        <div class="max-seats-note">
                            Có thể chọn tối đa 8 người. (Max:8)
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="screen-container">
                <div class="screen">MÀN HÌNH</div>
                <div class="room-title">${roomDisplay}</div>
            </div>
            
            <div class="seats-grid" id="seatsGrid">
    `;
    
    // Sắp xếp hàng từ A đến Z
    const sortedRows = Object.keys(seatsByRow).sort();
    
    sortedRows.forEach(rowLabel => {
        const rowSeats = seatsByRow[rowLabel];
        
        html += `
            <div class="seat-row" data-row-label="${rowLabel.toUpperCase()}">
                <div class="row-label">${rowLabel}</div>
                <div class="seat-row-content">
                    <!-- Bên trái: ghế 1-6 -->
                    <div class="seat-side seat-side-left">
        `;
        
        // Sắp xếp ghế theo số
        const sortedSeats = [...rowSeats].sort((a, b) => (a.seat_number || 0) - (b.seat_number || 0));
        
        // Tạo mảng ghế bên trái (1-6) và bên phải (7-12)
        const leftSeats = {};
        const rightSeats = {};
        
        sortedSeats.forEach(seat => {
            const seatNumber = seat.seat_number || 0;
            const seatType = (seat.seat_type || 'normal').toLowerCase();
            
            // Bỏ qua ghế disabled và couple
            if (['disabled', 'couple'].includes(seatType)) {
                return;
            }
            
            if (seatNumber >= 1 && seatNumber <= 6) {
                leftSeats[seatNumber] = seat;
            } else if (seatNumber >= 7 && seatNumber <= 12) {
                rightSeats[seatNumber] = seat;
            }
        });
        
        // Hiển thị ghế bên trái (1-6)
        for (let i = 1; i <= 6; i++) {
            const seat = leftSeats[i];
            
            if (seat) {
                const seatNumber = seat.seat_number || 0;
                const seatLabel = (seat.row_label || rowLabel) + seatNumber;
                const seatKey = seatLabel;
                const isBooked = bookedSeats.includes(seatKey);
                const seatType = (seat.seat_type || 'normal').toLowerCase();
                const seatStatus = (seat.status || 'available').toLowerCase();
                
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
                         ${title}>
                        ${seatNumber}
                    </div>
                `;
            } else {
                // Ghế không tồn tại, hiển thị ô trống
                html += '<div class="seat seat-empty"></div>';
            }
        }
        
        html += `
                    </div>
                    
                    <!-- Khoảng trống giữa (aisle) -->
                    <div class="seat-aisle" style="width: 40px; flex-shrink: 0;"></div>
                    
                    <!-- Bên phải: ghế 7-12 -->
                    <div class="seat-side seat-side-right">
        `;
        
        // Hiển thị ghế bên phải (7-12)
        for (let i = 7; i <= 12; i++) {
            const seat = rightSeats[i];
            
            if (seat) {
                const seatNumber = seat.seat_number || 0;
                const seatLabel = (seat.row_label || rowLabel) + seatNumber;
                const seatKey = seatLabel;
                const isBooked = bookedSeats.includes(seatKey);
                const seatType = (seat.seat_type || 'normal').toLowerCase();
                const seatStatus = (seat.status || 'available').toLowerCase();
                
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
                         ${title}>
                        ${seatNumber}
                    </div>
                `;
            } else {
                // Ghế không tồn tại, hiển thị ô trống
                html += '<div class="seat seat-empty"></div>';
            }
        }
        
        html += `
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
            
            <div class="seat-legend">
                <div class="legend-item">
                    <div class="legend-seat normal"></div>
                    <span class="legend-text">Thường</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat vip"></div>
                    <span class="legend-text">VIP</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat selected"></div>
                    <span class="legend-text">Ghế bạn chọn</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat booked"></div>
                    <span class="legend-text">Đã đặt</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat maintenance"></div>
                    <span class="legend-text">Bảo trì</span>
                </div>
            </div>
            
           
            
            <div class="selected-seats-summary" id="selectedSeatsSummary" style="display: none;">
                <div class="summary-content">
                    <div>
                        <div class="summary-seats-label">Ghế đã chọn:</div>
                        <div class="selected-seats-list" id="selectedSeatsList"></div>
                    </div>
                    <div style="text-align: right;">
                        <div class="total-price-label">Tổng tiền:</div>
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
    
    // Cập nhật hiển thị ghế (sau khi DOM đã render)
    setTimeout(() => {
        updateSeatVisibility();
        updatePriceDisplay();
    }, 100);
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
    
    const seatId = seatElement.getAttribute('data-seat-id');
    const seatLabel = seatElement.getAttribute('data-seat-label');
    const seatType = seatElement.getAttribute('data-seat-type');
    
    // Nếu ghế đã được chọn, bỏ chọn
    if (seatElement.classList.contains('selected')) {
        removeSeatFromGroup(seatId);
        updateAdjacentOptions(); 
        updateSummary();
        updateSeatVisibility();
        return;
    }
    
    // Kiểm tra số lượng ghế đã chọn
    if (selectedSeats.length >= totalPeople) {
        alert(`Bạn chỉ có thể chọn tối đa ${totalPeople} ghế!`);
        return;
    }
    
    // Xác định dãy của ghế hiện tại
    const currentSeatLabel = seatElement.getAttribute('data-seat-label') || '';
    const currentSeatNumber = parseInt(currentSeatLabel.substring(1)) || 0;
    const currentSeatSide = (currentSeatNumber >= 1 && currentSeatNumber <= 6) ? 'left' : 'right';
    
    // Kiểm tra nếu đã chọn ghế ở dãy khác - KHÔNG CHO PHÉP
    if (selectedSeats.length > 0 && selectedSide !== null) {
        if (selectedSide !== currentSeatSide) {
            alert('Ngồi cạnh nhau là không được sang bên dãy 2. Mỗi người chỉ có thể chọn 1 trong 2 dãy!');
            return;
        }
    }
    
    remainingSeats = totalPeople - selectedSeats.length;
    
    // Nếu chưa chọn số lượng ghế liền nhau, mặc định chọn 1 ghế
    if (selectedAdjacentCount === 0) {
        selectedAdjacentCount = 1;
    }
    
    // Xác định số ghế sẽ chọn: lấy min giữa số ghế còn lại và số ghế liền nhau đã chọn
    let seatsToSelect = Math.min(remainingSeats, selectedAdjacentCount);
    
    // Nếu số ghế còn lại nhỏ hơn số ghế liền nhau đã chọn, chỉ cho phép chọn số ghế còn lại
    if (remainingSeats < selectedAdjacentCount) {
        seatsToSelect = remainingSeats;
    }
    
    // Nếu chọn 1 ghế, kiểm tra cách 1 ô trước khi chọn
    if (seatsToSelect === 1) {
        // Kiểm tra cách 1 ô với ghế đã đặt
        const adjacentError = checkAdjacentBookedSeats(seatElement);
        if (adjacentError) {
            alert(adjacentError);
            return;
        }
        
        // Kiểm tra cách 1 ô với ghế đã chọn
        if (selectedSeats.length > 0 && !canAddSeatsWithoutGap([{
            id: seatId,
            label: seatLabel,
            type: seatType
        }])) {
            alert('Không được phép có khoảng trống 1 ô giữa các ghế đã chọn. Vui lòng chọn lại!');
            return;
        }
        
        const seatEl = seatElement;
        seatEl.classList.add('selected');
        seatEl.classList.remove('vip', 'available');
        
        selectedSeats.push({
            id: seatId,
            label: seatLabel,
            type: seatType
        });
        
        selectedGroups.push({
            count: 1,
            seats: [{
                id: seatId,
                label: seatLabel,
                type: seatType
            }]
        });
        
        // Lưu dãy đã chọn
        if (selectedSide === null) {
            selectedSide = currentSeatSide;
        }
        
        remainingSeats = totalPeople - selectedSeats.length;
        updateAdjacentOptions();
        updateSummary();
        updateSeatVisibility();
        return;
    }
    
    // Nếu chọn nhiều ghế liền nhau, gọi hàm selectAdjacentSeats
    const groupSeats = selectAdjacentSeats(seatElement, seatsToSelect);
    if (groupSeats.length > 0) {
        // Kiểm tra cách 1 ô trước khi thêm vào
        if (selectedSeats.length > 0 && !canAddSeatsWithoutGap(groupSeats)) {
            // Bỏ chọn các ghế vừa chọn
            groupSeats.forEach(seat => {
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
            alert('Không được phép có khoảng trống 1 ô giữa các ghế đã chọn. Vui lòng chọn lại!');
            return;
        }
        
        // Lưu dãy đã chọn
        if (selectedSide === null) {
            selectedSide = currentSeatSide;
        }
        
        selectedGroups.push({
            count: seatsToSelect,
            seats: groupSeats
        });
        selectedSeats = selectedSeats.concat(groupSeats);
        remainingSeats = totalPeople - selectedSeats.length;
        
        // Cập nhật lại options sau khi chọn
        updateAdjacentOptions();
    }
    
    updateSummary();
    updateSeatVisibility();
}

// Kiểm tra ghế liền kề có bị đặt không (cách 1 ô)
function checkAdjacentBookedSeats(seatElement) {
    const row = seatElement.closest('.seat-row');
    if (!row) return null;
    
    // Lấy số ghế hiện tại từ label (ví dụ: "A3" -> số 3)
    const currentSeatLabel = seatElement.getAttribute('data-seat-label') || '';
    const currentRow = currentSeatLabel.charAt(0);
    const currentNumber = parseInt(currentSeatLabel.substring(1)) || 0;
    
    if (currentNumber === 0) return null;
    
    // Xác định dãy của ghế hiện tại (1-6 hay 7-12)
    const currentSeatSide = (currentNumber >= 1 && currentNumber <= 6) ? 'left' : 'right';
    
    // Lấy tất cả ghế trong hàng cùng row
    const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.seat-empty)'));
    
    // Tìm ghế bên trái (số nhỏ hơn 1) và ghế bên phải (số lớn hơn 1)
    for (let seat of allSeatsInRow) {
        const seatLabel = seat.getAttribute('data-seat-label') || '';
        const seatRow = seatLabel.charAt(0);
        const seatNumber = parseInt(seatLabel.substring(1)) || 0;
        
        // Chỉ kiểm tra ghế cùng hàng và cùng dãy
        if (seatRow !== currentRow) continue;
        
        const seatSide = (seatNumber >= 1 && seatNumber <= 6) ? 'left' : 'right';
        if (seatSide !== currentSeatSide) continue;
        
        // Kiểm tra ghế bên trái (số nhỏ hơn đúng 1) - cách 1 ô
        if (seatNumber === currentNumber - 1 && seat.classList.contains('booked')) {
            return `Không thể chọn ghế này vì ghế ${seatLabel} đã được đặt. Vui lòng chọn ghế khác (không được cách 1 ô với ghế đã đặt).`;
        }
        
        // Kiểm tra ghế bên phải (số lớn hơn đúng 1) - cách 1 ô
        if (seatNumber === currentNumber + 1 && seat.classList.contains('booked')) {
            return `Không thể chọn ghế này vì ghế ${seatLabel} đã được đặt. Vui lòng chọn ghế khác (không được cách 1 ô với ghế đã đặt).`;
        }
    }
    
    return null;
}

// Kiểm tra xem có thể thêm các ghế này mà không tạo khoảng trống 1 ô không
function canAddSeatsWithoutGap(newSeats) {
    if (selectedSeats.length === 0) {
        return true; // Nếu chưa có ghế nào, luôn cho phép
    }
    
    // Lấy tất cả ghế đã chọn (bao gồm cả ghế mới)
    const allSelectedSeats = [...selectedSeats, ...newSeats];
    
    // Nhóm theo hàng - chỉ kiểm tra trong cùng một hàng
    const seatsByRow = {};
    allSelectedSeats.forEach(seat => {
        const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
        if (!seatEl) return;
        
        const row = seatEl.closest('.seat-row');
        if (!row) return;
        
        const rowLabel = row.getAttribute('data-row-label');
        if (!rowLabel) return;
        
        if (!seatsByRow[rowLabel]) {
            seatsByRow[rowLabel] = [];
        }
        
        const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.gap)'));
        const seatIndex = allSeatsInRow.indexOf(seatEl);
        
        if (seatIndex !== -1) {
            seatsByRow[rowLabel].push({
                seat: seat,
                index: seatIndex
            });
        }
    });
    
    // Kiểm tra từng hàng - chỉ kiểm tra trong cùng một hàng
    for (const rowLabel in seatsByRow) {
        const seats = seatsByRow[rowLabel].sort((a, b) => a.index - b.index);
        
        // Chỉ kiểm tra nếu có ít nhất 2 ghế trong cùng một hàng
        if (seats.length < 2) {
            continue; // Nếu chỉ có 1 ghế trong hàng, không cần kiểm tra
        }
        
        // Kiểm tra xem có khoảng trống 1 ô giữa các nhóm ghế không
        for (let i = 0; i < seats.length - 1; i++) {
            const gap = seats[i + 1].index - seats[i].index;
            
            // gap = 1: 2 ghế liền nhau (OK)
            // gap = 2: có 1 ô trống giữa 2 ghế (KHÔNG CHO PHÉP nếu ô đó là available)
            // gap > 2: có 2+ ô trống (OK)
            if (gap === 2) {
                // Kiểm tra xem ô trống đó có phải là ghế available không
                const row = document.querySelector(`[data-row-label="${rowLabel}"]`);
                if (row) {
                    const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.gap)'));
                    const emptySeatIndex = seats[i].index + 1;
                    if (emptySeatIndex < allSeatsInRow.length) {
                        const emptySeat = allSeatsInRow[emptySeatIndex];
                        // Nếu ô trống là ghế available (không phải booked/maintenance/selected), không cho phép
                        if (emptySeat && 
                            !emptySeat.classList.contains('booked') && 
                            !emptySeat.classList.contains('maintenance') &&
                            !emptySeat.classList.contains('selected')) {
                            return false;
                        }
                    }
                }
            }
        }
    }
    
    return true;
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
                    // Reset về trạng thái ban đầu
                    if (seat.type === 'vip') {
                        seatEl.classList.add('vip');
                    } else {
                        seatEl.classList.add('available');
                    }
                    // Đảm bảo có thể click lại được
                    seatEl.style.pointerEvents = 'auto';
                    seatEl.style.opacity = '1';
                }
            });
            selectedSeats = selectedSeats.filter(s => !group.seats.some(gs => gs.id === s.id));
            selectedGroups.splice(i, 1);
            remainingSeats = (adultCount + studentCount) - selectedSeats.length;
            
            // Reset selectedSide nếu không còn ghế nào được chọn
            if (selectedSeats.length === 0) {
                selectedSide = null;
            }
            
            updateAdjacentOptions(); // Cập nhật lại options sau khi bỏ chọn
            updateSummary(); // Cập nhật summary
            updateSeatVisibility(); // Cập nhật hiển thị ghế
            break;
        }
    }
}

function selectAdjacentSeats(startSeatElement, count) {
    const row = startSeatElement.closest('.seat-row');
    if (!row) return [];
    
    // Lấy số ghế bắt đầu
    const startSeatLabel = startSeatElement.getAttribute('data-seat-label') || '';
    const startRow = startSeatLabel.charAt(0);
    const startNumber = parseInt(startSeatLabel.substring(1)) || 0;
    
    if (startNumber === 0) return [];
    
    // Xác định dãy của ghế bắt đầu (1-6 hay 7-12)
    const startSeatSide = (startNumber >= 1 && startNumber <= 6) ? 'left' : 'right';
    
    // Kiểm tra nếu đã chọn ghế ở dãy khác - KHÔNG CHO PHÉP
    if (selectedSeats.length > 0 && selectedSide !== null) {
        if (selectedSide !== startSeatSide) {
            alert('Ngồi cạnh nhau là không được sang bên dãy 2. Mỗi người chỉ có thể chọn 1 trong 2 dãy!');
            return [];
        }
    }
    
    // Lấy tất cả ghế trong hàng (bao gồm cả ghế bảo trì để kiểm tra)
    const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.seat-empty)'));
    const startIndex = allSeatsInRow.indexOf(startSeatElement);
    
    if (startIndex === -1) return [];
    
    // Kiểm tra xem có đủ ghế available liền nhau từ vị trí này không
    let availableCount = 0;
    let hasMaintenanceInBetween = false;
    let touchesMaintenance = false; // Kiểm tra xem có chạm đến ghế bảo trì không
    const seatsToSelect = [];
    const seatIndices = []; // Lưu index của các ghế đã chọn để kiểm tra khoảng cách
    
    // Duyệt từ ghế bắt đầu, tìm đủ số ghế available LIỀN NHAU (không nhảy cóc)
    for (let i = startIndex; i < allSeatsInRow.length && availableCount < count; i++) {
        const seat = allSeatsInRow[i];
        
        // Nếu ghế đã được chọn, DỪNG LẠI (không tiếp tục, không nhảy cóc)
        if (seat.classList.contains('selected')) {
            break; // Dừng lại, không chọn tiếp
        }
        
        // Nếu gặp ghế bảo trì hoặc booked
        if (seat.classList.contains('maintenance') || seat.classList.contains('booked')) {
            // Nếu số lượng = 2 và đã chọn 1 ghế, có thể bỏ qua ghế bảo trì (ghế 1, ghế bảo trì, ghế 3)
            if (count === 2 && availableCount === 1) {
                continue; // Bỏ qua ghế bảo trì, tiếp tục tìm ghế tiếp theo
            }
            // Nếu số lượng > 2 và đã chọn ít nhất 1 ghế, có ghế bảo trì ở giữa
            if (count > 2 && availableCount > 0) {
                hasMaintenanceInBetween = true;
                touchesMaintenance = true;
                break;
            }
            // Nếu chưa chọn ghế nào và gặp ghế bảo trì ngay từ đầu, không cho phép
            if (availableCount === 0) {
                break;
            }
            // Nếu đã chọn ghế và gặp ghế bảo trì, dừng lại (không cho phép tràn qua)
            touchesMaintenance = true;
            break;
        }
        
        // Kiểm tra xem ghế này có phải là ghế available không (không phải gap)
        if (!seat.classList.contains('available') && !seat.classList.contains('vip')) {
            // Nếu không phải available hoặc vip, có thể là gap hoặc loại khác, bỏ qua
            continue;
        }
        
        // Ghế available, thêm vào danh sách
        const seatId = seat.getAttribute('data-seat-id');
        const seatLabel = seat.getAttribute('data-seat-label');
        const seatType = seat.getAttribute('data-seat-type');
        
        seat.classList.add('selected');
        seat.classList.remove('vip', 'available');
        seatsToSelect.push({
            id: seatId,
            label: seatLabel,
            type: seatType
        });
        seatIndices.push(i);
        availableCount++;
    }
    
    // Kiểm tra xem có chạm đến hoặc vượt qua ghế bảo trì không
    // Kiểm tra toàn bộ phạm vi cần chọn: từ startIndex đến startIndex + count - 1
    // Nếu trong phạm vi này có ghế bảo trì → không cho phép (trừ trường hợp số lượng = 2 và ghế bảo trì ở giữa)
    if (!touchesMaintenance) {
        // Tính toán phạm vi cần chọn
        const endIndex = startIndex + count - 1;
        
        // Kiểm tra từng ghế trong phạm vi
        for (let i = startIndex; i <= endIndex && i < allSeatsInRow.length; i++) {
            const seat = allSeatsInRow[i];
            if (seat && (seat.classList.contains('maintenance') || seat.classList.contains('booked'))) {
                // Nếu số lượng = 2 và ghế bảo trì ở giữa (vị trí startIndex + 1), cho phép
                if (count === 2 && i === startIndex + 1) {
                    // Cho phép bỏ qua 1 ghế bảo trì ở giữa khi số lượng = 2
                    // Nhưng phải đảm bảo có đủ 2 ghế available (1 trước + 1 sau ghế bảo trì)
                    continue;
                }
                // Các trường hợp khác: chạm đến hoặc vượt qua ghế bảo trì → không cho phép
                // Đặc biệt: khi số lượng >= 4, không được phép chạm đến hoặc vượt qua ghế bảo trì
                touchesMaintenance = true;
                hasMaintenanceInBetween = true;
                break;
            }
        }
    }
    
    // Kiểm tra xem các ghế đã chọn có liền nhau không (không có ghế available ở giữa)
    if (availableCount > 0 && seatIndices.length > 1) {
        for (let j = 0; j < seatIndices.length - 1; j++) {
            const gap = seatIndices[j + 1] - seatIndices[j];
            // Nếu khoảng cách > 1, có nghĩa là có ghế ở giữa
            if (gap > 1) {
                // Kiểm tra xem ghế ở giữa có phải là ghế available không (không phải bảo trì/booked)
                for (let k = seatIndices[j] + 1; k < seatIndices[j + 1]; k++) {
                    const middleSeat = allSeatsInRow[k];
                    if (middleSeat) {
                        // Nếu ghế ở giữa là available hoặc vip (chưa được chọn), không cho phép nhảy cóc
                        if (middleSeat.classList.contains('available') || middleSeat.classList.contains('vip')) {
                            // Đây là nhảy cóc, không cho phép
                            hasMaintenanceInBetween = true; // Dùng biến này để báo lỗi
                            break;
                        }
                        // Nếu ghế ở giữa là bảo trì hoặc booked
                        if (middleSeat.classList.contains('maintenance') || middleSeat.classList.contains('booked')) {
                            // Nếu số lượng > 2, không cho phép tràn qua ghế bảo trì
                            if (count > 2) {
                                hasMaintenanceInBetween = true;
                                touchesMaintenance = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
    
    // Nếu không đủ số ghế hoặc có ghế bảo trì ở giữa hoặc nhảy cóc hoặc chạm đến ghế bảo trì
    if (availableCount < count || hasMaintenanceInBetween || touchesMaintenance) {
        // Bỏ chọn các ghế đã chọn
        seatsToSelect.forEach(seat => {
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
        
        if (touchesMaintenance || hasMaintenanceInBetween) {
            if (count > 2) {
                alert(`Không thể chọn ${count} ghế liền nhau vì chạm đến hoặc vượt qua ghế bảo trì. Vui lòng chọn lại!`);
            } else {
                alert(`Không thể chọn ${count} ghế liền nhau vì chạm đến ghế bảo trì. Vui lòng chọn lại!`);
            }
        } else {
            alert(`Không đủ ${count} ghế liền nhau từ vị trí này!`);
        }
        return [];
    }
    
    // Lưu dãy đã chọn nếu chưa có
    if (selectedSide === null && seatsToSelect.length > 0) {
        selectedSide = startSeatSide;
    }
    
    return seatsToSelect;
}

function updatePriceDisplay() {
    try {
        const priceAdultNormal = document.getElementById('priceAdultNormal');
        const priceAdultVip = document.getElementById('priceAdultVip');
        const priceStudentNormal = document.getElementById('priceStudentNormal');
        const priceStudentVip = document.getElementById('priceStudentVip');
        
        if (priceAdultNormal) priceAdultNormal.textContent = adultPrice.toLocaleString('vi-VN') + ' đ';
        if (priceAdultVip) priceAdultVip.textContent = adultVipPrice.toLocaleString('vi-VN') + ' đ';
        if (priceStudentNormal) priceStudentNormal.textContent = studentPrice.toLocaleString('vi-VN') + ' đ';
        if (priceStudentVip) priceStudentVip.textContent = studentVipPrice.toLocaleString('vi-VN') + ' đ';
    } catch (error) {
        console.error('Error updating price display:', error);
    }
}

function updateSummary() {
    const summaryElement = document.getElementById('selectedSeatsSummary');
    const seatsListElement = document.getElementById('selectedSeatsList');
    const totalPriceElement = document.getElementById('totalPrice');
    const continueBtn = document.getElementById('continueBtn');
    
    if (!summaryElement || !seatsListElement || !totalPriceElement || !continueBtn) return;
    
    const totalPeople = adultCount + studentCount;
    const remainingSeats = totalPeople - selectedSeats.length;
    
    if (selectedSeats.length === 0) {
        summaryElement.style.display = 'none';
        continueBtn.disabled = true;
        continueBtn.textContent = 'Tiếp tục';
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
        seatLabels.push(`<span class="seat-badge">${label}</span>`);
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
    
    // Kiểm tra đã chọn đủ chưa
    if (remainingSeats > 0) {
        continueBtn.disabled = true;
        continueBtn.textContent = `Còn thiếu ${remainingSeats} ghế`;
        continueBtn.style.opacity = '0.6';
        continueBtn.style.cursor = 'not-allowed';
    } else if (selectedSeats.length > totalPeople) {
        continueBtn.disabled = true;
        continueBtn.textContent = `Đã chọn quá ${selectedSeats.length - totalPeople} ghế`;
        continueBtn.style.opacity = '0.6';
        continueBtn.style.cursor = 'not-allowed';
    } else {
        continueBtn.disabled = false;
        continueBtn.textContent = 'Tiếp tục';
        continueBtn.style.opacity = '1';
        continueBtn.style.cursor = 'pointer';
    }
}

function continueBooking() {
    const totalPeople = adultCount + studentCount;
    
    if (totalPeople === 0) {
        alert('Vui lòng chọn số lượng người trước!');
        return;
    }
    
    if (selectedSeats.length === 0) {
        alert('Vui lòng chọn ít nhất một ghế!');
        return;
    }
    
    // Kiểm tra đã chọn đủ số lượng chưa
    if (selectedSeats.length < totalPeople) {
        const remaining = totalPeople - selectedSeats.length;
        alert(`Bạn cần chọn thêm ${remaining} ghế nữa để tiếp tục!`);
        return;
    }
    
    if (selectedSeats.length > totalPeople) {
        alert(`Bạn đã chọn quá số lượng! Vui lòng bỏ bớt ${selectedSeats.length - totalPeople} ghế.`);
        return;
    }
    
    // Kiểm tra không có khoảng trống 1 ô giữa các ghế đã chọn
    if (!validateSeatSpacing()) {
        alert('Không được phép có khoảng trống 1 ô giữa các ghế đã chọn. Vui lòng chọn lại!');
        return;
    }
    
    const seatIds = selectedSeats.map(s => s.id).join(',');
    const seatLabels = selectedSeats.map(s => s.label).join(',');
    
    // Truyền thêm thông tin số lượng người lớn và sinh viên
    window.location.href = `<?= BASE_URL ?>?act=payment&showtime_id=${currentShowtimeId}&seats=${seatIds}&seat_labels=${encodeURIComponent(seatLabels)}&adult_count=${adultCount}&student_count=${studentCount}`;
}

// Kiểm tra không cho phép có khoảng trống 1 ô giữa các ghế đã chọn
function validateSeatSpacing() {
    // Nhóm các ghế đã chọn theo hàng
    const seatsByRow = {};
    selectedSeats.forEach(seat => {
        const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
        if (!seatEl) return;
        
        const row = seatEl.closest('.seat-row');
        if (!row) return;
        
        const rowLabel = row.getAttribute('data-row-label');
        if (!rowLabel) return;
        
        if (!seatsByRow[rowLabel]) {
            seatsByRow[rowLabel] = [];
        }
        
        // Lấy số thứ tự của ghế trong hàng
        const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.gap)'));
        const seatIndex = allSeatsInRow.indexOf(seatEl);
        
        if (seatIndex !== -1) {
            seatsByRow[rowLabel].push({
                seat: seat,
                index: seatIndex
            });
        }
    });
    
    // Kiểm tra từng hàng
    for (const rowLabel in seatsByRow) {
        const seats = seatsByRow[rowLabel].sort((a, b) => a.index - b.index);
        
        for (let i = 0; i < seats.length - 1; i++) {
            const gap = seats[i + 1].index - seats[i].index;
            
            // gap = 1: 2 ghế liền nhau (OK)
            // gap = 2: có 1 ô trống giữa 2 ghế (KHÔNG CHO PHÉP)
            // gap > 2: có 2+ ô trống (OK)
            if (gap === 2) {
                // Kiểm tra xem ô trống đó có phải là ghế available không
                const row = document.querySelector(`[data-row-label="${rowLabel}"]`);
                if (row) {
                    const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.gap)'));
                    const emptySeatIndex = seats[i].index + 1;
                    if (emptySeatIndex < allSeatsInRow.length) {
                        const emptySeat = allSeatsInRow[emptySeatIndex];
                        // Nếu ô trống là ghế available (không phải booked/maintenance), không cho phép
                        if (emptySeat && !emptySeat.classList.contains('booked') && !emptySeat.classList.contains('maintenance')) {
                            return false;
                        }
                    }
                }
            }
        }
    }
    
    return true;
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
        selectedSide = null; // Reset dãy đã chọn
        updateSummary();
    }
    
    updateAdjacentOptions();
    updateSeatVisibility();
}

// Hàm cập nhật hiển thị ghế dựa trên số lượng đã chọn
function updateSeatVisibility() {
    const totalPeople = adultCount + studentCount;
    const allSeats = document.querySelectorAll('.seat:not(.seat-empty)');
    
    if (totalPeople === 0) {
        // Chưa chọn số lượng, ẩn tất cả ghế
        allSeats.forEach(seat => {
            seat.style.display = 'none';
        });
    } else if (totalPeople === 1) {
        // Chọn 1 người, chỉ hiển thị ghế available và vip (có thể chọn)
        // Ẩn ghế booked, maintenance
        allSeats.forEach(seat => {
            // Kiểm tra xem ghế có thể chọn không
            const isBooked = seat.classList.contains('booked');
            const isMaintenance = seat.classList.contains('maintenance');
            const isAvailable = seat.classList.contains('available');
            const isVip = seat.classList.contains('vip');
            const isSelected = seat.classList.contains('selected');
            
            // Hiển thị ghế nếu:
            // 1. Đã được chọn (selected) - để người dùng thấy ghế đã chọn
            // 2. Hoặc là available/vip và không phải booked/maintenance
            if (isSelected || ((isAvailable || isVip) && !isBooked && !isMaintenance)) {
                seat.style.display = 'flex';
            } else {
                // Ẩn ghế booked, maintenance, hoặc ghế không có class available/vip
                seat.style.display = 'none';
            }
        });
    } else {
        // Chọn nhiều người, hiển thị tất cả ghế
        allSeats.forEach(seat => {
            seat.style.display = 'flex';
        });
    }
}

// Cập nhật lại options dựa trên số ghế còn lại
function updateAdjacentOptions() {
    const adultSelect = document.getElementById('adultQuantity');
    const studentSelect = document.getElementById('studentQuantity');
    
    if (!adultSelect || !studentSelect) return;
    
    adultCount = parseInt(adultSelect.value) || 0;
    studentCount = parseInt(studentSelect.value) || 0;
    const totalPeople = adultCount + studentCount;
    
    const adjacentOptions = document.getElementById('adjacentOptions');
    if (!adjacentOptions) return;
    
    remainingSeats = totalPeople - selectedSeats.length;
    
    if (totalPeople === 0) {
        adjacentOptions.innerHTML = '';
        selectedAdjacentCount = 0;
        return;
    }
    
    // Nếu đã chọn đủ, không hiển thị options nữa nhưng vẫn giữ selectedAdjacentCount để có thể bỏ chọn
    if (remainingSeats === 0) {
        adjacentOptions.innerHTML = '<div style="color: rgba(255, 255, 255, 0.7); font-size: 14px;">Đã chọn đủ số lượng ghế</div>';
        // Giữ lại selectedAdjacentCount để có thể bỏ chọn và chọn lại
        // Không reset về 0
        return;
    }
    
    let availableOptions = [];
    
    // Luôn hiển thị option 1 (chọn từng ghế) - giống Lotte Cinema
    // Sau đó hiển thị các option khác dựa trên tổng số người, tối đa đến 4 ghế liền nhau
    if (totalPeople === 1) {
        availableOptions = [1];
    } else if (totalPeople === 2) {
        availableOptions = [1, 2]; // Luôn có option 1
    } else if (totalPeople === 3) {
        availableOptions = [1, 3]; // Luôn có option 1
    } else if (totalPeople === 4) {
        availableOptions = [1, 2, 4]; // Luôn có option 1
    } else if (totalPeople === 5) {
        availableOptions = [1, 2, 3]; // Luôn có option 1
    } else if (totalPeople === 6) {
        availableOptions = [1, 2, 3, 4]; // Luôn có option 1
    } else if (totalPeople === 7) {
        availableOptions = [1, 2, 3, 4]; // Luôn có option 1
    } else if (totalPeople === 8) {
        availableOptions = [1, 2, 3, 4]; // Luôn có option 1
    } else if (totalPeople >= 9) {
        availableOptions = [1, 2, 3, 4]; // Luôn có option 1
    }
    
    // Đảm bảo không có option nào > 4
    availableOptions = availableOptions.filter(opt => opt <= 4);
    
    // Lọc options: chỉ hiển thị options có thể tạo thành số ghế còn lại
    // QUAN TRỌNG: Khi chưa chọn ghế nào (remainingSeats === totalPeople), hiển thị đầy đủ availableOptions
    // Khi đã chọn một số ghế, chỉ hiển thị options phù hợp với số ghế còn lại
    // QUAN TRỌNG: Tối đa chỉ có 4 ghế liền nhau, không được hiển thị option > 4
    // QUAN TRỌNG: Option 1 chỉ xuất hiện khi số lượng = 1, không xuất hiện khi số lượng >= 4
    const displayOptions = [];
    
    if (remainingSeats > 0) {
        // Nếu chưa chọn ghế nào (remainingSeats === totalPeople), hiển thị đầy đủ availableOptions
        if (remainingSeats === totalPeople) {
            // Hiển thị tất cả availableOptions (đã được lọc sẵn theo totalPeople)
            displayOptions.push(...availableOptions);
        } else {
            // Đã chọn một số ghế, chỉ hiển thị options phù hợp với số ghế còn lại
            // QUAN TRỌNG: Chỉ hiển thị options mà số ghế còn lại CHIA HẾT cho option đó
            // Ví dụ: còn 4 ghế → chỉ hiển thị options 2, 4 (vì 4 chia hết cho 2 và 4, không chia hết cho 3)
            // Ví dụ: còn 3 ghế → chỉ hiển thị option 3 (vì 3 chỉ chia hết cho 3)
            // Ví dụ: còn 2 ghế → chỉ hiển thị option 2 (vì 2 chỉ chia hết cho 2)
            // Ví dụ: còn 1 ghế → chỉ hiển thị option 1 (nếu totalPeople = 1), không hiển thị nếu totalPeople >= 4
            
            // Lọc từ availableOptions, luôn hiển thị option 1 (chọn từng ghế)
            availableOptions.forEach(opt => {
                if (opt <= remainingSeats && opt <= 4) { // Đảm bảo opt <= 4 và opt <= số ghế còn lại
                    // Luôn thêm option 1 nếu có thể
                    if (opt === 1) {
                        displayOptions.push(opt);
                    } else {
                        // Với các option khác, chỉ thêm nếu số ghế còn lại >= option đó
                        displayOptions.push(opt);
                    }
                }
            });
            
            // Đảm bảo luôn có option 1 nếu còn ghế cần chọn
            if (remainingSeats > 0 && !displayOptions.includes(1)) {
                displayOptions.push(1);
            }
        }
        
        // Sắp xếp lại theo thứ tự tăng dần
        displayOptions.sort((a, b) => a - b);
        
        // Đảm bảo không có option nào > 4, nhưng luôn giữ option 1
        const finalDisplayOptions = displayOptions.filter(opt => opt <= 4);
        displayOptions.length = 0;
        displayOptions.push(...finalDisplayOptions);
        
        // Đảm bảo luôn có option 1
        if (remainingSeats > 0 && !displayOptions.includes(1)) {
            displayOptions.push(1);
        }
    }
    
    adjacentOptions.innerHTML = '';
    
    // Giữ lại selectedAdjacentCount hiện tại nếu nó vẫn hợp lệ
    let newSelectedCount = selectedAdjacentCount;
    if (displayOptions.length > 0) {
        // Nếu selectedAdjacentCount hiện tại <= số ghế còn lại và có trong displayOptions, giữ lại
        if (selectedAdjacentCount > 0 && selectedAdjacentCount <= remainingSeats && displayOptions.includes(selectedAdjacentCount)) {
            newSelectedCount = selectedAdjacentCount; // Giữ lại option đã chọn trước đó
        } else if (selectedAdjacentCount > 0 && selectedAdjacentCount <= remainingSeats) {
            // Nếu option đã chọn <= số ghế còn lại nhưng không có trong displayOptions, thêm vào
            if (!displayOptions.includes(selectedAdjacentCount)) {
                displayOptions.push(selectedAdjacentCount);
                displayOptions.sort((a, b) => a - b); // Sắp xếp lại
            }
            newSelectedCount = selectedAdjacentCount;
        } else {
            // Nếu không hợp lệ, chọn option lớn nhất <= số ghế còn lại
            newSelectedCount = Math.max(...displayOptions.filter(opt => opt <= remainingSeats));
        }
    } else {
        newSelectedCount = 0;
    }
    
    // Nếu chưa có ghế nào được chọn, mặc định chọn option 1 (cho phép chọn từng ghế)
    if (selectedSeats.length === 0) {
        if (displayOptions.includes(1)) {
            newSelectedCount = 1; // Mặc định cho phép chọn từng ghế
        } else if (displayOptions.length > 0) {
            newSelectedCount = displayOptions[0]; // Hoặc option đầu tiên
        }
    }
    
    selectedAdjacentCount = newSelectedCount;
    
    displayOptions.forEach(count => {
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
        
        // Đánh dấu option được chọn
        if (count === selectedAdjacentCount) {
            option.classList.add('active');
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
