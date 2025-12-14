<?php
$allPromotions = $allPromotions ?? [];
$movieSpecificPromotions = $movieSpecificPromotions ?? [];
$otherPromotions = $otherPromotions ?? [];
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

    <section class="promo-carousels" id="promoList">
        <div class="section-header">
            <div>
                <p class="eyebrow">Ưu đãi nổi bật</p>
                <h2>Chọn khuyến mãi phù hợp</h2>
            </div>
            <div class="filter-note">
                <span class="dot live"></span> Cập nhật theo giờ cao điểm đặt vé
            </div>
        </div>

        <!-- Carousel 1: Tất cả mã giảm giá -->
        <?php if (!empty($allPromotions)): ?>
            <div class="carousel-section">
                <h3 class="carousel-title">Tất cả ưu đãi</h3>
                <div class="carousel-wrapper">
                    <button class="carousel-arrow carousel-arrow-left" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="carousel-container" data-carousel="all">
                        <div class="carousel-track">
                            <?php foreach ($allPromotions as $promo): ?>
                                <?php include __DIR__ . '/_promo_card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-arrow carousel-arrow-right" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Carousel 2: Mã giảm giá cho phim cụ thể -->
        <?php if (!empty($movieSpecificPromotions)): ?>
            <div class="carousel-section">
                <h3 class="carousel-title">Ưu đãi phim cụ thể</h3>
                <div class="carousel-wrapper">
                    <button class="carousel-arrow carousel-arrow-left" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="carousel-container" data-carousel="movie">
                        <div class="carousel-track">
                            <?php foreach ($movieSpecificPromotions as $promo): ?>
                                <?php include __DIR__ . '/_promo_card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-arrow carousel-arrow-right" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Carousel 3: Các mã giảm giá khác -->
        <?php if (!empty($otherPromotions)): ?>
            <div class="carousel-section">
                <h3 class="carousel-title">Ưu đãi tổng quát</h3>
                <div class="carousel-wrapper">
                    <button class="carousel-arrow carousel-arrow-left" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="carousel-container" data-carousel="other">
                        <div class="carousel-track">
                            <?php foreach ($otherPromotions as $promo): ?>
                                <?php include __DIR__ . '/_promo_card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-arrow carousel-arrow-right" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
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
                    <p>Ở bước thanh toán, dán mã hoặc chọn trực tiếp trong "Ví mã giảm".</p>
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
    // Carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        const carousels = document.querySelectorAll('.carousel-container');

        carousels.forEach(carousel => {
            const track = carousel.querySelector('.carousel-track');
            const wrapper = carousel.closest('.carousel-wrapper');
            const leftArrow = wrapper.querySelector('.carousel-arrow-left');
            const rightArrow = wrapper.querySelector('.carousel-arrow-right');
            const cards = track.querySelectorAll('.promo-card');

            if (cards.length === 0) return;

            let currentIndex = 0;
            let isDragging = false;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let animationID = 0;

            const getCardWidth = () => {
                if (cards.length === 0) return 0;
                const card = cards[0];
                const cardStyle = window.getComputedStyle(card);
                const cardWidth = card.offsetWidth;
                const gap = parseInt(window.getComputedStyle(track).gap) || 24;
                return cardWidth + gap;
            };

            const getMaxIndex = () => {
                const cardWidth = getCardWidth();
                const containerWidth = carousel.offsetWidth;
                const visibleCards = Math.floor(containerWidth / cardWidth);
                return Math.max(0, cards.length - visibleCards);
            };

            // Arrow navigation
            const updateArrows = () => {
                const maxIndex = getMaxIndex();
                if (leftArrow) {
                    leftArrow.style.opacity = currentIndex > 0 ? '1' : '0.3';
                    leftArrow.style.pointerEvents = currentIndex > 0 ? 'auto' : 'none';
                }
                if (rightArrow) {
                    rightArrow.style.opacity = currentIndex < maxIndex ? '1' : '0.3';
                    rightArrow.style.pointerEvents = currentIndex < maxIndex ? 'auto' : 'none';
                }
            };

            const setPosition = () => {
                const cardWidth = getCardWidth();
                currentTranslate = -currentIndex * cardWidth;
                track.style.transform = `translateX(${currentTranslate}px)`;
            };

            const scrollToIndex = (index) => {
                const maxIndex = getMaxIndex();
                currentIndex = Math.max(0, Math.min(index, maxIndex));
                setPosition();
                updateArrows();
            };

            if (leftArrow) {
                leftArrow.addEventListener('click', () => {
                    scrollToIndex(currentIndex - 1);
                });
            }

            if (rightArrow) {
                rightArrow.addEventListener('click', () => {
                    scrollToIndex(currentIndex + 1);
                });
            }

            // Drag functionality
            const getPositionX = (event) => {
                return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
            };

            const dragStart = (event) => {
                startX = getPositionX(event);
                isDragging = true;
                track.style.cursor = 'grabbing';
                track.style.transition = 'none';
                prevTranslate = currentTranslate;
                animationID = requestAnimationFrame(animation);
            };

            const drag = (event) => {
                if (!isDragging) return;
                const currentPosition = getPositionX(event);
                const moved = currentPosition - startX;
                currentTranslate = prevTranslate + moved;
            };

            const dragEnd = () => {
                if (!isDragging) return;
                cancelAnimationFrame(animationID);
                isDragging = false;
                track.style.cursor = 'grab';
                track.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';

                const cardWidth = getCardWidth();
                const movedBy = -currentTranslate / cardWidth;
                const roundedIndex = Math.round(movedBy);
                scrollToIndex(roundedIndex);
                prevTranslate = currentTranslate;
            };

            const animation = () => {
                if (isDragging) {
                    track.style.transform = `translateX(${currentTranslate}px)`;
                    requestAnimationFrame(animation);
                }
            };

            track.addEventListener('mousedown', dragStart);
            track.addEventListener('touchstart', dragStart);
            track.addEventListener('mousemove', drag);
            track.addEventListener('touchmove', drag);
            track.addEventListener('mouseup', dragEnd);
            track.addEventListener('mouseleave', dragEnd);
            track.addEventListener('touchend', dragEnd);

            // Prevent image drag
            track.addEventListener('dragstart', (e) => e.preventDefault());

            // Initialize
            const initCardWidth = getCardWidth();
            currentTranslate = -currentIndex * initCardWidth;
            prevTranslate = currentTranslate;
            setPosition();
            updateArrows();

            // Update on resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    setPosition();
                    updateArrows();
                }, 250);
            });
        });
    });

    // Copy code functionality
    document.querySelectorAll('.code-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const code = this.dataset.code;
            if (!code || this.disabled) return;
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

    document.querySelectorAll('[data-scroll]').forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.scroll);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
</script>