-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2025 at 02:56 PM
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
-- Database: `duan`
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
(4, 3, 9, NULL, '2025-11-19 10:21:08', 'C12,C11', 'normal', NULL, 160000.00, 0.00, 160000.00, 'paid', NULL, 'BK2025111943C218', 1, 18),
(5, 3, 9, NULL, '2025-11-19 10:32:15', 'B7,B6,B5', 'normal', NULL, 240000.00, 0.00, 240000.00, 'paid', NULL, 'BK20251119F0FF11', 1, 18),
(6, 3, 9, NULL, '2025-11-19 12:53:17', 'C8,C7', 'normal', NULL, 160000.00, 0.00, 160000.00, 'cancelled', NULL, 'BK20251119D29273', 1, 18),
(7, 3, 9, NULL, '2025-11-19 13:23:29', 'A11', NULL, NULL, 80000.00, 0.00, 80000.00, 'paid', NULL, 'BK17635334097649', 1, 18),
(8, 3, 9, NULL, '2025-11-19 13:38:21', 'D11', NULL, NULL, 80000.00, 0.00, 80000.00, 'paid', NULL, 'BK17635343016235', 1, 18),
(10, 3, 9, NULL, '2025-11-19 23:29:59', 'A4,A5,A6,A7', NULL, NULL, 320000.00, 0.00, 320000.00, 'paid', NULL, 'BK17635697994209', 1, 18),
(11, 3, 9, NULL, '2025-11-19 23:34:04', 'B3,B4', NULL, NULL, 160000.00, 0.00, 160000.00, 'paid', NULL, 'BK17635700449534', 1, 18),
(12, 3, 9, NULL, '2025-11-19 23:41:37', 'B9', NULL, NULL, 80000.00, 0.00, 80000.00, 'paid', NULL, 'BK17635704977283', 1, 18),
(13, 3, 7, NULL, '2025-11-19 23:54:26', 'B8,B9', NULL, NULL, 180000.00, 0.00, 180000.00, 'pending', NULL, 'BK17635712663434', 1, 17),
(14, 3, 2, NULL, '2025-11-20 09:43:19', 'A5,A6,A7,A8,A9', NULL, NULL, 400000.00, 0.00, 400000.00, 'paid', NULL, 'BK17636065992196', 1, 18),
(15, 3, 4, NULL, '2025-11-20 19:46:25', 'A3,A4', NULL, NULL, 180000.00, 0.00, 180000.00, 'paid', NULL, 'BK17636427856547', 1, 17),
(16, 3, 2, NULL, '2025-11-20 21:31:24', 'B6', NULL, NULL, 55000.00, 0.00, 55000.00, 'pending', NULL, 'BK17636490845963', 1, 18),
(17, 3, 2, NULL, '2025-11-20 21:32:21', 'B6', NULL, NULL, 55000.00, 0.00, 55000.00, 'paid', NULL, 'BK17636491412783', 1, 18);

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
(6, 7, 'Quán kỳ nma', 'ko hay lắm đâu', 120, 'image/phim 4.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-20', '2025-11-23', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-14 19:38:50', '2025-11-20 21:14:48'),
(7, 3, 'Phòng trọ ma bầu', 'kinh dị ko nên xem', 120, 'image/phim 5.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-17', '2025-11-20', '3D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-14 20:27:20', '2025-11-17 09:52:58'),
(8, 1, 'Truy tìm long diên hương', 'cx hay lắm nha', 122, 'image/phim5.jpg', 'https://youtu.be/XjhAFebnNkM?si=vKFX_9ElyDAoSMMX', '2025-11-19', '2025-11-21', '2D', 'Tiếng Việt', 'Phụ Đề', 'C18', 'Việt Nam', 'active', '2025-11-15 11:43:43', '2025-11-19 09:39:00'),
(9, 1, 'TRỐN CHẠY TỬ THẦN-T18', 'Trong bối cảnh xã hội tương lai gần, Trốn Chạy Tử Thần là chương trình truyền hình ăn khách nhất, một cuộc thi sinh tồn khốc liệt nơi các thí sinh, được gọi là “Runners”, phải trốn chạy suốt 30 ngày khỏi sự truy đuổi của các sát thủ chuyên nghiệp. Mọi bước đi của họ đều được phát sóng công khai cho khán giả theo dõi và phần thưởng tiền mặt sẽ tăng lên sau mỗi ngày sống sót. Vì cần tiền cứu chữa cho cô con gái bệnh nặng, Ben...', 133, 'image/phim 3.jpg', 'https://youtu.be/NuOl156fv_c?si=98qM39lvGn18VcdI', '2025-11-19', '2025-11-20', '2D', 'Tiếng Anh', 'Phụ Đề', 'C16', 'Mỹ', 'active', '2025-11-15 22:40:20', '2025-11-19 07:29:12'),
(10, 6, 'MỘ ĐOM ĐÓM', 'Hai anh em Seita và Setsuko mất mẹ sau cuộc thả bom dữ dội của không quân Mỹ. Cả hai phải vật lộn để tồn tại ở Nhật Bản hậu Thế chiến II. Nhưng xã hội khắc nghiệt và chúng vật lộn tìm kiếm thức ăn cũng như thoát khỏi những khó khăn giữa chiến tranh.', 120, 'image/phim 6.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-17', '2025-11-19', '3D', 'Tiếng Việt', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-17 09:58:57', '2025-11-17 19:38:42'),
(11, 2, 'Tay Anh Giữ Một Vì Sao', 'Tay Anh Giữ Một Vì Sao” mang đến một làn gió mới trong dòng phim chiếu rạp hay khi kết hợp khéo léo giữa yếu tố hài hước và cảm xúc chân thành. Câu chuyện xoay quanh siêu sao Kang Jun Woo bỗng rơi vào chuỗi sự cố trớ trêu khiến anh vô tình “mắc kẹt” tại Việt Nam. Tại đây, anh gặp Thảo - cô gái bán cà phê giản dị nhưng mang trong mình khát vọng lớn lao. Những va chạm và hiểu lầm dần trở thành sợi dây gắn kết, giúp cả hai tìm thấy niềm tin, ước mơ và định nghĩa mới về tình yêu. Bộ phim không chỉ khiến khán giả bật cười bởi những tình huống duyên dáng mà còn chạm đến trái tim bằng câu chuyện nhân văn về sự đồng cảm và thay đổi.', 117, 'image/phim7.jpg', 'https://youtu.be/elpzTvcWy0Q?si=_CHytHk32w_WIgVd', '2025-11-20', '2025-11-22', '2D', 'Tiếng Anh', 'Phụ Đề', 'P', 'Việt Nam', 'active', '2025-11-19 09:35:10', '2025-11-19 09:35:10'),
(12, 2, 'Chị Ngã Em Nâng', 'Giữa muôn vàn phim chiếu rạp hay tháng 10, “Chị Ngã Em Nâng” nổi bật như một bản giao hòa đầy cảm xúc về tình thân và nghị lực con người. Bộ phim khắc họa hành trình của hai chị em Thương và Lực là những người lớn lên trong gia đình gắn bó với nghề làm nhang truyền thống. Với tuổi thơ nhiều mất mát, Thương trở thành điểm tựa duy nhất cho em trai, mang trong mình khát vọng đổi đời và niềm tin mãnh liệt vào tương lai. Thế nhưng, khi thành công đến, sự kỳ vọng và áp lực vô tình khiến tình chị em rạn nứt, đẩy họ đến những lựa chọn đau lòng. “Chị Ngã Em Nâng” chạm đến trái tim người xem bằng những giá trị nhân văn sâu sắc, về tình thương, sự bao dung và ý nghĩa của hai chữ “gia đình”.', 122, 'image/hh.jpg', 'https://youtu.be/_ygZTJBJkJ4?si=u8Extq4lLZlTT5Go', '2025-11-20', '2025-11-22', '3D', 'Tiếng Việt', 'Phụ Đề', 'C13', 'Việt Nam', 'active', '2025-11-19 09:40:28', '2025-11-19 09:40:28');

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
(9, 17, 'vnpay', '15273478', '2025-11-20 14:33:33', 55000.00, 0.00, 55000.00, 'paid');

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
(18, 1, 'R2', 'Phòng Chiếu 2', 130),
(19, 1, 'R3', 'Phòng Chiếu 3', 150),
(21, 2, 'R1', 'Phòng Chiếu 1', 100),
(22, 2, 'R2', 'Phòng Chiếu 2', 150),
(23, 3, 'S1', 'Phòng S1', 150),
(24, 3, 'S2', 'Phòng S2', 170);

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
(121, 18, 'A', 1, 'normal', NULL, 'active'),
(122, 18, 'A', 2, 'normal', NULL, 'active'),
(123, 18, 'A', 3, 'normal', NULL, 'active'),
(124, 18, 'A', 4, 'vip', 0.00, 'available'),
(125, 18, 'A', 5, 'vip', 0.00, 'available'),
(126, 18, 'A', 6, 'vip', 0.00, 'available'),
(127, 18, 'A', 7, 'vip', 0.00, 'available'),
(128, 18, 'A', 8, 'vip', 0.00, 'available'),
(129, 18, 'A', 9, 'vip', 0.00, 'available'),
(130, 18, 'A', 10, 'vip', 0.00, 'available'),
(131, 18, 'A', 11, 'vip', 0.00, 'available'),
(132, 18, 'A', 12, 'vip', 0.00, 'available'),
(133, 18, 'A', 13, 'vip', 0.00, 'available'),
(134, 18, 'A', 14, 'vip', 0.00, 'available'),
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
(1020, 21, 'J', 12, 'normal', 0.00, 'available'),
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
(1069, 23, 'D', 4, 'normal', 0.00, 'maintenance'),
(1070, 23, 'D', 5, 'normal', 0.00, 'maintenance'),
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
(1621, 24, 'A', 1, 'normal', 0.00, 'available'),
(1622, 24, 'A', 2, 'normal', 0.00, 'available'),
(1623, 24, 'A', 3, 'normal', 0.00, 'available'),
(1624, 24, 'A', 4, 'normal', 0.00, 'available'),
(1625, 24, 'A', 5, 'normal', 0.00, 'available'),
(1626, 24, 'A', 6, 'normal', 0.00, 'available'),
(1627, 24, 'A', 7, 'normal', 0.00, 'available'),
(1628, 24, 'A', 8, 'normal', 0.00, 'available'),
(1629, 24, 'A', 9, 'normal', 0.00, 'available'),
(1630, 24, 'A', 10, 'normal', 0.00, 'available'),
(1631, 24, 'A', 11, 'normal', 0.00, 'available'),
(1632, 24, 'A', 12, 'normal', 0.00, 'available'),
(1633, 24, 'A', 13, 'normal', 0.00, 'available'),
(1634, 24, 'A', 14, 'normal', 0.00, 'available'),
(1635, 24, 'A', 15, 'normal', 0.00, 'available'),
(1636, 24, 'A', 16, 'normal', 0.00, 'available'),
(1637, 24, 'A', 17, 'normal', 0.00, 'available'),
(1638, 24, 'B', 1, 'normal', 0.00, 'available'),
(1639, 24, 'B', 2, 'normal', 0.00, 'available'),
(1640, 24, 'B', 3, 'normal', 0.00, 'available'),
(1641, 24, 'B', 4, 'normal', 0.00, 'available'),
(1642, 24, 'B', 5, 'normal', 0.00, 'available'),
(1643, 24, 'B', 6, 'normal', 0.00, 'available'),
(1644, 24, 'B', 7, 'normal', 0.00, 'available'),
(1645, 24, 'B', 8, 'normal', 0.00, 'available'),
(1646, 24, 'B', 9, 'normal', 0.00, 'available'),
(1647, 24, 'B', 10, 'normal', 0.00, 'available'),
(1648, 24, 'B', 11, 'normal', 0.00, 'available'),
(1649, 24, 'B', 12, 'normal', 0.00, 'available'),
(1650, 24, 'B', 13, 'normal', 0.00, 'available'),
(1651, 24, 'B', 14, 'normal', 0.00, 'available'),
(1652, 24, 'B', 15, 'normal', 0.00, 'available'),
(1653, 24, 'B', 16, 'normal', 0.00, 'available'),
(1654, 24, 'B', 17, 'normal', 0.00, 'available'),
(1655, 24, 'C', 1, 'normal', 0.00, 'available'),
(1656, 24, 'C', 2, 'normal', 0.00, 'available'),
(1657, 24, 'C', 3, 'normal', 0.00, 'available'),
(1658, 24, 'C', 4, 'normal', 0.00, 'available'),
(1659, 24, 'C', 5, 'normal', 0.00, 'available'),
(1660, 24, 'C', 6, 'normal', 0.00, 'available'),
(1661, 24, 'C', 7, 'normal', 0.00, 'available'),
(1662, 24, 'C', 8, 'normal', 0.00, 'available'),
(1663, 24, 'C', 9, 'normal', 0.00, 'available'),
(1664, 24, 'C', 10, 'normal', 0.00, 'available'),
(1665, 24, 'C', 11, 'normal', 0.00, 'available'),
(1666, 24, 'C', 12, 'normal', 0.00, 'available'),
(1667, 24, 'C', 13, 'normal', 0.00, 'available'),
(1668, 24, 'C', 14, 'normal', 0.00, 'available'),
(1669, 24, 'C', 15, 'normal', 0.00, 'available'),
(1670, 24, 'C', 16, 'normal', 0.00, 'available'),
(1671, 24, 'C', 17, 'normal', 0.00, 'available'),
(1672, 24, 'D', 1, 'normal', 0.00, 'available'),
(1673, 24, 'D', 2, 'normal', 0.00, 'available'),
(1674, 24, 'D', 3, 'normal', 0.00, 'available'),
(1675, 24, 'D', 4, 'normal', 0.00, 'available'),
(1676, 24, 'D', 5, 'normal', 0.00, 'available'),
(1677, 24, 'D', 6, 'normal', 0.00, 'available'),
(1678, 24, 'D', 7, 'normal', 0.00, 'available'),
(1679, 24, 'D', 8, 'normal', 0.00, 'available'),
(1680, 24, 'D', 9, 'normal', 0.00, 'available'),
(1681, 24, 'D', 10, 'normal', 0.00, 'available'),
(1682, 24, 'D', 11, 'normal', 0.00, 'available'),
(1683, 24, 'D', 12, 'normal', 0.00, 'available'),
(1684, 24, 'D', 13, 'normal', 0.00, 'available'),
(1685, 24, 'D', 14, 'normal', 0.00, 'available'),
(1686, 24, 'D', 15, 'normal', 0.00, 'available'),
(1687, 24, 'D', 16, 'normal', 0.00, 'available'),
(1688, 24, 'D', 17, 'normal', 0.00, 'available'),
(1689, 24, 'E', 1, 'normal', 0.00, 'available'),
(1690, 24, 'E', 2, 'normal', 0.00, 'available'),
(1691, 24, 'E', 3, 'normal', 0.00, 'available'),
(1692, 24, 'E', 4, 'normal', 0.00, 'available'),
(1693, 24, 'E', 5, 'normal', 0.00, 'available'),
(1694, 24, 'E', 6, 'normal', 0.00, 'available'),
(1695, 24, 'E', 7, 'normal', 0.00, 'available'),
(1696, 24, 'E', 8, 'normal', 0.00, 'available'),
(1697, 24, 'E', 9, 'normal', 0.00, 'available'),
(1698, 24, 'E', 10, 'normal', 0.00, 'available'),
(1699, 24, 'E', 11, 'normal', 0.00, 'available'),
(1700, 24, 'E', 12, 'normal', 0.00, 'available'),
(1701, 24, 'E', 13, 'normal', 0.00, 'available'),
(1702, 24, 'E', 14, 'normal', 0.00, 'available'),
(1703, 24, 'E', 15, 'normal', 0.00, 'available'),
(1704, 24, 'E', 16, 'normal', 0.00, 'available'),
(1705, 24, 'E', 17, 'normal', 0.00, 'available'),
(1706, 24, 'F', 1, 'normal', 0.00, 'available'),
(1707, 24, 'F', 2, 'normal', 0.00, 'available'),
(1708, 24, 'F', 3, 'normal', 0.00, 'available'),
(1709, 24, 'F', 4, 'normal', 0.00, 'available'),
(1710, 24, 'F', 5, 'normal', 0.00, 'available'),
(1711, 24, 'F', 6, 'normal', 0.00, 'available'),
(1712, 24, 'F', 7, 'normal', 0.00, 'available'),
(1713, 24, 'F', 8, 'normal', 0.00, 'available'),
(1714, 24, 'F', 9, 'normal', 0.00, 'available'),
(1715, 24, 'F', 10, 'normal', 0.00, 'available'),
(1716, 24, 'F', 11, 'normal', 0.00, 'available'),
(1717, 24, 'F', 12, 'normal', 0.00, 'available'),
(1718, 24, 'F', 13, 'normal', 0.00, 'available'),
(1719, 24, 'F', 14, 'normal', 0.00, 'available'),
(1720, 24, 'F', 15, 'normal', 0.00, 'available'),
(1721, 24, 'F', 16, 'normal', 0.00, 'available'),
(1722, 24, 'F', 17, 'normal', 0.00, 'available'),
(1723, 24, 'G', 1, 'normal', 0.00, 'available'),
(1724, 24, 'G', 2, 'normal', 0.00, 'available'),
(1725, 24, 'G', 3, 'normal', 0.00, 'available'),
(1726, 24, 'G', 4, 'normal', 0.00, 'available'),
(1727, 24, 'G', 5, 'normal', 0.00, 'available'),
(1728, 24, 'G', 6, 'normal', 0.00, 'available'),
(1729, 24, 'G', 7, 'normal', 0.00, 'available'),
(1730, 24, 'G', 8, 'normal', 0.00, 'available'),
(1731, 24, 'G', 9, 'normal', 0.00, 'available'),
(1732, 24, 'G', 10, 'normal', 0.00, 'available'),
(1733, 24, 'G', 11, 'normal', 0.00, 'available'),
(1734, 24, 'G', 12, 'normal', 0.00, 'available'),
(1735, 24, 'G', 13, 'normal', 0.00, 'available'),
(1736, 24, 'G', 14, 'normal', 0.00, 'available'),
(1737, 24, 'G', 15, 'normal', 0.00, 'available'),
(1738, 24, 'G', 16, 'normal', 0.00, 'available'),
(1739, 24, 'G', 17, 'normal', 0.00, 'available'),
(1740, 24, 'H', 1, 'normal', 0.00, 'available'),
(1741, 24, 'H', 2, 'normal', 0.00, 'available'),
(1742, 24, 'H', 3, 'normal', 0.00, 'available'),
(1743, 24, 'H', 4, 'normal', 0.00, 'available'),
(1744, 24, 'H', 5, 'normal', 0.00, 'available'),
(1745, 24, 'H', 6, 'normal', 0.00, 'available'),
(1746, 24, 'H', 7, 'normal', 0.00, 'available'),
(1747, 24, 'H', 8, 'normal', 0.00, 'available'),
(1748, 24, 'H', 9, 'normal', 0.00, 'available'),
(1749, 24, 'H', 10, 'normal', 0.00, 'available'),
(1750, 24, 'H', 11, 'normal', 0.00, 'available'),
(1751, 24, 'H', 12, 'normal', 0.00, 'available'),
(1752, 24, 'H', 13, 'normal', 0.00, 'available'),
(1753, 24, 'H', 14, 'normal', 0.00, 'available'),
(1754, 24, 'H', 15, 'normal', 0.00, 'available'),
(1755, 24, 'H', 16, 'normal', 0.00, 'available'),
(1756, 24, 'H', 17, 'normal', 0.00, 'available'),
(1757, 24, 'I', 1, 'normal', 0.00, 'available'),
(1758, 24, 'I', 2, 'normal', 0.00, 'available'),
(1759, 24, 'I', 3, 'normal', 0.00, 'available'),
(1760, 24, 'I', 4, 'normal', 0.00, 'available'),
(1761, 24, 'I', 5, 'normal', 0.00, 'available'),
(1762, 24, 'I', 6, 'normal', 0.00, 'available'),
(1763, 24, 'I', 7, 'normal', 0.00, 'available'),
(1764, 24, 'I', 8, 'normal', 0.00, 'available'),
(1765, 24, 'I', 9, 'normal', 0.00, 'available'),
(1766, 24, 'I', 10, 'normal', 0.00, 'available'),
(1767, 24, 'I', 11, 'normal', 0.00, 'available'),
(1768, 24, 'I', 12, 'normal', 0.00, 'available'),
(1769, 24, 'I', 13, 'normal', 0.00, 'available'),
(1770, 24, 'I', 14, 'normal', 0.00, 'available'),
(1771, 24, 'I', 15, 'normal', 0.00, 'available'),
(1772, 24, 'I', 16, 'normal', 0.00, 'available'),
(1773, 24, 'I', 17, 'normal', 0.00, 'available'),
(1774, 24, 'J', 1, 'normal', 0.00, 'available'),
(1775, 24, 'J', 2, 'normal', 0.00, 'available'),
(1776, 24, 'J', 3, 'normal', 0.00, 'available'),
(1777, 24, 'J', 4, 'normal', 0.00, 'available'),
(1778, 24, 'J', 5, 'normal', 0.00, 'available'),
(1779, 24, 'J', 6, 'normal', 0.00, 'available'),
(1780, 24, 'J', 7, 'normal', 0.00, 'available'),
(1781, 24, 'J', 8, 'normal', 0.00, 'available'),
(1782, 24, 'J', 9, 'normal', 0.00, 'available'),
(1783, 24, 'J', 10, 'normal', 0.00, 'available'),
(1784, 24, 'J', 11, 'normal', 0.00, 'available'),
(1785, 24, 'J', 12, 'normal', 0.00, 'available'),
(1786, 24, 'J', 13, 'normal', 0.00, 'available'),
(1787, 24, 'J', 14, 'normal', 0.00, 'available'),
(1788, 24, 'J', 15, 'normal', 0.00, 'available'),
(1789, 24, 'J', 16, 'normal', 0.00, 'available'),
(1790, 24, 'J', 17, 'normal', 0.00, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `movie_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `show_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `adult_price` decimal(10,2) DEFAULT NULL,
  `student_price` decimal(10,2) DEFAULT NULL,
  `format` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`id`, `movie_id`, `room_id`, `show_date`, `start_time`, `end_time`, `adult_price`, `student_price`, `format`) VALUES
(1, 8, 17, '2025-11-19', '15:00:00', '16:30:00', 80000.00, 65000.00, '2D'),
(2, 7, 18, '2025-11-20', '16:00:00', '17:00:00', 75000.00, 65000.00, '3D'),
(3, 6, 17, '2025-11-20', '15:00:00', '17:00:00', 85000.00, 70000.00, '3D'),
(4, 8, 17, '2025-11-21', '17:30:00', '19:32:00', 80000.00, 65000.00, '2D'),
(5, 6, 18, '2025-11-19', '14:00:00', '16:00:00', 75000.00, 60000.00, '3D'),
(6, 9, 19, '2025-11-20', '15:00:00', '17:13:00', 70000.00, 60000.00, '2D'),
(7, 9, 17, '2025-11-19', '10:30:00', '12:43:00', 80000.00, 70000.00, '2D'),
(8, 7, 21, '2025-11-21', '13:45:00', '15:45:00', 75000.00, 60000.00, '3D'),
(9, 9, 18, '2025-11-19', '09:30:00', '11:43:00', 80000.00, 70000.00, '2D');

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
(1, 'weekday', '2D', 'student', 'normal', 55000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(2, 'weekday', '2D', 'student', 'vip', 65000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(3, 'weekday', '2D', 'adult', 'normal', 65000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(4, 'weekday', '2D', 'adult', 'vip', 75000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(5, 'weekday', '3D', 'student', 'normal', 65000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(6, 'weekday', '3D', 'student', 'vip', 75000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(7, 'weekday', '3D', 'adult', 'normal', 75000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(8, 'weekday', '3D', 'adult', 'vip', 85000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(9, 'weekend', '2D', 'student', 'normal', 65000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(10, 'weekend', '2D', 'student', 'vip', 75000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(11, 'weekend', '2D', 'adult', 'normal', 75000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(12, 'weekend', '2D', 'adult', 'vip', 85000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(13, 'weekend', '3D', 'student', 'normal', 75000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(14, 'weekend', '3D', 'student', 'vip', 85000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(15, 'weekend', '3D', 'adult', 'normal', 85000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53'),
(16, 'weekend', '3D', 'adult', 'vip', 95000.00, '2025-11-22 14:55:53', '2025-11-22 14:55:53');

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
(2, 'Nguyễn Đức Anh', 'anp93005@gmail.com', '$2y$10$CWyRPSmpryxfnvWJk.WU6ee587peAVpJ2WM.gPnWxn1EURYPTorwe', '0386036692', '2025-10-28', NULL, 0.00, '2025-11-15 20:56:13', 'admin', 'active'),
(3, 'nguyễn văn A', 'anh123@gmail.com', '$2y$10$VaYpeaUFxGUKFgO3yq7xVe.qnTi4VRvnnxK4ZiLkysvEq2jvCVr8.', '0386036636', '2000-10-12', NULL, 1755000.00, '2025-11-15 21:17:27', 'customer', 'active'),
(4, 'Bảo Châu', 'baochau06@gmail.com', '$2y$10$4msjaSiici7YXHciPSW0Cu/bYvTZuQRdlhm3ifOL4LTesvPmpzGxq', '0386036693', '2006-12-11', NULL, 0.00, '2025-11-18 22:28:02', 'staff', 'active');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1791;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket_prices`
--
ALTER TABLE `ticket_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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

--
-- Update showtimes format based on movie format
-- This ensures showtimes.format matches the movie format
--
UPDATE showtimes s
INNER JOIN movies m ON s.movie_id = m.id
SET s.format = 
    CASE 
        WHEN UPPER(TRIM(m.format)) IN ('3D', 'IMAX', '4DX') THEN '3D'
        ELSE '2D'
    END;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
