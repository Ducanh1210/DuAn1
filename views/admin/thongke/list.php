<?php
// views/admin/thongke/list.php
// Expected variables (one of these):
// 1) For daily aggregated view:
//    $from (YYYY-MM-DD), $to (YYYY-MM-DD), $revenueByDay (assoc 'YYYY-MM-DD' => revenue numeric), 
//    optionally $ticketsByDay (assoc 'YYYY-MM-DD' => tickets_sold)
// 2) For booking list view:
//    $bookingsList (array of bookings: each booking should have id, booking_date, booking_code, full_name/user email, final_amount, status, booked_seats, cinema_name, room_name)
// Common optional:
//    $pagination (['current' => int, 'total_pages' => int, 'total' => int, 'perPage' => int])
//    $mode = 'days' | 'bookings' (default 'days')

$mode = $mode ?? (isset($revenueByDay) ? 'days' : (isset($bookingsList) ? 'bookings' : 'days'));
$from = $from ?? date('Y-m-d', strtotime('-6 days'));
$to   = $to   ?? date('Y-m-d');
?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Thống kê — Danh sách</h3>
    <div class="btn-group">
      <a href="?act=stats&mode=days" class="btn btn-sm <?= $mode === 'days' ? 'btn-primary' : 'btn-outline-primary' ?>">Theo ngày</a>
      <a href="?act=stats&mode=bookings" class="btn btn-sm <?= $mode === 'bookings' ? 'btn-primary' : 'btn-outline-primary' ?>">Chi tiết đặt vé</a>
    </div>
  </div>

  <!-- Filter form -->
  <form class="row g-2 align-items-end mb-3" method="get">
    <input type="hidden" name="act" value="<?= htmlspecialchars($_GET['act'] ?? 'stats') ?>">
    <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">
    <div class="col-auto">
      <label class="form-label">Từ</label>
      <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
    </div>
    <div class="col-auto">
      <label class="form-label">Đến</label>
      <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
    </div>
    <div class="col-auto">
      <label class="form-label">Trạng thái</label>
      <select name="status" class="form-select">
        <option value="">Tất cả</option>
        <option value="confirmed" <?= (isset($_GET['status']) && $_GET['status']=='confirmed') ? 'selected' : '' ?>>Confirmed</option>
        <option value="completed" <?= (isset($_GET['status']) && $_GET['status']=='completed') ? 'selected' : '' ?>>Completed</option>
        <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status']=='cancelled') ? 'selected' : '' ?>>Cancelled</option>
      </select>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Lọc</button>
    </div>
    <div class="col-auto ms-auto">
      <button type="button" class="btn btn-outline-secondary" onclick="exportCurrentList()">Xuất CSV</button>
    </div>
  </form>

  <?php if ($mode === 'days'): ?>
    <!-- Aggregated by day -->
    <div class="card mb-3">
      <div class="card-body p-3">
        <h5 class="card-title">Doanh thu theo ngày</h5>
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>Ngày</th>
                <th class="text-end">Doanh thu (VND)</th>
                <th class="text-end">Số đơn</th>
                <th class="text-end">Số vé</th>
                <th class="text-end">Ghi chú</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // revenueByDay expected as assoc date => revenue
                $totalRevenue = 0;
                $totalBookingsCount = 0;
                $totalTickets = 0;
                if (!empty($revenueByDay) && is_array($revenueByDay)):
                  foreach ($revenueByDay as $day => $rev):
                    $revVal = (float)$rev;
                    $bookCount = isset($bookingsCountByDay[$day]) ? (int)$bookingsCountByDay[$day] : null;
                    $tickets = isset($ticketsByDay[$day]) ? (int)$ticketsByDay[$day] : null;
                    $totalRevenue += $revVal;
                    $totalBookingsCount += $bookCount ?? 0;
                    $totalTickets += $tickets ?? 0;
              ?>
                <tr>
                  <td><?= htmlspecialchars($day) ?></td>
                  <td class="text-end"><?= number_format($revVal,0,',','.') ?> đ</td>
                  <td class="text-end"><?= $bookCount !== null ? number_format($bookCount) : '-' ?></td>
                  <td class="text-end"><?= $tickets !== null ? number_format($tickets) : '-' ?></td>
                  <td class="text-end">
                    <a href="?act=stats&mode=bookings&from=<?=urlencode($day)?>&to=<?=urlencode($day)?>" class="btn btn-sm btn-link">Xem chi tiết</a>
                  </td>
                </tr>
              <?php
                  endforeach;
                else:
              ?>
                <tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>
              <?php endif; ?>
            </tbody>
            <?php if (!empty($revenueByDay) && is_array($revenueByDay)): ?>
            <tfoot>
              <tr>
                <th>Tổng</th>
                <th class="text-end"><?= number_format($totalRevenue,0,',','.') ?> đ</th>
                <th class="text-end"><?= number_format($totalBookingsCount) ?></th>
                <th class="text-end"><?= number_format($totalTickets) ?></th>
                <th></th>
              </tr>
            </tfoot>
            <?php endif; ?>
          </table>
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- Booking list / detail -->
    <div class="card mb-3">
      <div class="card-body p-3">
        <h5 class="card-title">Danh sách đặt vé</h5>
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Ngày đặt</th>
                <th>Mã</th>
                <th>Người đặt</th>
                <th>Rạp / Phòng</th>
                <th class="text-end">Vé</th>
                <th class="text-end">Tổng (VND)</th>
                <th>Trạng thái</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($bookingsList) && is_array($bookingsList)): ?>
                <?php foreach ($bookingsList as $i => $b): ?>
                  <tr>
                    <td><?= htmlspecialchars($b['id'] ?? ($i+1)) ?></td>
                    <td><?= htmlspecialchars(isset($b['booking_date']) ? $b['booking_date'] : '') ?></td>
                    <td><?= htmlspecialchars($b['booking_code'] ?? '-') ?></td>
                    <td>
                      <?= htmlspecialchars($b['full_name'] ?? $b['user_email'] ?? '-') ?><br/>
                      <small class="text-muted"><?= htmlspecialchars($b['phone'] ?? '') ?></small>
                    </td>
                    <td>
                      <?= htmlspecialchars($b['cinema_name'] ?? $b['cinema'] ?? '-') ?>
                      <br/><small><?= htmlspecialchars($b['room_name'] ?? $b['room'] ?? '') ?></small>
                    </td>
                    <td class="text-end">
                      <?php
                        // try to show seats count
                        $tickets = '-';
                        if (!empty($b['booked_seats'])) {
                          // if comma separated list
                          $tickets = (strlen($b['booked_seats']) - strlen(str_replace(',', '', $b['booked_seats'])) + 1);
                        } elseif (isset($b['tickets_count'])) {
                          $tickets = (int)$b['tickets_count'];
                        }
                        echo is_numeric($tickets) ? number_format($tickets) : htmlspecialchars($tickets);
                      ?>
                    </td>
                    <td class="text-end"><?= number_format($b['final_amount'] ?? 0, 0, ',', '.') ?> đ</td>
                    <td><?= htmlspecialchars($b['status'] ?? '') ?></td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-outline-primary" href="?act=bookings-show&id=<?= urlencode($b['id'] ?? '') ?>">Xem</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination (if provided by controller) -->
        <?php if (!empty($pagination) && isset($pagination['total_pages'])): ?>
          <nav>
            <ul class="pagination">
              <?php
                $cur = (int)($pagination['current'] ?? 1);
                $totalPages = (int)$pagination['total_pages'];
                $baseUrl = $_SERVER['REQUEST_URI'];
                // remove page param
                $baseUrl = preg_replace('/([&?])page=\d+/', '$1', $baseUrl);
                for ($p = 1; $p <= $totalPages; $p++):
                  $active = $p === $cur ? 'active' : '';
                  $sep = (parse_url($baseUrl, PHP_URL_QUERY) ? '&' : '?');
                  $link = $baseUrl . $sep . 'page=' . $p;
              ?>
                <li class="page-item <?= $active ?>"><a class="page-link" href="<?= htmlspecialchars($link) ?>"><?= $p ?></a></li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<script>
