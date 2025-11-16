<?php
class CinemasController
{
    public $cinema;

    public function __construct()
    {
        $this->cinema = new Cinema();
    }

    /**
     * Hiển thị danh sách rạp (Admin)
     */
    public function list()
    {
        $data = $this->cinema->all();
        
        render('admin/cinemas/list.php', [
            'data' => $data
        ]);
    }

    /**
     * Hiển thị form tạo rạp mới (Admin)
     */
    public function create()
    {
        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên rạp";
            }

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'name' => trim($_POST['name']),
                    'address' => trim($_POST['address'] ?? '')
                ];
                
                $result = $this->cinema->insert($data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=cinemas');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm rạp. Vui lòng thử lại.';
                }
            }
        }

        render('admin/cinemas/create.php', ['errors' => $errors]);
    }

    /**
     * Hiển thị form sửa rạp (Admin)
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        $cinema = $this->cinema->find($id);
        if (!$cinema) {
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['name'] ?? ''))) {
                $errors['name'] = "Bạn vui lòng nhập tên rạp";
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'name' => trim($_POST['name']),
                    'address' => trim($_POST['address'] ?? '')
                ];
                
                $result = $this->cinema->update($id, $data);
                
                if ($result) {
                    header('Location: ' . BASE_URL . '?act=cinemas');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật rạp. Vui lòng thử lại.';
                }
            }
        }

        render('admin/cinemas/edit.php', ['cinema' => $cinema, 'errors' => $errors]);
    }

    /**
     * Xóa rạp (Admin)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=cinemas');
            exit;
        }

        $cinema = $this->cinema->find($id);
        if ($cinema) {
            $this->cinema->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=cinemas');
        exit;
    }
}

?>
