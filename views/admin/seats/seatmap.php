<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Sơ đồ ghế - <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>)</h4>
      <div>
        <form method="GET" class="d-inline me-2">
          <input type="hidden" name="act" value="seats-seatmap">
          <select name="room_id" class="form-select d-inline-block" style="width: auto;" onchange="this.form.submit()">
            <?php foreach ($rooms as $r): ?>
              <option value="<?= $r['id'] ?>" <?= $room['id'] == $r['id'] ? 'selected' : '' ?>>
                <?php if (isAdmin()): ?>
                  <?= htmlspecialchars($r['name'] ?? '') ?> - <?= htmlspecialchars($r['cinema_name'] ?? '') ?> (<?= htmlspecialchars($r['room_code'] ?? '') ?>)
                <?php else: ?>
                  <?= htmlspecialchars($r['name'] ?? '') ?> (<?= htmlspecialchars($r['room_code'] ?? '') ?>)
                <?php endif; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
        <a href="<?= BASE_URL ?>?act=seats-generate&room_id=<?= $room['id'] ?>" class="btn btn-success me-2">
          <i class="bi bi-grid-3x3-gap"></i> Tạo lại sơ đồ
        </a>
        <a href="<?= BASE_URL ?>?act=seats-create&room_id=<?= $room['id'] ?>" class="btn btn-primary me-2">
          <i class="bi bi-plus-circle"></i> Thêm ghế
        </a>
        <a href="<?= BASE_URL ?>?act=seats" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Legend -->
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

      <!-- Screen -->
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

      <!-- Seat Map -->
      <div class="seat-map-container" style="overflow-x: auto;">
        <?php if (!empty($seatMap)): ?>
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
                      $seatType = $seat['seat_type'] ?? 'normal';
                      $status = $seat['status'] ?? 'available';
                      // Nếu trạng thái không hợp lệ, mặc định là available
                      $validStatuses = ['available', 'booked', 'maintenance', 'reserved'];
                      if (!in_array($status, $validStatuses)) {
                        $status = 'available';
                      }
                      
                      // Màu sắc theo loại ghế (chỉ normal và vip)
                      $typeColors = [
                        'normal' => 'secondary',
                        'vip' => 'warning'
                      ];
                      // Nếu là loại ghế không hợp lệ, mặc định là normal
                      if (!isset($typeColors[$seatType])) {
                        $seatType = 'normal';
                      }
                      $typeColor = $typeColors[$seatType] ?? 'secondary';
                      
                      // Màu sắc theo trạng thái
                      $statusColors = [
                        'available' => 'success',
                        'booked' => 'danger',
                        'maintenance' => 'warning',
                        'reserved' => 'info'
                      ];
                      $statusColor = $statusColors[$status] ?? 'secondary';
                      
                      // Xử lý màu sắc và hiển thị
                      $badgeColor = '';
                      $textColor = '';
                      $displayContent = $seat['seat_number'];
                      $isMaintenance = ($status === 'maintenance');
                      
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
                          // Ghế available: hiển thị theo loại ghế
                          $badgeColor = $typeColor;
                          if ($seatType === 'vip') {
                              $textColor = 'text-dark'; // VIP có chữ đen trên nền vàng
                          }
                      }
                  ?>
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
                    <span class="seat-badge" style="width: 40px; height: 40px; display: inline-block; opacity: 0.3;"></span>
                  <?php endif; ?>
                <?php endfor; ?>
                </div>
                
                <!-- Khoảng trống giữa (aisle) -->
                <div class="seat-aisle" style="width: 40px; flex-shrink: 0;"></div>
                
                <!-- Bên phải: ghế 7-12 -->
                <div class="seat-side d-flex gap-1">
                  <?php
                  // Tạo mảng ghế bên phải (7-12)
                  $rightSeats = [];
                  foreach ($seats as $seat) {
                    $seatNumber = $seat['seat_number'] ?? 0;
                    if ($seatNumber >= 7 && $seatNumber <= 12) {
                      $rightSeats[$seatNumber] = $seat;
                    }
                  }
                  
                  // Hiển thị ghế bên phải (7-12)
                  for ($i = 7; $i <= 12; $i++):
                    $seat = $rightSeats[$i] ?? null;
                    
                    if ($seat):
                      $seatType = $seat['seat_type'] ?? 'normal';
                      $status = $seat['status'] ?? 'available';
                      // Nếu trạng thái không hợp lệ, mặc định là available
                      $validStatuses = ['available', 'booked', 'maintenance', 'reserved'];
                      if (!in_array($status, $validStatuses)) {
                        $status = 'available';
                      }
                      
                      // Màu sắc theo loại ghế (chỉ normal và vip)
                      $typeColors = [
                        'normal' => 'secondary',
                        'vip' => 'warning'
                      ];
                      // Nếu là loại ghế không hợp lệ, mặc định là normal
                      if (!isset($typeColors[$seatType])) {
                        $seatType = 'normal';
                      }
                      $typeColor = $typeColors[$seatType] ?? 'secondary';
                      
                      // Màu sắc theo trạng thái
                      $statusColors = [
                        'available' => 'success',
                        'booked' => 'danger',
                        'maintenance' => 'warning',
                        'reserved' => 'info'
                      ];
                      $statusColor = $statusColors[$status] ?? 'secondary';
                      
                      // Xử lý màu sắc và hiển thị
                      $badgeColor = '';
                      $textColor = '';
                      $displayContent = $seat['seat_number'];
                      $isMaintenance = ($status === 'maintenance');
                      
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
                          // Ghế available: hiển thị theo loại ghế
                          $badgeColor = $typeColor;
                          if ($seatType === 'vip') {
                              $textColor = 'text-dark'; // VIP có chữ đen trên nền vàng
                          }
                      }
                  ?>
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
                    <span class="seat-badge" style="width: 40px; height: 40px; display: inline-block; opacity: 0.3;"></span>
                  <?php endif; ?>
                <?php endfor; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Phòng này chưa có ghế. 
            <a href="<?= BASE_URL ?>?act=seats-generate&room_id=<?= $room['id'] ?>" class="alert-link">Tạo sơ đồ ghế tự động</a> 
            hoặc 
            <a href="<?= BASE_URL ?>?act=seats-create&room_id=<?= $room['id'] ?>" class="alert-link">Thêm ghế thủ công</a>
          </div>
        <?php endif; ?>
      </div>

      <style>
        .seat-map-container {
          max-width: 100%;
        }
        .seat-row {
          min-height: 50px;
        }
        .maintenance-seat {
          opacity: 0.9;
        }
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

