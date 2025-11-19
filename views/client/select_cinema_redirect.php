<?php
$showtime = $showtime ?? null;
$movie = $movie ?? null;
$targetShowtime = $targetShowtime ?? null;
?>
<div class="container py-4">
  <div class="alert alert-info">
    <h5>Đang chuyển tới trang chọn ghế...</h5>
    <p>Bạn sẽ được chuyển trong giây lát tới suất <strong><?= htmlspecialchars($targetShowtime['showtime_id'] ?? '') ?></strong> tại <?= htmlspecialchars($targetShowtime['cinema_name'] ?? '') ?> - <?= htmlspecialchars($targetShowtime['room_name'] ?? '') ?>.</p>
  </div>
  <p>Nếu trình duyệt không tự chuyển, <a id="manualLink" href="<?= BASE_URL ?>?act=datve&showtime_id=<?= $targetShowtime['showtime_id'] ?>">bấm vào đây</a>.</p>
</div>
<script>
setTimeout(function(){
  window.location.href = "<?= BASE_URL ?>?act=datve&showtime_id=<?= $targetShowtime['showtime_id'] ?>";
}, 2000);
</script>
