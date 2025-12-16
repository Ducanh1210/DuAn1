<?php
// SEATS/SEATMAP.PHP - TRANG SƠ ĐỒ GHẾ ADMIN
// Chức năng: Hiển thị sơ đồ ghế của phòng chiếu dạng lưới (hàng x cột), mỗi ghế có màu theo loại và trạng thái
// Biến từ controller: $room (thông tin phòng), $rooms (danh sách phòng để chọn), $seatMap (mảng ghế theo hàng)
// Layout: 12 ghế mỗi hàng, chia 2 bên (1-6 bên trái, 7-12 bên phải), có khoảng trống giữa (aisle)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề với tên phòng và các nút thao tác -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sơ đồ ghế - <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>)</h4>
      <div>
        <!-- Form chọn phòng: onchange tự động submit để chuyển phòng -->
        <form method="GET" class="d-inline me-2">
          <input type="hidden" name="act" value="seats-seatmap">
          <select name="room_id" class="form-select d-inline-block" style="width: auto;" onchange="this.form.submit()">
            <!-- Vòng lặp: hiển thị danh sách phòng từ $rooms -->
            <?php foreach ($rooms as $r): ?>
              <!-- selected: đánh dấu phòng hiện tại -->
              <option value="<?= $r['id'] ?>" <?= $room['id'] == $r['id'] ? 'selected' : '' ?>>
                <!-- Admin: hiển thị tên rạp, Manager/Staff: chỉ hiển thị tên phòng -->
                <?php if (isAdmin()): ?>
                  <?= htmlspecialchars($r['name'] ?? '') ?> - <?= htmlspecialchars($r['cinema_name'] ?? '') ?> (<?= htmlspecialchars($r['room_code'] ?? '') ?>)
                <?php else: ?>
                  <?= htmlspecialchars($r['name'] ?? '') ?> (<?= htmlspecialchars($r['room_code'] ?? '') ?>)
                <?php endif; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
        <!-- Link tạo lại sơ đồ ghế tự động -->
        <a href="<?= BASE_URL ?>?act=seats-generate&room_id=<?= $room['id'] ?>" class="btn btn-success me-2">
          <i class="bi bi-grid-3x3-gap"></i> Tạo lại sơ đồ
        </a>
        <!-- Link thêm ghế thủ công -->
        <a href="<?= BASE_URL ?>?act=seats-create&room_id=<?= $room['id'] ?>" class="btn btn-primary me-2">
          <i class="bi bi-plus-circle"></i> Thêm ghế
        </a>
        <!-- Link quay lại danh sách ghế -->
        <a href="<?= BASE_URL ?>?act=seats" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Chú thích màu sắc: giải thích ý nghĩa màu của từng loại ghế -->
      <div class="mb-4">
        <h6>Chú thích:</h6>
        <div class="d-flex flex-wrap gap-3">
          <div>
            <span class="badge bg-secondary me-2" style="width: 30px; height: 30px; display: inline-block; vertical-align: middle;"></span>
            <span>Thường</span>
          </div>
          <div>
            <span class="badge bg-warning me-2" style="width: 30px; height: 30px; display: inline-block; vertical-align: middle;"></span>
            <span>VIP</span>
          </div>
          <div>
            <span class="badge bg-danger me-2" style="width: 30px; height: 30px; display: inline-block; vertical-align: middle;"></span>
            <span>Đã đặt</span>
          </div>
          <div>
            <span class="badge bg-warning text-dark me-2" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; font-size: 16px; font-weight: bold;">✕</span>
            <span>Bảo trì</span>
          </div>
        </div>
      </div>

      <!-- Màn hình: hiển thị ở trên cùng sơ đồ ghế -->
      <div class="text-center mb-4">
        <div class="screen-display" style="
          background: linear-gradient(to bottom, #333, #555);
          color: white;
          padding: 15px 40px;
          border-radius: 10px;
          display: inline-block;
          font-weight: bold;
          box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        ">
          MÀN HÌNH
        </div>
      </div>

      <!-- Sơ đồ ghế: hiển thị ghế theo hàng, mỗi hàng có nhãn (A, B, C...) -->
      <div class="seat-map-container" style="overflow-x: auto;">
        <!-- Kiểm tra: nếu có ghế thì hiển thị, nếu không thì hiển thị thông báo -->
        <?php if (!empty($seatMap)): ?>
          <!-- Vòng lặp: duyệt qua từng hàng trong $seatMap (key là row_label: A, B, C...) -->
          <?php foreach ($seatMap as $rowLabel => $seats): ?>
            <div class="seat-row mb-2 d-flex align-items-center">
              <div class="row-label me-3" style="min-width: 30px; font-weight: bold; text-align: center;">
                <?= htmlspecialchars($rowLabel) ?>
              </div>
              <div class="seat-row-content d-flex align-items-center gap-2">
                <!-- Bên trái: ghế 1-6 -->
                <div class="seat-side d-flex gap-1">
                  <?php
                  // Tạo mảng ghế bên trái (1-6)
                  $leftSeats = [];
                  foreach ($seats as $seat) {
                    $seatNumber = $seat['seat_number'] ?? 0;
                    if ($seatNumber >= 1 && $seatNumber <= 6) {
                      $leftSeats[$seatNumber] = $seat;
                    }
                  }
                  
                  // Hiển thị ghế bên trái (1-6)
                  for ($i = 1; $i <= 6; $i++):
                    $seat = $leftSeats[$i] ?? null;
                    
                    if ($seat):
                      // Lấy loại ghế và trạng thái từ $seat
                      $seatType = $seat['seat_type'] ?? 'normal';
                      $status = $seat['status'] ?? 'available';
                      // Validate trạng thái: nếu không hợp lệ, mặc định là available
                      $validStatuses = ['available', 'booked', 'maintenance', 'reserved'];
                      if (!in_array($status, $validStatuses)) {
                        $status = 'available';
                      }
                      
                      // Mảng màu sắc theo loại ghế: normal (xám), vip (vàng)
                      $typeColors = [
                        'normal' => 'secondary',
                        'vip' => 'warning'
                      ];
                      // Validate loại ghế: nếu không hợp lệ, mặc định là normal
                      if (!isset($typeColors[$seatType])) {
                        $seatType = 'normal';
                      }
                      $typeColor = $typeColors[$seatType] ?? 'secondary';
                      
                      // Mảng màu sắc theo trạng thái: available (xanh), booked (đỏ), maintenance (vàng), reserved (xanh nhạt)
                      $statusColors = [
                        'available' => 'success',
                        'booked' => 'danger',
                        'maintenance' => 'warning',
                        'reserved' => 'info'
                      ];
                      $statusColor = $statusColors[$status] ?? 'secondary';
                      
                      // Xử lý màu sắc và nội dung hiển thị cho badge
                      $badgeColor = '';
                      $textColor = '';
                      $displayContent = $seat['seat_number'];
                      $isMaintenance = ($status === 'maintenance');
                      
                      // Xác định màu badge và nội dung hiển thị theo trạng thái
                      if ($isMaintenance) {
                          // Ghế bảo trì: màu vàng với dấu X
                          $badgeColor = 'warning';
                          $textColor = 'text-dark';
                          $displayContent = '✕';
                      } elseif ($status === 'booked') {
                          // Ghế đã đặt: màu đỏ
                          $badgeColor = 'danger';
                          $textColor = '';
                      } elseif ($status === 'reserved') {
                          // Ghế đã đặt trước: màu xanh nhạt
                          $badgeColor = 'info';
                          $textColor = '';
                      } else {
                          // Ghế available: hiển thị theo loại ghế (normal hoặc vip)
                          $badgeColor = $typeColor;
                          if ($seatType === 'vip') {
                              $textColor = 'text-dark'; // VIP có chữ đen trên nền vàng
                          }
                      }
                  ?>
                    <!-- Link đến trang sửa ghế: click vào ghế để sửa -->
                    <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $seat['id'] ?>" 
                       class="seat-badge badge bg-<?= $badgeColor ?> <?= $textColor ?> text-decoration-none <?= $isMaintenance ? 'maintenance-seat' : '' ?>" 
                       style="
                         width: 40px;
                         height: 40px;
                         display: inline-flex;
                         align-items: center;
                         justify-content: center;
                         cursor: pointer;
                         transition: transform 0.2s;
                         font-size: <?= $isMaintenance ? '18px' : '12px' ?>;
                         font-weight: <?= $isMaintenance ? 'bold' : 'normal' ?>;
                         position: relative;
                       "
                       onmouseover="this.style.transform='scale(1.1)'"
                       onmouseout="this.style.transform='scale(1)'"
                       title="Ghế <?= htmlspecialchars($seat['row_label']) ?><?= $seat['seat_number'] ?> - <?= ucfirst($seatType) ?> - <?= ucfirst($status) ?>">
                      <?= htmlspecialchars($displayContent) ?>
                    </a>
                  <?php else: ?>
                    <!-- Ghế không tồn tại: hiển thị khoảng trống mờ -->
                    <span class="seat-badge" style="width: 40px; height: 40px; display: inline-block; opacity: 0.3;"></span>
                  <?php endif; ?>
                <?php endfor; ?>
                </div>
                
                <!-- Khoảng trống giữa (aisle): phân cách giữa ghế bên trái và bên phải -->
                <div class="seat-aisle" style="width: 40px; flex-shrink: 0;"></div>
                
                <!-- Bên phải: ghế 7-12 -->
                <div class="seat-side d-flex gap-1">
                  <?php
                  // Tạo mảng ghế bên phải (7-12): lọc từ $seats
                  $rightSeats = [];
                  foreach ($seats as $seat) {
                    $seatNumber = $seat['seat_number'] ?? 0;
                    if ($seatNumber >= 7 && $seatNumber <= 12) {
                      $rightSeats[$seatNumber] = $seat;
                    }
                  }
                  
                  // Vòng lặp: hiển thị ghế bên phải (7-12)
                  for ($i = 7; $i <= 12; $i++):
                    $seat = $rightSeats[$i] ?? null;
                    
                    if ($seat):
                      // Lấy loại ghế và trạng thái từ $seat
                      $seatType = $seat['seat_type'] ?? 'normal';
                      $status = $seat['status'] ?? 'available';
                      // Validate trạng thái: nếu không hợp lệ, mặc định là available
                      $validStatuses = ['available', 'booked', 'maintenance', 'reserved'];
                      if (!in_array($status, $validStatuses)) {
                        $status = 'available';
                      }
                      
                      // Mảng màu sắc theo loại ghế: normal (xám), vip (vàng)
                      $typeColors = [
                        'normal' => 'secondary',
                        'vip' => 'warning'
                      ];
                      // Validate loại ghế: nếu không hợp lệ, mặc định là normal
                      if (!isset($typeColors[$seatType])) {
                        $seatType = 'normal';
                      }
                      $typeColor = $typeColors[$seatType] ?? 'secondary';
                      
                      // Mảng màu sắc theo trạng thái: available (xanh), booked (đỏ), maintenance (vàng), reserved (xanh nhạt)
                      $statusColors = [
                        'available' => 'success',
                        'booked' => 'danger',
                        'maintenance' => 'warning',
                        'reserved' => 'info'
                      ];
                      $statusColor = $statusColors[$status] ?? 'secondary';
                      
                      // Xử lý màu sắc và nội dung hiển thị cho badge
                      $badgeColor = '';
                      $textColor = '';
                      $displayContent = $seat['seat_number'];
                      $isMaintenance = ($status === 'maintenance');
                      
                      // Xác định màu badge và nội dung hiển thị theo trạng thái
                      if ($isMaintenance) {
                          // Ghế bảo trì: màu vàng với dấu X
                          $badgeColor = 'warning';
                          $textColor = 'text-dark';
                          $displayContent = '✕';
                      } elseif ($status === 'booked') {
                          // Ghế đã đặt: màu đỏ
                          $badgeColor = 'danger';
                          $textColor = '';
                      } elseif ($status === 'reserved') {
                          // Ghế đã đặt trước: màu xanh nhạt
                          $badgeColor = 'info';
                          $textColor = '';
                      } else {
                          // Ghế available: hiển thị theo loại ghế (normal hoặc vip)
                          $badgeColor = $typeColor;
                          if ($seatType === 'vip') {
                              $textColor = 'text-dark'; // VIP có chữ đen trên nền vàng
                          }
                      }
                  ?>
                    <!-- Link đến trang sửa ghế: click vào ghế để sửa -->
                    <a href="<?= BASE_URL ?>?act=seats-edit&id=<?= $seat['id'] ?>" 
                       class="seat-badge badge bg-<?= $badgeColor ?> <?= $textColor ?> text-decoration-none <?= $isMaintenance ? 'maintenance-seat' : '' ?>" 
                       style="
                         width: 40px;
                         height: 40px;
                         display: inline-flex;
                         align-items: center;
                         justify-content: center;
                         cursor: pointer;
                         transition: transform 0.2s;
                         font-size: <?= $isMaintenance ? '18px' : '12px' ?>;
                         font-weight: <?= $isMaintenance ? 'bold' : 'normal' ?>;
                         position: relative;
                       "
                       onmouseover="this.style.transform='scale(1.1)'"
                       onmouseout="this.style.transform='scale(1)'"
                       title="Ghế <?= htmlspecialchars($seat['row_label']) ?><?= $seat['seat_number'] ?> - <?= ucfirst($seatType) ?> - <?= ucfirst($status) ?>">
                      <?= htmlspecialchars($displayContent) ?>
                    </a>
                  <?php else: ?>
                    <!-- Ghế không tồn tại: hiển thị khoảng trống mờ -->
                    <span class="seat-badge" style="width: 40px; height: 40px; display: inline-block; opacity: 0.3;"></span>
                  <?php endif; ?>
                <?php endfor; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Thông báo: phòng chưa có ghế, có link tạo sơ đồ tự động hoặc thêm ghế thủ công -->
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Phòng này chưa có ghế. 
            <a href="<?= BASE_URL ?>?act=seats-generate&room_id=<?= $room['id'] ?>" class="alert-link">Tạo sơ đồ ghế tự động</a> 
            hoặc 
            <a href="<?= BASE_URL ?>?act=seats-create&room_id=<?= $room['id'] ?>" class="alert-link">Thêm ghế thủ công</a>
          </div>
        <?php endif; ?>
      </div>

      <!-- CSS tùy chỉnh: style cho sơ đồ ghế -->
      <style>
        /* Container sơ đồ ghế: max-width 100% */
        .seat-map-container {
          max-width: 100%;
        }
        /* Mỗi hàng ghế: min-height 50px */
        .seat-row {
          min-height: 50px;
        }
        /* Ghế bảo trì: opacity 0.9 */
        .maintenance-seat {
          opacity: 0.9;
        }
        /* Responsive: trên màn hình nhỏ, giảm kích thước ghế */
        @media (max-width: 768px) {
          .seat-badge {
            width: 35px !important;
            height: 35px !important;
            font-size: 10px !important;
          }
          .maintenance-seat {
            font-size: 16px !important;
          }
        }
      </style>
    </div>
  </div>
</div>

