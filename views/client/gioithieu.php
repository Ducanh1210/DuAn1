<?php
// Start session ở đầu file, trước mọi output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới Thiệu | TicketHub</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/gioithieu.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- phần menu -->
    <section class="hero">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="<?= BASE_URL ?>?act=trangchu">
                        <img src="<?= BASE_URL ?>/image/logokhongnen.png" alt="TicketHub Logo">
                    </a>
                </div>
                <div style="flex:1;display:flex;justify-content:center">
                    <nav class="nav-center">
                        <a href="<?= BASE_URL ?>?act=trangchu">Trang Chủ</a>
                        <a href="<?= BASE_URL ?>?act=gioithieu" class="active">Giới Thiệu</a>
                        <a href="<?= BASE_URL ?>?act=lichchieu">Lịch Chiếu</a>
                        <a href="<?= BASE_URL ?>?act=khuyenmai">Khuyến mãi</a>
                        <a href="<?= BASE_URL ?>?act=giave">Giá Vé</a>
                        <a href="<?= BASE_URL ?>?act=lienhe">Liên Hệ</a>
                    </nav>
                </div>
                <div class="nav-actions">
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
                                <a href="<?= BASE_URL ?>?act=thethanhvien">
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
        </div>
    </section>

    <!-- Nội dung giới thiệu -->
    <main>
        <div class="about-container">
            <div class="about-header">
                <h1 class="about-title">
                    <span class="dot"></span>
                    Giới Thiệu Về TicketHub
                </h1>
                <p class="about-subtitle">Trải nghiệm điện ảnh đỉnh cao, đặt vé dễ dàng</p>
            </div>

            <div class="about-content">
                <section class="about-section">
                    <div class="section-icon">
                        <i class="bi bi-film"></i>
                    </div>
                    <h2>Về Chúng Tôi</h2>
                    <p>
                        TicketHub là nền tảng đặt vé xem phim trực tuyến hàng đầu tại Việt Nam, mang đến cho khán giả
                        trải nghiệm giải trí điện ảnh tuyệt vời nhất. Với hệ thống hiện đại và dịch vụ chuyên nghiệp,
                        chúng tôi cam kết đem lại sự tiện lợi và hài lòng tối đa cho mọi khách hàng.
                    </p>
                    <p>
                        Được thành lập với sứ mệnh kết nối người yêu phim với những bộ phim hay nhất từ khắp nơi trên thế giới,
                        TicketHub không ngừng phát triển và cải tiến để phục vụ hàng triệu người xem mỗi năm.
                    </p>
                </section>

                <section class="about-section">
                    <div class="section-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h2>Tại Sao Chọn TicketHub?</h2>
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <h3>Đặt Vé Nhanh Chóng</h3>
                            <p>Chỉ với vài thao tác đơn giản, bạn có thể đặt vé xem phim yêu thích trong vài giây</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <h3>Thanh Toán An Toàn</h3>
                            <p>Hỗ trợ đa dạng hình thức thanh toán với bảo mật thông tin tối đa</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <h3>Lịch Chiếu Đa Dạng</h3>
                            <p>Cập nhật liên tục các suất chiếu mới nhất từ nhiều rạp trên toàn quốc</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h3>Ưu Đãi Hấp Dẫn</h3>
                            <p>Nhiều chương trình khuyến mãi và ưu đãi đặc biệt dành cho thành viên</p>
                        </div>
                    </div>
                </section>

                <section class="about-section">
                    <div class="section-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h2>Sứ Mệnh Của Chúng Tôi</h2>
                    <p>
                        Chúng tôi tin rằng điện ảnh là một hình thức nghệ thuật độc đáo có khả năng kết nối mọi người,
                        truyền cảm hứng và tạo nên những kỷ niệm đáng nhớ. TicketHub được xây dựng với mục tiêu làm cho
                        việc thưởng thức phim ảnh trở nên dễ dàng và thuận tiện hơn bao giờ hết.
                    </p>
                    <p>
                        Chúng tôi không ngừng nỗ lực để mang đến trải nghiệm tốt nhất cho khán giả, từ việc lựa chọn phim,
                        đặt vé, cho đến việc tận hưởng bộ phim tại rạp với chất lượng hình ảnh và âm thanh tuyệt hảo.
                    </p>
                </section>

                <section class="about-section stats-section">
                    <h2>Thành Tựu Của Chúng Tôi</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">500K+</div>
                            <div class="stat-label">Khách hàng thân thiết</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Rạp chiếu phim</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Bộ phim đã chiếu</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">2M+</div>
                            <div class="stat-label">Vé đã bán</div>
                        </div>
                    </div>
                </section>

                <section class="about-section cta-section">
                    <div class="cta-content">
                        <h2>Bắt Đầu Trải Nghiệm Ngay Hôm Nay</h2>
                        <p>Tham gia cùng hàng nghìn người yêu phim trên khắp Việt Nam</p>
                        <div class="cta-buttons">
                            <a href="<?= BASE_URL ?>?act=lichchieu" class="btn-primary">Xem Lịch Chiếu</a>
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <a href="<?= BASE_URL ?>?act=dangky" class="btn-secondary">Đăng Ký Ngay</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
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
                        <li><a href="<?= BASE_URL ?>?act=lichchieu">Lịch Chiếu</a></li>
                        <li><a href="<?= BASE_URL ?>?act=khuyenmai">Khuyến mãi</a></li>
                        <li><a href="<?= BASE_URL ?>?act=giave">Giá vé</a></li>
                        <li><a href="<?= BASE_URL ?>?act=tintuc">Tin tức</a></li>
                        <li><a href="<?= BASE_URL ?>?act=hoidap">Hỏi đáp</a></li>
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
                        Ô Chọ Dừa, TP. Hà Nội</p>
                    <p class="muted">Điện thoại: <a href="tel:02435141791">024.3514.1791</a></p>
                    <div class="certs">
                        <img src="" alt="Đã thông báo" class="gov-badge">
                    </div>
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
</script>

</html>