-- Fix AUTO_INCREMENT cho bảng showtimes
-- Chạy script này nếu bảng showtimes chưa có AUTO_INCREMENT cho cột id

-- Thêm PRIMARY KEY (bỏ qua lỗi nếu đã có)
-- ALTER TABLE `showtimes` ADD PRIMARY KEY (`id`);

-- Thêm AUTO_INCREMENT cho cột id
ALTER TABLE `showtimes` 
MODIFY COLUMN `id` int NOT NULL AUTO_INCREMENT;

-- Reset AUTO_INCREMENT về giá trị tiếp theo
SET @max_id = (SELECT COALESCE(MAX(id), 0) FROM showtimes);
SET @sql = CONCAT('ALTER TABLE showtimes AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

