<?php
// PAYMENT MODEL - Tương tác với bảng payments
// Chức năng: CRUD thanh toán, lưu thông tin giao dịch VNPay
class Payment
{
    public $conn; // Kết nối database (PDO)

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    // Thêm thông tin thanh toán vào database
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO payments (
                booking_id,
                method,
                transaction_code,
                payment_date,
                total_amount,
                discount_amount,
                final_amount,
                status
            ) VALUES (
                :booking_id,
                :method,
                :transaction_code,
                :payment_date,
                :total_amount,
                :discount_amount,
                :final_amount,
                :status
            )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':booking_id' => $data['booking_id'] ?? null,
                ':method' => $data['method'] ?? null,
                ':transaction_code' => $data['transaction_code'] ?? null,
                ':payment_date' => $data['payment_date'] ?? date('Y-m-d H:i:s'),
                ':total_amount' => $data['total_amount'] ?? null,
                ':discount_amount' => $data['discount_amount'] ?? 0,
                ':final_amount' => $data['final_amount'] ?? null,
                ':status' => $data['status'] ?? 'pending'
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    // Cập nhật thông tin thanh toán
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE payments SET 
                transaction_code = :transaction_code,
                payment_date = :payment_date,
                status = :status
                WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':transaction_code' => $data['transaction_code'] ?? null,
                ':payment_date' => $data['payment_date'] ?? date('Y-m-d H:i:s'),
                ':status' => $data['status'] ?? 'pending'
            ]);
            return true;
        } catch (Exception $e) {
            debug($e);
            return false;
        }
    }

    /**
     * Tìm thanh toán theo booking_id
     */
    public function findByBookingId($bookingId)
    {
        try {
            $sql = "SELECT * FROM payments WHERE booking_id = :booking_id ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':booking_id' => $bookingId]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Tìm thanh toán theo transaction_code
     */
    public function findByTransactionCode($transactionCode)
    {
        try {
            $sql = "SELECT * FROM payments WHERE transaction_code = :transaction_code ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':transaction_code' => $transactionCode]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }

    /**
     * Tìm thanh toán theo ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM payments WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            debug($e);
            return null;
        }
    }
}

?>

