<?php
// _PROMO_CARD.PHP - COMPONENT CARD KHUYẾN MÃI
// Chức năng: Component hiển thị card khuyến mãi (được include trong khuyenmai.php)
// Biến: $promo (thông tin khuyến mãi), $statusLabels (nhãn trạng thái)
$tag = htmlspecialchars($promo['tag']);
$status = htmlspecialchars($promo['display_status'] ?? $promo['status'] ?? 'ongoing');
$code = !empty($promo['code']) ? htmlspecialchars($promo['code']) : '';
$isMovieSpecific = !empty($promo['movie_id']);
$movieImage = !empty($promo['movie_image']) ? htmlspecialchars($promo['movie_image']) : null;
$movieTitle = !empty($promo['movie_title']) ? htmlspecialchars($promo['movie_title']) : null;
$isActive = ($status === 'ongoing' || $status === 'upcoming');
?>
<article class="promo-card <?= $isMovieSpecific ? 'promo-card-with-movie' : '' ?> <?= !$isActive ? 'promo-card-inactive' : '' ?>" data-status="<?= $status ?>">
    <?php if ($isMovieSpecific && $movieImage): ?>
        <div class="promo-movie-image">
            <img src="<?= BASE_URL . '/' . $movieImage ?>"
                alt="<?= $movieTitle ? $movieTitle . ' - Poster' : 'Movie Poster' ?>"
                title="<?= $movieTitle ? 'Mã giảm giá cho phim: ' . $movieTitle : '' ?>">
        </div>
    <?php endif; ?>
    <div class="promo-card-content">
        <div class="card-head">
            <span class="promo-tag tag-<?= $tag ?>"><?= $isMovieSpecific ? 'Phim cụ thể' : 'Tổng quát' ?></span>
            <span class="promo-status status-<?= $status ?>">
                <?= $statusLabels[$status] ?? 'Ưu đãi' ?>
            </span>
        </div>
        <h3><?= htmlspecialchars($promo['title']) ?></h3>
        <p class="desc"><?= htmlspecialchars($promo['description']) ?></p>
        <ul class="benefits">
            <?php if (!empty($promo['benefits']) && is_array($promo['benefits'])): ?>
                <?php foreach ($promo['benefits'] as $benefit): ?>
                    <?php if (!empty(trim($benefit))): ?>
                        <li>
                            <i class="bi bi-check2-circle"></i>
                            <span><?= htmlspecialchars(trim($benefit)) ?></span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <div class="meta">
            <span class="period">
                <i class="bi bi-calendar-event"></i>
                <?= htmlspecialchars($promo['period'] ?? 'Áp dụng thường xuyên') ?>
            </span>
            <?php if (!empty($promo['usage_label'])): ?>
                <span class="usage">
                    <i class="bi bi-ticket-perforated"></i>
                    <?= htmlspecialchars($promo['usage_label']) ?>
                </span>
            <?php endif; ?>
            <?php if (!empty($code) && $status === 'ongoing'): ?>
                <button class="code-btn" data-code="<?= $code ?>">
                    <span><?= $code ?></span>
                    <i class="bi bi-copy"></i>
                </button>
            <?php elseif (!empty($code)): ?>
                <button class="code-btn" disabled style="opacity: 0.5; cursor: not-allowed;" title="Mã này không thể sử dụng">
                    <span><?= $code ?></span>
                    <i class="bi bi-lock"></i>
                </button>
            <?php endif; ?>
        </div>
        <div class="promo-actions">
            <?php if ($status === 'ongoing'): ?>
                <a class="btn-primary" href="<?= !empty($promo['cta_link']) ? htmlspecialchars($promo['cta_link']) : BASE_URL . '?act=datve' ?>">
                    <?= htmlspecialchars($promo['cta'] ?? 'Sử dụng mã') ?>
                </a>
                <span class="hint">Áp dụng tự động khi nhập mã ở bước thanh toán</span>
            <?php elseif ($status === 'upcoming'): ?>
                <button class="btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                    <?= htmlspecialchars($promo['cta'] ?? 'Sắp diễn ra') ?>
                </button>
                <span class="hint">Chúng tôi sẽ gửi thông báo khi ưu đãi mở</span>
            <?php else: ?>
                <button class="btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                    Đã kết thúc
                </button>
                <span class="hint">Mã khuyến mãi này đã hết hạn</span>
            <?php endif; ?>
        </div>
    </div>
</article>