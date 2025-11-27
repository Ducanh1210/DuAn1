<?php
/**
 * FUNCTION.PHP - FILE HELPER CHUNG
 * 
 * CHỨC NĂNG:
 * - Debug: hiển thị dữ liệu để debug
 * - Kết nối database: connectDB()
 * - Render view: render() (admin), renderClient() (client)
 * - 404: notFound()
 * 
 * LUỒNG CHẠY RENDER:
 * 1. Controller gọi render() hoặc renderClient()
 * 2. Extract data array thành các biến
 * 3. Load layout (main.php hoặc client_layout.php)
 * 4. Layout include view tương ứng
 * 5. View sử dụng các biến đã extract để hiển thị
 */

/**
 * DEBUG - HIỂN THỊ DỮ LIỆU ĐỂ DEBUG
 * 
 * Mục đích: Hiển thị cấu trúc dữ liệu (array, object) để kiểm tra
 * Cách hoạt động: 
 * 1. Format dữ liệu bằng print_r()
 * 2. Dừng chương trình bằng die()
 * 
 * Sử dụng: debug($variable); // Hiển thị và dừng
 */
function debug($data)
{
    echo '<pre>'; // Thẻ pre để format
    print_r($data); // In cấu trúc dữ liệu
    die(); // Dừng chương trình
}

/**
 * NOT FOUND - HIỂN THỊ LỖI 404
 * 
 * Mục đích: Hiển thị trang 404 khi route không tồn tại
 * Cách hoạt động:
 * 1. Set HTTP status code = 404
 * 2. Hiển thị thông báo
 * 3. Dừng chương trình
 */
function notFound()
{
    http_response_code(404); // Set HTTP status code
    echo '404 - Page Not Found';
    exit; // Dừng chương trình
}


/**
 * KẾT NỐI DATABASE QUA PDO
 * 
 * Mục đích: Tạo kết nối PDO đến MySQL database
 * Cách hoạt động:
 * 1. Lấy thông tin kết nối từ env.php (DB_HOST, DB_PORT, DB_NAME, etc.)
 * 2. Tạo PDO connection
 * 3. Cấu hình PDO (error mode, fetch mode)
 * 4. Trả về connection object
 * 
 * Dữ liệu lấy từ: env.php (DB_HOST, DB_PORT, DB_NAME, DB_USERNAME, DB_PASSWORD)
 * Trả về: PDO connection object để query database
 */
function connectDB()
{
    // Lấy thông tin kết nối từ file cấu hình
    $host = DB_HOST; // Địa chỉ database server
    $port = DB_PORT; // Port (mặc định 3306)
    $dbname = DB_NAME; // Tên database
    try {
        // Tạo PDO connection
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // Cài đặt chế độ báo lỗi là xử lý ngoại lệ
        // Khi có lỗi SQL, PDO sẽ throw Exception thay vì chỉ warning
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cài đặt chế độ trả dữ liệu
        // FETCH_ASSOC: trả về array với key là tên cột (không có số index)
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn; // Trả về connection object
    } catch (PDOException $e) {
        // Nếu lỗi kết nối, hiển thị lỗi và dừng
        debug("Connection false:" . $e->getMessage());
    }
}

/**
 * RENDER VIEW VỚI LAYOUT ADMIN
 * 
 * Mục đích: Render view admin với layout chung (sidebar, header)
 * Cách hoạt động:
 * 1. Extract data array thành các biến (ví dụ: $data['movies'] -> $movies)
 * 2. Lưu viewPath vào $GLOBALS để layout có thể include
 * 3. Load layout main.php
 * 4. Layout sẽ include view tương ứng
 * 
 * Luồng chạy:
 * Controller -> render('admin/movies/list.php', ['data' => $movies])
 * -> Extract: $data = $movies
 * -> Load: views/layout/main.php
 * -> main.php include: views/admin/movies/list.php
 * -> list.php sử dụng biến $data để hiển thị
 * 
 * @param string $viewPath Đường dẫn đến file view (relative từ thư mục views/)
 * @param array $data Dữ liệu truyền vào view (sẽ được extract thành biến)
 */
function render($viewPath, $data = [])
{
    // Extract data array thành các biến
    // Ví dụ: ['movies' => $movies] -> tạo biến $movies
    extract($data);
    
    // Set biến $viewPath vào $GLOBALS để layout có thể include
    $GLOBALS['viewPath'] = $viewPath;
    
    // Include layout admin (có sidebar, header)
    // Layout sẽ tự động include view tương ứng
    require_once __DIR__ . '/../views/layout/main.php';
}

/**
 * RENDER VIEW VỚI LAYOUT CLIENT
 * 
 * Mục đích: Render view client với layout chung (header, footer)
 * Cách hoạt động:
 * 1. Extract data array thành các biến
 * 2. Lưu clientViewPath và pageTitle vào $GLOBALS
 * 3. Load layout client_layout.php
 * 4. Layout sẽ include view tương ứng
 * 
 * Luồng chạy:
 * Controller -> renderClient('client/trangchu.php', ['movies' => $movies], 'Trang chủ')
 * -> Extract: $movies
 * -> Load: views/layout/client_layout.php
 * -> client_layout.php include: views/client/trangchu.php
 * -> trangchu.php sử dụng biến $movies để hiển thị
 * 
 * @param string $viewPath Đường dẫn đến file view (relative từ thư mục views/client/)
 * @param array $data Dữ liệu truyền vào view
 * @param string $pageTitle Tiêu đề trang (hiển thị trong thẻ <title>)
 */
function renderClient($viewPath, $data = [], $pageTitle = 'Trang chủ')
{
    // Extract data array thành các biến
    extract($data);
    
    // Set biến để layout có thể include view
    $GLOBALS['clientViewPath'] = $viewPath;
    
    // Set page title để hiển thị trong thẻ <title>
    $GLOBALS['pageTitle'] = $pageTitle;
    
    // Include layout client (có header, footer)
    // Layout sẽ tự động include view tương ứng
    require_once __DIR__ . '/../views/layout/client_layout.php';
}