<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/lichchieu.css">
<style>
    .lichchieu-layout {
        display: flex;
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .cinema-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: linear-gradient(180deg, rgba(255, 105, 120, 0.08), rgba(0, 0, 0, 0.12));
        border-radius: 16px;
        padding: 24px;
        border: 2px solid rgba(255, 105, 120, 0.15);
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 
                    inset 0 1px 0 rgba(255, 255, 255, 0.05);
        position: relative;
    }
    
    .cinema-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 105, 120, 0.6), 
            rgba(255, 105, 120, 0.8),
            rgba(255, 105, 120, 0.6),
            transparent);
        border-radius: 16px 16px 0 0;
    }
    
    .cinema-sidebar h3 {
        font-family: "Montserrat", sans-serif;
        font-size: 24px;
        font-weight: 900;
        background: linear-gradient(135deg, #ffffff 0%, #ff6978 50%, #ffffff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(255, 105, 120, 0.3);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        text-shadow: 0 0 20px rgba(255, 105, 120, 0.3);
    }
    
    .cinema-sidebar h3::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, var(--accent), transparent);
    }
    
    .cinema-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .cinema-item {
        padding: 14px 18px;
        margin-bottom: 10px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid rgba(255, 255, 255, 0.08);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.01));
        font-family: "Montserrat", sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        position: relative;
        overflow: hidden;
    }
    
    .cinema-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 105, 120, 0.2), 
            transparent);
        transition: left 0.5s ease;
    }
    
    .cinema-item:hover {
        background: linear-gradient(135deg, 
            rgba(255, 105, 120, 0.15), 
            rgba(255, 105, 120, 0.08));
        border-color: rgba(255, 105, 120, 0.4);
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(255, 105, 120, 0.2),
                    0 0 0 1px rgba(255, 105, 120, 0.1);
        color: #ffffff;
    }
    
    .cinema-item:hover::before {
        left: 100%;
    }
    
    .cinema-item.active {
        background: linear-gradient(135deg, 
            var(--accent) 0%, 
            #ff5067 50%,
            var(--accent) 100%);
        color: #fff;
        border-color: var(--accent);
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 6px 24px rgba(255, 105, 120, 0.4),
                    0 0 0 2px rgba(255, 105, 120, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transform: translateX(6px) scale(1.02);
        position: relative;
    }
    
    .cinema-item.active::after {
        content: '✓';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        font-weight: 900;
        color: #fff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .cinema-item.disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
        background: rgba(255, 255, 255, 0.02) !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        color: rgba(255, 255, 255, 0.4) !important;
        font-weight: 400;
    }
    
    .cinema-item.disabled:hover {
        background: rgba(255, 255, 255, 0.02) !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .movies-content {
        flex: 1;
        min-width: 0;
    }
    
    .movies-content-header {
        margin-bottom: 20px;
    }
    
    .movies-content-header h2 {
        font-family: "Montserrat", sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 16px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .movie-disabled {
        opacity: 0.5;
        position: relative;
    }
    
    .movie-disabled a {
        cursor: not-allowed !important;
        pointer-events: none;
    }
    
    .movie-disabled::after {
        content: 'Vui lòng chọn rạp trước';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.9);
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        z-index: 100;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }
    
    @media (max-width: 980px) {
        .lichchieu-layout {
            flex-direction: column;
        }
        
        .cinema-sidebar {
            width: 100%;
            max-height: 300px;
        }
        
        .cinema-sidebar h3 {
            font-size: 20px;
            letter-spacing: 1px;
        }
        
        .cinema-item {
            padding: 12px 16px;
            font-size: 14px;
        }
        
        .cinema-item.active {
            font-size: 15px;
        }
    }
    
    @media (max-width: 480px) {
        .cinema-sidebar {
            padding: 18px;
            border-radius: 12px;
        }
        
        .cinema-sidebar h3 {
            font-size: 18px;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-bottom: 12px;
        }
        
        .cinema-item {
            padding: 10px 14px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .cinema-item.active {
            font-size: 14px;
            transform: translateX(3px) scale(1.01);
        }
    }
</style>

<!-- Phần nội dung lịch chiếu -->
<div class="page">
    <div class="date-pills" role="navigation" aria-label="Chọn ngày" style="margin-bottom: 24px;">
        <?php foreach ($dates as $dateItem):
            $isActive = ($dateItem['date'] === $selectedDate) ? 'active' : '';
            // Giữ lại tham số search, cinema và movie khi chuyển ngày
            $urlParams = ['act' => 'lichchieu', 'date' => $dateItem['date']];
            if (!empty($searchKeyword)) $urlParams['search'] = $searchKeyword;
            if (!empty($cinemaId)) $urlParams['cinema'] = $cinemaId;
            if (!empty($movieId)) $urlParams['movie'] = $movieId;
            $url = BASE_URL . '?' . http_build_query($urlParams);
            ?>
            <a href="<?= $url ?>" class="pill <?= $isActive ?>"
                data-date="<?= $dateItem['date'] ?>">
                <?= htmlspecialchars($dateItem['formatted']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="lichchieu-layout">
        <!-- Sidebar Rạp bên trái -->
        <aside class="cinema-sidebar">
            <h3>Rạp</h3>
            <ul class="cinema-list">
                <?php if (!empty($cinemas)): ?>
                    <?php foreach ($cinemas as $cinema): 
                        $isSelected = ($cinemaId == $cinema['id']);
                        $hasShowtime = !empty($cinema['has_showtime']);
                        
                        // URL khi click rạp
                        $urlParams = ['act' => 'lichchieu', 'date' => $selectedDate];
                        if (!empty($movieId)) $urlParams['movie'] = $movieId;
                        $urlParams['cinema'] = $cinema['id'];
                        $cinemaUrl = BASE_URL . '?' . http_build_query($urlParams);
                    ?>
                        <li class="cinema-item <?= $isSelected ? 'active' : '' ?> <?= !$hasShowtime ? 'disabled' : '' ?>"
                            <?php if ($hasShowtime): ?>
                                onclick="window.location.href='<?= $cinemaUrl ?>'"
                            <?php endif; ?>>
                            <?= htmlspecialchars($cinema['name']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li style="color: rgba(255, 255, 255, 0.5); padding: 12px;">Chưa có rạp nào</li>
                <?php endif; ?>
            </ul>
        </aside>

        <!-- Nội dung Phim bên phải -->
        <div class="movies-content">
            <div class="movies-content-header">
                <h2><span class="dot" aria-hidden="true"></span> Phim đang chiếu</h2>
            </div>

            <section class="movies-grid" aria-live="polite" id="moviesGrid">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): 
                // Chỉ cho phép click vào phim khi đã chọn rạp
                // Nếu có movie_id thì BẮT BUỘC phải có cinema_id
                // Nếu không có movie_id thì có thể click (hiển thị tất cả phim)
                $canClick = !empty($cinemaId);
                $movieUrl = $canClick ? BASE_URL . '?act=movies&id=' . $movie['id'] . '&cinema=' . $cinemaId . '&date=' . $selectedDate : 'javascript:void(0);';
            ?>
                <article class="movie-card <?= !$canClick ? 'movie-disabled' : '' ?>">
                    <a href="<?= $movieUrl ?>" class="poster" 
                       title="<?= $canClick ? 'Xem chi tiết phim ' . htmlspecialchars($movie['title']) : 'Vui lòng chọn rạp trước' ?>"
                       <?= !$canClick ? 'onclick="event.preventDefault(); alert(\'Vui lòng chọn rạp trước khi xem chi tiết phim\'); return false;"' : '' ?>>
                        <?php if (!empty($movie['image'])): ?>
                            <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>"
                                alt="<?= htmlspecialchars($movie['title']) ?> - Poster">
                        <?php else: ?>
                            <img src="<?= BASE_URL ?>/image/logo.png"
                                alt="<?= htmlspecialchars($movie['title']) ?> - Poster">
                        <?php endif; ?>
                    </a>

                    <div class="info">
                        <a href="<?= $movieUrl ?>" class="info-link" 
                           title="<?= $canClick ? 'Xem chi tiết phim ' . htmlspecialchars($movie['title']) : 'Vui lòng chọn rạp trước' ?>"
                           <?= !$canClick ? 'onclick="event.preventDefault(); alert(\'Vui lòng chọn rạp trước khi xem chi tiết phim\'); return false;"' : '' ?>>
                            <div class="badge-2d">
                                <?= !empty($movie['format']) ? htmlspecialchars($movie['format']) : '2D' ?>
                            </div>
                            <div class="meta-top">
                                <div class="tags">
                                    <?= !empty($movie['genre_name']) ? htmlspecialchars($movie['genre_name']) : '—' ?>
                                    <?php if (!empty($movie['duration'])): ?>
                                        · <?= htmlspecialchars($movie['duration']) ?> phút
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="title">
                                <?= strtoupper(htmlspecialchars($movie['title'])) ?>
                                <?php if (!empty($movie['age_rating'])): ?>
                                    - <?= htmlspecialchars($movie['age_rating']) ?>
                                <?php endif; ?>
                            </div>

                            <div class="meta-lines">
                                <div>
                                    Xuất xứ:
                                    <?= !empty($movie['original_language']) ? htmlspecialchars($movie['original_language']) : '—' ?>
                                </div>
                                <div>
                                    Khởi chiếu:
                                    <?= !empty($movie['release_date']) ? date('d/m/Y', strtotime($movie['release_date'])) : '—' ?>
                                </div>
                            </div>

                            <?php if (!empty($movie['age_rating'])): ?>
                                <div class="censor">
                                    <?= htmlspecialchars($movie['age_rating']) ?> - Phim được phổ biến đến
                                    người xem từ đủ <?= str_replace('T', '', $movie['age_rating']) ?> tuổi trở lên
                                </div>
                            <?php endif; ?>
                        </a>

                        <div class="divider" aria-hidden="true"></div>

                        <div class="showtimes" role="group" aria-label="Lịch chiếu">
                            <?php if (!empty($movie['showtimes']) && !empty($movie['showtime_ids'])): ?>
                                <?php
                                foreach ($movie['showtimes'] as $index => $time):
                                    $showtimeId = $movie['showtime_ids'][$index] ?? null;
                                    if (!$showtimeId) continue;
                                    // Link đến trang chi tiết phim với ngày và rạp đã chọn
                                    $movieDetailUrl = $canClick ? BASE_URL . '?act=movies&id=' . $movie['id'] . '&date=' . $selectedDate . '&cinema=' . $cinemaId : 'javascript:void(0);';
                                    ?>
                                    <a class="time-btn" href="<?= $movieDetailUrl ?>"
                                        title="<?= $canClick ? 'Xem chi tiết phim và chọn ghế cho suất chiếu ' . date('H:i', strtotime($time)) : 'Vui lòng chọn rạp trước' ?>"
                                        <?= !$canClick ? 'onclick="event.preventDefault(); alert(\'Vui lòng chọn rạp trước khi xem chi tiết phim\'); return false;"' : '' ?>
                                        style="<?= !$canClick ? 'opacity: 0.5; cursor: not-allowed;' : '' ?>">
                                        <?= date('H:i', strtotime($time)) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Chưa có lịch chiếu</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div
                style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: rgba(255, 255, 255, 0.6);">
                <i class="bi bi-calendar-x" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                <p style="font-size: 18px;">Không có lịch chiếu cho ngày này</p>
                <p style="font-size: 14px; margin-top: 8px;">Vui lòng chọn ngày khác</p>
            </div>
        <?php endif; ?>
            </section>
        </div>
    </div>
</div>

<script>
    // Lấy tham số từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('movie') || '';
    const cinemaId = urlParams.get('cinema') || '';
    const cinemasWithMovie = <?= !empty($cinemasWithMovie) ? json_encode(array_column($cinemasWithMovie, 'id')) : '[]' ?>;

    // Disable các link phim khi chưa chọn rạp
    document.addEventListener('DOMContentLoaded', function() {
        // Nếu có movie_id thì BẮT BUỘC phải có cinema_id
        // Nếu không có cinema_id thì disable tất cả các link phim
        if (!cinemaId) {
            const movieCards = document.querySelectorAll('.movie-card');
            movieCards.forEach(card => {
                if (card.classList.contains('movie-disabled')) {
                    const links = card.querySelectorAll('a');
                    links.forEach(link => {
                        // Đảm bảo tất cả các link đều bị disable
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Vui lòng chọn rạp trước khi xem chi tiết phim');
                            return false;
                        }, true); // Use capture phase để chắc chắn
                    });
                }
            });
        }
    });

    // Smooth scroll khi click vào ngày
    document.querySelectorAll('.date-pills .pill').forEach(function (pill) {
        pill.addEventListener('click', function (e) {
            // Thêm class active và xóa class active của các pill khác
            document.querySelectorAll('.date-pills .pill').forEach(function (p) {
                p.classList.remove('active');
            });
            this.classList.add('active');

            // Scroll lên đầu trang để xem kết quả
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
