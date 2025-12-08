-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2025 at 06:33 AM
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
(13, 3, 7, NULL, '2025-11-19 23:54:26', 'B8,B9', NULL, NULL, 180000.00, 0.00, 180000.00, 'pending', NULL, 'BK17635712663434', 1, 17),
(14, 3, 2, NULL, '2025-11-20 09:43:19', 'A5,A6,A7,A8,A9', NULL, NULL, 400000.00, 0.00, 400000.00, 'paid', NULL, 'BK17636065992196', 1, 18),
(15, 3, 4, NULL, '2025-11-20 19:46:25', 'A3,A4', NULL, NULL, 180000.00, 0.00, 180000.00, 'paid', NULL, 'BK17636427856547', 1, 17),
(17, 3, 2, NULL, '2025-11-20 21:32:21', 'B6', NULL, NULL, 55000.00, 0.00, 55000.00, 'paid', NULL, 'BK17636491412783', 1, 18),
(18, 3, 5, 3, '2025-11-24 10:53:16', 'A4,A5,A6,A7,A8', NULL, NULL, 375000.00, 75000.00, 300000.00, 'pending', NULL, 'BK17639563965750', 1, 18),
(19, 3, 10, 3, '2025-11-24 10:59:50', 'A4,A5,A6,A7,A8', NULL, NULL, 325000.00, 65000.00, 260000.00, 'cancelled', NULL, 'BK17639567904155', 2, 21),
(20, 3, 3, 6, '2025-11-26 10:48:58', 'A4', NULL, NULL, 85000.00, 85000.00, 0.00, 'pending', NULL, 'BK17641289388833', 1, 17),
(21, 3, NULL, NULL, '2025-11-27 20:05:22', 'A3', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17642487225080', 3, 23),
(22, 3, 11, NULL, '2025-11-27 21:16:22', 'A1,A2,A3,A4,A5', NULL, NULL, 355000.00, 0.00, 355000.00, 'paid', NULL, 'BK17642529829933', 1, 17),
(23, 3, 15, 6, '2025-11-28 10:02:48', 'A3', NULL, NULL, 75000.00, 63750.00, 11250.00, 'paid', NULL, 'BK17642989681601', 3, 23),
(24, 3, 15, 6, '2025-11-28 10:58:43', 'B6', NULL, NULL, 75000.00, 63750.00, 11250.00, 'paid', NULL, 'BK17643023239903', 3, 23),
(25, 3, 218, NULL, '2025-12-03 10:05:12', 'C4', NULL, NULL, 85000.00, 0.00, 85000.00, 'pending', NULL, 'BK17647311124144', 1, 17),
(26, 3, 218, NULL, '2025-12-03 10:05:27', 'C4', NULL, NULL, 85000.00, 0.00, 85000.00, 'paid', NULL, 'BK17647311272996', 1, 17),
(28, 3, 199, NULL, '2025-12-03 10:31:36', 'B1,B2,B3', NULL, NULL, 225000.00, 0.00, 225000.00, 'paid', NULL, 'BK17647326964828', 2, 21),
(29, 3, 199, NULL, '2025-12-03 11:02:50', 'A3', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17647345706049', 2, 21),
(30, 9, 199, NULL, '2025-12-03 11:02:51', 'A3', NULL, NULL, 75000.00, 0.00, 75000.00, 'paid', NULL, 'BK17647345715822', 2, 21);

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
  `rating` int DEFAULT NULL,
  `content` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `movie_id`, `rating`, `content`, `created_at`) VALUES
(2, 3, 6, 5, 'cũng hay nhm bn cần làm j đó cho khách hàng bt đến nhiều hơn', '2025-11-27 20:44:25'),
(3, 3, 7, 5, 'bổ rẻ hay nữa', '2025-11-27 21:10:59');

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
(7, 'nguyễn văn A', 'anh123@gmail.com', '0386036636', 'Đặt vé', 'bcsxvdszd', 'pending', 3, 1, '2025-12-06 20:11:50', '2025-12-06 20:11:50');

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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng mã giảm giá (movie_id = NULL: áp dụng cho tất cả phim, movie_id != NULL: chỉ áp dụng cho phim cụ thể)';

--
-- Dumping data for table `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `title`, `discount_percent`, `start_date`, `end_date`, `movie_id`, `description`, `benefits`, `status`, `cta`, `created_at`, `updated_at`) VALUES
(1, 'WEEKEND25', 'Giảm giá cuối tuần 25%', 25, '2025-11-01', '2025-12-31', NULL, 'Ưu đãi đặc biệt cho các suất chiếu cuối tuần (Thứ 6, Thứ 7, Chủ nhật). Áp dụng cho tất cả phim và tất cả rạp.', '[\"Áp dụng cho suất chiếu cuối tuần\", \"Giảm 25% cho mọi vé\", \"Không giới hạn số lượng vé\", \"Áp dụng cho tất cả phim\"]', 'active', 'Đặt vé cuối tuần', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(2, 'HOLIDAY30', 'Giảm giá ngày lễ 30%', 30, '2025-12-20', '2026-01-05', NULL, 'Ưu đãi đặc biệt trong dịp lễ Tết. Áp dụng cho các ngày lễ được chỉ định trong khoảng thời gian từ 20/12/2025 đến 05/01/2026.', '[\"Áp dụng trong dịp lễ Tết\", \"Giảm 30% cho mọi vé\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé dịp lễ', '2025-11-20 15:00:00', '2025-11-24 09:19:57'),
(3, 'COUPLE20', 'Ưu đãi cặp đôi 20%', 20, '2025-11-01', '2025-12-31', NULL, 'Ưu đãi dành riêng cho các cặp đôi. Áp dụng khi mua từ 2 vé trở lên trong cùng một đơn đặt vé.', '[\"Áp dụng khi mua từ 2 vé\", \"Giảm 20% cho mỗi vé\", \"Ghế liền kề miễn phí\", \"Áp dụng cho tất cả suất chiếu\"]', 'active', 'Đặt vé cặp đôi', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(4, 'FAMILY35', 'Ưu đãi gia đình 35%', 35, '2025-11-01', '2025-12-31', NULL, 'Ưu đãi đặc biệt cho gia đình. Áp dụng khi mua từ 3 vé trở lên trong cùng một đơn đặt vé.', '[\"Áp dụng khi mua từ 3 vé\", \"Giảm 35% cho mỗi vé\", \"Ưu tiên ghế gia đình\", \"Áp dụng cho tất cả suất chiếu\"]', 'active', 'Đặt vé gia đình', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(5, 'PREMIERE40', 'Giảm giá buổi chiếu đặc biệt 40%', 40, '2025-11-15', '2025-12-15', NULL, 'Ưu đãi đặc biệt cho các buổi chiếu đặc biệt và phim mới ra mắt. Áp dụng cho các suất chiếu được chỉ định.', '[\"Áp dụng cho buổi chiếu đặc biệt\", \"Giảm 40% cho mỗi vé\", \"Ưu tiên đặt chỗ sớm\", \"Số lượng có hạn\"]', 'active', 'Đặt vé chiếu đặc biệt', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(6, 'FASHION209', '85%', 85, '2025-11-28', '2025-11-30', NULL, '', NULL, 'active', '', '2025-11-26 10:47:51', '2025-11-28 10:02:17'),
(7, 'TRONCHAY30', 'Giảm giá TRỐN CHẠY TỬ THẦN 30%', 30, '2025-11-28', '2025-12-12', 9, 'Ưu đãi đặc biệt dành riêng cho phim TRỐN CHẠY TỬ THẦN-T18. Áp dụng cho tất cả suất chiếu của phim này.', '[\"Giảm 30% cho tổng đơn hàng\", \"Chỉ áp dụng cho phim TRỐN CHẠY TỬ THẦN-T18\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé ngay', '2025-11-28 10:00:00', '2025-11-28 10:00:00'),
(8, 'CHINGA25', 'Giảm giá Chị Ngã Em Nâng 25%', 25, '2025-11-28', '2025-12-11', 12, 'Ưu đãi đặc biệt dành riêng cho phim Chị Ngã Em Nâng. Áp dụng cho tất cả suất chiếu của phim này.', '[\"Giảm 25% cho tổng đơn hàng\", \"Chỉ áp dụng cho phim Chị Ngã Em Nâng\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé ngay', '2025-11-28 10:00:00', '2025-11-28 10:00:00');

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
(6, 7, NULL, 'Quán kỳ nma', 'ko hay lắm đâu', 120, 'image/phim 4.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-27', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-14 19:38:50', '2025-11-28 08:17:48'),
(7, 3, NULL, 'Phòng trọ ma bầu', 'kinh dị ko nên xem', 120, 'image/phim 5.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-27', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-14 20:27:20', '2025-11-28 08:18:00'),
(8, 1, NULL, 'Truy tìm long diên hương', 'cx hay lắm nha', 122, 'image/phim5.jpg', 'https://youtu.be/XjhAFebnNkM?si=vKFX_9ElyDAoSMMX', '2025-11-27', '2025-12-20', '2D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-15 11:43:43', '2025-11-28 08:18:07'),
(9, 1, NULL, 'TRỐN CHẠY TỬ THẦN-T18', 'Trong bối cảnh xã hội tương lai gần, Trốn Chạy Tử Thần là chương trình truyền hình ăn khách nhất, một cuộc thi sinh tồn khốc liệt nơi các thí sinh, được gọi là \"Runners\", phải trốn chạy suốt 30 ngày khỏi sự truy đuổi của các sát thủ chuyên nghiệp. Mọi bước đi của họ đều được phát sóng công khai cho khán giả theo dõi và phần thưởng tiền mặt sẽ tăng lên sau mỗi ngày sống sót. Vì cần tiền cứu chữa cho cô con gái bệnh nặng, Ben...', 133, 'image/phim 3.jpg', 'https://youtu.be/NuOl156fv_c?si=98qM39lvGn18VcdI', '2025-11-28', '2025-12-20', '2D', 'Tiếng Anh', 'Phụ Đề', 'C16', 'Mỹ', 'active', '2025-11-15 22:40:20', '2025-11-28 08:24:59'),
(10, 6, NULL, 'MỘ ĐOM ĐÓM', 'Hai anh em Seita và Setsuko mất mẹ sau cuộc thả bom dữ dội của không quân Mỹ. Cả hai phải vật lộn để tồn tại ở Nhật Bản hậu Thế chiến II. Nhưng xã hội khắc nghiệt và chúng vật lộn tìm kiếm thức ăn cũng như thoát khỏi những khó khăn giữa chiến tranh.', 120, 'image/phim 6.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-28', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-17 09:58:57', '2025-11-28 08:22:16'),
(11, 2, NULL, 'Tay Anh Giữ Một Vì Sao', 'Tay Anh Giữ Một Vì Sao\" mang đến một làn gió mới trong dòng phim chiếu rạp hay khi kết hợp khéo léo giữa yếu tố hài hước và cảm xúc chân thành. Câu chuyện xoay quanh siêu sao Kang Jun Woo bỗng rơi vào chuỗi sự cố trớ trêu khiến anh vô tình \"mắc kẹt\" tại Việt Nam. Tại đây, anh gặp Thảo - cô gái bán cà phê giản dị nhưng mang trong mình khát vọng lớn lao. Những va chạm và hiểu lầm dần trở thành sợi dây gắn kết, giúp cả hai tìm thấy niềm tin, ước mơ và định nghĩa mới về tình yêu. Bộ phim không chỉ khiến khán giả bật cười bởi những tình huống duyên dáng mà còn chạm đến trái tim bằng câu chuyện nhân văn về sự đồng cảm và thay đổi.', 117, 'image/phim7.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-28', '2025-12-20', '2D', 'Tiếng Anh', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-19 09:35:10', '2025-11-28 10:31:05'),
(12, 2, NULL, 'Chị Ngã Em Nâng', 'Giữa muôn vàn phim chiếu rạp hay tháng 10, \"Chị Ngã Em Nâng\" nổi bật như một bản giao hòa đầy cảm xúc về tình thân và nghị lực con người. Bộ phim khắc họa hành trình của hai chị em Thương và Lực là những người lớn lên trong gia đình gắn bó với nghề làm nhang truyền thống. Với tuổi thơ nhiều mất mát, Thương trở thành điểm tựa duy nhất cho em trai, mang trong mình khát vọng đổi đời và niềm tin mãnh liệt vào tương lai. Thế nhưng, khi thành công đến, sự kỳ vọng và áp lực vô tình khiến tình chị em rạn nứt, đẩy họ đến những lựa chọn đau lòng. \"Chị Ngã Em Nâng\" chạm đến trái tim người xem bằng những giá trị nhân văn sâu sắc, về tình thương, sự bao dung và ý nghĩa của hai chữ \"gia đình\".', 122, 'image/hh.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-28', '2025-12-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C13', 'Việt Nam', 'active', '2025-11-19 09:40:28', '2025-11-28 08:18:28');

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
(23, 11, 3, '2025-11-28 03:31:05');

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
(18, 30, 'vnpay', '15316674', '2025-12-03 04:03:15', 75000.00, 0.00, 75000.00, 'paid');

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
(1, 7, 23, '2025-12-18', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(2, 8, 18, '2025-12-11', '10:30:00', '12:52:00', 70000.00, 60000.00, '2D'),
(3, 11, 23, '2025-12-14', '12:00:00', '14:17:00', 70000.00, 60000.00, '2D'),
(4, 12, 21, '2025-12-09', '19:30:00', '21:52:00', 85000.00, 70000.00, '3D'),
(5, 12, 23, '2025-12-05', '10:30:00', '12:52:00', 85000.00, 70000.00, '3D'),
(6, 7, 21, '2025-12-09', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(7, 12, 22, '2025-12-16', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(8, 9, 23, '2025-11-28', '10:30:00', '13:03:00', 70000.00, 60000.00, '2D'),
(9, 10, 18, '2025-12-08', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(10, 9, 24, '2025-12-04', '21:00:00', '23:33:00', 70000.00, 60000.00, '2D'),
(11, 7, 24, '2025-12-03', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(12, 6, 18, '2025-11-30', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(13, 6, 23, '2025-12-01', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(14, 6, 24, '2025-12-07', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(15, 9, 21, '2025-12-15', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(16, 11, 24, '2025-12-16', '10:30:00', '12:47:00', 70000.00, 60000.00, '2D'),
(17, 12, 17, '2025-12-07', '12:00:00', '14:22:00', 85000.00, 70000.00, '3D'),
(18, 11, 23, '2025-11-30', '13:30:00', '15:47:00', 70000.00, 60000.00, '2D'),
(19, 11, 24, '2025-12-09', '15:00:00', '17:17:00', 70000.00, 60000.00, '2D'),
(20, 9, 18, '2025-12-08', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(21, 7, 23, '2025-12-08', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(22, 10, 19, '2025-12-11', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(23, 7, 24, '2025-12-12', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(24, 11, 18, '2025-12-12', '09:00:00', '11:17:00', 70000.00, 60000.00, '2D'),
(25, 11, 22, '2025-12-02', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(26, 10, 21, '2025-12-17', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(27, 6, 23, '2025-12-16', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(28, 8, 19, '2025-12-11', '19:30:00', '21:52:00', 70000.00, 60000.00, '2D'),
(29, 9, 22, '2025-12-01', '15:00:00', '17:33:00', 70000.00, 60000.00, '2D'),
(30, 8, 21, '2025-12-11', '10:30:00', '12:52:00', 70000.00, 60000.00, '2D'),
(31, 11, 21, '2025-12-14', '13:30:00', '15:47:00', 70000.00, 60000.00, '2D'),
(32, 7, 24, '2025-11-28', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(33, 9, 21, '2025-12-01', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(34, 12, 22, '2025-12-16', '16:30:00', '18:52:00', 85000.00, 70000.00, '3D'),
(35, 11, 17, '2025-12-19', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(36, 10, 18, '2025-12-15', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(37, 9, 25, '2025-12-19', '18:00:00', '20:33:00', 70000.00, 60000.00, '2D'),
(38, 6, 19, '2025-12-06', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(39, 9, 23, '2025-12-17', '15:00:00', '17:33:00', 70000.00, 60000.00, '2D'),
(40, 6, 24, '2025-12-08', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(41, 12, 22, '2025-12-10', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(42, 7, 22, '2025-12-05', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(43, 12, 22, '2025-11-28', '19:30:00', '21:52:00', 85000.00, 70000.00, '3D'),
(44, 8, 23, '2025-12-10', '18:00:00', '20:22:00', 70000.00, 60000.00, '2D'),
(45, 9, 21, '2025-12-07', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(46, 8, 23, '2025-12-18', '09:00:00', '11:22:00', 70000.00, 60000.00, '2D'),
(47, 9, 19, '2025-12-11', '13:30:00', '16:03:00', 70000.00, 60000.00, '2D'),
(48, 9, 19, '2025-12-10', '09:00:00', '11:33:00', 70000.00, 60000.00, '2D'),
(49, 10, 19, '2025-12-10', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(50, 6, 18, '2025-11-28', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(51, 9, 25, '2025-12-12', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(52, 10, 21, '2025-12-16', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(53, 11, 23, '2025-12-11', '09:00:00', '11:17:00', 70000.00, 60000.00, '2D'),
(54, 8, 19, '2025-12-09', '16:30:00', '18:52:00', 70000.00, 60000.00, '2D'),
(55, 7, 21, '2025-11-29', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(56, 10, 22, '2025-12-06', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(57, 10, 21, '2025-11-28', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(58, 7, 19, '2025-12-02', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(59, 9, 18, '2025-12-18', '21:00:00', '23:33:00', 70000.00, 60000.00, '2D'),
(60, 12, 17, '2025-12-09', '15:00:00', '17:22:00', 85000.00, 70000.00, '3D'),
(61, 9, 17, '2025-12-06', '09:00:00', '11:33:00', 70000.00, 60000.00, '2D'),
(62, 9, 23, '2025-12-13', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(63, 7, 25, '2025-12-18', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(64, 7, 19, '2025-12-06', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(65, 9, 25, '2025-12-16', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(66, 10, 22, '2025-12-17', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(67, 10, 22, '2025-12-10', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(68, 12, 19, '2025-12-06', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(69, 9, 19, '2025-12-14', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(70, 11, 23, '2025-12-17', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(71, 6, 24, '2025-12-13', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(72, 12, 21, '2025-11-30', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(73, 10, 17, '2025-12-16', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(74, 10, 18, '2025-12-06', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(75, 8, 19, '2025-12-08', '12:00:00', '14:22:00', 70000.00, 60000.00, '2D'),
(76, 7, 19, '2025-12-07', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(77, 9, 22, '2025-12-19', '10:30:00', '13:03:00', 70000.00, 60000.00, '2D'),
(78, 6, 19, '2025-11-29', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(79, 6, 25, '2025-12-08', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(80, 10, 19, '2025-12-12', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(81, 9, 18, '2025-12-06', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(82, 9, 25, '2025-12-06', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(83, 11, 17, '2025-12-07', '19:30:00', '21:47:00', 70000.00, 60000.00, '2D'),
(84, 7, 18, '2025-12-10', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(85, 8, 19, '2025-12-12', '15:00:00', '17:22:00', 70000.00, 60000.00, '2D'),
(86, 12, 22, '2025-12-20', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(87, 6, 25, '2025-12-10', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(88, 6, 25, '2025-12-19', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(89, 6, 17, '2025-12-16', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(90, 9, 25, '2025-12-16', '19:30:00', '22:03:00', 70000.00, 60000.00, '2D'),
(91, 11, 21, '2025-12-03', '09:00:00', '11:17:00', 70000.00, 60000.00, '2D'),
(92, 10, 22, '2025-12-05', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(93, 12, 24, '2025-12-04', '12:00:00', '14:22:00', 85000.00, 70000.00, '3D'),
(94, 12, 21, '2025-12-04', '19:30:00', '21:52:00', 85000.00, 70000.00, '3D'),
(95, 9, 24, '2025-12-17', '13:30:00', '16:03:00', 70000.00, 60000.00, '2D'),
(96, 8, 17, '2025-12-09', '10:30:00', '12:52:00', 70000.00, 60000.00, '2D'),
(97, 6, 25, '2025-12-19', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(98, 8, 24, '2025-12-07', '10:30:00', '12:52:00', 70000.00, 60000.00, '2D'),
(99, 11, 22, '2025-11-28', '19:30:00', '21:47:00', 70000.00, 60000.00, '2D'),
(100, 10, 19, '2025-12-06', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(101, 6, 21, '2025-12-15', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(102, 11, 23, '2025-11-29', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(103, 7, 17, '2025-12-19', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(104, 10, 23, '2025-12-14', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(105, 7, 24, '2025-12-05', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(106, 11, 17, '2025-11-28', '09:00:00', '11:17:00', 70000.00, 60000.00, '2D'),
(107, 9, 17, '2025-11-30', '13:30:00', '16:03:00', 70000.00, 60000.00, '2D'),
(108, 7, 21, '2025-12-09', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(109, 6, 19, '2025-11-30', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(110, 7, 18, '2025-12-08', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(111, 7, 18, '2025-12-17', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(112, 11, 25, '2025-12-12', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(113, 8, 17, '2025-12-07', '10:30:00', '12:52:00', 70000.00, 60000.00, '2D'),
(114, 11, 22, '2025-12-16', '19:30:00', '21:47:00', 70000.00, 60000.00, '2D'),
(115, 10, 17, '2025-12-15', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(116, 7, 25, '2025-12-12', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(117, 11, 22, '2025-12-11', '19:30:00', '21:47:00', 70000.00, 60000.00, '2D'),
(118, 11, 17, '2025-12-12', '12:00:00', '14:17:00', 70000.00, 60000.00, '2D'),
(119, 9, 23, '2025-12-06', '15:00:00', '17:33:00', 70000.00, 60000.00, '2D'),
(120, 12, 18, '2025-12-10', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(121, 12, 25, '2025-12-17', '09:00:00', '11:22:00', 85000.00, 70000.00, '3D'),
(122, 9, 21, '2025-11-29', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(123, 10, 17, '2025-12-19', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(124, 6, 25, '2025-12-18', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(125, 10, 21, '2025-12-18', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(126, 10, 19, '2025-12-07', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(127, 7, 24, '2025-12-20', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(128, 9, 25, '2025-12-06', '10:30:00', '13:03:00', 70000.00, 60000.00, '2D'),
(129, 10, 24, '2025-12-03', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(130, 10, 24, '2025-12-17', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(131, 11, 19, '2025-12-09', '09:00:00', '11:17:00', 70000.00, 60000.00, '2D'),
(132, 12, 25, '2025-11-30', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(133, 12, 21, '2025-12-01', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(134, 7, 18, '2025-12-05', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(135, 9, 24, '2025-12-12', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(136, 7, 23, '2025-12-12', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(137, 7, 22, '2025-12-10', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(138, 11, 24, '2025-11-28', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(139, 8, 17, '2025-12-12', '19:30:00', '21:52:00', 70000.00, 60000.00, '2D'),
(140, 9, 25, '2025-12-07', '13:30:00', '16:03:00', 70000.00, 60000.00, '2D'),
(141, 11, 22, '2025-12-04', '15:00:00', '17:17:00', 70000.00, 60000.00, '2D'),
(142, 12, 19, '2025-11-30', '10:30:00', '12:52:00', 85000.00, 70000.00, '3D'),
(143, 8, 21, '2025-12-13', '12:00:00', '14:22:00', 70000.00, 60000.00, '2D'),
(144, 7, 21, '2025-12-09', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(145, 7, 25, '2025-12-20', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(146, 9, 22, '2025-12-17', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(147, 7, 18, '2025-12-17', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(148, 11, 25, '2025-12-17', '12:00:00', '14:17:00', 70000.00, 60000.00, '2D'),
(149, 6, 17, '2025-12-02', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(150, 11, 17, '2025-11-29', '13:30:00', '15:47:00', 70000.00, 60000.00, '2D'),
(151, 9, 18, '2025-12-16', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(152, 12, 24, '2025-12-10', '16:30:00', '18:52:00', 85000.00, 70000.00, '3D'),
(153, 11, 23, '2025-12-03', '09:00:00', '11:17:00', 70000.00, 60000.00, '2D'),
(154, 10, 21, '2025-11-30', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(155, 12, 24, '2025-12-06', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(156, 9, 22, '2025-12-06', '21:00:00', '23:33:00', 70000.00, 60000.00, '2D'),
(157, 6, 19, '2025-12-10', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(158, 12, 21, '2025-12-12', '13:30:00', '15:52:00', 85000.00, 70000.00, '3D'),
(159, 10, 17, '2025-11-29', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(160, 12, 19, '2025-12-20', '12:00:00', '14:22:00', 85000.00, 70000.00, '3D'),
(161, 8, 24, '2025-12-06', '18:00:00', '20:22:00', 70000.00, 60000.00, '2D'),
(162, 12, 21, '2025-12-05', '12:00:00', '14:22:00', 85000.00, 70000.00, '3D'),
(163, 12, 25, '2025-12-06', '10:30:00', '12:52:00', 85000.00, 70000.00, '3D'),
(164, 10, 22, '2025-12-19', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(165, 6, 22, '2025-12-20', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(166, 6, 24, '2025-12-17', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(167, 11, 18, '2025-12-04', '10:30:00', '12:47:00', 70000.00, 60000.00, '2D'),
(168, 8, 21, '2025-12-01', '21:00:00', '23:22:00', 70000.00, 60000.00, '2D'),
(169, 7, 19, '2025-12-11', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(170, 6, 19, '2025-12-18', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(171, 12, 24, '2025-12-16', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(172, 9, 17, '2025-12-02', '15:00:00', '17:33:00', 70000.00, 60000.00, '2D'),
(173, 9, 22, '2025-12-20', '13:30:00', '16:03:00', 70000.00, 60000.00, '2D'),
(174, 12, 23, '2025-12-14', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(175, 10, 22, '2025-12-16', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(176, 12, 24, '2025-12-16', '13:30:00', '15:52:00', 85000.00, 70000.00, '3D'),
(177, 7, 18, '2025-11-28', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(178, 7, 17, '2025-12-19', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(179, 6, 25, '2025-12-14', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(180, 12, 18, '2025-12-16', '09:00:00', '11:22:00', 85000.00, 70000.00, '3D'),
(181, 10, 23, '2025-12-10', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(182, 12, 19, '2025-12-14', '19:30:00', '21:52:00', 85000.00, 70000.00, '3D'),
(183, 9, 23, '2025-12-15', '09:00:00', '11:33:00', 70000.00, 60000.00, '2D'),
(184, 8, 23, '2025-12-19', '21:00:00', '23:22:00', 70000.00, 60000.00, '2D'),
(185, 6, 23, '2025-12-04', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(186, 9, 22, '2025-12-13', '09:00:00', '11:33:00', 70000.00, 60000.00, '2D'),
(187, 6, 21, '2025-11-30', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(188, 11, 22, '2025-12-18', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(189, 8, 18, '2025-12-07', '09:00:00', '11:22:00', 70000.00, 60000.00, '2D'),
(190, 12, 18, '2025-12-02', '09:00:00', '11:22:00', 85000.00, 70000.00, '3D'),
(191, 10, 19, '2025-12-07', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(192, 6, 25, '2025-12-20', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(193, 7, 21, '2025-12-07', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(194, 6, 24, '2025-12-01', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(195, 7, 21, '2025-12-20', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(196, 10, 17, '2025-12-02', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(197, 7, 22, '2025-12-03', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(198, 12, 22, '2025-12-16', '18:00:00', '20:22:00', 85000.00, 70000.00, '3D'),
(199, 12, 21, '2025-12-03', '09:00:00', '11:22:00', 85000.00, 70000.00, '3D'),
(200, 11, 19, '2025-12-15', '19:30:00', '21:47:00', 70000.00, 60000.00, '2D'),
(201, 9, 18, '2025-11-30', '19:30:00', '22:03:00', 70000.00, 60000.00, '2D'),
(202, 8, 22, '2025-12-01', '16:30:00', '18:52:00', 70000.00, 60000.00, '2D'),
(203, 7, 18, '2025-12-12', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(204, 11, 22, '2025-12-15', '15:00:00', '17:17:00', 70000.00, 60000.00, '2D'),
(205, 7, 23, '2025-11-28', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(206, 8, 17, '2025-12-01', '13:30:00', '15:52:00', 70000.00, 60000.00, '2D'),
(207, 8, 25, '2025-12-04', '19:30:00', '21:52:00', 70000.00, 60000.00, '2D'),
(208, 6, 18, '2025-12-16', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(209, 8, 17, '2025-12-18', '15:00:00', '17:22:00', 70000.00, 60000.00, '2D'),
(210, 8, 25, '2025-12-13', '15:00:00', '17:22:00', 70000.00, 60000.00, '2D'),
(211, 6, 23, '2025-12-08', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(212, 10, 23, '2025-12-01', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(213, 12, 24, '2025-12-12', '16:30:00', '18:52:00', 85000.00, 70000.00, '3D'),
(214, 7, 19, '2025-12-20', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(215, 9, 18, '2025-12-15', '10:30:00', '13:03:00', 70000.00, 60000.00, '2D'),
(216, 6, 25, '2025-12-05', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(217, 11, 25, '2025-12-20', '19:30:00', '21:47:00', 70000.00, 60000.00, '2D'),
(218, 12, 17, '2025-12-03', '10:30:00', '12:52:00', 85000.00, 70000.00, '3D'),
(219, 7, 18, '2025-12-07', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(220, 6, 19, '2025-11-29', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(221, 7, 25, '2025-12-01', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(222, 10, 21, '2025-12-07', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(223, 8, 23, '2025-12-10', '09:00:00', '11:22:00', 70000.00, 60000.00, '2D'),
(224, 11, 18, '2025-12-17', '13:30:00', '15:47:00', 70000.00, 60000.00, '2D'),
(225, 12, 21, '2025-11-28', '12:00:00', '14:22:00', 85000.00, 70000.00, '3D'),
(226, 6, 24, '2025-11-28', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(227, 7, 25, '2025-12-03', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(228, 6, 22, '2025-12-04', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(229, 10, 24, '2025-12-05', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(230, 10, 25, '2025-12-11', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(231, 10, 23, '2025-12-09', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(232, 10, 17, '2025-12-15', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(233, 11, 18, '2025-12-20', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(234, 12, 24, '2025-12-12', '19:30:00', '21:52:00', 85000.00, 70000.00, '3D'),
(235, 11, 22, '2025-12-05', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(236, 7, 21, '2025-11-29', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(237, 11, 21, '2025-12-09', '15:00:00', '17:17:00', 70000.00, 60000.00, '2D'),
(238, 11, 24, '2025-11-29', '13:30:00', '15:47:00', 70000.00, 60000.00, '2D'),
(239, 9, 25, '2025-12-14', '10:30:00', '13:03:00', 70000.00, 60000.00, '2D'),
(240, 7, 21, '2025-12-08', '19:30:00', '21:50:00', 85000.00, 70000.00, '3D'),
(241, 11, 21, '2025-12-13', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(242, 6, 17, '2025-11-30', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(243, 11, 21, '2025-12-13', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(244, 6, 18, '2025-12-19', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(245, 10, 18, '2025-12-01', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(246, 7, 18, '2025-12-18', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(247, 9, 19, '2025-12-01', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(248, 12, 19, '2025-12-17', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(249, 10, 21, '2025-12-13', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(250, 12, 19, '2025-11-28', '19:30:00', '21:52:00', 85000.00, 70000.00, '3D'),
(251, 10, 25, '2025-11-30', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(252, 9, 22, '2025-12-19', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(253, 11, 25, '2025-12-13', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(254, 10, 18, '2025-12-17', '09:00:00', '11:20:00', 85000.00, 70000.00, '3D'),
(255, 9, 24, '2025-12-02', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(256, 9, 25, '2025-12-08', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(257, 6, 17, '2025-11-30', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(258, 8, 18, '2025-11-30', '19:30:00', '21:52:00', 70000.00, 60000.00, '2D'),
(259, 8, 22, '2025-11-28', '13:30:00', '15:52:00', 70000.00, 60000.00, '2D'),
(260, 7, 17, '2025-12-10', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(261, 11, 19, '2025-12-13', '10:30:00', '12:47:00', 70000.00, 60000.00, '2D'),
(262, 11, 21, '2025-12-02', '13:30:00', '15:47:00', 70000.00, 60000.00, '2D'),
(263, 10, 18, '2025-12-08', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(264, 8, 23, '2025-12-06', '10:30:00', '12:52:00', 70000.00, 60000.00, '2D'),
(265, 10, 25, '2025-12-10', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(266, 6, 25, '2025-11-28', '13:30:00', '15:50:00', 85000.00, 70000.00, '3D'),
(267, 6, 22, '2025-12-12', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(268, 7, 18, '2025-12-09', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(269, 12, 22, '2025-12-05', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(270, 11, 17, '2025-11-29', '16:30:00', '18:47:00', 70000.00, 60000.00, '2D'),
(271, 8, 23, '2025-12-10', '18:00:00', '20:22:00', 70000.00, 60000.00, '2D'),
(272, 9, 23, '2025-12-13', '15:00:00', '17:33:00', 70000.00, 60000.00, '2D'),
(273, 12, 21, '2025-11-28', '21:00:00', '23:22:00', 85000.00, 70000.00, '3D'),
(274, 11, 17, '2025-12-07', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(275, 6, 22, '2025-12-19', '15:00:00', '17:20:00', 85000.00, 70000.00, '3D'),
(276, 12, 19, '2025-12-17', '10:30:00', '12:52:00', 85000.00, 70000.00, '3D'),
(277, 11, 24, '2025-12-08', '15:00:00', '17:17:00', 70000.00, 60000.00, '2D'),
(278, 7, 18, '2025-12-01', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(279, 9, 23, '2025-11-28', '21:00:00', '23:33:00', 70000.00, 60000.00, '2D'),
(280, 9, 18, '2025-12-15', '16:30:00', '19:03:00', 70000.00, 60000.00, '2D'),
(281, 8, 22, '2025-11-30', '12:00:00', '14:22:00', 70000.00, 60000.00, '2D'),
(282, 8, 22, '2025-12-04', '16:30:00', '18:52:00', 70000.00, 60000.00, '2D'),
(283, 12, 19, '2025-12-17', '10:30:00', '12:52:00', 85000.00, 70000.00, '3D'),
(284, 10, 17, '2025-12-13', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(285, 11, 22, '2025-12-15', '15:00:00', '17:17:00', 70000.00, 60000.00, '2D'),
(286, 12, 17, '2025-12-20', '09:00:00', '11:22:00', 85000.00, 70000.00, '3D'),
(287, 7, 19, '2025-12-07', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(288, 11, 24, '2025-11-30', '18:00:00', '20:17:00', 70000.00, 60000.00, '2D'),
(289, 9, 17, '2025-12-15', '09:00:00', '11:33:00', 70000.00, 60000.00, '2D'),
(290, 6, 22, '2025-12-20', '12:00:00', '14:20:00', 85000.00, 70000.00, '3D'),
(291, 12, 19, '2025-12-08', '09:00:00', '11:22:00', 85000.00, 70000.00, '3D'),
(292, 8, 21, '2025-12-17', '18:00:00', '20:22:00', 70000.00, 60000.00, '2D'),
(293, 8, 18, '2025-12-02', '13:30:00', '15:52:00', 70000.00, 60000.00, '2D'),
(294, 10, 22, '2025-12-11', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D'),
(295, 6, 23, '2025-12-13', '18:00:00', '20:20:00', 85000.00, 70000.00, '3D'),
(296, 7, 19, '2025-12-09', '10:30:00', '12:50:00', 85000.00, 70000.00, '3D'),
(297, 9, 18, '2025-12-04', '12:00:00', '14:33:00', 70000.00, 60000.00, '2D'),
(298, 7, 23, '2025-12-01', '21:00:00', '23:20:00', 85000.00, 70000.00, '3D'),
(299, 12, 17, '2025-12-04', '15:00:00', '17:22:00', 85000.00, 70000.00, '3D'),
(300, 10, 19, '2025-12-05', '16:30:00', '18:50:00', 85000.00, 70000.00, '3D');

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
(3, 'nguyễn văn A', 'anh123@gmail.com', '$2y$10$7gVirXBuWLl4bzlkZvpaT.MVe380dkGfWNRRmYFa8t.GeG6R9dVp2', '0386036636', '2000-10-12', NULL, 2592500.00, '2025-11-15 21:17:27', 'customer', NULL, 'active'),
(4, 'Bảo Châu', 'baochau06@gmail.com', '$2y$10$4msjaSiici7YXHciPSW0Cu/bYvTZuQRdlhm3ifOL4LTesvPmpzGxq', '0386036693', '2006-12-11', NULL, 0.00, '2025-11-18 22:28:02', 'manager', 3, 'active'),
(5, 'quoc tuan', 'quoctuan26@gmail.com', '$2y$10$hMMowF.wiR0LVaRVr4kf3.Q1r/DAU3gdS6fe/qZbf8neBZAokstIK', '0386036695', '2006-10-02', NULL, 0.00, '2025-11-26 20:54:11', 'manager', 2, 'active'),
(7, 'Anh Duc', 'anhnguyen121016@gmail.com', '$2y$10$aYquSofzM2G67EOJkmCFU.39C3rEOraUdheu6RV8oFRISvlvkDm8G', '0386036692', '2006-06-14', NULL, 0.00, '2025-11-26 21:46:48', 'staff', 2, 'active'),
(8, 'nhat minh', 'minh264@gmail.com', '$2y$10$LF5hZWO7P8lwFvEACPVBdOdcIY4WpbPJlW13QikkT12QZiCd59qCy', '0386036652', '2006-06-12', NULL, 0.00, '2025-11-26 22:03:59', 'manager', 1, 'active'),
(9, 'phong', 'p@gmail.com', '$2y$10$Rhh7ZfmteVo7RQh.FlvaiOS3YXiiO..rOFSpiwbHMwuRSpFnxmXiu', '0846146594', '2006-12-12', NULL, 75000.00, '2025-12-03 11:01:27', 'customer', NULL, 'active');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `movie_cinemas`
--
ALTER TABLE `movie_cinemas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `movie_genres`
--
ALTER TABLE `movie_genres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `ticket_prices`
--
ALTER TABLE `ticket_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
