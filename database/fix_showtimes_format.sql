-- Script sửa format của showtimes để khớp với format của phim
-- Chạy script này để đảm bảo format trong showtimes đúng

-- Cập nhật format của showtimes dựa trên format của phim
-- Phim 3D/IMAX/4DX -> showtime format = '3D'
-- Phim 2D -> showtime format = '2D'

UPDATE showtimes s
INNER JOIN movies m ON s.movie_id = m.id
SET s.format = 
    CASE 
        WHEN UPPER(TRIM(m.format)) IN ('3D', 'IMAX', '4DX') THEN '3D'
        ELSE '2D'
    END;

-- Xem kết quả sau khi cập nhật
SELECT 
    s.id AS showtime_id,
    s.movie_id,
    m.title AS movie_title,
    m.format AS movie_format,
    s.format AS showtime_format,
    s.show_date,
    s.start_time
FROM showtimes s
INNER JOIN movies m ON s.movie_id = m.id
ORDER BY s.id;

