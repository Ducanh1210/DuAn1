<?php
class DiscountsController
{
    public $discountCode;

    public function __construct()
    {
        $this->discountCode = new DiscountCode();
    }

    /**
     * Hiển thị danh sách mã giảm giá (Admin)
     */
    public function list()
    {
        $data = $this->discountCode->all();

        render('admin/discounts/list.php', [
            'data' => $data
        ]);
    }

    /**
     * Hiển thị form tạo mã giảm giá mới (Admin)
     */
    public function create()
    {
        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['code'] ?? ''))) {
                $errors['code'] = "Bạn vui lòng nhập mã giảm giá";
            } else {
                // Kiểm tra mã code đã tồn tại chưa
                if ($this->discountCode->codeExists(trim($_POST['code']))) {
                    $errors['code'] = "Mã giảm giá này đã tồn tại";
                }
            }

            if (empty(trim($_POST['title'] ?? ''))) {
                $errors['title'] = "Bạn vui lòng nhập tiêu đề";
            }

            if (empty(trim($_POST['discount_percent'] ?? ''))) {
                $errors['discount_percent'] = "Bạn vui lòng nhập phần trăm giảm giá";
            } elseif (!is_numeric($_POST['discount_percent']) || $_POST['discount_percent'] < 0 || $_POST['discount_percent'] > 100) {
                $errors['discount_percent'] = "Phần trăm giảm giá phải là số từ 0 đến 100";
            }

            if (!empty($_POST['max_discount']) && !is_numeric($_POST['max_discount'])) {
                $errors['max_discount'] = "Giá trị giảm tối đa phải là số";
            }

            if (empty(trim($_POST['start_date'] ?? ''))) {
                $errors['start_date'] = "Bạn vui lòng chọn ngày bắt đầu";
            }

            if (empty(trim($_POST['end_date'] ?? ''))) {
                $errors['end_date'] = "Bạn vui lòng chọn ngày kết thúc";
            }

            // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
            if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                if (strtotime($_POST['end_date']) < strtotime($_POST['start_date'])) {
                    $errors['end_date'] = "Ngày kết thúc phải sau ngày bắt đầu";
                }
            }

            // Xử lý benefits (mảng các lợi ích)
            $benefits = [];
            if (!empty($_POST['benefits'])) {
                $benefitsList = is_array($_POST['benefits'])
                    ? $_POST['benefits']
                    : explode("\n", trim($_POST['benefits']));

                $benefits = array_filter(array_map('trim', $benefitsList), function ($item) {
                    return !empty($item);
                });
            }

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'code' => strtoupper(trim($_POST['code'])),
                    'title' => trim($_POST['title']),
                    'apply_to' => $_POST['apply_to'] ?? 'ticket',
                    'discount_percent' => (float)$_POST['discount_percent'],
                    'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null,
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'description' => trim($_POST['description'] ?? ''),
                    'benefits' => $benefits,
                    'status' => $_POST['status'] ?? 'active',
                    'cta' => trim($_POST['cta'] ?? '')
                ];

                $result = $this->discountCode->insert($data);

                if ($result) {
                    header('Location: ' . BASE_URL . '?act=discounts');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm mã giảm giá. Vui lòng thử lại.';
                }
            }
        }

        render('admin/discounts/create.php', ['errors' => $errors]);
    }

    /**
     * Hiển thị form sửa mã giảm giá (Admin)
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=discounts');
            exit;
        }

        $discount = $this->discountCode->find($id);
        if (!$discount) {
            header('Location: ' . BASE_URL . '?act=discounts');
            exit;
        }

        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['code'] ?? ''))) {
                $errors['code'] = "Bạn vui lòng nhập mã giảm giá";
            } else {
                // Kiểm tra mã code đã tồn tại chưa (trừ ID hiện tại)
                if ($this->discountCode->codeExists(trim($_POST['code']), $id)) {
                    $errors['code'] = "Mã giảm giá này đã tồn tại";
                }
            }

            if (empty(trim($_POST['title'] ?? ''))) {
                $errors['title'] = "Bạn vui lòng nhập tiêu đề";
            }

            if (empty(trim($_POST['discount_percent'] ?? ''))) {
                $errors['discount_percent'] = "Bạn vui lòng nhập phần trăm giảm giá";
            } elseif (!is_numeric($_POST['discount_percent']) || $_POST['discount_percent'] < 0 || $_POST['discount_percent'] > 100) {
                $errors['discount_percent'] = "Phần trăm giảm giá phải là số từ 0 đến 100";
            }

            if (!empty($_POST['max_discount']) && !is_numeric($_POST['max_discount'])) {
                $errors['max_discount'] = "Giá trị giảm tối đa phải là số";
            }

            if (empty(trim($_POST['start_date'] ?? ''))) {
                $errors['start_date'] = "Bạn vui lòng chọn ngày bắt đầu";
            }

            if (empty(trim($_POST['end_date'] ?? ''))) {
                $errors['end_date'] = "Bạn vui lòng chọn ngày kết thúc";
            }

            // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
            if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                if (strtotime($_POST['end_date']) < strtotime($_POST['start_date'])) {
                    $errors['end_date'] = "Ngày kết thúc phải sau ngày bắt đầu";
                }
            }

            // Xử lý benefits (mảng các lợi ích)
            $benefits = [];
            if (!empty($_POST['benefits'])) {
                $benefitsList = is_array($_POST['benefits'])
                    ? $_POST['benefits']
                    : explode("\n", trim($_POST['benefits']));

                $benefits = array_filter(array_map('trim', $benefitsList), function ($item) {
                    return !empty($item);
                });
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'code' => strtoupper(trim($_POST['code'])),
                    'title' => trim($_POST['title']),
                    'apply_to' => $_POST['apply_to'] ?? 'ticket',
                    'discount_percent' => (float)$_POST['discount_percent'],
                    'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null,
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'description' => trim($_POST['description'] ?? ''),
                    'benefits' => $benefits,
                    'status' => $_POST['status'] ?? 'active',
                    'cta' => trim($_POST['cta'] ?? '')
                ];

                $result = $this->discountCode->update($id, $data);

                if ($result) {
                    header('Location: ' . BASE_URL . '?act=discounts');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật mã giảm giá. Vui lòng thử lại.';
                }
            }
        }

        render('admin/discounts/edit.php', ['discount' => $discount, 'errors' => $errors]);
    }

    /**
     * Xóa mã giảm giá (Admin)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=discounts');
            exit;
        }

        $discount = $this->discountCode->find($id);
        if ($discount) {
            $this->discountCode->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=discounts');
        exit;
    }
}
