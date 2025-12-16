<?php
// LIENHE.PHP - TRANG LIÊN HỆ CLIENT
// Chức năng: Form liên hệ gửi tin nhắn cho admin
// Biến từ controller: $success (thành công), $error (lỗi), $formData (dữ liệu form), $cinemas (danh sách rạp)
$success = $success ?? false;
$error = $error ?? '';
$formData = $formData ?? [];
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/lienhe.css">

<div class="contact-page">
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Liên Hệ Với Chúng Tôi</h1>
            <p class="hero-subtitle">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="contact-container">
        <div class="contact-wrapper">
            <!-- Contact Form Section -->
            <div class="contact-form-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="bi bi-envelope-paper"></i>
                        Gửi Tin Nhắn
                    </h2>
                    <p class="section-description">
                        Điền form bên dưới để gửi tin nhắn cho chúng tôi. Bạn sẽ được yêu cầu đăng nhập khi nhấn nút "Gửi".
                    </p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>Cảm ơn bạn!</strong> Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi sớm nhất có thể.
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Lỗi!</strong> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">
                                <i class="bi bi-person"></i>
                                Họ và Tên <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-control" 
                                placeholder="Nhập họ và tên của bạn"
                                value="<?= htmlspecialchars($formData['name'] ?? ($currentUser['full_name'] ?? '')) ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="bi bi-envelope"></i>
                                Email <span class="required">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control" 
                                placeholder="your.email@example.com"
                                value="<?= htmlspecialchars($formData['email'] ?? ($currentUser['email'] ?? '')) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">
                                <i class="bi bi-telephone"></i>
                                Số Điện Thoại <span class="required">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="form-control" 
                                placeholder="0123 456 789"
                                value="<?= htmlspecialchars($formData['phone'] ?? ($currentUser['phone'] ?? '')) ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="cinema_id">
                                <i class="bi bi-building"></i>
                                Chọn Rạp <span class="required">*</span>
                            </label>
                            <select 
                                id="cinema_id" 
                                name="cinema_id" 
                                class="form-control" 
                                required
                            >
                                <option value="">-- Chọn rạp --</option>
                                <?php if (!empty($cinemas)): ?>
                                    <?php foreach ($cinemas as $cinema): ?>
                                        <option value="<?= $cinema['id'] ?>" <?= (isset($formData['cinema_id']) && $formData['cinema_id'] == $cinema['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cinema['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            <i class="bi bi-tag"></i>
                            Chủ Đề <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            class="form-control" 
                            placeholder="Ví dụ: Hỏi về giá vé, Đặt vé..."
                            value="<?= htmlspecialchars($formData['subject'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="message">
                            <i class="bi bi-chat-left-text"></i>
                            Nội Dung Tin Nhắn <span class="required">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            class="form-control" 
                            rows="6" 
                            placeholder="Nhập nội dung tin nhắn của bạn..."
                            required
                        ><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-send"></i>
                        <span>Gửi Tin Nhắn</span>
                    </button>
                </form>
            </div>

            <!-- Contact Info Section -->
            <div class="contact-info-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="bi bi-info-circle"></i>
                        Thông Tin Liên Hệ
                    </h2>
                    <p class="section-description">Các cách khác để liên hệ với chúng tôi</p>
                </div>

                <div class="contact-cards">
                    <!-- Address Card -->
                    <div class="contact-card">
                        <div class="card-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">Địa Chỉ</h3>
                            <p class="card-text">
                                Số 87 Láng Hạ<br>
                                Phường Ô Chợ Dừa<br>
                                Quận Đống Đa, TP. Hà Nội
                            </p>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="contact-card">
                        <div class="card-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">Điện Thoại</h3>
                            <p class="card-text">
                                <a href="tel:02435141791">024.3514.1791</a><br>
                                <a href="tel:19001234">1900.1234</a> (Hotline)
                            </p>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="contact-card">
                        <div class="card-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">Email</h3>
                            <p class="card-text">
                                <a href="mailto:info@tickethub.vn">info@tickethub.vn</a><br>
                                <a href="mailto:support@tickethub.vn">support@tickethub.vn</a>
                            </p>
                        </div>
                    </div>

                    <!-- Hours Card -->
                    <div class="contact-card">
                        <div class="card-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">Giờ Làm Việc</h3>
                            <p class="card-text">
                                Thứ 2 - Chủ Nhật: 8:00 - 22:00<br>
                                Hotline: 24/7
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="social-section">
                    <h3 class="social-title">Theo Dõi Chúng Tôi</h3>
                    <div class="social-links">
                        <a href="#" class="social-link facebook" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-link zalo" aria-label="Zalo">
                            <i class="bi bi-chat-dots"></i>
                            <span>Zalo</span>
                        </a>
                        <a href="#" class="social-link youtube" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                            <span>YouTube</span>
                        </a>
                        <a href="#" class="social-link instagram" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                            <span>Instagram</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-map"></i>
                    Vị Trí Của Chúng Tôi
                </h2>
            </div>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0966996788!2d105.8123153154305!3d21.028593785998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9b8c5e3b1b%3A0x5e3b1b8c5e3b1b8c!2zODcgTMOibiBI4buNLCBQaMaw4buNbmcgw7QgQ2jhu6cgRMO0YSwgxJDhu5NuZyDEkOG7qWMsIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s"
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Bản đồ vị trí TicketHub"
                ></iframe>
            </div>
        </div>
    </div>
</div>

<style>
/* Đảm bảo màu trắng cho text khi đang gõ - override tất cả CSS khác */
.contact-form-section input,
.contact-form-section textarea,
.contact-form-section select,
.contact-form-section input.form-control,
.contact-form-section textarea.form-control,
.contact-form-section select.form-control,
.contact-form input,
.contact-form textarea,
.contact-form select {
    color: #ffffff !important;
}

.contact-form-section input[type="text"],
.contact-form-section input[type="email"],
.contact-form-section input[type="tel"],
.contact-form-section textarea,
.contact-form-section select {
    color: #ffffff !important;
}

.contact-form-section input:focus,
.contact-form-section textarea:focus,
.contact-form-section select:focus,
.contact-form-section input.form-control:focus,
.contact-form-section textarea.form-control:focus,
.contact-form-section select.form-control:focus {
    color: #ffffff !important;
}

/* Override dark mode - chữ trắng */
[data-theme="dark"] .contact-form-section input,
[data-theme="dark"] .contact-form-section textarea,
[data-theme="dark"] .contact-form-section select {
    color: #ffffff !important;
}

[data-theme="dark"] .contact-form-section input:focus,
[data-theme="dark"] .contact-form-section textarea:focus,
[data-theme="dark"] .contact-form-section select:focus {
    color: #ffffff !important;
}

/* Light mode - chữ đen */
[data-theme="light"] .contact-form-section input,
[data-theme="light"] .contact-form-section textarea,
[data-theme="light"] .contact-form-section select,
[data-theme="light"] .contact-form-section input.form-control,
[data-theme="light"] .contact-form-section textarea.form-control,
[data-theme="light"] .contact-form-section select.form-control {
    color: #000000 !important;
    background: #ffffff !important;
}

[data-theme="light"] .contact-form-section input:focus,
[data-theme="light"] .contact-form-section textarea:focus,
[data-theme="light"] .contact-form-section select:focus {
    color: #000000 !important;
    background: #ffffff !important;
}

[data-theme="light"] .contact-form-section select option {
    background: #ffffff !important;
    color: #000000 !important;
}

/* Style cho dropdown select - nền tối */
.contact-form-section select,
.contact-form-section select.form-control {
    background: rgba(255, 255, 255, 0.08) !important;
    background-color: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
}

.contact-form-section select option {
    background: #1a1a1a !important;
    background-color: #1a1a1a !important;
    color: #ffffff !important;
}

.contact-form-section select option:checked,
.contact-form-section select option:hover {
    background: #2a2a2a !important;
    background-color: #2a2a2a !important;
    color: #ffffff !important;
}

/* Override Bootstrap dropdown nếu có */
.contact-form-section select.form-control option {
    background: #1a1a1a !important;
    color: #ffffff !important;
}
</style>

<script>
// Form validation và UX improvements
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    // Hàm để set màu chữ dựa trên theme
    function setInputColor() {
        const isLightMode = document.body.getAttribute('data-theme') === 'light';
        const textColor = isLightMode ? '#000000' : '#ffffff';
        
        inputs.forEach(input => {
            input.style.color = textColor;
        });
    }
    
    // Set màu ban đầu
    setInputColor();
    
    // Đảm bảo màu đúng khi đang gõ
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            setInputColor();
        });
        
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
            setInputColor();
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
            setInputColor();
        });
        
        // Check if input has value on load
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });
    
    // Theo dõi thay đổi theme
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                setInputColor();
            }
        });
    });
    
    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Đang gửi...</span>';
        
        // Re-enable after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 3000);
    });
});
</script>

