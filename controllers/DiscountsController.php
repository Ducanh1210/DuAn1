<?php
// DISCOUNTS CONTROLLER - Xử lý logic quản lý chương trình khuyến mãi
// Chức năng: CRUD chương trình khuyến mãi (Admin)
class DiscountsController
{
    public $discountCode; // Model DiscountCode để tương tác với database
    public $movie; // Model Movie để lấy danh sách phim

    public function __construct()
    {
        $this->discountCode = new DiscountCode(); // Khởi tạo Model DiscountCode
        $this->movie = new Movie(); // Khởi tạo Model Movie
    }

    // Danh sách chương trình khuyến mãi (Admin)
    public function list()
    {
        $data = $this->discountCode->all();

        render('admin/discounts/list.php', [
            'data' => $data
        ]);
    }

    // Hiển thị form tạo chương trình khuyến mãi mới (Admin) - validate, insert vào DB
    public function create()
    {
        $errors = [];
        $movies = $this->movie->getBasicList();

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedMovieId = $this->resolveMovieSelection($movies, $_POST['movie_id'] ?? null, $errors);

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
            } elseif (!is_numeric($_POST['discount_percent'])) {
                $errors['discount_percent'] = "Phần trăm giảm giá phải là số";
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

            if (!empty($_POST['max_discount']) && !is_numeric($_POST['max_discount'])) {
                $errors['max_discount'] = "Giá trị giảm tối đa phải là số";
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
                    'discount_percent' => (float)$_POST['discount_percent'],
                    'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null,
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'movie_id' => $selectedMovieId,
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
                    $errors['general'] = 'Có lỗi xảy ra khi thêm mã giảm giá. Vui lòng thử lại.';
                }
            }
        }

        render('admin/discounts/create.php', [
            'errors' => $errors,
            'movies' => $movies
        ]);
    }

    // Hiển thị form sửa chương trình khuyến mãi (Admin) - validate, update
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

        if (!empty($discount['benefits']) && is_string($discount['benefits'])) {
            $decodedBenefits = json_decode($discount['benefits'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedBenefits)) {
                $discount['benefits'] = $decodedBenefits;
            }
        }

        $errors = [];
        $movies = $this->movie->getBasicList();

        // Validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedMovieId = $this->resolveMovieSelection($movies, $_POST['movie_id'] ?? null, $errors);

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
            } elseif (!is_numeric($_POST['discount_percent'])) {
                $errors['discount_percent'] = "Phần trăm giảm giá phải là số";
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
                    'discount_percent' => (float)$_POST['discount_percent'],
                    'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : null,
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'movie_id' => $selectedMovieId,
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
                    $errors['general'] = 'Có lỗi xảy ra khi cập nhật mã giảm giá. Vui lòng thử lại.';
                }
            }
        }

        render('admin/discounts/edit.php', [
            'discount' => $discount,
            'errors' => $errors,
            'movies' => $movies
        ]);
    }

    // Xóa chương trình khuyến mãi (Admin)
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

    // Xác thực lựa chọn phim và trả về movie_id hợp lệ hoặc null (private helper)
    private function resolveMovieSelection(array $movies, $inputValue, array &$errors)
    {
        if ($inputValue === null || $inputValue === '') {
            return null;
        }

        $selectedMovieId = (int)$inputValue;
        if ($selectedMovieId <= 0) {
            $errors['movie_id'] = "Phim được chọn không hợp lệ.";
            return null;
        }

        foreach ($movies as $movie) {
            if ((int)($movie['id'] ?? 0) === $selectedMovieId) {
                return $selectedMovieId;
            }
        }

        $errors['movie_id'] = "Phim được chọn không hợp lệ.";
        return null;
    }
}
