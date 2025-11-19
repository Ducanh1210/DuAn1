<?php
$showtime = $showtime ?? null;
$movie = $movie ?? null;
$cinemas = $cinemas ?? [];
?>

<div class="container py-4">
  <h3>Chọn rạp cho: <?= htmlspecialchars($movie['title'] ?? '') ?></h3>

  <?php if (empty($cinemas)): ?>
    <div class="alert alert-warning">Chưa có rạp/ suất chiếu khác cho phim này trong ngày.</div>
    <a href="<?= BASE_URL ?>?act=trangchu" class="btn btn-secondary">Quay lại</a>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($cinemas as $s): ?>
        <a href="<?= BASE_URL ?>?act=datve&showtime_id=<?= $s['showtime_id'] ?>&room_id=<?= $s['room_id'] ?>"
           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1"><?= htmlspecialchars($s['cinema_name']) ?> — <?= htmlspecialchars($s['room_name']) ?></h5>
            <p class="mb-1">Mã suất: <?= $s['showtime_id'] ?> — Sức chứa: <?= htmlspecialchars($s['capacity'] ?? 'N/A') ?></p>
            <small class="text-muted"><?= date('H:i d/m/Y', strtotime($s['start_time'])) ?></small>
          </div>
          <div class="ms-3 text-end">
            <small class="text-muted">Chọn</small>
            <div style="font-weight:600; color:#0d6efd;"><?= date('H:i', strtotime($s['start_time'])) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
