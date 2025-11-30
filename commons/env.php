<?php
/**
 * ENV.PHP - FILE CẤU HÌNH MÔI TRƯỜNG
 * 
 * CHỨC NĂNG:
 * - Định nghĩa các hằng số cấu hình cho toàn bộ ứng dụng
 * - Cấu hình database connection
 * - Cấu hình BASE_URL cho links và redirects
 * 
 * LUỒNG CHẠY:
 * 1. File này được load đầu tiên trong index.php (dòng 30)
 * 2. Tất cả các file khác có thể sử dụng các hằng số này
 * 3. Khi cần thay đổi cấu hình, chỉ cần sửa file này
 * 
 * CÁC HẰNG SỐ:
 * - BASE_URL: URL gốc của website (dùng cho links, redirects)
 * - DB_HOST: Địa chỉ database server (thường là localhost)
 * - DB_PORT: Port của database (MySQL mặc định là 3306)
 * - DB_USERNAME: Tên đăng nhập database
 * - DB_PASSWORD: Mật khẩu database
 * - DB_NAME: Tên database
 * 
 * SỬ DỤNG:
 * - Được load trong index.php trước tất cả các file khác
 * - Các Model sử dụng để kết nối database (connectDB() trong function.php)
 * - Các Controller sử dụng BASE_URL để redirect và tạo links
 */

// URL gốc của website - dùng cho tất cả links và redirects
// Ví dụ: http://localhost/DuAn1/ hoặc https://yourdomain.com/
define('BASE_URL', 'http://localhost/DuAn1/');

// Cấu hình kết nối database MySQL
define('DB_HOST', 'localhost');      // Địa chỉ database server
define('DB_PORT', 3306);             // Port database (MySQL mặc định 3306)
define('DB_USERNAME', value: 'root'); // Tên đăng nhập database
define('DB_PASSWORD', value: '');     // Mật khẩu database (để trống nếu không có)
define('DB_NAME', value: 'duan1');   // Tên database
?>
