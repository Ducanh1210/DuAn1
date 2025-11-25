-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2025 at 02:00 PM
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
(4, 3, 9, NULL, '2025-11-19 10:21:08', 'C12,C11', 'normal', NULL, '160000.00', '0.00', '160000.00', 'paid', NULL, 'BK2025111943C218', 1, 18),
(5, 3, 9, NULL, '2025-11-19 10:32:15', 'B7,B6,B5', 'normal', NULL, '240000.00', '0.00', '240000.00', 'paid', NULL, 'BK20251119F0FF11', 1, 18),
(6, 3, 9, NULL, '2025-11-19 12:53:17', 'C8,C7', 'normal', NULL, '160000.00', '0.00', '160000.00', 'cancelled', NULL, 'BK20251119D29273', 1, 18),
(7, 3, 9, NULL, '2025-11-19 13:23:29', 'A11', NULL, NULL, '80000.00', '0.00', '80000.00', 'paid', NULL, 'BK17635334097649', 1, 18),
(8, 3, 9, NULL, '2025-11-19 13:38:21', 'D11', NULL, NULL, '80000.00', '0.00', '80000.00', 'paid', NULL, 'BK17635343016235', 1, 18),
(10, 3, 9, NULL, '2025-11-19 23:29:59', 'A4,A5,A6,A7', NULL, NULL, '320000.00', '0.00', '320000.00', 'paid', NULL, 'BK17635697994209', 1, 18),
(11, 3, 9, NULL, '2025-11-19 23:34:04', 'B3,B4', NULL, NULL, '160000.00', '0.00', '160000.00', 'paid', NULL, 'BK17635700449534', 1, 18),
(12, 3, 9, NULL, '2025-11-19 23:41:37', 'B9', NULL, NULL, '80000.00', '0.00', '80000.00', 'paid', NULL, 'BK17635704977283', 1, 18),
(13, 3, 7, NULL, '2025-11-19 23:54:26', 'B8,B9', NULL, NULL, '180000.00', '0.00', '180000.00', 'pending', NULL, 'BK17635712663434', 1, 17),
(14, 3, 2, NULL, '2025-11-20 09:43:19', 'A5,A6,A7,A8,A9', NULL, NULL, '400000.00', '0.00', '400000.00', 'paid', NULL, 'BK17636065992196', 1, 18),
(15, 3, 4, NULL, '2025-11-20 19:46:25', 'A3,A4', NULL, NULL, '180000.00', '0.00', '180000.00', 'paid', NULL, 'BK17636427856547', 1, 17),
(16, 3, 2, NULL, '2025-11-20 21:31:24', 'B6', NULL, NULL, '55000.00', '0.00', '55000.00', 'pending', NULL, 'BK17636490845963', 1, 18),
(17, 3, 2, NULL, '2025-11-20 21:32:21', 'B6', NULL, NULL, '55000.00', '0.00', '55000.00', 'paid', NULL, 'BK17636491412783', 1, 18);

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
  `description` text,
  `benefits` json DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `cta` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng mã giảm giá';

