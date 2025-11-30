<?php
// Start session ở đầu file, trước mọi output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ | TicketHub</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/notifications.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- phần menu -->
    <section class="hero" style="background-image: url('<?= BASE_URL ?>image/banner1.jpg');">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="<?= BASE_URL ?>?act=trangchu">
                        <img src="<?= BASE_URL ?>/image/logokhongnen.png" alt="TicketHub Logo">
                    </a>
                </div>
                <?php
                // Lấy trang hiện tại
                $currentAct = $_GET['act'] ?? 'trangchu';
                // Kiểm tra active cho từng menu item
                $isTrangChu = in_array($currentAct, ['trangchu', '', 'movies']);
                $isGioiThieu = ($currentAct === 'gioithieu');
                $isLichChieu = ($currentAct === 'lichchieu');
                $isGiaVe = ($currentAct === 'giave');
                $isLienHe = ($currentAct === 'lienhe');
                ?>
                <div style="flex:1;display:flex;justify-content:center">
                    <nav class="nav-center">
                        <a href="<?= BASE_URL ?>?act=trangchu" class="<?= $isTrangChu ? 'active' : '' ?>">Trang Chủ</a>
                        <a href="<?= BASE_URL ?>?act=gioithieu" class="<?= $isGioiThieu ? 'active' : '' ?>">Giới Thiệu</a>
                        <a href="<?= BASE_URL ?>?act=lichchieu" class="<?= $isLichChieu ? 'active' : '' ?>">Lịch Chiếu</a>
                        <a href="<?= BASE_URL ?>?act=khuyenmai" class="<?= $isKhuyenMai ? 'active' : '' ?>">Khuyến mãi</a>
                        <a href="<?= BASE_URL ?>?act=giave" class="<?= $isGiaVe ? 'active' : '' ?>">Giá Vé</a>
                        <a href="<?= BASE_URL ?>?act=lienhe" class="<?= $isLienHe ? 'active' : '' ?>">Liên Hệ</a>

                </div>
                <div class="nav-actions">
                    <?php
                    if (isset($_SESSION['user_id'])):
                        $userName = htmlspecialchars($_SESSION['user_name'] ?? 'User');
                    ?>
                        <!-- Notifications for logged-in users - ĐẶT TRƯỚC SEARCH -->
                        <div class="notification-wrapper">
                            <i class="bi bi-bell notification-icon" id="clientNotificationIcon"></i>
                            <span class="notification-badge" id="clientNotificationBadge" style="display: none;">0</span>
                            <div class="notification-dropdown" id="clientNotificationDropdown" style="display: none;">
                                <div class="notification-header">
                                    <h6>Thông báo</h6>
                                    <button class="btn-mark-all-read" id="clientMarkAllReadBtn">Đánh dấu tất cả đã đọc</button>
                                </div>
                                <div class="notification-list" id="clientNotificationList">
                                    <div class="notification-empty">Không có thông báo mới</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon" id="searchIcon" title="Tìm kiếm"></i>
                        <div class="search-box" id="searchBox">
                            <?php
                            // Lấy tham số từ URL để hiển thị trong form
                            $currentAct = $_GET['act'] ?? 'trangchu';
                            $currentSearch = $_GET['search'] ?? '';
                            $currentCinema = $_GET['cinema'] ?? '';
                            $currentDate = $_GET['date'] ?? '';
                            // Lấy danh sách rạp nếu chưa có
                            if (!isset($cinemas)) {
                                try {
                                    require_once __DIR__ . '/../../commons/env.php';
                                    require_once __DIR__ . '/../../commons/function.php';
                                    $conn = connectDB();
                                    $sql = "SELECT * FROM cinemas ORDER BY name ASC";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $cinemas = $stmt->fetchAll();
                                } catch (Exception $e) {
                                    $cinemas = [];
                                }
                            }
                            ?>
                            <form method="get" action="<?= BASE_URL ?>?act=<?= $currentAct ?>" id="searchForm"
                                class="search-form-horizontal">
                                <input type="hidden" name="act" value="<?= $currentAct ?>">
                                <?php if ($currentAct === 'lichchieu' && !empty($currentDate)): ?>
                                    <input type="hidden" name="date" value="<?= htmlspecialchars($currentDate) ?>">
                                <?php endif; ?>
                                <div class="search-field">
                                    <label for="searchInput">Tìm kiếm phim</label>
                                    <input type="text" name="search" id="searchInput" placeholder="Nhập tên phim..."
                                        value="<?= htmlspecialchars($currentSearch) ?>">
                                </div>
                                <div class="search-field">
                                    <label for="cinemaSelect">Lọc theo rạp</label>
                                    <select name="cinema" id="cinemaSelect">
                                        <option value="">Tất cả rạp</option>
                                        <?php if (!empty($cinemas)): ?>
                                            <?php foreach ($cinemas as $cinema): ?>
                                                <option value="<?= $cinema['id'] ?>" <?= $currentCinema == $cinema['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cinema['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="submit" id="searchBtn" class="search-submit-btn">
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                                <?php if (!empty($currentSearch) || !empty($currentCinema)): ?>
                                    <?php
                                    $clearUrl = BASE_URL . '?act=' . $currentAct;
                                    if ($currentAct === 'lichchieu' && !empty($currentDate)) {
                                        $clearUrl .= '&date=' . htmlspecialchars($currentDate);
                                    }
                                    ?>
                                    <a href="<?= $clearUrl ?>" class="search-clear-btn">
                                        <i class="bi bi-x"></i>
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION['user_id'])):
                        $userName = htmlspecialchars($_SESSION['user_name'] ?? 'User');
                    ?>
                        <div class="user-dropdown-wrapper">
                            <div class="user-dropdown-toggle" id="userDropdownToggle">
                                <i class="bi bi-person-circle" style="font-size: 24px; color: rgba(255, 255, 255, 0.9);"></i>
                                <span><?= $userName ?></span>
                                <i class="bi bi-chevron-down" style="font-size: 12px; margin-left: 4px;"></i>
                            </div>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <a href="<?= BASE_URL ?>?act=profile">
                                    <i class="bi bi-person"></i>
                                    <span>Thông tin cá nhân</span>
                                </a>
                                <a href="<?= BASE_URL ?>?act=profile&tab=membership">
                                    <i class="bi bi-credit-card"></i>
                                    <span>Thẻ thành viên</span>
                                </a>
                                <a href="<?= BASE_URL ?>?act=dangxuat" class="logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Đăng xuất</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a class="btn-login" href="<?= BASE_URL ?>?act=dangnhap">Đăng Nhập</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-content">
                <div class="left">
                    <div class="brand-badge">MARVEL STUDIOS</div>
                    <h1>Guardians<br />of the Galaxy</h1>
                    <div class="meta">Action | Adventure | Sci-Fi
                        &nbsp;&nbsp; • &nbsp;&nbsp; 2018 &nbsp;&nbsp; •
                        &nbsp;&nbsp; 2h 8m</div>
                    <p class="desc">Trong một thế giới hậu tận thế nơi các
                        thành phố di chuyển trên những bánh xe và tiêu thụ
                        lẫn nhau để sinh tồn, hai người gặp nhau ở London và
                        cố gắng ngăn chặn một âm mưu.</p>
                    <div class="cta">
                        <a class="btn-primary" href="<?= BASE_URL ?>?act=lichchieu">Đặt Vé →</a>
                        <a class="btn-secondary" href="https://youtu.be/KCSNFZKbhZE?si=HytyMDkb_dx0XPrV" target="_blank" rel="noopener noreferrer">Watch Trailer</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- phần nội dung -->
    <main>
        <section class="movies-wrap">
            <div class="movies-inner">
                <!-- Left: carousel -->
                <div class="movies-left">
                    <div class="section-header">
                        <div class="title-left">
                            <span class="dot"></span>
                            <h3>Phim đang chiếu</h3>
                        </div>
                        <a class="view-all" href="<?= BASE_URL ?>?act=lichchieu">Xem tất cả</a>
                    </div>

                    <div class="carousel" id="movieCarousel" tabindex="0">
                        <div class="carousel-track">
                            <!-- movie card (repeat) -->
                            <?php if (!empty($moviesNowShowing)): ?>
                                <?php foreach ($moviesNowShowing as $movie): ?>
                                    <a href="<?= BASE_URL ?>?act=movies&id=<?= $movie['id'] ?>" class="movie-card-link">
                                        <article class="movie-card">
                                            <div class="poster">
                                                <?php if (!empty($movie['image'])): ?>
                                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>"
                                                        alt="<?= htmlspecialchars($movie['title']) ?>">
                                                <?php else: ?>
                                                    <img src="<?= BASE_URL ?>/image/logo.png"
                                                        alt="<?= htmlspecialchars($movie['title']) ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body">
                                                <div class="tags">
                                                    <?= !empty($movie['genre_name']) ? htmlspecialchars($movie['genre_name']) : '—' ?>
                                                </div>
                                                <div class="date">
                                                    <?= !empty($movie['release_date']) ? date('d/m/Y', strtotime($movie['release_date'])) : '—' ?>
                                                </div>
                                                <h4 class="movie-title">
                                                    <?= strtoupper(htmlspecialchars($movie['title'])) ?>
                                                    <?php if (!empty($movie['age_rating'])): ?>
                                                        - <?= htmlspecialchars($movie['age_rating']) ?>
                                                    <?php endif; ?>
                                                </h4>
                                            </div>
                                        </article>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center text-muted" style="padding: 40px;">Chưa có phim đang chiếu</p>
                            <?php endif; ?>
                        </div>

                        <!-- dots -->
                        <div class="carousel-dots" id="carouselDots"></div>
                    </div>
                </div>
                <aside class="movies-right">
                    <div class="promo-header">
                        <h3 class="promo-title">Khuyến mãi</h3>
                        <a class="view-all" href="<?= BASE_URL ?>?act=khuyenmai">Xem tất cả</a>
                    </div>

                    <div class="promo-carousel-wrapper" id="viewport">
                        <div class="promo-carousel">
                            <div class="promo-list" id="track"></div>
                        </div>
                    </div>

                    <!-- small pager (mirrors carousel pages) -->
                    <div class="mini-dots" id="dots"></div>
                </aside>
            </div>
        </section>

        <!-- phần phim sắp chiếu -->
        <section class="movies-wrap">
            <div class="movies-inner">
                <!-- Left: carousel -->
                <div class="movies-left">
                    <div class="section-header">
                        <div class="title-left">
                            <span class="dot"></span>
                            <h3>Phim Sắp Chiếu</h3>
                        </div>
                        <a class="view-all" href="<?= BASE_URL ?>?act=lichchieu">Xem tất cả</a>
                    </div>

                    <div class="carousel" id="movieCarouselComingSoon" tabindex="0">
                        <div class="carousel-track">
                            <!-- movie card (repeat) -->
                            <?php if (!empty($moviesComingSoon)): ?>
                                <?php foreach ($moviesComingSoon as $movie): ?>
                                    <a href="<?= BASE_URL ?>?act=movies&id=<?= $movie['id'] ?>" class="movie-card-link">
                                        <article class="movie-card">
                                            <div class="poster">
                                                <?php if (!empty($movie['image'])): ?>
                                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>"
                                                        alt="<?= htmlspecialchars($movie['title']) ?>">
                                                <?php else: ?>
                                                    <img src="<?= BASE_URL ?>/image/logo.png"
                                                        alt="<?= htmlspecialchars($movie['title']) ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body">
                                                <div class="tags">
                                                    <?= !empty($movie['genre_name']) ? htmlspecialchars($movie['genre_name']) : '—' ?>
                                                </div>
                                                <div class="date">
                                                    <?= !empty($movie['release_date']) ? date('d/m/Y', strtotime($movie['release_date'])) : '—' ?>
                                                </div>
                                                <h4 class="movie-title">
                                                    <?= strtoupper(htmlspecialchars($movie['title'])) ?>
                                                    <?php if (!empty($movie['age_rating'])): ?>
                                                        - <?= htmlspecialchars($movie['age_rating']) ?>
                                                    <?php endif; ?>
                                                </h4>
                                            </div>
                                        </article>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center text-muted" style="padding: 40px;">Chưa có phim sắp chiếu</p>
                            <?php endif; ?>
                        </div>

                        <!-- dots -->
                        <div class="carousel-dots" id="carouselDotsComingSoon"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- phần footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-top">
                <!-- Links -->
                <div class="col links">
                    <h4>Khám phá</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>?act=trangchu">Trang Chủ</a></li>
                        <li><a href="<?= BASE_URL ?>?act=gioithieu">Giới thiệu</a></li>
                        <li><a href="<?= BASE_URL ?>?act=lichchieu">Lịch Chiếu</a></li>
                        <li><a href="<?= BASE_URL ?>?act=khuyenmai">Khuyến mãi</a></li>
                        <li><a href="<?= BASE_URL ?>?act=giave">Giá vé</a></li>
                        <li><a href="<?= BASE_URL ?>?act=lienhe">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Apps & Social -->
                <div class="col media">
                    <h4>Ứng dụng & MXH</h4>
                    <div class="badges">
                        <!-- Replace src with official badges for production -->
                        <img src="./img/badge-googleplay.png" alt="Google Play" class="app-badge">
                        <img src="./img/badge-appstore.png" alt="App Store" class="app-badge">
                    </div>

                    <div class="socials" aria-label="Mạng xã hội">
                        <a href="#" class="s-ico" aria-label="Facebook">
                            <!-- Facebook SVG -->
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M22 12C22 6.48 17.52 2 12 2S2 6.48 2 12c0 4.99 3.66 9.12 8.44 9.88v-6.99H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.25c-1.23 0-1.61.76-1.61 1.54v1.85h2.74l-.44 2.9h-2.3V21.9C18.34 21.12 22 16.99 22 12z" />
                            </svg>
                        </a>

                        <a href="#" class="s-ico" aria-label="Zalo">
                            <!-- generic chat icon -->
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M12 2C6.48 2 2 5.94 2 10.5c0 2.45 1.42 4.67 3.7 6.04L6 22l4.59-2.54C12.3 19.71 12.65 19.75 13 19.75c5.52 0 10-3.94 10-8.5S17.52 2 12 2z" />
                            </svg>
                        </a>

                        <a href="#" class="s-ico" aria-label="YouTube">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M10 15l5.19-3L10 9v6zm12-6.2c0-.9-.36-1.73-1-2.36-.66-.64-1.5-1-2.4-1.05C16.18 4.99 12 5 12 5s-4.18-.01-6.6.39c-.9.05-1.74.41-2.4 1.05-.64.63-1 1.46-1 2.36V12c0 .9.36 1.73 1 2.36.66.64 1.5 1 2.4 1.05C7.82 16.99 12 17 12 17s4.18.01 6.6-.39c.9-.05 1.74-.41 2.4-1.05.64-.63 1-1.46 1-2.36v-3.2z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Contact & Address -->
                <div class="col contact">
                    <h4>Liên hệ</h4>
                    <p class="muted">Cơ quan chủ quản: <strong>BỘ
                            VĂN HÓA, THỂ THAO VÀ DU
                            LỊCH</strong></p>
                    <p class="muted">Địa chỉ: Số 87 Láng Hạ, Phường
                        Ô Chợ Dừa, TP. Hà Nội</p>
                    <p class="muted">Điện thoại: <a href="tel:02435141791">024.3514.1791</a></p>
                </div>

                <!-- Subscribe -->
                <div class="col subscribe">
                    <h4>Nhận thông báo</h4>
                    <p class="muted">Đăng ký để nhận khuyến mãi và
                        lịch chiếu mới nhất.</p>
                    <form id="footer-subscribe" class="subscribe-form" onsubmit="return false;">
                        <input type="email" id="sub-email" placeholder="Nhập email của bạn" aria-label="Email">
                        <button type="submit" id="sub-btn">Đăng
                            ký</button>
                    </form>
                    <div id="sub-msg" class="sub-msg" aria-live="polite"></div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="copy">© <span id="currentYear"></span>
                    Trung tâm Chiếu phim Quốc gia. Bản quyền thuộc
                    Trung tâm. Giấy phép: 224/GP-TTĐT. <span class="sep">|</span> Dev by <a href="https://anvui.vn"
                        target="_blank" rel="noopener">Anvui.vn</a>
                </p>
            </div>
        </div>
    </footer>
