<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Chi tiết phòng: <?= htmlspecialchars($room['name']) ?></h4>
      <div>
        <a href="<?= BASE_URL ?>?act=rooms-edit&id=<?= $room['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa
        </a>
        <a href="<?= BASE_URL ?>?act=rooms" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">ID</label>
            <p class="mb-0"><?= $room['id'] ?></p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Rạp</label>
            <p class="mb-0"><strong><?= htmlspecialchars($room['cinema_name'] ?? 'N/A') ?></strong></p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Mã phòng</label>
            <p class="mb-0"><span class="badge bg-secondary"><?= htmlspecialchars($room['room_code'] ?? 'N/A') ?></span></p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Tên phòng</label>
            <p class="mb-0"><strong><?= htmlspecialchars($room['name'] ?? 'N/A') ?></strong></p>
          </div>

          <div class="mb-3">
            <label class="text-muted small mb-1 d-block">Số ghế</label>
            <p class="mb-0"><?= number_format($room['seat_count'] ?? 0, 0, ',', '.') ?> ghế</p>
          </div>
        </div>
      </div>

      <div class="mt-4">
        <a href="<?= BASE_URL ?>?act=rooms-edit&id=<?= $room['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Sửa phòng
        </a>
        <a href="<?= BASE_URL ?>?act=rooms-delete&id=<?= $room['id'] ?>" 
           class="btn btn-danger"
           onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này? Lưu ý: Nếu có lịch chiếu thuộc phòng này, bạn cần xóa lịch chiếu trước.')">
          <i class="bi bi-trash"></i> Xóa phòng
        </a>
        <a href="<?= BASE_URL ?>?act=rooms" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
      </div>
    </div>
  </div>
</div>

