-- Bảng lưu trữ thông tin liên hệ của khách hàng
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Họ và tên khách hàng',
  `email` varchar(255) NOT NULL COMMENT 'Email khách hàng',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Số điện thoại',
  `subject` varchar(255) NOT NULL COMMENT 'Chủ đề liên hệ',
  `message` text NOT NULL COMMENT 'Nội dung tin nhắn',
  `status` varchar(20) DEFAULT 'pending' COMMENT 'Trạng thái: pending, processing, resolved, closed',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng lưu trữ thông tin liên hệ của khách hàng';

