-- ============================================
-- File: add_user_status.sql
-- Mô tả: Thêm cột status vào bảng users để quản lý trạng thái tài khoản
-- ============================================

-- Kiểm tra và thêm cột status vào bảng users (nếu chưa có)
SET @dbname = DATABASE();
SET @tablename = 'users';
SET @columnname = 'status';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE `', @tablename, '` ADD COLUMN `', @columnname, '` VARCHAR(20) DEFAULT ''active'' COMMENT ''Trạng thái: active, banned'' AFTER `role`;')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Cập nhật tất cả user hiện tại thành active (nếu chưa có giá trị)
UPDATE `users` SET `status` = 'active' WHERE `status` IS NULL OR `status` = '';