function exportCurrentList() {
  // Build CSV depending on current mode
  const mode = <?= json_encode($mode) ?>;
  let rows = [];
  if (mode === 'days') {
    rows.push(['Ngày', 'Doanh thu']);
    <?php if (!empty($revenueByDay) && is_array($revenueByDay)): ?>
      <?php foreach ($revenueByDay as $d => $v): ?>
        rows.push([<?= json_encode($d) ?>, <?= json_encode((float)$v) ?>]);
      <?php endforeach; ?>
    <?php endif; ?>
  } else {
    rows.push(['ID', 'Ngày đặt', 'Mã', 'Người đặt', 'Rạp', 'Phòng', 'Vé', 'Tổng', 'Trạng thái']);
    <?php if (!empty($bookingsList) && is_array($bookingsList)): ?>
      <?php foreach ($bookingsList as $b): ?>
        <?php
          $ticketsJs = ' ';
          if (!empty($b['booked_seats'])) {
            $cnt = (strlen($b['booked_seats']) - strlen(str_replace(',', '', $b['booked_seats'])) + 1);
            $ticketsJs = $cnt;
          } elseif (isset($b['tickets_count'])) {
            $ticketsJs = (int)$b['tickets_count'];
          } else {
            $ticketsJs = '';
          }
        ?>
        rows.push([<?= json_encode($b['id'] ?? '') ?>, <?= json_encode($b['booking_date'] ?? '') ?>, <?= json_encode($b['booking_code'] ?? '') ?>, <?= json_encode($b['full_name'] ?? $b['user_email'] ?? '') ?>, <?= json_encode($b['cinema_name'] ?? $b['cinema'] ?? '') ?>, <?= json_encode($b['room_name'] ?? $b['room'] ?? '') ?>, <?= json_encode($ticketsJs) ?>, <?= json_encode((float)($b['final_amount'] ?? 0)) ?>, <?= json_encode($b['status'] ?? '') ?>]);
      <?php endforeach; ?>
    <?php endif; ?>
  }

  // convert to CSV text
  const csv = rows.map(r => r.map(c => `"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'thongke_<?= date("Ymd_His") ?>.csv';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}
</script>
