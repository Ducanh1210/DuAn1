<?php
$promotions = $promotions ?? [];
$membershipBenefits = $membershipBenefits ?? [];
$faqs = $faqs ?? [];
$heroStats = $heroStats ?? [];

$statusLabels = [
    'ongoing' => 'Đang diễn ra',
    'upcoming' => 'Sắp diễn ra',
    'ended' => 'Đã kết thúc'
];
?>

<div class="promo-page">
    <section class="promo-hero">
        <div>
            <p class="eyebrow">TicketHub ưu đãi mỗi tuần</p>
            <h1>Khuyến mãi nóng - nhận mã trong 1 chạm</h1>
            <p class="lead">
                Tổng hợp ưu đãi vé xem phim, combo bắp nước và quyền lợi thành viên mới nhất.
                Đặt vé online, nhập mã ngay phần thanh toán để nhận mức giảm cao nhất.
            </p>
            <div class="hero-cta">
                <a class="btn-primary" href="#promoList">Xem ưu đãi</a>
                <button class="btn-outline" type="button" data-scroll="#promoNewsletter">Nhận thông báo</button>
            </div>
        </div>
        <div class="hero-stats">
            <?php foreach ($heroStats as $stat): ?>
                <div class="stat-card">
                    <span class="value"><?= htmlspecialchars($stat['value']) ?></span>
                    <span class="label"><?= htmlspecialchars($stat['label']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="promo-grid" id="promoList">
        <div class="section-header">
            <div>
                <p class="eyebrow">Ưu đãi nổi bật</p>
                <h2>Chọn khuyến mãi phù hợp</h2>
            </div>
            <div class="filter-note">
                <span class="dot live"></span> Cập nhật theo giờ cao điểm đặt vé
            </div>
        </div>

        <div class="grid">
            <?php foreach ($promotions as $promo): ?>
                <?php
                $tag = htmlspecialchars($promo['tag']);
                $status = htmlspecialchars($promo['display_status'] ?? $promo['status'] ?? 'ongoing');
                $code = !empty($promo['code']) ? htmlspecialchars($promo['code']) : '';
                ?>
                <article class="promo-card" data-status="<?= $status ?>">
                    <div class="card-head">
                        <span class="promo-tag tag-<?= $tag ?>"><?= ucfirst($tag) ?></span>
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
                                <?= htmlspecialchars($promo['cta'] ?? 'Đặt vé ngay') ?>
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
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="membership">
        <div class="section-header">
            <div>
                <p class="eyebrow">Thẻ thành viên TicketHub</p>
                <h2>Tăng hạng để mở khóa ưu đãi riêng</h2>
            </div>
            <a href="<?= BASE_URL ?>?act=profile&tab=membership" class="link-action">
                Xem bảng quyền lợi
                <i class="bi bi-arrow-up-right"></i>
            </a>
        </div>

        <div class="benefit-grid">
            <?php foreach ($membershipBenefits as $benefit): ?>
                <article class="benefit-card">
                    <div class="icon-wrap">
                        <i class="bi <?= htmlspecialchars($benefit['icon']) ?>"></i>
                    </div>
                    <h3><?= htmlspecialchars($benefit['title']) ?></h3>
                    <p><?= htmlspecialchars($benefit['desc']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="how-to">
        <div class="section-header">
            <div>
                <p class="eyebrow">3 bước sử dụng ưu đãi</p>
                <h2>Áp dụng nhanh trên mọi thiết bị</h2>
            </div>
        </div>
        <ol class="steps">
            <li>
                <span class="step-number">1</span>
                <div>
                    <h3>Chọn suất & ghế yêu thích</h3>
                    <p>Truy cập mục Lịch chiếu hoặc chi tiết phim để đặt vé trực tuyến.</p>
                </div>
            </li>
            <li>
                <span class="step-number">2</span>
                <div>
                    <h3>Nhập mã khuyến mãi</h3>
                    <p>Ở bước thanh toán, dán mã hoặc chọn trực tiếp trong “Ví mã giảm”.</p>
                </div>
            </li>
            <li>
                <span class="step-number">3</span>
                <div>
                    <h3>Nhận vé và tích điểm</h3>
                    <p>Vé điện tử gửi về email/app, điểm thưởng cộng ngay sau khi thanh toán.</p>
                </div>
            </li>
        </ol>
    </section>

    <section class="faq">
        <div class="section-header">
            <div>
                <p class="eyebrow">Giải đáp nhanh</p>
                <h2>Câu hỏi thường gặp</h2>
            </div>
        </div>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
                <article class="faq-item">
                    <h3><?= htmlspecialchars($faq['question']) ?></h3>
                    <p><?= htmlspecialchars($faq['answer']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="newsletter" id="promoNewsletter">
        <div class="newsletter-card">
            <div>
                <p class="eyebrow">Không bỏ lỡ ưu đãi</p>
                <h2>Nhận email thông báo khuyến mãi</h2>
                <p>Chúng tôi gửi tối đa 2 email/tuần với mã độc quyền dành riêng cho bạn.</p>
            </div>
            <form class="newsletter-form" onsubmit="return false;">
                <input type="email" placeholder="Email của bạn" aria-label="Email">
                <button type="submit">Đăng ký</button>
            </form>
        </div>
    </section>
</div>

<script>
    document.querySelectorAll('.code-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const code = this.dataset.code;
            navigator.clipboard.writeText(code).then(() => {
                this.classList.add('copied');
                this.innerHTML = `<span>Đã sao chép</span><i class="bi bi-check-lg"></i>`;
                setTimeout(() => {
                    this.classList.remove('copied');
                    this.innerHTML = `<span>${code}</span><i class="bi bi-copy"></i>`;
                }, 2000);
            });
        });
    });

    document.querySelectorAll('[data-scroll]').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const target = document.querySelector(this.dataset.scroll);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>

