-- Migration: Thêm cột cinema_id vào bảng comments
-- Mục đích: Cho phép lọc và hiển thị bình luận theo rạp

-- Thêm cột cinema_id
ALTER TABLE `comments` 
ADD COLUMN `cinema_id` int DEFAULT NULL AFTER `movie_id`;

-- Thêm foreign key
ALTER TABLE `comments`
ADD CONSTRAINT `fk_comments_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Thêm index để tăng tốc truy vấn theo rạp
ALTER TABLE `comments`
ADD INDEX `idx_cinema_id` (`cinema_id`);

