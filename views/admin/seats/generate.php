<?php
// SEATS/GENERATE.PHP - TRANG TẠO SƠ ĐỒ GHẾ TỰ ĐỘNG ADMIN
// Chức năng: Form tạo sơ đồ ghế tự động cho phòng chiếu (số hàng, số ghế mỗi hàng, loại ghế, phụ thu)
// Biến từ controller: $rooms (danh sách phòng), $errors (lỗi validation)
// JavaScript: tự động tính toán số hàng và số ghế mỗi hàng dựa trên capacity của phòng
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề trang -->
    <div class="card-header">
      <h4 class="mb-0">Tạo sơ đồ ghế tự động</h4>
    </div>
    <div class="card-body">
      <!-- Hiển thị lỗi tổng quát nếu có: $errors['general'] từ controller -->
      <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($errors['general']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Form tạo sơ đồ ghế: onsubmit gọi hàm validateForm() để validate client-side -->
      <form method="POST" action="<?= BASE_URL ?>?act=seats-generate" onsubmit="return validateForm()">
        <div class="row">
          <div class="col-md-6">
            <!-- Select phòng: bắt buộc (*), onchange gọi updateSeatConfig() để tự động tính toán -->
            <div class="mb-3">
              <label class="form-label">Chọn phòng <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select" required onchange="updateSeatConfig()">
                <option value="">-- Chọn phòng --</option>
                <!-- Vòng lặp: hiển thị danh sách phòng từ $rooms -->
                <?php foreach ($rooms as $room): ?>
                  <!-- data-capacity: lưu capacity để JavaScript sử dụng, selected nếu $_GET['room_id'] trùng -->
                  <option value="<?= $room['id'] ?>"
                    data-capacity="<?= $room['seat_count'] ?? $room['capacity'] ?? 0 ?>"
                    <?= (isset($_GET['room_id']) && $_GET['room_id'] == $room['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>) - <?= $room['seat_count'] ?? $room['capacity'] ?? 0 ?> ghế
                  </option>
                <?php endforeach; ?>
              </select>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (isset($errors['room_id'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['room_id']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Input số hàng ghế: bắt buộc (*), min=1, max=26 (A-Z), mặc định 10 -->
            <div class="mb-3">
              <label class="form-label">Số hàng ghế (A-Z) <span class="text-danger">*</span></label>
              <input type="number" name="rows" id="rows" class="form-control" min="1" max="26" value="10" required>
              <small class="text-muted">Tối đa 26 hàng (A đến Z)</small>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (isset($errors['rows'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['rows']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Input số ghế mỗi hàng: bắt buộc (*), min=1, max=50, mặc định 15 -->
            <div class="mb-3">
              <label class="form-label">Số ghế mỗi hàng <span class="text-danger">*</span></label>
              <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" max="50" value="15" required>
              <small class="text-muted">Tối đa 50 ghế mỗi hàng</small>
              <!-- Hiển thị lỗi validation nếu có -->
              <?php if (isset($errors['seats_per_row'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['seats_per_row']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Select loại ghế: normal (Thường) hoặc vip (VIP) -->
            <div class="mb-3">
              <label class="form-label">Loại ghế</label>
              <select name="seat_type" id="seat_type" class="form-select">
                <option value="normal">Thường</option>
                <option value="vip">VIP</option>
              </select>
            </div>

            <!-- Input phụ thu: không bắt buộc, min=0, step=1000 (làm tròn 1000), mặc định 0 -->
            <div class="mb-3">
              <label class="form-label">Phụ thu (VNĐ)</label>
              <input type="number" name="extra_price" id="extra_price" class="form-control" min="0" step="1000" value="0">
              <small class="text-muted">Giá phụ thu cho loại ghế này (0 nếu không có)</small>
            </div>

            <!-- Checkbox xóa ghế hiện có: nếu checked, sẽ xóa tất cả ghế cũ trước khi tạo mới -->
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="clear_existing" id="clear_existing" value="1">
                <label class="form-check-label" for="clear_existing">
                  Xóa tất cả ghế hiện có trong phòng trước khi tạo mới
                </label>
                <small class="text-muted d-block">Lưu ý: Hành động này không thể hoàn tác!</small>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Tạo sơ đồ ghế
              </button>
              <a href="<?= BASE_URL ?>?act=seats" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
              </a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="alert alert-info">
              <h6><i class="bi bi-info-circle"></i> Hướng dẫn:</h6>
              <ul class="mb-0">
                <li>Chọn phòng chiếu cần tạo sơ đồ ghế</li>
                <li>Nhập số hàng ghế (ví dụ: 10 hàng = A, B, C... J)</li>
                <li>Nhập số ghế mỗi hàng (ví dụ: 15 ghế = 1, 2, 3... 15)</li>
                <li>Chọn loại ghế mặc định cho tất cả ghế</li>
                <li>Nhập phụ thu nếu có (ví dụ: VIP +20,000đ)</li>
                <li>Nếu phòng đã có ghế, bạn có thể chọn xóa ghế cũ trước khi tạo mới</li>
              </ul>
            </div>
            <div class="alert alert-warning">
              <strong>Lưu ý:</strong> Sau khi tạo, bạn có thể chỉnh sửa từng ghế riêng lẻ nếu cần thay đổi loại ghế hoặc trạng thái.
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Dữ liệu phòng với capacity: chuyển mảng $rooms thành object JavaScript với key là room_id
  const roomsData = <?= json_encode(array_reduce($rooms, function ($carry, $room) {
                      $carry[$room['id']] = ['capacity' => $room['seat_count'] ?? $room['capacity'] ?? 0];
                      return $carry;
                    }, [])) ?>;

  // Cấu hình sơ đồ ghế cho các phòng cụ thể (12 cột cho tất cả): hardcode cho một số phòng
  const roomSeatConfig = {
    // Phòng Chiếu 1: 10 hàng x 12 cột
    17: {
      rows: 10,
      seatsPerRow: 12
    },
    // Phòng Chiếu 2: 12 hàng x 12 cột
    18: {
      rows: 12,
      seatsPerRow: 12
    },
    // Phòng Chiếu 3: 14 hàng x 12 cột
    19: {
      rows: 14,
      seatsPerRow: 12
    }
  };

  // Hàm tính toán số hàng và số ghế mỗi hàng dựa trên capacity: tìm cách chia tối ưu
  function calculateSeatConfig(capacity, roomId) {
    // Kiểm tra xem phòng có cấu hình riêng không: nếu có thì dùng cấu hình đó
    if (roomId && roomSeatConfig[roomId]) {
      return roomSeatConfig[roomId];
    }

    // Nếu capacity không hợp lệ: trả về cấu hình mặc định (10 hàng x 12 cột)
    if (!capacity || capacity <= 0) {
      return {
        rows: 10,
        seatsPerRow: 12
      }; // Mặc định 12 cột
    }

    // Biến lưu kết quả tốt nhất
    let bestRows = null;
    let bestSeatsPerRow = null;
    let bestDiff = Infinity;

    // Thử các cách chia từ 5 đến 26 hàng
    // Ưu tiên số hàng từ 8-15 để có sơ đồ đẹp hơn
    let exactMatches = []; // Lưu các cách chia chính xác (capacity chia hết cho rows)
    let bestApprox = null; // Cách chia gần nhất (nếu không có cách chính xác)

    for (let rows = 5; rows <= 26; rows++) {
      // Kiểm tra xem capacity có chia hết cho rows không
      if (capacity % rows === 0) {
        // Chia hết, đây là cách chia chính xác
        const seatsPerRow = capacity / rows;

        if (seatsPerRow <= 50 && seatsPerRow > 0) {
          exactMatches.push({
            rows: rows,
            seatsPerRow: seatsPerRow
          });
        }
      } else {
        // Không chia hết, thử Math.ceil và Math.floor
        const seatsPerRowCeil = Math.ceil(capacity / rows);
        const seatsPerRowFloor = Math.floor(capacity / rows);

        // Chỉ xét nếu chưa có cách chia chính xác
        if (exactMatches.length === 0) {
          // Kiểm tra với Math.ceil
          if (seatsPerRowCeil <= 50) {
            const totalSeats = rows * seatsPerRowCeil;
            const diff = Math.abs(totalSeats - capacity);

            if (bestApprox === null || diff < bestApprox.diff) {
              bestApprox = {
                rows: rows,
                seatsPerRow: seatsPerRowCeil,
                diff: diff
              };
            } else if (diff === bestApprox.diff && rows < bestApprox.rows) {
              bestApprox = {
                rows: rows,
                seatsPerRow: seatsPerRowCeil,
                diff: diff
              };
            }
          }

          // Kiểm tra với Math.floor (nếu khác với ceil)
          if (seatsPerRowFloor > 0 && seatsPerRowFloor !== seatsPerRowCeil && seatsPerRowFloor <= 50) {
            const totalSeats = rows * seatsPerRowFloor;
            const diff = Math.abs(totalSeats - capacity);

            if (bestApprox === null || diff < bestApprox.diff) {
              bestApprox = {
                rows: rows,
                seatsPerRow: seatsPerRowFloor,
                diff: diff
              };
            } else if (diff === bestApprox.diff && rows < bestApprox.rows) {
              bestApprox = {
                rows: rows,
                seatsPerRow: seatsPerRowFloor,
                diff: diff
              };
            }
          }
        }
      }
    }

    // Nếu có cách chia chính xác, ưu tiên số hàng từ 8-15
    if (exactMatches.length > 0) {
      // Tìm cách chia trong khoảng 8-15 hàng
      const preferred = exactMatches.find(m => m.rows >= 8 && m.rows <= 15);
      if (preferred) {
        bestRows = preferred.rows;
        bestSeatsPerRow = preferred.seatsPerRow;
        bestDiff = 0;
      } else {
        // Không có trong khoảng 8-15, chọn số hàng gần 10 nhất
        exactMatches.sort((a, b) => Math.abs(a.rows - 10) - Math.abs(b.rows - 10));
        bestRows = exactMatches[0].rows;
        bestSeatsPerRow = exactMatches[0].seatsPerRow;
        bestDiff = 0;
      }
    } else if (bestApprox !== null) {
      // Không có cách chia chính xác, dùng cách gần nhất
      bestRows = bestApprox.rows;
      bestSeatsPerRow = bestApprox.seatsPerRow;
      bestDiff = bestApprox.diff;
    }

    // Nếu không tìm thấy cách chia tốt, dùng cách chia mặc định (12 cột)
    if (bestRows === null) {
      bestRows = Math.ceil(capacity / 12);
      bestSeatsPerRow = 12;
      if (bestRows > 26) {
        bestRows = 26;
        bestSeatsPerRow = Math.ceil(capacity / 26);
      }
    }

    // Đảm bảo không vượt quá giới hạn
    if (bestSeatsPerRow > 50) {
      bestSeatsPerRow = 50;
      bestRows = Math.ceil(capacity / 50);
    }
    if (bestRows > 26) {
      bestRows = 26;
      bestSeatsPerRow = Math.ceil(capacity / 26);
    }

    return {
      rows: bestRows,
      seatsPerRow: bestSeatsPerRow
    };
  }

  // Cập nhật số hàng và số ghế mỗi hàng khi chọn phòng: gọi khi onchange của select phòng
  function updateSeatConfig() {
    const roomSelect = document.getElementById('room_id');
    const selectedRoomId = parseInt(roomSelect.value);

    // Kiểm tra: phải chọn phòng và phòng phải có trong dữ liệu
    if (!selectedRoomId || !roomsData[selectedRoomId]) {
      return;
    }

    // Lấy capacity của phòng và tính toán cấu hình tối ưu
    const capacity = roomsData[selectedRoomId].capacity;
    const config = calculateSeatConfig(capacity, selectedRoomId);

    // Cập nhật giá trị vào input rows và seats_per_row
    document.getElementById('rows').value = config.rows;
    document.getElementById('seats_per_row').value = config.seatsPerRow;
  }

  // Tự động cập nhật khi trang load nếu đã chọn phòng: gọi khi DOM ready
  document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    // Nếu đã có phòng được chọn (từ $_GET['room_id']), tự động tính toán
    if (roomSelect.value) {
      updateSeatConfig();
    }
  });

  // Validation form client-side: kiểm tra dữ liệu trước khi submit
  function validateForm() {
    // Lấy giá trị các trường
    const roomId = document.getElementById('room_id').value;
    const rows = parseInt(document.getElementById('rows').value);
    const seatsPerRow = parseInt(document.getElementById('seats_per_row').value);
    const clearExisting = document.getElementById('clear_existing').checked;

    // Kiểm tra phòng: bắt buộc phải chọn
    if (!roomId) {
      alert('Vui lòng chọn phòng');
      return false;
    }

    // Kiểm tra số hàng: phải từ 1 đến 26
    if (rows <= 0 || rows > 26) {
      alert('Số hàng phải từ 1 đến 26');
      return false;
    }

    // Kiểm tra số ghế mỗi hàng: phải từ 1 đến 50
    if (seatsPerRow <= 0 || seatsPerRow > 50) {
      alert('Số ghế mỗi hàng phải từ 1 đến 50');
      return false;
    }

    // Nếu chọn xóa ghế hiện có: xác nhận lại (hành động không thể hoàn tác)
    if (clearExisting) {
      if (!confirm('Bạn có chắc chắn muốn xóa tất cả ghế hiện có? Hành động này không thể hoàn tác!')) {
        return false;
      }
    }

    // Tính tổng số ghế sẽ tạo và so sánh với capacity của phòng
    const totalSeats = rows * seatsPerRow;
    const capacity = roomsData[roomId] ? roomsData[roomId].capacity : 0;

    // Nếu capacity > 0 và totalSeats khác capacity: cảnh báo và xác nhận
    if (capacity > 0 && totalSeats !== capacity) {
      if (!confirm(`Phòng này có ${capacity} ghế, nhưng bạn sẽ tạo ${totalSeats} ghế (${rows} hàng x ${seatsPerRow} ghế). Tiếp tục?`)) {
        return false;
      }
    } else {
      // Xác nhận tổng số ghế sẽ tạo
      if (!confirm(`Bạn sẽ tạo ${totalSeats} ghế (${rows} hàng x ${seatsPerRow} ghế). Tiếp tục?`)) {
        return false;
      }
    }

    return true; // Cho phép submit
  }
</script>