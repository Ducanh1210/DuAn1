<?php

/**
 * Class VNPay - Xử lý thanh toán VNPay
 */
class VNPay
{
    private $vnp_TmnCode = 'LSVDSCN3';
    private $vnp_HashSecret = 'BX4AW12LQPR2Z76SH7J6KX0TPBSFF3F0';
    private $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    private $vnp_ReturnUrl;

    public function __construct()
    {
        // URL callback từ VNPay về website
        // Loại bỏ dấu / cuối cùng nếu có để tránh double slash
        $baseUrl = rtrim(BASE_URL, '/');
        $this->vnp_ReturnUrl = $baseUrl . '/?act=vnpay-return';
    }

    /**
     * Tạo URL thanh toán VNPay
     * @param array $params Các tham số thanh toán
     * @return string URL thanh toán
     */
    public function createPaymentUrl($params)
    {
        $vnp_TmnCode = $this->vnp_TmnCode;
        $vnp_HashSecret = $this->vnp_HashSecret;
        $vnp_Url = $this->vnp_Url;
        $vnp_ReturnUrl = $this->vnp_ReturnUrl;

        $vnp_TxnRef = $params['txn_ref'] ?? time(); // Mã đơn hàng
        $vnp_OrderInfo = $params['order_info'] ?? 'Thanh toan dat ve xem phim'; // Thông tin đơn hàng
        $vnp_OrderType = $params['order_type'] ?? 'other'; // Loại đơn hàng
        $vnp_Amount = $params['amount'] ?? 0; // Số tiền (VND)
        $vnp_Locale = $params['locale'] ?? 'vn'; // Ngôn ngữ
        $vnp_BankCode = $params['bank_code'] ?? ''; // Mã ngân hàng (nếu có)
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'; // IP khách hàng

        // Chuyển đổi số tiền (VNPay yêu cầu số tiền x 100)
        $vnp_Amount = $vnp_Amount * 100;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp dữ liệu theo thứ tự alphabet
        ksort($inputData);
        
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực checksum từ VNPay callback
     * @param array $data Dữ liệu từ VNPay callback
     * @return bool True nếu checksum hợp lệ
     */
    public function validateResponse($data)
    {
        $vnp_HashSecret = $this->vnp_HashSecret;
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';

        if (empty($vnp_SecureHash)) {
            return false;
        }

        // Tạo bản sao của data để không ảnh hưởng đến mảng gốc
        $inputData = [];
        foreach ($data as $key => $value) {
            // Chỉ lấy các key bắt đầu bằng vnp_ và không phải vnp_SecureHash
            if (substr($key, 0, 4) == "vnp_" && $key != "vnp_SecureHash") {
                $inputData[$key] = $value;
            }
        }

        // Sắp xếp dữ liệu theo thứ tự alphabet
        ksort($inputData);

        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            // Bỏ qua các giá trị rỗng
            if (strlen($value) > 0) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        return $vnpSecureHash === $vnp_SecureHash;
    }

    /**
     * Xử lý dữ liệu callback từ VNPay
     * @return array Dữ liệu đã xử lý
     */
    public function processCallback()
    {
        $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';
        $vnp_TxnRef = $_GET['vnp_TxnRef'] ?? '';
        $vnp_Amount = $_GET['vnp_Amount'] ?? 0;
        $vnp_TransactionStatus = $_GET['vnp_TransactionStatus'] ?? '';
        $vnp_OrderInfo = $_GET['vnp_OrderInfo'] ?? '';
        $vnp_TransactionNo = $_GET['vnp_TransactionNo'] ?? '';
        $vnp_BankCode = $_GET['vnp_BankCode'] ?? '';

        // Chuyển đổi số tiền về đơn vị VND (VNPay trả về x 100)
        $vnp_Amount = $vnp_Amount / 100;

        return [
            'response_code' => $vnp_ResponseCode,
            'txn_ref' => $vnp_TxnRef,
            'amount' => $vnp_Amount,
            'transaction_status' => $vnp_TransactionStatus,
            'order_info' => $vnp_OrderInfo,
            'transaction_no' => $vnp_TransactionNo,
            'bank_code' => $vnp_BankCode,
            'is_valid' => $this->validateResponse($_GET),
            'raw_data' => $_GET
        ];
    }

    /**
     * Kiểm tra thanh toán có thành công không
     * @param array $callbackData Dữ liệu từ callback
     * @return bool True nếu thanh toán thành công
     */
    public function isPaymentSuccess($callbackData)
    {
        return $callbackData['is_valid'] === true 
            && $callbackData['response_code'] === '00' 
            && $callbackData['transaction_status'] === '00';
    }
}

?>

