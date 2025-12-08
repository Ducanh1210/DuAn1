<?php
/**
 * MOVIES CONTROLLER - XỬ LÝ LOGIC QUẢN LÝ PHIM
 * 
 * CHỨC NĂNG:
 * - Quản lý phim (CRUD): danh sách, tạo, sửa, xóa, xem chi tiết
 * - Trang chủ client: hiển thị phim đang chiếu và sắp chiếu
 * - Chi tiết phim: thông tin phim, lịch chiếu, đánh giá
 * - Lịch chiếu: xem lịch chiếu theo ngày, rạp, phim
 * - Đánh giá phim: submit và hiển thị đánh giá
 * 
 * LUỒNG CHẠY TỔNG QUÁT:
 * 1. Nhận request từ index.php (routing)
 * 2. Xử lý logic (validate, lấy dữ liệu từ Model)
 * 3. Render View với dữ liệu đã xử lý
 */
class MoviesController
{
    public $movie; // Model Movie để tương tác với database
    public $genre; // Model Genre để lấy danh sách thể loại

    public function __construct()
    {
        // Khởi tạo các Models cần thiết
        $this->movie = new Movie(); // Model để query bảng movies
        $this->genre = new Genre(); // Model để query bảng genres
        require_once __DIR__ . '/../models/DiscountCode.php'; // Model cho mã giảm giá
    }

    /**
     * DANH SÁCH PHIM (ADMIN)
     * 
     * LUỒNG CHẠY:
     * 1. Lấy số trang từ URL (?page=1)
     * 2. Gọi Model Movie->paginate() để lấy dữ liệu phân trang
     * 3. Render view admin/movies/list.php với dữ liệu và phân trang
     * 
     * DỮ LIỆU LẤY:
     * - Từ $_GET: page (số trang)
     * - Từ Model Movie: paginate() -> danh sách phim, tổng số trang, tổng số record
     * - Hiển thị: danh sách phim với phân trang
     */
    public function list()
    {
        // Lấy số trang từ URL, mặc định là trang 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Đảm bảo page >= 1 (không cho số âm)
        
        // Gọi Model để lấy dữ liệu phân trang (5 phim mỗi trang)
        $result = $this->movie->paginate($page, 5);
        
        // Render view với dữ liệu và thông tin phân trang
        render('admin/movies/list.php', [
            'data' => $result['data'], // Danh sách phim
            'pagination' => [
                'currentPage' => $result['page'], // Trang hiện tại
                'totalPages' => $result['totalPages'], // Tổng số trang
                'total' => $result['total'], // Tổng số phim
                'perPage' => $result['perPage'] // Số phim mỗi trang
            ]
        ]);
    }

    /**
     * Hiển thị form tạo phim mới (Admin)
     */
    public function create()
{
    $errors = [];
    $genres = $this->genre->all();
    $uploaded_image = null;
    
    // validate form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ktra trường rỗng
        if (empty(trim($_POST['title'] ?? ''))) {
            $errors['title'] = "Bạn vui lòng nhập tên phim";
        }

        if (empty(trim($_POST['description'] ?? ''))) {
            $errors['description'] = "Bạn vui lòng nhập mô tả";
        }

        if (empty($_FILES['image']['name'])) {
            $errors['image'] = "Bạn vui lòng chọn hình ảnh";
        }

        if (empty(trim($_POST['duration'] ?? ''))) {
            $errors['duration'] = "Bạn vui lòng nhập thời lượng";
        }

        if (empty(trim($_POST['genre_id'] ?? ''))) {
            $errors['genre_id'] = "Bạn vui lòng chọn thể loại";
        }

        if (empty(trim($_POST['release_date'] ?? ''))) {
            $errors['release_date'] = "Bạn vui lòng chọn ngày phát hành";
        }

        if (empty(trim($_POST['format'] ?? ''))) {
            $errors['format'] = "Bạn vui lòng chọn loại";
        } elseif (!in_array(strtoupper(trim($_POST['format'])), ['2D', '3D'])) {
            $errors['format'] = "Loại phim chỉ có thể là 2D hoặc 3D";
        }

        // Kiểm tra và upload ảnh (chỉ khi không có lỗi validation ban đầu)
        $imagePath = null;
        if (empty($errors) && !empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors['image'] = 'Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif)';
            } else {
                $dest = 'image/' . basename($_FILES['image']['name']);
                $temp = $_FILES['image']['tmp_name'];
                if (move_uploaded_file($temp, $dest)) {
                    $imagePath = $dest;
                    $uploaded_image = $dest;
                } else {
                    $errors['image'] = 'Lỗi khi upload hình ảnh';
                }
            }
        }
        