</body>
<script type="text/javascript" src="app.js"></script>
<script>
    // Đặt năm hiện tại
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Search box toggle functionality
    (function() {
        const searchIcon = document.getElementById('searchIcon');
        const searchBox = document.getElementById('searchBox');
        const searchInput = document.getElementById('searchInput');
        const searchClose = document.getElementById('searchClose');
        const searchBtn = document.getElementById('searchBtn');

        // Toggle search box khi click vào icon
        if (searchIcon) {
            searchIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                searchBox.classList.add('active');
                searchInput.focus();
            });
        }

        // Đóng search box khi click bên ngoài
        document.addEventListener('click', function(e) {
            if (!searchBox.contains(e.target) && !searchIcon.contains(e.target)) {
                searchBox.classList.remove('active');
            }
        });

        // Xử lý khi nhấn Enter trong search input
        const searchForm = document.getElementById('searchForm');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (searchForm) {
                        searchForm.submit();
                    }
                }
            });
        }

        // Xử lý khi click vào nút search
        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (searchForm) {
                    searchForm.submit();
                }
            });
        }
    })();

    // User dropdown toggle
    (function() {
        const userDropdownToggle = document.getElementById('userDropdownToggle');
        const userDropdownWrapper = userDropdownToggle ? userDropdownToggle.closest('.user-dropdown-wrapper') : null;
        const userDropdownMenu = document.getElementById('userDropdownMenu');

        if (userDropdownToggle && userDropdownWrapper) {
            userDropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownWrapper.classList.toggle('active');
            });

            // Đóng dropdown khi click bên ngoài
            document.addEventListener('click', function(e) {
                if (!userDropdownWrapper.contains(e.target)) {
                    userDropdownWrapper.classList.remove('active');
                }
            });
        }
    })();

    // Promo Carousel - Tự động chạy và hỗ trợ vuốt (mỗi trang 3 ảnh)
    (function(){
        // ======= Thay ảnh ở đây =======
        const imagePaths = [
            '<?= BASE_URL ?>/image/banner1.jpg',
            '<?= BASE_URL ?>/image/banner2.jpg',
            '<?= BASE_URL ?>/image/banner3.jpg',
            '<?= BASE_URL ?>/image/banner4.jpg',
            '<?= BASE_URL ?>/image/banner3.jpg',
            '<?= BASE_URL ?>/image/banner4.jpg'
        ];
        // =====================================

        const perPage = 3; // số ảnh trên 1 trang
        const pages = [];

        for(let i=0; i<imagePaths.length; i+=perPage){
            pages.push(imagePaths.slice(i, i+perPage));
        }

        const track = document.getElementById('track');
        const dotsContainer = document.getElementById('dots');
        const viewport = document.getElementById('viewport');

        if (!track || !dotsContainer || !viewport) return;

        // tạo DOM cho các page
        pages.forEach((pageImgs, pageIndex) => {
            const page = document.createElement('div');
            page.className = 'page';
            pageImgs.forEach(src => {
                const a = document.createElement('a');
                a.className = 'card';
                a.href = '#';
                const img = document.createElement('img');
                img.src = src;
                img.alt = '';
                a.appendChild(img);
                page.appendChild(a);
            });
            track.appendChild(page);

            // tạo dot
            const dot = document.createElement('div');
            dot.className = 'dot' + (pageIndex === 0 ? ' active' : '');
            dot.dataset.idx = pageIndex;
            dot.addEventListener('click', () => goToPage(pageIndex));
            dotsContainer.appendChild(dot);
        });

        const dotElems = Array.from(dotsContainer.children);
        const totalPages = pages.length;
        let pageIndex = 0;
        let autoTimer = null;

        function update(){
            track.style.transform = `translateX(-${pageIndex * 100}%)`;
            dotElems.forEach(d => d.classList.remove('active'));
            if(dotElems[pageIndex]) dotElems[pageIndex].classList.add('active');
        }

        function goToPage(i){
            pageIndex = ((i % totalPages) + totalPages) % totalPages;
            update();
            restartAuto();
        }

        // auto-play (theo page)
        function startAuto(){
            stopAuto();
            autoTimer = setInterval(() => {
                goToPage(pageIndex + 1);
            }, 3500);
        }

        function stopAuto(){
            if(autoTimer){ clearInterval(autoTimer); autoTimer = null; }
        }

        function restartAuto(){
            stopAuto();
            startAuto();
        }

        // swipe support (touch + mouse)
        let startX = 0;
        let isDragging = false;

        viewport.addEventListener('touchstart', (e) => {
            stopAuto();
            isDragging = true;
            startX = e.touches[0].clientX;
        }, {passive:true});

        viewport.addEventListener('touchend', (e) => {
            if(!isDragging) return;
            const endX = e.changedTouches[0].clientX;
            handleSwipe(startX, endX);
            isDragging = false;
            restartAuto();
        });

        // mouse drag for desktop
        viewport.addEventListener('mousedown', (e) => {
            stopAuto();
            isDragging = true;
            startX = e.clientX;
        });

        window.addEventListener('mouseup', (e) => {
            if(!isDragging) return;
            const endX = e.clientX;
            handleSwipe(startX, endX);
            isDragging = false;
            restartAuto();
        });

        function handleSwipe(start, end){
            const diff = end - start;
            const threshold = 40; // px để tính là swipe
            if(diff < -threshold){
                goToPage(pageIndex + 1);
            } else if(diff > threshold){
                goToPage(pageIndex - 1);
            } else {
                // small movement -> no change
                goToPage(pageIndex);
            }
        }

        // pause auto when hover (desktop)
        viewport.addEventListener('mouseenter', stopAuto);
        viewport.addEventListener('mouseleave', startAuto);

        // init
        update();
        startAuto();

        // ensure correct alignment on resize
        window.addEventListener('resize', update);
    })();
