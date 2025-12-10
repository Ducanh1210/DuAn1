<?php
class DiscountCodesController
{
    public $discountCode;

    public function __construct()
    {
        $this->discountCode = new DiscountCode();
    }

    /**
     * Hiển thị danh sách mã khuyến mại (Admin)
     */
    public function list()
    {
        $data = $this->discountCode->all();

        render('admin/discounts/list.php', [
            'data' => $data
        ]);
    }

    /**
     * Hiển thị form tạo mã khuyến mại mới (Admin)
     */
    public function create()
    {
        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['code'] ?? ''))) {
                $errors['code'] = "Bạn vui lòng nhập mã khuyến mại";
            } elseif ($this->discountCode->codeExists(trim($_POST['code']))) {
                $errors['code'] = "Mã khuyến mại này đã tồn tại";
            }

            if (empty(trim($_POST['title'] ?? ''))) {
                $errors['title'] = "Bạn vui lòng nhập tiêu đề";
            }

            if (empty($_POST['discount_percent'] ?? '') || !is_numeric($_POST['discount_percent'])) {
                $errors['discount_percent'] = "Bạn vui lòng nhập phần trăm giảm giá hợp lệ";
            } else {
                $discountPercent = (float)$_POST['discount_percent'];
                if ($discountPercent < 0) {
                    $errors['discount_percent'] = "Phần trăm giảm giá không được nhỏ hơn 0%";
                } elseif ($discountPercent >= 100) {
                    $errors['discount_percent'] = "Không được giảm giá 100% hoặc lớn hơn. Tối đa chỉ được 85%";
                } elseif ($discountPercent > 85) {
                    $errors['discount_percent'] = "Phần trăm giảm giá không được vượt quá 85%";
                }
            }

            // Validate số lượt sử dụng (tùy chọn, mỗi ghế trừ 1 lượt)
            if (isset($_POST['usage_limit']) && $_POST['usage_limit'] !== '') {
                if (!is_numeric($_POST['usage_limit']) || (int)$_POST['usage_limit'] < 0) {
                    $errors['usage_limit'] = "Số lượt sử dụng phải là số không âm";
                }
            }

            // Validate số lượt sử dụng (tùy chọn, mỗi ghế trừ 1 lượt)
            if (isset($_POST['usage_limit']) && $_POST['usage_limit'] !== '') {
                if (!is_numeric($_POST['usage_limit']) || (int)$_POST['usage_limit'] < 0) {
                    $errors['usage_limit'] = "Số lượt sử dụng phải là số không âm";
                }
            }

            if (empty($_POST['start_date'] ?? '')) {
                $errors['start_date'] = "Bạn vui lòng chọn ngày bắt đầu";
            }

            if (empty($_POST['end_date'] ?? '')) {
                $errors['end_date'] = "Bạn vui lòng chọn ngày kết thúc";
            }

            if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                if (strtotime($_POST['start_date']) > strtotime($_POST['end_date'])) {
                    $errors['end_date'] = "Ngày kết thúc phải sau ngày bắt đầu";
                }
            }

            // Xử lý benefits (mảng)
            $benefits = [];
            if (!empty($_POST['benefits'])) {
                $benefitsArray = is_array($_POST['benefits']) ? $_POST['benefits'] : explode("\n", $_POST['benefits']);
                $benefits = array_filter(array_map('trim', $benefitsArray));
            }

            // Nếu không có lỗi, lưu vào database
            if (empty($errors)) {
                $data = [
                    'code' => trim($_POST['code']),
                    'title' => trim($_POST['title']),
                    'discount_percent' => (int)$_POST['discount_percent'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'description' => trim($_POST['description'] ?? ''),
                    'benefits' => $benefits,
                    'status' => $_POST['status'] ?? 'active',
                    'cta' => trim($_POST['cta'] ?? ''),
                    'usage_limit' => (isset($_POST['usage_limit']) && $_POST['usage_limit'] !== '') ? (int)$_POST['usage_limit'] : null
                ];

                $result = $this->discountCode->insert($data);

                if ($result) {
                    header('Location: ' . BASE_URL . '?act=discounts');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi thêm mã khuyến mại. Vui lòng thử lại.';
                }
            }
        }

        render('admin/discounts/create.php', ['errors' => $errors]);
    }

    /**
     * Hiển thị form sửa mã khuyến mại (Admin)
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

        // Parse benefits từ JSON
        if (!empty($discount['benefits'])) {
            $benefits = json_decode($discount['benefits'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($benefits)) {
                $discount['benefits'] = $benefits;
            } else {
                $discount['benefits'] = [];
            }
        } else {
            $discount['benefits'] = [];
        }

        $errors = [];

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra trường rỗng
            if (empty(trim($_POST['code'] ?? ''))) {
                $errors['code'] = "Bạn vui lòng nhập mã khuyến mại";
            } elseif ($this->discountCode->codeExists(trim($_POST['code']), $id)) {
                $errors['code'] = "Mã khuyến mại này đã tồn tại";
            }

            if (empty(trim($_POST['title'] ?? ''))) {
                $errors['title'] = "Bạn vui lòng nhập tiêu đề";
            }

            if (empty($_POST['discount_percent'] ?? '') || !is_numeric($_POST['discount_percent'])) {
                $errors['discount_percent'] = "Bạn vui lòng nhập phần trăm giảm giá hợp lệ";
            } else {
                $discountPercent = (float)$_POST['discount_percent'];
                if ($discountPercent < 0) {
                    $errors['discount_percent'] = "Phần trăm giảm giá không được nhỏ hơn 0%";
                } elseif ($discountPercent >= 100) {
                    $errors['discount_percent'] = "Không được giảm giá 100% hoặc lớn hơn. Tối đa chỉ được 85%";
                } elseif ($discountPercent > 85) {
                    $errors['discount_percent'] = "Phần trăm giảm giá không được vượt quá 85%";
                }
            }

            if (empty($_POST['start_date'] ?? '')) {
                $errors['start_date'] = "Bạn vui lòng chọn ngày bắt đầu";
            }

            if (empty($_POST['end_date'] ?? '')) {
                $errors['end_date'] = "Bạn vui lòng chọn ngày kết thúc";
            }

            if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                if (strtotime($_POST['start_date']) > strtotime($_POST['end_date'])) {
                    $errors['end_date'] = "Ngày kết thúc phải sau ngày bắt đầu";
                }
            }

            // Xử lý benefits (mảng)
            $benefits = [];
            if (!empty($_POST['benefits'])) {
                $benefitsArray = is_array($_POST['benefits']) ? $_POST['benefits'] : explode("\n", $_POST['benefits']);
                $benefits = array_filter(array_map('trim', $benefitsArray));
            }

            // Nếu không có lỗi, cập nhật vào database
            if (empty($errors)) {
                $data = [
                    'code' => trim($_POST['code']),
                    'title' => trim($_POST['title']),
                    'discount_percent' => (int)$_POST['discount_percent'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'description' => trim($_POST['description'] ?? ''),
                    'benefits' => $benefits,
                    'status' => $_POST['status'] ?? 'active',
                    'cta' => trim($_POST['cta'] ?? ''),
                    'usage_limit' => (isset($_POST['usage_limit']) && $_POST['usage_limit'] !== '') ? (int)$_POST['usage_limit'] : null
                ];

                $result = $this->discountCode->update($id, $data);

                if ($result) {
                    header('Location: ' . BASE_URL . '?act=discounts');
                    exit;
                } else {
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật mã khuyến mại. Vui lòng thử lại.';
                }
            }
        }

        render('admin/discounts/edit.php', ['discount' => $discount, 'errors' => $errors]);
    }

    /**
     * Xóa mã khuyến mại (Admin)
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
