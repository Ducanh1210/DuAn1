<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng Nhập | TicketHub</title>
        <link rel="stylesheet" href="<?= BASE_URL ?>/views/layout/css/dangnhap.css" />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@300;400;500&display=swap"
            rel="stylesheet" />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    </head>
    <body>
        <div class="wrap" role="main" aria-labelledby="login-title">

            <!-- LEFT: BRAND / PROMO -->
            <aside class="panel-brand" aria-hidden="false">
                <div class="brand-top">
                    <div class="brand-logo" aria-hidden="true"><img
                            src="<?= BASE_URL ?>/image/logokhongnen.png" alt="TicketHub"></div>
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

            <!-- RIGHT: AUTH FORM -->
            <section class="auth" aria-labelledby="login-title">
                <h2 id="login-title">Đăng nhập vào TicketHub</h2>
                
                <?php if (isset($_GET['registered']) && $_GET['registered'] == '1'): ?>
                    <div class="alert alert-success" style="padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; background: rgba(45, 212, 191, 0.1); border: 1px solid rgba(45, 212, 191, 0.3); color: #2dd4bf;">
                        <i class="bi bi-check-circle"></i>
                        <div>
                            <strong>Đăng ký thành công!</strong>
                            <p style="margin: 4px 0 0 0; font-size: 14px;">Vui lòng đăng nhập với tài khoản vừa tạo.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error" style="padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; background: rgba(255, 75, 75, 0.1); border: 1px solid rgba(255, 75, 75, 0.3); color: #ff6a6a;">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?php if (isset($errors['login'])): ?>
                            <span><?= htmlspecialchars($errors['login']) ?></span>
                        <?php else: ?>
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <!-- email / phone -->
                    <div class="field">
                        <label for="email">Email</label>
                        <div class="input" role="group"
                            aria-labelledby="email">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true"><path
                                    d="M3 7.5A3.5 3.5 0 016.5 4h11A3.5 3.5 0 0121 7.5V16a3.5 3.5 0 01-3.5 3.5h-11A3.5 3.5 0 013 16V7.5z"
                                    stroke="rgba(255,255,255,0.16)"
                                    stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round" /></svg>
                            <input id="email" name="email" type="email"
                                autocomplete="username"
                                placeholder="Email"
                                value="<?= htmlspecialchars($_POST['email'] ?? ($_GET['email'] ?? '')) ?>"
                                required aria-required="true"
                                class="<?= !empty($errors['email']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['email']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                        </div>
                        <?php if (!empty($errors['email'])): ?>
                            <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['email']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- password -->
                    <div class="field">
                        <label for="password">Mật khẩu</label>
                        <div class="input" style="position:relative;">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true"><path
                                    d="M17 11V9a5 5 0 00-10 0v2"
                                    stroke="rgba(255,255,255,0.16)"
                                    stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round" /><rect x="3" y="11"
                                    width="18" height="10" rx="2"
                                    stroke="rgba(255,255,255,0.16)"
                                    stroke-width="1.2" /></svg>
                            <input id="password" name="password" type="password"
                                autocomplete="current-password"
                                placeholder="Mật khẩu" required
                                aria-required="true" minlength="6"
                                class="<?= !empty($errors['password']) ? 'error' : '' ?>"
                                style="<?= !empty($errors['password']) ? 'border-color: rgba(255, 75, 75, 0.5) !important;' : '' ?>">
                        </div>
                        <?php if (!empty($errors['password'])): ?>
                            <span style="color: #ff6a6a; font-size: 12px; margin-top: 4px; display: block;"><?= htmlspecialchars($errors['password']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- meta row -->
                    <div class="meta-row">
                        <a href="#" class="forgot">Quên mật khẩu?</a>
                    </div>

                    <!-- submit -->
                    <div class="actions">
                        <button class="btn btn-primary" type="submit">Đăng
                            nhập</button>
                    </div>
                    <div class="foot">Chưa có tài khoản? <a href="<?= BASE_URL ?>?act=dangky"
                            style="color:#9ad7ff;text-decoration:underline">Đăng ký</a></div>
                </form>
            </section>
        </div>
    </body>
</html>