-- Bảng quản lý voucher/khuyến mãi
-- Tạo bảng vouchers để quản lý các chương trình khuyến mãi

CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề khuyến mãi',
  `description` text COMMENT 'Mô tả chi tiết khuyến mãi',
  `tag` varchar(50) DEFAULT 'general' COMMENT 'Loại khuyến mãi: general, flash, member, newuser, student, combo, etc.',
  `code` varchar(50) DEFAULT NULL COMMENT 'Mã giảm giá (hiển thị cho người dùng)',
  `discount_code_id` int DEFAULT NULL COMMENT 'ID mã giảm giá trong bảng discount_codes (liên kết với mã giảm giá thực tế)',
  `benefits` text COMMENT 'Danh sách lợi ích (mỗi lợi ích cách nhau bởi dấu |)',
  `period` varchar(255) DEFAULT NULL COMMENT 'Chuỗi hiển thị thời gian áp dụng (VD: "01/11/2025 - 31/12/2025")',
  `image` varchar(255) DEFAULT NULL COMMENT 'Hình ảnh banner khuyến mãi',
  `start_date` datetime DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `end_date` datetime DEFAULT NULL COMMENT 'Ngày kết thúc',
  `status` varchar(20) DEFAULT 'active' COMMENT 'Trạng thái: active, inactive, ongoing, upcoming, ended',
  `cta` varchar(100) DEFAULT 'Đặt vé ngay' COMMENT 'Text nút call-to-action',
  `cta_link` varchar(255) DEFAULT NULL COMMENT 'Link khi click CTA (mặc định: trang đặt vé)',
  `priority` int DEFAULT 0 COMMENT 'Độ ưu tiên hiển thị (số càng cao càng hiển thị trước)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`start_date`, `end_date`),
  KEY `idx_discount_code` (`discount_code_id`),
  KEY `idx_tag` (`tag`),
  CONSTRAINT `fk_vouchers_discount_code` FOREIGN KEY (`discount_code_id`) REFERENCES `discount_codes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Bảng quản lý voucher/khuyến mãi';

-- Thêm dữ liệu mẫu
INSERT INTO `vouchers` (`title`, `description`, `tag`, `code`, `benefits`, `period`, `start_date`, `end_date`, `status`, `cta`, `priority`) VALUES
('Giảm 20% cho thành viên mới', 'Đăng ký tài khoản mới và nhận ngay 20% giảm giá cho lần đặt vé đầu tiên. Áp dụng cho tất cả các suất chiếu.', 'newuser', 'WELCOME20', 'Giảm 20% cho lần đặt vé đầu tiên|Áp dụng cho tất cả suất chiếu|Không giới hạn số lượng vé|Có thể kết hợp với ưu đãi khác', '01/11/2025 - 31/12/2025', '2025-11-01 00:00:00', '2025-12-31 23:59:59', 'ongoing', 'Đăng ký ngay', 10),
('Flash Sale - Giảm 50% thứ 2 hàng tuần', 'Mỗi thứ 2 hàng tuần, giảm 50% cho tất cả vé xem phim. Chỉ áp dụng khi đặt vé online.', 'flash', 'MONDAY50', 'Giảm 50% cho tất cả vé|Chỉ áp dụng thứ 2 hàng tuần|Đặt vé online|Áp dụng cho tất cả phim', 'Mỗi thứ 2 hàng tuần', '2025-11-01 00:00:00', '2025-12-31 23:59:59', 'ongoing', 'Đặt vé ngay', 9),
('Combo bắp nước - Mua 2 tặng 1', 'Mua 2 combo bắp nước, tặng ngay 1 combo. Áp dụng khi mua kèm vé xem phim.', 'combo', 'COMBO2+1', 'Mua 2 combo tặng 1|Áp dụng khi mua kèm vé|Combo size lớn|Áp dụng tất cả các ngày', '01/11/2025 - 31/12/2025', '2025-11-01 00:00:00', '2025-12-31 23:59:59', 'ongoing', 'Xem menu', 8),
('Ưu đãi sinh viên - Giảm 30%', 'Sinh viên được giảm 30% khi xuất trình thẻ sinh viên hợp lệ. Áp dụng cho tất cả suất chiếu.', 'student', 'STUDENT30', 'Giảm 30% cho sinh viên|Xuất trình thẻ sinh viên|Áp dụng tất cả suất chiếu|Không giới hạn số lần sử dụng', 'Áp dụng thường xuyên', '2025-11-01 00:00:00', '2025-12-31 23:59:59', 'ongoing', 'Đặt vé ngay', 7),
('Thành viên VIP - Giảm 15% mỗi đơn', 'Thành viên hạng VIP được giảm 15% tự động cho mọi đơn đặt vé. Không cần nhập mã.', 'member', NULL, 'Giảm 15% tự động|Áp dụng cho mọi đơn hàng|Không cần nhập mã|Tích điểm thưởng', 'Áp dụng thường xuyên', '2025-11-01 00:00:00', '2025-12-31 23:59:59', 'ongoing', 'Nâng cấp VIP', 6),
('Black Friday - Giảm đến 70%', 'Chương trình Black Friday đặc biệt. Giảm giá lên đến 70% cho các suất chiếu được chọn.', 'flash', 'BLACKFRIDAY70', 'Giảm đến 70%|Áp dụng cho suất chiếu được chọn|Chỉ trong ngày Black Friday|Số lượng có hạn', '29/11/2025', '2025-11-29 00:00:00', '2025-11-29 23:59:59', 'upcoming', 'Sắp diễn ra', 5);

