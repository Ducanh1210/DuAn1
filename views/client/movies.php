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
    const MAX_COLUMNS = 12;
    const ALLOWED_SINGLE_COLUMNS = [1, 3, 4, 6, 7, 9, 10, 12];
    let currentShowtimeId = null;
    let countdownInterval = null;
    let countdown = 900;
    let adultPrice = 70000; // Giá vé người lớn ghế thường (sẽ được cập nhật từ API)
    let studentPrice = 60000; // Giá vé sinh viên ghế thường (sẽ được cập nhật từ API)
    let adultVipPrice = 80000; // Giá vé người lớn ghế VIP (sẽ được cập nhật từ API)
    let studentVipPrice = 70000; // Giá vé sinh viên ghế VIP (sẽ được cập nhật từ API)
    let lastAdultCount = 0;
    let lastStudentCount = 0;

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
        container.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

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
        lastAdultCount = 0;
        lastStudentCount = 0;
        remainingSeats = 0;

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
        const {
            showtime,
            room,
            seatsByRow,
            bookedSeats
        } = data;

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
        const cinemaName = room && room.cinema_name ? room.cinema_name : '';

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
                <div class="room-title">${roomDisplay}</div>
                <div class="room-subtitle">${cinemaName}</div>
                <div class="screen">MÀN HÌNH</div>
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
                         data-seat-row="${rowLabel.toUpperCase()}"
                         data-seat-column="${seatNumber}"
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
                         data-seat-row="${rowLabel.toUpperCase()}"
                         data-seat-column="${seatNumber}"
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
            
            <div class="price-info-box" id="priceInfoBox">
                <h3 class="price-info-title">Bảng giá vé</h3>
                <div class="price-grid">
                    <div class="price-card">
                        <div class="price-label">Người lớn - Thường</div>
                        <div class="price-value adult-normal" id="priceAdultNormal">-</div>
                    </div>
                    <div class="price-card">
                        <div class="price-label">Người lớn - VIP</div>
                        <div class="price-value adult-vip" id="priceAdultVip">-</div>
                    </div>
                    <div class="price-card">
                        <div class="price-label">Sinh viên - Thường</div>
                        <div class="price-value student-normal" id="priceStudentNormal">-</div>
                    </div>
                    <div class="price-card">
                        <div class="price-label">Sinh viên - VIP</div>
                        <div class="price-value student-vip" id="priceStudentVip">-</div>
                    </div>
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

        hideSeatsOver12();
        updateDisabledColumns();
        // Khởi tạo phần chọn ghế liền nhau
        updateTicketSelection();

        // Cập nhật hiển thị giá trong bảng giá (sau khi DOM đã render)
        setTimeout(() => {
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

        if (selectedAdjacentCount === 0) {
            alert('Vui lòng chọn số lượng ghế liền nhau!');
            return;
        }

        remainingSeats = totalPeople - selectedSeats.length;

        const seatId = seatElement.getAttribute('data-seat-id');
        const seatType = seatElement.getAttribute('data-seat-type');

        if (seatElement.classList.contains('selected')) {
            // Bỏ chọn ghế - không hiện thông báo, chỉ bỏ chọn
            removeSeatFromGroup(seatId);
            // Cập nhật lại options sau khi bỏ chọn, nhưng giữ lại selectedAdjacentCount nếu có thể
            updateAdjacentOptionsAfterSelection();
            updateSummary();
            return; // Dừng lại, không cần làm gì thêm
        } else {
            // Kiểm tra selectedAdjacentCount trước khi chọn - chỉ kiểm tra khi chưa chọn đủ
            const totalPeople = adultCount + studentCount;
            if (selectedAdjacentCount === 0 && selectedSeats.length < totalPeople) {
                alert('Vui lòng chọn số lượng ghế liền nhau!');
                return;
            }

            if (selectedSeats.length >= totalPeople) {
                alert(`Bạn chỉ có thể chọn tối đa ${totalPeople} ghế!`);
                return;
            }

            remainingSeats = totalPeople - selectedSeats.length;

            // Xác định số ghế sẽ chọn: lấy min giữa số ghế còn lại và số ghế liền nhau đã chọn
            let seatsToSelect = Math.min(remainingSeats, selectedAdjacentCount);

            // Kiểm tra trường hợp đặc biệt: sót lại 1 ghế và tất cả ghế cùng 1 hàng
            const row = seatElement.closest('.seat-row');
            const rowLabel = row ? (row.getAttribute('data-row-label') || '').toUpperCase() : '';
            const allSeatsInSameRow = selectedSeats.length > 0 && selectedSeats.every(seat => seat.row === rowLabel);
            const allowLastSingleSeat = remainingSeats === 1 && allSeatsInSameRow;

            const groupSeats = selectAdjacentSeatsSmart(seatElement, seatsToSelect, allowLastSingleSeat);
            if (groupSeats.length > 0) {
                // Kiểm tra không có khoảng trống 1 ô trước khi thêm vào
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

                selectedGroups.push({
                    count: seatsToSelect,
                    seats: groupSeats
                });
                selectedSeats = selectedSeats.concat(groupSeats);
                remainingSeats = totalPeople - selectedSeats.length;

                // Cập nhật lại options sau khi chọn nhưng giữ lại selectedAdjacentCount
                updateAdjacentOptionsAfterSelection();
            }
        }

        updateSummary();
        updateDisabledColumns();
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
                updateDisabledColumns();
                break;
            }
        }
    }

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

            if (blockStart < 1 || blockEnd > MAX_COLUMNS) {
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

        resetAllSelections();
        adultCount = 0;
        studentCount = 0;
        lastAdultCount = 0;
        lastStudentCount = 0;
        selectedAdjacentCount = 0;
        remainingSeats = 0;

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

        if (totalPeople === 0) {
            remainingSeats = 0;
            adjacentOptions.innerHTML = '';
            selectedAdjacentCount = 0;
            updateDisabledColumns();
            return;
        }

        remainingSeats = totalPeople - selectedSeats.length;

        // Chỉ reset và render lại options nếu số lượng người thay đổi
        if (adultCount !== prevAdult || studentCount !== prevStudent) {
            adjacentOptions.innerHTML = '';
            selectedAdjacentCount = 0;
        } else {
            // Nếu số lượng người không thay đổi và đã có selectedAdjacentCount, giữ lại
            // Chỉ cập nhật lại disabled columns
            if (selectedAdjacentCount > 0) {
                updateDisabledColumns();
                return;
            }
        }

        let availableOptions = [];

        // Luôn hiển thị đầy đủ options dựa trên tổng số người, tối đa đến 4 ghế liền nhau
        // KHÔNG BAO GIỜ hiển thị option > 4
        // Option 1 chỉ xuất hiện khi số lượng = 1, không xuất hiện khi số lượng >= 4
        if (totalPeople === 1) {
            availableOptions = [1];
        } else if (totalPeople === 2) {
            availableOptions = [2];
        } else if (totalPeople === 3) {
            availableOptions = [3];
        } else if (totalPeople === 4) {
            availableOptions = [2, 4];
        } else if (totalPeople === 5) {
            // 5 người: chỉ hiển thị 2, 3 (không có 1, 4)
            availableOptions = [2, 3];
        } else if (totalPeople >= 6 && totalPeople <= 8) {
            availableOptions = [2, 3, 4];
        }

        const radioBaseStyle = 'width: 24px; height: 24px; border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 50%; background: transparent; position: relative; flex-shrink: 0; transition: all 0.2s;';
        const activateOption = (optionEl, isActive) => {
            const radio = optionEl.querySelector('.adjacent-option-radio');
            if (!radio) return;
            if (isActive) {
                radio.style.cssText = `${radioBaseStyle}border-color: #ff8c00; background: #ff8c00;`;
                radio.innerHTML = '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 10px; height: 10px; border-radius: 50%; background: #fff;"></div>';
                optionEl.classList.add('active');
            } else {
                radio.style.cssText = radioBaseStyle;
                radio.innerHTML = '';
                optionEl.classList.remove('active');
            }
        };

        availableOptions.forEach(count => {
            const option = document.createElement('div');
            option.className = 'adjacent-option';
            option.setAttribute('data-count', count);
            option.style.cssText = 'display: flex; align-items: center; gap: 4px; cursor: pointer; transition: all 0.2s;';

            const radio = document.createElement('div');
            radio.className = 'adjacent-option-radio';
            radio.style.cssText = radioBaseStyle;

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

            // Giữ lại selectedAdjacentCount nếu nó vẫn hợp lệ
            if (count === selectedAdjacentCount && count <= remainingSeats) {
                activateOption(option, true);
            } else if (totalPeople <= 3 && count === totalPeople && selectedAdjacentCount === 0) {
                // Chỉ tự động chọn khi chưa có lựa chọn nào
                activateOption(option, true);
                selectedAdjacentCount = count;
            }

            option.onclick = function() {
                document.querySelectorAll('.adjacent-option').forEach(opt => activateOption(opt, false));
                activateOption(this, true);
                selectedAdjacentCount = count;
                remainingSeats = totalPeople - selectedSeats.length;
                updateDisabledColumns();
                updateSummary();
            };

            adjacentOptions.appendChild(option);
        });

        updateDisabledColumns();
        setTimeout(updateDisabledColumns, 100);
    }

    function updateAdjacentOptionsAfterSelection() {
        // Cập nhật lại remainingSeats và disabled columns sau khi chọn ghế
        // KHÔNG reset selectedAdjacentCount để giữ lại lựa chọn của người dùng
        const totalPeople = adultCount + studentCount;
        remainingSeats = totalPeople - selectedSeats.length;

        // Cập nhật lại disabled columns
        updateDisabledColumns();

        // Cập nhật lại summary
        updateSummary();
    }

    function updateDisabledColumns() {
        const totalPeople = adultCount + studentCount;
        const seats = document.querySelectorAll('#seatsGrid .seat');
        seats.forEach(seat => {
            const col = parseInt(seat.getAttribute('data-seat-column')) || 0;
            if (totalPeople === 1 && selectedAdjacentCount === 1) {
                if (col > 0 && !ALLOWED_SINGLE_COLUMNS.includes(col) &&
                    !seat.classList.contains('booked') &&
                    !seat.classList.contains('maintenance') &&
                    !seat.classList.contains('selected')) {
                    seat.classList.add('disabled-column');
                } else {
                    seat.classList.remove('disabled-column');
                }
            } else {
                seat.classList.remove('disabled-column');
            }
        });
    }

    function hideSeatsOver12() {
        const seats = document.querySelectorAll('#seatsGrid .seat[data-seat-column]');
        seats.forEach(seat => {
            const col = parseInt(seat.getAttribute('data-seat-column')) || 0;
            if (col > MAX_COLUMNS) {
                seat.style.display = 'none';
                seat.style.visibility = 'hidden';
            } else {
                seat.style.display = '';
                seat.style.visibility = '';
            }
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