</script>

<?php if (isset($_SESSION['user_id'])): ?>
<script>
    // Client-side notifications
    (function() {
        const notificationIcon = document.getElementById('clientNotificationIcon');
        const notificationBadge = document.getElementById('clientNotificationBadge');
        const notificationDropdown = document.getElementById('clientNotificationDropdown');
        const notificationList = document.getElementById('clientNotificationList');
        const markAllReadBtn = document.getElementById('clientMarkAllReadBtn');

        function loadNotifications() {
            fetch('<?= BASE_URL ?>?act=api-client-notifications&unread_only=true')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notifications = data.notifications || [];
                        updateNotificationBadge(data.unread_count || 0);
                        renderNotifications(notifications);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function updateNotificationBadge(count) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.style.display = 'block';
            } else {
                notificationBadge.style.display = 'none';
            }
        }

        function renderNotifications(notifications) {
            if (notifications.length === 0) {
                notificationList.innerHTML = '<div class="notification-empty">Không có thông báo mới</div>';
                return;
            }

            notificationList.innerHTML = notifications.map(notif => `
                <div class="notification-item unread" data-id="${notif.id}">
                    <div class="notification-content">
                        <div class="notification-title">${notif.title}</div>
                        <div class="notification-message">${notif.message}</div>
                        <div class="notification-time">${formatTime(notif.created_at)}</div>
                    </div>
                    ${notif.related_id ? `<a href="<?= BASE_URL ?>?act=my-bookings" class="notification-link">Xem chi tiết</a>` : ''}
                </div>
            `).join('');

            // Add click event to mark as read
            notificationList.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking the link
                    if (e.target.tagName === 'A') return;
                    
                    const id = this.dataset.id;
                    if (id) {
                        markAsRead(id, this);
                    }
                });
            });
        }

        function markAsRead(id, element) {
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('<?= BASE_URL ?>?act=api-client-notifications-mark-read', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationBadge(data.unread_count || 0);
                        // Remove the notification item from list
                        if (element) {
                            element.remove();
                        // If no more notifications, show empty message
                        if (notificationList.children.length === 0) {
                            notificationList.innerHTML = '<div class="notification-empty">Không có thông báo mới</div>';
                        }
                        }
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
        }

        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                
                fetch('<?= BASE_URL ?>?act=api-client-notifications-mark-all-read', {
                    method: 'POST'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update badge to 0 (will hide it)
                            updateNotificationBadge(0);
                            // Clear all notifications from list immediately
                            notificationList.innerHTML = '<div class="notification-empty">Không có thông báo mới</div>';
                        }
                    })
                    .catch(error => console.error('Error marking all as read:', error));
            });
        }

        if (notificationIcon) {
            notificationIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                if (notificationDropdown.style.display === 'none' || notificationDropdown.style.display === '') {
                    notificationDropdown.style.display = 'block';
                    loadNotifications();
                } else {
                    notificationDropdown.style.display = 'none';
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (notificationDropdown && notificationIcon && 
                !notificationDropdown.contains(e.target) && 
                !notificationIcon.contains(e.target) &&
                !e.target.closest('.notification-wrapper')) {
                notificationDropdown.style.display = 'none';
            }
        });

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            if (diff < 60) return 'Vừa xong';
            if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
            if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
            return date.toLocaleDateString('vi-VN');
        }

        // Load notifications on page load and refresh every 30 seconds
        loadNotifications();
        setInterval(loadNotifications, 30000);
    })();
</script>
<?php endif; ?>

</html>