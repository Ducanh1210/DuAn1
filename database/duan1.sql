-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 16, 2025 at 02:13 PM
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
-- Database: `duan1`
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

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `showtime_id`, `discount_id`, `booking_date`, `booked_seats`, `seat_type`, `customer_type`, `total_amount`, `discount_amount`, `final_amount`, `status`, `qr_code`, `booking_code`, `cinema_id`, `room_id`) VALUES
(4, 3, NULL, NULL, '2025-11-19 10:21:08', 'C12,C11', 'normal', NULL, 160000.00, 0.00, 160000.00, 'paid', NULL, 'BK2025111943C218', 1, 18),
(5, 3, NULL, NULL, '2025-11-19 10:32:15', 'B7,B6,B5', 'normal', NULL, 240000.00, 0.00, 240000.00, 'paid', NULL, 'BK20251119F0FF11', 1, 18),
(6, 3, NULL, NULL, '2025-11-19 12:53:17', 'C8,C7', 'normal', NULL, 160000.00, 0.00, 160000.00, 'cancelled', NULL, 'BK20251119D29273', 1, 18),
(7, 3, NULL, NULL, '2025-11-19 13:23:29', 'A11', NULL, NULL, 80000.00, 0.00, 80000.00, 'paid', NULL, 'BK17635334097649', 1, 18),
(8, 3, NULL, NULL, '2025-11-19 13:38:21', 'D11', NULL, NULL, 80000.00, 0.00, 80000.00, 'paid', NULL, 'BK17635343016235', 1, 18),
(10, 3, NULL, NULL, '2025-11-19 23:29:59', 'A4,A5,A6,A7', NULL, NULL, 320000.00, 0.00, 320000.00, 'paid', NULL, 'BK17635697994209', 1, 18),
(11, 3, NULL, NULL, '2025-11-19 23:34:04', 'B3,B4', NULL, NULL, 160000.00, 0.00, 160000.00, 'paid', NULL, 'BK17635700449534', 1, 18),
(12, 3, NULL, NULL, '2025-11-19 23:41:37', 'B9', NULL, NULL, 80000.00, 0.00, 80000.00, 'paid', NULL, 'BK17635704977283', 1, 18),
(13, 3, NULL, NULL, '2025-11-19 23:54:26', 'B8,B9', NULL, NULL, 180000.00, 0.00, 180000.00, 'pending', NULL, 'BK17635712663434', 1, 17),
(14, 3, NULL, NULL, '2025-11-20 09:43:19', 'A5,A6,A7,A8,A9', NULL, NULL, 400000.00, 0.00, 400000.00, 'paid', NULL, 'BK17636065992196', 1, 18),
(15, 3, NULL, NULL, '2025-11-20 19:46:25', 'A3,A4', NULL, NULL, 180000.00, 0.00, 180000.00, 'paid', NULL, 'BK17636427856547', 1, 17),
(17, 3, NULL, NULL, '2025-11-20 21:32:21', 'B6', NULL, NULL, 55000.00, 0.00, 55000.00, 'paid', NULL, 'BK17636491412783', 1, 18),
(18, 3, NULL, 3, '2025-11-24 10:53:16', 'A4,A5,A6,A7,A8', NULL, NULL, 375000.00, 75000.00, 300000.00, 'pending', NULL, 'BK17639563965750', 1, 18),
(19, 3, NULL, 3, '2025-11-24 10:59:50', 'A4,A5,A6,A7,A8', NULL, NULL, 325000.00, 65000.00, 260000.00, 'cancelled', NULL, 'BK17639567904155', 2, 21),
(20, 3, NULL, 6, '2025-11-26 10:48:58', 'A4', NULL, NULL, 85000.00, 85000.00, 0.00, 'pending', NULL, 'BK17641289388833', 1, 17),
(21, 3, NULL, NULL, '2025-11-27 20:05:22', 'A3', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17642487225080', 3, 23),
(22, 3, NULL, NULL, '2025-11-27 21:16:22', 'A1,A2,A3,A4,A5', NULL, NULL, 355000.00, 0.00, 355000.00, 'paid', NULL, 'BK17642529829933', 1, 17),
(23, 3, NULL, 6, '2025-11-28 10:02:48', 'A3', NULL, NULL, 75000.00, 63750.00, 11250.00, 'paid', NULL, 'BK17642989681601', 3, 23),
(24, 3, NULL, 6, '2025-11-28 10:58:43', 'B6', NULL, NULL, 75000.00, 63750.00, 11250.00, 'paid', NULL, 'BK17643023239903', 3, 23),
(25, 3, NULL, NULL, '2025-12-03 10:05:12', 'C4', NULL, NULL, 85000.00, 0.00, 85000.00, 'pending', NULL, 'BK17647311124144', 1, 17),
(26, 3, NULL, NULL, '2025-12-03 10:05:27', 'C4', NULL, NULL, 85000.00, 0.00, 85000.00, 'paid', NULL, 'BK17647311272996', 1, 17),
(28, 3, NULL, NULL, '2025-12-03 10:31:36', 'B1,B2,B3', NULL, NULL, 225000.00, 0.00, 225000.00, 'paid', NULL, 'BK17647326964828', 2, 21),
(29, 3, NULL, NULL, '2025-12-03 11:02:50', 'A3', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17647345706049', 2, 21),
(30, 9, NULL, NULL, '2025-12-03 11:02:51', 'A3', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17647345715822', 2, 21),
(31, 3, NULL, NULL, '2025-12-09 00:33:32', 'A1', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17652152121573', 1, 18),
(32, 3, NULL, 8, '2025-12-09 16:30:32', 'A1', NULL, NULL, 75000.00, 18750.00, 56250.00, 'paid', NULL, 'BK17652726329554', 2, 21),
(33, 3, 1, 1, '2025-12-11 23:25:06', 'D1,D2,D3,D4,E3,E4,E5,E6', NULL, NULL, 660000.00, 165000.00, 495000.00, 'paid', NULL, 'BK17654703064290', 1, 17),
(34, 3, 359, 5, '2025-12-11 23:46:20', 'A1,A2,A3,A4', NULL, NULL, 260000.00, 104000.00, 156000.00, 'paid', NULL, 'BK17654715801861', 1, 19),
(35, 3, 116, NULL, '2025-12-11 23:58:15', 'A1,A2,B3,B4,A3,A4,A5,A6', NULL, NULL, 520000.00, 0.00, 520000.00, 'paid', NULL, 'BK17654722955914', 2, 22);

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
(1, 'Times City', '458 Minh Khai, Hai Bà Trưng, Hà Nội', '2025-11-15 12:00:17'),
(2, 'Nguyễn Du', '116 Nguyễn Du, Quận 1, TP.HCM', '2025-11-15 12:00:17'),
(3, 'Phủ Lý', 'TTTM Vincom Plaza Phủ Lý, số 60, đường Biên Hòa, Phường Phủ Lý, Tỉnh Ninh Bình', '2025-11-16 17:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `movie_id` int DEFAULT NULL,
  `cinema_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `content` text,
  `status` varchar(20) DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `movie_id`, `cinema_id`, `rating`, `content`, `status`, `created_at`) VALUES
(2, 3, 6, 1, 5, 'cũng hay nhm bn cần làm j đó cho khách hàng bt đến nhiều hơn', 'active', '2025-11-27 20:44:25'),
(3, 3, 7, 3, 5, 'bổ rẻ hay nữa', 'active', '2025-11-27 21:10:59'),
(4, 3, 10, 1, 5, 'cũng hay đó', 'active', '2025-12-09 00:35:13'),
(5, 3, 10, 2, 4, 'bình thường', 'active', '2025-12-10 21:11:38'),
(6, 3, 12, 1, 4, 'nhân viên ngon', 'active', '2025-12-11 23:48:51');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Họ và tên khách hàng',
  `email` varchar(255) NOT NULL COMMENT 'Email khách hàng',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Số điện thoại',
  `subject` varchar(255) NOT NULL COMMENT 'Chủ đề liên hệ',
  `message` text NOT NULL COMMENT 'Nội dung tin nhắn',
  `status` varchar(20) DEFAULT 'pending' COMMENT 'Trạng thái: pending, processing, resolved, closed',
  `user_id` int DEFAULT NULL,
  `cinema_id` int DEFAULT NULL COMMENT 'ID rạp mà khách hàng muốn gửi phản hồi',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng lưu trữ thông tin liên hệ của khách hàng';

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `user_id`, `cinema_id`, `created_at`, `updated_at`) VALUES
(3, 'Duc anh', 'anh123@gmail.com', '0386036692', 'Đặt vé', 'fnbfdbdfd', 'resolved', 3, 2, '2025-12-06 20:04:41', '2025-12-06 20:18:10'),
(7, 'nguyễn văn A', 'anh123@gmail.com', '0386036636', 'Đặt vé', 'bcsxvdszd', 'pending', 3, 1, '2025-12-06 20:11:50', '2025-12-06 20:11:50'),
(9, 'Trương Tổng', 'hhhhhh@gmail.com', '0846146594', 'ngol thí', 'nhân viên ngon quá', 'pending', 11, 3, '2025-12-11 23:43:29', '2025-12-11 23:43:29');

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
  `title` varchar(255) NOT NULL,
  `discount_percent` int DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `movie_id` int DEFAULT NULL,
  `description` text,
  `benefits` json DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `cta` varchar(100) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_used` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng mã giảm giá (movie_id = NULL: áp dụng cho tất cả phim, movie_id != NULL: chỉ áp dụng cho phim cụ thể)';

--
-- Dumping data for table `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `title`, `discount_percent`, `start_date`, `end_date`, `movie_id`, `description`, `benefits`, `status`, `cta`, `usage_limit`, `usage_used`, `created_at`, `updated_at`) VALUES
(1, 'WEEKEND25', 'Giảm giá cuối tuần 25%', 25, '2025-11-10', '2025-12-31', 7, 'Ưu đãi đặc biệt cho các suất chiếu cuối tuần (Thứ 6, Thứ 7, Chủ nhật). Áp dụng cho tất cả phim và tất cả rạp.', '[\"Áp dụng cho suất chiếu cuối tuần\", \"Giảm 25% cho mọi vé\", \"Không giới hạn số lượng vé\", \"Áp dụng cho tất cả phim\"]', 'active', 'Đặt vé cuối tuần', 4, 0, '2025-11-20 15:00:00', '2025-12-16 21:11:01'),
(2, 'HOLIDAY30', 'Giảm giá ngày lễ 30%', 30, '2025-12-20', '2026-01-05', NULL, 'Ưu đãi đặc biệt trong dịp lễ Tết. Áp dụng cho các ngày lễ được chỉ định trong khoảng thời gian từ 20/12/2025 đến 05/01/2026.', '[\"Áp dụng trong dịp lễ Tết\", \"Giảm 30% cho mọi vé\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé dịp lễ', NULL, 0, '2025-11-20 15:00:00', '2025-11-24 09:19:57'),
(3, 'COUPLE20', 'Ưu đãi cặp đôi 20%', 20, '2025-11-01', '2025-12-31', NULL, 'Ưu đãi dành riêng cho các cặp đôi. Áp dụng khi mua từ 2 vé trở lên trong cùng một đơn đặt vé.', '[\"Áp dụng khi mua từ 2 vé\", \"Giảm 20% cho mỗi vé\", \"Ghế liền kề miễn phí\", \"Áp dụng cho tất cả suất chiếu\"]', 'active', 'Đặt vé cặp đôi', NULL, 0, '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(4, 'FAMILY35', 'Ưu đãi gia đình 35%', 35, '2025-11-01', '2025-12-31', NULL, 'Ưu đãi đặc biệt cho gia đình. Áp dụng khi mua từ 3 vé trở lên trong cùng một đơn đặt vé.', '[\"Áp dụng khi mua từ 3 vé\", \"Giảm 35% cho mỗi vé\", \"Ưu tiên ghế gia đình\", \"Áp dụng cho tất cả suất chiếu\"]', 'active', 'Đặt vé gia đình', NULL, 0, '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(5, 'PREMIERE40', 'Giảm giá buổi chiếu đặc biệt 40%', 40, '2025-11-15', '2025-12-15', NULL, 'Ưu đãi đặc biệt cho các buổi chiếu đặc biệt và phim mới ra mắt. Áp dụng cho các suất chiếu được chỉ định.', '[\"Áp dụng cho buổi chiếu đặc biệt\", \"Giảm 40% cho mỗi vé\", \"Ưu tiên đặt chỗ sớm\", \"Số lượng có hạn\"]', 'active', 'Đặt vé chiếu đặc biệt', NULL, 0, '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(6, 'FASHION209', '85%', 85, '2025-11-28', '2025-11-30', NULL, '', NULL, 'active', '', NULL, 0, '2025-11-26 10:47:51', '2025-11-28 10:02:17'),
(7, 'TRONCHAY30', 'Giảm giá TRỐN CHẠY TỬ THẦN 30%', 30, '2025-11-28', '2025-12-21', 9, 'Ưu đãi đặc biệt dành riêng cho phim TRỐN CHẠY TỬ THẦN-T18. Áp dụng cho tất cả suất chiếu của phim này.', '[\"Giảm 30% cho tổng đơn hàng\", \"Chỉ áp dụng cho phim TRỐN CHẠY TỬ THẦN-T18\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé ngay', 8, 0, '2025-11-28 10:00:00', '2025-12-16 21:10:28'),
(8, 'CHINGA25', 'Giảm giá Chị Ngã Em Nâng 25%', 25, '2025-12-10', '2025-12-21', 12, 'Ưu đãi đặc biệt dành riêng cho phim Chị Ngã Em Nâng. Áp dụng cho tất cả suất chiếu của phim này.', '[\"Giảm 25% cho tổng đơn hàng\", \"Chỉ áp dụng cho phim Chị Ngã Em Nâng\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé ngay', 2, 0, '2025-11-28 10:00:00', '2025-12-16 21:10:00');

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
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int NOT NULL,
  `genre_id` int DEFAULT NULL,
  `cinema_id` int DEFAULT NULL,
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

INSERT INTO `movies` (`id`, `genre_id`, `cinema_id`, `title`, `description`, `duration`, `image`, `trailer`, `release_date`, `end_date`, `format`, `original_language`, `subtitle_or_dub`, `age_rating`, `producer`, `status`, `created_at`, `updated_at`) VALUES
(6, 7, NULL, 'Quán kỳ nma', 'ko hay lắm đâu', 120, 'image/phim 4.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-12-11', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-14 19:38:50', '2025-12-11 21:34:04'),
(7, 3, NULL, 'Phòng trọ ma bầu', 'kinh dị ko nên xem', 120, 'image/phim 5.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-12-11', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-14 20:27:20', '2025-12-11 11:59:41'),
(8, 1, NULL, 'Truy tìm long diên hương', 'cx hay lắm nha', 122, 'image/phim5.jpg', 'https://youtu.be/XjhAFebnNkM?si=vKFX_9ElyDAoSMMX', '2025-12-11', '2025-12-20', '2D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-15 11:43:43', '2025-12-11 21:34:16'),
(9, 1, NULL, 'TRỐN CHẠY TỬ THẦN-T18', 'Trong bối cảnh xã hội tương lai gần, Trốn Chạy Tử Thần là chương trình truyền hình ăn khách nhất, một cuộc thi sinh tồn khốc liệt nơi các thí sinh, được gọi là \"Runners\", phải trốn chạy suốt 30 ngày khỏi sự truy đuổi của các sát thủ chuyên nghiệp. Mọi bước đi của họ đều được phát sóng công khai cho khán giả theo dõi và phần thưởng tiền mặt sẽ tăng lên sau mỗi ngày sống sót. Vì cần tiền cứu chữa cho cô con gái bệnh nặng, Ben...', 133, 'image/phim 3.jpg', 'https://youtu.be/NuOl156fv_c?si=98qM39lvGn18VcdI', '2025-12-11', '2025-12-20', '2D', 'Tiếng Anh', 'Phụ Đề', 'C16', 'Mỹ', 'active', '2025-11-15 22:40:20', '2025-12-11 21:34:23'),
(10, 6, NULL, 'MỘ ĐOM ĐÓM', 'Hai anh em Seita và Setsuko mất mẹ sau cuộc thả bom dữ dội của không quân Mỹ. Cả hai phải vật lộn để tồn tại ở Nhật Bản hậu Thế chiến II. Nhưng xã hội khắc nghiệt và chúng vật lộn tìm kiếm thức ăn cũng như thoát khỏi những khó khăn giữa chiến tranh.', 120, 'image/phim 6.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-12-11', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-17 09:58:57', '2025-12-11 21:34:30'),
(11, 2, NULL, 'Tay Anh Giữ Một Vì Sao', 'Tay Anh Giữ Một Vì Sao\" mang đến một làn gió mới trong dòng phim chiếu rạp hay khi kết hợp khéo léo giữa yếu tố hài hước và cảm xúc chân thành. Câu chuyện xoay quanh siêu sao Kang Jun Woo bỗng rơi vào chuỗi sự cố trớ trêu khiến anh vô tình \"mắc kẹt\" tại Việt Nam. Tại đây, anh gặp Thảo - cô gái bán cà phê giản dị nhưng mang trong mình khát vọng lớn lao. Những va chạm và hiểu lầm dần trở thành sợi dây gắn kết, giúp cả hai tìm thấy niềm tin, ước mơ và định nghĩa mới về tình yêu. Bộ phim không chỉ khiến khán giả bật cười bởi những tình huống duyên dáng mà còn chạm đến trái tim bằng câu chuyện nhân văn về sự đồng cảm và thay đổi.', 117, 'image/phim7.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-12-11', '2025-12-20', '2D', 'Tiếng Anh', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-19 09:35:10', '2025-12-11 21:33:37'),
(12, 2, NULL, 'Chị Ngã Em Nâng', 'Giữa muôn vàn phim chiếu rạp hay tháng 10, \"Chị Ngã Em Nâng\" nổi bật như một bản giao hòa đầy cảm xúc về tình thân và nghị lực con người. Bộ phim khắc họa hành trình của hai chị em Thương và Lực là những người lớn lên trong gia đình gắn bó với nghề làm nhang truyền thống. Với tuổi thơ nhiều mất mát, Thương trở thành điểm tựa duy nhất cho em trai, mang trong mình khát vọng đổi đời và niềm tin mãnh liệt vào tương lai. Thế nhưng, khi thành công đến, sự kỳ vọng và áp lực vô tình khiến tình chị em rạn nứt, đẩy họ đến những lựa chọn đau lòng. \"Chị Ngã Em Nâng\" chạm đến trái tim người xem bằng những giá trị nhân văn sâu sắc, về tình thương, sự bao dung và ý nghĩa của hai chữ \"gia đình\".', 122, 'image/hh.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-12-11', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C13', 'Việt Nam', 'active', '2025-11-19 09:40:28', '2025-12-11 21:34:40'),
(15, 7, NULL, 'Anh Trai Say Xe', 'Anh Trai Say Xe” xoay quanh nhóm bạn thân từ thời cấp 3 gồm Tae Jeong, Do Jin, Yeon Min và Geum Bok. Họ từng hứa sẽ đi du lịch cùng nhau sau khi tốt nghiệp, nhưng vì cuộc sống mỗi người một hướng: kẻ bận kiếm sống, người xuất gia, kẻ định cư, người lại gặp vấn đề tâm lý và lời hứa hẹn khi xưa cứ thế bị bỏ lỡ suốt hơn mười năm. Khi hội bạn này quyết định thực hiện chuyến xuất ngoại đầu tiên tại Bangkok, mọi chuyện nhanh chóng vượt khỏi tầm kiểm soát. Bốn anh trai liên tục gặp tình huống dở khóc dở cười, tạo nên hành trình đầy tiếng cười, kịch tính và cả những khoảnh khắc lắng đọng. Sự xuất hiện của cô nàng “vô duyên” Ok Sim càng khiến chuyến đi trở thành ký ức không thể quên. Đây là những bộ phim chiếu rạp hay đáng mong chờ cho những tháng cuối năm 2025.', 110, 'image/p1.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-12-15', '2025-12-26', '2D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Hàn Quốc', 'active', '2025-12-10 10:14:14', '2025-12-10 21:36:13'),
(16, 3, NULL, 'Núi Tế Vong', 'Núi Tế Vong” kể về Gia Minh, Ngọc Hân và An Vĩ – ba sinh viên trong câu lạc bộ leo núi. Trong một chuyến thám hiểm, họ phớt lờ cảnh báo địa phương và bước vào khu vực cấm dưới Núi Ngọc Sơn, nơi đây nổi tiếng với lời truyền: “Nếu thấy bóng người mặc áo mưa vàng, tuyệt đối đừng đi theo.” Cả nhóm nhanh chóng bị lạc và rơi vào một vòng lặp kỳ quái dù đã đánh dấu đường đi. Giữa sương mù dày đặc, một bóng người khoác áo mưa vàng xuất hiện tưởng chừng đem lại hy vọng, nhưng lại mở ra chuỗi hiện tượng rùng rợn liên tiếp. Khi một người trong nhóm mất tích bí ẩn, hai người còn lại phải đối mặt với nỗi ám ảnh tột độ, bị săn đuổi bởi một thực thể ma quỷ và mắc kẹt trong địa ngục của sự lặp lại. Bộ phim dựa trên một kỳ án nổi tiếng của Đài Loan, tái hiện “Vong Hồn Áo Mưa Vàng” đầy ám ảnh.', 122, 'image/p2.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-12-15', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C13', 'Mỹ', 'active', '2025-11-19 09:40:28', '2025-12-10 21:36:36'),
(17, 2, NULL, 'Sư Thầy Gặp Sư Lầy', '“Sư Thầy Gặp Sư Lầy” theo chân Luang Phi Pae – một nhà sư Thái Lan nghiêm khắc, tu tập trong truyền thống Phật giáo Nam tông với giới luật chặt chẽ. Trong chuyến sang Nhật dự lễ cưới của em gái, Sư Pae bàng hoàng khi phát hiện chú rể lại là một nhà sư Nhật thuộc tông phái cho phép tăng sĩ kết hôn. Sự khác biệt quan niệm về tu hành, giới luật và đời sống gia đình khiến hai bên liên tục rơi vào những tình huống oái oăm, hài hước. Khi mâu thuẫn giữa “không được kết hôn” và “kết hôn là bình thường” dần bùng lên, bộ phim mở ra hành trình vừa vui nhộn vừa cảm động, khai thác sự va chạm văn hoá và niềm tin tôn giáo theo góc nhìn nhẹ nhàng, nhân văn.', 90, 'image/p3.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-12-15', '2025-12-22', '2D', 'Tiếng Việt', 'Phụ Đề', 'C16', 'Thái Lan', 'active', '2025-12-10 10:30:11', '2025-12-10 21:37:09'),
(18, 6, NULL, 'Zootopia 2', 'Trong bộ phim \"Zootopia 2 - Phi Vụ Động Trời 2\" từ Walt Disney Animation Studios, hai thám tử Judy Hopps (lồng tiếng bởi Ginnifer Goodwin) và Nick Wilde (lồng tiếng bởi Jason Bateman) bước vào hành trình truy tìm một sinh vật bò sát bí ẩn vừa xuất hiện tại Zootopia và khiến cả vương quốc động vật bị đảo lộn. Để phá được vụ án, Judy và Nick buộc phải hoạt động bí mật tại những khu vực mới lạ của thành phố – nơi mối quan hệ đồng nghiệp của họ bị thử thách hơn bao giờ hết.', 120, 'image/zootopia.jpg', 'https://www.youtube.com/watch?v=BjkIOU5PhyQ', '2025-12-11', '2025-12-31', '2D', 'Tiếng Anh', 'Tiếng Việt', 'P', 'Animation USA', 'active', '2025-12-11 22:35:06', '2025-12-11 22:35:06'),
(19, 6, NULL, 'Vua của các vua', 'Trong một đêm Giáng Sinh đầy tuyết trắng, nhà văn Charles Dickens kể cho cậu con trai nhỏ của mình nghe câu chuyện kỳ diệu về “Vua của các Vua” – Chúa Giê-su. Cậu bé tưởng tượng mình được bước vào chính thế giới ấy - nơi có ánh sao rực rỡ, tiếng hát thiên thần vang vọng, và những phép lạ tuyệt vời xảy ra khắp nơi. Cậu theo chân Chúa Giê-su, chứng kiến lòng nhân hậu, tình yêu thương và những điều kỳ diệu Người mang đến cho mọi người. Khi câu chuyện đi đến hành trình gian nan và phép màu phục sinh, cậu bé hiểu ra ý nghĩa thật sự của niềm tin, sự tha thứ và hy vọng — những món quà quý giá nhất mà mùa Giáng Sinh gửi tặng cho mỗi trái tim.', 60, 'image/vuacuacacvua.jpg', 'https://www.youtube.com/watch?v=0kCRLxU4M8Y', '2025-12-14', '2025-12-31', '2D', 'Tiếng Hàn Quốc', 'Tiếng Việt', 'P', 'Animation Korea', 'active', '2025-12-11 22:49:50', '2025-12-11 22:49:50'),
(20, 3, NULL, 'Ma lủng tường', 'Một gia đình chết một cách khủng khiếp. Một cô gái bị buộc tội là kẻ giết người.Cuộc chiến trừ tà đẫm máu và gây choáng nhất cuối năm giữa hậu duệ một gia tộc diệt quỷ cùng những thế lực tà ác khủng khiếp nhất xứ vạn đảo.', 99, 'image/malungtuong.jpg', 'https://www.youtube.com/watch?v=W3MX-xMTivU', '2025-12-23', '2026-01-11', '3D', 'Tiếng Anh', 'Tiếng Việt', 'C18', 'Horror', 'active', '2025-12-11 22:58:12', '2025-12-16 19:14:19'),
(24, 1, NULL, 'AVATAR', 'phần 2 thú vị của bộ phim avatar', 120, 'image/avatar.jpg', 'https://www.youtube.com/watch?v=Ma1x7ikpid8', '2025-12-26', '2026-01-03', '3D', 'Tiếng Anh', 'Tiếng Việt', 'C16', 'AEON', 'active', '2025-12-16 19:19:17', '2025-12-16 19:19:17'),
(25, 1, NULL, 'Chú thuật hồi chiến', 'thử thách gha shibuya đầy hấp hẫn và hồi hộp', 120, 'image/chuthuathoichien.jpg', 'https://www.youtube.com/watch?v=EWKm0lolQRM', '2025-12-22', '2026-01-11', '3D', 'Tiếng Nhật', 'Tiếng Việt', 'C13', 'Japan movie', 'active', '2025-12-16 19:24:25', '2025-12-16 19:24:25'),
(26, 1, NULL, 'SPONGEBOB', 'tuổi thơ của biết bao người quay trở lại', 120, 'image/spongebob.jpg', 'https://www.youtube.com/watch?v=XdPt8QWTypI', '2025-12-23', '2025-12-26', '3D', 'Tiếng Nhật', 'Tiếng Việt', 'P', 'USA', 'active', '2025-12-16 19:27:29', '2025-12-16 19:27:29'),
(27, 2, NULL, 'SCARLET', 'Bộ phim hoạt hình hot nhất năm', 120, 'image/scarlet.jpg', 'https://www.youtube.com/watch?v=uveBZ_JKrU4', '2025-12-23', '2026-01-11', '2D', 'Tiếng Anh', 'Tiếng Việt', 'P', 'Japan', 'active', '2025-12-16 19:32:10', '2025-12-16 19:32:10'),
(28, 1, NULL, 'Em sẽ khử anh', 'bộ phim hành động kịch tính', 90, 'image/emsekhuanh.jpg', 'https://www.youtube.com/watch?v=5ijfhBt40ck', '2025-12-27', '2026-01-11', '2D', 'Tiếng Anh', 'Tiếng Việt', 'C16', 'AEON', 'active', '2025-12-16 19:35:57', '2025-12-16 19:35:57'),
(29, 3, NULL, 'Thử thách thần chết', 'Thử thách thần chết sẽ đưa bạn ...', 120, 'image/thuthachthanchet.jpg', 'https://www.youtube.com/watch?v=PPwIGAajeXc', '2025-12-25', '2026-01-10', '3D', 'Tiếng Nhật', 'Tiếng Việt', 'C16', 'AEON', 'active', '2025-12-16 19:39:43', '2025-12-16 19:39:43'),
(30, 3, NULL, 'Oán thai đòi mẹ', 'Bộ phim kinh dị vn', 120, 'image/oanthaidoime.jpg', 'https://www.youtube.com/watch?v=VsvB5pzmx3g', '2025-12-28', '2026-01-11', '3D', 'Tiếng Anh', 'Tiếng Việt', 'C18', 'Horror', 'active', '2025-12-16 19:44:55', '2025-12-16 19:44:55'),
(31, 2, NULL, 'Tom & Jerry', 'Bộ phim hoạt hình bất hủ', 120, 'image/tom.jpg', 'https://www.youtube.com/watch?v=kP9TfCWaQT4', '2025-12-31', '2026-01-11', '3D', 'Tiếng Anh', 'Tiếng Việt', 'C16', 'USA', 'active', '2025-12-16 19:48:44', '2025-12-16 19:48:44'),
(32, 7, NULL, 'Linh trưởng', 'linh trưởng', 60, 'image/linhtruong.jpg', 'https://www.youtube.com/watch?v=vA8iXY8a1YQ', '2025-12-27', '2026-01-11', '3D', 'Tiếng Anh', 'Tiếng Việt', 'C13', 'USA', 'active', '2025-12-16 19:52:05', '2025-12-16 19:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `movie_cinemas`
--

CREATE TABLE `movie_cinemas` (
  `id` int NOT NULL,
  `movie_id` int NOT NULL,
  `cinema_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movie_cinemas`
--

INSERT INTO `movie_cinemas` (`id`, `movie_id`, `cinema_id`, `created_at`) VALUES
(4, 7, 1, '2025-11-28 01:18:00'),
(5, 7, 2, '2025-11-28 01:18:00'),
(6, 7, 3, '2025-11-28 01:18:00'),
(7, 8, 1, '2025-11-28 01:18:07'),
(8, 8, 3, '2025-11-28 01:18:07'),
(12, 12, 2, '2025-11-28 01:18:28'),
(16, 10, 3, '2025-11-28 01:22:16'),
(17, 9, 2, '2025-11-28 01:24:59'),
(18, 9, 3, '2025-11-28 01:24:59'),
(20, 6, 1, '2025-11-28 03:10:02'),
(21, 11, 1, '2025-11-28 03:31:05'),
(22, 11, 2, '2025-11-28 03:31:05'),
(23, 11, 3, '2025-11-28 03:31:05'),
(25, 15, 1, '2025-12-10 03:14:14'),
(26, 16, 1, '2025-12-10 03:17:25'),
(27, 17, 2, '2025-12-10 03:30:11'),
(28, 10, 1, '2025-12-11 14:33:52'),
(29, 18, 1, '2025-12-11 15:35:06'),
(30, 18, 2, '2025-12-11 15:35:06'),
(31, 18, 3, '2025-12-11 15:35:06'),
(32, 19, 1, '2025-12-11 15:49:50'),
(33, 19, 2, '2025-12-11 15:49:50'),
(34, 19, 3, '2025-12-11 15:49:50'),
(35, 20, 1, '2025-12-11 15:58:12'),
(36, 20, 2, '2025-12-11 15:58:12'),
(43, 24, 1, '2025-12-16 12:19:17'),
(44, 25, 2, '2025-12-16 12:24:25'),
(45, 25, 3, '2025-12-16 12:24:25'),
(46, 26, 1, '2025-12-16 12:27:29'),
(47, 26, 2, '2025-12-16 12:27:29'),
(48, 26, 3, '2025-12-16 12:27:29'),
(49, 27, 1, '2025-12-16 12:32:10'),
(50, 27, 2, '2025-12-16 12:32:10'),
(51, 28, 1, '2025-12-16 12:35:57'),
(52, 28, 2, '2025-12-16 12:35:57'),
(53, 28, 3, '2025-12-16 12:35:57'),
(54, 29, 1, '2025-12-16 12:39:43'),
(55, 29, 2, '2025-12-16 12:39:43'),
(56, 29, 3, '2025-12-16 12:39:43'),
(57, 30, 1, '2025-12-16 12:44:55'),
(58, 31, 1, '2025-12-16 12:48:44'),
(59, 31, 2, '2025-12-16 12:48:44'),
(60, 31, 3, '2025-12-16 12:48:44'),
(61, 32, 1, '2025-12-16 12:52:05'),
(62, 32, 2, '2025-12-16 12:52:05'),
(63, 32, 3, '2025-12-16 12:52:05');

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `method`, `transaction_code`, `payment_date`, `total_amount`, `discount_amount`, `final_amount`, `status`) VALUES
(1, 6, 'vnpay', '15270232', '2025-11-19 05:54:14', 160000.00, 0.00, 160000.00, 'failed'),
(2, 7, 'vnpay', '15270278', '2025-11-19 06:24:14', 80000.00, 0.00, 80000.00, 'paid'),
(3, 8, 'vnpay', '15270313', '2025-11-19 06:38:44', 80000.00, 0.00, 80000.00, 'paid'),
(4, NULL, 'vnpay', '15271280', '2025-11-19 15:55:34', 640000.00, 0.00, 640000.00, 'paid'),
(5, 11, 'vnpay', '15271319', '2025-11-19 16:34:32', 160000.00, 0.00, 160000.00, 'paid'),
(6, 12, 'vnpay', '15271325', '2025-11-19 16:42:48', 80000.00, 0.00, 80000.00, 'paid'),
(7, 14, 'vnpay', '15271629', '2025-11-20 02:44:19', 400000.00, 0.00, 400000.00, 'paid'),
(8, 15, 'vnpay', '15273280', '2025-11-20 12:47:13', 180000.00, 0.00, 180000.00, 'paid'),
(9, 17, 'vnpay', '15273478', '2025-11-20 14:33:33', 55000.00, 0.00, 55000.00, 'paid'),
(10, 21, 'vnpay', '15306159', '2025-11-27 13:06:51', 75000.00, 0.00, 75000.00, 'paid'),
(11, 22, 'vnpay', '15306311', '2025-11-27 14:16:54', 355000.00, 0.00, 355000.00, 'paid'),
(12, 23, 'vnpay', '15307770', '2025-11-28 03:03:26', 11250.00, 63750.00, 11250.00, 'paid'),
(13, 24, 'vnpay', '15307936', '2025-11-28 03:59:37', 11250.00, 63750.00, 11250.00, 'paid'),
(14, 26, 'vnpay', '15316533', '2025-12-03 03:05:47', 85000.00, 0.00, 85000.00, 'paid'),
(15, NULL, 'vnpay', '15316592', '2025-12-03 03:25:30', 112500.00, 37500.00, 112500.00, 'paid'),
(16, 28, 'vnpay', '15316605', '2025-12-03 03:31:48', 225000.00, 0.00, 225000.00, 'paid'),
(17, 29, 'vnpay', '15316675', '2025-12-03 04:03:14', 75000.00, 0.00, 75000.00, 'paid'),
(18, 30, 'vnpay', '15316674', '2025-12-03 04:03:15', 75000.00, 0.00, 75000.00, 'paid'),
(19, 31, 'vnpay', '15328492', '2025-12-08 17:34:13', 75000.00, 0.00, 75000.00, 'paid'),
(20, 32, 'vnpay', '15329950', '2025-12-09 09:40:30', 56250.00, 18750.00, 56250.00, 'paid'),
(21, 33, 'vnpay', '15335846', '2025-12-11 16:25:26', 495000.00, 165000.00, 495000.00, 'paid'),
(22, 34, 'vnpay', '15335893', '2025-12-11 16:48:24', 156000.00, 104000.00, 156000.00, 'paid'),
(23, 35, 'vnpay', '15335902', '2025-12-11 16:58:31', 520000.00, 0.00, 520000.00, 'paid');

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
(24, 'view_dashboard', 'Xem dashboard', 'Xem trang tổng quan', 'dashboard', '2025-11-18 14:45:36'),
(50, 'view_cinemas_manager', 'Xem rạp (Quản lý)', 'Xem danh sách rạp được gán', 'cinemas', '2025-11-26 21:47:33'),
(51, 'manage_cinemas_manager', 'Quản lý rạp (Quản lý)', 'Quản lý rạp được gán', 'cinemas', '2025-11-26 21:47:33');

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
(116, 'manager', 14, '2025-11-26 21:47:34'),
(117, 'manager', 8, '2025-11-26 21:47:34'),
(118, 'manager', 10, '2025-11-26 21:47:34'),
(119, 'manager', 12, '2025-11-26 21:47:34'),
(120, 'manager', 13, '2025-11-26 21:47:34'),
(121, 'manager', 7, '2025-11-26 21:47:34'),
(122, 'manager', 21, '2025-11-26 21:47:34'),
(123, 'manager', 24, '2025-11-26 21:47:34'),
(124, 'manager', 5, '2025-11-26 21:47:34'),
(125, 'manager', 1, '2025-11-26 21:47:34'),
(126, 'manager', 9, '2025-11-26 21:47:34'),
(127, 'manager', 11, '2025-11-26 21:47:34'),
(128, 'manager', 23, '2025-11-26 21:47:34'),
(129, 'staff', 14, '2025-11-27 18:58:31'),
(130, 'staff', 13, '2025-11-27 18:58:31'),
(131, 'staff', 22, '2025-11-27 18:58:31'),
(132, 'staff', 21, '2025-11-27 18:58:31'),
(133, 'staff', 24, '2025-11-27 18:58:31'),
(134, 'staff', 2, '2025-11-27 18:58:31'),
(135, 'staff', 3, '2025-11-27 18:58:31'),
(136, 'staff', 1, '2025-11-27 18:58:31'),
(137, 'staff', 12, '2025-11-27 18:58:31'),
(138, 'staff', 11, '2025-11-27 18:58:31'),
(139, 'staff', 15, '2025-11-27 18:58:31');

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
(18, 1, 'R2', 'Phòng Chiếu 2', 150),
(19, 1, 'R3', 'Phòng Chiếu 3', 200),
(21, 2, 'R1', 'PC1', 130),
(22, 2, 'R2', 'PC2', 150),
(23, 3, 'S1', 'PL1', 150),
(24, 3, 'S2', 'PL2', 170),
(25, 2, 'R3', 'PC3', 120);

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
(4, 17, 'A', 4, 'vip', 0.00, 'available'),
(5, 17, 'A', 5, 'vip', 0.00, 'available'),
(6, 17, 'A', 6, 'vip', 0.00, 'available'),
(7, 17, 'A', 7, 'vip', 0.00, 'available'),
(8, 17, 'A', 8, 'vip', 0.00, 'available'),
(9, 17, 'A', 9, 'vip', 0.00, 'available'),
(10, 17, 'A', 10, 'normal', 0.00, 'maintenance'),
(11, 17, 'A', 11, 'normal', NULL, 'active'),
(12, 17, 'A', 12, 'normal', 0.00, 'inactive'),
(13, 17, 'B', 1, 'normal', NULL, 'active'),
(14, 17, 'B', 2, 'normal', NULL, 'active'),
(15, 17, 'B', 3, 'vip', 0.00, 'available'),
(16, 17, 'B', 4, 'vip', 0.00, 'available'),
(17, 17, 'B', 5, 'vip', 0.00, 'available'),
(18, 17, 'B', 6, 'vip', 0.00, 'available'),
(19, 17, 'B', 7, 'vip', 0.00, 'available'),
(20, 17, 'B', 8, 'vip', 0.00, 'available'),
(21, 17, 'B', 9, 'vip', 0.00, 'available'),
(22, 17, 'B', 10, 'vip', 0.00, 'available'),
(23, 17, 'B', 11, 'normal', NULL, 'active'),
(24, 17, 'B', 12, 'normal', NULL, 'active'),
(25, 17, 'C', 1, 'VIP', 0.00, 'active'),
(26, 17, 'C', 2, 'VIP', 0.00, 'active'),
(27, 17, 'C', 3, 'normal', 0.00, 'maintenance'),
(28, 17, 'C', 4, 'vip', 0.00, 'available'),
(29, 17, 'C', 5, 'vip', 0.00, 'available'),
(30, 17, 'C', 6, 'vip', 0.00, 'available'),
(31, 17, 'C', 7, 'vip', 0.00, 'available'),
(32, 17, 'C', 8, 'vip', 0.00, 'available'),
(33, 17, 'C', 9, 'vip', 0.00, 'available'),
(34, 17, 'C', 10, 'vip', 0.00, 'available'),
(35, 17, 'C', 11, 'VIP', 0.00, 'active'),
(36, 17, 'C', 12, 'VIP', 0.00, 'active'),
(37, 17, 'D', 1, 'VIP', 0.00, 'active'),
(38, 17, 'D', 2, 'VIP', 0.00, 'active'),
(39, 17, 'D', 3, 'vip', 0.00, 'available'),
(40, 17, 'D', 4, 'vip', 0.00, 'available'),
(41, 17, 'D', 5, 'vip', 0.00, 'available'),
(42, 17, 'D', 6, 'vip', 0.00, 'available'),
(43, 17, 'D', 7, 'vip', 0.00, 'available'),
(44, 17, 'D', 8, 'vip', 0.00, 'available'),
(45, 17, 'D', 9, 'normal', 0.00, 'maintenance'),
(46, 17, 'D', 10, 'vip', 0.00, 'available'),
(47, 17, 'D', 11, 'VIP', 0.00, 'active'),
(48, 17, 'D', 12, 'VIP', 0.00, 'active'),
(49, 17, 'E', 1, 'normal', 0.00, 'inactive'),
(50, 17, 'E', 2, 'VIP', 0.00, 'active'),
(51, 17, 'E', 3, 'vip', 0.00, 'available'),
(52, 17, 'E', 4, 'vip', 0.00, 'available'),
(53, 17, 'E', 5, 'vip', 0.00, 'available'),
(54, 17, 'E', 6, 'vip', 0.00, 'available'),
(55, 17, 'E', 7, 'vip', 0.00, 'available'),
(56, 17, 'E', 8, 'vip', 0.00, 'available'),
(57, 17, 'E', 9, 'vip', 0.00, 'available'),
(58, 17, 'E', 10, 'vip', 0.00, 'available'),
(59, 17, 'E', 11, 'VIP', 0.00, 'active'),
(60, 17, 'E', 12, 'normal', 0.00, 'inactive'),
(61, 17, 'F', 1, 'normal', NULL, 'active'),
(62, 17, 'F', 2, 'VIP', 0.00, 'active'),
(63, 17, 'F', 3, 'normal', 0.00, 'available'),
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
(95, 17, 'H', 11, 'normal', 0.00, 'available'),
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
(1021, 23, 'A', 1, 'normal', 0.00, 'available'),
(1022, 23, 'A', 2, 'normal', 0.00, 'available'),
(1023, 23, 'A', 3, 'normal', 0.00, 'available'),
(1024, 23, 'A', 4, 'normal', 0.00, 'available'),
(1025, 23, 'A', 5, 'normal', 0.00, 'available'),
(1026, 23, 'A', 6, 'normal', 0.00, 'available'),
(1027, 23, 'A', 7, 'normal', 0.00, 'available'),
(1028, 23, 'A', 8, 'normal', 0.00, 'available'),
(1029, 23, 'A', 9, 'normal', 0.00, 'available'),
(1030, 23, 'A', 10, 'normal', 0.00, 'available'),
(1031, 23, 'A', 11, 'normal', 0.00, 'available'),
(1032, 23, 'A', 12, 'normal', 0.00, 'available'),
(1033, 23, 'A', 13, 'normal', 0.00, 'available'),
(1034, 23, 'A', 14, 'normal', 0.00, 'available'),
(1035, 23, 'A', 15, 'normal', 0.00, 'available'),
(1036, 23, 'B', 1, 'normal', 0.00, 'available'),
(1037, 23, 'B', 2, 'normal', 0.00, 'available'),
(1038, 23, 'B', 3, 'normal', 0.00, 'available'),
(1039, 23, 'B', 4, 'normal', 0.00, 'available'),
(1040, 23, 'B', 5, 'normal', 0.00, 'available'),
(1041, 23, 'B', 6, 'normal', 0.00, 'available'),
(1042, 23, 'B', 7, 'normal', 0.00, 'available'),
(1043, 23, 'B', 8, 'normal', 0.00, 'available'),
(1044, 23, 'B', 9, 'normal', 0.00, 'available'),
(1045, 23, 'B', 10, 'normal', 0.00, 'available'),
(1046, 23, 'B', 11, 'normal', 0.00, 'available'),
(1047, 23, 'B', 12, 'normal', 0.00, 'available'),
(1048, 23, 'B', 13, 'normal', 0.00, 'available'),
(1049, 23, 'B', 14, 'normal', 0.00, 'available'),
(1050, 23, 'B', 15, 'normal', 0.00, 'available'),
(1051, 23, 'C', 1, 'normal', 0.00, 'available'),
(1052, 23, 'C', 2, 'normal', 0.00, 'available'),
(1053, 23, 'C', 3, 'normal', 0.00, 'available'),
(1054, 23, 'C', 4, 'normal', 0.00, 'available'),
(1055, 23, 'C', 5, 'normal', 0.00, 'available'),
(1056, 23, 'C', 6, 'normal', 0.00, 'available'),
(1057, 23, 'C', 7, 'normal', 0.00, 'available'),
(1058, 23, 'C', 8, 'normal', 0.00, 'available'),
(1059, 23, 'C', 9, 'normal', 0.00, 'available'),
(1060, 23, 'C', 10, 'normal', 0.00, 'available'),
(1061, 23, 'C', 11, 'normal', 0.00, 'available'),
(1062, 23, 'C', 12, 'normal', 0.00, 'available'),
(1063, 23, 'C', 13, 'normal', 0.00, 'available'),
(1064, 23, 'C', 14, 'normal', 0.00, 'available'),
(1065, 23, 'C', 15, 'normal', 0.00, 'available'),
(1066, 23, 'D', 1, 'normal', 0.00, 'available'),
(1067, 23, 'D', 2, 'normal', 0.00, 'available'),
(1068, 23, 'D', 3, 'vip', 10000.00, 'available'),
(1069, 23, 'D', 4, 'normal', 0.00, 'available'),
(1070, 23, 'D', 5, 'normal', 0.00, 'available'),
(1071, 23, 'D', 6, 'normal', 0.00, 'available'),
(1072, 23, 'D', 7, 'normal', 0.00, 'available'),
(1073, 23, 'D', 8, 'normal', 0.00, 'available'),
(1074, 23, 'D', 9, 'normal', 0.00, 'available'),
(1075, 23, 'D', 10, 'normal', 0.00, 'available'),
(1076, 23, 'D', 11, 'normal', 0.00, 'available'),
(1077, 23, 'D', 12, 'normal', 0.00, 'available'),
(1078, 23, 'D', 13, 'normal', 0.00, 'available'),
(1079, 23, 'D', 14, 'normal', 0.00, 'available'),
(1080, 23, 'D', 15, 'normal', 0.00, 'available'),
(1081, 23, 'E', 1, 'normal', 0.00, 'available'),
(1082, 23, 'E', 2, 'normal', 0.00, 'available'),
(1083, 23, 'E', 3, 'normal', 0.00, 'available'),
(1084, 23, 'E', 4, 'normal', 0.00, 'available'),
(1085, 23, 'E', 5, 'normal', 0.00, 'available'),
(1086, 23, 'E', 6, 'normal', 0.00, 'available'),
(1087, 23, 'E', 7, 'normal', 0.00, 'available'),
(1088, 23, 'E', 8, 'normal', 0.00, 'available'),
(1089, 23, 'E', 9, 'normal', 0.00, 'available'),
(1090, 23, 'E', 10, 'normal', 0.00, 'available'),
(1091, 23, 'E', 11, 'normal', 0.00, 'available'),
(1092, 23, 'E', 12, 'normal', 0.00, 'available'),
(1093, 23, 'E', 13, 'normal', 0.00, 'available'),
(1094, 23, 'E', 14, 'normal', 0.00, 'available'),
(1095, 23, 'E', 15, 'normal', 0.00, 'available'),
(1096, 23, 'F', 1, 'normal', 0.00, 'available'),
(1097, 23, 'F', 2, 'normal', 0.00, 'available'),
(1098, 23, 'F', 3, 'normal', 0.00, 'available'),
(1099, 23, 'F', 4, 'normal', 0.00, 'available'),
(1100, 23, 'F', 5, 'normal', 0.00, 'available'),
(1101, 23, 'F', 6, 'normal', 0.00, 'available'),
(1102, 23, 'F', 7, 'normal', 0.00, 'available'),
(1103, 23, 'F', 8, 'normal', 0.00, 'available'),
(1104, 23, 'F', 9, 'normal', 0.00, 'available'),
(1105, 23, 'F', 10, 'normal', 0.00, 'available'),
(1106, 23, 'F', 11, 'normal', 0.00, 'available'),
(1107, 23, 'F', 12, 'normal', 0.00, 'available'),
(1108, 23, 'F', 13, 'normal', 0.00, 'available'),
(1109, 23, 'F', 14, 'normal', 0.00, 'available'),
(1110, 23, 'F', 15, 'normal', 0.00, 'available'),
(1111, 23, 'G', 1, 'normal', 0.00, 'available'),
(1112, 23, 'G', 2, 'normal', 0.00, 'available'),
(1113, 23, 'G', 3, 'normal', 0.00, 'available'),
(1114, 23, 'G', 4, 'normal', 0.00, 'available'),
(1115, 23, 'G', 5, 'normal', 0.00, 'available'),
(1116, 23, 'G', 6, 'normal', 0.00, 'available'),
(1117, 23, 'G', 7, 'normal', 0.00, 'available'),
(1118, 23, 'G', 8, 'normal', 0.00, 'available'),
(1119, 23, 'G', 9, 'normal', 0.00, 'available'),
(1120, 23, 'G', 10, 'normal', 0.00, 'available'),
(1121, 23, 'G', 11, 'normal', 0.00, 'available'),
(1122, 23, 'G', 12, 'normal', 0.00, 'available'),
(1123, 23, 'G', 13, 'normal', 0.00, 'available'),
(1124, 23, 'G', 14, 'normal', 0.00, 'available'),
(1125, 23, 'G', 15, 'normal', 0.00, 'available'),
(1126, 23, 'H', 1, 'normal', 0.00, 'available'),
(1127, 23, 'H', 2, 'normal', 0.00, 'available'),
(1128, 23, 'H', 3, 'normal', 0.00, 'available'),
(1129, 23, 'H', 4, 'normal', 0.00, 'available'),
(1130, 23, 'H', 5, 'normal', 0.00, 'available'),
(1131, 23, 'H', 6, 'normal', 0.00, 'available'),
(1132, 23, 'H', 7, 'normal', 0.00, 'available'),
(1133, 23, 'H', 8, 'normal', 0.00, 'available'),
(1134, 23, 'H', 9, 'normal', 0.00, 'available'),
(1135, 23, 'H', 10, 'normal', 0.00, 'available'),
(1136, 23, 'H', 11, 'normal', 0.00, 'available'),
(1137, 23, 'H', 12, 'normal', 0.00, 'available'),
(1138, 23, 'H', 13, 'normal', 0.00, 'available'),
(1139, 23, 'H', 14, 'normal', 0.00, 'available'),
(1140, 23, 'H', 15, 'normal', 0.00, 'available'),
(1141, 23, 'I', 1, 'normal', 0.00, 'available'),
(1142, 23, 'I', 2, 'normal', 0.00, 'available'),
(1143, 23, 'I', 3, 'normal', 0.00, 'available'),
(1144, 23, 'I', 4, 'normal', 0.00, 'available'),
(1145, 23, 'I', 5, 'normal', 0.00, 'available'),
(1146, 23, 'I', 6, 'normal', 0.00, 'available'),
(1147, 23, 'I', 7, 'normal', 0.00, 'available'),
(1148, 23, 'I', 8, 'normal', 0.00, 'available'),
(1149, 23, 'I', 9, 'normal', 0.00, 'available'),
(1150, 23, 'I', 10, 'normal', 0.00, 'available'),
(1151, 23, 'I', 11, 'normal', 0.00, 'available'),
(1152, 23, 'I', 12, 'normal', 0.00, 'available'),
(1153, 23, 'I', 13, 'normal', 0.00, 'available'),
(1154, 23, 'I', 14, 'normal', 0.00, 'available'),
(1155, 23, 'I', 15, 'normal', 0.00, 'available'),
(1156, 23, 'J', 1, 'normal', 0.00, 'available'),
(1157, 23, 'J', 2, 'normal', 0.00, 'available'),
(1158, 23, 'J', 3, 'normal', 0.00, 'available'),
(1159, 23, 'J', 4, 'normal', 0.00, 'available'),
(1160, 23, 'J', 5, 'normal', 0.00, 'available'),
(1161, 23, 'J', 6, 'normal', 0.00, 'available'),
(1162, 23, 'J', 7, 'normal', 0.00, 'available'),
(1163, 23, 'J', 8, 'normal', 0.00, 'available'),
(1164, 23, 'J', 9, 'normal', 0.00, 'available'),
(1165, 23, 'J', 10, 'normal', 0.00, 'available'),
(1166, 23, 'J', 11, 'normal', 0.00, 'available'),
(1167, 23, 'J', 12, 'normal', 0.00, 'available'),
(1168, 23, 'J', 13, 'normal', 0.00, 'available'),
(1169, 23, 'J', 14, 'normal', 0.00, 'available'),
(1170, 23, 'J', 15, 'normal', 0.00, 'available'),
(1321, 22, 'A', 1, 'normal', 0.00, 'available'),
(1322, 22, 'A', 2, 'normal', 0.00, 'available'),
(1323, 22, 'A', 3, 'normal', 0.00, 'available'),
(1324, 22, 'A', 4, 'normal', 0.00, 'available'),
(1325, 22, 'A', 5, 'normal', 0.00, 'available'),
(1326, 22, 'A', 6, 'normal', 0.00, 'available'),
(1327, 22, 'A', 7, 'normal', 0.00, 'available'),
(1328, 22, 'A', 8, 'normal', 0.00, 'available'),
(1329, 22, 'A', 9, 'normal', 0.00, 'available'),
(1330, 22, 'A', 10, 'normal', 0.00, 'available'),
(1331, 22, 'A', 11, 'normal', 0.00, 'available'),
(1332, 22, 'A', 12, 'normal', 0.00, 'available'),
(1333, 22, 'A', 13, 'normal', 0.00, 'available'),
(1334, 22, 'A', 14, 'normal', 0.00, 'available'),
(1335, 22, 'A', 15, 'normal', 0.00, 'available'),
(1336, 22, 'B', 1, 'normal', 0.00, 'available'),
(1337, 22, 'B', 2, 'normal', 0.00, 'available'),
(1338, 22, 'B', 3, 'normal', 0.00, 'available'),
(1339, 22, 'B', 4, 'normal', 0.00, 'available'),
(1340, 22, 'B', 5, 'normal', 0.00, 'available'),
(1341, 22, 'B', 6, 'normal', 0.00, 'available'),
(1342, 22, 'B', 7, 'normal', 0.00, 'available'),
(1343, 22, 'B', 8, 'normal', 0.00, 'available'),
(1344, 22, 'B', 9, 'normal', 0.00, 'available'),
(1345, 22, 'B', 10, 'normal', 0.00, 'available'),
(1346, 22, 'B', 11, 'normal', 0.00, 'available'),
(1347, 22, 'B', 12, 'normal', 0.00, 'available'),
(1348, 22, 'B', 13, 'normal', 0.00, 'available'),
(1349, 22, 'B', 14, 'normal', 0.00, 'available'),
(1350, 22, 'B', 15, 'normal', 0.00, 'available'),
(1351, 22, 'C', 1, 'normal', 0.00, 'available'),
(1352, 22, 'C', 2, 'normal', 0.00, 'available'),
(1353, 22, 'C', 3, 'normal', 0.00, 'available'),
(1354, 22, 'C', 4, 'normal', 0.00, 'available'),
(1355, 22, 'C', 5, 'normal', 0.00, 'available'),
(1356, 22, 'C', 6, 'normal', 0.00, 'available'),
(1357, 22, 'C', 7, 'normal', 0.00, 'available'),
(1358, 22, 'C', 8, 'normal', 0.00, 'available'),
(1359, 22, 'C', 9, 'normal', 0.00, 'available'),
(1360, 22, 'C', 10, 'normal', 0.00, 'available'),
(1361, 22, 'C', 11, 'normal', 0.00, 'available'),
(1362, 22, 'C', 12, 'normal', 0.00, 'available'),
(1363, 22, 'C', 13, 'normal', 0.00, 'available'),
(1364, 22, 'C', 14, 'normal', 0.00, 'available'),
(1365, 22, 'C', 15, 'normal', 0.00, 'available'),
(1366, 22, 'D', 1, 'normal', 0.00, 'available'),
(1367, 22, 'D', 2, 'normal', 0.00, 'available'),
(1368, 22, 'D', 3, 'normal', 0.00, 'available'),
(1369, 22, 'D', 4, 'normal', 0.00, 'available'),
(1370, 22, 'D', 5, 'normal', 0.00, 'available'),
(1371, 22, 'D', 6, 'normal', 0.00, 'available'),
(1372, 22, 'D', 7, 'normal', 0.00, 'available'),
(1373, 22, 'D', 8, 'normal', 0.00, 'available'),
(1374, 22, 'D', 9, 'normal', 0.00, 'available'),
(1375, 22, 'D', 10, 'normal', 0.00, 'available'),
(1376, 22, 'D', 11, 'normal', 0.00, 'available'),
(1377, 22, 'D', 12, 'normal', 0.00, 'available'),
(1378, 22, 'D', 13, 'normal', 0.00, 'available'),
(1379, 22, 'D', 14, 'normal', 0.00, 'available'),
(1380, 22, 'D', 15, 'normal', 0.00, 'available'),
(1381, 22, 'E', 1, 'normal', 0.00, 'available'),
(1382, 22, 'E', 2, 'normal', 0.00, 'available'),
(1383, 22, 'E', 3, 'normal', 0.00, 'available'),
(1384, 22, 'E', 4, 'normal', 0.00, 'available'),
(1385, 22, 'E', 5, 'normal', 0.00, 'available'),
(1386, 22, 'E', 6, 'normal', 0.00, 'available'),
(1387, 22, 'E', 7, 'normal', 0.00, 'available'),
(1388, 22, 'E', 8, 'normal', 0.00, 'available'),
(1389, 22, 'E', 9, 'normal', 0.00, 'available'),
(1390, 22, 'E', 10, 'normal', 0.00, 'available'),
(1391, 22, 'E', 11, 'normal', 0.00, 'available'),
(1392, 22, 'E', 12, 'normal', 0.00, 'available'),
(1393, 22, 'E', 13, 'normal', 0.00, 'available'),
(1394, 22, 'E', 14, 'normal', 0.00, 'available'),
(1395, 22, 'E', 15, 'normal', 0.00, 'available'),
(1396, 22, 'F', 1, 'normal', 0.00, 'available'),
(1397, 22, 'F', 2, 'normal', 0.00, 'available'),
(1398, 22, 'F', 3, 'normal', 0.00, 'available'),
(1399, 22, 'F', 4, 'normal', 0.00, 'available'),
(1400, 22, 'F', 5, 'normal', 0.00, 'available'),
(1401, 22, 'F', 6, 'normal', 0.00, 'available'),
(1402, 22, 'F', 7, 'normal', 0.00, 'available'),
(1403, 22, 'F', 8, 'normal', 0.00, 'available'),
(1404, 22, 'F', 9, 'normal', 0.00, 'available'),
(1405, 22, 'F', 10, 'normal', 0.00, 'available'),
(1406, 22, 'F', 11, 'normal', 0.00, 'available'),
(1407, 22, 'F', 12, 'normal', 0.00, 'available'),
(1408, 22, 'F', 13, 'normal', 0.00, 'available'),
(1409, 22, 'F', 14, 'normal', 0.00, 'available'),
(1410, 22, 'F', 15, 'normal', 0.00, 'available'),
(1411, 22, 'G', 1, 'normal', 0.00, 'available'),
(1412, 22, 'G', 2, 'normal', 0.00, 'available'),
(1413, 22, 'G', 3, 'normal', 0.00, 'available'),
(1414, 22, 'G', 4, 'normal', 0.00, 'available'),
(1415, 22, 'G', 5, 'normal', 0.00, 'available'),
(1416, 22, 'G', 6, 'normal', 0.00, 'available'),
(1417, 22, 'G', 7, 'normal', 0.00, 'available'),
(1418, 22, 'G', 8, 'normal', 0.00, 'available'),
(1419, 22, 'G', 9, 'normal', 0.00, 'available'),
(1420, 22, 'G', 10, 'normal', 0.00, 'available'),
(1421, 22, 'G', 11, 'normal', 0.00, 'available'),
(1422, 22, 'G', 12, 'normal', 0.00, 'available'),
(1423, 22, 'G', 13, 'normal', 0.00, 'available'),
(1424, 22, 'G', 14, 'normal', 0.00, 'available'),
(1425, 22, 'G', 15, 'normal', 0.00, 'available'),
(1426, 22, 'H', 1, 'normal', 0.00, 'available'),
(1427, 22, 'H', 2, 'normal', 0.00, 'available'),
(1428, 22, 'H', 3, 'normal', 0.00, 'available'),
(1429, 22, 'H', 4, 'normal', 0.00, 'available'),
(1430, 22, 'H', 5, 'normal', 0.00, 'available'),
(1431, 22, 'H', 6, 'normal', 0.00, 'available'),
(1432, 22, 'H', 7, 'normal', 0.00, 'available'),
(1433, 22, 'H', 8, 'normal', 0.00, 'available'),
(1434, 22, 'H', 9, 'normal', 0.00, 'available'),
(1435, 22, 'H', 10, 'normal', 0.00, 'available'),
(1436, 22, 'H', 11, 'normal', 0.00, 'available'),
(1437, 22, 'H', 12, 'normal', 0.00, 'available'),
(1438, 22, 'H', 13, 'normal', 0.00, 'available'),
(1439, 22, 'H', 14, 'normal', 0.00, 'available'),
(1440, 22, 'H', 15, 'normal', 0.00, 'available'),
(1441, 22, 'I', 1, 'normal', 0.00, 'available'),
(1442, 22, 'I', 2, 'normal', 0.00, 'available'),
(1443, 22, 'I', 3, 'normal', 0.00, 'available'),
(1444, 22, 'I', 4, 'normal', 0.00, 'available'),
(1445, 22, 'I', 5, 'normal', 0.00, 'available'),
(1446, 22, 'I', 6, 'normal', 0.00, 'available'),
(1447, 22, 'I', 7, 'normal', 0.00, 'available'),
(1448, 22, 'I', 8, 'normal', 0.00, 'available'),
(1449, 22, 'I', 9, 'normal', 0.00, 'available'),
(1450, 22, 'I', 10, 'normal', 0.00, 'available'),
(1451, 22, 'I', 11, 'normal', 0.00, 'available'),
(1452, 22, 'I', 12, 'normal', 0.00, 'available'),
(1453, 22, 'I', 13, 'normal', 0.00, 'available'),
(1454, 22, 'I', 14, 'normal', 0.00, 'available'),
(1455, 22, 'I', 15, 'normal', 0.00, 'available'),
(1456, 22, 'J', 1, 'normal', 0.00, 'available'),
(1457, 22, 'J', 2, 'normal', 0.00, 'available'),
(1458, 22, 'J', 3, 'normal', 0.00, 'available'),
(1459, 22, 'J', 4, 'normal', 0.00, 'available'),
(1460, 22, 'J', 5, 'normal', 0.00, 'available'),
(1461, 22, 'J', 6, 'normal', 0.00, 'available'),
(1462, 22, 'J', 7, 'normal', 0.00, 'available'),
(1463, 22, 'J', 8, 'normal', 0.00, 'available'),
(1464, 22, 'J', 9, 'normal', 0.00, 'available'),
(1465, 22, 'J', 10, 'normal', 0.00, 'available'),
(1466, 22, 'J', 11, 'normal', 0.00, 'available'),
(1467, 22, 'J', 12, 'normal', 0.00, 'available'),
(1468, 22, 'J', 13, 'normal', 0.00, 'available'),
(1469, 22, 'J', 14, 'normal', 0.00, 'available'),
(1470, 22, 'J', 15, 'normal', 0.00, 'available'),
(3591, 18, 'A', 1, 'normal', 0.00, 'available'),
(3592, 18, 'A', 2, 'normal', 0.00, 'available'),
(3593, 18, 'A', 3, 'normal', 0.00, 'available'),
(3594, 18, 'A', 4, 'normal', 0.00, 'available'),
(3595, 18, 'A', 5, 'normal', 0.00, 'available'),
(3596, 18, 'A', 6, 'normal', 0.00, 'available'),
(3597, 18, 'A', 7, 'normal', 0.00, 'available'),
(3598, 18, 'A', 8, 'normal', 0.00, 'available'),
(3599, 18, 'A', 9, 'normal', 0.00, 'available'),
(3600, 18, 'A', 10, 'normal', 0.00, 'available'),
(3601, 18, 'A', 11, 'normal', 0.00, 'available'),
(3602, 18, 'A', 12, 'normal', 0.00, 'available'),
(3603, 18, 'B', 1, 'normal', 0.00, 'available'),
(3604, 18, 'B', 2, 'normal', 0.00, 'available'),
(3605, 18, 'B', 3, 'normal', 0.00, 'available'),
(3606, 18, 'B', 4, 'normal', 0.00, 'available'),
(3607, 18, 'B', 5, 'normal', 0.00, 'available'),
(3608, 18, 'B', 6, 'normal', 0.00, 'available'),
(3609, 18, 'B', 7, 'normal', 0.00, 'available'),
(3610, 18, 'B', 8, 'normal', 0.00, 'available'),
(3611, 18, 'B', 9, 'normal', 0.00, 'available'),
(3612, 18, 'B', 10, 'normal', 0.00, 'available'),
(3613, 18, 'B', 11, 'normal', 0.00, 'available'),
(3614, 18, 'B', 12, 'normal', 0.00, 'available'),
(3615, 18, 'C', 1, 'vip', 0.00, 'available'),
(3616, 18, 'C', 2, 'vip', 0.00, 'available'),
(3617, 18, 'C', 3, 'vip', 0.00, 'available'),
(3618, 18, 'C', 4, 'vip', 0.00, 'available'),
(3619, 18, 'C', 5, 'vip', 0.00, 'available'),
(3620, 18, 'C', 6, 'vip', 0.00, 'available'),
(3621, 18, 'C', 7, 'vip', 0.00, 'available'),
(3622, 18, 'C', 8, 'vip', 0.00, 'available'),
(3623, 18, 'C', 9, 'vip', 0.00, 'available'),
(3624, 18, 'C', 10, 'vip', 0.00, 'available'),
(3625, 18, 'C', 11, 'vip', 0.00, 'available'),
(3626, 18, 'C', 12, 'vip', 0.00, 'available'),
(3627, 18, 'D', 1, 'vip', 0.00, 'available'),
(3628, 18, 'D', 2, 'vip', 0.00, 'available'),
(3629, 18, 'D', 3, 'vip', 0.00, 'available'),
(3630, 18, 'D', 4, 'vip', 0.00, 'available'),
(3631, 18, 'D', 5, 'vip', 0.00, 'available'),
(3632, 18, 'D', 6, 'vip', 0.00, 'available'),
(3633, 18, 'D', 7, 'vip', 0.00, 'available'),
(3634, 18, 'D', 8, 'vip', 0.00, 'available'),
(3635, 18, 'D', 9, 'vip', 0.00, 'available'),
(3636, 18, 'D', 10, 'vip', 0.00, 'available'),
(3637, 18, 'D', 11, 'normal', 0.00, 'available'),
(3638, 18, 'D', 12, 'normal', 0.00, 'available'),
(3639, 18, 'E', 1, 'vip', 0.00, 'available'),
(3640, 18, 'E', 2, 'vip', 0.00, 'available'),
(3641, 18, 'E', 3, 'vip', 0.00, 'available'),
(3642, 18, 'E', 4, 'vip', 0.00, 'available'),
(3643, 18, 'E', 5, 'vip', 0.00, 'available'),
(3644, 18, 'E', 6, 'vip', 0.00, 'available'),
(3645, 18, 'E', 7, 'normal', 0.00, 'available'),
(3646, 18, 'E', 8, 'normal', 0.00, 'available'),
(3647, 18, 'E', 9, 'normal', 0.00, 'available'),
(3648, 18, 'E', 10, 'normal', 0.00, 'available'),
(3649, 18, 'E', 11, 'normal', 0.00, 'available'),
(3650, 18, 'E', 12, 'normal', 0.00, 'available'),
(3651, 18, 'F', 1, 'normal', 0.00, 'available'),
(3652, 18, 'F', 2, 'normal', 0.00, 'available'),
(3653, 18, 'F', 3, 'normal', 0.00, 'available'),
(3654, 18, 'F', 4, 'normal', 0.00, 'available'),
(3655, 18, 'F', 5, 'normal', 0.00, 'available'),
(3656, 18, 'F', 6, 'normal', 0.00, 'available'),
(3657, 18, 'F', 7, 'normal', 0.00, 'available'),
(3658, 18, 'F', 8, 'normal', 0.00, 'available'),
(3659, 18, 'F', 9, 'normal', 0.00, 'available'),
(3660, 18, 'F', 10, 'normal', 0.00, 'available'),
(3661, 18, 'F', 11, 'normal', 0.00, 'available'),
(3662, 18, 'F', 12, 'normal', 0.00, 'available'),
(3663, 18, 'G', 1, 'normal', 0.00, 'available'),
(3664, 18, 'G', 2, 'normal', 0.00, 'available'),
(3665, 18, 'G', 3, 'normal', 0.00, 'available'),
(3666, 18, 'G', 4, 'normal', 0.00, 'available'),
(3667, 18, 'G', 5, 'normal', 0.00, 'available'),
(3668, 18, 'G', 6, 'normal', 0.00, 'available'),
(3669, 18, 'G', 7, 'normal', 0.00, 'available'),
(3670, 18, 'G', 8, 'normal', 0.00, 'available'),
(3671, 18, 'G', 9, 'normal', 0.00, 'available'),
(3672, 18, 'G', 10, 'normal', 0.00, 'available'),
(3673, 18, 'G', 11, 'normal', 0.00, 'available'),
(3674, 18, 'G', 12, 'normal', 0.00, 'available'),
(3675, 18, 'H', 1, 'normal', 0.00, 'available'),
(3676, 18, 'H', 2, 'normal', 0.00, 'available'),
(3677, 18, 'H', 3, 'normal', 0.00, 'available'),
(3678, 18, 'H', 4, 'normal', 0.00, 'available'),
(3679, 18, 'H', 5, 'normal', 0.00, 'available'),
(3680, 18, 'H', 6, 'normal', 0.00, 'available'),
(3681, 18, 'H', 7, 'normal', 0.00, 'available'),
(3682, 18, 'H', 8, 'normal', 0.00, 'available'),
(3683, 18, 'H', 9, 'normal', 0.00, 'available'),
(3684, 18, 'H', 10, 'normal', 0.00, 'available'),
(3685, 18, 'H', 11, 'normal', 0.00, 'available'),
(3686, 18, 'H', 12, 'normal', 0.00, 'available'),
(3687, 18, 'I', 1, 'normal', 0.00, 'available'),
(3688, 18, 'I', 2, 'normal', 0.00, 'available'),
(3689, 18, 'I', 3, 'normal', 0.00, 'available'),
(3690, 18, 'I', 4, 'normal', 0.00, 'available'),
(3691, 18, 'I', 5, 'normal', 0.00, 'available'),
(3692, 18, 'I', 6, 'normal', 0.00, 'available'),
(3693, 18, 'I', 7, 'normal', 0.00, 'available'),
(3694, 18, 'I', 8, 'normal', 0.00, 'available'),
(3695, 18, 'I', 9, 'normal', 0.00, 'available'),
(3696, 18, 'I', 10, 'normal', 0.00, 'available'),
(3697, 18, 'I', 11, 'normal', 0.00, 'available'),
(3698, 18, 'I', 12, 'normal', 0.00, 'available'),
(3699, 18, 'J', 1, 'normal', 0.00, 'available'),
(3700, 18, 'J', 2, 'normal', 0.00, 'available'),
(3701, 18, 'J', 3, 'normal', 0.00, 'available'),
(3702, 18, 'J', 4, 'normal', 0.00, 'available'),
(3703, 18, 'J', 5, 'normal', 0.00, 'available'),
(3704, 18, 'J', 6, 'normal', 0.00, 'available'),
(3705, 18, 'J', 7, 'normal', 0.00, 'available'),
(3706, 18, 'J', 8, 'normal', 0.00, 'available'),
(3707, 18, 'J', 9, 'normal', 0.00, 'available'),
(3708, 18, 'J', 10, 'normal', 0.00, 'available'),
(3709, 18, 'J', 11, 'normal', 0.00, 'available'),
(3710, 18, 'J', 12, 'normal', 0.00, 'available'),
(3711, 18, 'K', 1, 'normal', 0.00, 'available'),
(3712, 18, 'K', 2, 'normal', 0.00, 'available'),
(3713, 18, 'K', 3, 'normal', 0.00, 'available'),
(3714, 18, 'K', 4, 'normal', 0.00, 'available'),
(3715, 18, 'K', 5, 'normal', 0.00, 'available'),
(3716, 18, 'K', 6, 'normal', 0.00, 'available'),
(3717, 18, 'K', 7, 'normal', 0.00, 'available'),
(3718, 18, 'K', 8, 'normal', 0.00, 'available'),
(3719, 18, 'K', 9, 'normal', 0.00, 'available'),
(3720, 18, 'K', 10, 'normal', 0.00, 'available'),
(3721, 18, 'K', 11, 'normal', 0.00, 'available'),
(3722, 18, 'K', 12, 'normal', 0.00, 'available'),
(3723, 18, 'L', 1, 'normal', 0.00, 'available'),
(3724, 18, 'L', 2, 'normal', 0.00, 'available'),
(3725, 18, 'L', 3, 'normal', 0.00, 'available'),
(3726, 18, 'L', 4, 'normal', 0.00, 'available'),
(3727, 18, 'L', 5, 'normal', 0.00, 'available'),
(3728, 18, 'L', 6, 'normal', 0.00, 'available'),
(3729, 18, 'L', 7, 'normal', 0.00, 'available'),
(3730, 18, 'L', 8, 'normal', 0.00, 'available'),
(3731, 18, 'L', 9, 'normal', 0.00, 'available'),
(3732, 18, 'L', 10, 'normal', 0.00, 'available'),
(3733, 18, 'L', 11, 'normal', 0.00, 'available'),
(3734, 18, 'L', 12, 'normal', 0.00, 'available'),
(3735, 18, 'M', 1, 'normal', 0.00, 'available'),
(3736, 18, 'M', 2, 'normal', 0.00, 'available'),
(3737, 18, 'M', 3, 'normal', 0.00, 'available'),
(3738, 18, 'M', 4, 'normal', 0.00, 'available'),
(3739, 18, 'M', 5, 'normal', 0.00, 'available'),
(3740, 18, 'M', 6, 'normal', 0.00, 'available'),
(3741, 19, 'A', 1, 'normal', 0.00, 'available'),
(3742, 19, 'A', 2, 'normal', 0.00, 'available'),
(3743, 19, 'A', 3, 'normal', 0.00, 'available'),
(3744, 19, 'A', 4, 'normal', 0.00, 'available'),
(3745, 19, 'A', 5, 'normal', 0.00, 'available'),
(3746, 19, 'A', 6, 'normal', 0.00, 'available'),
(3747, 19, 'A', 7, 'normal', 0.00, 'available'),
(3748, 19, 'A', 8, 'normal', 0.00, 'available'),
(3749, 19, 'A', 9, 'normal', 0.00, 'available'),
(3750, 19, 'A', 10, 'normal', 0.00, 'available'),
(3751, 19, 'A', 11, 'normal', 0.00, 'available'),
(3752, 19, 'A', 12, 'normal', 0.00, 'available'),
(3753, 19, 'B', 1, 'normal', 0.00, 'available'),
(3754, 19, 'B', 2, 'normal', 0.00, 'available'),
(3755, 19, 'B', 3, 'normal', 0.00, 'available'),
(3756, 19, 'B', 4, 'normal', 0.00, 'available'),
(3757, 19, 'B', 5, 'normal', 0.00, 'available'),
(3758, 19, 'B', 6, 'normal', 0.00, 'available'),
(3759, 19, 'B', 7, 'normal', 0.00, 'available'),
(3760, 19, 'B', 8, 'normal', 0.00, 'available'),
(3761, 19, 'B', 9, 'normal', 0.00, 'available'),
(3762, 19, 'B', 10, 'normal', 0.00, 'available'),
(3763, 19, 'B', 11, 'normal', 0.00, 'available'),
(3764, 19, 'B', 12, 'normal', 0.00, 'available'),
(3765, 19, 'C', 1, 'normal', 0.00, 'available'),
(3766, 19, 'C', 2, 'normal', 0.00, 'available'),
(3767, 19, 'C', 3, 'normal', 0.00, 'available'),
(3768, 19, 'C', 4, 'normal', 0.00, 'available'),
(3769, 19, 'C', 5, 'normal', 0.00, 'available'),
(3770, 19, 'C', 6, 'normal', 0.00, 'available'),
(3771, 19, 'C', 7, 'normal', 0.00, 'available'),
(3772, 19, 'C', 8, 'normal', 0.00, 'available'),
(3773, 19, 'C', 9, 'normal', 0.00, 'available'),
(3774, 19, 'C', 10, 'normal', 0.00, 'available'),
(3775, 19, 'C', 11, 'normal', 0.00, 'available'),
(3776, 19, 'C', 12, 'normal', 0.00, 'available'),
(3777, 19, 'D', 1, 'normal', 0.00, 'available'),
(3778, 19, 'D', 2, 'normal', 0.00, 'available'),
(3779, 19, 'D', 3, 'normal', 0.00, 'available'),
(3780, 19, 'D', 4, 'normal', 0.00, 'available'),
(3781, 19, 'D', 5, 'normal', 0.00, 'available'),
(3782, 19, 'D', 6, 'normal', 0.00, 'available'),
(3783, 19, 'D', 7, 'normal', 0.00, 'available'),
(3784, 19, 'D', 8, 'normal', 0.00, 'available'),
(3785, 19, 'D', 9, 'normal', 0.00, 'available'),
(3786, 19, 'D', 10, 'normal', 0.00, 'available'),
(3787, 19, 'D', 11, 'normal', 0.00, 'available'),
(3788, 19, 'D', 12, 'normal', 0.00, 'available'),
(3789, 19, 'E', 1, 'normal', 0.00, 'available'),
(3790, 19, 'E', 2, 'normal', 0.00, 'available'),
(3791, 19, 'E', 3, 'normal', 0.00, 'available'),
(3792, 19, 'E', 4, 'normal', 0.00, 'available'),
(3793, 19, 'E', 5, 'normal', 0.00, 'available'),
(3794, 19, 'E', 6, 'normal', 0.00, 'available'),
(3795, 19, 'E', 7, 'normal', 0.00, 'available'),
(3796, 19, 'E', 8, 'normal', 0.00, 'available'),
(3797, 19, 'E', 9, 'normal', 0.00, 'available'),
(3798, 19, 'E', 10, 'normal', 0.00, 'available'),
(3799, 19, 'E', 11, 'normal', 0.00, 'available'),
(3800, 19, 'E', 12, 'normal', 0.00, 'available'),
(3801, 19, 'F', 1, 'normal', 0.00, 'available'),
(3802, 19, 'F', 2, 'normal', 0.00, 'available'),
(3803, 19, 'F', 3, 'normal', 0.00, 'available'),
(3804, 19, 'F', 4, 'normal', 0.00, 'available'),
(3805, 19, 'F', 5, 'normal', 0.00, 'available'),
(3806, 19, 'F', 6, 'normal', 0.00, 'available'),
(3807, 19, 'F', 7, 'normal', 0.00, 'available'),
(3808, 19, 'F', 8, 'normal', 0.00, 'available'),
(3809, 19, 'F', 9, 'normal', 0.00, 'available'),
(3810, 19, 'F', 10, 'normal', 0.00, 'available'),
(3811, 19, 'F', 11, 'normal', 0.00, 'available'),
(3812, 19, 'F', 12, 'normal', 0.00, 'available'),
(3813, 19, 'G', 1, 'normal', 0.00, 'available'),
(3814, 19, 'G', 2, 'normal', 0.00, 'available'),
(3815, 19, 'G', 3, 'normal', 0.00, 'available'),
(3816, 19, 'G', 4, 'normal', 0.00, 'available'),
(3817, 19, 'G', 5, 'normal', 0.00, 'available'),
(3818, 19, 'G', 6, 'normal', 0.00, 'available'),
(3819, 19, 'G', 7, 'normal', 0.00, 'available'),
(3820, 19, 'G', 8, 'normal', 0.00, 'available'),
(3821, 19, 'G', 9, 'normal', 0.00, 'available'),
(3822, 19, 'G', 10, 'normal', 0.00, 'available'),
(3823, 19, 'G', 11, 'normal', 0.00, 'available'),
(3824, 19, 'G', 12, 'normal', 0.00, 'available'),
(3825, 19, 'H', 1, 'normal', 0.00, 'available'),
(3826, 19, 'H', 2, 'normal', 0.00, 'available'),
(3827, 19, 'H', 3, 'normal', 0.00, 'available'),
(3828, 19, 'H', 4, 'normal', 0.00, 'available'),
(3829, 19, 'H', 5, 'normal', 0.00, 'available'),
(3830, 19, 'H', 6, 'normal', 0.00, 'available'),
(3831, 19, 'H', 7, 'normal', 0.00, 'available'),
(3832, 19, 'H', 8, 'normal', 0.00, 'available'),
(3833, 19, 'H', 9, 'normal', 0.00, 'available'),
(3834, 19, 'H', 10, 'normal', 0.00, 'available'),
(3835, 19, 'H', 11, 'normal', 0.00, 'available'),
(3836, 19, 'H', 12, 'normal', 0.00, 'available'),
(3837, 19, 'I', 1, 'normal', 0.00, 'available'),
(3838, 19, 'I', 2, 'normal', 0.00, 'available'),
(3839, 19, 'I', 3, 'normal', 0.00, 'available'),
(3840, 19, 'I', 4, 'normal', 0.00, 'available'),
(3841, 19, 'I', 5, 'normal', 0.00, 'available'),
(3842, 19, 'I', 6, 'normal', 0.00, 'available'),
(3843, 19, 'I', 7, 'normal', 0.00, 'available'),
(3844, 19, 'I', 8, 'normal', 0.00, 'available'),
(3845, 19, 'I', 9, 'normal', 0.00, 'available'),
(3846, 19, 'I', 10, 'normal', 0.00, 'available'),
(3847, 19, 'I', 11, 'normal', 0.00, 'available'),
(3848, 19, 'I', 12, 'normal', 0.00, 'available'),
(3849, 19, 'J', 1, 'normal', 0.00, 'available'),
(3850, 19, 'J', 2, 'normal', 0.00, 'available'),
(3851, 19, 'J', 3, 'normal', 0.00, 'available'),
(3852, 19, 'J', 4, 'normal', 0.00, 'available'),
(3853, 19, 'J', 5, 'normal', 0.00, 'available'),
(3854, 19, 'J', 6, 'normal', 0.00, 'available'),
(3855, 19, 'J', 7, 'normal', 0.00, 'available'),
(3856, 19, 'J', 8, 'normal', 0.00, 'available'),
(3857, 19, 'J', 9, 'normal', 0.00, 'available'),
(3858, 19, 'J', 10, 'normal', 0.00, 'available'),
(3859, 19, 'J', 11, 'normal', 0.00, 'available'),
(3860, 19, 'J', 12, 'normal', 0.00, 'available'),
(3861, 19, 'K', 1, 'normal', 0.00, 'available'),
(3862, 19, 'K', 2, 'normal', 0.00, 'available'),
(3863, 19, 'K', 3, 'normal', 0.00, 'available'),
(3864, 19, 'K', 4, 'normal', 0.00, 'available'),
(3865, 19, 'K', 5, 'normal', 0.00, 'available'),
(3866, 19, 'K', 6, 'normal', 0.00, 'available'),
(3867, 19, 'K', 7, 'normal', 0.00, 'available'),
(3868, 19, 'K', 8, 'normal', 0.00, 'available'),
(3869, 19, 'K', 9, 'normal', 0.00, 'available'),
(3870, 19, 'K', 10, 'normal', 0.00, 'available'),
(3871, 19, 'K', 11, 'normal', 0.00, 'available'),
(3872, 19, 'K', 12, 'normal', 0.00, 'available'),
(3873, 19, 'L', 1, 'normal', 0.00, 'available'),
(3874, 19, 'L', 2, 'normal', 0.00, 'available'),
(3875, 19, 'L', 3, 'normal', 0.00, 'available'),
(3876, 19, 'L', 4, 'normal', 0.00, 'available'),
(3877, 19, 'L', 5, 'normal', 0.00, 'available'),
(3878, 19, 'L', 6, 'normal', 0.00, 'available'),
(3879, 19, 'L', 7, 'normal', 0.00, 'available'),
(3880, 19, 'L', 8, 'normal', 0.00, 'available'),
(3881, 19, 'L', 9, 'normal', 0.00, 'available'),
(3882, 19, 'L', 10, 'normal', 0.00, 'available'),
(3883, 19, 'L', 11, 'normal', 0.00, 'available'),
(3884, 19, 'L', 12, 'normal', 0.00, 'available'),
(3885, 19, 'M', 1, 'normal', 0.00, 'available'),
(3886, 19, 'M', 2, 'normal', 0.00, 'available'),
(3887, 19, 'M', 3, 'normal', 0.00, 'available'),
(3888, 19, 'M', 4, 'normal', 0.00, 'available'),
(3889, 19, 'M', 5, 'normal', 0.00, 'available'),
(3890, 19, 'M', 6, 'normal', 0.00, 'available'),
(3891, 19, 'M', 7, 'normal', 0.00, 'available'),
(3892, 19, 'M', 8, 'normal', 0.00, 'available'),
(3893, 19, 'M', 9, 'normal', 0.00, 'available'),
(3894, 19, 'M', 10, 'normal', 0.00, 'available'),
(3895, 19, 'M', 11, 'normal', 0.00, 'available'),
(3896, 19, 'M', 12, 'normal', 0.00, 'available'),
(3897, 19, 'N', 1, 'normal', 0.00, 'available'),
(3898, 19, 'N', 2, 'normal', 0.00, 'available'),
(3899, 19, 'N', 3, 'normal', 0.00, 'available'),
(3900, 19, 'N', 4, 'normal', 0.00, 'available'),
(3901, 19, 'N', 5, 'normal', 0.00, 'available'),
(3902, 19, 'N', 6, 'normal', 0.00, 'available'),
(3903, 19, 'N', 7, 'normal', 0.00, 'available'),
(3904, 19, 'N', 8, 'normal', 0.00, 'available'),
(3905, 19, 'N', 9, 'normal', 0.00, 'available'),
(3906, 19, 'N', 10, 'normal', 0.00, 'available'),
(3907, 19, 'N', 11, 'normal', 0.00, 'available'),
(3908, 19, 'N', 12, 'normal', 0.00, 'available'),
(3909, 19, 'O', 1, 'normal', 0.00, 'available'),
(3910, 19, 'O', 2, 'normal', 0.00, 'available'),
(3911, 19, 'O', 3, 'normal', 0.00, 'available'),
(3912, 19, 'O', 4, 'normal', 0.00, 'available'),
(3913, 19, 'O', 5, 'normal', 0.00, 'available'),
(3914, 19, 'O', 6, 'normal', 0.00, 'available'),
(3915, 19, 'O', 7, 'normal', 0.00, 'available'),
(3916, 19, 'O', 8, 'normal', 0.00, 'available'),
(3917, 19, 'O', 9, 'normal', 0.00, 'available'),
(3918, 19, 'O', 10, 'normal', 0.00, 'available'),
(3919, 19, 'O', 11, 'normal', 0.00, 'available'),
(3920, 19, 'O', 12, 'normal', 0.00, 'available'),
(3921, 19, 'P', 1, 'normal', 0.00, 'available'),
(3922, 19, 'P', 2, 'normal', 0.00, 'available'),
(3923, 19, 'P', 3, 'normal', 0.00, 'available'),
(3924, 19, 'P', 4, 'normal', 0.00, 'available'),
(3925, 19, 'P', 5, 'normal', 0.00, 'available'),
(3926, 19, 'P', 6, 'normal', 0.00, 'available'),
(3927, 19, 'P', 7, 'normal', 0.00, 'available'),
(3928, 19, 'P', 8, 'normal', 0.00, 'available'),
(3929, 19, 'P', 9, 'normal', 0.00, 'available'),
(3930, 19, 'P', 10, 'normal', 0.00, 'available'),
(3931, 19, 'P', 11, 'normal', 0.00, 'available'),
(3932, 19, 'P', 12, 'normal', 0.00, 'available'),
(3933, 19, 'Q', 1, 'normal', 0.00, 'available'),
(3934, 19, 'Q', 2, 'normal', 0.00, 'available'),
(3935, 19, 'Q', 3, 'normal', 0.00, 'available'),
(3936, 19, 'Q', 4, 'normal', 0.00, 'available'),
(3937, 19, 'Q', 5, 'normal', 0.00, 'available'),
(3938, 19, 'Q', 6, 'normal', 0.00, 'available'),
(3939, 19, 'Q', 7, 'normal', 0.00, 'available'),
(3940, 19, 'Q', 8, 'normal', 0.00, 'available'),
(3941, 21, 'A', 1, 'normal', 0.00, 'available'),
(3942, 21, 'A', 2, 'normal', 0.00, 'available'),
(3943, 21, 'A', 3, 'normal', 0.00, 'available'),
(3944, 21, 'A', 4, 'normal', 0.00, 'available'),
(3945, 21, 'A', 5, 'normal', 0.00, 'available'),
(3946, 21, 'A', 6, 'normal', 0.00, 'available'),
(3947, 21, 'A', 7, 'normal', 0.00, 'available'),
(3948, 21, 'A', 8, 'normal', 0.00, 'available'),
(3949, 21, 'A', 9, 'normal', 0.00, 'available'),
(3950, 21, 'A', 10, 'normal', 0.00, 'available'),
(3951, 21, 'A', 11, 'normal', 0.00, 'available'),
(3952, 21, 'A', 12, 'normal', 0.00, 'available'),
(3953, 21, 'B', 1, 'normal', 0.00, 'available'),
(3954, 21, 'B', 2, 'normal', 0.00, 'available'),
(3955, 21, 'B', 3, 'normal', 0.00, 'available'),
(3956, 21, 'B', 4, 'normal', 0.00, 'available'),
(3957, 21, 'B', 5, 'normal', 0.00, 'available'),
(3958, 21, 'B', 6, 'normal', 0.00, 'available'),
(3959, 21, 'B', 7, 'normal', 0.00, 'available'),
(3960, 21, 'B', 8, 'normal', 0.00, 'available'),
(3961, 21, 'B', 9, 'normal', 0.00, 'available'),
(3962, 21, 'B', 10, 'normal', 0.00, 'available'),
(3963, 21, 'B', 11, 'normal', 0.00, 'available'),
(3964, 21, 'B', 12, 'normal', 0.00, 'available'),
(3965, 21, 'C', 1, 'normal', 0.00, 'available'),
(3966, 21, 'C', 2, 'normal', 0.00, 'available'),
(3967, 21, 'C', 3, 'normal', 0.00, 'available'),
(3968, 21, 'C', 4, 'normal', 0.00, 'available'),
(3969, 21, 'C', 5, 'normal', 0.00, 'available'),
(3970, 21, 'C', 6, 'normal', 0.00, 'available'),
(3971, 21, 'C', 7, 'normal', 0.00, 'available'),
(3972, 21, 'C', 8, 'normal', 0.00, 'available'),
(3973, 21, 'C', 9, 'normal', 0.00, 'available'),
(3974, 21, 'C', 10, 'normal', 0.00, 'available'),
(3975, 21, 'C', 11, 'normal', 0.00, 'available'),
(3976, 21, 'C', 12, 'normal', 0.00, 'available'),
(3977, 21, 'D', 1, 'normal', 0.00, 'available'),
(3978, 21, 'D', 2, 'normal', 0.00, 'available'),
(3979, 21, 'D', 3, 'normal', 0.00, 'available'),
(3980, 21, 'D', 4, 'normal', 0.00, 'available'),
(3981, 21, 'D', 5, 'normal', 0.00, 'available'),
(3982, 21, 'D', 6, 'normal', 0.00, 'available'),
(3983, 21, 'D', 7, 'normal', 0.00, 'available'),
(3984, 21, 'D', 8, 'normal', 0.00, 'available'),
(3985, 21, 'D', 9, 'normal', 0.00, 'available'),
(3986, 21, 'D', 10, 'normal', 0.00, 'available'),
(3987, 21, 'D', 11, 'normal', 0.00, 'available'),
(3988, 21, 'D', 12, 'normal', 0.00, 'available'),
(3989, 21, 'E', 1, 'normal', 0.00, 'available'),
(3990, 21, 'E', 2, 'normal', 0.00, 'available'),
(3991, 21, 'E', 3, 'normal', 0.00, 'available'),
(3992, 21, 'E', 4, 'normal', 0.00, 'available'),
(3993, 21, 'E', 5, 'normal', 0.00, 'available'),
(3994, 21, 'E', 6, 'normal', 0.00, 'available'),
(3995, 21, 'E', 7, 'normal', 0.00, 'available'),
(3996, 21, 'E', 8, 'normal', 0.00, 'available'),
(3997, 21, 'E', 9, 'normal', 0.00, 'available'),
(3998, 21, 'E', 10, 'normal', 0.00, 'available'),
(3999, 21, 'E', 11, 'normal', 0.00, 'available'),
(4000, 21, 'E', 12, 'normal', 0.00, 'available'),
(4001, 21, 'F', 1, 'normal', 0.00, 'available'),
(4002, 21, 'F', 2, 'normal', 0.00, 'available'),
(4003, 21, 'F', 3, 'normal', 0.00, 'available'),
(4004, 21, 'F', 4, 'normal', 0.00, 'available'),
(4005, 21, 'F', 5, 'normal', 0.00, 'available'),
(4006, 21, 'F', 6, 'normal', 0.00, 'available'),
(4007, 21, 'F', 7, 'normal', 0.00, 'available'),
(4008, 21, 'F', 8, 'normal', 0.00, 'available'),
(4009, 21, 'F', 9, 'normal', 0.00, 'available'),
(4010, 21, 'F', 10, 'normal', 0.00, 'available'),
(4011, 21, 'F', 11, 'normal', 0.00, 'available'),
(4012, 21, 'F', 12, 'normal', 0.00, 'available'),
(4013, 21, 'G', 1, 'normal', 0.00, 'available'),
(4014, 21, 'G', 2, 'normal', 0.00, 'available'),
(4015, 21, 'G', 3, 'normal', 0.00, 'available'),
(4016, 21, 'G', 4, 'normal', 0.00, 'available'),
(4017, 21, 'G', 5, 'normal', 0.00, 'available'),
(4018, 21, 'G', 6, 'normal', 0.00, 'available'),
(4019, 21, 'G', 7, 'normal', 0.00, 'available'),
(4020, 21, 'G', 8, 'normal', 0.00, 'available'),
(4021, 21, 'G', 9, 'normal', 0.00, 'available'),
(4022, 21, 'G', 10, 'normal', 0.00, 'available'),
(4023, 21, 'G', 11, 'normal', 0.00, 'available'),
(4024, 21, 'G', 12, 'normal', 0.00, 'available'),
(4025, 21, 'H', 1, 'normal', 0.00, 'available'),
(4026, 21, 'H', 2, 'normal', 0.00, 'available'),
(4027, 21, 'H', 3, 'normal', 0.00, 'available'),
(4028, 21, 'H', 4, 'normal', 0.00, 'available'),
(4029, 21, 'H', 5, 'normal', 0.00, 'available'),
(4030, 21, 'H', 6, 'normal', 0.00, 'available'),
(4031, 21, 'H', 7, 'normal', 0.00, 'available'),
(4032, 21, 'H', 8, 'normal', 0.00, 'available'),
(4033, 21, 'H', 9, 'normal', 0.00, 'available'),
(4034, 21, 'H', 10, 'normal', 0.00, 'available'),
(4035, 21, 'H', 11, 'normal', 0.00, 'available'),
(4036, 21, 'H', 12, 'normal', 0.00, 'available'),
(4037, 21, 'I', 1, 'normal', 0.00, 'available'),
(4038, 21, 'I', 2, 'normal', 0.00, 'available'),
(4039, 21, 'I', 3, 'normal', 0.00, 'available'),
(4040, 21, 'I', 4, 'normal', 0.00, 'available'),
(4041, 21, 'I', 5, 'normal', 0.00, 'available'),
(4042, 21, 'I', 6, 'normal', 0.00, 'available'),
(4043, 21, 'I', 7, 'normal', 0.00, 'available'),
(4044, 21, 'I', 8, 'normal', 0.00, 'available'),
(4045, 21, 'I', 9, 'normal', 0.00, 'available'),
(4046, 21, 'I', 10, 'normal', 0.00, 'available'),
(4047, 21, 'I', 11, 'normal', 0.00, 'available'),
(4048, 21, 'I', 12, 'normal', 0.00, 'available'),
(4049, 21, 'J', 1, 'normal', 0.00, 'available'),
(4050, 21, 'J', 2, 'normal', 0.00, 'available'),
(4051, 21, 'J', 3, 'normal', 0.00, 'available'),
(4052, 21, 'J', 4, 'normal', 0.00, 'available'),
(4053, 21, 'J', 5, 'normal', 0.00, 'available'),
(4054, 21, 'J', 6, 'normal', 0.00, 'available'),
(4055, 21, 'J', 7, 'normal', 0.00, 'available'),
(4056, 21, 'J', 8, 'normal', 0.00, 'available'),
(4057, 21, 'J', 9, 'normal', 0.00, 'available'),
(4058, 21, 'J', 10, 'normal', 0.00, 'available'),
(4059, 21, 'J', 11, 'normal', 0.00, 'available'),
(4060, 21, 'J', 12, 'normal', 0.00, 'available'),
(4061, 21, 'K', 1, 'normal', 0.00, 'available'),
(4062, 21, 'K', 2, 'normal', 0.00, 'available'),
(4063, 21, 'K', 3, 'normal', 0.00, 'available'),
(4064, 21, 'K', 4, 'normal', 0.00, 'available'),
(4065, 21, 'K', 5, 'normal', 0.00, 'available'),
(4066, 21, 'K', 6, 'normal', 0.00, 'available'),
(4067, 21, 'K', 7, 'normal', 0.00, 'available'),
(4068, 21, 'K', 8, 'normal', 0.00, 'available'),
(4069, 21, 'K', 9, 'normal', 0.00, 'available'),
(4070, 21, 'K', 10, 'normal', 0.00, 'available'),
(4071, 24, 'A', 1, 'normal', 0.00, 'available'),
(4072, 24, 'A', 2, 'normal', 0.00, 'available'),
(4073, 24, 'A', 3, 'normal', 0.00, 'available'),
(4074, 24, 'A', 4, 'normal', 0.00, 'available'),
(4075, 24, 'A', 5, 'normal', 0.00, 'available'),
(4076, 24, 'A', 6, 'normal', 0.00, 'available'),
(4077, 24, 'A', 7, 'normal', 0.00, 'available'),
(4078, 24, 'A', 8, 'normal', 0.00, 'available'),
(4079, 24, 'A', 9, 'normal', 0.00, 'available'),
(4080, 24, 'A', 10, 'normal', 0.00, 'available'),
(4081, 24, 'A', 11, 'normal', 0.00, 'available'),
(4082, 24, 'A', 12, 'normal', 0.00, 'available'),
(4083, 24, 'B', 1, 'normal', 0.00, 'available'),
(4084, 24, 'B', 2, 'normal', 0.00, 'available'),
(4085, 24, 'B', 3, 'normal', 0.00, 'available'),
(4086, 24, 'B', 4, 'normal', 0.00, 'available'),
(4087, 24, 'B', 5, 'normal', 0.00, 'available'),
(4088, 24, 'B', 6, 'normal', 0.00, 'available'),
(4089, 24, 'B', 7, 'normal', 0.00, 'available'),
(4090, 24, 'B', 8, 'normal', 0.00, 'available'),
(4091, 24, 'B', 9, 'normal', 0.00, 'available'),
(4092, 24, 'B', 10, 'normal', 0.00, 'available'),
(4093, 24, 'B', 11, 'normal', 0.00, 'available'),
(4094, 24, 'B', 12, 'normal', 0.00, 'available'),
(4095, 24, 'C', 1, 'normal', 0.00, 'available'),
(4096, 24, 'C', 2, 'normal', 0.00, 'available'),
(4097, 24, 'C', 3, 'normal', 0.00, 'available'),
(4098, 24, 'C', 4, 'normal', 0.00, 'available'),
(4099, 24, 'C', 5, 'normal', 0.00, 'available'),
(4100, 24, 'C', 6, 'normal', 0.00, 'available'),
(4101, 24, 'C', 7, 'normal', 0.00, 'available'),
(4102, 24, 'C', 8, 'normal', 0.00, 'available'),
(4103, 24, 'C', 9, 'normal', 0.00, 'available'),
(4104, 24, 'C', 10, 'normal', 0.00, 'available'),
(4105, 24, 'C', 11, 'normal', 0.00, 'available'),
(4106, 24, 'C', 12, 'normal', 0.00, 'available'),
(4107, 24, 'D', 1, 'normal', 0.00, 'available'),
(4108, 24, 'D', 2, 'normal', 0.00, 'available'),
(4109, 24, 'D', 3, 'normal', 0.00, 'available'),
(4110, 24, 'D', 4, 'normal', 0.00, 'available'),
(4111, 24, 'D', 5, 'normal', 0.00, 'available'),
(4112, 24, 'D', 6, 'normal', 0.00, 'available'),
(4113, 24, 'D', 7, 'normal', 0.00, 'available'),
(4114, 24, 'D', 8, 'normal', 0.00, 'available'),
(4115, 24, 'D', 9, 'normal', 0.00, 'available'),
(4116, 24, 'D', 10, 'normal', 0.00, 'available'),
(4117, 24, 'D', 11, 'normal', 0.00, 'available'),
(4118, 24, 'D', 12, 'normal', 0.00, 'available'),
(4119, 24, 'E', 1, 'normal', 0.00, 'available'),
(4120, 24, 'E', 2, 'normal', 0.00, 'available'),
(4121, 24, 'E', 3, 'normal', 0.00, 'available'),
(4122, 24, 'E', 4, 'normal', 0.00, 'available'),
(4123, 24, 'E', 5, 'normal', 0.00, 'available'),
(4124, 24, 'E', 6, 'normal', 0.00, 'available'),
(4125, 24, 'E', 7, 'normal', 0.00, 'available'),
(4126, 24, 'E', 8, 'normal', 0.00, 'available'),
(4127, 24, 'E', 9, 'normal', 0.00, 'available'),
(4128, 24, 'E', 10, 'normal', 0.00, 'available'),
(4129, 24, 'E', 11, 'normal', 0.00, 'available'),
(4130, 24, 'E', 12, 'normal', 0.00, 'available'),
(4131, 24, 'F', 1, 'normal', 0.00, 'available'),
(4132, 24, 'F', 2, 'normal', 0.00, 'available'),
(4133, 24, 'F', 3, 'normal', 0.00, 'available'),
(4134, 24, 'F', 4, 'normal', 0.00, 'available'),
(4135, 24, 'F', 5, 'normal', 0.00, 'available'),
(4136, 24, 'F', 6, 'normal', 0.00, 'available'),
(4137, 24, 'F', 7, 'normal', 0.00, 'available'),
(4138, 24, 'F', 8, 'normal', 0.00, 'available'),
(4139, 24, 'F', 9, 'normal', 0.00, 'available'),
(4140, 24, 'F', 10, 'normal', 0.00, 'available'),
(4141, 24, 'F', 11, 'normal', 0.00, 'available'),
(4142, 24, 'F', 12, 'normal', 0.00, 'available'),
(4143, 24, 'G', 1, 'normal', 0.00, 'available'),
(4144, 24, 'G', 2, 'normal', 0.00, 'available'),
(4145, 24, 'G', 3, 'normal', 0.00, 'available'),
(4146, 24, 'G', 4, 'normal', 0.00, 'available'),
(4147, 24, 'G', 5, 'normal', 0.00, 'available'),
(4148, 24, 'G', 6, 'normal', 0.00, 'available'),
(4149, 24, 'G', 7, 'normal', 0.00, 'available'),
(4150, 24, 'G', 8, 'normal', 0.00, 'available'),
(4151, 24, 'G', 9, 'normal', 0.00, 'available'),
(4152, 24, 'G', 10, 'normal', 0.00, 'available'),
(4153, 24, 'G', 11, 'normal', 0.00, 'available'),
(4154, 24, 'G', 12, 'normal', 0.00, 'available'),
(4155, 24, 'H', 1, 'normal', 0.00, 'available'),
(4156, 24, 'H', 2, 'normal', 0.00, 'available'),
(4157, 24, 'H', 3, 'normal', 0.00, 'available'),
(4158, 24, 'H', 4, 'normal', 0.00, 'available'),
(4159, 24, 'H', 5, 'normal', 0.00, 'available'),
(4160, 24, 'H', 6, 'normal', 0.00, 'available'),
(4161, 24, 'H', 7, 'normal', 0.00, 'available'),
(4162, 24, 'H', 8, 'normal', 0.00, 'available'),
(4163, 24, 'H', 9, 'normal', 0.00, 'available'),
(4164, 24, 'H', 10, 'normal', 0.00, 'available'),
(4165, 24, 'H', 11, 'normal', 0.00, 'available'),
(4166, 24, 'H', 12, 'normal', 0.00, 'available'),
(4167, 24, 'I', 1, 'normal', 0.00, 'available'),
(4168, 24, 'I', 2, 'normal', 0.00, 'available'),
(4169, 24, 'I', 3, 'normal', 0.00, 'available'),
(4170, 24, 'I', 4, 'normal', 0.00, 'available'),
(4171, 24, 'I', 5, 'normal', 0.00, 'available'),
(4172, 24, 'I', 6, 'normal', 0.00, 'available'),
(4173, 24, 'I', 7, 'normal', 0.00, 'available'),
(4174, 24, 'I', 8, 'normal', 0.00, 'available'),
(4175, 24, 'I', 9, 'normal', 0.00, 'available'),
(4176, 24, 'I', 10, 'normal', 0.00, 'available'),
(4177, 24, 'I', 11, 'normal', 0.00, 'available'),
(4178, 24, 'I', 12, 'normal', 0.00, 'available'),
(4179, 24, 'J', 1, 'normal', 0.00, 'available'),
(4180, 24, 'J', 2, 'normal', 0.00, 'available'),
(4181, 24, 'J', 3, 'normal', 0.00, 'available'),
(4182, 24, 'J', 4, 'normal', 0.00, 'available'),
(4183, 24, 'J', 5, 'normal', 0.00, 'available'),
(4184, 24, 'J', 6, 'normal', 0.00, 'available'),
(4185, 24, 'J', 7, 'normal', 0.00, 'available'),
(4186, 24, 'J', 8, 'normal', 0.00, 'available'),
(4187, 24, 'J', 9, 'normal', 0.00, 'available'),
(4188, 24, 'J', 10, 'normal', 0.00, 'available'),
(4189, 24, 'J', 11, 'normal', 0.00, 'available'),
(4190, 24, 'J', 12, 'normal', 0.00, 'available'),
(4191, 24, 'K', 1, 'normal', 0.00, 'available'),
(4192, 24, 'K', 2, 'normal', 0.00, 'available'),
(4193, 24, 'K', 3, 'normal', 0.00, 'available'),
(4194, 24, 'K', 4, 'normal', 0.00, 'available'),
(4195, 24, 'K', 5, 'normal', 0.00, 'available'),
(4196, 24, 'K', 6, 'normal', 0.00, 'available'),
(4197, 24, 'K', 7, 'normal', 0.00, 'available'),
(4198, 24, 'K', 8, 'normal', 0.00, 'available'),
(4199, 24, 'K', 9, 'normal', 0.00, 'available'),
(4200, 24, 'K', 10, 'normal', 0.00, 'available'),
(4201, 24, 'K', 11, 'normal', 0.00, 'available'),
(4202, 24, 'K', 12, 'normal', 0.00, 'available'),
(4203, 24, 'L', 1, 'normal', 0.00, 'available'),
(4204, 24, 'L', 2, 'normal', 0.00, 'available'),
(4205, 24, 'L', 3, 'normal', 0.00, 'available'),
(4206, 24, 'L', 4, 'normal', 0.00, 'available'),
(4207, 24, 'L', 5, 'normal', 0.00, 'available'),
(4208, 24, 'L', 6, 'normal', 0.00, 'available'),
(4209, 24, 'L', 7, 'normal', 0.00, 'available'),
(4210, 24, 'L', 8, 'normal', 0.00, 'available'),
(4211, 24, 'L', 9, 'normal', 0.00, 'available'),
(4212, 24, 'L', 10, 'normal', 0.00, 'available'),
(4213, 24, 'L', 11, 'normal', 0.00, 'available'),
(4214, 24, 'L', 12, 'normal', 0.00, 'available'),
(4215, 24, 'M', 1, 'normal', 0.00, 'available'),
(4216, 24, 'M', 2, 'normal', 0.00, 'available'),
(4217, 24, 'M', 3, 'normal', 0.00, 'available'),
(4218, 24, 'M', 4, 'normal', 0.00, 'available'),
(4219, 24, 'M', 5, 'normal', 0.00, 'available'),
(4220, 24, 'M', 6, 'normal', 0.00, 'available'),
(4221, 24, 'M', 7, 'normal', 0.00, 'available'),
(4222, 24, 'M', 8, 'normal', 0.00, 'available'),
(4223, 24, 'M', 9, 'normal', 0.00, 'available'),
(4224, 24, 'M', 10, 'normal', 0.00, 'available'),
(4225, 24, 'M', 11, 'normal', 0.00, 'available'),
(4226, 24, 'M', 12, 'normal', 0.00, 'available'),
(4227, 24, 'N', 1, 'normal', 0.00, 'available'),
(4228, 24, 'N', 2, 'normal', 0.00, 'available'),
(4229, 24, 'N', 3, 'normal', 0.00, 'available'),
(4230, 24, 'N', 4, 'normal', 0.00, 'available'),
(4231, 24, 'N', 5, 'normal', 0.00, 'available'),
(4232, 24, 'N', 6, 'normal', 0.00, 'available'),
(4233, 24, 'N', 7, 'normal', 0.00, 'available'),
(4234, 24, 'N', 8, 'normal', 0.00, 'available'),
(4235, 24, 'N', 9, 'normal', 0.00, 'available'),
(4236, 24, 'N', 10, 'normal', 0.00, 'available'),
(4237, 24, 'N', 11, 'normal', 0.00, 'available'),
(4238, 24, 'N', 12, 'normal', 0.00, 'available'),
(4239, 24, 'O', 1, 'normal', 0.00, 'available'),
(4240, 24, 'O', 2, 'normal', 0.00, 'available');
INSERT INTO `seats` (`id`, `room_id`, `row_label`, `seat_number`, `seat_type`, `extra_price`, `status`) VALUES
(4241, 25, 'A', 1, 'normal', 0.00, 'available'),
(4242, 25, 'A', 2, 'normal', 0.00, 'available'),
(4243, 25, 'A', 3, 'normal', 0.00, 'available'),
(4244, 25, 'A', 4, 'normal', 0.00, 'available'),
(4245, 25, 'A', 5, 'normal', 0.00, 'available'),
(4246, 25, 'A', 6, 'normal', 0.00, 'available'),
(4247, 25, 'A', 7, 'normal', 0.00, 'available'),
(4248, 25, 'A', 8, 'normal', 0.00, 'available'),
(4249, 25, 'A', 9, 'normal', 0.00, 'available'),
(4250, 25, 'A', 10, 'normal', 0.00, 'available'),
(4251, 25, 'A', 11, 'normal', 0.00, 'available'),
(4252, 25, 'A', 12, 'normal', 0.00, 'available'),
(4253, 25, 'B', 1, 'normal', 0.00, 'available'),
(4254, 25, 'B', 2, 'normal', 0.00, 'available'),
(4255, 25, 'B', 3, 'normal', 0.00, 'available'),
(4256, 25, 'B', 4, 'normal', 0.00, 'available'),
(4257, 25, 'B', 5, 'normal', 0.00, 'available'),
(4258, 25, 'B', 6, 'normal', 0.00, 'available'),
(4259, 25, 'B', 7, 'normal', 0.00, 'available'),
(4260, 25, 'B', 8, 'normal', 0.00, 'available'),
(4261, 25, 'B', 9, 'normal', 0.00, 'available'),
(4262, 25, 'B', 10, 'normal', 0.00, 'available'),
(4263, 25, 'B', 11, 'normal', 0.00, 'available'),
(4264, 25, 'B', 12, 'normal', 0.00, 'available'),
(4265, 25, 'C', 1, 'normal', 0.00, 'available'),
(4266, 25, 'C', 2, 'normal', 0.00, 'available'),
(4267, 25, 'C', 3, 'normal', 0.00, 'available'),
(4268, 25, 'C', 4, 'normal', 0.00, 'available'),
(4269, 25, 'C', 5, 'normal', 0.00, 'available'),
(4270, 25, 'C', 6, 'normal', 0.00, 'available'),
(4271, 25, 'C', 7, 'normal', 0.00, 'available'),
(4272, 25, 'C', 8, 'normal', 0.00, 'available'),
(4273, 25, 'C', 9, 'normal', 0.00, 'available'),
(4274, 25, 'C', 10, 'normal', 0.00, 'available'),
(4275, 25, 'C', 11, 'normal', 0.00, 'available'),
(4276, 25, 'C', 12, 'normal', 0.00, 'available'),
(4277, 25, 'D', 1, 'normal', 0.00, 'available'),
(4278, 25, 'D', 2, 'normal', 0.00, 'available'),
(4279, 25, 'D', 3, 'normal', 0.00, 'available'),
(4280, 25, 'D', 4, 'normal', 0.00, 'available'),
(4281, 25, 'D', 5, 'normal', 0.00, 'available'),
(4282, 25, 'D', 6, 'normal', 0.00, 'available'),
(4283, 25, 'D', 7, 'normal', 0.00, 'available'),
(4284, 25, 'D', 8, 'normal', 0.00, 'available'),
(4285, 25, 'D', 9, 'normal', 0.00, 'available'),
(4286, 25, 'D', 10, 'normal', 0.00, 'available'),
(4287, 25, 'D', 11, 'normal', 0.00, 'available'),
(4288, 25, 'D', 12, 'normal', 0.00, 'available'),
(4289, 25, 'E', 1, 'normal', 0.00, 'available'),
(4290, 25, 'E', 2, 'normal', 0.00, 'available'),
(4291, 25, 'E', 3, 'normal', 0.00, 'available'),
(4292, 25, 'E', 4, 'normal', 0.00, 'available'),
(4293, 25, 'E', 5, 'normal', 0.00, 'available'),
(4294, 25, 'E', 6, 'normal', 0.00, 'available'),
(4295, 25, 'E', 7, 'normal', 0.00, 'available'),
(4296, 25, 'E', 8, 'normal', 0.00, 'available'),
(4297, 25, 'E', 9, 'normal', 0.00, 'available'),
(4298, 25, 'E', 10, 'normal', 0.00, 'available'),
(4299, 25, 'E', 11, 'normal', 0.00, 'available'),
(4300, 25, 'E', 12, 'normal', 0.00, 'available'),
(4301, 25, 'F', 1, 'normal', 0.00, 'available'),
(4302, 25, 'F', 2, 'normal', 0.00, 'available'),
(4303, 25, 'F', 3, 'normal', 0.00, 'available'),
(4304, 25, 'F', 4, 'normal', 0.00, 'available'),
(4305, 25, 'F', 5, 'normal', 0.00, 'available'),
(4306, 25, 'F', 6, 'normal', 0.00, 'available'),
(4307, 25, 'F', 7, 'normal', 0.00, 'available'),
(4308, 25, 'F', 8, 'normal', 0.00, 'available'),
(4309, 25, 'F', 9, 'normal', 0.00, 'available'),
(4310, 25, 'F', 10, 'normal', 0.00, 'available'),
(4311, 25, 'F', 11, 'normal', 0.00, 'available'),
(4312, 25, 'F', 12, 'normal', 0.00, 'available'),
(4313, 25, 'G', 1, 'normal', 0.00, 'available'),
(4314, 25, 'G', 2, 'normal', 0.00, 'available'),
(4315, 25, 'G', 3, 'normal', 0.00, 'available'),
(4316, 25, 'G', 4, 'normal', 0.00, 'available'),
(4317, 25, 'G', 5, 'normal', 0.00, 'available'),
(4318, 25, 'G', 6, 'normal', 0.00, 'available'),
(4319, 25, 'G', 7, 'normal', 0.00, 'available'),
(4320, 25, 'G', 8, 'normal', 0.00, 'available'),
(4321, 25, 'G', 9, 'normal', 0.00, 'available'),
(4322, 25, 'G', 10, 'normal', 0.00, 'available'),
(4323, 25, 'G', 11, 'normal', 0.00, 'available'),
(4324, 25, 'G', 12, 'normal', 0.00, 'available'),
(4325, 25, 'H', 1, 'normal', 0.00, 'available'),
(4326, 25, 'H', 2, 'normal', 0.00, 'available'),
(4327, 25, 'H', 3, 'normal', 0.00, 'available'),
(4328, 25, 'H', 4, 'normal', 0.00, 'available'),
(4329, 25, 'H', 5, 'normal', 0.00, 'available'),
(4330, 25, 'H', 6, 'normal', 0.00, 'available'),
(4331, 25, 'H', 7, 'normal', 0.00, 'available'),
(4332, 25, 'H', 8, 'normal', 0.00, 'available'),
(4333, 25, 'H', 9, 'normal', 0.00, 'available'),
(4334, 25, 'H', 10, 'normal', 0.00, 'available'),
(4335, 25, 'H', 11, 'normal', 0.00, 'available'),
(4336, 25, 'H', 12, 'normal', 0.00, 'available'),
(4337, 25, 'I', 1, 'normal', 0.00, 'available'),
(4338, 25, 'I', 2, 'normal', 0.00, 'available'),
(4339, 25, 'I', 3, 'normal', 0.00, 'available'),
(4340, 25, 'I', 4, 'normal', 0.00, 'available'),
(4341, 25, 'I', 5, 'normal', 0.00, 'available'),
(4342, 25, 'I', 6, 'normal', 0.00, 'available'),
(4343, 25, 'I', 7, 'normal', 0.00, 'available'),
(4344, 25, 'I', 8, 'normal', 0.00, 'available'),
(4345, 25, 'I', 9, 'normal', 0.00, 'available'),
(4346, 25, 'I', 10, 'normal', 0.00, 'available'),
(4347, 25, 'I', 11, 'normal', 0.00, 'available'),
(4348, 25, 'I', 12, 'normal', 0.00, 'available'),
(4349, 25, 'J', 1, 'normal', 0.00, 'available'),
(4350, 25, 'J', 2, 'normal', 0.00, 'available'),
(4351, 25, 'J', 3, 'normal', 0.00, 'available'),
(4352, 25, 'J', 4, 'normal', 0.00, 'available'),
(4353, 25, 'J', 5, 'normal', 0.00, 'available'),
(4354, 25, 'J', 6, 'normal', 0.00, 'available'),
(4355, 25, 'J', 7, 'normal', 0.00, 'available'),
(4356, 25, 'J', 8, 'normal', 0.00, 'available'),
(4357, 25, 'J', 9, 'normal', 0.00, 'available'),
(4358, 25, 'J', 10, 'normal', 0.00, 'available'),
(4359, 25, 'J', 11, 'normal', 0.00, 'available'),
(4360, 25, 'J', 12, 'normal', 0.00, 'available');

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
(1, 6, 17, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(2, 6, 18, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(3, 6, 19, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(4, 6, 17, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(5, 6, 18, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(6, 6, 19, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(7, 6, 17, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(8, 6, 18, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(9, 6, 19, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(10, 6, 17, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(11, 6, 18, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(12, 6, 19, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(13, 7, 17, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(14, 7, 18, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(15, 7, 19, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(16, 7, 17, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(17, 7, 18, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(18, 7, 19, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(19, 7, 17, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(20, 7, 18, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(21, 7, 19, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(22, 7, 17, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(23, 7, 18, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(24, 7, 19, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(25, 7, 21, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(26, 7, 22, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(27, 7, 25, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(28, 7, 21, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(29, 7, 22, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(30, 7, 25, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(31, 7, 21, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(32, 7, 22, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(33, 7, 25, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(34, 7, 21, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(35, 7, 22, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(36, 7, 25, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(37, 7, 23, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(38, 7, 24, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(39, 7, 23, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(40, 7, 24, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(41, 7, 23, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(42, 7, 24, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(43, 7, 23, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(44, 7, 24, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(45, 7, 23, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(46, 7, 24, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(47, 7, 23, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(48, 7, 24, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(49, 8, 17, '2025-12-15', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(50, 8, 18, '2025-12-15', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(51, 8, 19, '2025-12-15', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(52, 8, 17, '2025-12-16', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(53, 8, 18, '2025-12-16', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(54, 8, 19, '2025-12-16', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(55, 8, 17, '2025-12-17', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(56, 8, 18, '2025-12-17', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(57, 8, 19, '2025-12-17', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(58, 8, 23, '2025-12-15', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(59, 8, 24, '2025-12-15', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(60, 8, 23, '2025-12-15', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(61, 8, 24, '2025-12-16', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(62, 8, 23, '2025-12-16', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(63, 8, 24, '2025-12-16', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(64, 8, 23, '2025-12-17', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(65, 8, 24, '2025-12-17', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(66, 8, 23, '2025-12-17', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(67, 9, 21, '2025-12-15', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(68, 9, 22, '2025-12-15', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(69, 9, 25, '2025-12-15', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(70, 9, 21, '2025-12-16', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(71, 9, 22, '2025-12-16', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(72, 9, 25, '2025-12-16', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(73, 9, 21, '2025-12-17', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(74, 9, 22, '2025-12-17', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(75, 9, 25, '2025-12-17', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(76, 9, 21, '2025-12-18', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(77, 9, 22, '2025-12-18', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(78, 9, 25, '2025-12-18', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(79, 9, 23, '2025-12-15', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(80, 9, 24, '2025-12-15', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(81, 9, 23, '2025-12-15', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(82, 9, 24, '2025-12-16', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(83, 9, 23, '2025-12-16', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(84, 9, 24, '2025-12-16', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(85, 9, 23, '2025-12-17', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(86, 9, 24, '2025-12-17', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(87, 9, 23, '2025-12-17', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(88, 9, 24, '2025-12-18', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(89, 9, 23, '2025-12-18', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(90, 9, 24, '2025-12-18', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(91, 10, 23, '2025-12-15', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(92, 10, 24, '2025-12-15', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(93, 10, 23, '2025-12-15', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(94, 10, 24, '2025-12-16', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(95, 10, 23, '2025-12-16', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(96, 10, 24, '2025-12-16', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(97, 10, 23, '2025-12-17', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(98, 10, 24, '2025-12-17', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(99, 10, 23, '2025-12-17', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(100, 10, 24, '2025-12-18', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(101, 10, 23, '2025-12-18', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(102, 10, 24, '2025-12-18', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(103, 11, 17, '2025-12-15', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(104, 11, 18, '2025-12-15', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(105, 11, 19, '2025-12-15', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(106, 11, 17, '2025-12-16', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(107, 11, 18, '2025-12-16', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(108, 11, 19, '2025-12-16', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(109, 11, 17, '2025-12-17', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(110, 11, 18, '2025-12-17', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(111, 11, 19, '2025-12-17', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(112, 11, 17, '2025-12-18', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(113, 11, 18, '2025-12-18', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(114, 11, 19, '2025-12-18', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(115, 11, 21, '2025-12-15', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(116, 11, 22, '2025-12-15', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(117, 11, 25, '2025-12-15', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(118, 11, 21, '2025-12-16', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(119, 11, 22, '2025-12-16', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(120, 11, 25, '2025-12-16', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(121, 11, 21, '2025-12-17', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(122, 11, 22, '2025-12-17', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(123, 11, 25, '2025-12-17', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(124, 11, 21, '2025-12-18', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(125, 11, 22, '2025-12-18', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(126, 11, 25, '2025-12-18', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(127, 11, 23, '2025-12-15', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(128, 11, 24, '2025-12-15', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(129, 11, 23, '2025-12-15', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(130, 11, 24, '2025-12-16', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(131, 11, 23, '2025-12-16', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(132, 11, 24, '2025-12-16', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(133, 11, 23, '2025-12-17', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(134, 11, 24, '2025-12-17', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(135, 11, 23, '2025-12-17', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(136, 11, 24, '2025-12-18', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(137, 11, 23, '2025-12-18', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(138, 11, 24, '2025-12-18', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(139, 12, 21, '2025-12-16', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(140, 12, 22, '2025-12-16', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(141, 12, 25, '2025-12-16', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(142, 12, 21, '2025-12-17', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(143, 12, 22, '2025-12-17', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(144, 12, 25, '2025-12-17', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(145, 12, 21, '2025-12-18', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(146, 12, 22, '2025-12-18', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(147, 12, 25, '2025-12-18', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(148, 15, 17, '2025-12-16', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(149, 15, 18, '2025-12-16', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(150, 15, 19, '2025-12-16', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(151, 15, 17, '2025-12-17', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(152, 15, 18, '2025-12-17', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(153, 15, 19, '2025-12-17', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(154, 15, 17, '2025-12-18', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(155, 15, 18, '2025-12-18', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(156, 15, 19, '2025-12-18', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(157, 16, 17, '2025-12-16', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(158, 16, 18, '2025-12-16', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(159, 16, 19, '2025-12-16', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(160, 16, 17, '2025-12-17', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(161, 16, 18, '2025-12-17', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(162, 16, 19, '2025-12-17', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(163, 16, 17, '2025-12-18', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(164, 16, 18, '2025-12-18', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(165, 16, 19, '2025-12-18', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(166, 17, 21, '2025-12-16', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(167, 17, 22, '2025-12-16', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(168, 17, 25, '2025-12-16', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(169, 17, 21, '2025-12-17', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(170, 17, 22, '2025-12-17', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(171, 17, 25, '2025-12-17', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(172, 17, 21, '2025-12-18', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(173, 17, 22, '2025-12-18', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(174, 17, 25, '2025-12-18', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(175, 6, 17, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(176, 6, 18, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(177, 6, 19, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(178, 6, 17, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(179, 6, 18, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(180, 6, 19, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(181, 6, 17, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(182, 6, 18, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(183, 6, 19, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(184, 6, 17, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(185, 6, 18, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(186, 6, 19, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(187, 7, 17, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(188, 7, 18, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(189, 7, 19, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(190, 7, 17, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(191, 7, 18, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(192, 7, 19, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(193, 7, 17, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(194, 7, 18, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(195, 7, 19, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(196, 7, 17, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(197, 7, 18, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(198, 7, 19, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(199, 7, 21, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(200, 7, 22, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(201, 7, 25, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(202, 7, 21, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(203, 7, 22, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(204, 7, 25, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(205, 7, 21, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(206, 7, 22, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(207, 7, 25, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(208, 7, 21, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(209, 7, 22, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(210, 7, 25, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(211, 7, 23, '2025-12-11', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(212, 7, 24, '2025-12-11', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(213, 7, 23, '2025-12-11', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(214, 7, 24, '2025-12-12', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(215, 7, 23, '2025-12-12', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(216, 7, 24, '2025-12-12', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(217, 7, 23, '2025-12-13', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(218, 7, 24, '2025-12-13', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(219, 7, 23, '2025-12-13', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(220, 7, 24, '2025-12-14', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(221, 7, 23, '2025-12-14', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(222, 7, 24, '2025-12-14', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(223, 8, 17, '2025-12-15', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(224, 8, 18, '2025-12-15', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(225, 8, 19, '2025-12-15', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(226, 8, 17, '2025-12-16', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(227, 8, 18, '2025-12-16', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(228, 8, 19, '2025-12-16', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(229, 8, 17, '2025-12-17', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(230, 8, 18, '2025-12-17', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(231, 8, 19, '2025-12-17', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(232, 8, 23, '2025-12-15', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(233, 8, 24, '2025-12-15', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(234, 8, 23, '2025-12-15', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(235, 8, 24, '2025-12-16', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(236, 8, 23, '2025-12-16', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(237, 8, 24, '2025-12-16', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(238, 8, 23, '2025-12-17', '09:00:00', '11:02:00', 70000.00, 60000.00, '2D'),
(239, 8, 24, '2025-12-17', '13:00:00', '15:02:00', 70000.00, 60000.00, '2D'),
(240, 8, 23, '2025-12-17', '19:00:00', '21:02:00', 70000.00, 60000.00, '2D'),
(241, 9, 21, '2025-12-15', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(242, 9, 22, '2025-12-15', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(243, 9, 25, '2025-12-15', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(244, 9, 21, '2025-12-16', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(245, 9, 22, '2025-12-16', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(246, 9, 25, '2025-12-16', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(247, 9, 21, '2025-12-17', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(248, 9, 22, '2025-12-17', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(249, 9, 25, '2025-12-17', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(250, 9, 21, '2025-12-18', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(251, 9, 22, '2025-12-18', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(252, 9, 25, '2025-12-18', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(253, 9, 23, '2025-12-15', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(254, 9, 24, '2025-12-15', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(255, 9, 23, '2025-12-15', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(256, 9, 24, '2025-12-16', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(257, 9, 23, '2025-12-16', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(258, 9, 24, '2025-12-16', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(259, 9, 23, '2025-12-17', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(260, 9, 24, '2025-12-17', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(261, 9, 23, '2025-12-17', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(262, 9, 24, '2025-12-18', '09:00:00', '11:13:00', 70000.00, 60000.00, '2D'),
(263, 9, 23, '2025-12-18', '13:00:00', '15:13:00', 70000.00, 60000.00, '2D'),
(264, 9, 24, '2025-12-18', '19:00:00', '21:13:00', 70000.00, 60000.00, '2D'),
(265, 10, 23, '2025-12-18', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(266, 10, 24, '2025-12-18', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(267, 10, 23, '2025-12-18', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(268, 10, 24, '2025-12-19', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(269, 10, 23, '2025-12-19', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(270, 10, 24, '2025-12-19', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(271, 10, 23, '2025-12-20', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(272, 10, 24, '2025-12-20', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(273, 10, 23, '2025-12-20', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(274, 10, 24, '2025-12-21', '09:00:00', '11:00:00', 85000.00, 70000.00, '3D'),
(275, 10, 23, '2025-12-21', '13:00:00', '15:00:00', 85000.00, 70000.00, '3D'),
(276, 10, 24, '2025-12-21', '19:00:00', '21:00:00', 85000.00, 70000.00, '3D'),
(277, 11, 17, '2025-12-19', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(278, 11, 18, '2025-12-19', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(279, 11, 19, '2025-12-19', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(280, 11, 17, '2025-12-20', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(281, 11, 18, '2025-12-20', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(282, 11, 19, '2025-12-20', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(283, 11, 17, '2025-12-21', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(284, 11, 18, '2025-12-21', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(285, 11, 19, '2025-12-21', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(286, 11, 17, '2025-12-22', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(287, 11, 18, '2025-12-22', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(288, 11, 19, '2025-12-22', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(289, 11, 21, '2025-12-19', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(290, 11, 22, '2025-12-19', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(291, 11, 25, '2025-12-19', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(292, 11, 21, '2025-12-20', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(293, 11, 22, '2025-12-20', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(294, 11, 25, '2025-12-20', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(295, 11, 21, '2025-12-21', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(296, 11, 22, '2025-12-21', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(297, 11, 25, '2025-12-21', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(298, 11, 21, '2025-12-22', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(299, 11, 22, '2025-12-22', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(300, 11, 25, '2025-12-22', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(301, 11, 23, '2025-12-19', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(302, 11, 24, '2025-12-19', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(303, 11, 23, '2025-12-19', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(304, 11, 24, '2025-12-20', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(305, 11, 23, '2025-12-20', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(306, 11, 24, '2025-12-20', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(307, 11, 23, '2025-12-21', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(308, 11, 24, '2025-12-21', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(309, 11, 23, '2025-12-21', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(310, 11, 24, '2025-12-22', '09:00:00', '10:57:00', 70000.00, 60000.00, '2D'),
(311, 11, 23, '2025-12-22', '13:00:00', '14:57:00', 70000.00, 60000.00, '2D'),
(312, 11, 24, '2025-12-22', '19:00:00', '20:57:00', 70000.00, 60000.00, '2D'),
(313, 12, 21, '2025-12-22', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(314, 12, 22, '2025-12-22', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(315, 12, 25, '2025-12-22', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(316, 12, 21, '2025-12-23', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(317, 12, 22, '2025-12-23', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(318, 12, 25, '2025-12-23', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(319, 12, 21, '2025-12-24', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(320, 12, 22, '2025-12-24', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(321, 12, 25, '2025-12-24', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(322, 12, 21, '2025-12-25', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(323, 12, 22, '2025-12-25', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(324, 12, 25, '2025-12-25', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(325, 15, 17, '2025-12-23', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(326, 15, 18, '2025-12-23', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(327, 15, 19, '2025-12-23', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(328, 15, 17, '2025-12-24', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(329, 15, 18, '2025-12-24', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(330, 15, 19, '2025-12-24', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(331, 15, 17, '2025-12-25', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(332, 15, 18, '2025-12-25', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(333, 15, 19, '2025-12-25', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(334, 15, 17, '2025-12-26', '09:00:00', '10:50:00', 70000.00, 60000.00, '2D'),
(335, 15, 18, '2025-12-26', '13:00:00', '14:50:00', 70000.00, 60000.00, '2D'),
(336, 15, 19, '2025-12-26', '19:00:00', '20:50:00', 70000.00, 60000.00, '2D'),
(337, 16, 17, '2025-12-26', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(338, 16, 18, '2025-12-26', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(339, 16, 19, '2025-12-26', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(340, 16, 17, '2025-12-27', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(341, 16, 18, '2025-12-27', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(342, 16, 19, '2025-12-27', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(343, 16, 17, '2025-12-28', '09:00:00', '11:02:00', 85000.00, 70000.00, '3D'),
(344, 16, 18, '2025-12-28', '13:00:00', '15:02:00', 85000.00, 70000.00, '3D'),
(345, 16, 19, '2025-12-28', '19:00:00', '21:02:00', 85000.00, 70000.00, '3D'),
(346, 17, 21, '2025-12-26', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(347, 17, 22, '2025-12-26', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(348, 17, 25, '2025-12-26', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(349, 17, 21, '2025-12-27', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(350, 17, 22, '2025-12-27', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(351, 17, 25, '2025-12-27', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(352, 17, 21, '2025-12-28', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(353, 17, 22, '2025-12-28', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(354, 17, 25, '2025-12-28', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(355, 17, 21, '2025-12-29', '09:00:00', '10:30:00', 70000.00, 60000.00, '2D'),
(356, 17, 22, '2025-12-29', '13:00:00', '14:30:00', 70000.00, 60000.00, '2D'),
(357, 17, 25, '2025-12-29', '19:00:00', '20:30:00', 70000.00, 60000.00, '2D'),
(358, 16, 23, '2025-12-11', '21:17:00', '23:19:00', NULL, NULL, '2D'),
(359, 12, 19, '2025-12-11', '10:25:00', '12:27:00', NULL, NULL, '2D'),
(360, 12, 19, '2025-12-12', '10:30:00', '12:32:00', NULL, NULL, '2D'),
(361, 18, 21, '2025-12-11', '21:30:00', '23:30:00', NULL, NULL, '2D'),
(362, NULL, 22, '2025-12-12', '11:17:00', '12:33:00', NULL, NULL, '2D');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_prices`
--

CREATE TABLE `ticket_prices` (
  `id` int NOT NULL,
  `day_type` varchar(20) NOT NULL COMMENT 'weekday hoặc weekend',
  `format` varchar(10) NOT NULL COMMENT '2D hoặc 3D',
  `customer_type` varchar(20) NOT NULL COMMENT 'adult hoặc student',
  `seat_type` varchar(20) NOT NULL COMMENT 'normal hoặc vip',
  `base_price` decimal(10,2) NOT NULL COMMENT 'Giá cơ bản (VNĐ)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ticket_prices`
--

INSERT INTO `ticket_prices` (`id`, `day_type`, `format`, `customer_type`, `seat_type`, `base_price`, `created_at`, `updated_at`) VALUES
(1, 'weekday', '2D', 'student', 'normal', 55000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(2, 'weekday', '2D', 'student', 'vip', 65000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(3, 'weekday', '2D', 'adult', 'normal', 65000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(4, 'weekday', '2D', 'adult', 'vip', 75000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(5, 'weekday', '3D', 'student', 'normal', 65000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(6, 'weekday', '3D', 'student', 'vip', 75000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(7, 'weekday', '3D', 'adult', 'normal', 75000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(8, 'weekday', '3D', 'adult', 'vip', 85000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(9, 'weekend', '2D', 'student', 'normal', 65000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(10, 'weekend', '2D', 'student', 'vip', 75000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(11, 'weekend', '2D', 'adult', 'normal', 75000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(12, 'weekend', '2D', 'adult', 'vip', 85000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(13, 'weekend', '3D', 'student', 'normal', 75000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(14, 'weekend', '3D', 'student', 'vip', 85000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(15, 'weekend', '3D', 'adult', 'normal', 85000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24'),
(16, 'weekend', '3D', 'adult', 'vip', 95000.00, '2025-11-22 14:55:53', '2025-11-28 11:07:24');

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
  `role` varchar(20) DEFAULT 'customer' COMMENT 'Vai trò: admin, staff, customer',
  `cinema_id` int DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active' COMMENT 'Trạng thái: active, banned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `birth_date`, `tier_id`, `total_spending`, `created_at`, `role`, `cinema_id`, `status`) VALUES
(2, 'Nguyễn Đức Anh', 'anp93005@gmail.com', '$2y$10$CWyRPSmpryxfnvWJk.WU6ee587peAVpJ2WM.gPnWxn1EURYPTorwe', '0386036692', '2025-10-28', NULL, 0.00, '2025-11-15 20:56:13', 'admin', NULL, 'active'),
(3, 'nguyễn văn A', 'anh123@gmail.com', '$2y$10$7gVirXBuWLl4bzlkZvpaT.MVe380dkGfWNRRmYFa8t.GeG6R9dVp2', '0386036636', '2000-10-12', NULL, 4206750.00, '2025-11-15 21:17:27', 'customer', NULL, 'active'),
(4, 'Bảo Châu', 'baochau06@gmail.com', '$2y$10$4msjaSiici7YXHciPSW0Cu/bYvTZuQRdlhm3ifOL4LTesvPmpzGxq', '0386036693', '2006-12-11', NULL, 0.00, '2025-11-18 22:28:02', 'manager', 3, 'active'),
(5, 'quoc tuan', 'quoctuan26@gmail.com', '$2y$10$hMMowF.wiR0LVaRVr4kf3.Q1r/DAU3gdS6fe/qZbf8neBZAokstIK', '0386036695', '2006-10-02', NULL, 0.00, '2025-11-26 20:54:11', 'manager', 2, 'active'),
(7, 'Anh Duc', 'anhnguyen121016@gmail.com', '$2y$10$aYquSofzM2G67EOJkmCFU.39C3rEOraUdheu6RV8oFRISvlvkDm8G', '0386036692', '2006-06-14', NULL, 0.00, '2025-11-26 21:46:48', 'staff', 2, 'active'),
(8, 'nhat minh', 'minh264@gmail.com', '$2y$10$LF5hZWO7P8lwFvEACPVBdOdcIY4WpbPJlW13QikkT12QZiCd59qCy', '0386036652', '2006-06-12', NULL, 0.00, '2025-11-26 22:03:59', 'manager', 1, 'active'),
(9, 'phong', 'p@gmail.com', '$2y$10$Rhh7ZfmteVo7RQh.FlvaiOS3YXiiO..rOFSpiwbHMwuRSpFnxmXiu', '0846146594', '2006-12-12', NULL, 75000.00, '2025-12-03 11:01:27', 'customer', NULL, 'active'),
(10, 'Duc anh1', 'ducanh@gmail.com', '$2y$10$5iVpce9YSxtrBDYhp2wq6.FVOIk.0sl60SGduHcmoHqoE2APKPE3u', '0386036563', '2000-10-12', NULL, 0.00, '2025-12-09 00:21:00', 'staff', NULL, 'active'),
(11, 'Trương Tổng', 'hhhhhh@gmail.com', '$2y$10$AoGOHdc/rx.AK0aXqGtwV.yTbHfLywSCFSHrfEWl0Ed8xWtQjUloK', '0846146594', '2006-05-09', NULL, 0.00, '2025-12-11 23:26:19', 'customer', NULL, 'active');

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
  ADD KEY `fk_comments_movie` (`movie_id`),
  ADD KEY `fk_comments_cinema` (`cinema_id`),
  ADD KEY `idx_comments_status` (`status`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `fk_contacts_user` (`user_id`);

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
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk_discount_codes_movie` (`movie_id`);

--
-- Indexes for table `food_drinks`
--
ALTER TABLE `food_drinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movies_genre` (`genre_id`),
  ADD KEY `fk_movies_cinema` (`cinema_id`);

--
-- Indexes for table `movie_cinemas`
--
ALTER TABLE `movie_cinemas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_movie_cinema` (`movie_id`,`cinema_id`),
  ADD KEY `fk_movie_cinemas_movie` (`movie_id`),
  ADD KEY `fk_movie_cinemas_cinema` (`cinema_id`);

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
-- Indexes for table `ticket_prices`
--
ALTER TABLE `ticket_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_price` (`day_type`,`format`,`customer_type`,`seat_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_tiers` (`tier_id`),
  ADD KEY `fk_users_cinema` (`cinema_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer_tiers`
--
ALTER TABLE `customer_tiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `food_drinks`
--
ALTER TABLE `food_drinks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `movie_cinemas`
--
ALTER TABLE `movie_cinemas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `movie_genres`
--
ALTER TABLE `movie_genres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID quyền', AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID phân quyền', AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4361;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `ticket_prices`
--
ALTER TABLE `ticket_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_contacts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD CONSTRAINT `fk_discount_codes_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movies_genre` FOREIGN KEY (`genre_id`) REFERENCES `movie_genres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `movie_cinemas`
--
ALTER TABLE `movie_cinemas`
  ADD CONSTRAINT `fk_movie_cinemas_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movie_cinemas_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_users_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_tiers` FOREIGN KEY (`tier_id`) REFERENCES `customer_tiers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
