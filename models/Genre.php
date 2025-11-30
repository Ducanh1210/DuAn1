<?php
class Genre
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả thể loại phim
     */
    public function all()
    {
        try {
            $sql = "SELECT * FROM movie_genres ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            debug($e);
            return [];
        }
    }

    /**
     * Lấy thể loại theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM movie_genres WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Thêm thể loại mới
     */
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO movie_genres (name, description) VALUES (:name, :description)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Cập nhật thể loại
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE movie_genres SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Xóa thể loại
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM movie_genres WHERE id = :id";
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