--
-- Dumping data for table `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `title`, `discount_percent`, `start_date`, `end_date`, `description`, `benefits`, `status`, `cta`, `created_at`, `updated_at`) VALUES
(1, 'WEEKEND25', 'Giảm giá cuối tuần 25%', 25, '2025-11-01', '2025-12-31', 'Ưu đãi đặc biệt cho các suất chiếu cuối tuần (Thứ 6, Thứ 7, Chủ nhật). Áp dụng cho tất cả phim và tất cả rạp.', '[\"Áp dụng cho suất chiếu cuối tuần\", \"Giảm 25% cho mọi vé\", \"Không giới hạn số lượng vé\", \"Áp dụng cho tất cả phim\"]', 'active', 'Đặt vé cuối tuần', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(2, 'HOLIDAY30', 'Giảm giá ngày lễ 30%', 30, '2025-12-20', '2026-01-05', 'Ưu đãi đặc biệt trong dịp lễ Tết. Áp dụng cho các ngày lễ được chỉ định trong khoảng thời gian từ 20/12/2025 đến 05/01/2026.', '[\"Áp dụng trong dịp lễ Tết\", \"Giảm 30% cho mọi vé\", \"Áp dụng cho tất cả suất chiếu\", \"Không giới hạn số lượng vé\"]', 'active', 'Đặt vé dịp lễ', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(3, 'COUPLE20', 'Ưu đãi cặp đôi 20%', 20, '2025-11-01', '2025-12-31', 'Ưu đãi dành riêng cho các cặp đôi. Áp dụng khi mua từ 2 vé trở lên trong cùng một đơn đặt vé.', '[\"Áp dụng khi mua từ 2 vé\", \"Giảm 20% cho mỗi vé\", \"Ghế liền kề miễn phí\", \"Áp dụng cho tất cả suất chiếu\"]', 'active', 'Đặt vé cặp đôi', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(4, 'FAMILY35', 'Ưu đãi gia đình 35%', 35, '2025-11-01', '2025-12-31', 'Ưu đãi đặc biệt cho gia đình. Áp dụng khi mua từ 3 vé trở lên trong cùng một đơn đặt vé.', '[\"Áp dụng khi mua từ 3 vé\", \"Giảm 35% cho mỗi vé\", \"Ưu tiên ghế gia đình\", \"Áp dụng cho tất cả suất chiếu\"]', 'active', 'Đặt vé gia đình', '2025-11-20 15:00:00', '2025-11-20 15:00:00'),
(5, 'PREMIERE40', 'Giảm giá buổi chiếu đặc biệt 40%', 40, '2025-11-15', '2025-12-15', 'Ưu đãi đặc biệt cho các buổi chiếu đặc biệt và phim mới ra mắt. Áp dụng cho các suất chiếu được chỉ định.', '[\"Áp dụng cho buổi chiếu đặc biệt\", \"Giảm 40% cho mỗi vé\", \"Ưu tiên đặt chỗ sớm\", \"Số lượng có hạn\"]', 'active', 'Đặt vé chiếu đặc biệt', '2025-11-20 15:00:00', '2025-11-20 15:00:00');

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
(6, 7, 'Quán kỳ nma', 'ko hay lắm đâu', 120, 'image/phim 4.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-20', '2025-12-31', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-14 19:38:50', '2025-11-20 21:14:48'),
(7, 3, 'Phòng trọ ma bầu', 'kinh dị ko nên xem', 120, 'image/phim 5.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-17', '2025-12-31', '3D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-14 20:27:20', '2025-11-17 09:52:58'),
(8, 1, 'Truy tìm long diên hương', 'cx hay lắm nha', 122, 'image/phim5.jpg', 'https://youtu.be/XjhAFebnNkM?si=vKFX_9ElyDAoSMMX', '2025-11-19', '2025-12-31', '2D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-15 11:43:43', '2025-11-19 09:39:00'),
(9, 1, 'TRỐN CHẠY TỬ THẦN-T18', 'Trong bối cảnh xã hội tương lai gần, Trốn Chạy Tử Thần là chương trình truyền hình ăn khách nhất, một cuộc thi sinh tồn khốc liệt nơi các thí sinh, được gọi là \"Runners\", phải trốn chạy suốt 30 ngày khỏi sự truy đuổi của các sát thủ chuyên nghiệp. Mọi bước đi của họ đều được phát sóng công khai cho khán giả theo dõi và phần thưởng tiền mặt sẽ tăng lên sau mỗi ngày sống sót. Vì cần tiền cứu chữa cho cô con gái bệnh nặng, Ben...', 133, 'image/phim 3.jpg', 'https://youtu.be/NuOl156fv_c?si=98qM39lvGn18VcdI', '2025-11-19', '2025-12-31', '2D', 'Tiếng Anh', 'Phụ Đề', 'C16', 'Mỹ', 'active', '2025-11-15 22:40:20', '2025-11-19 07:29:12'),
(10, 6, 'MỘ ĐOM ĐÓM', 'Hai anh em Seita và Setsuko mất mẹ sau cuộc thả bom dữ dội của không quân Mỹ. Cả hai phải vật lộn để tồn tại ở Nhật Bản hậu Thế chiến II. Nhưng xã hội khắc nghiệt và chúng vật lộn tìm kiếm thức ăn cũng như thoát khỏi những khó khăn giữa chiến tranh.', 120, 'image/phim 6.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-17', '2025-12-31', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-17 09:58:57', '2025-11-17 19:38:42'),
(11, 2, 'Tay Anh Giữ Một Vì Sao', 'Tay Anh Giữ Một Vì Sao\" mang đến một làn gió mới trong dòng phim chiếu rạp hay khi kết hợp khéo léo giữa yếu tố hài hước và cảm xúc chân thành. Câu chuyện xoay quanh siêu sao Kang Jun Woo bỗng rơi vào chuỗi sự cố trớ trêu khiến anh vô tình \"mắc kẹt\" tại Việt Nam. Tại đây, anh gặp Thảo - cô gái bán cà phê giản dị nhưng mang trong mình khát vọng lớn lao. Những va chạm và hiểu lầm dần trở thành sợi dây gắn kết, giúp cả hai tìm thấy niềm tin, ước mơ và định nghĩa mới về tình yêu. Bộ phim không chỉ khiến khán giả bật cười bởi những tình huống duyên dáng mà còn chạm đến trái tim bằng câu chuyện nhân văn về sự đồng cảm và thay đổi.', 117, 'image/phim7.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-20', '2025-12-31', '2D', 'Tiếng Anh', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-19 09:35:10', '2025-11-19 09:35:10'),
(12, 2, 'Chị Ngã Em Nâng', 'Giữa muôn vàn phim chiếu rạp hay tháng 10, \"Chị Ngã Em Nâng\" nổi bật như một bản giao hòa đầy cảm xúc về tình thân và nghị lực con người. Bộ phim khắc họa hành trình của hai chị em Thương và Lực là những người lớn lên trong gia đình gắn bó với nghề làm nhang truyền thống. Với tuổi thơ nhiều mất mát, Thương trở thành điểm tựa duy nhất cho em trai, mang trong mình khát vọng đổi đời và niềm tin mãnh liệt vào tương lai. Thế nhưng, khi thành công đến, sự kỳ vọng và áp lực vô tình khiến tình chị em rạn nứt, đẩy họ đến những lựa chọn đau lòng. \"Chị Ngã Em Nâng\" chạm đến trái tim người xem bằng những giá trị nhân văn sâu sắc, về tình thương, sự bao dung và ý nghĩa của hai chữ \"gia đình\".', 122, 'image/hh.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-20', '2025-12-31', '3D', 'Tiếng Việt', 'Phụ Đề', 'C13', 'Việt Nam', 'active', '2025-11-19 09:40:28', '2025-11-19 09:40:28');

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
(1, 6, 'vnpay', '15270232', '2025-11-19 05:54:14', '160000.00', '0.00', '160000.00', 'failed'),
(2, 7, 'vnpay', '15270278', '2025-11-19 06:24:14', '80000.00', '0.00', '80000.00', 'paid'),
(3, 8, 'vnpay', '15270313', '2025-11-19 06:38:44', '80000.00', '0.00', '80000.00', 'paid'),
(4, NULL, 'vnpay', '15271280', '2025-11-19 15:55:34', '640000.00', '0.00', '640000.00', 'paid'),
(5, 11, 'vnpay', '15271319', '2025-11-19 16:34:32', '160000.00', '0.00', '160000.00', 'paid'),
(6, 12, 'vnpay', '15271325', '2025-11-19 16:42:48', '80000.00', '0.00', '80000.00', 'paid'),
(7, 14, 'vnpay', '15271629', '2025-11-20 02:44:19', '400000.00', '0.00', '400000.00', 'paid'),
(8, 15, 'vnpay', '15273280', '2025-11-20 12:47:13', '180000.00', '0.00', '180000.00', 'paid'),
(9, 17, 'vnpay', '15273478', '2025-11-20 14:33:33', '55000.00', '0.00', '55000.00', 'paid');

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
(105, 'staff', 14, '2025-11-18 23:16:57'),
(106, 'staff', 13, '2025-11-18 23:16:57'),
(107, 'staff', 22, '2025-11-18 23:16:57'),
(108, 'staff', 21, '2025-11-18 23:16:57'),
(109, 'staff', 24, '2025-11-18 23:16:57'),
(110, 'staff', 2, '2025-11-18 23:16:57'),
(111, 'staff', 3, '2025-11-18 23:16:57'),
(112, 'staff', 1, '2025-11-18 23:16:57'),
(113, 'staff', 12, '2025-11-18 23:16:57'),
(114, 'staff', 11, '2025-11-18 23:16:57'),
(115, 'staff', 15, '2025-11-18 23:16:57');

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
(18, 1, 'R2', 'Phòng Chiếu 2', 144),
(19, 1, 'R3', 'Phòng Chiếu 3', 168),
(21, 2, 'R1', 'Phòng Chiếu 1', 120),
(22, 2, 'R2', 'Phòng Chiếu 2', 144),
(23, 3, 'S1', 'Phòng S1', 120),
(24, 3, 'S2', 'Phòng S2', 144);

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
(3, 17, 'A', 3, 'vip', '0.00', 'available'),
(4, 17, 'A', 4, 'vip', '0.00', 'available'),
(5, 17, 'A', 5, 'vip', '0.00', 'available'),
(6, 17, 'A', 6, 'vip', '0.00', 'available'),
(7, 17, 'A', 7, 'vip', '0.00', 'available'),
(8, 17, 'A', 8, 'vip', '0.00', 'available'),
(9, 17, 'A', 9, 'vip', '0.00', 'available'),
(10, 17, 'A', 10, 'normal', '0.00', 'maintenance'),
(11, 17, 'A', 11, 'normal', NULL, 'active'),
(12, 17, 'A', 12, 'normal', '0.00', 'inactive'),
(13, 17, 'B', 1, 'normal', NULL, 'active'),
(14, 17, 'B', 2, 'normal', NULL, 'active'),
(15, 17, 'B', 3, 'vip', '0.00', 'available'),
(16, 17, 'B', 4, 'vip', '0.00', 'available'),
(17, 17, 'B', 5, 'vip', '0.00', 'available'),
(18, 17, 'B', 6, 'vip', '0.00', 'available'),
(19, 17, 'B', 7, 'vip', '0.00', 'available'),
(20, 17, 'B', 8, 'vip', '0.00', 'available'),
(21, 17, 'B', 9, 'vip', '0.00', 'available'),
(22, 17, 'B', 10, 'vip', '0.00', 'available'),
(23, 17, 'B', 11, 'normal', NULL, 'active'),
(24, 17, 'B', 12, 'normal', NULL, 'active'),
(25, 17, 'C', 1, 'VIP', '0.00', 'active'),
(26, 17, 'C', 2, 'VIP', '0.00', 'active'),
(27, 17, 'C', 3, 'normal', '0.00', 'maintenance'),
(28, 17, 'C', 4, 'vip', '0.00', 'available'),
(29, 17, 'C', 5, 'vip', '0.00', 'available'),
(30, 17, 'C', 6, 'vip', '0.00', 'available'),
(31, 17, 'C', 7, 'vip', '0.00', 'available'),
(32, 17, 'C', 8, 'vip', '0.00', 'available'),
(33, 17, 'C', 9, 'vip', '0.00', 'available'),
(34, 17, 'C', 10, 'vip', '0.00', 'available'),
(35, 17, 'C', 11, 'VIP', '0.00', 'active'),
(36, 17, 'C', 12, 'VIP', '0.00', 'active'),
(37, 17, 'D', 1, 'VIP', '0.00', 'active'),
(38, 17, 'D', 2, 'VIP', '0.00', 'active'),
(39, 17, 'D', 3, 'vip', '0.00', 'available'),
(40, 17, 'D', 4, 'vip', '0.00', 'available'),
(41, 17, 'D', 5, 'vip', '0.00', 'available'),
(42, 17, 'D', 6, 'vip', '0.00', 'available'),
(43, 17, 'D', 7, 'vip', '0.00', 'available'),
(44, 17, 'D', 8, 'vip', '0.00', 'available'),
(45, 17, 'D', 9, 'normal', '0.00', 'maintenance'),
(46, 17, 'D', 10, 'vip', '0.00', 'available'),
(47, 17, 'D', 11, 'VIP', '0.00', 'active'),
(48, 17, 'D', 12, 'VIP', '0.00', 'active'),
(49, 17, 'E', 1, 'normal', '0.00', 'inactive'),
(50, 17, 'E', 2, 'VIP', '0.00', 'active'),
(51, 17, 'E', 3, 'vip', '0.00', 'available'),
(52, 17, 'E', 4, 'vip', '0.00', 'available'),
(53, 17, 'E', 5, 'vip', '0.00', 'available'),
(54, 17, 'E', 6, 'vip', '0.00', 'available'),
(55, 17, 'E', 7, 'vip', '0.00', 'available'),
(56, 17, 'E', 8, 'vip', '0.00', 'available'),
(57, 17, 'E', 9, 'vip', '0.00', 'available'),
(58, 17, 'E', 10, 'vip', '0.00', 'available'),
(59, 17, 'E', 11, 'VIP', '0.00', 'active'),
(60, 17, 'E', 12, 'normal', '0.00', 'inactive'),
(61, 17, 'F', 1, 'normal', NULL, 'active'),
(62, 17, 'F', 2, 'VIP', '0.00', 'active'),
(63, 17, 'F', 3, 'normal', '0.00', 'available'),
(64, 17, 'F', 4, 'VIP', '0.00', 'active'),
(65, 17, 'F', 5, 'VIP', '0.00', 'active'),
(66, 17, 'F', 6, 'VIP', '0.00', 'active'),
(67, 17, 'F', 7, 'VIP', '0.00', 'active'),
(68, 17, 'F', 8, 'VIP', '0.00', 'active'),
(69, 17, 'F', 9, 'VIP', '0.00', 'active'),
(70, 17, 'F', 10, 'VIP', '0.00', 'active'),
(71, 17, 'F', 11, 'VIP', '0.00', 'active'),
(72, 17, 'F', 12, 'normal', NULL, 'active'),
(73, 17, 'G', 1, 'normal', NULL, 'active'),
(74, 17, 'G', 2, 'normal', '0.00', 'inactive'),
(75, 17, 'G', 3, 'VIP', '0.00', 'active'),
(76, 17, 'G', 4, 'VIP', '0.00', 'active'),
(77, 17, 'G', 5, 'VIP', '0.00', 'active'),
(78, 17, 'G', 6, 'VIP', '0.00', 'active'),
(79, 17, 'G', 7, 'VIP', '0.00', 'active'),
(80, 17, 'G', 8, 'VIP', '0.00', 'active'),
(81, 17, 'G', 9, 'VIP', '0.00', 'active'),
(82, 17, 'G', 10, 'VIP', '0.00', 'active'),
(83, 17, 'G', 11, 'VIP', '0.00', 'active'),
(84, 17, 'G', 12, 'normal', '0.00', 'inactive'),
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
(95, 17, 'H', 11, 'normal', '0.00', 'available'),
(96, 17, 'H', 12, 'normal', NULL, 'active'),
(97, 17, 'I', 1, 'normal', '0.00', 'inactive'),
(98, 17, 'I', 2, 'normal', '0.00', 'active'),
(99, 17, 'I', 3, 'normal', '0.00', 'active'),
(100, 17, 'I', 4, 'normal', '0.00', 'active'),
(101, 17, 'I', 5, 'normal', '0.00', 'inactive'),
(102, 17, 'I', 6, 'normal', '0.00', 'inactive'),
(103, 17, 'I', 7, 'normal', NULL, 'active'),
(104, 17, 'I', 8, 'normal', NULL, 'active'),
(105, 17, 'I', 9, 'normal', NULL, 'active'),
(106, 17, 'I', 10, 'normal', NULL, 'active'),
(107, 17, 'I', 11, 'normal', NULL, 'active'),
(108, 17, 'I', 12, 'normal', NULL, 'active'),
(109, 17, 'J', 1, 'normal', '0.00', 'inactive'),
(110, 17, 'J', 2, 'normal', '0.00', 'inactive'),
(111, 17, 'J', 3, 'normal', '0.00', 'inactive'),
(112, 17, 'J', 4, 'normal', '0.00', 'active'),
(113, 17, 'J', 5, 'normal', '0.00', 'active'),
(114, 17, 'J', 6, 'normal', '0.00', 'active'),
(115, 17, 'J', 7, 'normal', '0.00', 'active'),
(116, 17, 'J', 8, 'normal', '0.00', 'active'),
(117, 17, 'J', 9, 'normal', '0.00', 'active'),
(118, 17, 'J', 10, 'normal', '0.00', 'active'),
(119, 17, 'J', 11, 'normal', '0.00', 'inactive'),
(120, 17, 'J', 12, 'normal', '0.00', 'inactive'),
(901, 21, 'A', 1, 'normal', '0.00', 'available'),
(902, 21, 'A', 2, 'normal', '0.00', 'available'),
(903, 21, 'A', 3, 'normal', '0.00', 'available'),
(904, 21, 'A', 4, 'normal', '0.00', 'available'),
(905, 21, 'A', 5, 'normal', '0.00', 'available'),
(906, 21, 'A', 6, 'normal', '0.00', 'available'),
(907, 21, 'A', 7, 'normal', '0.00', 'available'),
(908, 21, 'A', 8, 'normal', '0.00', 'available'),
(909, 21, 'A', 9, 'normal', '0.00', 'available'),
(910, 21, 'A', 10, 'normal', '0.00', 'available'),
(911, 21, 'A', 11, 'normal', '0.00', 'available'),
(912, 21, 'A', 12, 'normal', '0.00', 'available'),
(913, 21, 'B', 1, 'normal', '0.00', 'available'),
(914, 21, 'B', 2, 'normal', '0.00', 'available'),
(915, 21, 'B', 3, 'normal', '0.00', 'available'),
(916, 21, 'B', 4, 'normal', '0.00', 'available'),
(917, 21, 'B', 5, 'normal', '0.00', 'available'),
(918, 21, 'B', 6, 'normal', '0.00', 'available'),
(919, 21, 'B', 7, 'normal', '0.00', 'available'),
(920, 21, 'B', 8, 'normal', '0.00', 'available'),
(921, 21, 'B', 9, 'normal', '0.00', 'available'),
(922, 21, 'B', 10, 'normal', '0.00', 'available'),
(923, 21, 'B', 11, 'normal', '0.00', 'available'),
(924, 21, 'B', 12, 'normal', '0.00', 'available'),
(925, 21, 'C', 1, 'normal', '0.00', 'available'),
(926, 21, 'C', 2, 'normal', '0.00', 'available'),
(927, 21, 'C', 3, 'normal', '0.00', 'available'),
(928, 21, 'C', 4, 'normal', '0.00', 'available'),
(929, 21, 'C', 5, 'normal', '0.00', 'available'),
(930, 21, 'C', 6, 'normal', '0.00', 'available'),
(931, 21, 'C', 7, 'normal', '0.00', 'available'),
(932, 21, 'C', 8, 'normal', '0.00', 'available'),
(933, 21, 'C', 9, 'normal', '0.00', 'available'),
(934, 21, 'C', 10, 'normal', '0.00', 'available'),
(935, 21, 'C', 11, 'normal', '0.00', 'available'),
(936, 21, 'C', 12, 'normal', '0.00', 'available'),
(937, 21, 'D', 1, 'normal', '0.00', 'available'),
(938, 21, 'D', 2, 'normal', '0.00', 'available'),
(939, 21, 'D', 3, 'normal', '0.00', 'available'),
(940, 21, 'D', 4, 'normal', '0.00', 'available'),
(941, 21, 'D', 5, 'normal', '0.00', 'available'),
(942, 21, 'D', 6, 'normal', '0.00', 'available'),
(943, 21, 'D', 7, 'normal', '0.00', 'available'),
(944, 21, 'D', 8, 'normal', '0.00', 'available'),
(945, 21, 'D', 9, 'normal', '0.00', 'available'),
(946, 21, 'D', 10, 'normal', '0.00', 'available'),
(947, 21, 'D', 11, 'normal', '0.00', 'available'),
(948, 21, 'D', 12, 'normal', '0.00', 'available'),
(949, 21, 'E', 1, 'normal', '0.00', 'available'),
(950, 21, 'E', 2, 'normal', '0.00', 'available'),
(951, 21, 'E', 3, 'normal', '0.00', 'available'),
(952, 21, 'E', 4, 'normal', '0.00', 'available'),
(953, 21, 'E', 5, 'normal', '0.00', 'available'),
(954, 21, 'E', 6, 'normal', '0.00', 'available'),
(955, 21, 'E', 7, 'normal', '0.00', 'available'),
(956, 21, 'E', 8, 'normal', '0.00', 'available'),
(957, 21, 'E', 9, 'normal', '0.00', 'available'),
(958, 21, 'E', 10, 'normal', '0.00', 'available'),
(959, 21, 'E', 11, 'normal', '0.00', 'available'),
(960, 21, 'E', 12, 'normal', '0.00', 'available'),
(961, 21, 'F', 1, 'normal', '0.00', 'available'),
(962, 21, 'F', 2, 'normal', '0.00', 'available'),
(963, 21, 'F', 3, 'normal', '0.00', 'available'),
(964, 21, 'F', 4, 'normal', '0.00', 'available'),
(965, 21, 'F', 5, 'normal', '0.00', 'available'),
(966, 21, 'F', 6, 'normal', '0.00', 'available'),
(967, 21, 'F', 7, 'normal', '0.00', 'available'),
(968, 21, 'F', 8, 'normal', '0.00', 'available'),
(969, 21, 'F', 9, 'normal', '0.00', 'available'),
(970, 21, 'F', 10, 'normal', '0.00', 'available'),
(971, 21, 'F', 11, 'normal', '0.00', 'available'),
(972, 21, 'F', 12, 'normal', '0.00', 'available'),
(973, 21, 'G', 1, 'normal', '0.00', 'available'),
(974, 21, 'G', 2, 'normal', '0.00', 'available'),
(975, 21, 'G', 3, 'normal', '0.00', 'available'),
(976, 21, 'G', 4, 'normal', '0.00', 'available'),
(977, 21, 'G', 5, 'normal', '0.00', 'available'),
(978, 21, 'G', 6, 'normal', '0.00', 'available'),
(979, 21, 'G', 7, 'normal', '0.00', 'available'),
(980, 21, 'G', 8, 'normal', '0.00', 'available'),
(981, 21, 'G', 9, 'normal', '0.00', 'available'),
(982, 21, 'G', 10, 'normal', '0.00', 'available'),
(983, 21, 'G', 11, 'normal', '0.00', 'available'),
(984, 21, 'G', 12, 'normal', '0.00', 'available'),
(985, 21, 'H', 1, 'normal', '0.00', 'available'),
(986, 21, 'H', 2, 'normal', '0.00', 'available'),
(987, 21, 'H', 3, 'normal', '0.00', 'available'),
(988, 21, 'H', 4, 'normal', '0.00', 'available'),
(989, 21, 'H', 5, 'normal', '0.00', 'available'),
(990, 21, 'H', 6, 'normal', '0.00', 'available'),
(991, 21, 'H', 7, 'normal', '0.00', 'available'),
(992, 21, 'H', 8, 'normal', '0.00', 'available'),
(993, 21, 'H', 9, 'normal', '0.00', 'available'),
(994, 21, 'H', 10, 'normal', '0.00', 'available'),
(995, 21, 'H', 11, 'normal', '0.00', 'available'),
(996, 21, 'H', 12, 'normal', '0.00', 'available'),
(997, 21, 'I', 1, 'normal', '0.00', 'available'),
(998, 21, 'I', 2, 'normal', '0.00', 'available'),
(999, 21, 'I', 3, 'normal', '0.00', 'available'),
(1000, 21, 'I', 4, 'normal', '0.00', 'available'),
(1001, 21, 'I', 5, 'normal', '0.00', 'available'),
(1002, 21, 'I', 6, 'normal', '0.00', 'available'),
(1003, 21, 'I', 7, 'normal', '0.00', 'available'),
(1004, 21, 'I', 8, 'normal', '0.00', 'available'),
(1005, 21, 'I', 9, 'normal', '0.00', 'available'),
(1006, 21, 'I', 10, 'normal', '0.00', 'available'),
(1007, 21, 'I', 11, 'normal', '0.00', 'available'),
(1008, 21, 'I', 12, 'normal', '0.00', 'available'),
(1009, 21, 'J', 1, 'normal', '0.00', 'available'),
(1010, 21, 'J', 2, 'normal', '0.00', 'available'),
(1011, 21, 'J', 3, 'normal', '0.00', 'available'),
(1012, 21, 'J', 4, 'normal', '0.00', 'available'),
(1013, 21, 'J', 5, 'normal', '0.00', 'available'),
(1014, 21, 'J', 6, 'normal', '0.00', 'available'),
(1015, 21, 'J', 7, 'normal', '0.00', 'available'),
(1016, 21, 'J', 8, 'normal', '0.00', 'available'),
(1017, 21, 'J', 9, 'normal', '0.00', 'available'),
(1018, 21, 'J', 10, 'normal', '0.00', 'available'),
(1019, 21, 'J', 11, 'normal', '0.00', 'available'),
(1020, 21, 'J', 12, 'normal', '0.00', 'available'),
(1791, 18, 'A', 1, 'normal', '0.00', 'available'),
(1792, 18, 'A', 2, 'normal', '0.00', 'available'),
(1793, 18, 'A', 3, 'normal', '0.00', 'available'),
(1794, 18, 'A', 4, 'normal', '0.00', 'available'),
(1795, 18, 'A', 5, 'normal', '0.00', 'available'),
(1796, 18, 'A', 6, 'normal', '0.00', 'available'),
(1797, 18, 'A', 7, 'normal', '0.00', 'available'),
(1798, 18, 'A', 8, 'normal', '0.00', 'available'),
(1799, 18, 'A', 9, 'normal', '0.00', 'available'),
(1800, 18, 'A', 10, 'normal', '0.00', 'available'),
(1801, 18, 'A', 11, 'normal', '0.00', 'available'),
(1802, 18, 'A', 12, 'normal', '0.00', 'available'),
(1803, 18, 'B', 1, 'normal', '0.00', 'available'),
(1804, 18, 'B', 2, 'normal', '0.00', 'available'),
(1805, 18, 'B', 3, 'normal', '0.00', 'available'),
(1806, 18, 'B', 4, 'normal', '0.00', 'available'),
(1807, 18, 'B', 5, 'normal', '0.00', 'available'),
(1808, 18, 'B', 6, 'normal', '0.00', 'available'),
(1809, 18, 'B', 7, 'normal', '0.00', 'available'),
(1810, 18, 'B', 8, 'normal', '0.00', 'available'),
(1811, 18, 'B', 9, 'normal', '0.00', 'available'),
(1812, 18, 'B', 10, 'normal', '0.00', 'available'),
(1813, 18, 'B', 11, 'normal', '0.00', 'available'),
(1814, 18, 'B', 12, 'normal', '0.00', 'available'),
(1815, 18, 'C', 1, 'normal', '0.00', 'available'),
(1816, 18, 'C', 2, 'normal', '0.00', 'available'),
(1817, 18, 'C', 3, 'normal', '0.00', 'available'),
(1818, 18, 'C', 4, 'normal', '0.00', 'available'),
(1819, 18, 'C', 5, 'normal', '0.00', 'available'),
(1820, 18, 'C', 6, 'normal', '0.00', 'available'),
(1821, 18, 'C', 7, 'normal', '0.00', 'available'),
(1822, 18, 'C', 8, 'normal', '0.00', 'available'),
(1823, 18, 'C', 9, 'normal', '0.00', 'available'),
(1824, 18, 'C', 10, 'normal', '0.00', 'available'),
(1825, 18, 'C', 11, 'normal', '0.00', 'available'),
(1826, 18, 'C', 12, 'normal', '0.00', 'available'),
(1827, 18, 'D', 1, 'normal', '0.00', 'available'),
(1828, 18, 'D', 2, 'normal', '0.00', 'available'),
(1829, 18, 'D', 3, 'normal', '0.00', 'available'),
(1830, 18, 'D', 4, 'normal', '0.00', 'available'),
(1831, 18, 'D', 5, 'normal', '0.00', 'available'),
(1832, 18, 'D', 6, 'normal', '0.00', 'available'),
(1833, 18, 'D', 7, 'normal', '0.00', 'available'),
(1834, 18, 'D', 8, 'normal', '0.00', 'available'),
(1835, 18, 'D', 9, 'normal', '0.00', 'available'),
(1836, 18, 'D', 10, 'normal', '0.00', 'available'),
(1837, 18, 'D', 11, 'normal', '0.00', 'available'),
(1838, 18, 'D', 12, 'normal', '0.00', 'available'),
(1839, 18, 'E', 1, 'normal', '0.00', 'available'),
(1840, 18, 'E', 2, 'normal', '0.00', 'available'),
(1841, 18, 'E', 3, 'normal', '0.00', 'available'),
(1842, 18, 'E', 4, 'normal', '0.00', 'available'),
(1843, 18, 'E', 5, 'normal', '0.00', 'available'),
(1844, 18, 'E', 6, 'normal', '0.00', 'available'),
(1845, 18, 'E', 7, 'normal', '0.00', 'available'),
(1846, 18, 'E', 8, 'normal', '0.00', 'available'),
(1847, 18, 'E', 9, 'normal', '0.00', 'available'),
(1848, 18, 'E', 10, 'normal', '0.00', 'available'),
(1849, 18, 'E', 11, 'normal', '0.00', 'available'),
(1850, 18, 'E', 12, 'normal', '0.00', 'available'),
(1851, 18, 'F', 1, 'normal', '0.00', 'available'),
(1852, 18, 'F', 2, 'normal', '0.00', 'available'),
(1853, 18, 'F', 3, 'normal', '0.00', 'available'),
(1854, 18, 'F', 4, 'normal', '0.00', 'available'),
(1855, 18, 'F', 5, 'normal', '0.00', 'available'),
(1856, 18, 'F', 6, 'normal', '0.00', 'available'),
(1857, 18, 'F', 7, 'normal', '0.00', 'available'),
(1858, 18, 'F', 8, 'normal', '0.00', 'available'),
(1859, 18, 'F', 9, 'normal', '0.00', 'available'),
(1860, 18, 'F', 10, 'normal', '0.00', 'available'),
(1861, 18, 'F', 11, 'normal', '0.00', 'available'),
(1862, 18, 'F', 12, 'normal', '0.00', 'available'),
(1863, 18, 'G', 1, 'normal', '0.00', 'available'),
(1864, 18, 'G', 2, 'normal', '0.00', 'available'),
(1865, 18, 'G', 3, 'normal', '0.00', 'available'),
(1866, 18, 'G', 4, 'normal', '0.00', 'available'),
(1867, 18, 'G', 5, 'normal', '0.00', 'available'),
(1868, 18, 'G', 6, 'normal', '0.00', 'available'),
(1869, 18, 'G', 7, 'normal', '0.00', 'available'),
(1870, 18, 'G', 8, 'normal', '0.00', 'available'),
(1871, 18, 'G', 9, 'normal', '0.00', 'available'),
(1872, 18, 'G', 10, 'normal', '0.00', 'available'),
(1873, 18, 'G', 11, 'normal', '0.00', 'available'),
(1874, 18, 'G', 12, 'normal', '0.00', 'available'),
(1875, 18, 'H', 1, 'normal', '0.00', 'available'),
(1876, 18, 'H', 2, 'normal', '0.00', 'available'),
(1877, 18, 'H', 3, 'normal', '0.00', 'available'),
(1878, 18, 'H', 4, 'normal', '0.00', 'available'),
(1879, 18, 'H', 5, 'normal', '0.00', 'available'),
(1880, 18, 'H', 6, 'normal', '0.00', 'available'),
(1881, 18, 'H', 7, 'normal', '0.00', 'available'),
(1882, 18, 'H', 8, 'normal', '0.00', 'available'),
(1883, 18, 'H', 9, 'normal', '0.00', 'available'),
(1884, 18, 'H', 10, 'normal', '0.00', 'available'),
(1885, 18, 'H', 11, 'normal', '0.00', 'available'),
(1886, 18, 'H', 12, 'normal', '0.00', 'available'),
(1887, 18, 'I', 1, 'normal', '0.00', 'available'),
(1888, 18, 'I', 2, 'normal', '0.00', 'available'),
(1889, 18, 'I', 3, 'normal', '0.00', 'available'),
(1890, 18, 'I', 4, 'normal', '0.00', 'available'),
(1891, 18, 'I', 5, 'normal', '0.00', 'available'),
(1892, 18, 'I', 6, 'normal', '0.00', 'available'),
(1893, 18, 'I', 7, 'normal', '0.00', 'available'),
(1894, 18, 'I', 8, 'normal', '0.00', 'available'),
(1895, 18, 'I', 9, 'normal', '0.00', 'available'),
(1896, 18, 'I', 10, 'normal', '0.00', 'available'),
(1897, 18, 'I', 11, 'normal', '0.00', 'available'),
(1898, 18, 'I', 12, 'normal', '0.00', 'available'),
(1899, 18, 'J', 1, 'normal', '0.00', 'available'),
(1900, 18, 'J', 2, 'normal', '0.00', 'available'),
(1901, 18, 'J', 3, 'normal', '0.00', 'available'),
(1902, 18, 'J', 4, 'normal', '0.00', 'available'),
(1903, 18, 'J', 5, 'normal', '0.00', 'available'),
(1904, 18, 'J', 6, 'normal', '0.00', 'available'),
(1905, 18, 'J', 7, 'normal', '0.00', 'available'),
(1906, 18, 'J', 8, 'normal', '0.00', 'available'),
(1907, 18, 'J', 9, 'normal', '0.00', 'available'),
(1908, 18, 'J', 10, 'normal', '0.00', 'available'),
(1909, 18, 'J', 11, 'normal', '0.00', 'available'),
(1910, 18, 'J', 12, 'normal', '0.00', 'available'),
(1911, 18, 'K', 1, 'normal', '0.00', 'available'),
(1912, 18, 'K', 2, 'normal', '0.00', 'available'),
(1913, 18, 'K', 3, 'normal', '0.00', 'available'),
(1914, 18, 'K', 4, 'normal', '0.00', 'available'),
(1915, 18, 'K', 5, 'normal', '0.00', 'available'),
(1916, 18, 'K', 6, 'normal', '0.00', 'available'),
(1917, 18, 'K', 7, 'normal', '0.00', 'available'),
(1918, 18, 'K', 8, 'normal', '0.00', 'available'),
(1919, 18, 'K', 9, 'normal', '0.00', 'available'),
(1920, 18, 'K', 10, 'normal', '0.00', 'available'),
(1921, 18, 'K', 11, 'normal', '0.00', 'available'),
(1922, 18, 'K', 12, 'normal', '0.00', 'available'),
(1923, 18, 'L', 1, 'normal', '0.00', 'available'),
(1924, 18, 'L', 2, 'normal', '0.00', 'available'),
(1925, 18, 'L', 3, 'normal', '0.00', 'available'),
(1926, 18, 'L', 4, 'normal', '0.00', 'available'),
(1927, 18, 'L', 5, 'normal', '0.00', 'available'),
(1928, 18, 'L', 6, 'normal', '0.00', 'available'),
(1929, 18, 'L', 7, 'normal', '0.00', 'available'),
(1930, 18, 'L', 8, 'normal', '0.00', 'available'),
(1931, 18, 'L', 9, 'normal', '0.00', 'available'),
(1932, 18, 'L', 10, 'normal', '0.00', 'available'),
(1933, 18, 'L', 11, 'normal', '0.00', 'available'),
(1934, 18, 'L', 12, 'normal', '0.00', 'available'),
(1935, 19, 'A', 1, 'normal', '0.00', 'available'),
(1936, 19, 'A', 2, 'normal', '0.00', 'available'),
(1937, 19, 'A', 3, 'normal', '0.00', 'available'),
(1938, 19, 'A', 4, 'normal', '0.00', 'available'),
(1939, 19, 'A', 5, 'normal', '0.00', 'available'),
(1940, 19, 'A', 6, 'normal', '0.00', 'available'),
(1941, 19, 'A', 7, 'normal', '0.00', 'available'),
(1942, 19, 'A', 8, 'normal', '0.00', 'available'),
(1943, 19, 'A', 9, 'normal', '0.00', 'available'),
(1944, 19, 'A', 10, 'normal', '0.00', 'available'),
(1945, 19, 'A', 11, 'normal', '0.00', 'available'),
(1946, 19, 'A', 12, 'normal', '0.00', 'available'),
(1947, 19, 'B', 1, 'normal', '0.00', 'available'),
(1948, 19, 'B', 2, 'normal', '0.00', 'available'),
(1949, 19, 'B', 3, 'normal', '0.00', 'available'),
(1950, 19, 'B', 4, 'normal', '0.00', 'available'),
(1951, 19, 'B', 5, 'normal', '0.00', 'available'),
(1952, 19, 'B', 6, 'normal', '0.00', 'available'),
(1953, 19, 'B', 7, 'normal', '0.00', 'available'),
(1954, 19, 'B', 8, 'normal', '0.00', 'available'),
(1955, 19, 'B', 9, 'normal', '0.00', 'available'),
(1956, 19, 'B', 10, 'normal', '0.00', 'available'),
(1957, 19, 'B', 11, 'normal', '0.00', 'available'),
(1958, 19, 'B', 12, 'normal', '0.00', 'available'),
(1959, 19, 'C', 1, 'normal', '0.00', 'available'),
(1960, 19, 'C', 2, 'normal', '0.00', 'available'),
(1961, 19, 'C', 3, 'normal', '0.00', 'available'),
(1962, 19, 'C', 4, 'normal', '0.00', 'available'),
(1963, 19, 'C', 5, 'normal', '0.00', 'available'),
(1964, 19, 'C', 6, 'normal', '0.00', 'available'),
(1965, 19, 'C', 7, 'normal', '0.00', 'available'),
(1966, 19, 'C', 8, 'normal', '0.00', 'available'),
(1967, 19, 'C', 9, 'normal', '0.00', 'available'),
(1968, 19, 'C', 10, 'normal', '0.00', 'available'),
(1969, 19, 'C', 11, 'normal', '0.00', 'available'),
(1970, 19, 'C', 12, 'normal', '0.00', 'available'),
(1971, 19, 'D', 1, 'normal', '0.00', 'available'),
(1972, 19, 'D', 2, 'normal', '0.00', 'available'),
(1973, 19, 'D', 3, 'normal', '0.00', 'available'),
(1974, 19, 'D', 4, 'normal', '0.00', 'available'),
(1975, 19, 'D', 5, 'normal', '0.00', 'available'),
(1976, 19, 'D', 6, 'normal', '0.00', 'available'),
(1977, 19, 'D', 7, 'normal', '0.00', 'available'),
(1978, 19, 'D', 8, 'normal', '0.00', 'available'),
(1979, 19, 'D', 9, 'normal', '0.00', 'available'),
(1980, 19, 'D', 10, 'normal', '0.00', 'available'),
(1981, 19, 'D', 11, 'normal', '0.00', 'available'),
(1982, 19, 'D', 12, 'normal', '0.00', 'available'),
(1983, 19, 'E', 1, 'normal', '0.00', 'available'),
(1984, 19, 'E', 2, 'normal', '0.00', 'available'),
(1985, 19, 'E', 3, 'normal', '0.00', 'available'),
(1986, 19, 'E', 4, 'normal', '0.00', 'available'),
(1987, 19, 'E', 5, 'normal', '0.00', 'available'),
(1988, 19, 'E', 6, 'normal', '0.00', 'available'),
(1989, 19, 'E', 7, 'normal', '0.00', 'available'),
(1990, 19, 'E', 8, 'normal', '0.00', 'available'),
(1991, 19, 'E', 9, 'normal', '0.00', 'available'),
(1992, 19, 'E', 10, 'normal', '0.00', 'available'),
(1993, 19, 'E', 11, 'normal', '0.00', 'available'),
(1994, 19, 'E', 12, 'normal', '0.00', 'available'),
(1995, 19, 'F', 1, 'normal', '0.00', 'available'),
(1996, 19, 'F', 2, 'normal', '0.00', 'available'),
(1997, 19, 'F', 3, 'normal', '0.00', 'available'),
(1998, 19, 'F', 4, 'normal', '0.00', 'available'),
(1999, 19, 'F', 5, 'normal', '0.00', 'available'),
(2000, 19, 'F', 6, 'normal', '0.00', 'available'),
(2001, 19, 'F', 7, 'normal', '0.00', 'available'),
(2002, 19, 'F', 8, 'normal', '0.00', 'available'),
(2003, 19, 'F', 9, 'normal', '0.00', 'available'),
(2004, 19, 'F', 10, 'normal', '0.00', 'available'),
(2005, 19, 'F', 11, 'normal', '0.00', 'available'),
(2006, 19, 'F', 12, 'normal', '0.00', 'available'),
(2007, 19, 'G', 1, 'normal', '0.00', 'available'),
(2008, 19, 'G', 2, 'normal', '0.00', 'available'),
(2009, 19, 'G', 3, 'normal', '0.00', 'available'),
(2010, 19, 'G', 4, 'normal', '0.00', 'available'),
(2011, 19, 'G', 5, 'normal', '0.00', 'available'),
(2012, 19, 'G', 6, 'normal', '0.00', 'available'),
(2013, 19, 'G', 7, 'normal', '0.00', 'available'),
(2014, 19, 'G', 8, 'normal', '0.00', 'available'),
(2015, 19, 'G', 9, 'normal', '0.00', 'available'),
(2016, 19, 'G', 10, 'normal', '0.00', 'available'),
(2017, 19, 'G', 11, 'normal', '0.00', 'available'),
(2018, 19, 'G', 12, 'normal', '0.00', 'available'),
(2019, 19, 'H', 1, 'normal', '0.00', 'available'),
(2020, 19, 'H', 2, 'normal', '0.00', 'available'),
(2021, 19, 'H', 3, 'normal', '0.00', 'available'),
(2022, 19, 'H', 4, 'normal', '0.00', 'available'),
(2023, 19, 'H', 5, 'normal', '0.00', 'available'),
(2024, 19, 'H', 6, 'normal', '0.00', 'available'),
(2025, 19, 'H', 7, 'normal', '0.00', 'available'),
(2026, 19, 'H', 8, 'normal', '0.00', 'available'),
(2027, 19, 'H', 9, 'normal', '0.00', 'available'),
(2028, 19, 'H', 10, 'normal', '0.00', 'available'),
(2029, 19, 'H', 11, 'normal', '0.00', 'available'),
(2030, 19, 'H', 12, 'normal', '0.00', 'available'),
(2031, 19, 'I', 1, 'normal', '0.00', 'available'),
(2032, 19, 'I', 2, 'normal', '0.00', 'available'),
(2033, 19, 'I', 3, 'normal', '0.00', 'available'),
(2034, 19, 'I', 4, 'normal', '0.00', 'available'),
(2035, 19, 'I', 5, 'normal', '0.00', 'available'),
(2036, 19, 'I', 6, 'normal', '0.00', 'available'),
(2037, 19, 'I', 7, 'normal', '0.00', 'available'),
(2038, 19, 'I', 8, 'normal', '0.00', 'available'),
(2039, 19, 'I', 9, 'normal', '0.00', 'available'),
(2040, 19, 'I', 10, 'normal', '0.00', 'available'),
(2041, 19, 'I', 11, 'normal', '0.00', 'available'),
(2042, 19, 'I', 12, 'normal', '0.00', 'available'),
(2043, 19, 'J', 1, 'normal', '0.00', 'available'),
(2044, 19, 'J', 2, 'normal', '0.00', 'available'),
(2045, 19, 'J', 3, 'normal', '0.00', 'available'),
(2046, 19, 'J', 4, 'normal', '0.00', 'available'),
(2047, 19, 'J', 5, 'normal', '0.00', 'available'),
(2048, 19, 'J', 6, 'normal', '0.00', 'available'),
(2049, 19, 'J', 7, 'normal', '0.00', 'available'),
(2050, 19, 'J', 8, 'normal', '0.00', 'available'),
(2051, 19, 'J', 9, 'normal', '0.00', 'available'),
(2052, 19, 'J', 10, 'normal', '0.00', 'available'),
(2053, 19, 'J', 11, 'normal', '0.00', 'available'),
(2054, 19, 'J', 12, 'normal', '0.00', 'available'),
(2055, 19, 'K', 1, 'normal', '0.00', 'available'),
(2056, 19, 'K', 2, 'normal', '0.00', 'available'),
(2057, 19, 'K', 3, 'normal', '0.00', 'available'),
(2058, 19, 'K', 4, 'normal', '0.00', 'available'),
(2059, 19, 'K', 5, 'normal', '0.00', 'available'),
(2060, 19, 'K', 6, 'normal', '0.00', 'available'),
(2061, 19, 'K', 7, 'normal', '0.00', 'available'),
(2062, 19, 'K', 8, 'normal', '0.00', 'available'),
(2063, 19, 'K', 9, 'normal', '0.00', 'available'),
(2064, 19, 'K', 10, 'normal', '0.00', 'available'),
(2065, 19, 'K', 11, 'normal', '0.00', 'available'),
(2066, 19, 'K', 12, 'normal', '0.00', 'available'),
(2067, 19, 'L', 1, 'normal', '0.00', 'available'),
(2068, 19, 'L', 2, 'normal', '0.00', 'available'),
(2069, 19, 'L', 3, 'normal', '0.00', 'available'),
(2070, 19, 'L', 4, 'normal', '0.00', 'available'),
(2071, 19, 'L', 5, 'normal', '0.00', 'available'),
(2072, 19, 'L', 6, 'normal', '0.00', 'available'),
(2073, 19, 'L', 7, 'normal', '0.00', 'available'),
(2074, 19, 'L', 8, 'normal', '0.00', 'available'),
(2075, 19, 'L', 9, 'normal', '0.00', 'available'),
(2076, 19, 'L', 10, 'normal', '0.00', 'available'),
(2077, 19, 'L', 11, 'normal', '0.00', 'available'),
(2078, 19, 'L', 12, 'normal', '0.00', 'available'),
(2079, 19, 'M', 1, 'normal', '0.00', 'available'),
(2080, 19, 'M', 2, 'normal', '0.00', 'available'),
(2081, 19, 'M', 3, 'normal', '0.00', 'available'),
(2082, 19, 'M', 4, 'normal', '0.00', 'available'),
(2083, 19, 'M', 5, 'normal', '0.00', 'available'),
(2084, 19, 'M', 6, 'normal', '0.00', 'available'),
(2085, 19, 'M', 7, 'normal', '0.00', 'available'),
(2086, 19, 'M', 8, 'normal', '0.00', 'available'),
(2087, 19, 'M', 9, 'normal', '0.00', 'available'),
(2088, 19, 'M', 10, 'normal', '0.00', 'available'),
(2089, 19, 'M', 11, 'normal', '0.00', 'available'),
(2090, 19, 'M', 12, 'normal', '0.00', 'available'),
(2091, 19, 'N', 1, 'normal', '0.00', 'available'),
(2092, 19, 'N', 2, 'normal', '0.00', 'available'),
(2093, 19, 'N', 3, 'normal', '0.00', 'available'),
(2094, 19, 'N', 4, 'normal', '0.00', 'available'),
(2095, 19, 'N', 5, 'normal', '0.00', 'available'),
(2096, 19, 'N', 6, 'normal', '0.00', 'available'),
(2097, 19, 'N', 7, 'normal', '0.00', 'available'),
(2098, 19, 'N', 8, 'normal', '0.00', 'available'),
(2099, 19, 'N', 9, 'normal', '0.00', 'available'),
(2100, 19, 'N', 10, 'normal', '0.00', 'available'),
(2101, 19, 'N', 11, 'normal', '0.00', 'available'),
(2102, 19, 'N', 12, 'normal', '0.00', 'available'),
(2103, 22, 'A', 1, 'normal', '0.00', 'available'),
(2104, 22, 'A', 2, 'normal', '0.00', 'available'),
(2105, 22, 'A', 3, 'normal', '0.00', 'available'),
(2106, 22, 'A', 4, 'normal', '0.00', 'available'),
(2107, 22, 'A', 5, 'normal', '0.00', 'available'),
(2108, 22, 'A', 6, 'normal', '0.00', 'available'),
(2109, 22, 'A', 7, 'normal', '0.00', 'available'),
(2110, 22, 'A', 8, 'normal', '0.00', 'available'),
(2111, 22, 'A', 9, 'normal', '0.00', 'available'),
(2112, 22, 'A', 10, 'normal', '0.00', 'available'),
(2113, 22, 'A', 11, 'normal', '0.00', 'available'),
(2114, 22, 'A', 12, 'normal', '0.00', 'available'),
(2115, 22, 'B', 1, 'normal', '0.00', 'available'),
(2116, 22, 'B', 2, 'normal', '0.00', 'available'),
(2117, 22, 'B', 3, 'normal', '0.00', 'available'),
(2118, 22, 'B', 4, 'normal', '0.00', 'available'),
(2119, 22, 'B', 5, 'normal', '0.00', 'available'),
(2120, 22, 'B', 6, 'normal', '0.00', 'available'),
(2121, 22, 'B', 7, 'normal', '0.00', 'available'),
(2122, 22, 'B', 8, 'normal', '0.00', 'available'),
(2123, 22, 'B', 9, 'normal', '0.00', 'available'),
(2124, 22, 'B', 10, 'normal', '0.00', 'available'),
(2125, 22, 'B', 11, 'normal', '0.00', 'available'),
(2126, 22, 'B', 12, 'normal', '0.00', 'available'),
(2127, 22, 'C', 1, 'normal', '0.00', 'available'),
(2128, 22, 'C', 2, 'normal', '0.00', 'available'),
(2129, 22, 'C', 3, 'normal', '0.00', 'available'),
(2130, 22, 'C', 4, 'normal', '0.00', 'available'),
(2131, 22, 'C', 5, 'normal', '0.00', 'available'),
(2132, 22, 'C', 6, 'normal', '0.00', 'available'),
(2133, 22, 'C', 7, 'normal', '0.00', 'available'),
(2134, 22, 'C', 8, 'normal', '0.00', 'available'),
(2135, 22, 'C', 9, 'normal', '0.00', 'available'),
(2136, 22, 'C', 10, 'normal', '0.00', 'available'),
(2137, 22, 'C', 11, 'normal', '0.00', 'available'),
(2138, 22, 'C', 12, 'normal', '0.00', 'available'),
(2139, 22, 'D', 1, 'normal', '0.00', 'available'),
(2140, 22, 'D', 2, 'normal', '0.00', 'available'),
(2141, 22, 'D', 3, 'normal', '0.00', 'available'),
(2142, 22, 'D', 4, 'normal', '0.00', 'available'),
(2143, 22, 'D', 5, 'normal', '0.00', 'available'),
(2144, 22, 'D', 6, 'normal', '0.00', 'available'),
(2145, 22, 'D', 7, 'normal', '0.00', 'available'),
(2146, 22, 'D', 8, 'normal', '0.00', 'available'),
(2147, 22, 'D', 9, 'normal', '0.00', 'available'),
(2148, 22, 'D', 10, 'normal', '0.00', 'available'),
(2149, 22, 'D', 11, 'normal', '0.00', 'available'),
(2150, 22, 'D', 12, 'normal', '0.00', 'available'),
(2151, 22, 'E', 1, 'normal', '0.00', 'available'),
(2152, 22, 'E', 2, 'normal', '0.00', 'available'),
(2153, 22, 'E', 3, 'normal', '0.00', 'available'),
(2154, 22, 'E', 4, 'normal', '0.00', 'available'),
(2155, 22, 'E', 5, 'normal', '0.00', 'available'),
(2156, 22, 'E', 6, 'normal', '0.00', 'available'),
(2157, 22, 'E', 7, 'normal', '0.00', 'available'),
(2158, 22, 'E', 8, 'normal', '0.00', 'available'),
(2159, 22, 'E', 9, 'normal', '0.00', 'available'),
(2160, 22, 'E', 10, 'normal', '0.00', 'available'),
(2161, 22, 'E', 11, 'normal', '0.00', 'available'),
(2162, 22, 'E', 12, 'normal', '0.00', 'available'),
(2163, 22, 'F', 1, 'normal', '0.00', 'available'),
(2164, 22, 'F', 2, 'normal', '0.00', 'available'),
(2165, 22, 'F', 3, 'normal', '0.00', 'available'),
(2166, 22, 'F', 4, 'normal', '0.00', 'available'),
(2167, 22, 'F', 5, 'normal', '0.00', 'available'),
(2168, 22, 'F', 6, 'normal', '0.00', 'available'),
(2169, 22, 'F', 7, 'normal', '0.00', 'available'),
(2170, 22, 'F', 8, 'normal', '0.00', 'available'),
(2171, 22, 'F', 9, 'normal', '0.00', 'available'),
(2172, 22, 'F', 10, 'normal', '0.00', 'available'),
(2173, 22, 'F', 11, 'normal', '0.00', 'available'),
(2174, 22, 'F', 12, 'normal', '0.00', 'available'),
(2175, 22, 'G', 1, 'normal', '0.00', 'available'),
(2176, 22, 'G', 2, 'normal', '0.00', 'available'),
(2177, 22, 'G', 3, 'normal', '0.00', 'available'),
(2178, 22, 'G', 4, 'normal', '0.00', 'available'),
(2179, 22, 'G', 5, 'normal', '0.00', 'available'),
(2180, 22, 'G', 6, 'normal', '0.00', 'available'),
(2181, 22, 'G', 7, 'normal', '0.00', 'available'),
(2182, 22, 'G', 8, 'normal', '0.00', 'available'),
(2183, 22, 'G', 9, 'normal', '0.00', 'available'),
(2184, 22, 'G', 10, 'normal', '0.00', 'available'),
(2185, 22, 'G', 11, 'normal', '0.00', 'available'),
(2186, 22, 'G', 12, 'normal', '0.00', 'available'),
(2187, 22, 'H', 1, 'normal', '0.00', 'available'),
(2188, 22, 'H', 2, 'normal', '0.00', 'available'),
(2189, 22, 'H', 3, 'normal', '0.00', 'available'),
(2190, 22, 'H', 4, 'normal', '0.00', 'available'),
(2191, 22, 'H', 5, 'normal', '0.00', 'available'),
(2192, 22, 'H', 6, 'normal', '0.00', 'available'),
(2193, 22, 'H', 7, 'normal', '0.00', 'available'),
(2194, 22, 'H', 8, 'normal', '0.00', 'available'),
(2195, 22, 'H', 9, 'normal', '0.00', 'available'),
(2196, 22, 'H', 10, 'normal', '0.00', 'available'),
(2197, 22, 'H', 11, 'normal', '0.00', 'available'),
(2198, 22, 'H', 12, 'normal', '0.00', 'available'),
(2199, 22, 'I', 1, 'normal', '0.00', 'available'),
(2200, 22, 'I', 2, 'normal', '0.00', 'available'),
(2201, 22, 'I', 3, 'normal', '0.00', 'available'),
(2202, 22, 'I', 4, 'normal', '0.00', 'available'),
(2203, 22, 'I', 5, 'normal', '0.00', 'available'),
(2204, 22, 'I', 6, 'normal', '0.00', 'available'),
(2205, 22, 'I', 7, 'normal', '0.00', 'available'),
(2206, 22, 'I', 8, 'normal', '0.00', 'available'),
(2207, 22, 'I', 9, 'normal', '0.00', 'available'),
(2208, 22, 'I', 10, 'normal', '0.00', 'available'),
(2209, 22, 'I', 11, 'normal', '0.00', 'available'),
(2210, 22, 'I', 12, 'normal', '0.00', 'available'),
(2211, 22, 'J', 1, 'normal', '0.00', 'available'),
(2212, 22, 'J', 2, 'normal', '0.00', 'available'),
(2213, 22, 'J', 3, 'normal', '0.00', 'available'),
(2214, 22, 'J', 4, 'normal', '0.00', 'available'),
(2215, 22, 'J', 5, 'normal', '0.00', 'available'),
(2216, 22, 'J', 6, 'normal', '0.00', 'available'),
(2217, 22, 'J', 7, 'normal', '0.00', 'available'),
(2218, 22, 'J', 8, 'normal', '0.00', 'available'),
(2219, 22, 'J', 9, 'normal', '0.00', 'available'),
(2220, 22, 'J', 10, 'normal', '0.00', 'available'),
(2221, 22, 'J', 11, 'normal', '0.00', 'available'),
(2222, 22, 'J', 12, 'normal', '0.00', 'available'),
(2223, 22, 'K', 1, 'normal', '0.00', 'available'),
(2224, 22, 'K', 2, 'normal', '0.00', 'available'),
(2225, 22, 'K', 3, 'normal', '0.00', 'available'),
(2226, 22, 'K', 4, 'normal', '0.00', 'available'),
(2227, 22, 'K', 5, 'normal', '0.00', 'available'),
(2228, 22, 'K', 6, 'normal', '0.00', 'available'),
(2229, 22, 'K', 7, 'normal', '0.00', 'available'),
(2230, 22, 'K', 8, 'normal', '0.00', 'available'),
(2231, 22, 'K', 9, 'normal', '0.00', 'available'),
(2232, 22, 'K', 10, 'normal', '0.00', 'available'),
(2233, 22, 'K', 11, 'normal', '0.00', 'available'),
(2234, 22, 'K', 12, 'normal', '0.00', 'available'),
(2235, 22, 'L', 1, 'normal', '0.00', 'available'),
(2236, 22, 'L', 2, 'normal', '0.00', 'available'),
(2237, 22, 'L', 3, 'normal', '0.00', 'available'),
(2238, 22, 'L', 4, 'normal', '0.00', 'available'),
(2239, 22, 'L', 5, 'normal', '0.00', 'available'),
(2240, 22, 'L', 6, 'normal', '0.00', 'available'),
(2241, 22, 'L', 7, 'normal', '0.00', 'available'),
(2242, 22, 'L', 8, 'normal', '0.00', 'available'),
(2243, 22, 'L', 9, 'normal', '0.00', 'available'),
(2244, 22, 'L', 10, 'normal', '0.00', 'available'),
(2245, 22, 'L', 11, 'normal', '0.00', 'available'),
(2246, 22, 'L', 12, 'normal', '0.00', 'available'),
(2247, 23, 'A', 1, 'normal', '0.00', 'available'),
(2248, 23, 'A', 2, 'normal', '0.00', 'available'),
(2249, 23, 'A', 3, 'normal', '0.00', 'available'),
(2250, 23, 'A', 4, 'normal', '0.00', 'available'),
(2251, 23, 'A', 5, 'normal', '0.00', 'available'),
(2252, 23, 'A', 6, 'normal', '0.00', 'available'),
(2253, 23, 'A', 7, 'normal', '0.00', 'available'),
(2254, 23, 'A', 8, 'normal', '0.00', 'available'),
(2255, 23, 'A', 9, 'normal', '0.00', 'available'),
(2256, 23, 'A', 10, 'normal', '0.00', 'available'),
(2257, 23, 'A', 11, 'normal', '0.00', 'available'),
(2258, 23, 'A', 12, 'normal', '0.00', 'available'),
(2259, 23, 'B', 1, 'normal', '0.00', 'available'),
(2260, 23, 'B', 2, 'normal', '0.00', 'available'),
(2261, 23, 'B', 3, 'normal', '0.00', 'available'),
(2262, 23, 'B', 4, 'normal', '0.00', 'available'),
(2263, 23, 'B', 5, 'normal', '0.00', 'available'),
(2264, 23, 'B', 6, 'normal', '0.00', 'available'),
(2265, 23, 'B', 7, 'normal', '0.00', 'available'),
(2266, 23, 'B', 8, 'normal', '0.00', 'available'),
(2267, 23, 'B', 9, 'normal', '0.00', 'available'),
(2268, 23, 'B', 10, 'normal', '0.00', 'available'),
(2269, 23, 'B', 11, 'normal', '0.00', 'available'),
(2270, 23, 'B', 12, 'normal', '0.00', 'available'),
(2271, 23, 'C', 1, 'normal', '0.00', 'available'),
(2272, 23, 'C', 2, 'normal', '0.00', 'available'),
(2273, 23, 'C', 3, 'normal', '0.00', 'available'),
(2274, 23, 'C', 4, 'normal', '0.00', 'available'),
(2275, 23, 'C', 5, 'normal', '0.00', 'available'),
(2276, 23, 'C', 6, 'normal', '0.00', 'available'),
(2277, 23, 'C', 7, 'normal', '0.00', 'available'),
(2278, 23, 'C', 8, 'normal', '0.00', 'available'),
(2279, 23, 'C', 9, 'normal', '0.00', 'available'),
(2280, 23, 'C', 10, 'normal', '0.00', 'available'),
(2281, 23, 'C', 11, 'normal', '0.00', 'available'),
(2282, 23, 'C', 12, 'normal', '0.00', 'available'),
(2283, 23, 'D', 1, 'normal', '0.00', 'available'),
(2284, 23, 'D', 2, 'normal', '0.00', 'available'),
(2285, 23, 'D', 3, 'normal', '0.00', 'available'),
(2286, 23, 'D', 4, 'normal', '0.00', 'available'),
(2287, 23, 'D', 5, 'normal', '0.00', 'available'),
(2288, 23, 'D', 6, 'normal', '0.00', 'available'),
(2289, 23, 'D', 7, 'normal', '0.00', 'available'),
(2290, 23, 'D', 8, 'normal', '0.00', 'available'),
(2291, 23, 'D', 9, 'normal', '0.00', 'available'),
(2292, 23, 'D', 10, 'normal', '0.00', 'available'),
(2293, 23, 'D', 11, 'normal', '0.00', 'available'),
(2294, 23, 'D', 12, 'normal', '0.00', 'available'),
(2295, 23, 'E', 1, 'normal', '0.00', 'available'),
(2296, 23, 'E', 2, 'normal', '0.00', 'available'),
(2297, 23, 'E', 3, 'normal', '0.00', 'available'),
(2298, 23, 'E', 4, 'normal', '0.00', 'available'),
(2299, 23, 'E', 5, 'normal', '0.00', 'available'),
(2300, 23, 'E', 6, 'normal', '0.00', 'available'),
(2301, 23, 'E', 7, 'normal', '0.00', 'available'),
(2302, 23, 'E', 8, 'normal', '0.00', 'available'),
(2303, 23, 'E', 9, 'normal', '0.00', 'available'),
(2304, 23, 'E', 10, 'normal', '0.00', 'available'),
(2305, 23, 'E', 11, 'normal', '0.00', 'available'),
(2306, 23, 'E', 12, 'normal', '0.00', 'available'),
(2307, 23, 'F', 1, 'normal', '0.00', 'available'),
(2308, 23, 'F', 2, 'normal', '0.00', 'available'),
(2309, 23, 'F', 3, 'normal', '0.00', 'available'),
(2310, 23, 'F', 4, 'normal', '0.00', 'available'),
(2311, 23, 'F', 5, 'normal', '0.00', 'available'),
(2312, 23, 'F', 6, 'normal', '0.00', 'available'),
(2313, 23, 'F', 7, 'normal', '0.00', 'available'),
(2314, 23, 'F', 8, 'normal', '0.00', 'available'),
(2315, 23, 'F', 9, 'normal', '0.00', 'available'),
(2316, 23, 'F', 10, 'normal', '0.00', 'available'),
(2317, 23, 'F', 11, 'normal', '0.00', 'available'),
(2318, 23, 'F', 12, 'normal', '0.00', 'available'),
(2319, 23, 'G', 1, 'normal', '0.00', 'available'),
(2320, 23, 'G', 2, 'normal', '0.00', 'available'),
(2321, 23, 'G', 3, 'normal', '0.00', 'available'),
(2322, 23, 'G', 4, 'normal', '0.00', 'available'),
(2323, 23, 'G', 5, 'normal', '0.00', 'available'),
(2324, 23, 'G', 6, 'normal', '0.00', 'available'),
(2325, 23, 'G', 7, 'normal', '0.00', 'available'),
(2326, 23, 'G', 8, 'normal', '0.00', 'available'),
(2327, 23, 'G', 9, 'normal', '0.00', 'available'),
(2328, 23, 'G', 10, 'normal', '0.00', 'available'),
(2329, 23, 'G', 11, 'normal', '0.00', 'available'),
(2330, 23, 'G', 12, 'normal', '0.00', 'available'),
(2331, 23, 'H', 1, 'normal', '0.00', 'available'),
(2332, 23, 'H', 2, 'normal', '0.00', 'available'),
(2333, 23, 'H', 3, 'normal', '0.00', 'available'),
(2334, 23, 'H', 4, 'normal', '0.00', 'available'),
(2335, 23, 'H', 5, 'normal', '0.00', 'available'),
(2336, 23, 'H', 6, 'normal', '0.00', 'available'),
(2337, 23, 'H', 7, 'normal', '0.00', 'available'),
(2338, 23, 'H', 8, 'normal', '0.00', 'available'),
(2339, 23, 'H', 9, 'normal', '0.00', 'available'),
(2340, 23, 'H', 10, 'normal', '0.00', 'available'),
(2341, 23, 'H', 11, 'normal', '0.00', 'available'),
(2342, 23, 'H', 12, 'normal', '0.00', 'available'),
(2343, 23, 'I', 1, 'normal', '0.00', 'available'),
(2344, 23, 'I', 2, 'normal', '0.00', 'available'),
(2345, 23, 'I', 3, 'normal', '0.00', 'available'),
(2346, 23, 'I', 4, 'normal', '0.00', 'available'),
(2347, 23, 'I', 5, 'normal', '0.00', 'available'),
(2348, 23, 'I', 6, 'normal', '0.00', 'available'),
(2349, 23, 'I', 7, 'normal', '0.00', 'available'),
(2350, 23, 'I', 8, 'normal', '0.00', 'available'),
(2351, 23, 'I', 9, 'normal', '0.00', 'available'),
(2352, 23, 'I', 10, 'normal', '0.00', 'available'),
(2353, 23, 'I', 11, 'normal', '0.00', 'available'),
(2354, 23, 'I', 12, 'normal', '0.00', 'available'),
(2355, 23, 'J', 1, 'normal', '0.00', 'available'),
(2356, 23, 'J', 2, 'normal', '0.00', 'available'),
(2357, 23, 'J', 3, 'normal', '0.00', 'available'),
(2358, 23, 'J', 4, 'normal', '0.00', 'available'),
(2359, 23, 'J', 5, 'normal', '0.00', 'available'),
(2360, 23, 'J', 6, 'normal', '0.00', 'available'),
(2361, 23, 'J', 7, 'normal', '0.00', 'available'),
(2362, 23, 'J', 8, 'normal', '0.00', 'available'),
(2363, 23, 'J', 9, 'normal', '0.00', 'available'),
(2364, 23, 'J', 10, 'normal', '0.00', 'available'),
(2365, 23, 'J', 11, 'normal', '0.00', 'available'),
(2366, 23, 'J', 12, 'normal', '0.00', 'available'),
(2367, 24, 'A', 1, 'normal', '0.00', 'available'),
(2368, 24, 'A', 2, 'normal', '0.00', 'available'),
(2369, 24, 'A', 3, 'normal', '0.00', 'available'),
(2370, 24, 'A', 4, 'normal', '0.00', 'available'),
(2371, 24, 'A', 5, 'normal', '0.00', 'available'),
(2372, 24, 'A', 6, 'normal', '0.00', 'available'),
(2373, 24, 'A', 7, 'normal', '0.00', 'available'),
(2374, 24, 'A', 8, 'normal', '0.00', 'available'),
(2375, 24, 'A', 9, 'normal', '0.00', 'available'),
(2376, 24, 'A', 10, 'normal', '0.00', 'available'),
(2377, 24, 'A', 11, 'normal', '0.00', 'available'),
(2378, 24, 'A', 12, 'normal', '0.00', 'available'),
(2379, 24, 'B', 1, 'normal', '0.00', 'available'),
(2380, 24, 'B', 2, 'normal', '0.00', 'available'),
(2381, 24, 'B', 3, 'normal', '0.00', 'available'),
(2382, 24, 'B', 4, 'normal', '0.00', 'available'),
(2383, 24, 'B', 5, 'normal', '0.00', 'available'),
(2384, 24, 'B', 6, 'normal', '0.00', 'available'),
(2385, 24, 'B', 7, 'normal', '0.00', 'available'),
(2386, 24, 'B', 8, 'normal', '0.00', 'available'),
(2387, 24, 'B', 9, 'normal', '0.00', 'available'),
(2388, 24, 'B', 10, 'normal', '0.00', 'available'),
(2389, 24, 'B', 11, 'normal', '0.00', 'available'),
(2390, 24, 'B', 12, 'normal', '0.00', 'available'),
(2391, 24, 'C', 1, 'normal', '0.00', 'available'),
(2392, 24, 'C', 2, 'normal', '0.00', 'available'),
(2393, 24, 'C', 3, 'normal', '0.00', 'available'),
(2394, 24, 'C', 4, 'normal', '0.00', 'available'),
(2395, 24, 'C', 5, 'normal', '0.00', 'available'),
(2396, 24, 'C', 6, 'normal', '0.00', 'available'),
(2397, 24, 'C', 7, 'normal', '0.00', 'available'),
(2398, 24, 'C', 8, 'normal', '0.00', 'available'),
(2399, 24, 'C', 9, 'normal', '0.00', 'available'),
(2400, 24, 'C', 10, 'normal', '0.00', 'available'),
(2401, 24, 'C', 11, 'normal', '0.00', 'available'),
(2402, 24, 'C', 12, 'normal', '0.00', 'available'),
(2403, 24, 'D', 1, 'normal', '0.00', 'available'),
(2404, 24, 'D', 2, 'normal', '0.00', 'available'),
(2405, 24, 'D', 3, 'normal', '0.00', 'available'),
(2406, 24, 'D', 4, 'normal', '0.00', 'available'),
(2407, 24, 'D', 5, 'normal', '0.00', 'available'),
(2408, 24, 'D', 6, 'normal', '0.00', 'available'),
(2409, 24, 'D', 7, 'normal', '0.00', 'available'),
(2410, 24, 'D', 8, 'normal', '0.00', 'available'),
(2411, 24, 'D', 9, 'normal', '0.00', 'available'),
(2412, 24, 'D', 10, 'normal', '0.00', 'available'),
(2413, 24, 'D', 11, 'normal', '0.00', 'available'),
(2414, 24, 'D', 12, 'normal', '0.00', 'available'),
(2415, 24, 'E', 1, 'normal', '0.00', 'available'),
(2416, 24, 'E', 2, 'normal', '0.00', 'available'),
(2417, 24, 'E', 3, 'normal', '0.00', 'available'),
(2418, 24, 'E', 4, 'normal', '0.00', 'available'),
(2419, 24, 'E', 5, 'normal', '0.00', 'available'),
(2420, 24, 'E', 6, 'normal', '0.00', 'available'),
(2421, 24, 'E', 7, 'normal', '0.00', 'available'),
(2422, 24, 'E', 8, 'normal', '0.00', 'available'),
(2423, 24, 'E', 9, 'normal', '0.00', 'available'),
(2424, 24, 'E', 10, 'normal', '0.00', 'available'),
(2425, 24, 'E', 11, 'normal', '0.00', 'available'),
(2426, 24, 'E', 12, 'normal', '0.00', 'available'),
(2427, 24, 'F', 1, 'normal', '0.00', 'available'),
(2428, 24, 'F', 2, 'normal', '0.00', 'available'),
(2429, 24, 'F', 3, 'normal', '0.00', 'available'),
(2430, 24, 'F', 4, 'normal', '0.00', 'available'),
(2431, 24, 'F', 5, 'normal', '0.00', 'available'),
(2432, 24, 'F', 6, 'normal', '0.00', 'available'),
(2433, 24, 'F', 7, 'normal', '0.00', 'available'),
(2434, 24, 'F', 8, 'normal', '0.00', 'available'),
(2435, 24, 'F', 9, 'normal', '0.00', 'available'),
(2436, 24, 'F', 10, 'normal', '0.00', 'available'),
(2437, 24, 'F', 11, 'normal', '0.00', 'available'),
(2438, 24, 'F', 12, 'normal', '0.00', 'available'),
(2439, 24, 'G', 1, 'normal', '0.00', 'available'),
(2440, 24, 'G', 2, 'normal', '0.00', 'available'),
(2441, 24, 'G', 3, 'normal', '0.00', 'available'),
(2442, 24, 'G', 4, 'normal', '0.00', 'available'),
(2443, 24, 'G', 5, 'normal', '0.00', 'available'),
(2444, 24, 'G', 6, 'normal', '0.00', 'available'),
(2445, 24, 'G', 7, 'normal', '0.00', 'available'),
(2446, 24, 'G', 8, 'normal', '0.00', 'available'),
(2447, 24, 'G', 9, 'normal', '0.00', 'available'),
(2448, 24, 'G', 10, 'normal', '0.00', 'available'),
(2449, 24, 'G', 11, 'normal', '0.00', 'available'),
(2450, 24, 'G', 12, 'normal', '0.00', 'available'),
(2451, 24, 'H', 1, 'normal', '0.00', 'available'),
(2452, 24, 'H', 2, 'normal', '0.00', 'available'),
(2453, 24, 'H', 3, 'normal', '0.00', 'available'),
(2454, 24, 'H', 4, 'normal', '0.00', 'available'),
(2455, 24, 'H', 5, 'normal', '0.00', 'available'),
(2456, 24, 'H', 6, 'normal', '0.00', 'available'),
(2457, 24, 'H', 7, 'normal', '0.00', 'available'),
(2458, 24, 'H', 8, 'normal', '0.00', 'available'),
(2459, 24, 'H', 9, 'normal', '0.00', 'available'),
(2460, 24, 'H', 10, 'normal', '0.00', 'available'),
(2461, 24, 'H', 11, 'normal', '0.00', 'available'),
(2462, 24, 'H', 12, 'normal', '0.00', 'available'),
(2463, 24, 'I', 1, 'normal', '0.00', 'available'),
(2464, 24, 'I', 2, 'normal', '0.00', 'available'),
(2465, 24, 'I', 3, 'normal', '0.00', 'available'),
(2466, 24, 'I', 4, 'normal', '0.00', 'available'),
(2467, 24, 'I', 5, 'normal', '0.00', 'available'),
(2468, 24, 'I', 6, 'normal', '0.00', 'available'),
(2469, 24, 'I', 7, 'normal', '0.00', 'available'),
(2470, 24, 'I', 8, 'normal', '0.00', 'available'),
(2471, 24, 'I', 9, 'normal', '0.00', 'available'),
(2472, 24, 'I', 10, 'normal', '0.00', 'available'),
(2473, 24, 'I', 11, 'normal', '0.00', 'available'),
(2474, 24, 'I', 12, 'normal', '0.00', 'available'),
(2475, 24, 'J', 1, 'normal', '0.00', 'available'),
(2476, 24, 'J', 2, 'normal', '0.00', 'available'),
(2477, 24, 'J', 3, 'normal', '0.00', 'available'),
(2478, 24, 'J', 4, 'normal', '0.00', 'available'),
(2479, 24, 'J', 5, 'normal', '0.00', 'available'),
(2480, 24, 'J', 6, 'normal', '0.00', 'available'),
(2481, 24, 'J', 7, 'normal', '0.00', 'available'),
(2482, 24, 'J', 8, 'normal', '0.00', 'available'),
(2483, 24, 'J', 9, 'normal', '0.00', 'available'),
(2484, 24, 'J', 10, 'normal', '0.00', 'available'),
(2485, 24, 'J', 11, 'normal', '0.00', 'available'),
(2486, 24, 'J', 12, 'normal', '0.00', 'available'),
(2487, 24, 'K', 1, 'normal', '0.00', 'available'),
(2488, 24, 'K', 2, 'normal', '0.00', 'available'),
(2489, 24, 'K', 3, 'normal', '0.00', 'available'),
(2490, 24, 'K', 4, 'normal', '0.00', 'available'),
(2491, 24, 'K', 5, 'normal', '0.00', 'available'),
(2492, 24, 'K', 6, 'normal', '0.00', 'available'),
(2493, 24, 'K', 7, 'normal', '0.00', 'available'),
(2494, 24, 'K', 8, 'normal', '0.00', 'available'),
(2495, 24, 'K', 9, 'normal', '0.00', 'available'),
(2496, 24, 'K', 10, 'normal', '0.00', 'available'),
(2497, 24, 'K', 11, 'normal', '0.00', 'available'),
(2498, 24, 'K', 12, 'normal', '0.00', 'available'),
(2499, 24, 'L', 1, 'normal', '0.00', 'available'),
(2500, 24, 'L', 2, 'normal', '0.00', 'available'),
(2501, 24, 'L', 3, 'normal', '0.00', 'available'),
(2502, 24, 'L', 4, 'normal', '0.00', 'available'),
(2503, 24, 'L', 5, 'normal', '0.00', 'available'),
(2504, 24, 'L', 6, 'normal', '0.00', 'available'),
(2505, 24, 'L', 7, 'normal', '0.00', 'available'),
(2506, 24, 'L', 8, 'normal', '0.00', 'available'),
(2507, 24, 'L', 9, 'normal', '0.00', 'available'),
(2508, 24, 'L', 10, 'normal', '0.00', 'available'),
(2509, 24, 'L', 11, 'normal', '0.00', 'available'),
(2510, 24, 'L', 12, 'normal', '0.00', 'available');

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
(1, 8, 17, '2025-11-19', '15:00:00', '17:02:00', '80000.00', '65000.00', '2D'),
(2, 7, 18, '2025-11-20', '16:00:00', '18:00:00', '75000.00', '65000.00', '3D'),
(3, 6, 17, '2025-11-20', '15:00:00', '17:00:00', '85000.00', '70000.00', '3D'),
(4, 8, 17, '2025-11-21', '17:30:00', '19:32:00', '80000.00', '65000.00', '2D'),
(5, 6, 18, '2025-11-19', '14:00:00', '16:00:00', '75000.00', '60000.00', '3D'),
(6, 9, 19, '2025-11-20', '15:00:00', '17:13:00', '70000.00', '60000.00', '2D'),
(7, 9, 17, '2025-11-19', '10:30:00', '12:43:00', '80000.00', '70000.00', '2D'),
(8, 7, 21, '2025-11-21', '13:45:00', '15:45:00', '75000.00', '60000.00', '3D'),
(9, 9, 18, '2025-11-19', '09:30:00', '11:43:00', '80000.00', '70000.00', '2D'),
(10, 6, 17, '2025-11-25', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(11, 7, 18, '2025-11-25', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(12, 8, 19, '2025-11-25', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(13, 9, 21, '2025-11-25', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(14, 10, 22, '2025-11-25', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(15, 11, 17, '2025-11-25', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(16, 12, 18, '2025-11-25', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(17, 6, 19, '2025-11-25', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(18, 7, 21, '2025-11-25', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(19, 8, 22, '2025-11-25', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(20, 9, 17, '2025-11-25', '14:00:00', '16:13:00', '80000.00', '70000.00', '2D'),
(21, 10, 18, '2025-11-25', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(22, 11, 19, '2025-11-25', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(23, 12, 21, '2025-11-25', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(24, 6, 22, '2025-11-25', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(25, 7, 17, '2025-11-25', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(26, 8, 18, '2025-11-25', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(27, 9, 19, '2025-11-25', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(28, 10, 21, '2025-11-25', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(29, 11, 22, '2025-11-25', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(30, 12, 17, '2025-11-25', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(31, 6, 18, '2025-11-25', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(32, 7, 19, '2025-11-25', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(33, 8, 21, '2025-11-25', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(34, 9, 22, '2025-11-25', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(35, 10, 17, '2025-11-25', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(36, 11, 18, '2025-11-25', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(37, 12, 19, '2025-11-25', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(38, 6, 21, '2025-11-25', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(39, 7, 22, '2025-11-25', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(40, 8, 17, '2025-11-26', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(41, 9, 18, '2025-11-26', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(42, 10, 19, '2025-11-26', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(43, 11, 21, '2025-11-26', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(44, 12, 22, '2025-11-26', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(45, 6, 17, '2025-11-26', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(46, 7, 18, '2025-11-26', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(47, 8, 19, '2025-11-26', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(48, 9, 21, '2025-11-26', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(49, 10, 22, '2025-11-26', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(50, 11, 17, '2025-11-26', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(51, 12, 18, '2025-11-26', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(52, 6, 19, '2025-11-26', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(53, 7, 21, '2025-11-26', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(54, 8, 22, '2025-11-26', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(55, 9, 17, '2025-11-26', '16:30:00', '18:43:00', '80000.00', '70000.00', '2D'),
(56, 10, 18, '2025-11-26', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(57, 11, 19, '2025-11-26', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(58, 12, 21, '2025-11-26', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(59, 6, 22, '2025-11-26', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(60, 7, 17, '2025-11-26', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(61, 8, 18, '2025-11-26', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(62, 9, 19, '2025-11-26', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(63, 10, 21, '2025-11-26', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(64, 11, 22, '2025-11-26', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(65, 12, 17, '2025-11-26', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(66, 6, 18, '2025-11-26', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(67, 7, 19, '2025-11-26', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(68, 8, 21, '2025-11-26', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(69, 9, 22, '2025-11-26', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(70, 10, 17, '2025-11-27', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(71, 11, 18, '2025-11-27', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(72, 12, 19, '2025-11-27', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(73, 6, 21, '2025-11-27', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(74, 7, 22, '2025-11-27', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(75, 8, 17, '2025-11-27', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(76, 9, 18, '2025-11-27', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(77, 10, 19, '2025-11-27', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(78, 11, 21, '2025-11-27', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(79, 12, 22, '2025-11-27', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(80, 6, 17, '2025-11-27', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(81, 7, 18, '2025-11-27', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(82, 8, 19, '2025-11-27', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(83, 9, 21, '2025-11-27', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(84, 10, 22, '2025-11-27', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(85, 11, 17, '2025-11-27', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(86, 12, 18, '2025-11-27', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(87, 6, 19, '2025-11-27', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(88, 7, 21, '2025-11-27', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(89, 8, 22, '2025-11-27', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(90, 9, 17, '2025-11-27', '19:00:00', '21:13:00', '80000.00', '70000.00', '2D'),
(91, 10, 18, '2025-11-27', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(92, 11, 19, '2025-11-27', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(93, 12, 21, '2025-11-27', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(94, 6, 22, '2025-11-27', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(95, 7, 17, '2025-11-27', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(96, 8, 18, '2025-11-27', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(97, 9, 19, '2025-11-27', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(98, 10, 21, '2025-11-27', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(99, 11, 22, '2025-11-27', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(100, 12, 17, '2025-11-28', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(101, 6, 18, '2025-11-28', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(102, 7, 19, '2025-11-28', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(103, 8, 21, '2025-11-28', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(104, 9, 22, '2025-11-28', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(105, 10, 17, '2025-11-28', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(106, 11, 18, '2025-11-28', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(107, 12, 19, '2025-11-28', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(108, 6, 21, '2025-11-28', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(109, 7, 22, '2025-11-28', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(110, 8, 17, '2025-11-28', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(111, 9, 18, '2025-11-28', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(112, 10, 19, '2025-11-28', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(113, 11, 21, '2025-11-28', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(114, 12, 22, '2025-11-28', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(115, 6, 17, '2025-11-28', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(116, 7, 18, '2025-11-28', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(117, 8, 19, '2025-11-28', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(118, 9, 21, '2025-11-28', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(119, 10, 22, '2025-11-28', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(120, 11, 17, '2025-11-28', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(121, 12, 18, '2025-11-28', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(122, 6, 19, '2025-11-28', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(123, 7, 21, '2025-11-28', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(124, 8, 22, '2025-11-28', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(125, 9, 17, '2025-11-28', '21:30:00', '23:43:00', '80000.00', '70000.00', '2D'),
(126, 10, 18, '2025-11-28', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(127, 11, 19, '2025-11-28', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(128, 12, 21, '2025-11-28', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(129, 6, 22, '2025-11-28', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(130, 7, 17, '2025-11-29', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(131, 8, 18, '2025-11-29', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(132, 9, 19, '2025-11-29', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(133, 10, 21, '2025-11-29', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(134, 11, 22, '2025-11-29', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(135, 12, 17, '2025-11-29', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(136, 6, 18, '2025-11-29', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(137, 7, 19, '2025-11-29', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(138, 8, 21, '2025-11-29', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(139, 9, 22, '2025-11-29', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(140, 10, 17, '2025-11-29', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(141, 11, 18, '2025-11-29', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(142, 12, 19, '2025-11-29', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(143, 6, 21, '2025-11-29', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(144, 7, 22, '2025-11-29', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(145, 8, 17, '2025-11-29', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(146, 9, 18, '2025-11-29', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(147, 10, 19, '2025-11-29', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(148, 11, 21, '2025-11-29', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(149, 12, 22, '2025-11-29', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(150, 6, 17, '2025-11-29', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(151, 7, 18, '2025-11-29', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(152, 8, 19, '2025-11-29', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(153, 9, 21, '2025-11-29', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(154, 10, 22, '2025-11-29', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(155, 11, 17, '2025-11-29', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(156, 12, 18, '2025-11-29', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(157, 6, 19, '2025-11-29', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(158, 7, 21, '2025-11-29', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(159, 8, 22, '2025-11-29', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(160, 9, 17, '2025-11-30', '09:00:00', '11:13:00', '80000.00', '70000.00', '2D'),
(161, 10, 18, '2025-11-30', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(162, 11, 19, '2025-11-30', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(163, 12, 21, '2025-11-30', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(164, 6, 22, '2025-11-30', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(165, 7, 17, '2025-11-30', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(166, 8, 18, '2025-11-30', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(167, 9, 19, '2025-11-30', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(168, 10, 21, '2025-11-30', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(169, 11, 22, '2025-11-30', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(170, 12, 17, '2025-11-30', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(171, 6, 18, '2025-11-30', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(172, 7, 19, '2025-11-30', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(173, 8, 21, '2025-11-30', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(174, 9, 22, '2025-11-30', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(175, 10, 17, '2025-11-30', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(176, 11, 18, '2025-11-30', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(177, 12, 19, '2025-11-30', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(178, 6, 21, '2025-11-30', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(179, 7, 22, '2025-11-30', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(180, 8, 17, '2025-11-30', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(181, 9, 18, '2025-11-30', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(182, 10, 19, '2025-11-30', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(183, 11, 21, '2025-11-30', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(184, 12, 22, '2025-11-30', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(185, 6, 17, '2025-11-30', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(186, 7, 18, '2025-11-30', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(187, 8, 19, '2025-11-30', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(188, 9, 21, '2025-11-30', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(189, 10, 22, '2025-11-30', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(190, 11, 17, '2025-12-01', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(191, 12, 18, '2025-12-01', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(192, 6, 19, '2025-12-01', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(193, 7, 21, '2025-12-01', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(194, 8, 22, '2025-12-01', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(195, 9, 17, '2025-12-01', '11:30:00', '13:43:00', '80000.00', '70000.00', '2D'),
(196, 10, 18, '2025-12-01', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(197, 11, 19, '2025-12-01', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(198, 12, 21, '2025-12-01', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(199, 6, 22, '2025-12-01', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(200, 7, 17, '2025-12-01', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(201, 8, 18, '2025-12-01', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(202, 9, 19, '2025-12-01', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(203, 10, 21, '2025-12-01', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(204, 11, 22, '2025-12-01', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(205, 12, 17, '2025-12-01', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(206, 6, 18, '2025-12-01', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(207, 7, 19, '2025-12-01', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(208, 8, 21, '2025-12-01', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(209, 9, 22, '2025-12-01', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(210, 10, 17, '2025-12-01', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(211, 11, 18, '2025-12-01', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(212, 12, 19, '2025-12-01', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(213, 6, 21, '2025-12-01', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(214, 7, 22, '2025-12-01', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(215, 8, 17, '2025-12-01', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(216, 9, 18, '2025-12-01', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(217, 10, 19, '2025-12-01', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(218, 11, 21, '2025-12-01', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(219, 12, 22, '2025-12-01', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(220, 6, 17, '2025-12-02', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(221, 7, 18, '2025-12-02', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(222, 8, 19, '2025-12-02', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(223, 9, 21, '2025-12-02', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(224, 10, 22, '2025-12-02', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(225, 11, 17, '2025-12-02', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(226, 12, 18, '2025-12-02', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(227, 6, 19, '2025-12-02', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(228, 7, 21, '2025-12-02', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(229, 8, 22, '2025-12-02', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(230, 9, 17, '2025-12-02', '14:00:00', '16:13:00', '80000.00', '70000.00', '2D'),
(231, 10, 18, '2025-12-02', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(232, 11, 19, '2025-12-02', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(233, 12, 21, '2025-12-02', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(234, 6, 22, '2025-12-02', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(235, 7, 17, '2025-12-02', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(236, 8, 18, '2025-12-02', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(237, 9, 19, '2025-12-02', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(238, 10, 21, '2025-12-02', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(239, 11, 22, '2025-12-02', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(240, 12, 17, '2025-12-02', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(241, 6, 18, '2025-12-02', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(242, 7, 19, '2025-12-02', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(243, 8, 21, '2025-12-02', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(244, 9, 22, '2025-12-02', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(245, 10, 17, '2025-12-02', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(246, 11, 18, '2025-12-02', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(247, 12, 19, '2025-12-02', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(248, 6, 21, '2025-12-02', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(249, 7, 22, '2025-12-02', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(250, 8, 17, '2025-12-03', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(251, 9, 18, '2025-12-03', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(252, 10, 19, '2025-12-03', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(253, 11, 21, '2025-12-03', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(254, 12, 22, '2025-12-03', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(255, 6, 17, '2025-12-03', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(256, 7, 18, '2025-12-03', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(257, 8, 19, '2025-12-03', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(258, 9, 21, '2025-12-03', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(259, 10, 22, '2025-12-03', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(260, 11, 17, '2025-12-03', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(261, 12, 18, '2025-12-03', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(262, 6, 19, '2025-12-03', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(263, 7, 21, '2025-12-03', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(264, 8, 22, '2025-12-03', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(265, 9, 17, '2025-12-03', '16:30:00', '18:43:00', '80000.00', '70000.00', '2D'),
(266, 10, 18, '2025-12-03', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(267, 11, 19, '2025-12-03', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(268, 12, 21, '2025-12-03', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(269, 6, 22, '2025-12-03', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(270, 7, 17, '2025-12-03', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(271, 8, 18, '2025-12-03', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(272, 9, 19, '2025-12-03', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(273, 10, 21, '2025-12-03', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(274, 11, 22, '2025-12-03', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(275, 12, 17, '2025-12-03', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(276, 6, 18, '2025-12-03', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(277, 7, 19, '2025-12-03', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(278, 8, 21, '2025-12-03', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(279, 9, 22, '2025-12-03', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(280, 10, 17, '2025-12-04', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(281, 11, 18, '2025-12-04', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(282, 12, 19, '2025-12-04', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(283, 6, 21, '2025-12-04', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(284, 7, 22, '2025-12-04', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(285, 8, 17, '2025-12-04', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(286, 9, 18, '2025-12-04', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(287, 10, 19, '2025-12-04', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(288, 11, 21, '2025-12-04', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(289, 12, 22, '2025-12-04', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(290, 6, 17, '2025-12-04', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(291, 7, 18, '2025-12-04', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(292, 8, 19, '2025-12-04', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(293, 9, 21, '2025-12-04', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(294, 10, 22, '2025-12-04', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(295, 11, 17, '2025-12-04', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(296, 12, 18, '2025-12-04', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(297, 6, 19, '2025-12-04', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(298, 7, 21, '2025-12-04', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(299, 8, 22, '2025-12-04', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(300, 9, 17, '2025-12-04', '19:00:00', '21:13:00', '80000.00', '70000.00', '2D'),
(301, 10, 18, '2025-12-04', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(302, 11, 19, '2025-12-04', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(303, 12, 21, '2025-12-04', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(304, 6, 22, '2025-12-04', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(305, 7, 17, '2025-12-04', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(306, 8, 18, '2025-12-04', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(307, 9, 19, '2025-12-04', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(308, 10, 21, '2025-12-04', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(309, 11, 22, '2025-12-04', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(310, 12, 17, '2025-12-05', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(311, 6, 18, '2025-12-05', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(312, 7, 19, '2025-12-05', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(313, 8, 21, '2025-12-05', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(314, 9, 22, '2025-12-05', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(315, 10, 17, '2025-12-05', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(316, 11, 18, '2025-12-05', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(317, 12, 19, '2025-12-05', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(318, 6, 21, '2025-12-05', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(319, 7, 22, '2025-12-05', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(320, 8, 17, '2025-12-05', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(321, 9, 18, '2025-12-05', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(322, 10, 19, '2025-12-05', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(323, 11, 21, '2025-12-05', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(324, 12, 22, '2025-12-05', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(325, 6, 17, '2025-12-05', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(326, 7, 18, '2025-12-05', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(327, 8, 19, '2025-12-05', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(328, 9, 21, '2025-12-05', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(329, 10, 22, '2025-12-05', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(330, 11, 17, '2025-12-05', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(331, 12, 18, '2025-12-05', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(332, 6, 19, '2025-12-05', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(333, 7, 21, '2025-12-05', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(334, 8, 22, '2025-12-05', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(335, 9, 17, '2025-12-05', '21:30:00', '23:43:00', '80000.00', '70000.00', '2D'),
(336, 10, 18, '2025-12-05', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(337, 11, 19, '2025-12-05', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(338, 12, 21, '2025-12-05', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(339, 6, 22, '2025-12-05', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(340, 7, 17, '2025-12-06', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(341, 8, 18, '2025-12-06', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(342, 9, 19, '2025-12-06', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(343, 10, 21, '2025-12-06', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(344, 11, 22, '2025-12-06', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(345, 12, 17, '2025-12-06', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(346, 6, 18, '2025-12-06', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(347, 7, 19, '2025-12-06', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(348, 8, 21, '2025-12-06', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(349, 9, 22, '2025-12-06', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(350, 10, 17, '2025-12-06', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(351, 11, 18, '2025-12-06', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(352, 12, 19, '2025-12-06', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(353, 6, 21, '2025-12-06', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(354, 7, 22, '2025-12-06', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(355, 8, 17, '2025-12-06', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(356, 9, 18, '2025-12-06', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(357, 10, 19, '2025-12-06', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(358, 11, 21, '2025-12-06', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(359, 12, 22, '2025-12-06', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(360, 6, 17, '2025-12-06', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(361, 7, 18, '2025-12-06', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(362, 8, 19, '2025-12-06', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(363, 9, 21, '2025-12-06', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(364, 10, 22, '2025-12-06', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(365, 11, 17, '2025-12-06', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(366, 12, 18, '2025-12-06', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(367, 6, 19, '2025-12-06', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(368, 7, 21, '2025-12-06', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(369, 8, 22, '2025-12-06', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(370, 9, 17, '2025-12-07', '09:00:00', '11:13:00', '80000.00', '70000.00', '2D'),
(371, 10, 18, '2025-12-07', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(372, 11, 19, '2025-12-07', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(373, 12, 21, '2025-12-07', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(374, 6, 22, '2025-12-07', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(375, 7, 17, '2025-12-07', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(376, 8, 18, '2025-12-07', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(377, 9, 19, '2025-12-07', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(378, 10, 21, '2025-12-07', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(379, 11, 22, '2025-12-07', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(380, 12, 17, '2025-12-07', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(381, 6, 18, '2025-12-07', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(382, 7, 19, '2025-12-07', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(383, 8, 21, '2025-12-07', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(384, 9, 22, '2025-12-07', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(385, 10, 17, '2025-12-07', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(386, 11, 18, '2025-12-07', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(387, 12, 19, '2025-12-07', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(388, 6, 21, '2025-12-07', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(389, 7, 22, '2025-12-07', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(390, 8, 17, '2025-12-07', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(391, 9, 18, '2025-12-07', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(392, 10, 19, '2025-12-07', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(393, 11, 21, '2025-12-07', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(394, 12, 22, '2025-12-07', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(395, 6, 17, '2025-12-07', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(396, 7, 18, '2025-12-07', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(397, 8, 19, '2025-12-07', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(398, 9, 21, '2025-12-07', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(399, 10, 22, '2025-12-07', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(400, 11, 17, '2025-12-08', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(401, 12, 18, '2025-12-08', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(402, 6, 19, '2025-12-08', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(403, 7, 21, '2025-12-08', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(404, 8, 22, '2025-12-08', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(405, 9, 17, '2025-12-08', '11:30:00', '13:43:00', '80000.00', '70000.00', '2D'),
(406, 10, 18, '2025-12-08', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(407, 11, 19, '2025-12-08', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(408, 12, 21, '2025-12-08', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(409, 6, 22, '2025-12-08', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(410, 7, 17, '2025-12-08', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(411, 8, 18, '2025-12-08', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(412, 9, 19, '2025-12-08', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(413, 10, 21, '2025-12-08', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(414, 11, 22, '2025-12-08', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(415, 12, 17, '2025-12-08', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(416, 6, 18, '2025-12-08', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(417, 7, 19, '2025-12-08', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(418, 8, 21, '2025-12-08', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(419, 9, 22, '2025-12-08', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(420, 10, 17, '2025-12-08', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(421, 11, 18, '2025-12-08', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(422, 12, 19, '2025-12-08', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(423, 6, 21, '2025-12-08', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(424, 7, 22, '2025-12-08', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(425, 8, 17, '2025-12-08', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(426, 9, 18, '2025-12-08', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(427, 10, 19, '2025-12-08', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(428, 11, 21, '2025-12-08', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(429, 12, 22, '2025-12-08', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(430, 6, 17, '2025-12-09', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(431, 7, 18, '2025-12-09', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(432, 8, 19, '2025-12-09', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(433, 9, 21, '2025-12-09', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(434, 10, 22, '2025-12-09', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(435, 11, 17, '2025-12-09', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(436, 12, 18, '2025-12-09', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(437, 6, 19, '2025-12-09', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(438, 7, 21, '2025-12-09', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(439, 8, 22, '2025-12-09', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(440, 9, 17, '2025-12-09', '14:00:00', '16:13:00', '80000.00', '70000.00', '2D'),
(441, 10, 18, '2025-12-09', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(442, 11, 19, '2025-12-09', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(443, 12, 21, '2025-12-09', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(444, 6, 22, '2025-12-09', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(445, 7, 17, '2025-12-09', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(446, 8, 18, '2025-12-09', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(447, 9, 19, '2025-12-09', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(448, 10, 21, '2025-12-09', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(449, 11, 22, '2025-12-09', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(450, 12, 17, '2025-12-09', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(451, 6, 18, '2025-12-09', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(452, 7, 19, '2025-12-09', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(453, 8, 21, '2025-12-09', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(454, 9, 22, '2025-12-09', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(455, 10, 17, '2025-12-09', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(456, 11, 18, '2025-12-09', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(457, 12, 19, '2025-12-09', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(458, 6, 21, '2025-12-09', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(459, 7, 22, '2025-12-09', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(460, 8, 17, '2025-12-10', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(461, 9, 18, '2025-12-10', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(462, 10, 19, '2025-12-10', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(463, 11, 21, '2025-12-10', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(464, 12, 22, '2025-12-10', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(465, 6, 17, '2025-12-10', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(466, 7, 18, '2025-12-10', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(467, 8, 19, '2025-12-10', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(468, 9, 21, '2025-12-10', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(469, 10, 22, '2025-12-10', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(470, 11, 17, '2025-12-10', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(471, 12, 18, '2025-12-10', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(472, 6, 19, '2025-12-10', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(473, 7, 21, '2025-12-10', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(474, 8, 22, '2025-12-10', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(475, 9, 17, '2025-12-10', '16:30:00', '18:43:00', '80000.00', '70000.00', '2D'),
(476, 10, 18, '2025-12-10', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(477, 11, 19, '2025-12-10', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(478, 12, 21, '2025-12-10', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(479, 6, 22, '2025-12-10', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(480, 7, 17, '2025-12-10', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(481, 8, 18, '2025-12-10', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(482, 9, 19, '2025-12-10', '19:00:00', '21:13:00', '70000.00', '60000.00', '2D'),
(483, 10, 21, '2025-12-10', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(484, 11, 22, '2025-12-10', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(485, 12, 17, '2025-12-10', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(486, 6, 18, '2025-12-10', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D'),
(487, 7, 19, '2025-12-10', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(488, 8, 21, '2025-12-10', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(489, 9, 22, '2025-12-10', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(490, 10, 17, '2025-12-11', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(491, 11, 18, '2025-12-11', '09:00:00', '10:57:00', '80000.00', '70000.00', '2D'),
(492, 12, 19, '2025-12-11', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(493, 6, 21, '2025-12-11', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(494, 7, 22, '2025-12-11', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(495, 8, 17, '2025-12-11', '11:30:00', '13:32:00', '80000.00', '65000.00', '2D'),
(496, 9, 18, '2025-12-11', '11:30:00', '13:43:00', '70000.00', '60000.00', '2D'),
(497, 10, 19, '2025-12-11', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(498, 11, 21, '2025-12-11', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(499, 12, 22, '2025-12-11', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(500, 6, 17, '2025-12-11', '14:00:00', '16:00:00', '85000.00', '70000.00', '3D'),
(501, 7, 18, '2025-12-11', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(502, 8, 19, '2025-12-11', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(503, 9, 21, '2025-12-11', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(504, 10, 22, '2025-12-11', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(505, 11, 17, '2025-12-11', '16:30:00', '18:27:00', '80000.00', '70000.00', '2D'),
(506, 12, 18, '2025-12-11', '16:30:00', '18:32:00', '85000.00', '70000.00', '3D'),
(507, 6, 19, '2025-12-11', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(508, 7, 21, '2025-12-11', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(509, 8, 22, '2025-12-11', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(510, 9, 17, '2025-12-11', '19:00:00', '21:13:00', '80000.00', '70000.00', '2D'),
(511, 10, 18, '2025-12-11', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(512, 11, 19, '2025-12-11', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(513, 12, 21, '2025-12-11', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(514, 6, 22, '2025-12-11', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(515, 7, 17, '2025-12-11', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(516, 8, 18, '2025-12-11', '21:30:00', '23:32:00', '80000.00', '65000.00', '2D'),
(517, 9, 19, '2025-12-11', '21:30:00', '23:43:00', '70000.00', '60000.00', '2D'),
(518, 10, 21, '2025-12-11', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(519, 11, 22, '2025-12-11', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(520, 12, 17, '2025-12-12', '09:00:00', '11:02:00', '85000.00', '70000.00', '3D'),
(521, 6, 18, '2025-12-12', '09:00:00', '11:00:00', '85000.00', '70000.00', '3D'),
(522, 7, 19, '2025-12-12', '09:00:00', '11:00:00', '75000.00', '65000.00', '3D'),
(523, 8, 21, '2025-12-12', '09:00:00', '11:02:00', '80000.00', '65000.00', '2D'),
(524, 9, 22, '2025-12-12', '09:00:00', '11:13:00', '70000.00', '60000.00', '2D'),
(525, 10, 17, '2025-12-12', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(526, 11, 18, '2025-12-12', '11:30:00', '13:27:00', '80000.00', '70000.00', '2D'),
(527, 12, 19, '2025-12-12', '11:30:00', '13:32:00', '85000.00', '70000.00', '3D'),
(528, 6, 21, '2025-12-12', '11:30:00', '13:30:00', '85000.00', '70000.00', '3D'),
(529, 7, 22, '2025-12-12', '11:30:00', '13:30:00', '75000.00', '65000.00', '3D'),
(530, 8, 17, '2025-12-12', '14:00:00', '16:02:00', '80000.00', '65000.00', '2D'),
(531, 9, 18, '2025-12-12', '14:00:00', '16:13:00', '70000.00', '60000.00', '2D'),
(532, 10, 19, '2025-12-12', '14:00:00', '16:00:00', '75000.00', '65000.00', '3D'),
(533, 11, 21, '2025-12-12', '14:00:00', '15:57:00', '80000.00', '70000.00', '2D'),
(534, 12, 22, '2025-12-12', '14:00:00', '16:02:00', '85000.00', '70000.00', '3D'),
(535, 6, 17, '2025-12-12', '16:30:00', '18:30:00', '85000.00', '70000.00', '3D'),
(536, 7, 18, '2025-12-12', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(537, 8, 19, '2025-12-12', '16:30:00', '18:32:00', '80000.00', '65000.00', '2D'),
(538, 9, 21, '2025-12-12', '16:30:00', '18:43:00', '70000.00', '60000.00', '2D'),
(539, 10, 22, '2025-12-12', '16:30:00', '18:30:00', '75000.00', '65000.00', '3D'),
(540, 11, 17, '2025-12-12', '19:00:00', '20:57:00', '80000.00', '70000.00', '2D'),
(541, 12, 18, '2025-12-12', '19:00:00', '21:02:00', '85000.00', '70000.00', '3D'),
(542, 6, 19, '2025-12-12', '19:00:00', '21:00:00', '85000.00', '70000.00', '3D'),
(543, 7, 21, '2025-12-12', '19:00:00', '21:00:00', '75000.00', '65000.00', '3D'),
(544, 8, 22, '2025-12-12', '19:00:00', '21:02:00', '80000.00', '65000.00', '2D'),
(545, 9, 17, '2025-12-12', '21:30:00', '23:43:00', '80000.00', '70000.00', '2D'),
(546, 10, 18, '2025-12-12', '21:30:00', '23:30:00', '75000.00', '65000.00', '3D'),
(547, 11, 19, '2025-12-12', '21:30:00', '23:27:00', '80000.00', '70000.00', '2D'),
(548, 12, 21, '2025-12-12', '21:30:00', '23:32:00', '85000.00', '70000.00', '3D'),
(549, 6, 22, '2025-12-12', '21:30:00', '23:30:00', '85000.00', '70000.00', '3D');

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
(1, 'weekday', '2D', 'student', 'normal', '55000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(2, 'weekday', '2D', 'student', 'vip', '65000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(3, 'weekday', '2D', 'adult', 'normal', '65000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(4, 'weekday', '2D', 'adult', 'vip', '75000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(5, 'weekday', '3D', 'student', 'normal', '65000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(6, 'weekday', '3D', 'student', 'vip', '75000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(7, 'weekday', '3D', 'adult', 'normal', '75000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(8, 'weekday', '3D', 'adult', 'vip', '85000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(9, 'weekend', '2D', 'student', 'normal', '65000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(10, 'weekend', '2D', 'student', 'vip', '75000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(11, 'weekend', '2D', 'adult', 'normal', '75000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(12, 'weekend', '2D', 'adult', 'vip', '85000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(13, 'weekend', '3D', 'student', 'normal', '75000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(14, 'weekend', '3D', 'student', 'vip', '85000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(15, 'weekend', '3D', 'adult', 'normal', '85000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(16, 'weekend', '3D', 'adult', 'vip', '95000.00', '2025-11-22 14:55:53', '2025-11-22 14:55:53');

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
  `status` varchar(20) DEFAULT 'active' COMMENT 'Trạng thái: active, banned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `birth_date`, `tier_id`, `total_spending`, `created_at`, `role`, `status`) VALUES
(2, 'Nguyễn Đức Anh', 'anp93005@gmail.com', '$2y$10$CWyRPSmpryxfnvWJk.WU6ee587peAVpJ2WM.gPnWxn1EURYPTorwe', '0386036692', '2025-10-28', NULL, '0.00', '2025-11-15 20:56:13', 'admin', 'active'),
(3, 'nguyễn văn A', 'anh123@gmail.com', '$2y$10$VaYpeaUFxGUKFgO3yq7xVe.qnTi4VRvnnxK4ZiLkysvEq2jvCVr8.', '0386036636', '2000-10-12', NULL, '1755000.00', '2025-11-15 21:17:27', 'customer', 'active'),
(4, 'Bảo Châu', 'baochau06@gmail.com', '$2y$10$4msjaSiici7YXHciPSW0Cu/bYvTZuQRdlhm3ifOL4LTesvPmpzGxq', '0386036693', '2006-12-11', NULL, '0.00', '2025-11-18 22:28:02', 'staff', 'active'),
(5, 'anima', 'animaxer.sss@gmail.com', '$2y$10$s/LVCiKr91wviPGv9DpSRe.kZ4H1uoCR02PUnebGokW4hab3ry4T.', '0814983862', '2006-01-13', NULL, '0.00', '2025-11-25 20:17:34', 'customer', 'active');

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
  ADD KEY `fk_users_tiers` (`tier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `movie_genres`
--
ALTER TABLE `movie_genres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID quyền', AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID phân quyền', AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2511;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=550;

--
-- AUTO_INCREMENT for table `ticket_prices`
--
ALTER TABLE `ticket_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
