<?php
/**
 * GENRES CONTROLLER - XỬ LÝ LOGIC QUẢN LÝ THỂ LOẠI PHIM
 * 
 * CHỨC NĂNG:
 * - CRUD thể loại: danh sách, tạo, sửa, xóa
 * - Chỉ admin mới có quyền quản lý thể loại
 * 
 * LUỒNG CHẠY:
 * 1. Kiểm tra quyền admin (requireAdmin)
 * 2. Lấy dữ liệu từ Model Genre
 * 3. Render View với dữ liệu
 */
class GenresController
{
    public $genre; // Model Genre để tương tác với database

    public function __construct()
    {
        // Khởi tạo Model Genre
        $this->genre = new Genre();
    }

    /**
     * DANH SÁCH THỂ LOẠI (ADMIN)
     * 
     * LUỒNG CHẠY:
     * 1. Kiểm tra quyền admin (requireAdmin)
     * 2. Lấy danh sách thể loại từ Model
     * 3. Render view với dữ liệu
     * 
     * DỮ LIỆU LẤY:
     * - Từ Model Genre: all() -> danh sách thể loại
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin(); // Chỉ admin mới có quyền quản lý thể loại
        
        $data = $this->genre->all(); // Lấy danh sách thể loại
        
        render('admin/genres/list.php', [
            'data' => $data
        ]);
    }

    /**
     * Hiển thị form tạo thể loại mới (Admin)
     */
    public function create()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên thể loại";
            }

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'name' => trim($_POST['name']),
                    'description' => trim($_POST['description'] ?? '')
                ];
                
                $result = $this->genre->insert($data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=genres');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm thể loại. Vui lòng thử lại.';
                }
            }
        }

        render('admin/genres/create.php', ['errors' => $errors]);
    }

    /**
     * Hiển thị form sửa thể loại (Admin)
     */
    public function edit()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=genres');
            exit;
        }

        $genre = $this->genre->find($id);
        if (!$genre) {
            header('Location: ' . BASE_URL . '?act=genres');
            exit;
        }

        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên thể loại";
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'name' => trim($_POST['name']),
                    'description' => trim($_POST['description'] ?? '')
                ];
                
                $result = $this->genre->update($id, $data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=genres');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật thể loại. Vui lòng thử lại.';
                }
            }
        }

        render('admin/genres/edit.php', ['genre' => $genre, 'errors' => $errors]);
    }

    /**
     * Xóa thể loại (Admin)
     */
    public function delete()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=genres');
            exit;
        }

        $genre = $this->genre->find($id);
        if ($genre) {
            $this->genre->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=genres');
        exit;
    }
}

?>
