<?php
// CONTACT MODEL - Tương tác với bảng contacts
// Chức năng: CRUD liên hệ, phân trang, lọc theo trạng thái/rạp
class Contact
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    /**
     * Lấy tất cả liên hệ
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy liên hệ với phân trang
     */
    public function paginate($page = 1, $perPage = 10, $status = null, $cinema_id = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xây dựng WHERE clause
            $whereConditions = [];
            $params = [];
            
            if ($status) {
                $whereConditions[] = "contacts.status = :status";
                $params[':status'] = $status;
            }
            
            if ($cinema_id !== null) {
                $whereConditions[] = "contacts.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinema_id;
            }
            
            $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : '';
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM contacts " . $whereClause;
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang với JOIN để lấy tên rạp
            $sql = "SELECT contacts.*, cinemas.name as cinema_name 
                    FROM contacts 
                    LEFT JOIN cinemas ON contacts.cinema_id = cinemas.id
                    {$whereClause}
                    ORDER BY contacts.created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage)
            ];
        } catch (Exception $e) {
            debug($e);
            return [
                'data' => [],
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0
            ];
        }
    }

    /**
     * Lấy liên hệ theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT contacts.*, cinemas.name as cinema_name 
                    FROM contacts 
                    LEFT JOIN cinemas ON contacts.cinema_id = cinemas.id
                    WHERE contacts.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Thêm liên hệ mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO contacts (name, email, phone, subject, message, cinema_id, user_id, status) 
                    VALUES (:name, :email, :phone, :subject, :message, :cinema_id, :user_id, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':subject' => $data['subject'] ?? null,
                ':message' => $data['message'] ?? null,
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':user_id' => $data['user_id'] ?? null,
                ':status' => $data['status'] ?? 'pending'
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật liên hệ
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE contacts SET 
                    name = :name,
                    email = :email,
                    phone = :phone,
                    subject = :subject,
                    message = :message,
                    cinema_id = :cinema_id,
                    status = :status
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':subject' => $data['subject'] ?? null,
                ':message' => $data['message'] ?? null,
                ':cinema_id' => $data['cinema_id'] ?? null,
                ':status' => $data['status'] ?? 'pending'
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật trạng thái
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE contacts SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa liên hệ
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM contacts WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Đếm số liên hệ theo trạng thái
     */
    public function countByStatus($status, $cinema_id = null)
    {
        try {
            $whereConditions = ["status = :status"];
            $params = [':status' => $status];
            
            if ($cinema_id !== null) {
                $whereConditions[] = "cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinema_id;
            }
            
            $whereClause = "WHERE " . implode(" AND ", $whereConditions);
            $sql = "SELECT COUNT(*) as total FROM contacts {$whereClause}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            debug($e);
            return 0;
        }
    }

    /**
     * Đếm tổng số liên hệ chưa xử lý (pending)
     */
    public function countPending()
    {
        return $this->countByStatus('pending');
    }
}

?>

