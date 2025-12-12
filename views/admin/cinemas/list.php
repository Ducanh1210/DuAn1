<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý rạp chiếu phim</h4>
      <div>
        <a href="<?= BASE_URL ?>?act=cinemas-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm rạp mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên rạp</th>
              <th>Địa chỉ</th>
              <th>Ngày tạo</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php foreach ($data as $item): ?>
              <tr>
                <td><?= $item['id'] ?></td>
                <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                <td><?= htmlspecialchars($item['address'] ?? 'Chưa có địa chỉ') ?></td>
                <td><?= $item['created_at'] ? date('d/m/Y H:i', strtotime($item['created_at'])) : 'N/A' ?></td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= BASE_URL ?>?act=cinemas-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=cinemas-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa rạp này? Lưu ý: Nếu có phòng chiếu hoặc lịch chiếu thuộc rạp này, bạn cần xử lý trước khi xóa.')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">Chưa có rạp nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
