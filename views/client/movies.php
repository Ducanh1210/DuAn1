<?php
// Lấy thông tin phim từ biến $movie (sẽ được truyền từ controller)
$movie = $movie ?? null;
$showtimes = $showtimes ?? [];
$dates = $dates ?? [];
$selectedDate = $selectedDate ?? date('Y-m-d');
?>

<!-- Định nghĩa hàm showSeatSelection TRƯỚC khi render HTML -->
<script>
    // Đảm bảo hàm showSeatSelection được định nghĩa trong global scope NGAY LẬP TỨC
    window.showSeatSelection = function(button) {
        // Kiểm tra xem button có tồn tại không
        if (!button) {
            console.error('Button không tồn tại');
            return;
        }

        const showtimeId = button.getAttribute('data-showtime-id');
        const showtimeTime = button.getAttribute('data-showtime-time');

        // Kiểm tra xem có showtimeId không
        if (!showtimeId) {
            console.error('Không có showtime ID');
            alert('Không tìm thấy thông tin suất chiếu');
            return;
        }

        // Đánh dấu button đang active
        document.querySelectorAll('.time-pill').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');

        // Đảm bảo button có thể click được
        button.style.pointerEvents = 'auto';
        button.style.cursor = 'pointer';

        // Hiển thị container chọn ghế
        const container = document.getElementById('seatSelectionContainer');
        if (container) {
            container.style.display = 'block';

            // Scroll đến phần chọn ghế
            container.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Gọi loadSeatData với cơ chế retry cho tới khi hàm sẵn sàng
        let retryCount = 0;
        const tryLoadSeats = () => {
            if (typeof window.loadSeatData === 'function') {
                window.loadSeatData(showtimeId, showtimeTime);
            } else if (retryCount < 15) { // retry ~3s (15 * 200ms)
                retryCount++;
                setTimeout(tryLoadSeats, 200);
            } else {
                console.error('loadSeatData function vẫn không tồn tại sau khi retry');
                alert('Không tải được dữ liệu ghế. Vui lòng tải lại trang.');
            }
        };
        tryLoadSeats();
    };
    console.log('showSeatSelection function defined:', typeof window.showSeatSelection === 'function');
</script>

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
                                <a href="<?= BASE_URL ?>?act=movies&id=<?= $movie['id'] ?? '' ?>&date=<?= $date['date'] ?><?= !empty($cinemaId) ? '&cinema=' . htmlspecialchars($cinemaId) : '' ?>"
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

<!-- Hàm showSeatSelection đã được định nghĩa ở đầu file -->
<script>
    // Hàm showSeatSelection đã được định nghĩa ở đầu file (dòng 12)
    // Các hàm và biến khác cho seat selection

    // Reset viewport khi trang load để tránh zoom
    function resetViewport() {
        // Reset zoom level
        if (document.body.style.zoom) {
            document.body.style.zoom = '';
        }
        // Reset transform scale nếu có
        if (document.body.style.transform) {
            document.body.style.transform = '';
        }
        // Reset document zoom
        if (document.documentElement.style.zoom) {
            document.documentElement.style.zoom = '';
        }
        // Reset viewport meta tag
        const viewport = document.querySelector('meta[name="viewport"]');
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
        }
        // Force reset browser zoom
        if (window.devicePixelRatio && window.devicePixelRatio !== 1) {
            // Nếu có zoom, reset về 1
            document.body.style.zoom = '1';
        }
    }

    // Gọi reset viewport ngay khi script chạy
    resetViewport();

    // Reset lại khi URL có parameter _reset_zoom (không reload lại trang)
    if (window.location.search.includes('_reset_zoom')) {
        // Reset viewport ngay lập tức
        resetViewport();

        // Xóa parameters khỏi URL sau khi reset (không reload)
        const url = new URL(window.location.href);
        url.searchParams.delete('_reset_zoom');
        url.searchParams.delete('_t');
        url.searchParams.delete('_r');
        url.searchParams.delete('_nocache');
        window.history.replaceState({}, '', url.toString());

        // Reset lại viewport một lần nữa
        setTimeout(resetViewport, 100);
    }

    // Xử lý xem trailer và đảm bảo time-pill có thể click được
    document.addEventListener('DOMContentLoaded', function() {
        // Reset viewport khi DOM load xong
        resetViewport();

        // Kiểm tra nếu có showtime_id trong URL (quay lại từ thanh toán)
        const urlParams = new URLSearchParams(window.location.search);
        const showtimeId = urlParams.get('showtime_id');
        if (showtimeId) {
            // Tìm button time-pill tương ứng và tự động click để mở phần chọn ghế
            // Chỉ chạy 1 lần, đợi đủ thời gian để DOM và functions đã load xong
            let autoOpenAttempted = false;
            const tryAutoOpen = () => {
                if (autoOpenAttempted) return;
                const timePill = document.querySelector(`.time-pill[data-showtime-id="${showtimeId}"]`);
                if (timePill && typeof window.showSeatSelection === 'function' && typeof window.loadSeatData === 'function') {
                    autoOpenAttempted = true;
                    console.log('Auto-opening seat selection for showtime:', showtimeId);
                    window.showSeatSelection(timePill);
                }
            };

            // Thử ngay, sau đó thử lại nếu chưa sẵn sàng
            tryAutoOpen();
            setTimeout(tryAutoOpen, 300);
            setTimeout(tryAutoOpen, 600);
        }

        const watchTrailerBtn = document.getElementById('watchTrailer');
        if (watchTrailerBtn) {
            watchTrailerBtn.addEventListener('click', function() {
                const trailerUrl = this.getAttribute('data-trailer');
                if (trailerUrl) {
                    window.open(trailerUrl, '_blank');
                }
            });
        }

        // Đảm bảo tất cả time-pill buttons có thể click được
        const timePills = document.querySelectorAll('.time-pill');
        console.log('Found time-pill buttons:', timePills.length);
        console.log('showSeatSelection function exists:', typeof window.showSeatSelection === 'function');

        timePills.forEach((pill, index) => {
            // Đảm bảo pointer-events và cursor được set đúng
            pill.style.pointerEvents = 'auto';
            pill.style.cursor = 'pointer';
            pill.style.zIndex = '10';
            pill.style.position = 'relative';

            // Lấy thông tin showtime
            const showtimeId = pill.getAttribute('data-showtime-id');
            const showtimeTime = pill.getAttribute('data-showtime-time');

            console.log(`Time-pill ${index}: showtimeId=${showtimeId}, showtimeTime=${showtimeTime}`);

            if (showtimeId) {
                // Xóa onclick attribute cũ
                pill.removeAttribute('onclick');

                // Thêm event listener mới (sử dụng capture phase để đảm bảo không bị block)
                pill.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Time-pill clicked:', showtimeId, showtimeTime);

                    if (typeof window.showSeatSelection === 'function') {
                        window.showSeatSelection(this);
                    } else {
                        console.error('showSeatSelection function not found');
                        alert('Có lỗi xảy ra. Vui lòng tải lại trang.');
                    }
                }, true); // Use capture phase

                // Thêm một event listener khác ở bubble phase để đảm bảo
                pill.addEventListener('click', function(e) {
                    console.log('Time-pill click (bubble phase):', showtimeId);
                }, false);
            }
        });
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

    // Hàm showSeatSelection đã được định nghĩa ở đầu script tag trong global scope (window.showSeatSelection)
    // Không cần định nghĩa lại ở đây

    // Định nghĩa loadSeatData trong global scope để có thể gọi từ showSeatSelection
    window.loadSeatData = function(showtimeId, showtimeTime) {
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
    };
    console.log('loadSeatData function defined:', typeof window.loadSeatData === 'function');;
    console.log('loadSeatData function defined:', typeof window.loadSeatData === 'function');

    function renderSeatSelection(data, showtimeTime) {
        const content = document.getElementById('seatSelectionContent');
        const {
            showtime,
            room,
            seatsByRow,
            bookedSeats
        } = data;

        // Chuẩn hóa dữ liệu ghế (fallback khi API không trả seatsByRow)
        let normalizedSeatsByRow = seatsByRow && typeof seatsByRow === 'object' ? seatsByRow : null;
        if (!normalizedSeatsByRow && Array.isArray(data.seats)) {
            normalizedSeatsByRow = {};
            data.seats.forEach(seat => {
                const rowLabel = (seat.row_label || seat.row || '').toUpperCase();
                if (!rowLabel) return;
                if (!normalizedSeatsByRow[rowLabel]) normalizedSeatsByRow[rowLabel] = [];
                normalizedSeatsByRow[rowLabel].push(seat);
            });
        }
        const normalizedBookedSeats = Array.isArray(bookedSeats) ?
            bookedSeats :
            (Array.isArray(data.booked_seats) ? data.booked_seats : []);

        if (!normalizedSeatsByRow || Object.keys(normalizedSeatsByRow).length === 0) {
            alert('Không tải được dữ liệu ghế. Vui lòng thử lại hoặc chọn suất chiếu khác.');
            return;
        }

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
        <style>
           /* Force reset zoom khi trang load */
           html, body {
               zoom: 1 !important;
               -webkit-text-size-adjust: 100% !important;
               -moz-text-size-adjust: 100% !important;
               -ms-text-size-adjust: 100% !important;
               text-size-adjust: 100% !important;
           }
           
           /* ==== CỘT BỊ DISABLE KHI CHỌN 1 GHẾ LẺ ==== */
.seat.disabled-column {
    background: #2a2a2a !important;
    color: transparent !important;
    opacity: 0.5 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
    position: relative !important;
    border-color: #333 !important;
}

.seat.disabled-column::after {
    content: '✕' !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    font-size: 18px !important;
    font-weight: bold !important;
    color: #999 !important;
    z-index: 2 !important;
    line-height: 1 !important;
}

           /* ==== HÀNG BỊ DISABLE KHI CHỌN 1 GHẾ LẺ ==== */
.seat-row.disabled-row {
    opacity: 0.4 !important;
    pointer-events: none !important;
}

.seat-row.disabled-row .seat {
    background: #2a2a2a !important;
    color: transparent !important;
    opacity: 0.5 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
    position: relative !important;
    border-color: #333 !important;
}

.seat-row.disabled-row .seat::after {
    content: '✕' !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    font-size: 18px !important;
    font-weight: bold !important;
    color: #999 !important;
    z-index: 2 !important;
    line-height: 1 !important;
}

.seat-row.disabled-row .row-label {
    color: #666 !important;
    opacity: 0.5 !important;
}

           /* ==== KHUNG CHỌN GHẾ ==== */
.ticket-selection-panel {
    max-width: 1200px;
    margin: 30px auto;
    padding: 24px 26px;
    border-radius: 18px;
    background: transparent;
    box-shadow: none;
    position: relative;
    color: #f5f5f5;
    overflow: hidden;
}

/* viền cam phía trên */
.ticket-selection-panel::before {
    content: "";
    position: absolute;
    top: 0;
    left: 18px;
    right: 18px;
    height: 3px;
    border-radius: 999px;
    background: linear-gradient(90deg, #ffb347, #ff7b00);
}

/* viền cam phía trái tiêu đề */
.ticket-panel-title {
    margin: 10px 0 22px;
    font-size: 22px;
    font-weight: 600;
    position: relative;
    padding-left: 24px;
}

.ticket-panel-title::before {
    content: "";
    position: absolute;
    left: 0;
    top: 3px;
    bottom: 3px;
    width: 5px;
    border-radius: 999px;
    background: linear-gradient(180deg, #ffb347, #ff7b00);
}

/* ==== HÀNG SỐ LƯỢNG ==== */
.quantity-section {
    display: flex;
    justify-content: flex-start;
    gap: 20px;
    margin-bottom: 24px;
}

.quantity-wrapper {
    flex: 1;
    max-width: 200px; /* Giới hạn độ rộng để ngắn lại */
}

.quantity-label {
    font-size: 15px;
    letter-spacing: 0.2px;
    display: block;
    margin-bottom: 6px;
    position: relative;
}

/* gạch ngang mỏng sau label */
.quantity-label::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: -6px;
    height: 1px;
    background: linear-gradient(90deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
}

/* select kiểu neumorphism */
.quantity-select {
    width: 100%;
    margin-top: 14px;
    padding: 10px 14px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.16);
    background: linear-gradient(145deg, #383838, #2a2a2a);
    box-shadow:
        4px 4px 10px rgba(0, 0, 0, 0.9),
        -3px -3px 8px rgba(90, 90, 90, 0.25);
    color: #f5f5f5;
    font-size: 15px;
    appearance: none;
    outline: none;
    position: relative;
    transition: all 0.3s ease;
    cursor: pointer;
}

/* mũi tên giả */
.quantity-select {
    background-image:
        linear-gradient(145deg, #383838, #2a2a2a),
        linear-gradient(135deg, transparent 50%, #f5f5f5 50%);
    background-repeat: no-repeat;
    background-position:
        0 0,
        calc(100% - 14px) center;
    background-size:
        100% 100%,
        8px 8px;
    padding-right: 32px;
}

.quantity-select:hover {
    background: linear-gradient(145deg, #4a4a4a, #3a3a3a);
    border-color: rgba(255, 159, 59, 0.6);
    box-shadow:
        4px 4px 12px rgba(0, 0, 0, 0.95),
        -3px -3px 10px rgba(90, 90, 90, 0.35),
        0 0 0 2px rgba(255, 159, 59, 0.3);
    transform: translateY(-1px);
    color: #ffffff;
}

/* Style cho option elements trong dropdown */
.quantity-select option {
    background: #2a2a2a;
    color: #f5f5f5;
    padding: 10px;
    border: none;
}

.quantity-select:focus {
    border-color: #ff9f3b;
    box-shadow:
        0 0 0 1px rgba(255, 159, 59, 0.5),
        4px 4px 12px rgba(0, 0, 0, 0.95);
}

.quantity-select:active {
    transform: translateY(0);
    box-shadow:
        2px 2px 8px rgba(0, 0, 0, 0.9),
        -2px -2px 6px rgba(90, 90, 90, 0.25);
}

/* ==== PHẦN GHẾ LIỀN NHAU ==== */
.adjacent-section {
    margin-top: 22px;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.adjacent-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    justify-content: space-between; /* Để max-seats-note ở bên phải */
}

.adjacent-header-label {
    font-size: 15px;
    font-weight: 500;
}

.info-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #2f4f74;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: default;
    box-shadow: 0 0 0 1px rgba(173, 216, 230, 0.4);
}

/* container nút số ghế liền nhau (nếu có) */
.adjacent-options-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
}

/* ghi chú max ghế - chuyển sang góc phải */
.max-seats-note {
    margin-top: 0;
    margin-left: auto;
    font-size: 11px;
    opacity: 0.75;
    display: flex;
    align-items: center;
    gap: 4px;
    text-align: right;
    white-space: nowrap;
}

.max-seats-note::before {
    content: "💡";
    font-size: 12px;
}

        </style>
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
                            <div class="max-seats-note">
                                Tối đa 8 người
                            </div>
                        </div>
                        <div id="adjacentOptions" class="adjacent-options-container">
                            <!-- Sẽ được render động -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="screen-container">
                <div class="room-subtitle">${cinemaName}</div>
                <div class="room-title">${roomDisplay}</div>
                <div class="screen">MÀN HÌNH</div>
            </div>
            
            <div class="seats-grid" id="seatsGrid">
    `;

        // Sắp xếp hàng từ A đến Z
        const sortedRows = Object.keys(normalizedSeatsByRow).sort();

        sortedRows.forEach(rowLabel => {
            const rowSeats = normalizedSeatsByRow[rowLabel];

            html += `
            <div class="seat-row" data-row-label="${rowLabel.toUpperCase()}">
                <div class="row-label">${rowLabel}</div>
        `;

            // Bản đồ ghế theo số cột để giữ thẳng hàng 12 cột
            const sortedSeats = [...rowSeats].sort((a, b) => (a.seat_number || 0) - (b.seat_number || 0));
            const seatMap = {};

            sortedSeats.forEach(seat => {
                const seatNumber = seat.seat_number || 0;
                const seatType = (seat.seat_type || 'normal').toLowerCase();
                if (seatNumber > MAX_COLUMNS) return;
                if (['disabled', 'couple'].includes(seatType)) return;
                seatMap[seatNumber] = seat;
            });

            for (let seatNumber = 1; seatNumber <= MAX_COLUMNS; seatNumber++) {
                if (seatNumber === 7) {
                    html += '<div class="seat-gap"></div>';
                }

                const seat = seatMap[seatNumber];

                if (!seat) {
                    html += '<div class="seat-empty"></div>';
                    continue;
                }

                const seatType = (seat.seat_type || 'normal').toLowerCase();
                const seatLabel = (seat.row_label || rowLabel) + seatNumber;
                const seatKey = seatLabel;
                const isBooked = normalizedBookedSeats.includes(seatKey);
                const seatStatus = (seat.status || 'available').toLowerCase();
                const isMaintenance = (seatStatus === 'maintenance');

                let seatClass = 'available';
                let onClick = `onclick="toggleSeat(this)"`;
                let title = '';

                if (isMaintenance) {
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
            }

            html += `
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

            // Khi chọn ghế đi đôi (2 người): tự động chọn ghế gần nhất
            const row = seatElement.closest('.seat-row');
            const rowLabel = row ? (row.getAttribute('data-row-label') || '').toUpperCase() : '';
            const allSeatsInSameRow = selectedSeats.length > 0 && selectedSeats.every(seat => seat.row === rowLabel);
            const allowLastSingleSeat = remainingSeats === 1 && allSeatsInSameRow;

            const isPairSelection = seatsToSelect >= 2 && selectedAdjacentCount === 2;
            let usedCoupleStrategy = false;
            let groupSeats = [];

            if (isPairSelection) {
                groupSeats = selectAdjacentSeatsForCouple(seatElement);
                usedCoupleStrategy = groupSeats.length > 0;
            }

            if (groupSeats.length === 0) {
                groupSeats = selectAdjacentSeatsSmart(seatElement, seatsToSelect, allowLastSingleSeat);
            }
            if (groupSeats.length > 0) {
                const isCoupleSelection =
                    usedCoupleStrategy &&
                    (
                        (totalPeople === 2 && selectedSeats.length === 0) ||
                        (totalPeople === 4 && remainingSeats === 2)
                    );

                // Chỉ kiểm tra gap khi không phải là chọn ghế đi đôi lần đầu
                if (!isCoupleSelection && selectedSeats.length > 0 && !canAddSeatsWithoutGap(groupSeats)) {
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

        // Khi số lượng >= 2: KHÔNG cho phép chọn ở 2 dãy khác nhau khi đang chọn liền nhau
        const totalPeople = adultCount + studentCount;
        if (totalPeople >= 2) {
            // Kiểm tra xem có đang trong quá trình chọn liền nhau không
            // Nếu selectedAdjacentCount > 0 và chưa chọn đủ nhóm hiện tại, không được nhảy sang hàng khác
            const remainingSeats = totalPeople - selectedSeats.length;

            // Kiểm tra xem nhóm hiện tại đã chọn đủ chưa
            // Nếu đã chọn đủ một nhóm (selectedAdjacentCount ghế), có thể chọn ở hàng khác
            let currentGroupComplete = false;
            if (selectedGroups.length > 0) {
                const lastGroup = selectedGroups[selectedGroups.length - 1];
                // Nếu nhóm cuối cùng đã chọn đủ số ghế theo selectedAdjacentCount
                if (lastGroup.seats.length >= selectedAdjacentCount) {
                    currentGroupComplete = true;
                }
            }

            // Nếu chưa có nhóm nào hoặc nhóm hiện tại chưa chọn đủ, kiểm tra xem có đang chọn liền nhau không
            const isSelectingAdjacent = selectedAdjacentCount > 0 && remainingSeats > 0 && !currentGroupComplete;

            if (isSelectingAdjacent) {
                // Kiểm tra tất cả ghế đã chọn và ghế mới phải cùng một hàng
                const allSeats = [...selectedSeats, ...newSeats];
                const rows = new Set();
                allSeats.forEach(seat => {
                    const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                    if (seatEl) {
                        const row = seatEl.closest('.seat-row');
                        if (row) {
                            const rowLabel = row.getAttribute('data-row-label');
                            if (rowLabel) {
                                rows.add(rowLabel);
                            }
                        }
                    }
                });

                // Nếu có ghế ở nhiều hơn 1 hàng, không cho phép
                if (rows.size > 1) {
                    return false;
                }
            }

            // Kiểm tra không được chọn ghế 6 nhảy sang dãy bên kia (block khác)
            const newSeatsCols = newSeats.map(seat => {
                const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                if (seatEl) {
                    return parseInt(seatEl.getAttribute('data-seat-column')) || 0;
                }
                return 0;
            }).filter(col => col > 0);

            // Kiểm tra xem có ghế nào ở cột 6 không
            if (newSeatsCols.includes(6)) {
                // Nếu có ghế ở cột 6, kiểm tra xem có ghế nào ở cột 7-12 không
                const hasRightBlock = newSeatsCols.some(col => col >= 7 && col <= 12);
                if (hasRightBlock) {
                    return false; // Không cho phép chọn ghế 6 và ghế ở dãy bên kia cùng lúc
                }
            }

            // Kiểm tra xem có ghế nào ở cột 7 không
            if (newSeatsCols.includes(7)) {
                // Nếu có ghế ở cột 7, kiểm tra xem có ghế nào ở cột 1-6 không
                const hasLeftBlock = newSeatsCols.some(col => col >= 1 && col <= 6);
                if (hasLeftBlock) {
                    return false; // Không cho phép chọn ghế 7 và ghế ở dãy bên kia cùng lúc
                }
            }

            // Kiểm tra gap trong cùng dãy (cho tất cả trường hợp)
            const allSeats = [...selectedSeats, ...newSeats];
            const rows = new Set();
            allSeats.forEach(seat => {
                const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                if (seatEl) {
                    const row = seatEl.closest('.seat-row');
                    if (row) {
                        const rowLabel = row.getAttribute('data-row-label');
                        if (rowLabel) {
                            rows.add(rowLabel);
                        }
                    }
                }
            });

            // Kiểm tra gap trong từng hàng
            for (const rowLabel of rows) {
                const row = document.querySelector(`[data-row-label="${rowLabel}"]`);
                if (row) {
                    const allSeatsInRow = Array.from(row.querySelectorAll('.seat:not(.gap)'));
                    const cols = allSeats
                        .filter(seat => {
                            const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                            if (!seatEl) return false;
                            return seatEl.closest('.seat-row') === row;
                        })
                        .map(seat => {
                            const seatEl = document.querySelector(`[data-seat-id="${seat.id}"]`);
                            return allSeatsInRow.indexOf(seatEl);
                        })
                        .filter(idx => idx !== -1)
                        .sort((a, b) => a - b);

                    // Kiểm tra gap trong cùng dãy
                    for (let i = 0; i < cols.length - 1; i++) {
                        const gap = cols[i + 1] - cols[i];
                        if (gap === 2) {
                            // Có gap 1 ô, kiểm tra xem ô đó có available không
                            const emptySeatIndex = cols[i] + 1;
                            if (emptySeatIndex < allSeatsInRow.length) {
                                const emptySeat = allSeatsInRow[emptySeatIndex];
                                if (emptySeat &&
                                    !emptySeat.classList.contains('booked') &&
                                    !emptySeat.classList.contains('maintenance') &&
                                    !emptySeat.classList.contains('selected')) {
                                    return false; // Không cho phép gap 1 ô trong cùng dãy
                                }
                            }
                        }
                    }
                }
            }
            return true;
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

    function selectAdjacentSeatsForCouple(startSeatElement) {
        const row = startSeatElement.closest('.seat-row');
        if (!row) return [];

        const rowLabel = (row.getAttribute('data-row-label') || '').toUpperCase();
        const startColumn = parseInt(startSeatElement.getAttribute('data-seat-column')) || 0;
        if (!startColumn) {
            return [];
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
            !seat.classList.contains('selected') &&
            !seat.classList.contains('disabled-column');

        if (!isSeatSelectable(seatMap[startColumn])) {
            return [];
        }

        const partnerColumn = (startColumn % 2 === 0) ? startColumn - 1 : startColumn + 1;

        if (partnerColumn < 1 || partnerColumn > MAX_COLUMNS || !isInSameBlock(startColumn, partnerColumn)) {
            return [];
        }

        if (!isSeatSelectable(seatMap[partnerColumn])) {
            return [];
        }

        const selectedSeatElements = [seatMap[startColumn], seatMap[partnerColumn]].sort((a, b) => {
            const colA = parseInt(a.getAttribute('data-seat-column')) || 0;
            const colB = parseInt(b.getAttribute('data-seat-column')) || 0;
            return colA - colB;
        });

        return selectedSeatElements.map(seat => {
            seat.classList.add('selected');
            seat.classList.remove('vip', 'available');
            return {
                id: seat.getAttribute('data-seat-id'),
                row: rowLabel,
                column: parseInt(seat.getAttribute('data-seat-column')) || 0,
                type: seat.getAttribute('data-seat-type') || 'normal'
            };
        });
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
                // Không hiển thị thông báo, chỉ return rỗng để ẩn
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

        // Logic đặc biệt cho số lượng >= 3: phải chọn đúng trong phạm vi 6 ghế của mỗi dãy
        if (count >= 3) {
            // Xác định phạm vi hợp lệ trong block
            let validRanges = [];
            if (startBlock === 'left') {
                // Dãy trái (1-6)
                if (count === 3) {
                    // Chỉ có thể chọn: 1-3 hoặc 4-6
                    validRanges = [
                        [1, 3],
                        [4, 6]
                    ];
                } else if (count === 4) {
                    // Chỉ có thể chọn: 1-4 hoặc 3-6
                    validRanges = [
                        [1, 4],
                        [3, 6]
                    ];
                } else if (count === 5) {
                    // Chỉ có thể chọn: 1-5 hoặc 2-6
                    validRanges = [
                        [1, 5],
                        [2, 6]
                    ];
                } else if (count === 6) {
                    // Chỉ có thể chọn: 1-6
                    validRanges = [
                        [1, 6]
                    ];
                }
            } else if (startBlock === 'right') {
                // Dãy phải (7-12)
                if (count === 3) {
                    // Chỉ có thể chọn: 7-9 hoặc 10-12
                    validRanges = [
                        [7, 9],
                        [10, 12]
                    ];
                } else if (count === 4) {
                    // Chỉ có thể chọn: 7-10 hoặc 9-12
                    validRanges = [
                        [7, 10],
                        [9, 12]
                    ];
                } else if (count === 5) {
                    // Chỉ có thể chọn: 7-11 hoặc 8-12
                    validRanges = [
                        [7, 11],
                        [8, 12]
                    ];
                } else if (count === 6) {
                    // Chỉ có thể chọn: 7-12
                    validRanges = [
                        [7, 12]
                    ];
                }
            }

            // Tìm range chứa startColumn
            let selectedRange = null;
            for (const range of validRanges) {
                if (startColumn >= range[0] && startColumn <= range[1]) {
                    selectedRange = range;
                    break;
                }
            }

            if (!selectedRange) {
                return []; // Không có range hợp lệ
            }

            // Tạo candidate từ range đã chọn
            const blockStart = selectedRange[0];
            const blockEnd = selectedRange[1];
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
                return [];
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

            candidates.push({
                seats: seatsList,
                rowLabel,
                newCols,
                priority: {
                    isolatedCount,
                    touchesBoth,
                    preferLeft: 0,
                    gapLeft,
                    gapRight,
                    centerDistance: Math.abs(startColumn - ((blockStart + blockEnd) / 2)),
                    leanOffset: 0,
                    blockStart
                }
            });
        }


        // Tạo các candidate ranges trong cùng block (hàng hiện tại)
        // Khi chọn 2 ghế, ưu tiên offset lớn hơn (chọn về bên trái) trước
        const offsetOrder = count === 2 ? [1, 0] : [];
        for (let i = 0; i < count; i++) {
            const offset = offsetOrder.length > 0 ? (i < offsetOrder.length ? offsetOrder[i] : i) : i;
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

            candidates.push({
                seats: seatsList,
                rowLabel,
                newCols,
                priority: {
                    isolatedCount, // Số ghế đơn lẻ (ưu tiên thấp hơn = tốt hơn)
                    touchesBoth, // Số ghế đã chọn được ghép (ưu tiên thấp hơn = tốt hơn)
                    preferLeft: 0, // Luôn ưu tiên chọn về bên trái
                    preferUpperRow: 0, // Ưu tiên ở đúng hàng đang click
                    gapLeft,
                    gapRight,
                    centerDistance: Math.abs(startColumn - ((blockStart + blockEnd) / 2)),
                    leanOffset: offset, // offset lớn hơn = ưu tiên hơn (chọn về bên trái)
                    blockStart
                }
            });
        }


        if (candidates.length === 0) {
            alert(`Không đủ ${count} ghế liền nhau trong cùng block từ vị trí này!`);
            return [];
        }

        // Sắp xếp: ưu tiên ít ghế đơn lẻ, ghép với ghế đã chọn, ưu tiên hàng trên, ưu tiên offset lớn (chọn về bên trái)
        candidates.sort((a, b) => {
            const keys = ['isolatedCount', 'touchesBoth', 'preferUpperRow', 'preferLeft', 'gapLeft', 'gapRight', 'centerDistance', 'blockStart'];
            for (const key of keys) {
                const diff = (a.priority[key] || 0) - (b.priority[key] || 0);
                if (Math.abs(diff) > 0.0001) {
                    return diff;
                }
            }
            // Ưu tiên offset lớn hơn (chọn về bên trái) - sắp xếp ngược lại
            const offsetDiff = (b.priority.leanOffset || 0) - (a.priority.leanOffset || 0);
            if (Math.abs(offsetDiff) > 0.0001) {
                return offsetDiff;
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

        // Truyền thêm thông tin số lượng người lớn và sinh viên, và đánh dấu đến từ movies.php
        const movieId = "<?= $movie['id'] ?? '' ?>";
        const cinemaId = "<?= !empty($cinemaId) ? $cinemaId : '' ?>";
        const date = "<?= $selectedDate ?? date('Y-m-d') ?>";
        window.location.href = `<?= BASE_URL ?>?act=payment&showtime_id=${currentShowtimeId}&seats=${seatIds}&seat_labels=${encodeURIComponent(seatLabels)}&adult_count=${adultCount}&student_count=${studentCount}&from=movies&movie_id=${movieId}&cinema=${cinemaId}&date=${date}`;
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

        // Tính số ghế còn lại cần chọn
        remainingSeats = totalPeople - selectedSeats.length;

        // Luôn hiển thị đầy đủ options dựa trên tổng số người, tối đa đến 4 ghế liền nhau
        // KHÔNG BAO GIỜ hiển thị option > 4
        // Option 1 chỉ xuất hiện khi số lượng = 1, không xuất hiện khi số lượng >= 4
        if (totalPeople === 1) {
            availableOptions = [1];
            // Tự động set selectedAdjacentCount = 1 khi totalPeople === 1
            selectedAdjacentCount = 1;
            // Cập nhật disabled columns ngay lập tức
            updateDisabledColumns();
        } else if (totalPeople === 2) {
            availableOptions = [2];
            // Tự động set selectedAdjacentCount = 2 khi totalPeople === 2
            if (selectedAdjacentCount === 0) {
                selectedAdjacentCount = 2;
            }
            // Khi số lượng = 2, cho phép chọn ở 2 dãy khác nhau (không bắt buộc liền nhau)
            // Nhưng khi chọn ghế, vẫn tự động chọn ghế gần nhất trong cùng dãy
        } else if (totalPeople === 3) {
            availableOptions = [3];
            // Tự động set selectedAdjacentCount = 3 khi totalPeople === 3
            if (selectedAdjacentCount === 0) {
                selectedAdjacentCount = 3;
            }
            // Khi số lượng = 3, cho phép chọn ở 2 dãy khác nhau
        } else if (totalPeople === 4) {
            // Nếu đã chọn ghế, chỉ hiển thị options phù hợp với số ghế còn lại
            if (selectedSeats.length > 0) {
                // Đã chọn một số ghế, chỉ hiển thị options <= remainingSeats
                if (remainingSeats >= 4) {
                    availableOptions = [2, 4];
                } else if (remainingSeats >= 2) {
                    availableOptions = [2];
                } else {
                    availableOptions = [remainingSeats];
                }
            } else {
                // Chưa chọn ghế nào, hiển thị đầy đủ
                availableOptions = [2, 4];
            }
        } else if (totalPeople === 5) {
            // 5 người: chỉ hiển thị 2, 3 (không có 1, 4)
            if (selectedSeats.length > 0) {
                // Đã chọn một số ghế, chỉ hiển thị options <= remainingSeats
                if (remainingSeats >= 3) {
                    availableOptions = [2, 3];
                } else if (remainingSeats >= 2) {
                    availableOptions = [2];
                } else {
                    availableOptions = [remainingSeats];
                }
            } else {
                availableOptions = [2, 3];
            }
        } else if (totalPeople >= 6 && totalPeople <= 8) {
            if (selectedSeats.length > 0) {
                // Đã chọn một số ghế, chỉ hiển thị options <= remainingSeats
                if (remainingSeats >= 4) {
                    availableOptions = [2, 3, 4];
                } else if (remainingSeats >= 3) {
                    availableOptions = [2, 3];
                } else if (remainingSeats >= 2) {
                    availableOptions = [2];
                } else {
                    availableOptions = [remainingSeats];
                }
            } else {
                availableOptions = [2, 3, 4];
            }
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
            } else if (totalPeople <= 3 && count === totalPeople) {
                // Tự động chọn option khi totalPeople <= 3
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
        const seatRows = document.querySelectorAll('#seatsGrid .seat-row');

        // Danh sách các cột bị disable khi chọn 1 ghế lẻ (cột 2, 5, 8, 11)
        const DISABLED_COLUMNS_SINGLE = [2, 5, 8, 11];

        // Bỏ disabled hàng (không cần disable hàng nữa)
        seatRows.forEach(row => {
            row.classList.remove('disabled-row');
        });

        if (totalPeople === 1 && selectedAdjacentCount === 1) {
            // Disable các cột 2, 5, 8, 11 và các cột không được phép chọn
            seats.forEach(seat => {
                const col = parseInt(seat.getAttribute('data-seat-column')) || 0;
                if (col > 0 &&
                    (DISABLED_COLUMNS_SINGLE.includes(col) || !ALLOWED_SINGLE_COLUMNS.includes(col)) &&
                    !seat.classList.contains('booked') &&
                    !seat.classList.contains('maintenance') &&
                    !seat.classList.contains('selected')) {
                    seat.classList.add('disabled-column');
                } else {
                    seat.classList.remove('disabled-column');
                }
            });

            // Hiển thị tất cả các hàng - không ẩn hàng nào
            seatRows.forEach(row => {
                row.style.display = '';
            });
        } else {
            // Hiển thị tất cả các hàng và ghế khi số lượng > 1
            seats.forEach(seat => {
                seat.classList.remove('disabled-column');
            });
            seatRows.forEach(row => {
                row.style.display = '';
            });
        }
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

<!-- Phần đánh giá và bình luận -->
<section class="reviews-section" style="background: #1a1a1a; padding: 20px 0; margin-top: 30px;">
    <div class="container">
        <h2 style="color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 15px; text-align: center;">
            <i class="bi bi-star-fill" style="color: #ff8c00; font-size: 16px;"></i> Xếp hạng và đánh giá phim
        </h2>

        <!-- Form đánh giá - Chỉ hiển thị khi chưa đánh giá -->
        <?php if (empty($existingComment)): ?>
            <div class="review-form-container" style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 15px; margin-bottom: 20px; position: relative;">
                <?php if (!$isLoggedIn): ?>
                    <div style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; text-align: center; font-size: 12px;">
                        <i class="bi bi-info-circle" style="font-size: 14px; color: #ffc107; margin-right: 5px;"></i>
                        <span style="color: rgba(255, 255, 255, 0.8);">
                            Các đánh giá phim có thể được viết sau khi đăng nhập và mua vé.
                            <a href="<?= BASE_URL ?>?act=dangnhap" style="color: #ff8c00; text-decoration: none; font-weight: 600; margin-left: 3px;">Đăng nhập</a>
                        </span>
                    </div>
                <?php elseif (!$hasPurchased): ?>
                    <div style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; text-align: center; font-size: 12px;">
                        <i class="bi bi-info-circle" style="font-size: 14px; color: #ffc107; margin-right: 5px;"></i>
                        <span style="color: rgba(255, 255, 255, 0.8);">
                            Bạn cần mua vé và thanh toán phim này trước khi có thể đánh giá.
                        </span>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?act=submit-movie-review" method="POST" id="reviewForm">
                    <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">

                    <div style="margin-bottom: 12px;">
                        <label style="color: #fff; display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">Xếp hạng <span style="color: #ff8c00;">*</span></label>
                        <div class="star-rating-input" style="display: flex; gap: 5px; align-items: center; <?= (!$isLoggedIn || !$hasPurchased) ? 'opacity: 0.5; pointer-events: none;' : '' ?>">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>"
                                    <?= ($existingComment && $existingComment['rating'] == $i) ? 'checked' : '' ?>
                                    <?= (!$isLoggedIn || !$hasPurchased) ? 'disabled' : 'required' ?>
                                    style="display: none;">
                                <label for="star<?= $i ?>" class="star-label-input" data-rating="<?= $i ?>"
                                    style="cursor: <?= ($isLoggedIn && $hasPurchased) ? 'pointer' : 'not-allowed' ?>; font-size: 20px; color: #666; transition: color 0.2s;">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            <?php endfor; ?>
                            <span id="ratingText" style="color: rgba(255, 255, 255, 0.7); margin-left: 10px; font-size: 12px;">
                                <?= $existingComment ? 'Đã chọn ' . $existingComment['rating'] . ' sao' : 'Chọn số sao' ?>
                            </span>
                        </div>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label for="reviewContent" style="color: #fff; display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">
                            Bình luận <span style="color: #ff8c00;">*</span>
                        </label>
                        <textarea name="content" id="reviewContent" rows="4"
                            placeholder="<?= !$isLoggedIn ? 'Các đánh giá phim có thể được viết sau khi đăng nhập và mua vé.' : (!$hasPurchased ? 'Bạn cần mua vé phim này trước khi có thể đánh giá.' : 'Chia sẻ cảm nhận của bạn về bộ phim này...') ?>"
                            <?= (!$isLoggedIn || !$hasPurchased) ? 'disabled' : 'required' ?>
                            style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(0, 0, 0, 0.3); color: #fff; font-size: 13px; resize: vertical; <?= (!$isLoggedIn || !$hasPurchased) ? 'opacity: 0.5; cursor: not-allowed;' : '' ?>"
                            maxlength="1000"><?= $existingComment ? htmlspecialchars($existingComment['content']) : '' ?></textarea>
                        <div style="text-align: right; margin-top: 3px; color: rgba(255, 255, 255, 0.5); font-size: 11px;">
                            <span id="charCount"><?= $existingComment ? strlen($existingComment['content']) : 0 ?></span>/1000 Ký tự
                        </div>
                    </div>

                    <button type="submit"
                        <?= (!$isLoggedIn || !$hasPurchased) ? 'disabled' : '' ?>
                        style="background: <?= ($isLoggedIn && $hasPurchased) ? '#ff8c00' : '#666' ?>; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 500; font-size: 13px; cursor: <?= ($isLoggedIn && $hasPurchased) ? 'pointer' : 'not-allowed' ?>; transition: background 0.2s; <?= (!$isLoggedIn || !$hasPurchased) ? 'opacity: 0.6;' : '' ?>">
                        <i class="bi bi-check-circle" style="font-size: 12px;"></i> Gửi đánh giá
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Thông báo đã đánh giá -->
            <div class="review-form-container" style="background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                <div style="text-align: center; color: rgba(255, 255, 255, 0.9); font-size: 13px;">
                    <i class="bi bi-check-circle-fill" style="font-size: 16px; color: #4caf50; margin-right: 5px;"></i>
                    <span>Bạn đã đánh giá bộ phim này. Mỗi tài khoản chỉ được đánh giá 1 lần cho mỗi bộ phim.</span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Danh sách bình luận -->
        <div class="comments-list">
            <h3 style="color: #fff; font-size: 15px; font-weight: 600; margin-bottom: 12px;">
                Đánh giá từ khách hàng (<?= count($comments ?? []) ?>)
            </h3>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment):
                    $isMyComment = ($isLoggedIn && isset($_SESSION['user_id']) && $comment['user_id'] == $_SESSION['user_id']);
                ?>
                    <div class="comment-item" style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                            <div style="flex: 1;">
                                <div style="color: #fff; font-weight: 500; font-size: 13px; margin-bottom: 4px;">
                                    <?= htmlspecialchars($comment['user_name'] ?? 'Khách') ?>
                                    <?php if ($isMyComment): ?>
                                        <span style="color: #4caf50; font-size: 11px; margin-left: 5px;">(Đánh giá của bạn)</span>
                                    <?php endif; ?>
                                </div>
                                <div style="display: flex; gap: 3px; align-items: center;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star-fill" style="color: <?= $i <= ($comment['rating'] ?? 0) ? '#ff8c00' : '#666' ?>; font-size: 12px;"></i>
                                    <?php endfor; ?>
                                    <span style="color: rgba(255, 255, 255, 0.7); font-size: 12px; margin-left: 4px;">
                                        <?= $comment['rating'] ?? 0 ?>/5
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="color: rgba(255, 255, 255, 0.5); font-size: 11px;">
                                    <?= date('d/m/Y', strtotime($comment['created_at'] ?? 'now')) ?>
                                </div>
                            </div>
                        </div>
                        <div style="color: rgba(255, 255, 255, 0.9); line-height: 1.5; font-size: 13px;">
                            <?= nl2br(htmlspecialchars($comment['content'] ?? '')) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 20px; color: rgba(255, 255, 255, 0.5); font-size: 13px;">
                    <i class="bi bi-chat-left-text" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    <p>Chưa có đánh giá nào cho phim này.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Star rating interaction
    const starLabels = document.querySelectorAll('.star-label-input');
    const ratingText = document.getElementById('ratingText');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');

    const ratingTexts = {
        1: 'Rất tệ',
        2: 'Tệ',
        3: 'Bình thường',
        4: 'Tốt',
        5: 'Rất tốt'
    };

    <?php if ($isLoggedIn && $hasPurchased): ?>
        starLabels.forEach(label => {
            label.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                highlightStars(rating);
                ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
            });
        });

        document.querySelector('.star-rating-input')?.addEventListener('mouseleave', function() {
            const checked = document.querySelector('input[name="rating"]:checked');
            if (checked) {
                const rating = parseInt(checked.value);
                highlightStars(rating);
                ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
            } else {
                resetStars();
                ratingText.textContent = 'Chọn số sao';
            }
        });

        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const rating = parseInt(this.value);
                highlightStars(rating);
                ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
            });
        });
    <?php endif; ?>

    function highlightStars(rating) {
        starLabels.forEach((label) => {
            const starRating = parseInt(label.dataset.rating);
            if (starRating <= rating) {
                label.classList.add('active');
                label.style.color = '#ff8c00';
            } else {
                label.classList.remove('active');
                label.style.color = '#666';
            }
        });
    }

    function resetStars() {
        starLabels.forEach(label => {
            label.classList.remove('active');
            label.style.color = '#666';
        });
    }

    // Initialize stars if existing comment
    <?php if ($existingComment && $existingComment['rating']): ?>
        highlightStars(<?= $existingComment['rating'] ?>);
        ratingText.textContent = ratingTexts[<?= $existingComment['rating'] ?>] || 'Chọn số sao';
    <?php endif; ?>

    // Character count
    const reviewContent = document.getElementById('reviewContent');
    const charCount = document.getElementById('charCount');

    if (reviewContent && charCount) {
        reviewContent.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;

            if (length > 1000) {
                charCount.style.color = '#dc3545';
                this.value = this.value.substring(0, 1000);
                charCount.textContent = 1000;
            } else if (length >= 10) {
                charCount.style.color = '#28a745';
            } else {
                charCount.style.color = '#ffc107';
            }
        });
    }

    // Form validation
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            <?php if (!$isLoggedIn || !$hasPurchased): ?>
                e.preventDefault();
                <?php if (!$isLoggedIn): ?>
                    alert('Vui lòng đăng nhập để đánh giá phim');
                    window.location.href = '<?= BASE_URL ?>?act=dangnhap';
                <?php else: ?>
                    alert('Bạn cần mua vé phim này trước khi có thể đánh giá');
                <?php endif; ?>
                return false;
            <?php else: ?>
                const rating = document.querySelector('input[name="rating"]:checked');
                const content = reviewContent?.value.trim() || '';

                if (!rating) {
                    e.preventDefault();
                    alert('Vui lòng chọn đánh giá từ 1 đến 5 sao');
                    return false;
                }

                if (content.length < 10) {
                    e.preventDefault();
                    alert('Nội dung bình luận phải có ít nhất 10 ký tự');
                    reviewContent?.focus();
                    return false;
                }

                if (content.length > 1000) {
                    e.preventDefault();
                    alert('Nội dung bình luận không được vượt quá 1000 ký tự');
                    reviewContent?.focus();
                    return false;
                }
            <?php endif; ?>
        });
    }

    // Show success message
    <?php if (isset($_GET['review_success'])): ?>
        alert('Đánh giá của bạn đã được gửi thành công!');
        window.history.replaceState({}, document.title, window.location.pathname + '?act=movies&id=<?= $movie['id'] ?>');
    <?php endif; ?>

    // Show error message
    <?php if (isset($_GET['error'])): ?>
        alert('<?= htmlspecialchars($_GET['error']) ?>');
    <?php endif; ?>
</script>