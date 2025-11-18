-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2025 at 03:24 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `du_an_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `showtime_id` int DEFAULT NULL,
  `discount_id` int DEFAULT NULL,
  `booking_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `booked_seats` varchar(255) DEFAULT NULL,
  `seat_type` varchar(20) DEFAULT NULL,
  `customer_type` varchar(20) DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `final_amount` decimal(12,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `booking_code` varchar(50) DEFAULT NULL,
  `cinema_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_items`
--

CREATE TABLE `booking_items` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `food_drink_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cinemas`
--

CREATE TABLE `cinemas` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cinemas`
--

INSERT INTO `cinemas` (`id`, `name`, `address`, `created_at`) VALUES
(1, 'CGV Times City', '458 Minh Khai, Hai Bà Trưng, Hà Nội', '2025-11-15 12:00:17'),
(2, 'Galaxy Nguyễn Du', '116 Nguyễn Du, Quận 1, TP.HCM', '2025-11-15 12:00:17'),
(3, 'Phủ Lý', 'TTTM Vincom Plaza Phủ Lý, số 60, đường Biên Hòa, Phường Phủ Lý, Tỉnh Ninh Bình', '2025-11-16 17:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `movie_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `content` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_tiers`
--

CREATE TABLE `customer_tiers` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `spending_min` decimal(12,2) DEFAULT '0.00',
  `spending_max` decimal(12,2) DEFAULT NULL,
  `discount_percent` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` int DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_drinks`
--

CREATE TABLE `food_drinks` (
  `id` int NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `payment_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `invoice_code` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `extra_fee` decimal(10,2) DEFAULT NULL,
  `final_amount` decimal(12,2) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int NOT NULL,
  `genre_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `duration` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `original_language` varchar(50) DEFAULT NULL,
  `subtitle_or_dub` varchar(100) DEFAULT NULL,
  `age_rating` varchar(10) DEFAULT NULL,
  `producer` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `genre_id`, `title`, `description`, `duration`, `image`, `trailer`, `release_date`, `end_date`, `format`, `original_language`, `subtitle_or_dub`, `age_rating`, `producer`, `status`, `created_at`, `updated_at`) VALUES
(6, 7, 'Quán kỳ nma', 'ko hay lắm đâu', 120, 'image/phim 4.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-08', '2025-11-16', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'inactive', '2025-11-14 19:38:50', '2025-11-17 09:48:32'),
(7, 3, 'Phòng trọ ma bầu', 'kinh dị ko nên xem', 120, 'image/phim 5.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-17', '2025-11-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-14 20:27:20', '2025-11-17 09:52:58'),
(8, 1, 'Truy tìm long diên hương', 'cx hay lắm nha', 122, 'image/phim 2.jpg', 'https://youtu.be/XjhAFebnNkM?si=vKFX_9ElyDAoSMMX', '2025-11-16', '2025-11-17', '2D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-15 11:43:43', '2025-11-15 22:42:10'),
(9, 1, 'TRỐN CHẠY TỬ THẦN-T18', 'Trong bối cảnh xã hội tương lai gần, Trốn Chạy Tử Thần là chương trình truyền hình ăn khách nhất, một cuộc thi sinh tồn khốc liệt nơi các thí sinh, được gọi là “Runners”, phải trốn chạy suốt 30 ngày khỏi sự truy đuổi của các sát thủ chuyên nghiệp. Mọi bước đi của họ đều được phát sóng công khai cho khán giả theo dõi và phần thưởng tiền mặt sẽ tăng lên sau mỗi ngày sống sót. Vì cần tiền cứu chữa cho cô con gái bệnh nặng, Ben...', 133, 'image/phim 3.jpg', 'https://youtu.be/NuOl156fv_c?si=98qM39lvGn18VcdI', '2025-11-19', '2025-11-20', '2D', 'Tiếng Anh', 'Phụ Đề', 'C16', 'Mỹ', 'coming_soon', '2025-11-15 22:40:20', '2025-11-17 09:55:03'),
(10, 6, 'MỘ ĐOM ĐÓM', 'Hai anh em Seita và Setsuko mất mẹ sau cuộc thả bom dữ dội của không quân Mỹ. Cả hai phải vật lộn để tồn tại ở Nhật Bản hậu Thế chiến II. Nhưng xã hội khắc nghiệt và chúng vật lộn tìm kiếm thức ăn cũng như thoát khỏi những khó khăn giữa chiến tranh.', 120, 'image/phim 6.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-17', '2025-11-19', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-17 09:58:57', '2025-11-17 19:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `movie_genres`
--

CREATE TABLE `movie_genres` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movie_genres`
--

INSERT INTO `movie_genres` (`id`, `name`, `description`) VALUES
(1, 'Hành động', 'Các bộ phim kịch tính, nhiều cảnh chiến đấu, cháy nổ.'),
(2, 'Tình cảm', 'Phim về tình yêu, cảm xúc và các mối quan hệ.'),
(3, 'Kinh dị', 'Phim gây sợ hãi, giật gân, hồi hộp.'),
(5, 'Khoa học viễn tưởng', 'Phim về công nghệ, tương lai, vũ trụ và thế giới giả tưởng.'),
(6, 'Hoạt hình', 'Phim dành cho trẻ em và người lớn, đồ họa hoạt hình.'),
(7, 'Kịch', 'Phim chính kịch về cuộc sống và con người');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `final_amount` decimal(12,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL COMMENT 'ID quyền',
  `name` varchar(100) NOT NULL COMMENT 'Tên quyền (VD: manage_movies)',
  `display_name` varchar(255) NOT NULL COMMENT 'Tên hiển thị (VD: Quản lý phim)',
  `description` text COMMENT 'Mô tả quyền',
  `module` varchar(50) DEFAULT NULL COMMENT 'Module (VD: movies, users, bookings)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng quyền';

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `module`, `created_at`) VALUES
(1, 'view_movies', 'Xem danh sách phim', 'Xem danh sách phim', 'movies', '2025-11-18 14:45:36'),
(2, 'create_movies', 'Thêm phim mới', 'Thêm phim mới vào hệ thống', 'movies', '2025-11-18 14:45:36'),
(3, 'edit_movies', 'Sửa phim', 'Chỉnh sửa thông tin phim', 'movies', '2025-11-18 14:45:36'),
(4, 'delete_movies', 'Xóa phim', 'Xóa phim khỏi hệ thống', 'movies', '2025-11-18 14:45:36'),
(5, 'view_genres', 'Xem thể loại', 'Xem danh sách thể loại phim', 'genres', '2025-11-18 14:45:36'),
(6, 'manage_genres', 'Quản lý thể loại', 'Thêm, sửa, xóa thể loại phim', 'genres', '2025-11-18 14:45:36'),
(7, 'view_cinemas', 'Xem rạp', 'Xem danh sách rạp chiếu phim', 'cinemas', '2025-11-18 14:45:36'),
(8, 'manage_cinemas', 'Quản lý rạp', 'Thêm, sửa, xóa rạp chiếu phim', 'cinemas', '2025-11-18 14:45:36'),
(9, 'view_rooms', 'Xem phòng phim', 'Xem danh sách phòng chiếu', 'rooms', '2025-11-18 14:45:36'),
(10, 'manage_rooms', 'Quản lý phòng phim', 'Thêm, sửa, xóa phòng chiếu', 'rooms', '2025-11-18 14:45:36'),
(11, 'view_showtimes', 'Xem suất chiếu', 'Xem danh sách suất chiếu', 'showtimes', '2025-11-18 14:45:36'),
(12, 'manage_showtimes', 'Quản lý suất chiếu', 'Thêm, sửa, xóa suất chiếu', 'showtimes', '2025-11-18 14:45:36'),
(13, 'view_bookings', 'Xem đặt vé', 'Xem danh sách đơn đặt vé', 'bookings', '2025-11-18 14:45:36'),
(14, 'manage_bookings', 'Quản lý đặt vé', 'Xác nhận, cập nhật trạng thái đơn hàng', 'bookings', '2025-11-18 14:45:36'),
(15, 'view_users', 'Xem người dùng', 'Xem danh sách người dùng', 'users', '2025-11-18 14:45:36'),
(16, 'create_users', 'Thêm người dùng', 'Thêm người dùng mới', 'users', '2025-11-18 14:45:36'),
(17, 'edit_users', 'Sửa người dùng', 'Chỉnh sửa thông tin người dùng', 'users', '2025-11-18 14:45:36'),
(18, 'delete_users', 'Xóa người dùng', 'Xóa người dùng khỏi hệ thống', 'users', '2025-11-18 14:45:36'),
(19, 'view_permissions', 'Xem phân quyền', 'Xem danh sách quyền và phân quyền', 'permissions', '2025-11-18 14:45:36'),
(20, 'manage_permissions', 'Quản lý phân quyền', 'Phân quyền cho các role', 'permissions', '2025-11-18 14:45:36'),
(21, 'view_comments', 'Xem bình luận', 'Xem danh sách bình luận', 'comments', '2025-11-18 14:45:36'),
(22, 'manage_comments', 'Quản lý bình luận', 'Xóa, ẩn bình luận', 'comments', '2025-11-18 14:45:36'),
(23, 'view_statistics', 'Xem thống kê', 'Xem các báo cáo thống kê', 'statistics', '2025-11-18 14:45:36'),
(24, 'view_dashboard', 'Xem dashboard', 'Xem trang tổng quan', 'dashboard', '2025-11-18 14:45:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int NOT NULL COMMENT 'ID phân quyền',
  `role` varchar(20) NOT NULL COMMENT 'Vai trò: admin, staff, customer',
  `permission_id` int NOT NULL COMMENT 'ID quyền',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng phân quyền cho từng vai trò';

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role`, `permission_id`, `created_at`) VALUES
(47, 'admin', 2, '2025-11-18 15:14:04'),
(48, 'admin', 16, '2025-11-18 15:14:04'),
(49, 'admin', 4, '2025-11-18 15:14:04'),
(50, 'admin', 18, '2025-11-18 15:14:04'),
(51, 'admin', 3, '2025-11-18 15:14:04'),
(52, 'admin', 17, '2025-11-18 15:14:04'),
(53, 'admin', 14, '2025-11-18 15:14:04'),
(54, 'admin', 8, '2025-11-18 15:14:04'),
(55, 'admin', 22, '2025-11-18 15:14:04'),
(56, 'admin', 6, '2025-11-18 15:14:04'),
(57, 'admin', 20, '2025-11-18 15:14:04'),
(58, 'admin', 10, '2025-11-18 15:14:04'),
(59, 'admin', 12, '2025-11-18 15:14:04'),
(60, 'admin', 13, '2025-11-18 15:14:04'),
(61, 'admin', 7, '2025-11-18 15:14:04'),
(62, 'admin', 21, '2025-11-18 15:14:04'),
(63, 'admin', 24, '2025-11-18 15:14:04'),
(64, 'admin', 5, '2025-11-18 15:14:04'),
(65, 'admin', 1, '2025-11-18 15:14:04'),
(66, 'admin', 19, '2025-11-18 15:14:04'),
(67, 'admin', 9, '2025-11-18 15:14:04'),
(68, 'admin', 11, '2025-11-18 15:14:04'),
(69, 'admin', 23, '2025-11-18 15:14:04'),
(70, 'admin', 15, '2025-11-18 15:14:04'),
(78, 'staff', 2, '2025-11-18 15:14:04'),
(79, 'staff', 3, '2025-11-18 15:14:04'),
(80, 'staff', 14, '2025-11-18 15:14:04'),
(81, 'staff', 22, '2025-11-18 15:14:04'),
(82, 'staff', 12, '2025-11-18 15:14:04'),
(83, 'staff', 13, '2025-11-18 15:14:04'),
(84, 'staff', 21, '2025-11-18 15:14:04'),
(85, 'staff', 24, '2025-11-18 15:14:04'),
(86, 'staff', 1, '2025-11-18 15:14:04'),
(87, 'staff', 11, '2025-11-18 15:14:04'),
(88, 'staff', 15, '2025-11-18 15:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `cinema_id` int DEFAULT NULL,
  `room_code` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `seat_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `cinema_id`, `room_code`, `name`, `seat_count`) VALUES
(17, 1, 'R1', 'Phòng Chiếu 1', 120),
(18, 1, 'R2', 'Phòng Chiếu 2', 130),
(19, 1, 'R3', 'Phòng Chiếu 3', 150),
(21, 2, 'R1', 'Phòng Chiếu 1', 100),
(22, 2, 'R2', 'Phòng Chiếu 2', 140);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int NOT NULL,
  `room_id` int DEFAULT NULL,
  `row_label` varchar(10) DEFAULT NULL,
  `seat_number` int DEFAULT NULL,
  `seat_type` varchar(20) DEFAULT NULL,
  `extra_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `room_id`, `row_label`, `seat_number`, `seat_type`, `extra_price`, `status`) VALUES
(1, 17, 'A', 1, 'normal', NULL, 'active'),
(2, 17, 'A', 2, 'normal', NULL, 'active'),
(3, 17, 'A', 3, 'vip', 0.00, 'available'),
(4, 17, 'A', 4, 'normal', NULL, 'active'),
(5, 17, 'A', 5, 'normal', NULL, 'active'),
(6, 17, 'A', 6, 'normal', 0.00, 'inactive'),
(7, 17, 'A', 7, 'normal', NULL, 'active'),
(8, 17, 'A', 8, 'normal', NULL, 'active'),
(9, 17, 'A', 9, 'normal', NULL, 'active'),
(10, 17, 'A', 10, 'normal', NULL, 'active'),
(11, 17, 'A', 11, 'normal', NULL, 'active'),
(12, 17, 'A', 12, 'normal', 0.00, 'inactive'),
(13, 17, 'B', 1, 'normal', NULL, 'active'),
(14, 17, 'B', 2, 'normal', NULL, 'active'),
(15, 17, 'B', 3, 'normal', NULL, 'active'),
(16, 17, 'B', 4, 'normal', NULL, 'active'),
(17, 17, 'B', 5, 'normal', NULL, 'active'),
(18, 17, 'B', 6, 'normal', NULL, 'active'),
(19, 17, 'B', 7, 'normal', NULL, 'active'),
(20, 17, 'B', 8, 'normal', NULL, 'active'),
(21, 17, 'B', 9, 'normal', NULL, 'active'),
(22, 17, 'B', 10, 'normal', NULL, 'active'),
(23, 17, 'B', 11, 'normal', NULL, 'active'),
(24, 17, 'B', 12, 'normal', NULL, 'active'),
(25, 17, 'C', 1, 'VIP', 0.00, 'active'),
(26, 17, 'C', 2, 'VIP', 0.00, 'active'),
(27, 17, 'C', 3, 'VIP', 0.00, 'active'),
(28, 17, 'C', 4, 'VIP', 0.00, 'active'),
(29, 17, 'C', 5, 'VIP', 0.00, 'active'),
(30, 17, 'C', 6, 'VIP', 0.00, 'active'),
(31, 17, 'C', 7, 'VIP', 0.00, 'active'),
(32, 17, 'C', 8, 'VIP', 0.00, 'active'),
(33, 17, 'C', 9, 'VIP', 0.00, 'active'),
(34, 17, 'C', 10, 'VIP', 0.00, 'active'),
(35, 17, 'C', 11, 'VIP', 0.00, 'active'),
(36, 17, 'C', 12, 'VIP', 0.00, 'active'),
(37, 17, 'D', 1, 'VIP', 0.00, 'active'),
(38, 17, 'D', 2, 'VIP', 0.00, 'active'),
(39, 17, 'D', 3, 'VIP', 0.00, 'active'),
(40, 17, 'D', 4, 'VIP', 0.00, 'active'),
(41, 17, 'D', 5, 'VIP', 0.00, 'active'),
(42, 17, 'D', 6, 'VIP', 0.00, 'active'),
(43, 17, 'D', 7, 'VIP', 0.00, 'active'),
(44, 17, 'D', 8, 'VIP', 0.00, 'active'),
(45, 17, 'D', 9, 'VIP', 0.00, 'active'),
(46, 17, 'D', 10, 'VIP', 0.00, 'active'),
(47, 17, 'D', 11, 'VIP', 0.00, 'active'),
(48, 17, 'D', 12, 'VIP', 0.00, 'active'),
(49, 17, 'E', 1, 'normal', 0.00, 'inactive'),
(50, 17, 'E', 2, 'VIP', 0.00, 'active'),
(51, 17, 'E', 3, 'VIP', 0.00, 'active'),
(52, 17, 'E', 4, 'VIP', 0.00, 'active'),
(53, 17, 'E', 5, 'VIP', 0.00, 'active'),
(54, 17, 'E', 6, 'VIP', 0.00, 'active'),
(55, 17, 'E', 7, 'VIP', 0.00, 'active'),
(56, 17, 'E', 8, 'VIP', 0.00, 'active'),
(57, 17, 'E', 9, 'VIP', 0.00, 'active'),
(58, 17, 'E', 10, 'VIP', 0.00, 'active'),
(59, 17, 'E', 11, 'VIP', 0.00, 'active'),
(60, 17, 'E', 12, 'normal', 0.00, 'inactive'),
(61, 17, 'F', 1, 'normal', NULL, 'active'),
(62, 17, 'F', 2, 'VIP', 0.00, 'active'),
(63, 17, 'F', 3, 'VIP', 0.00, 'active'),
(64, 17, 'F', 4, 'VIP', 0.00, 'active'),
(65, 17, 'F', 5, 'VIP', 0.00, 'active'),
(66, 17, 'F', 6, 'VIP', 0.00, 'active'),
(67, 17, 'F', 7, 'VIP', 0.00, 'active'),
(68, 17, 'F', 8, 'VIP', 0.00, 'active'),
(69, 17, 'F', 9, 'VIP', 0.00, 'active'),
(70, 17, 'F', 10, 'VIP', 0.00, 'active'),
(71, 17, 'F', 11, 'VIP', 0.00, 'active'),
(72, 17, 'F', 12, 'normal', NULL, 'active'),
(73, 17, 'G', 1, 'normal', NULL, 'active'),
(74, 17, 'G', 2, 'normal', 0.00, 'inactive'),
(75, 17, 'G', 3, 'VIP', 0.00, 'active'),
(76, 17, 'G', 4, 'VIP', 0.00, 'active'),
(77, 17, 'G', 5, 'VIP', 0.00, 'active'),
(78, 17, 'G', 6, 'VIP', 0.00, 'active'),
(79, 17, 'G', 7, 'VIP', 0.00, 'active'),
(80, 17, 'G', 8, 'VIP', 0.00, 'active'),
(81, 17, 'G', 9, 'VIP', 0.00, 'active'),
(82, 17, 'G', 10, 'VIP', 0.00, 'active'),
(83, 17, 'G', 11, 'VIP', 0.00, 'active'),
(84, 17, 'G', 12, 'normal', 0.00, 'inactive'),
(85, 17, 'H', 1, 'normal', NULL, 'active'),
(86, 17, 'H', 2, 'normal', NULL, 'active'),
(87, 17, 'H', 3, 'normal', NULL, 'active'),
(88, 17, 'H', 4, 'normal', NULL, 'active'),
(89, 17, 'H', 5, 'normal', NULL, 'active'),
(90, 17, 'H', 6, 'normal', NULL, 'active'),
(91, 17, 'H', 7, 'normal', NULL, 'active'),
(92, 17, 'H', 8, 'normal', NULL, 'active'),
(93, 17, 'H', 9, 'normal', NULL, 'active'),
(94, 17, 'H', 10, 'normal', NULL, 'active'),
(95, 17, 'H', 11, 'normal', NULL, 'active'),
(96, 17, 'H', 12, 'normal', NULL, 'active'),
(97, 17, 'I', 1, 'normal', 0.00, 'inactive'),
(98, 17, 'I', 2, 'normal', 0.00, 'active'),
(99, 17, 'I', 3, 'normal', 0.00, 'active'),
(100, 17, 'I', 4, 'normal', 0.00, 'active'),
(101, 17, 'I', 5, 'normal', 0.00, 'inactive'),
(102, 17, 'I', 6, 'normal', 0.00, 'inactive'),
(103, 17, 'I', 7, 'normal', NULL, 'active'),
(104, 17, 'I', 8, 'normal', NULL, 'active'),
(105, 17, 'I', 9, 'normal', NULL, 'active'),
(106, 17, 'I', 10, 'normal', NULL, 'active'),
(107, 17, 'I', 11, 'normal', NULL, 'active'),
(108, 17, 'I', 12, 'normal', NULL, 'active'),
(109, 17, 'J', 1, 'normal', 0.00, 'inactive'),
(110, 17, 'J', 2, 'normal', 0.00, 'inactive'),
(111, 17, 'J', 3, 'normal', 0.00, 'inactive'),
(112, 17, 'J', 4, 'normal', 0.00, 'active'),
(113, 17, 'J', 5, 'normal', 0.00, 'active'),
(114, 17, 'J', 6, 'normal', 0.00, 'active'),
(115, 17, 'J', 7, 'normal', 0.00, 'active'),
(116, 17, 'J', 8, 'normal', 0.00, 'active'),
(117, 17, 'J', 9, 'normal', 0.00, 'active'),
(118, 17, 'J', 10, 'normal', 0.00, 'active'),
(119, 17, 'J', 11, 'normal', 0.00, 'inactive'),
(120, 17, 'J', 12, 'normal', 0.00, 'inactive'),
(121, 18, 'A', 1, 'normal', NULL, 'active'),
(122, 18, 'A', 2, 'normal', NULL, 'active'),
(123, 18, 'A', 3, 'normal', NULL, 'active'),
(124, 18, 'A', 4, 'normal', NULL, 'active'),
(125, 18, 'A', 5, 'normal', NULL, 'active'),
(126, 18, 'A', 6, 'normal', NULL, 'active'),
(127, 18, 'A', 7, 'normal', NULL, 'active'),
(128, 18, 'A', 8, 'normal', NULL, 'active'),
(129, 18, 'A', 9, 'normal', NULL, 'active'),
(130, 18, 'A', 10, 'normal', NULL, 'active'),
(131, 18, 'A', 11, 'normal', NULL, 'active'),
(132, 18, 'A', 12, 'normal', NULL, 'active'),
(133, 18, 'A', 13, 'normal', NULL, 'active'),
(134, 18, 'A', 14, 'normal', NULL, 'active'),
(135, 18, 'A', 15, 'normal', NULL, 'active'),
(136, 18, 'A', 16, 'normal', NULL, 'active'),
(137, 18, 'A', 17, 'normal', NULL, 'active'),
(138, 18, 'B', 1, 'normal', NULL, 'active'),
(139, 18, 'B', 2, 'normal', NULL, 'active'),
(140, 18, 'B', 3, 'normal', NULL, 'active'),
(141, 18, 'B', 4, 'normal', NULL, 'active'),
(142, 18, 'B', 5, 'normal', NULL, 'active'),
(143, 18, 'B', 6, 'normal', NULL, 'active'),
(144, 18, 'B', 7, 'normal', NULL, 'active'),
(145, 18, 'B', 8, 'normal', NULL, 'active'),
(146, 18, 'B', 9, 'normal', NULL, 'active'),
(147, 18, 'B', 10, 'normal', NULL, 'active'),
(148, 18, 'B', 11, 'normal', NULL, 'active'),
(149, 18, 'B', 12, 'normal', NULL, 'active'),
(150, 18, 'B', 13, 'normal', NULL, 'active'),
(151, 18, 'B', 14, 'normal', NULL, 'active'),
(152, 18, 'B', 15, 'normal', NULL, 'active'),
(153, 18, 'B', 16, 'normal', NULL, 'active'),
(154, 18, 'B', 17, 'normal', NULL, 'active'),
(155, 18, 'C', 1, 'normal', NULL, 'active'),
(156, 18, 'C', 2, 'normal', NULL, 'active'),
(157, 18, 'C', 3, 'normal', NULL, 'active'),
(158, 18, 'C', 4, 'normal', NULL, 'active'),
(159, 18, 'C', 5, 'normal', NULL, 'active'),
(160, 18, 'C', 6, 'normal', NULL, 'active'),
(161, 18, 'C', 7, 'normal', NULL, 'active'),
(162, 18, 'C', 8, 'normal', NULL, 'active'),
(163, 18, 'C', 9, 'normal', NULL, 'active'),
(164, 18, 'C', 10, 'normal', NULL, 'active'),
(165, 18, 'C', 11, 'normal', NULL, 'active'),
(166, 18, 'C', 12, 'normal', NULL, 'active'),
(167, 18, 'C', 13, 'normal', NULL, 'active'),
(168, 18, 'C', 14, 'normal', NULL, 'active'),
(169, 18, 'C', 15, 'normal', NULL, 'active'),
(170, 18, 'C', 16, 'normal', NULL, 'active'),
(171, 18, 'C', 17, 'normal', NULL, 'active'),
(172, 18, 'D', 1, 'normal', NULL, 'active'),
(173, 18, 'D', 2, 'normal', NULL, 'active'),
(174, 18, 'D', 3, 'normal', NULL, 'active'),
(175, 18, 'D', 4, 'normal', NULL, 'active'),
(176, 18, 'D', 5, 'normal', NULL, 'active'),
(177, 18, 'D', 6, 'normal', NULL, 'active'),
(178, 18, 'D', 7, 'normal', NULL, 'active'),
(179, 18, 'D', 8, 'normal', NULL, 'active'),
(180, 18, 'D', 9, 'normal', NULL, 'active'),
(181, 18, 'D', 10, 'normal', NULL, 'active'),
(182, 18, 'D', 11, 'normal', NULL, 'active'),
(183, 18, 'D', 12, 'normal', NULL, 'active'),
(184, 18, 'D', 13, 'normal', NULL, 'active'),
(185, 18, 'D', 14, 'normal', NULL, 'active'),
(186, 18, 'D', 15, 'normal', NULL, 'active'),
(187, 18, 'D', 16, 'normal', NULL, 'active'),
(188, 18, 'D', 17, 'normal', NULL, 'active'),
(189, 18, 'E', 1, 'normal', NULL, 'active'),
(190, 18, 'E', 2, 'normal', NULL, 'active'),
(191, 18, 'E', 3, 'normal', NULL, 'active'),
(192, 18, 'E', 4, 'normal', NULL, 'active'),
(193, 18, 'E', 5, 'normal', NULL, 'active'),
(194, 18, 'E', 6, 'normal', NULL, 'active'),
(195, 18, 'E', 7, 'normal', NULL, 'active'),
(196, 18, 'E', 8, 'normal', NULL, 'active'),
(197, 18, 'E', 9, 'normal', NULL, 'active'),
(198, 18, 'E', 10, 'normal', NULL, 'active'),
(199, 18, 'E', 11, 'normal', NULL, 'active'),
(200, 18, 'E', 12, 'normal', NULL, 'active'),
(201, 18, 'E', 13, 'normal', NULL, 'active'),
(202, 18, 'E', 14, 'normal', NULL, 'active'),
(203, 18, 'E', 15, 'normal', NULL, 'active'),
(204, 18, 'E', 16, 'normal', NULL, 'active'),
(205, 18, 'E', 17, 'normal', NULL, 'active'),
(206, 18, 'F', 1, 'normal', NULL, 'active'),
(207, 18, 'F', 2, 'normal', NULL, 'active'),
(208, 18, 'F', 3, 'normal', NULL, 'active'),
(209, 18, 'F', 4, 'normal', NULL, 'active'),
(210, 18, 'F', 5, 'normal', NULL, 'active'),
(211, 18, 'F', 6, 'normal', NULL, 'active'),
(212, 18, 'F', 7, 'normal', NULL, 'active'),
(213, 18, 'F', 8, 'normal', NULL, 'active'),
(214, 18, 'F', 9, 'normal', NULL, 'active'),
(215, 18, 'F', 10, 'normal', NULL, 'active'),
(216, 18, 'F', 11, 'normal', NULL, 'active'),
(217, 18, 'F', 12, 'normal', NULL, 'active'),
(218, 18, 'F', 13, 'normal', NULL, 'active'),
(219, 18, 'F', 14, 'normal', NULL, 'active'),
(220, 18, 'F', 15, 'normal', NULL, 'active'),
(221, 18, 'F', 16, 'normal', NULL, 'active'),
(222, 18, 'F', 17, 'normal', NULL, 'active'),
(223, 18, 'G', 1, 'normal', NULL, 'active'),
(224, 18, 'G', 2, 'normal', NULL, 'active'),
(225, 18, 'G', 3, 'normal', NULL, 'active'),
(226, 18, 'G', 4, 'normal', NULL, 'active'),
(227, 18, 'G', 5, 'normal', NULL, 'active'),
(228, 18, 'G', 6, 'normal', NULL, 'active'),
(229, 18, 'G', 7, 'normal', NULL, 'active'),
(230, 18, 'G', 8, 'normal', NULL, 'active'),
(231, 18, 'G', 9, 'normal', NULL, 'active'),
(232, 18, 'G', 10, 'normal', NULL, 'active'),
(233, 18, 'G', 11, 'normal', NULL, 'active'),
(234, 18, 'G', 12, 'normal', NULL, 'active'),
(235, 18, 'G', 13, 'normal', NULL, 'active'),
(236, 18, 'G', 14, 'normal', NULL, 'active'),
(237, 18, 'G', 15, 'normal', NULL, 'active'),
(238, 18, 'G', 16, 'normal', NULL, 'active'),
(239, 18, 'G', 17, 'normal', NULL, 'active'),
(240, 18, 'H', 1, 'normal', NULL, 'active'),
(241, 18, 'H', 2, 'normal', NULL, 'active'),
(242, 18, 'H', 3, 'normal', NULL, 'active'),
(243, 18, 'H', 4, 'normal', NULL, 'active'),
(244, 18, 'H', 5, 'normal', NULL, 'active'),
(245, 18, 'H', 6, 'normal', NULL, 'active'),
(246, 18, 'H', 7, 'normal', NULL, 'active'),
(247, 18, 'H', 8, 'normal', NULL, 'active'),
(248, 18, 'H', 9, 'normal', NULL, 'active'),
(249, 18, 'H', 10, 'normal', NULL, 'active'),
(250, 18, 'H', 11, 'normal', NULL, 'active'),
(587, 22, '1', 1, 'normal', NULL, 'active'),
(588, 22, '2', 1, 'normal', NULL, 'active'),
(589, 22, '3', 1, 'normal', NULL, 'active'),
(590, 22, '4', 1, 'normal', NULL, 'active'),
(591, 22, '5', 1, 'normal', NULL, 'active'),
(592, 22, '6', 1, 'normal', NULL, 'active'),
(593, 22, '7', 1, 'normal', NULL, 'active'),
(594, 22, '8', 1, 'normal', NULL, 'active'),
(595, 22, '9', 1, 'normal', NULL, 'active'),
(596, 22, '10', 1, 'normal', NULL, 'active'),
(597, 22, '11', 1, 'normal', NULL, 'active'),
(598, 22, '12', 1, 'normal', NULL, 'active'),
(599, 22, '13', 1, 'normal', NULL, 'active'),
(600, 22, '14', 1, 'normal', NULL, 'active'),
(601, 19, 'A', 1, 'normal', NULL, 'active'),
(602, 19, 'B', 1, 'normal', NULL, 'active'),
(603, 19, 'C', 1, 'normal', NULL, 'active'),
(604, 19, 'D', 1, 'normal', NULL, 'active'),
(605, 19, 'E', 1, 'normal', NULL, 'active'),
(606, 19, 'F', 1, 'normal', NULL, 'active'),
(607, 19, 'G', 1, 'normal', NULL, 'active'),
(608, 19, 'H', 1, 'normal', NULL, 'active'),
(609, 19, 'I', 1, 'normal', NULL, 'active'),
(610, 19, 'J', 1, 'normal', NULL, 'active'),
(611, 19, 'A', 1, 'normal', NULL, 'active'),
(612, 19, 'A', 2, 'normal', NULL, 'active'),
(613, 19, 'A', 3, 'normal', NULL, 'active'),
(614, 19, 'A', 4, 'normal', NULL, 'active'),
(615, 19, 'A', 5, 'normal', NULL, 'active'),
(616, 19, 'A', 6, 'normal', NULL, 'active'),
(617, 19, 'A', 7, 'normal', NULL, 'active'),
(618, 19, 'A', 8, 'normal', NULL, 'active'),
(619, 19, 'A', 9, 'normal', NULL, 'active'),
(620, 19, 'A', 10, 'normal', NULL, 'active'),
(621, 19, 'A', 11, 'normal', NULL, 'active'),
(622, 19, 'A', 12, 'normal', NULL, 'active'),
(623, 19, 'A', 13, 'normal', NULL, 'active'),
(624, 19, 'A', 14, 'normal', NULL, 'active'),
(625, 19, 'A', 15, 'normal', NULL, 'active'),
(626, 19, 'B', 1, 'normal', NULL, 'active'),
(627, 19, 'B', 2, 'normal', NULL, 'active'),
(628, 19, 'B', 3, 'normal', NULL, 'active'),
(629, 19, 'B', 4, 'normal', NULL, 'active'),
(630, 19, 'B', 5, 'normal', NULL, 'active'),
(631, 19, 'B', 6, 'normal', NULL, 'active'),
(632, 19, 'B', 7, 'normal', NULL, 'active'),
(633, 19, 'B', 8, 'normal', NULL, 'active'),
(634, 19, 'B', 9, 'normal', NULL, 'active'),
(635, 19, 'B', 10, 'normal', NULL, 'active'),
(636, 19, 'B', 11, 'normal', NULL, 'active'),
(637, 19, 'B', 12, 'normal', NULL, 'active'),
(638, 19, 'B', 13, 'normal', NULL, 'active'),
(639, 19, 'B', 14, 'normal', NULL, 'active'),
(640, 19, 'B', 15, 'normal', NULL, 'active'),
(641, 19, 'C', 1, 'normal', NULL, 'active'),
(642, 19, 'C', 2, 'normal', NULL, 'active'),
(643, 19, 'C', 3, 'normal', NULL, 'active'),
(644, 19, 'C', 4, 'normal', NULL, 'active'),
(645, 19, 'C', 5, 'normal', NULL, 'active'),
(646, 19, 'C', 6, 'normal', NULL, 'active'),
(647, 19, 'C', 7, 'normal', NULL, 'active'),
(648, 19, 'C', 8, 'normal', NULL, 'active'),
(649, 19, 'C', 9, 'normal', NULL, 'active'),
(650, 19, 'C', 10, 'normal', NULL, 'active'),
(651, 19, 'C', 11, 'normal', NULL, 'active'),
(652, 19, 'C', 12, 'normal', NULL, 'active'),
(653, 19, 'C', 13, 'normal', NULL, 'active'),
(654, 19, 'C', 14, 'normal', NULL, 'active'),
(655, 19, 'C', 15, 'normal', NULL, 'active'),
(656, 19, 'D', 1, 'normal', NULL, 'active'),
(657, 19, 'D', 2, 'normal', NULL, 'active'),
(658, 19, 'D', 3, 'normal', NULL, 'active'),
(659, 19, 'D', 4, 'normal', NULL, 'active'),
(660, 19, 'D', 5, 'normal', NULL, 'active'),
(661, 19, 'D', 6, 'normal', NULL, 'active'),
(662, 19, 'D', 7, 'normal', NULL, 'active'),
(663, 19, 'D', 8, 'normal', NULL, 'active'),
(664, 19, 'D', 9, 'normal', NULL, 'active'),
(665, 19, 'D', 10, 'normal', NULL, 'active'),
(666, 19, 'D', 11, 'normal', NULL, 'active'),
(667, 19, 'D', 12, 'normal', NULL, 'active'),
(668, 19, 'D', 13, 'normal', NULL, 'active'),
(669, 19, 'D', 14, 'normal', NULL, 'active'),
(670, 19, 'D', 15, 'normal', NULL, 'active'),
(671, 19, 'E', 1, 'normal', NULL, 'active'),
(672, 19, 'E', 2, 'normal', NULL, 'active'),
(673, 19, 'E', 3, 'normal', NULL, 'active'),
(674, 19, 'E', 4, 'normal', NULL, 'active'),
(675, 19, 'E', 5, 'normal', NULL, 'active'),
(676, 19, 'E', 6, 'normal', NULL, 'active'),
(677, 19, 'E', 7, 'normal', NULL, 'active'),
(678, 19, 'E', 8, 'normal', NULL, 'active'),
(679, 19, 'E', 9, 'normal', NULL, 'active'),
(680, 19, 'E', 10, 'normal', NULL, 'active'),
(681, 19, 'E', 11, 'normal', NULL, 'active'),
(682, 19, 'E', 12, 'normal', NULL, 'active'),
(683, 19, 'E', 13, 'normal', NULL, 'active'),
(684, 19, 'E', 14, 'normal', NULL, 'active'),
(685, 19, 'E', 15, 'normal', NULL, 'active'),
(686, 19, 'F', 1, 'normal', NULL, 'active'),
(687, 19, 'F', 2, 'normal', NULL, 'active'),
(688, 19, 'F', 3, 'normal', NULL, 'active'),
(689, 19, 'F', 4, 'normal', NULL, 'active'),
(690, 19, 'F', 5, 'normal', NULL, 'active'),
(691, 19, 'F', 6, 'normal', NULL, 'active'),
(692, 19, 'F', 7, 'normal', NULL, 'active'),
(693, 19, 'F', 8, 'normal', NULL, 'active'),
(694, 19, 'F', 9, 'normal', NULL, 'active'),
(695, 19, 'F', 10, 'normal', NULL, 'active'),
(696, 19, 'F', 11, 'normal', NULL, 'active'),
(697, 19, 'F', 12, 'normal', NULL, 'active'),
(698, 19, 'F', 13, 'normal', NULL, 'active'),
(699, 19, 'F', 14, 'normal', NULL, 'active'),
(700, 19, 'F', 15, 'normal', NULL, 'active'),
(701, 19, 'G', 1, 'normal', NULL, 'active'),
(702, 19, 'G', 2, 'normal', NULL, 'active'),
(703, 19, 'G', 3, 'normal', NULL, 'active'),
(704, 19, 'G', 4, 'normal', NULL, 'active'),
(705, 19, 'G', 5, 'normal', NULL, 'active'),
(706, 19, 'G', 6, 'normal', NULL, 'active'),
(707, 19, 'G', 7, 'normal', NULL, 'active'),
(708, 19, 'G', 8, 'normal', NULL, 'active'),
(709, 19, 'G', 9, 'normal', NULL, 'active'),
(710, 19, 'G', 10, 'normal', NULL, 'active'),
(711, 19, 'G', 11, 'normal', NULL, 'active'),
(712, 19, 'G', 12, 'normal', NULL, 'active'),
(713, 19, 'G', 13, 'normal', NULL, 'active'),
(714, 19, 'G', 14, 'normal', NULL, 'active'),
(715, 19, 'G', 15, 'normal', NULL, 'active'),
(716, 19, 'H', 1, 'normal', NULL, 'active'),
(717, 19, 'H', 2, 'normal', NULL, 'active'),
(718, 19, 'H', 3, 'normal', NULL, 'active'),
(719, 19, 'H', 4, 'normal', NULL, 'active'),
(720, 19, 'H', 5, 'normal', NULL, 'active'),
(721, 19, 'H', 6, 'normal', NULL, 'active'),
(722, 19, 'H', 7, 'normal', NULL, 'active'),
(723, 19, 'H', 8, 'normal', NULL, 'active'),
(724, 19, 'H', 9, 'normal', NULL, 'active'),
(725, 19, 'H', 10, 'normal', NULL, 'active'),
(726, 19, 'H', 11, 'normal', NULL, 'active'),
(727, 19, 'H', 12, 'normal', NULL, 'active'),
(728, 19, 'H', 13, 'normal', NULL, 'active'),
(729, 19, 'H', 14, 'normal', NULL, 'active'),
(730, 19, 'H', 15, 'normal', NULL, 'active'),
(731, 19, 'I', 1, 'normal', NULL, 'active'),
(732, 19, 'I', 2, 'normal', NULL, 'active'),
(733, 19, 'I', 3, 'normal', NULL, 'active'),
(734, 19, 'I', 4, 'normal', NULL, 'active'),
(735, 19, 'I', 5, 'normal', NULL, 'active'),
(736, 19, 'I', 6, 'normal', NULL, 'active'),
(737, 19, 'I', 7, 'normal', NULL, 'active'),
(738, 19, 'I', 8, 'normal', NULL, 'active'),
(739, 19, 'I', 9, 'normal', NULL, 'active'),
(740, 19, 'I', 10, 'normal', NULL, 'active'),
(741, 19, 'I', 11, 'normal', NULL, 'active'),
(742, 19, 'I', 12, 'normal', NULL, 'active'),
(743, 19, 'I', 13, 'normal', NULL, 'active'),
(744, 19, 'I', 14, 'normal', NULL, 'active'),
(745, 19, 'I', 15, 'normal', NULL, 'active'),
(746, 19, 'J', 1, 'normal', NULL, 'active'),
(747, 19, 'J', 2, 'normal', NULL, 'active'),
(748, 19, 'J', 3, 'normal', NULL, 'active'),
(749, 19, 'J', 4, 'normal', NULL, 'active'),
(750, 19, 'J', 5, 'normal', NULL, 'active'),
(901, 21, 'A', 1, 'normal', 0.00, 'available'),
(902, 21, 'A', 2, 'normal', 0.00, 'available'),
(903, 21, 'A', 3, 'normal', 0.00, 'available'),
(904, 21, 'A', 4, 'normal', 0.00, 'available'),
(905, 21, 'A', 5, 'normal', 0.00, 'available'),
(906, 21, 'A', 6, 'normal', 0.00, 'available'),
(907, 21, 'A', 7, 'normal', 0.00, 'available'),
(908, 21, 'A', 8, 'normal', 0.00, 'available'),
(909, 21, 'A', 9, 'normal', 0.00, 'available'),
(910, 21, 'A', 10, 'normal', 0.00, 'available'),
(911, 21, 'A', 11, 'normal', 0.00, 'available'),
(912, 21, 'A', 12, 'normal', 0.00, 'available'),
(913, 21, 'B', 1, 'normal', 0.00, 'available'),
(914, 21, 'B', 2, 'normal', 0.00, 'available'),
(915, 21, 'B', 3, 'normal', 0.00, 'available'),
(916, 21, 'B', 4, 'normal', 0.00, 'available'),
(917, 21, 'B', 5, 'normal', 0.00, 'available'),
(918, 21, 'B', 6, 'normal', 0.00, 'available'),
(919, 21, 'B', 7, 'normal', 0.00, 'available'),
(920, 21, 'B', 8, 'normal', 0.00, 'available'),
(921, 21, 'B', 9, 'normal', 0.00, 'available'),
(922, 21, 'B', 10, 'normal', 0.00, 'available'),
(923, 21, 'B', 11, 'normal', 0.00, 'available'),
(924, 21, 'B', 12, 'normal', 0.00, 'available'),
(925, 21, 'C', 1, 'normal', 0.00, 'available'),
(926, 21, 'C', 2, 'normal', 0.00, 'available'),
(927, 21, 'C', 3, 'normal', 0.00, 'available'),
(928, 21, 'C', 4, 'normal', 0.00, 'available'),
(929, 21, 'C', 5, 'normal', 0.00, 'available'),
(930, 21, 'C', 6, 'normal', 0.00, 'available'),
(931, 21, 'C', 7, 'normal', 0.00, 'available'),
(932, 21, 'C', 8, 'normal', 0.00, 'available'),
(933, 21, 'C', 9, 'normal', 0.00, 'available'),
(934, 21, 'C', 10, 'normal', 0.00, 'available'),
(935, 21, 'C', 11, 'normal', 0.00, 'available'),
(936, 21, 'C', 12, 'normal', 0.00, 'available'),
(937, 21, 'D', 1, 'normal', 0.00, 'available'),
(938, 21, 'D', 2, 'normal', 0.00, 'available'),
(939, 21, 'D', 3, 'normal', 0.00, 'available'),
(940, 21, 'D', 4, 'normal', 0.00, 'available'),
(941, 21, 'D', 5, 'normal', 0.00, 'available'),
(942, 21, 'D', 6, 'normal', 0.00, 'available'),
(943, 21, 'D', 7, 'normal', 0.00, 'available'),
(944, 21, 'D', 8, 'normal', 0.00, 'available'),
(945, 21, 'D', 9, 'normal', 0.00, 'available'),
(946, 21, 'D', 10, 'normal', 0.00, 'available'),
(947, 21, 'D', 11, 'normal', 0.00, 'available'),
(948, 21, 'D', 12, 'normal', 0.00, 'available'),
(949, 21, 'E', 1, 'normal', 0.00, 'available'),
(950, 21, 'E', 2, 'normal', 0.00, 'available'),
(951, 21, 'E', 3, 'normal', 0.00, 'available'),
(952, 21, 'E', 4, 'normal', 0.00, 'available'),
(953, 21, 'E', 5, 'normal', 0.00, 'available'),
(954, 21, 'E', 6, 'normal', 0.00, 'available'),
(955, 21, 'E', 7, 'normal', 0.00, 'available'),
(956, 21, 'E', 8, 'normal', 0.00, 'available'),
(957, 21, 'E', 9, 'normal', 0.00, 'available'),
(958, 21, 'E', 10, 'normal', 0.00, 'available'),
(959, 21, 'E', 11, 'normal', 0.00, 'available'),
(960, 21, 'E', 12, 'normal', 0.00, 'available'),
(961, 21, 'F', 1, 'normal', 0.00, 'available'),
(962, 21, 'F', 2, 'normal', 0.00, 'available'),
(963, 21, 'F', 3, 'normal', 0.00, 'available'),
(964, 21, 'F', 4, 'normal', 0.00, 'available'),
(965, 21, 'F', 5, 'normal', 0.00, 'available'),
(966, 21, 'F', 6, 'normal', 0.00, 'available'),
(967, 21, 'F', 7, 'normal', 0.00, 'available'),
(968, 21, 'F', 8, 'normal', 0.00, 'available'),
(969, 21, 'F', 9, 'normal', 0.00, 'available'),
(970, 21, 'F', 10, 'normal', 0.00, 'available'),
(971, 21, 'F', 11, 'normal', 0.00, 'available'),
(972, 21, 'F', 12, 'normal', 0.00, 'available'),
(973, 21, 'G', 1, 'normal', 0.00, 'available'),
(974, 21, 'G', 2, 'normal', 0.00, 'available'),
(975, 21, 'G', 3, 'normal', 0.00, 'available'),
(976, 21, 'G', 4, 'normal', 0.00, 'available'),
(977, 21, 'G', 5, 'normal', 0.00, 'available'),
(978, 21, 'G', 6, 'normal', 0.00, 'available'),
(979, 21, 'G', 7, 'normal', 0.00, 'available'),
(980, 21, 'G', 8, 'normal', 0.00, 'available'),
(981, 21, 'G', 9, 'normal', 0.00, 'available'),
(982, 21, 'G', 10, 'normal', 0.00, 'available'),
(983, 21, 'G', 11, 'normal', 0.00, 'available'),
(984, 21, 'G', 12, 'normal', 0.00, 'available'),
(985, 21, 'H', 1, 'normal', 0.00, 'available'),
(986, 21, 'H', 2, 'normal', 0.00, 'available'),
(987, 21, 'H', 3, 'normal', 0.00, 'available'),
(988, 21, 'H', 4, 'normal', 0.00, 'available'),
(989, 21, 'H', 5, 'normal', 0.00, 'available'),
(990, 21, 'H', 6, 'normal', 0.00, 'available'),
(991, 21, 'H', 7, 'normal', 0.00, 'available'),
(992, 21, 'H', 8, 'normal', 0.00, 'available'),
(993, 21, 'H', 9, 'normal', 0.00, 'available'),
(994, 21, 'H', 10, 'normal', 0.00, 'available'),
(995, 21, 'H', 11, 'normal', 0.00, 'available'),
(996, 21, 'H', 12, 'normal', 0.00, 'available'),
(997, 21, 'I', 1, 'normal', 0.00, 'available'),
(998, 21, 'I', 2, 'normal', 0.00, 'available'),
(999, 21, 'I', 3, 'normal', 0.00, 'available'),
(1000, 21, 'I', 4, 'normal', 0.00, 'available'),
(1001, 21, 'I', 5, 'normal', 0.00, 'available'),
(1002, 21, 'I', 6, 'normal', 0.00, 'available'),
(1003, 21, 'I', 7, 'normal', 0.00, 'available'),
(1004, 21, 'I', 8, 'normal', 0.00, 'available'),
(1005, 21, 'I', 9, 'normal', 0.00, 'available'),
(1006, 21, 'I', 10, 'normal', 0.00, 'available'),
(1007, 21, 'I', 11, 'normal', 0.00, 'available'),
(1008, 21, 'I', 12, 'normal', 0.00, 'available'),
(1009, 21, 'J', 1, 'normal', 0.00, 'available'),
(1010, 21, 'J', 2, 'normal', 0.00, 'available'),
(1011, 21, 'J', 3, 'normal', 0.00, 'available'),
(1012, 21, 'J', 4, 'normal', 0.00, 'available'),
(1013, 21, 'J', 5, 'normal', 0.00, 'available'),
(1014, 21, 'J', 6, 'normal', 0.00, 'available'),
(1015, 21, 'J', 7, 'normal', 0.00, 'available'),
(1016, 21, 'J', 8, 'normal', 0.00, 'available'),
(1017, 21, 'J', 9, 'normal', 0.00, 'available'),
(1018, 21, 'J', 10, 'normal', 0.00, 'available'),
(1019, 21, 'J', 11, 'normal', 0.00, 'available'),
(1020, 21, 'J', 12, 'normal', 0.00, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `id` int NOT NULL,
  `movie_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `show_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `adult_price` decimal(10,2) DEFAULT NULL,
  `student_price` decimal(10,2) DEFAULT NULL,
  `format` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`id`, `movie_id`, `room_id`, `show_date`, `start_time`, `end_time`, `adult_price`, `student_price`, `format`) VALUES
(1, 8, 17, '2025-11-18', '15:00:00', '16:30:00', 80000.00, 65000.00, '2D'),
(2, 7, 18, '2025-11-17', '16:00:00', '17:00:00', 75000.00, 65000.00, '2D'),
(3, 6, 19, '2025-11-19', '15:00:00', '17:00:00', 85000.00, 70000.00, '3D'),
(4, 8, 17, '2025-11-18', '17:30:00', '19:32:00', 80000.00, 65000.00, '2D'),
(5, 6, 18, '2025-11-17', '14:00:00', '16:00:00', 75000.00, 60000.00, '2D'),
(6, 9, 19, '2025-11-17', '15:00:00', '17:13:00', 70000.00, 60000.00, '2D'),
(7, 9, 17, '2025-11-18', '10:30:00', '12:43:00', 80000.00, 70000.00, '2D'),
(8, 7, 21, '2025-11-18', '13:45:00', '15:45:00', 75000.00, 60000.00, '2D');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `tier_id` int DEFAULT NULL,
  `total_spending` decimal(12,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(20) DEFAULT 'customer' COMMENT 'Vai trò: admin, staff, customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `birth_date`, `tier_id`, `total_spending`, `created_at`, `role`) VALUES
(2, 'Duc anh', 'anp93005@gmail.com', '$2y$10$CWyRPSmpryxfnvWJk.WU6ee587peAVpJ2WM.gPnWxn1EURYPTorwe', '0386036692', '2025-10-28', NULL, 0.00, '2025-11-15 20:56:13', 'admin'),
(3, 'nguyễn văn A', 'anh123@gmail.com', '$2y$10$VaYpeaUFxGUKFgO3yq7xVe.qnTi4VRvnnxK4ZiLkysvEq2jvCVr8.', '0386036636', '2000-10-12', NULL, 0.00, '2025-11-15 21:17:27', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bookings_user` (`user_id`),
  ADD KEY `fk_bookings_showtime` (`showtime_id`),
  ADD KEY `fk_bookings_discount` (`discount_id`),
  ADD KEY `fk_bookings_cinema` (`cinema_id`),
  ADD KEY `fk_bookings_room` (`room_id`);

--
-- Indexes for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_items_booking` (`booking_id`),
  ADD KEY `fk_booking_items_food` (`food_drink_id`);

--
-- Indexes for table `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_user` (`user_id`),
  ADD KEY `fk_comments_movie` (`movie_id`);

--
-- Indexes for table `customer_tiers`
--
ALTER TABLE `customer_tiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `food_drinks`
--
ALTER TABLE `food_drinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_code` (`invoice_code`),
  ADD KEY `fk_invoices_payment` (`payment_id`),
  ADD KEY `fk_invoices_user` (`user_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movies_genre` (`genre_id`);

--
-- Indexes for table `movie_genres`
--
ALTER TABLE `movie_genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_booking` (`booking_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission` (`role`,`permission_id`),
  ADD KEY `fk_role_permissions_permission` (`permission_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rooms_cinema` (`cinema_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_seats_room` (`room_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_showtimes_movie` (`movie_id`),
  ADD KEY `fk_showtimes_room` (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_tiers` (`tier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_tiers`
--
ALTER TABLE `customer_tiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_drinks`
--
ALTER TABLE `food_drinks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movie_genres`
--
ALTER TABLE `movie_genres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID quyền', AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID phân quyền', AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1021;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount_codes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_showtime` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD CONSTRAINT `fk_booking_items_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_items_food` FOREIGN KEY (`food_drink_id`) REFERENCES `food_drinks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_genre` FOREIGN KEY (`genre_id`) REFERENCES `movie_genres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rooms_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `fk_seats_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `fk_showtimes_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_showtimes_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_tiers` FOREIGN KEY (`tier_id`) REFERENCES `customer_tiers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
