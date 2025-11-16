<?php
class Cinema
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả rạp
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM cinemas ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy rạp theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM cinemas WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Thêm rạp mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO cinemas (name, address) VALUES (:name, :address)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':address' => $data['address'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật rạp
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE cinemas SET name = :name, address = :address WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':address' => $data['address'] ?? null
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa rạp
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM cinemas WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }
}

?>
