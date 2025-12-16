<?php
/**
 * PAYMENT MODEL - TƯƠNG TÁC VỚI BẢNG PAYMENTS
 * 
 * Chức năng: CRUD thanh toán, lưu thông tin giao dịch VNPay
 * 
 * BẢNG PAYMENTS:
 * - id: ID thanh toán
 * - booking_id: ID đơn đặt vé (foreign key)
 * - method: Phương thức thanh toán (vnpay, momo, ...)
 * - transaction_code: Mã giao dịch từ VNPay (vnp_TransactionNo)
 * - payment_date: Ngày giờ thanh toán
 * - total_amount: Tổng tiền (trước giảm giá)
 * - discount_amount: Số tiền giảm giá
 * - final_amount: Tổng tiền cuối cùng (sau giảm giá)
 * - status: Trạng thái (pending, paid, failed)
 */
class Payment
{
    public $conn; // Kết nối database (PDO)

    /**
     * Constructor: Khởi tạo kết nối database
     */
    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo
    }

    /**
     * THÊM THÔNG TIN THANH TOÁN VÀO DATABASE
     * 
     * Chức năng: Lưu thông tin giao dịch thanh toán vào bảng payments
     * 
     * LUỒNG XỬ LÝ:
     * 1. Tạo booking (trong BookingController->processPayment())
     * 2. Gửi user đến VNPay thanh toán
     * 3. VNPay callback về (BookingController->vnpayReturn())
     * 4. Nếu thanh toán thành công -> insert payment với status = 'paid'
     * 5. Nếu thanh toán thất bại -> insert payment với status = 'failed'
     * 
     * @param array $data Dữ liệu thanh toán:
     *   - booking_id: ID đơn đặt vé
     *   - method: Phương thức thanh toán (vnpay)
     *   - transaction_code: Mã giao dịch VNPay (vnp_TransactionNo)
     *   - payment_date: Ngày giờ thanh toán (mặc định: hiện tại)
     *   - total_amount: Tổng tiền (trước giảm giá)
     *   - discount_amount: Số tiền giảm giá
     *   - final_amount: Tổng tiền cuối cùng (sau giảm giá)
     *   - status: Trạng thái (pending, paid, failed)
     * @return int|false ID của payment vừa tạo, hoặc false nếu lỗi
     */
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

    /**
     * CẬP NHẬT THÔNG TIN THANH TOÁN
     * 
     * Chức năng: Cập nhật thông tin thanh toán (thường dùng khi VNPay callback)
     * 
     * TRƯỜNG HỢP SỬ DỤNG:
     * - Khi VNPay callback về, nếu đã có payment record (pending) -> update thành paid/failed
     * - Cập nhật transaction_code từ VNPay
     * - Cập nhật payment_date
     * - Cập nhật status (paid/failed)
     * 
     * @param int $id ID của payment cần cập nhật
     * @param array $data Dữ liệu cập nhật:
     *   - transaction_code: Mã giao dịch VNPay
     *   - payment_date: Ngày giờ thanh toán
     *   - status: Trạng thái (paid, failed)
     * @return bool True nếu thành công, False nếu lỗi
     */
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
     * TÌM THANH TOÁN THEO BOOKING_ID
     * 
     * Chức năng: Tìm payment record của một booking cụ thể
     * 
     * TRƯỜNG HỢP SỬ DỤNG:
     * - Khi VNPay callback về, cần tìm payment record đã tạo trước đó (nếu có)
     * - Nếu có -> update, nếu không có -> insert mới
     * 
     * @param int $bookingId ID đơn đặt vé
     * @return array|null Payment record hoặc null nếu không tìm thấy
     */
    public function findByBookingId($bookingId)
    {
        try {
            // ORDER BY id DESC LIMIT 1: Lấy payment mới nhất (nếu có nhiều payment cho cùng 1 booking)
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
     * TÌM THANH TOÁN THEO TRANSACTION_CODE
     * 
     * Chức năng: Tìm payment record theo mã giao dịch VNPay
     * 
     * TRƯỜNG HỢP SỬ DỤNG:
     * - Kiểm tra xem transaction_code đã được sử dụng chưa (tránh duplicate)
     * - Tra cứu giao dịch theo mã VNPay
     * 
     * @param string $transactionCode Mã giao dịch VNPay (vnp_TransactionNo)
     * @return array|null Payment record hoặc null nếu không tìm thấy
     */
    public function findByTransactionCode($transactionCode)
    {
        try {
            // ORDER BY id DESC LIMIT 1: Lấy payment mới nhất (nếu có nhiều payment cùng transaction_code)
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
     * TÌM THANH TOÁN THEO ID
     * 
     * Chức năng: Tìm payment record theo ID
     * 
     * @param int $id ID của payment
     * @return array|null Payment record hoặc null nếu không tìm thấy
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

