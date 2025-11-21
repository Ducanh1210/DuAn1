<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h4 class="mb-0">Tạo sơ đồ ghế tự động</h4>
    </div>
    <div class="card-body">
      <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($errors['general']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>?act=seats-generate" onsubmit="return validateForm()">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Chọn phòng <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select" required onchange="updateSeatConfig()">
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $room): ?>
                  <option value="<?= $room['id'] ?>" 
                          data-capacity="<?= $room['capacity'] ?? 0 ?>"
                          <?= (isset($_GET['room_id']) && $_GET['room_id'] == $room['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($room['name'] ?? '') ?> (<?= htmlspecialchars($room['room_code'] ?? '') ?>) - <?= $room['capacity'] ?? 0 ?> ghế
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['room_id'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['room_id']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Số hàng ghế (A-Z) <span class="text-danger">*</span></label>
              <input type="number" name="rows" id="rows" class="form-control" min="1" max="26" value="10" required>
              <small class="text-muted">Tối đa 26 hàng (A đến Z)</small>
              <?php if (isset($errors['rows'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['rows']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Số ghế mỗi hàng <span class="text-danger">*</span></label>
              <input type="number" name="seats_per_row" id="seats_per_row" class="form-control" min="1" max="50" value="15" required>
              <small class="text-muted">Tối đa 50 ghế mỗi hàng</small>
              <?php if (isset($errors['seats_per_row'])): ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($errors['seats_per_row']) ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Loại ghế</label>
              <select name="seat_type" id="seat_type" class="form-select">
                <option value="normal">Thường</option>
                <option value="vip">VIP</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Phụ thu (VNĐ)</label>
              <input type="number" name="extra_price" id="extra_price" class="form-control" min="0" step="1000" value="0">
              <small class="text-muted">Giá phụ thu cho loại ghế này (0 nếu không có)</small>
            </div>

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
// Dữ liệu phòng với capacity
const roomsData = {
  <?php foreach ($rooms as $room): ?>
    <?= $room['id'] ?>: { capacity: <?= $room['capacity'] ?? 0 ?> },
  <?php endforeach; ?>
};

// Hàm tính toán số hàng và số ghế mỗi hàng dựa trên capacity
function calculateSeatConfig(capacity) {
  if (!capacity || capacity <= 0) {
    return { rows: 10, seatsPerRow: 15 }; // Mặc định
  }

  let bestRows = null;
  let bestSeatsPerRow = null;
  let bestDiff = Infinity;
  
  // Thử các cách chia từ 5 đến 26 hàng
  // Ưu tiên số hàng từ 8-15 để có sơ đồ đẹp hơn
  let exactMatches = []; // Lưu các cách chia chính xác
  let bestApprox = null; // Cách chia gần nhất (nếu không có cách chính xác)
  
  for (let rows = 5; rows <= 26; rows++) {
    // Kiểm tra xem capacity có chia hết cho rows không
    if (capacity % rows === 0) {
      // Chia hết, đây là cách chia chính xác
      const seatsPerRow = capacity / rows;
      
      if (seatsPerRow <= 50 && seatsPerRow > 0) {
        exactMatches.push({ rows: rows, seatsPerRow: seatsPerRow });
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
            bestApprox = { rows: rows, seatsPerRow: seatsPerRowCeil, diff: diff };
          } else if (diff === bestApprox.diff && rows < bestApprox.rows) {
            bestApprox = { rows: rows, seatsPerRow: seatsPerRowCeil, diff: diff };
          }
        }
        
        // Kiểm tra với Math.floor (nếu khác với ceil)
        if (seatsPerRowFloor > 0 && seatsPerRowFloor !== seatsPerRowCeil && seatsPerRowFloor <= 50) {
          const totalSeats = rows * seatsPerRowFloor;
          const diff = Math.abs(totalSeats - capacity);
          
          if (bestApprox === null || diff < bestApprox.diff) {
            bestApprox = { rows: rows, seatsPerRow: seatsPerRowFloor, diff: diff };
          } else if (diff === bestApprox.diff && rows < bestApprox.rows) {
            bestApprox = { rows: rows, seatsPerRow: seatsPerRowFloor, diff: diff };
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
  
  // Nếu không tìm thấy cách chia tốt, dùng cách chia mặc định
  if (bestRows === null) {
    bestRows = 10;
    bestSeatsPerRow = Math.ceil(capacity / 10);
    if (bestSeatsPerRow > 50) {
      bestSeatsPerRow = 50;
      bestRows = Math.ceil(capacity / 50);
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
  
  return { rows: bestRows, seatsPerRow: bestSeatsPerRow };
}

// Cập nhật số hàng và số ghế mỗi hàng khi chọn phòng
function updateSeatConfig() {
  const roomSelect = document.getElementById('room_id');
  const selectedRoomId = roomSelect.value;
  
  if (!selectedRoomId || !roomsData[selectedRoomId]) {
    return;
  }
  
  const capacity = roomsData[selectedRoomId].capacity;
  const config = calculateSeatConfig(capacity);
  
  document.getElementById('rows').value = config.rows;
  document.getElementById('seats_per_row').value = config.seatsPerRow;
}

// Tự động cập nhật khi trang load nếu đã chọn phòng
document.addEventListener('DOMContentLoaded', function() {
  const roomSelect = document.getElementById('room_id');
  if (roomSelect.value) {
    updateSeatConfig();
  }
});

function validateForm() {
  const roomId = document.getElementById('room_id').value;
  const rows = parseInt(document.getElementById('rows').value);
  const seatsPerRow = parseInt(document.getElementById('seats_per_row').value);
  const clearExisting = document.getElementById('clear_existing').checked;

  if (!roomId) {
    alert('Vui lòng chọn phòng');
    return false;
  }

  if (rows <= 0 || rows > 26) {
    alert('Số hàng phải từ 1 đến 26');
    return false;
  }

  if (seatsPerRow <= 0 || seatsPerRow > 50) {
    alert('Số ghế mỗi hàng phải từ 1 đến 50');
    return false;
  }

  if (clearExisting) {
    if (!confirm('Bạn có chắc chắn muốn xóa tất cả ghế hiện có? Hành động này không thể hoàn tác!')) {
      return false;
    }
  }

  const totalSeats = rows * seatsPerRow;
  const capacity = roomsData[roomId] ? roomsData[roomId].capacity : 0;
  
  if (capacity > 0 && totalSeats !== capacity) {
    if (!confirm(`Phòng này có ${capacity} ghế, nhưng bạn sẽ tạo ${totalSeats} ghế (${rows} hàng x ${seatsPerRow} ghế). Tiếp tục?`)) {
      return false;
    }
  } else {
    if (!confirm(`Bạn sẽ tạo ${totalSeats} ghế (${rows} hàng x ${seatsPerRow} ghế). Tiếp tục?`)) {
      return false;
    }
  }

  return true;
}
</script>

