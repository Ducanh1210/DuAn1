<!-- Phần nội dung lịch chiếu -->
<div class="page">
    <header class="page-header" role="banner">
        <h2><span class="dot" aria-hidden="true"></span> Phim đang
            chiếu</h2>

        <div class="date-pills" role="navigation" aria-label="Chọn ngày">
            <?php foreach ($dates as $dateItem):
                $isActive = ($dateItem['date'] === $selectedDate) ? 'active' : '';
                // Giữ lại tham số search và cinema khi chuyển ngày
                $urlParams = ['act' => 'lichchieu', 'date' => $dateItem['date']];
                if (!empty($searchKeyword)) $urlParams['search'] = $searchKeyword;
                if (!empty($cinemaId)) $urlParams['cinema'] = $cinemaId;
                $url = BASE_URL . '?' . http_build_query($urlParams);
                ?>
                <a href="<?= $url ?>" class="pill <?= $isActive ?>"
                    data-date="<?= $dateItem['date'] ?>">
                    <?= htmlspecialchars($dateItem['formatted']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </header>

    <section class="movies-grid" aria-live="polite" id="moviesGrid">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): 
                $movieUrl = BASE_URL . '?act=movies&id=' . $movie['id'];
            ?>
                <article class="movie-card">
                    <a href="<?= $movieUrl ?>" class="poster" title="Xem chi tiết phim <?= htmlspecialchars($movie['title']) ?>">
                        <?php if (!empty($movie['image'])): ?>
                            <img src="<?= BASE_URL . '/' . htmlspecialchars($movie['image']) ?>"
                                alt="<?= htmlspecialchars($movie['title']) ?> - Poster">
                        <?php else: ?>
                            <img src="<?= BASE_URL ?>/image/logo.png"
                                alt="<?= htmlspecialchars($movie['title']) ?> - Poster">
                        <?php endif; ?>
                    </a>

                    <div class="info">
                        <a href="<?= $movieUrl ?>" class="info-link" title="Xem chi tiết phim <?= htmlspecialchars($movie['title']) ?>">
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
                            <?php if (!empty($movie['showtimes'])): ?>
                                <?php
                                foreach ($movie['showtimes'] as $index => $time):
                                    ?>
                                    <a class="time-btn" href="<?= $movieUrl ?>"
                                        title="Xem chi tiết phim <?= htmlspecialchars($movie['title']) ?>">
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

<script>
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
