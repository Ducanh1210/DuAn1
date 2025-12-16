<?php
// GENRES/LIST.PHP - TRANG QUẢN LÝ THỂ LOẠI PHIM ADMIN
// Chức năng: Hiển thị danh sách thể loại phim với các thao tác sửa/xóa
// Biến từ controller: $data (danh sách thể loại)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và nút thêm thể loại mới -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý thể loại phim</h4>
      <div>
        <!-- Link đến trang tạo thể loại mới -->
        <a href="<?= BASE_URL ?>?act=genres-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm thể loại mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Bảng danh sách thể loại -->
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên thể loại</th>
              <th>Mô tả</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <!-- Kiểm tra nếu có dữ liệu thể loại -->
            <?php if (!empty($data)): ?>
              <!-- Vòng lặp: duyệt qua từng thể loại -->
              <?php foreach ($data as $item): ?>
              <tr>
                <!-- ID thể loại -->
                <td><?= $item['id'] ?></td>
                <!-- Tên thể loại -->
                <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                <!-- Mô tả thể loại: hiển thị "Chưa có mô tả" nếu không có -->
                <td><?= htmlspecialchars($item['description'] ?? 'Chưa có mô tả') ?></td>
                <!-- Các nút thao tác: sửa và xóa -->
                <td>
                  <div class="btn-group" role="group">
                    <!-- Link sửa thể loại -->
                    <a href="<?= BASE_URL ?>?act=genres-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <!-- Link xóa thể loại: có confirm dialog để xác nhận -->
                    <a href="<?= BASE_URL ?>?act=genres-delete&id=<?= $item['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Xóa"
                       <!-- onclick: hiển thị hộp thoại xác nhận trước khi xóa -->
                       onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại này? Lưu ý: Nếu có phim thuộc thể loại này, bạn cần cập nhật thể loại cho các phim trước khi xóa.')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <!-- Thông báo nếu không có thể loại nào -->
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Chưa có thể loại nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
