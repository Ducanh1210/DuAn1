<?php
// hỗ trợ show bất cứ data nào
function debug($data)
{
    echo '<pre>';
    print_r($data);
    die();
}

function notFound()
{
    http_response_code(404);
    echo '404 - Page Not Found';
    exit;
}


//kết nối CSDL qua PDO

function connectDB()
{
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;
    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    } catch (PDOException $e) {
        debug("Connection false:" . $e->getMessage());
    }
}

/**
 * Render view với layout chung (Admin)
 * @param string $viewPath Đường dẫn đến file view (relative từ thư mục views/)
 * @param array $data Dữ liệu truyền vào view
 */
function render($viewPath, $data = [])
{
    // Extract data array thành các biến
    extract($data);
    
    // Set biến $viewPath để layout có thể include
    $GLOBALS['viewPath'] = $viewPath;
    
    // Include layout, layout sẽ include view
    require_once __DIR__ . '/../views/layout/main.php';
}

/**
 * Render view với layout client (Header + Footer chung)
 * @param string $viewPath Đường dẫn đến file view (relative từ thư mục views/client/)
 * @param array $data Dữ liệu truyền vào view
 * @param string $pageTitle Tiêu đề trang
 */
function renderClient($viewPath, $data = [], $pageTitle = 'Trang chủ')
{
    // Extract data array thành các biến
    extract($data);
    
    // Set biến để layout có thể include view
    $GLOBALS['clientViewPath'] = $viewPath;
    
    // Set page title
    $GLOBALS['pageTitle'] = $pageTitle;
    
    // Include layout client, layout sẽ include view
    require_once __DIR__ . '/../views/layout/client_layout.php';
}