        // Kiểm tra trailer nếu có
        if (!empty($_POST['trailer'])) {
            if (!filter_var($_POST['trailer'], FILTER_VALIDATE_URL)) {
                $errors['trailer'] = 'Link trailer phải là URL hợp lệ';
            }
        }

        // Kiểm tra end_date nếu có
        if (!empty($_POST['end_date'])) {
            if (strtotime($_POST['end_date']) === false) {
                $errors['end_date'] = 'Ngày kết thúc không hợp lệ';
            } elseif (!empty($_POST['release_date']) && strtotime($_POST['end_date']) < strtotime($_POST['release_date'])) {
                $errors['end_date'] = 'Ngày kết thúc phải sau ngày phát hành';
            }
        }

        // Nếu không có lỗi, lưu vào database
        if (empty($errors)) {
            // Tự động tính status dựa trên ngày
            $today = date('Y-m-d');
            $releaseDate = trim($_POST['release_date']);
            $endDate = trim($_POST['end_date'] ?? '');
            
            $status = 'active'; // Mặc định là active
            if ($endDate && $today > $endDate) {
                // Nếu đã quá ngày kết thúc
                $status = 'inactive';
            } elseif ($releaseDate && $today < $releaseDate) {
                // Nếu chưa đến ngày phát hành, vẫn để active để có thể hiển thị "Sắp chiếu"
                $status = 'active';
            }
            
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'image' => $imagePath,
                'trailer' => trim($_POST['trailer'] ?? ''),
                'duration' => trim($_POST['duration']),
                'release_date' => $releaseDate,
                'end_date' => $endDate ?: null,
                'format' => trim($_POST['format']),
                'original_language' => trim($_POST['original_language'] ?? ''),
                'subtitle_or_dub' => trim($_POST['subtitle_or_dub'] ?? ''),
                'age_rating' => trim($_POST['age_rating'] ?? ''),
                'producer' => trim($_POST['producer'] ?? ''),
                'genre_id' => trim($_POST['genre_id']),
                'status' => $status
            ];
            $this->movie->insert($data);
            header('Location: ' . BASE_URL . '?act=/');
            exit;
        }
    }

    render('admin/movies/create.php', ['errors' => $errors, 'genres' => $genres, 'uploaded_image' => $uploaded_image]);
}


    /**
     * Hiển thị form sửa phim (Admin)
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=/');
            exit;
        }

        $movie = $this->movie->find($id);
        if (!$movie) {
            header('Location: ' . BASE_URL . '?act=/');
            exit;
        }

        $errors = [];
        $genres = $this->genre->all();

        // validate form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // kiểm tra trường rỗng
            if (empty(trim($_POST['title'] ?? ''))) {
                $errors['title'] = "Bạn vui lòng nhập tên phim";
            }

            if (empty(trim($_POST['description'] ?? ''))) {
                $errors['description'] = "Bạn vui lòng nhập mô tả";
            }

            if (empty(trim($_POST['duration'] ?? ''))) {
                $errors['duration'] = "Bạn vui lòng nhập thời lượng";
            }

            if (empty(trim($_POST['genre_id'] ?? ''))) {
                $errors['genre_id'] = "Bạn vui lòng chọn thể loại";
            }

            if (empty(trim($_POST['release_date'] ?? ''))) {
                $errors['release_date'] = "Bạn vui lòng chọn ngày phát hành";
            }

            if (empty(trim($_POST['format'] ?? ''))) {
                $errors['format'] = "Bạn vui lòng chọn loại";
            }

            // Lấy thông tin phim hiện tại
            $imagePath = $movie['image']; // Giữ nguyên ảnh cũ nếu không có ảnh mới

            // Kiểm tra và upload ảnh mới nếu có
            if (empty($errors) && !empty($_FILES['image']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $errors['image'] = 'Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif)';
                } else {
                    $dest = 'image/' . basename($_FILES['image']['name']);
                    $temp = $_FILES['image']['tmp_name'];
                    if (move_uploaded_file($temp, $dest)) {
                        // Xóa hình ảnh cũ nếu có
                        if ($imagePath && file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        $imagePath = $dest; // Cập nhật ảnh mới
                    } else {
                        $errors['image'] = 'Lỗi khi upload hình ảnh';
                    }
                }
            }

            // Kiểm tra trailer nếu có
            if (!empty($_POST['trailer'])) {
                if (!filter_var($_POST['trailer'], FILTER_VALIDATE_URL)) {
                    $errors['trailer'] = 'Link trailer phải là URL hợp lệ';
                }
            }

            // Kiểm tra end_date nếu có
            if (!empty($_POST['end_date'])) {
                if (strtotime($_POST['end_date']) === false) {
                    $errors['end_date'] = 'Ngày kết thúc không hợp lệ';
                } elseif (!empty($_POST['release_date']) && strtotime($_POST['end_date']) < strtotime($_POST['release_date'])) {
                    $errors['end_date'] = 'Ngày kết thúc phải sau ngày phát hành';
                }
            }

            // Nếu không có lỗi, cập nhật phim trong cơ sở dữ liệu
            if (empty($errors)) {
                // Tự động tính status dựa trên ngày
                $today = date('Y-m-d');
                $releaseDate = trim($_POST['release_date']);
                $endDate = trim($_POST['end_date'] ?? '');
                
                $status = 'active'; // Mặc định là active
                if ($endDate && $today > $endDate) {
                    // Nếu đã quá ngày kết thúc
                    $status = 'inactive';
                } elseif ($releaseDate && $today < $releaseDate) {
                    // Nếu chưa đến ngày phát hành, vẫn để active để có thể hiển thị "Sắp chiếu"
                    $status = 'active';
                }
                
                $data = [
                    'title' => trim($_POST['title']),
                    'description' => trim($_POST['description']),
                    'image' => $imagePath,
                    'trailer' => trim($_POST['trailer'] ?? ''),
                    'duration' => trim($_POST['duration']),
                    'release_date' => $releaseDate,
                    'end_date' => $endDate ?: null,
                    'format' => trim($_POST['format']),
                    'original_language' => trim($_POST['original_language'] ?? ''),
                    'subtitle_or_dub' => trim($_POST['subtitle_or_dub'] ?? ''),
                    'age_rating' => trim($_POST['age_rating'] ?? ''),
                    'producer' => trim($_POST['producer'] ?? ''),
                    'genre_id' => trim($_POST['genre_id']),
                    'status' => $status
                ];
                $this->movie->update($id, $data);
                header('Location: ' . BASE_URL . '?act=/');
                exit;
            }
        }

        render('admin/movies/edit.php', ['movie' => $movie, 'errors' => $errors, 'genres' => $genres]);
    }


    /**
     * Xóa phim (Admin)
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=/');
            exit;
        }

        $movie = $this->movie->find($id);
        if ($movie) {
            // Xóa hình ảnh nếu có
            if (!empty($movie['image']) && file_exists($movie['image'])) {
                unlink($movie['image']);
            }
            $this->movie->delete($id);
        }

        header('Location: ' . BASE_URL . '?act=/');
        exit;
    }

    /**
     * Xem chi tiết phim (Admin)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . '?act=/');
            exit;
        }

        $movie = $this->movie->find($id);
        if (!$movie) {
            header('Location: ' . BASE_URL . '?act=/');
            exit;
        }

        render('admin/movies/show.php', ['movie' => $movie]);
    }

    /**
     * Hiển thị trang chủ client (public)
     */
    public function trangchu()
    {
        // Lấy tham số tìm kiếm/lọc
        $searchKeyword = $_GET['search'] ?? '';
        $cinemaId = $_GET['cinema'] ?? '';

        // Lấy danh sách phim đang chiếu (có lọc nếu có)
        $moviesNowShowing = $this->getNowShowing($searchKeyword, $cinemaId);

        // Lấy danh sách phim sắp chiếu (chỉ lọc theo tên phim)
        $moviesComingSoon = $this->getComingSoon($searchKeyword);

        // Lấy danh sách rạp để hiển thị trong filter
        $cinemas = $this->getCinemas();

        // Include view client (không dùng layout chung)
        require_once __DIR__ . '/../views/client/trangchu.php';
        exit; // Dừng lại để không render layout admin
    }

    /**
     * Hiển thị trang khuyến mãi (Client)
     */
    public function khuyenmai()
    {
        $membershipBenefits = [
            [
                'icon' => 'bi-gift',
                'title' => 'Quà tặng mỗi tháng',
                'desc' => 'Voucher đồ ăn, vé miễn phí, suất chiếu đặc biệt dành riêng cho hội viên.'
            ],
            [
                'icon' => 'bi-lightning-charge',
                'title' => 'Đặc quyền ưu tiên',
                'desc' => 'Check-in và nhận vé nhanh, vào phòng chiếu sớm hơn 10 phút.'
            ],
            [
                'icon' => 'bi-graph-up',
                'title' => 'Tích điểm đa tầng',
                'desc' => 'Tự động nhân 1.5-3 lần điểm thưởng dựa trên hạng thẻ hiện tại.'
            ],
        ];

        $faqs = [
            [
                'question' => 'Làm sao để nhận mã khuyến mãi?',
                'answer' => 'Bạn chỉ cần đăng nhập tài khoản TicketHub, vào trang Khuyến mãi và bấm “Sao chép mã”. Mã sẽ lưu trong ví voucher và tự áp dụng ở bước thanh toán.'
            ],
            [
                'question' => 'Tôi có thể dùng nhiều mã trong cùng một đơn?',
                'answer' => 'Mỗi đơn hàng chỉ áp dụng 01 mã giảm giá. Tuy nhiên bạn có thể kết hợp thêm ưu đãi tích điểm và thẻ thành viên.'
            ],
            [
                'question' => 'Vé đã giảm giá có được hoàn/đổi?',
                'answer' => 'Bạn vẫn có thể đổi suất chiếu trước giờ chiếu ít nhất 2 tiếng. Tiền chênh lệch (nếu có) sẽ được thông báo trong bước xác nhận.'
            ],
        ];

        // Lấy discount codes từ database
        $discountCodeModel = new DiscountCode();
        $discountCodes = $discountCodeModel->all();
        
        // Chuyển đổi discount codes thành format promotions
        $dbPromotions = [];
        foreach ($discountCodes as $dc) {
            if ($dc['status'] === 'active') {
                $dbPromotions[] = [
                    'title' => 'Mã giảm giá: ' . $dc['code'],
                    'tag' => 'general',
                    'status' => 'ongoing',
                    'period' => ($dc['start_date'] ?? '') . ' - ' . ($dc['end_date'] ?? ''),
                    'description' => 'Giảm ' . $dc['discount_percent'] . '% cho đơn hàng của bạn.',
                    'benefits' => [
                        'Giảm ' . $dc['discount_percent'] . '% cho tổng đơn hàng',
                        'Áp dụng cho tất cả suất chiếu',
                        'Có hiệu lực từ ' . ($dc['start_date'] ?? '') . ' đến ' . ($dc['end_date'] ?? '')
                    ],
                    'code' => $dc['code'],
                    'cta' => 'Sử dụng mã'
                ];
            }
        }

        $heroStats = [
            ['label' => 'Ưu đãi đang diễn ra', 'value' => count($dbPromotions)],
            ['label' => 'Khách nhận mã trong tuần', 'value' => '12.457'],
            ['label' => 'Điểm thưởng đã tặng', 'value' => '3.2M']
        ];

        renderClient('client/khuyenmai.php', [
            'promotions' => $dbPromotions,
            'membershipBenefits' => $membershipBenefits,
            'faqs' => $faqs,
            'heroStats' => $heroStats
        ], 'Khuyến mãi');
        exit;
    }

    /**
     * API kiểm tra mã giảm giá (thay thế VoucherController->checkVoucher)
     */
    public function checkVoucher()
    {
        header('Content-Type: application/json');
        
        $code = $_GET['code'] ?? '';
        $totalAmount = floatval($_GET['total_amount'] ?? 0);
        
        if (empty($code)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập mã giảm giá'
            ]);
            exit;
        }
        
        $discountCodeModel = new DiscountCode();
        $discount = $discountCodeModel->validateDiscountCode($code, $totalAmount);
        
        if ($discount) {
            echo json_encode([
                'success' => true,
                'message' => 'Áp dụng thành công! Giảm ' . $discount['discount_percent'] . '%',
                'discount_code' => $discount
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
            ]);
        }
        exit;
    }

    /**
     * Lấy danh sách phim đang chiếu
     */
    private function getNowShowing($searchKeyword = '', $cinemaId = '')
    {
        try {
            $sql = "SELECT DISTINCT movies.*, movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id";
            
            $where = ["movies.status = 'active'", 
                     "(movies.release_date <= CURDATE() OR movies.release_date IS NULL)",
                     "(movies.end_date >= CURDATE() OR movies.end_date IS NULL)"];
            
            $params = [];

            // Lọc theo tên phim
            if (!empty($searchKeyword)) {
                $where[] = "movies.title LIKE :search";
                $params[':search'] = '%' . $searchKeyword . '%';
            }

            // Lọc theo rạp
            if (!empty($cinemaId)) {
                $sql .= " INNER JOIN showtimes ON movies.id = showtimes.movie_id
                         INNER JOIN rooms ON showtimes.room_id = rooms.id";
                $where[] = "rooms.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }

            $sql .= " WHERE " . implode(' AND ', $where);
            $sql .= " ORDER BY movies.release_date DESC LIMIT 20";
            
            $stmt = $this->movie->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy danh sách phim sắp chiếu
     */
    private function getComingSoon($searchKeyword = '')
    {
        try {
            $sql = "SELECT movies.*, movie_genres.name AS genre_name
                    FROM movies
                    LEFT JOIN movie_genres ON movies.genre_id = movie_genres.id
                    WHERE movies.status = 'active' 
                    AND movies.release_date > CURDATE()";
            
            $params = [];

            // Lọc theo tên phim
            if (!empty($searchKeyword)) {
                $sql .= " AND movies.title LIKE :search";
                $params[':search'] = '%' . $searchKeyword . '%';
            }

            $sql .= " ORDER BY movies.release_date ASC LIMIT 20";
            
            $stmt = $this->movie->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lấy danh sách rạp
     */
    private function getCinemas()
    {
        try {
            $sql = "SELECT * FROM cinemas ORDER BY name ASC";
            $stmt = $this->movie->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }


    /**
     * Hiển thị trang lịch chiếu (client)
     */
    public function lichchieu()
    {
        require_once './models/Showtime.php';
        require_once './models/Cinema.php';
        $showtimeModel = new Showtime();
        $cinemaModel = new Cinema();

        // Lấy tham số tìm kiếm/lọc
        $searchKeyword = $_GET['search'] ?? '';
        $cinemaId = $_GET['cinema'] ?? '';
        $movieId = $_GET['movie'] ?? ''; // Tham số phim từ trang chủ

        // Lấy ngày được chọn từ URL hoặc mặc định là hôm nay
        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        // Lấy danh sách ngày (7 ngày từ hôm nay)
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("+$i days"));
            $dates[] = [
                'date' => $date,
                'formatted' => date('d-m-Y', strtotime($date))
            ];
        }

        // Lấy danh sách lịch chiếu theo ngày (đã lọc theo rạp nếu có, và theo phim nếu có)
        $showtimes = $showtimeModel->getByDate($selectedDate, $cinemaId, $movieId);

        // Nhóm lịch chiếu theo phim và lọc theo tên phim
        $movies = [];
        foreach ($showtimes as $st) {
            $movieIdItem = $st['movie_id'];

            // Lọc theo tên phim
            if (!empty($searchKeyword) && stripos($st['movie_title'], $searchKeyword) === false) {
                continue;
            }

            // Nếu có tham số movie_id, chỉ hiển thị phim đó
            if (!empty($movieId) && $movieIdItem != $movieId) {
                continue;
            }

            if (!isset($movies[$movieIdItem])) {
                $movies[$movieIdItem] = [
                    'id' => $st['movie_id'],
                    'title' => $st['movie_title'],
                    'image' => $st['movie_image'],
                    'duration' => $st['movie_duration'],
                    'original_language' => $st['movie_original_language'],
                    'age_rating' => $st['movie_age_rating'],
                    'release_date' => $st['movie_release_date'],
                    'format' => $st['movie_format'],
                    'genre_name' => $st['genre_name'],
                    'showtimes' => [],
                    'showtime_ids' => []
                ];
            }

            // Thêm showtime vào phim
            $movies[$movieIdItem]['showtimes'][] = $st['start_time'];
            $movies[$movieIdItem]['showtime_ids'][] = $st['id'];
        }

        // Chuyển sang array và sắp xếp
        $movies = array_values($movies);
        usort($movies, function ($a, $b) {
            return strcmp($a['title'], $b['title']);
        });

        // Lấy tất cả rạp
        $cinemas = $this->getCinemas();
        
        // Kiểm tra rạp nào có showtime trong ngày được chọn
        $cinemasWithShowtimes = [];
        try {
            $sql = "SELECT DISTINCT cinemas.id 
                    FROM cinemas
                    INNER JOIN rooms ON cinemas.id = rooms.cinema_id
                    INNER JOIN showtimes ON rooms.id = showtimes.room_id
                    INNER JOIN movies ON showtimes.movie_id = movies.id
                    WHERE showtimes.show_date = :selected_date
                    AND movies.status = 'active'
                    AND (movies.release_date <= CURDATE() OR movies.release_date IS NULL)
                    AND (movies.end_date >= CURDATE() OR movies.end_date IS NULL)";
            
            $params = [':selected_date' => $selectedDate];
            
            // Nếu có tham số movie_id, chỉ lấy rạp có phim đó trong ngày đó
            if (!empty($movieId)) {
                $sql .= " AND showtimes.movie_id = :movie_id";
                $params[':movie_id'] = $movieId;
            }
            
            $stmt = $cinemaModel->conn->prepare($sql);
            $stmt->execute($params);
            $cinemasWithShowtimes = array_column($stmt->fetchAll(), 'id');
        } catch (Exception $e) {
            $cinemasWithShowtimes = [];
        }
        
        // Đánh dấu rạp nào có showtime
        foreach ($cinemas as &$cinema) {
            $cinema['has_showtime'] = in_array($cinema['id'], $cinemasWithShowtimes);
        }
        
        // Giữ lại biến cinemasWithMovie để tương thích với view (không dùng nữa nhưng giữ để tránh lỗi)
        $cinemasWithMovie = [];

        // Sử dụng layout client chung
        renderClient('client/lichchieu.php', [
            'movies' => $movies,
            'dates' => $dates,
            'selectedDate' => $selectedDate,
            'searchKeyword' => $searchKeyword,
            'cinemaId' => $cinemaId,
            'cinemas' => $cinemas,
            'movieId' => $movieId,
            'cinemasWithMovie' => $cinemasWithMovie
        ], 'Lịch Chiếu');
        exit;
    }

    /**
     * Hiển thị trang chi tiết phim (client)
     */
    public function movieDetail()
    {
        require_once './models/Showtime.php';
        $showtimeModel = new Showtime();
        
        $movieId = $_GET['id'] ?? null;
        if (!$movieId) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy thông tin phim
        $movie = $this->movie->find($movieId);
        if (!$movie) {
            header('Location: ' . BASE_URL . '?act=trangchu');
            exit;
        }

        // Lấy ngày được chọn từ URL hoặc mặc định là hôm nay
        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        // Lấy danh sách ngày từ hôm nay đến end_date của phim
        $today = date('Y-m-d');
        $startDate = $today;
        
        // Nếu phim chưa khởi chiếu, bắt đầu từ release_date
        if (!empty($movie['release_date']) && $movie['release_date'] > $today) {
            $startDate = $movie['release_date'];
        }
        
        // Kết thúc ở end_date hoặc 7 ngày từ hôm nay nếu không có end_date
        $endDate = !empty($movie['end_date']) ? $movie['end_date'] : date('Y-m-d', strtotime('+7 days'));
        
        // Đảm bảo end_date không nhỏ hơn startDate
        if ($endDate < $startDate) {
            $endDate = $startDate;
        }
        
        // Tạo danh sách ngày
        $dates = [];
        $currentDate = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        
        while ($currentDate <= $endTimestamp) {
            $dateStr = date('Y-m-d', $currentDate);
            $dayOfWeek = (int)date('w', $currentDate);
            $dates[] = [
                'date' => $dateStr,
                'formatted' => date('d-m-Y', $currentDate),
                'dayname' => $this->getDayNameVietnamese($dayOfWeek),
                'daynum' => date('d', $currentDate),
                'month' => date('m', $currentDate)
            ];
            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Lấy cinema_id từ URL nếu có (để lọc suất chiếu theo rạp)
        $cinemaId = $_GET['cinema'] ?? '';

        // Lấy lịch chiếu cho phim và ngày được chọn (đã lọc theo rạp nếu có)
        $showtimes = $showtimeModel->getByMovieAndDate($movieId, $selectedDate, $cinemaId);

        // Kiểm tra user đã đăng nhập và đã mua vé phim này chưa
        require_once __DIR__ . '/../commons/auth.php';
        $isLoggedIn = isLoggedIn();
        $userId = $isLoggedIn ? ($_SESSION['user_id'] ?? null) : null;
        $hasPurchased = false;
        $existingComment = null;
        
        if ($userId) {
            require_once __DIR__ . '/../models/Booking.php';
            require_once __DIR__ . '/../models/Comment.php';
            $bookingModel = new Booking();
            $commentModel = new Comment();
            
            // Kiểm tra đã mua vé chưa
            $hasPurchased = $bookingModel->hasPurchasedMovie($userId, $movieId);
            
            // Kiểm tra đã đánh giá chưa
            if ($hasPurchased) {
                $existingComment = $commentModel->getByUserAndMovie($userId, $movieId);
            }
        }
        
        // Lấy danh sách bình luận của phim
        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $comments = $commentModel->getByMovie($movieId);
        $ratingStats = $commentModel->getAverageRating($movieId);

        renderClient('client/movies.php', [
            'movie' => $movie,
            'showtimes' => $showtimes,
            'dates' => $dates,
            'selectedDate' => $selectedDate,
            'cinemaId' => $cinemaId,
            'isLoggedIn' => $isLoggedIn,
            'hasPurchased' => $hasPurchased,
            'existingComment' => $existingComment,
            'comments' => $comments,
            'ratingStats' => $ratingStats
        ], htmlspecialchars($movie['title']));
        exit;
    }

    /**
     * Submit review từ trang chi tiết phim
     */
    public function submitMovieReview()
    {
        require_once __DIR__ . '/../commons/auth.php';
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . '?act=dangnhap');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movieId = $_POST['movie_id'] ?? null;
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
            $content = trim($_POST['content'] ?? '');

            // Validation
            if (!$movieId) {
                $errors[] = 'Thông tin không hợp lệ';
            }

            // Kiểm tra user đã mua vé phim này chưa
            require_once __DIR__ . '/../models/Booking.php';
            $bookingModel = new Booking();
            if (!$bookingModel->hasPurchasedMovie($userId, $movieId)) {
                $errors[] = 'Bạn cần mua vé phim này trước khi đánh giá';
            }

            if ($rating === null || $rating < 1 || $rating > 5) {
                $errors[] = 'Vui lòng chọn đánh giá từ 1 đến 5 sao';
            }

            if (empty($content)) {
                $errors[] = 'Vui lòng nhập nội dung bình luận';
            } elseif (strlen($content) < 10) {
                $errors[] = 'Nội dung bình luận phải có ít nhất 10 ký tự';
            } elseif (strlen($content) > 1000) {
                $errors[] = 'Nội dung bình luận không được vượt quá 1000 ký tự';
            }

            if (empty($errors)) {
                require_once __DIR__ . '/../models/Comment.php';
                $commentModel = new Comment();

                // Lấy booking gần nhất của user cho phim này để lấy cinema_id
                $latestBooking = $bookingModel->getLatestBookingByUserAndMovie($userId, $movieId);
                $cinemaId = $latestBooking['cinema_id'] ?? null;

                if (!$cinemaId) {
                    $errors[] = 'Không thể xác định rạp chiếu phim. Vui lòng bình luận từ trang đặt vé của bạn.';
                } else {
                    // Kiểm tra đã bình luận chưa cho phim này ở rạp này
                    $existingComment = $commentModel->getByUserAndMovie($userId, $movieId, $cinemaId);

                    if ($existingComment) {
                        // Đã đánh giá rồi, không cho phép đánh giá lại
                        $_SESSION['error'] = 'Bạn đã bình luận phim này ở rạp này rồi. Mỗi tài khoản chỉ được bình luận 1 lần cho mỗi phim ở mỗi rạp.';
                        header('Location: ' . BASE_URL . '?act=movies&id=' . $movieId);
                        exit;
                    }

                    // Tạo bình luận mới
                    $result = $commentModel->insert([
                        'user_id' => $userId,
                        'movie_id' => $movieId,
                        'cinema_id' => $cinemaId,
                        'rating' => $rating,
                        'content' => $content
                    ]);

                    if ($result) {
                        header('Location: ' . BASE_URL . '?act=movies&id=' . $movieId . '&review_success=1');
                        exit;
                    } else {
                        $errors[] = 'Bạn đã bình luận phim này ở rạp này rồi. Mỗi tài khoản chỉ được bình luận 1 lần cho mỗi phim ở mỗi rạp.';
                    }
                }
            }
        }

        // Nếu có lỗi, quay lại trang chi tiết phim
        $movieId = $_GET['id'] ?? $_POST['movie_id'] ?? null;
        if ($movieId) {
            $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Có lỗi xảy ra';
            header('Location: ' . BASE_URL . '?act=movies&id=' . $movieId . '&error=' . urlencode($errorMsg));
        } else {
            header('Location: ' . BASE_URL . '?act=trangchu');
        }
        exit;
    }

    /**
     * Hiển thị trang giới thiệu
     */
    public function gioithieu()
    {
        renderClient('client/gioithieu.php', [], 'Giới Thiệu');
    }

    
    /**
     * Hiển thị trang liên hệ
     */
    public function lienhe()
    {
        renderClient('client/lienhe.php', [], 'Liên Hệ');
    }


    /**
     * Lấy tên thứ trong tuần bằng tiếng Việt
     */
    private function getDayNameVietnamese($dayOfWeek)
    {
        $days = [
            0 => 'Chủ nhật',
            1 => 'Thứ hai',
            2 => 'Thứ ba',
            3 => 'Thứ tư',
            4 => 'Thứ năm',
            5 => 'Thứ sáu',
            6 => 'Thứ bảy'
        ];
        return $days[$dayOfWeek] ?? '';
    }
}

?>