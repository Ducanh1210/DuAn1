<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý ghế</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=seats-generate" class="btn btn-success me-2">
          <i class="bi bi-grid-3x3-gap"></i> Tạo sơ đồ ghế tự động
        </a>
        <a href="<?= BASE_URL ?>?act=seats-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm ghế mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['success']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <!-- Filter by room -->
      <form method="GET" class="mb-4">
        <input type="hidden" name="act" value="seats">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Lọc theo phòng:</label>
            <select name="room_id" class="form-select" onchange="this.form.submit()">
              <option value="">-- Chọn phòng để xem sơ đồ ghế --</option>
              <?php foreach ($rooms as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $selectedRoomId == $r['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($r['name'] ?? '') ?> (<?= htmlspecialchars($r['room_code'] ?? '') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if ($selectedRoomId && $room): ?>
            <div class="col-md-8 d-flex align-items-end gap-2">
              <div class="text-muted">
                <strong>Tổng số ghế:</strong> <?= count($seatMap) > 0 ? array_sum(array_map(function($seats) { return count($seats); }, $seatMap)) : 0 ?> ghế
              </div>
            </div>
          <?php endif; ?>
        </div>
      </form>

      <?php if ($selectedRoomId && $room): ?>
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
      <?php else: ?>
        <!-- Sơ đồ ghế ảo (demo) khi chưa chọn phòng -->
        <div class="mb-4">
          <div class="text-center text-muted mb-3">
            <i class="bi bi-info-circle"></i> <strong>Sơ đồ mẫu</strong> - Vui lòng chọn phòng ở trên để xem sơ đồ ghế thực tế
          </div>
          
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
              opacity: 0.6;
            ">
              MÀN HÌNH
            </div>
          </div>

          <!-- Demo Seat Map (10 hàng x 12 ghế) -->
          <div class="seat-map-container" style="overflow-x: auto; opacity: 0.5;">
            <?php
            // Tạo sơ đồ ảo: 10 hàng (A-J), mỗi hàng 12 ghế
            $demoRows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
            $demoSeatTypes = ['normal', 'normal', 'normal', 'vip', 'vip', 'normal', 'normal', 'normal', 'normal', 'normal'];
            $demoStatuses = ['available', 'available', 'booked', 'available', 'available', 'available', 'maintenance', 'available', 'available', 'available'];
            
            foreach ($demoRows as $index => $rowLabel):
              $demoSeatType = $demoSeatTypes[$index] ?? 'normal';
              $demoStatus = $demoStatuses[$index] ?? 'available';
            ?>
              <div class="seat-row mb-2 d-flex align-items-center">
                <div class="row-label me-3" style="min-width: 30px; font-weight: bold; text-align: center;">
                  <?= htmlspecialchars($rowLabel) ?>
                </div>
                <div class="seat-row-content d-flex align-items-center gap-2">
                  <!-- Bên trái: ghế 1-6 -->
                  <div class="seat-side d-flex gap-1">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                      <?php
                      // Tạo một số ghế có trạng thái khác nhau để demo
                      $seatNum = $i;
                      $isBooked = ($rowLabel === 'C' && $seatNum <= 2);
                      $isVip = ($rowLabel === 'D' || $rowLabel === 'E');
                      $isMaintenance = ($rowLabel === 'G' && $seatNum === 3);
                      
                      $badgeColor = 'secondary';
                      $textColor = '';
                      $displayContent = $seatNum;
                      
                      if ($isMaintenance) {
                        $badgeColor = 'warning';
                        $textColor = 'text-dark';
                        $displayContent = '✕';
                      } elseif ($isBooked) {
                        $badgeColor = 'danger';
                      } elseif ($isVip) {
                        $badgeColor = 'warning';
                        $textColor = 'text-dark';
                      }
                      ?>
                      <span class="badge bg-<?= $badgeColor ?> <?= $textColor ?>" 
                            style="
                              width: 40px;
                              height: 40px;
                              display: inline-flex;
                              align-items: center;
                              justify-content: center;
                              font-size: <?= $isMaintenance ? '18px' : '12px' ?>;
                              font-weight: <?= $isMaintenance ? 'bold' : 'normal' ?>;
                              cursor: default;
                            ">
                        <?= htmlspecialchars($displayContent) ?>
                      </span>
                    <?php endfor; ?>
                  </div>
                  
                  <!-- Khoảng trống giữa (aisle) -->
                  <div class="seat-aisle" style="width: 40px; flex-shrink: 0;"></div>
                  
                  <!-- Bên phải: ghế 7-12 -->
                  <div class="seat-side d-flex gap-1">
                    <?php for ($i = 7; $i <= 12; $i++): ?>
                      <?php
                      // Tạo một số ghế có trạng thái khác nhau để demo
                      $seatNum = $i;
                      $isBooked = ($rowLabel === 'C' && $seatNum >= 7 && $seatNum <= 9);
                      $isVip = ($rowLabel === 'D' || $rowLabel === 'E');
                      $isMaintenance = false;
                      
                      $badgeColor = 'secondary';
                      $textColor = '';
                      $displayContent = $seatNum;
                      
                      if ($isMaintenance) {
                        $badgeColor = 'warning';
                        $textColor = 'text-dark';
                        $displayContent = '✕';
                      } elseif ($isBooked) {
                        $badgeColor = 'danger';
                      } elseif ($isVip) {
                        $badgeColor = 'warning';
                        $textColor = 'text-dark';
                      }
                      ?>
                      <span class="badge bg-<?= $badgeColor ?> <?= $textColor ?>" 
                            style="
                              width: 40px;
                              height: 40px;
                              display: inline-flex;
                              align-items: center;
                              justify-content: center;
                              font-size: 12px;
                              cursor: default;
                            ">
                        <?= htmlspecialchars($displayContent) ?>
                      </span>
                    <?php endfor; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

