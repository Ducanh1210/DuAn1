-- Bảng quản lý giá vé
CREATE TABLE IF NOT EXISTS `ticket_prices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day_type` varchar(20) NOT NULL COMMENT 'weekday hoặc weekend',
  `format` varchar(10) NOT NULL COMMENT '2D hoặc 3D',
  `customer_type` varchar(20) NOT NULL COMMENT 'adult hoặc student',
  `seat_type` varchar(20) NOT NULL COMMENT 'normal hoặc vip',
  `base_price` decimal(10,2) NOT NULL COMMENT 'Giá cơ bản (VNĐ)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_price` (`day_type`, `format`, `customer_type`, `seat_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert giá vé mặc định
-- Giá cơ bản: Sinh viên 60k, Người lớn 70k (ghế thường)
-- VIP: +10k, 3D: +10k
-- Weekday (2-5): -5k, Weekend (6-7-CN): +5k

-- Thứ 2-5 (weekday) - giảm 5k
-- 2D
INSERT INTO `ticket_prices` (`day_type`, `format`, `customer_type`, `seat_type`, `base_price`) VALUES
('weekday', '2D', 'student', 'normal', 55000),  -- 60k - 5k
('weekday', '2D', 'student', 'vip', 65000),      -- 60k + 10k - 5k
('weekday', '2D', 'adult', 'normal', 65000),     -- 70k - 5k
('weekday', '2D', 'adult', 'vip', 75000),        -- 70k + 10k - 5k
-- 3D (+10k)
('weekday', '3D', 'student', 'normal', 65000),   -- 60k + 10k - 5k
('weekday', '3D', 'student', 'vip', 75000),      -- 60k + 10k + 10k - 5k
('weekday', '3D', 'adult', 'normal', 75000),     -- 70k + 10k - 5k
('weekday', '3D', 'adult', 'vip', 85000)         -- 70k + 10k + 10k - 5k
ON DUPLICATE KEY UPDATE `base_price` = VALUES(`base_price`);

-- Thứ 6-7-CN và ngày lễ (weekend) - tăng 5k
-- 2D
INSERT INTO `ticket_prices` (`day_type`, `format`, `customer_type`, `seat_type`, `base_price`) VALUES
('weekend', '2D', 'student', 'normal', 65000),  -- 60k + 5k
('weekend', '2D', 'student', 'vip', 75000),      -- 60k + 10k + 5k
('weekend', '2D', 'adult', 'normal', 75000),     -- 70k + 5k
('weekend', '2D', 'adult', 'vip', 85000),        -- 70k + 10k + 5k
-- 3D (+10k)
('weekend', '3D', 'student', 'normal', 75000),   -- 60k + 10k + 5k
('weekend', '3D', 'student', 'vip', 85000),       -- 60k + 10k + 10k + 5k
('weekend', '3D', 'adult', 'normal', 85000),     -- 70k + 10k + 5k
('weekend', '3D', 'adult', 'vip', 95000)         -- 70k + 10k + 10k + 5k
ON DUPLICATE KEY UPDATE `base_price` = VALUES(`base_price`);
