<?php

class Notification
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả notifications (cho admin hoặc user)
     */
    public function all($limit = 50, $userId = null)
    {
        try {
            if ($userId === null) {
                // Admin: lấy notifications không có user_id (cho admin)
                $sql = "SELECT * FROM notifications WHERE user_id IS NULL ORDER BY created_at DESC LIMIT :limit";
            } else {
                // User: lấy notifications của user cụ thể
                $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
            }
            $stmt = $this->conn->prepare($sql);
            if ($userId !== null) {
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in Notification->all(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy notifications chưa đọc (cho admin hoặc user)
     */
    public function getUnread($limit = 50, $userId = null)
    {
        try {
            // Kiểm tra xem cột user_id có tồn tại không
            $checkStmt = $this->conn->query("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            $hasUserIdColumn = $checkStmt->rowCount() > 0;
            
            if ($hasUserIdColumn) {
                if ($userId === null) {
                    // Admin: lấy notifications không có user_id (cho admin)
                    $sql = "SELECT * FROM notifications WHERE is_read = 0 AND (user_id IS NULL OR user_id = 0) ORDER BY created_at DESC LIMIT :limit";
                } else {
                    // User: lấy notifications của user cụ thể
                    $sql = "SELECT * FROM notifications WHERE is_read = 0 AND user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
                }
                $stmt = $this->conn->prepare($sql);
                if ($userId !== null) {
                    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
                }
            } else {
                // Nếu chưa có cột user_id, tất cả notifications đều là của admin
                if ($userId === null) {
                    $sql = "SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT :limit";
                } else {
                    // User không có notifications nếu chưa có cột user_id
                    return [];
                }
                $stmt = $this->conn->prepare($sql);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error in Notification->getUnread(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm số notifications chưa đọc (cho admin hoặc user)
     */
    public function countUnread($userId = null)
    {
        try {
            // Kiểm tra xem cột user_id có tồn tại không
            $checkStmt = $this->conn->query("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            $hasUserIdColumn = $checkStmt->rowCount() > 0;
            
            if ($hasUserIdColumn) {
                if ($userId === null) {
                    // Admin: đếm notifications không có user_id
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE is_read = 0 AND (user_id IS NULL OR user_id = 0)";
                } else {
                    // User: đếm notifications của user cụ thể
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE is_read = 0 AND user_id = :user_id";
                }
                $stmt = $this->conn->prepare($sql);
                if ($userId !== null) {
                    $stmt->execute([':user_id' => $userId]);
                } else {
                    $stmt->execute();
                }
            } else {
                // Nếu chưa có cột user_id, tất cả notifications đều là của admin
                if ($userId === null) {
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE is_read = 0";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();
                } else {
                    return 0;
                }
            }
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            error_log('Error in Notification->countUnread(): ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy notification theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM notifications WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Error in Notification->find(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo notification mới
     */
    public function insert($data)
    {
        try {
            // Kiểm tra xem cột user_id có tồn tại không
            $stmt = $this->conn->query("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            $hasUserIdColumn = $stmt->rowCount() > 0;
            
            if ($hasUserIdColumn) {
                $sql = "INSERT INTO notifications (type, title, message, related_id, user_id, is_read, created_at) 
                        VALUES (:type, :title, :message, :related_id, :user_id, :is_read, NOW())";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':type' => $data['type'] ?? 'booking',
                    ':title' => $data['title'] ?? '',
                    ':message' => $data['message'] ?? '',
                    ':related_id' => $data['related_id'] ?? null,
                    ':user_id' => $data['user_id'] ?? null,
                    ':is_read' => $data['is_read'] ?? 0
                ]);
            } else {
                // Nếu chưa có cột user_id, insert không có user_id (tương thích với bảng cũ)
                $sql = "INSERT INTO notifications (type, title, message, related_id, is_read, created_at) 
                        VALUES (:type, :title, :message, :related_id, :is_read, NOW())";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':type' => $data['type'] ?? 'booking',
                    ':title' => $data['title'] ?? '',
                    ':message' => $data['message'] ?? '',
                    ':related_id' => $data['related_id'] ?? null,
                    ':is_read' => $data['is_read'] ?? 0
                ]);
            }
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            error_log('Error in Notification->insert(): ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Đánh dấu notification là đã đọc
     */
    public function markAsRead($id, $userId = null)
    {
        try {
            if ($userId === null) {
                // Admin: đánh dấu notification của admin
                $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id IS NULL";
            } else {
                // User: đánh dấu notification của user cụ thể
                $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id";
            }
            $stmt = $this->conn->prepare($sql);
            if ($userId !== null) {
                $stmt->execute([':id' => $id, ':user_id' => $userId]);
            } else {
                $stmt->execute([':id' => $id]);
            }
            return true;
        } catch (Exception $e) {
            error_log('Error in Notification->markAsRead(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Đánh dấu tất cả notifications là đã đọc (cho admin hoặc user)
     */
    public function markAllAsRead($userId = null)
    {
        try {
            if ($userId === null) {
                // Admin: đánh dấu tất cả notifications của admin
                $sql = "UPDATE notifications SET is_read = 1 WHERE is_read = 0 AND user_id IS NULL";
            } else {
                // User: đánh dấu tất cả notifications của user cụ thể
                $sql = "UPDATE notifications SET is_read = 1 WHERE is_read = 0 AND user_id = :user_id";
            }
            $stmt = $this->conn->prepare($sql);
            if ($userId !== null) {
                $stmt->execute([':user_id' => $userId]);
            } else {
                $stmt->execute();
            }
            return true;
        } catch (Exception $e) {
            error_log('Error in Notification->markAllAsRead(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa notification
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM notifications WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log('Error in Notification->delete(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra xem đã có notification cho booking này chưa (tránh duplicate)
     */
    public function existsForBooking($bookingId, $userId = null, $title = null)
    {
        try {
            // Kiểm tra xem cột user_id có tồn tại không
            $checkStmt = $this->conn->query("SHOW COLUMNS FROM notifications LIKE 'user_id'");
            $hasUserIdColumn = $checkStmt->rowCount() > 0;
            
            if ($hasUserIdColumn) {
                if ($userId === null) {
                    // Admin: kiểm tra unread notifications không có user_id
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE type = 'booking' AND related_id = :booking_id AND is_read = 0 AND (user_id IS NULL OR user_id = 0)";
                    if ($title) {
                        $sql .= " AND title = :title";
                    }
                } else {
                    // User: kiểm tra unread notifications của user cụ thể
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE type = 'booking' AND related_id = :booking_id AND is_read = 0 AND user_id = :user_id";
                    if ($title) {
                        $sql .= " AND title = :title";
                    }
                }
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':booking_id', $bookingId, PDO::PARAM_INT);
                if ($userId !== null) {
                    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
                }
                if ($title) {
                    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
                }
            } else {
                // Nếu chưa có cột user_id, tất cả notifications đều là của admin
                if ($userId === null) {
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE type = 'booking' AND related_id = :booking_id AND is_read = 0";
                    if ($title) {
                        $sql .= " AND title = :title";
                    }
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':booking_id', $bookingId, PDO::PARAM_INT);
                    if ($title) {
                        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
                    }
                } else {
                    return false; // User không có notifications nếu chưa có cột user_id
                }
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            error_log('Error in Notification->existsForBooking(): ' . $e->getMessage());
            return false;
        }
    }
}

