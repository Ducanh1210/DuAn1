<?php
// CINEMAS/LIST.PHP - TRANG QUẢN LÝ RẠP CHIẾU PHIM ADMIN
// Chức năng: Hiển thị danh sách rạp chiếu phim với các thao tác sửa/xóa
// Biến từ controller: $data (danh sách rạp)
?>
<div class="container-fluid">
  <div class="card">
    <!-- Header: tiêu đề và nút thêm rạp mới -->
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Quản lý rạp chiếu phim</h4>
      <div>
        <!-- Link đến trang tạo rạp mới -->
        <a href="<?= BASE_URL ?>?act=cinemas-create" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Thêm rạp mới
        </a>
      </div>
    </div>
    <div class="card-body">
      <!-- Hiển thị thông báo lỗi: từ $_SESSION['error'] -->
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['error']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); // Xóa sau khi hiển thị ?>
      <?php endif; ?>
      
      <!-- Hiển thị thông báo thành công: từ $_SESSION['success'] -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['success']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); // Xóa sau khi hiển thị ?>
      <?php endif; ?>
      
      <!-- Bảng danh sách rạp -->
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
            <!-- Kiểm tra nếu có dữ liệu rạp -->
            <?php if (!empty($data)): ?>
              <!-- Vòng lặp: duyệt qua từng rạp -->
              <?php foreach ($data as $item): ?>
              <tr>
                <!-- ID rạp -->
                <td><?= $item['id'] ?></td>
                <!-- Tên rạp -->
                <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                <!-- Địa chỉ rạp: hiển thị "Chưa có địa chỉ" nếu không có -->
                <td><?= htmlspecialchars($item['address'] ?? 'Chưa có địa chỉ') ?></td>
                <!-- Ngày tạo: format d/m/Y H:i -->
                <td><?= $item['created_at'] ? date('d/m/Y H:i', strtotime($item['created_at'])) : 'N/A' ?></td>
                <!-- Các nút thao tác: sửa và xóa -->
                <td>
                  <div class="btn-group" role="group">
                    <!-- Link sửa rạp -->
                    <a href="<?= BASE_URL ?>?act=cinemas-edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <!-- Link xóa rạp: có confirm dialog để xác nhận -->
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
              <!-- Thông báo nếu không có rạp nào -->
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
