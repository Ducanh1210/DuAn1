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
    <title><?= $GLOBALS['pageTitle'] ?? 'TicketHub' ?> | TicketHub</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/lichchieu.css">
    <?php if (isset($GLOBALS['clientViewPath']) && strpos($GLOBALS['clientViewPath'], 'movies.php') !== false): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/movies.css">
    <?php endif; ?>
    <?php if (isset($GLOBALS['clientViewPath']) && strpos($GLOBALS['clientViewPath'], 'thanhtoan.php') !== false): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/thanhtoan.css">
    <?php endif; ?>
    <?php if (isset($GLOBALS['clientViewPath']) && strpos($GLOBALS['clientViewPath'], 'gioithieu.php') !== false): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/gioithieu.css">
    <?php endif; ?>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Client Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="<?= BASE_URL ?>?act=trangchu">
                    <img src="<?= BASE_URL ?>/image/logokhongnen.png" alt="TicketHub Logo" />
                </a>
            </div>

            <?php
            // Lấy trang hiện tại
            $currentAct = $_GET['act'] ?? 'trangchu';
            // Kiểm tra active cho từng menu item
            $isTrangChu = in_array($currentAct, ['trangchu', '', 'movies']);
            $isGioiThieu = ($currentAct === 'gioithieu');
            $isLichChieu = ($currentAct === 'lichchieu');
            $isKhuyenMai = ($currentAct === 'khuyenmai');
            $isGiaVe = ($currentAct === 'giave');
            $isLienHe = ($currentAct === 'lienhe');
            ?>
            <nav class="menu">
                <a href="<?= BASE_URL ?>?act=trangchu" class="<?= $isTrangChu ? 'active' : '' ?>">Trang Chủ</a>
                <a href="<?= BASE_URL ?>?act=gioithieu" class="<?= $isGioiThieu ? 'active' : '' ?>">Giới Thiệu</a>
                <a href="<?= BASE_URL ?>?act=lichchieu" class="<?= $isLichChieu ? 'active' : '' ?>">Lịch Chiếu</a>
                <a href="<?= BASE_URL ?>?act=khuyenmai" class="<?= $isKhuyenMai ? 'active' : '' ?>">Khuyến Mãi</a>
                <a href="<?= BASE_URL ?>?act=giave" class="<?= $isGiaVe ? 'active' : '' ?>">Giá Vé</a>
                <a href="<?= BASE_URL ?>?act=lienhe" class="<?= $isLienHe ? 'active' : '' ?>">Liên Hệ</a>
            </nav>

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
                        <form method="get" action="<?= BASE_URL ?>?act=<?= $currentAct ?>" id="searchForm" class="search-form-horizontal">
                            <input type="hidden" name="act" value="<?= $currentAct ?>">
                            <?php if ($currentAct === 'lichchieu' && !empty($currentDate)): ?>
                                <input type="hidden" name="date" value="<?= htmlspecialchars($currentDate) ?>">
                            <?php endif; ?>
                            <div class="search-field">
                                <label for="searchInput">Tìm kiếm phim</label>
                                <input type="text" name="search" id="searchInput"
                                    placeholder="Nhập tên phim..." 
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
    </header>

    <!-- Main Content -->
    <?php
    // Include view động nếu có biến $clientViewPath
    if (isset($GLOBALS['clientViewPath']) && !empty($GLOBALS['clientViewPath'])) {
        $viewFile = __DIR__ . '/../' . $GLOBALS['clientViewPath'];
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo '<div class="page"><div style="padding: 20px; color: #fff;">Không tìm thấy view: ' . htmlspecialchars($GLOBALS['clientViewPath']) . '</div></div>';
        }
    } else {
        echo '<div class="page"><p>Nội dung trang</p></div>';
    }
    ?>

    <!-- Client Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-top">
                <!-- Links -->
                <div class="col links">
                    <h4>Khám phá</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>?act=trangchu">Trang Chủ</a></li>
                        <li><a href="<?= BASE_URL ?>?act=lichchieu">Lịch Chiếu</a></li>
                        <li><a href="<?= BASE_URL ?>?act=giave">Giá vé</a></li>
                        <li><a href="<?= BASE_URL ?>?act=tintuc">Tin tức</a></li>
                        <li><a href="<?= BASE_URL ?>?act=lienhe">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Apps & Social -->
                <div class="col media">
                    <h4>Ứng dụng & MXH</h4>
                    <div class="badges">
                        <img src="<?= BASE_URL ?>/image/logo.png" alt="Google Play" class="app-badge">
                        <img src="<?= BASE_URL ?>/image/logo.png" alt="App Store" class="app-badge">
                    </div>

                    <div class="socials" aria-label="Mạng xã hội">
                        <a href="#" class="s-ico" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M22 12C22 6.48 17.52 2 12 2S2 6.48 2 12c0 4.99 3.66 9.12 8.44 9.88v-6.99H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.25c-1.23 0-1.61.76-1.61 1.54v1.85h2.74l-.44 2.9h-2.3V21.9C18.34 21.12 22 16.99 22 12z" />
                            </svg>
                        </a>

                        <a href="#" class="s-ico" aria-label="Zalo">
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

    <script>
        // Đặt năm hiện tại
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Search box toggle functionality
        (function () {
            const searchIcon = document.getElementById('searchIcon');
            const searchBox = document.getElementById('searchBox');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Toggle search box khi click vào icon
            if (searchIcon) {
                searchIcon.addEventListener('click', function (e) {
                    e.stopPropagation();
                    searchBox.classList.add('active');
                    searchInput.focus();
                });
            }

            // Đóng search box khi click bên ngoài
            document.addEventListener('click', function (e) {
                if (!searchBox.contains(e.target) && !searchIcon.contains(e.target)) {
                    searchBox.classList.remove('active');
                }
            });

            // Xử lý khi nhấn Enter trong search input
            const searchForm = document.getElementById('searchForm');
            if (searchInput) {
                searchInput.addEventListener('keypress', function (e) {
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
                searchBtn.addEventListener('click', function (e) {
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
