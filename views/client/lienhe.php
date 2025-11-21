<?php
// views/client/lienhe.php
$errors = $errors ?? [];
$old = $old ?? [];
$success = $success ?? false;
?>

<section class="movie-hero" style="background: none; padding-top: 28px; padding-bottom: 28px;">
  <div class="container">
    <div class="hero-row" style="align-items:flex-start;">
      <div class="hero-meta" style="width:100%;">

        <div class="page-header" style="text-align:left; margin-bottom:18px;">
          <h2 class="title">
            Liên hệ
            <span class="dot" style="background:var(--accent);"></span>
          </h2>
          <p class="small" style="margin-top:8px; color:var(--muted-2);">
            Gửi cho chúng tôi thông điệp — chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.
          </p>
        </div>

        <div class="dates-wrap" style="padding:24px;">
          <?php if ($success): ?>
            <div class="alert" style="background: rgba(46, 204, 113, 0.08); border:1px solid rgba(46,204,113,0.18); padding:12px; color:#b8f2c6; margin-bottom:16px;">
              Cám ơn bạn! Thông điệp đã được gửi. Chúng tôi sẽ phản hồi sớm nhất có thể.
            </div>
          <?php endif; ?>

          <?php if (!empty($errors['general'])): ?>
            <div class="alert" style="background: rgba(255, 99, 71, 0.06); border:1px solid rgba(255,99,71,0.12); padding:12px; color:#ffd6d0; margin-bottom:16px;">
              <?= htmlspecialchars($errors['general']) ?>
            </div>
          <?php endif; ?>

          <form action="<?= (defined('BASE_URL') ? BASE_URL : '/') ?>?act=lienhe-submit" method="POST" novalidate>
            <div class="form-row" style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
              <div class="form-group">
                <label for="full_name" style="color:var(--muted-2); font-weight:600;">Họ và tên <span style="color:#ff6a6a">*</span></label>
                <input id="full_name" name="full_name" class="form-control" required
                       value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                       placeholder="Họ và tên">
                <?php if (!empty($errors['full_name'])): ?>
                  <small style="color:#ff8a8a; display:block; margin-top:6px;"><?= htmlspecialchars($errors['full_name']) ?></small>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="email" style="color:var(--muted-2); font-weight:600;">Email <span style="color:#ff6a6a">*</span></label>
                <input id="email" name="email" type="email" class="form-control" required
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       placeholder="email@example.com">
                <?php if (!empty($errors['email'])): ?>
                  <small style="color:#ff8a8a; display:block; margin-top:6px;"><?= htmlspecialchars($errors['email']) ?></small>
                <?php endif; ?>
              </div>
            </div>

            <div style="margin-top:14px;">
              <div class="form-group">
                <label for="phone" style="color:var(--muted-2); font-weight:600;">Số điện thoại</label>
                <input id="phone" name="phone" class="form-control"
                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                       placeholder="0123 456 789">
              </div>
            </div>

            <div style="margin-top:14px;">
              <div class="form-group">
                <label for="subject" style="color:var(--muted-2); font-weight:600;">Tiêu đề</label>
                <input id="subject" name="subject" class="form-control"
                       value="<?= htmlspecialchars($old['subject'] ?? '') ?>"
                       placeholder="Tiêu đề (tùy chọn)">
              </div>
            </div>

            <div style="margin-top:14px;">
              <div class="form-group">
                <label for="message" style="color:var(--muted-2); font-weight:600;">Nội dung <span style="color:#ff6a6a">*</span></label>
                <textarea id="message" name="message" class="form-control" rows="6" required placeholder="Nội dung liên hệ..."><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                <?php if (!empty($errors['message'])): ?>
                  <small style="color:#ff8a8a; display:block; margin-top:6px;"><?= htmlspecialchars($errors['message']) ?></small>
                <?php endif; ?>
              </div>
            </div>

            <div style="margin-top:18px; display:flex; gap:12px; align-items:center;">
              <button type="submit" class="btn-primary" style="padding:10px 18px; border-radius:999px;">Gửi liên hệ</button>
              <a href="<?= (defined('BASE_URL') ? BASE_URL : '/') ?>?act=trangchu" class="btn-secondary" style="padding:10px 16px; border-radius:999px; background:transparent; border:1px solid rgba(255,255,255,0.06); color:var(--muted); text-decoration:none;">Hủy</a>
            </div>
          </form>

          <hr style="margin:22px 0; border-color: rgba(255,255,255,0.04)">

          <div class="contact-info" style="color:var(--muted-2);">
            <h4 style="color:var(--muted); margin-bottom:8px;">Thông tin liên hệ khác</h4>
            <p style="margin:6px 0;">Địa chỉ: Số 87 Láng Hạ, Phường Ô Chợ Dừa, TP. Hà Nội</p>
            <p style="margin:6px 0;">Điện thoại: <a href="tel:02435141791">024.3514.1791</a></p>
            <p style="margin:6px 0;">Email: <a href="mailto:info@example.com">info@example.com</a></p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
