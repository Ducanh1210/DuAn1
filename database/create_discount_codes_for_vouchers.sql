-- Tạo discount codes cho các voucher hiện có
-- Chạy script này để liên kết voucher với discount codes

-- Xóa các discount codes cũ nếu có (tùy chọn)
-- DELETE FROM discount_codes WHERE code IN ('WELCOME20', 'MONDAY50', 'COMBO2+1', 'STUDENT30', 'BLACKFRIDAY70');

-- Tạo discount codes cho các voucher
INSERT INTO discount_codes (code, discount_percent, start_date, end_date, status) VALUES
('WELCOME20', 20, '2025-11-01', '2025-12-31', 'active'),
('MONDAY50', 50, '2025-11-01', '2025-12-31', 'active'),
('COMBO2+1', 0, '2025-11-01', '2025-12-31', 'active'), -- Combo không giảm giá, chỉ tặng
('STUDENT30', 30, '2025-11-01', '2025-12-31', 'active'),
('BLACKFRIDAY70', 70, '2025-11-29', '2025-11-29', 'active')
ON DUPLICATE KEY UPDATE 
    discount_percent = VALUES(discount_percent),
    start_date = VALUES(start_date),
    end_date = VALUES(end_date),
    status = VALUES(status);

-- Cập nhật discount_code_id cho các voucher
UPDATE vouchers v
INNER JOIN discount_codes dc ON v.code = dc.code
SET v.discount_code_id = dc.id
WHERE v.code IN ('WELCOME20', 'MONDAY50', 'COMBO2+1', 'STUDENT30', 'BLACKFRIDAY70');

