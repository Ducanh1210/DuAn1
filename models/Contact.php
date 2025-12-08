<?php
class Contact
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
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
    public function paginate($page = 1, $perPage = 10, $status = null, $cinemaId = null)
    {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Luôn JOIN để lấy thông tin rạp
            $joinClause = "LEFT JOIN users ON contacts.user_id = users.id 
                          LEFT JOIN cinemas ON users.cinema_id = cinemas.id";
            
            // Xây dựng WHERE clause
            $whereClause = '';
            $params = [];
            
            if ($cinemaId) {
                $whereClause = "WHERE users.cinema_id = :cinema_id";
                $params[':cinema_id'] = $cinemaId;
            }
            
            if ($status) {
                if ($whereClause) {
                    $whereClause .= " AND contacts.status = :status";
                } else {
                    $whereClause = "WHERE contacts.status = :status";
                }
                $params[':status'] = $status;
            }
            
            // Lấy tổng số bản ghi
            $countSql = "SELECT COUNT(*) as total FROM contacts {$joinClause} {$whereClause}";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Lấy dữ liệu phân trang với thông tin rạp
            $selectFields = "contacts.*, cinemas.name AS cinema_name, cinemas.id AS cinema_id";
            
            $sql = "SELECT {$selectFields} FROM contacts {$joinClause} {$whereClause}
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
            $sql = "SELECT * FROM contacts WHERE id = :id";
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
            $sql = "INSERT INTO contacts (name, email, phone, subject, message, status) 
                    VALUES (:name, :email, :phone, :subject, :message, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':subject' => $data['subject'] ?? null,
                ':message' => $data['message'] ?? null,
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
    public function countByStatus($status)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM contacts WHERE status = :status";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status' => $status]);
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

