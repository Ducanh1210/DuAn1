<?php
class GenresController
{
    public $genre;

    public function __construct()
    {
        $this->genre = new Genre();
    }

    /**
     * Hiển thị danh sách thể loại (Admin)
     */
    public function list()
    {
        require_once __DIR__ . '/../commons/auth.php';
        requireAdmin();
        
        $data = $this->genre->all();
        
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
