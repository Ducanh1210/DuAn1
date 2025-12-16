<?php
// DANGKY.PHP - TRANG ĐĂNG KÝ CLIENT
// Chức năng: Form đăng ký tài khoản mới (họ tên, email, password)
// Biến từ controller: $errors (lỗi validation)
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng Ký | TicketHub</title>
        <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/dangky.css" />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap"
            rel="stylesheet" />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    </head>
    <body>
        <div class="container" role="main" aria-labelledby="reg-title">
            <!-- LEFT: BRAND/BENEFITS - Panel giới thiệu bên trái -->
            <aside class="panel-brand" aria-hidden="false">
                <div class="brand-top">
                    <div class="brand-logo" aria-hidden="true"><img src="<?= BASE_URL ?>/image/logokhongnen.png" alt="TicketHub"></div>
                    <div>
                        <div class="brand-title">TICKETHUB</div>
                        <div class="brand-sub">Mua vé nhanh — Quản lý lịch sử —
                            Ưu đãi độc quyền</div>
                    </div>
                </div>

                <p class="small">Tạo tài khoản để nhận thông báo khuyến mãi và
                    đặt vé nhanh hơn. Bảo mật thông tin của bạn là ưu tiên hàng
                    đầu.</p>

                <div class="features" aria-hidden="true">
                    <div class="feature"><div
                            class="f-dot">1</div><div><strong>Thanh toán
                                nhanh</strong><div class="small">Nhiều phương
                                thức: thẻ, QR, ví điện tử</div></div></div>
                    <div class="feature"><div
                            class="f-dot">2</div><div><strong>Lưu vé & lịch
                                sử</strong><div class="small">Xem lại và quản lý
                                vé của bạn</div></div></div>
                    <div class="feature"><div
                            class="f-dot">3</div><div><strong>Ưu đãi đặc
                                biệt</strong><div class="small">Nhận mã giảm giá
                                khi đăng ký</div></div></div>
                </div>
            </aside>

            <!-- Form đăng ký -->
            <section class="panel-form" aria-labelledby="reg-title">
                <h2 id="reg-title">Tạo tài khoản mới</h2>
                <p class="lead">Nhanh chóng, an toàn — chỉ mất vài bước</p>

                <!-- Hiển thị lỗi validation -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error" style="padding: 14px 16px; border-radius: 10px; margin-bottom: 20px; background: rgba(255, 75, 75, 0.1); border: 1px solid rgba(255, 75, 75, 0.3); color: #ff6a6a;">
                        <i class="bi bi-exclamation-triangle"></i>
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form đăng ký: submit POST -->
                <form action="" method="POST">
                    <!-- Row 1: Họ tên + Ngày sinh -->
                    <div class="row">
                        <div class="col">
                            <label for="full_name">Họ và tên <span style="color: #ff6a6a;">*</span></label>
                            <input id="full_name" name="full_name" type="text"
                                placeholder="Nguyễn Văn A" 
                                value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                                required
                                class="<?= !empty($errors['full_name']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['full_name']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                            <?php if (!empty($errors['full_name'])): ?>
                                <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['full_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="col">
                            <label for="birth_date">Ngày sinh</label>
                            <input id="birth_date" name="birth_date" type="date"
                                placeholder="dd/mm/yyyy"
                                value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>"
                                class="<?= !empty($errors['birth_date']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['birth_date']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                            <?php if (!empty($errors['birth_date'])): ?>
                                <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['birth_date']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Row 2: Email + Số điện thoại -->
                    <div class="row">
                        <div class="col">
                            <label for="email">Email <span style="color: #ff6a6a;">*</span></label>
                            <input id="email" name="email" type="email"
                                placeholder="Email" 
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                required
                                class="<?= !empty($errors['email']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['email']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                            <?php if (!empty($errors['email'])): ?>
                                <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['email']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <label for="phone">Số điện thoại</label>
                            <input id="phone" name="phone" type="tel"
                                placeholder="09xxxxxxxx"
                                value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                class="<?= !empty($errors['phone']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['phone']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                            <?php if (!empty($errors['phone'])): ?>
                                <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['phone']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Row 3: Mật khẩu + Xác nhận mật khẩu -->
                    <div class="row">
                        <div class="col">
                            <label for="password">Mật khẩu <span style="color: #ff6a6a;">*</span></label>
                            <input id="password" name="password" type="password"
                                placeholder="Mật khẩu" minlength="6"
                                required
                                class="<?= !empty($errors['password']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['password']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                            <?php if (!empty($errors['password'])): ?>
                                <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['password']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <label for="confirm_password">Xác nhận mật khẩu <span style="color: #ff6a6a;">*</span></label>
                            <input id="confirm_password" name="confirm_password" type="password"
                                placeholder="Xác nhận mật khẩu" minlength="6"
                                required
                                class="<?= !empty($errors['confirm_password']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['confirm_password']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                            <?php if (!empty($errors['confirm_password'])): ?>
                                <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['confirm_password']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                   <!-- Nút submit -->
                   <div class="actions">
                        <button class="btn btn-primary" type="submit">Đăng Ký</button>
                    </div>
                    <!-- Link đăng nhập -->
                    <div class="foot">Đã có tài khoản? <a href="<?= BASE_URL ?>?act=dangnhap"
                            style="color:#9ad7ff;text-decoration:underline">Đăng nhập</a></div>
                </form>
            </section>
        </div>
    </body>
</html>