-- Tạo bảng permissions (quyền)
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên quyền (VD: manage_movies)',
  `display_name` varchar(255) NOT NULL COMMENT 'Tên hiển thị (VD: Quản lý phim)',
  `description` text COMMENT 'Mô tả quyền',
  `module` varchar(50) DEFAULT NULL COMMENT 'Module (VD: movies, users, bookings)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tạo bảng role_permissions (quyền của từng role)
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL COMMENT 'Vai trò: admin, staff, customer',
  `permission_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission` (`role`, `permission_id`),
  KEY `fk_role_permissions_permission` (`permission_id`),
  CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert các quyền cơ bản
INSERT INTO `permissions` (`name`, `display_name`, `description`, `module`) VALUES
-- Quản lý phim
('view_movies', 'Xem danh sách phim', 'Xem danh sách phim', 'movies'),
('create_movies', 'Thêm phim mới', 'Thêm phim mới vào hệ thống', 'movies'),
('edit_movies', 'Sửa phim', 'Chỉnh sửa thông tin phim', 'movies'),
('delete_movies', 'Xóa phim', 'Xóa phim khỏi hệ thống', 'movies'),

-- Quản lý thể loại
('view_genres', 'Xem thể loại', 'Xem danh sách thể loại phim', 'genres'),
('manage_genres', 'Quản lý thể loại', 'Thêm, sửa, xóa thể loại phim', 'genres'),

-- Quản lý rạp
('view_cinemas', 'Xem rạp', 'Xem danh sách rạp chiếu phim', 'cinemas'),
('manage_cinemas', 'Quản lý rạp', 'Thêm, sửa, xóa rạp chiếu phim', 'cinemas'),

-- Quản lý phòng phim
('view_rooms', 'Xem phòng phim', 'Xem danh sách phòng chiếu', 'rooms'),
('manage_rooms', 'Quản lý phòng phim', 'Thêm, sửa, xóa phòng chiếu', 'rooms'),

-- Quản lý suất chiếu
('view_showtimes', 'Xem suất chiếu', 'Xem danh sách suất chiếu', 'showtimes'),
('manage_showtimes', 'Quản lý suất chiếu', 'Thêm, sửa, xóa suất chiếu', 'showtimes'),

-- Quản lý đặt vé
('view_bookings', 'Xem đặt vé', 'Xem danh sách đơn đặt vé', 'bookings'),
('manage_bookings', 'Quản lý đặt vé', 'Xác nhận, cập nhật trạng thái đơn hàng', 'bookings'),

-- Quản lý người dùng
('view_users', 'Xem người dùng', 'Xem danh sách người dùng', 'users'),
('create_users', 'Thêm người dùng', 'Thêm người dùng mới', 'users'),
('edit_users', 'Sửa người dùng', 'Chỉnh sửa thông tin người dùng', 'users'),
('delete_users', 'Xóa người dùng', 'Xóa người dùng khỏi hệ thống', 'users'),

-- Quản lý phân quyền
('view_permissions', 'Xem phân quyền', 'Xem danh sách quyền và phân quyền', 'permissions'),
('manage_permissions', 'Quản lý phân quyền', 'Phân quyền cho các role', 'permissions'),

-- Quản lý bình luận
('view_comments', 'Xem bình luận', 'Xem danh sách bình luận', 'comments'),
('manage_comments', 'Quản lý bình luận', 'Xóa, ẩn bình luận', 'comments'),

-- Thống kê
('view_statistics', 'Xem thống kê', 'Xem các báo cáo thống kê', 'statistics'),

-- Dashboard
('view_dashboard', 'Xem dashboard', 'Xem trang tổng quan', 'dashboard');

-- Phân quyền cho Admin (tất cả quyền)
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'admin', `id` FROM `permissions`;

-- Phân quyền cho Staff (một số quyền)
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'staff', `id` FROM `permissions`
WHERE `name` IN (
  'view_movies', 'create_movies', 'edit_movies',
  'view_showtimes', 'manage_showtimes',
  'view_bookings', 'manage_bookings',
  'view_users',
  'view_comments', 'manage_comments',
  'view_dashboard'
);

-- Customer không có quyền quản trị (không insert gì)

