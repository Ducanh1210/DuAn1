<?php
/**
 * VNPAY.PHP - CLASS XỬ LÝ THANH TOÁN VNPAY
 * 
 * Chức năng: Tích hợp thanh toán VNPay vào hệ thống đặt vé
 * 
 * LUỒNG XỬ LÝ:
 * 1. Tạo URL thanh toán (createPaymentUrl): Tạo URL để redirect user đến VNPay
 * 2. VNPay xử lý thanh toán: User thanh toán trên VNPay
 * 3. VNPay callback (processCallback): VNPay gửi dữ liệu về website qua URL callback
 * 4. Xác thực checksum (validateResponse): Kiểm tra tính hợp lệ của dữ liệu từ VNPay
 * 5. Kiểm tra kết quả (isPaymentSuccess): Xác định thanh toán thành công hay thất bại
 * 
 * API ENDPOINTS:
 * - Sandbox URL: https://sandbox.vnpayment.vn/paymentv2/vpcpay.html (môi trường test)
 * - Production URL: https://vnpayment.vn/paymentv2/vpcpay.html (môi trường thực)
 * 
 * THAM SỐ QUAN TRỌNG:
 * - vnp_TmnCode: Mã terminal (merchant code) từ VNPay
 * - vnp_HashSecret: Secret key để tạo và xác thực checksum
 * - vnp_ReturnUrl: URL callback sau khi thanh toán xong
 */
class VNPay
{
    // Mã terminal (merchant code) từ VNPay - dùng để xác định merchant
    private $vnp_TmnCode = 'LSVDSCN3';
    
    // Secret key để tạo và xác thực checksum (bảo mật giao dịch)
    // LƯU Ý: Trong production, nên lưu trong file .env hoặc config riêng, không hardcode
    private $vnp_HashSecret = 'BX4AW12LQPR2Z76SH7J6KX0TPBSFF3F0';
    
    // URL API VNPay (sandbox - môi trường test)
    // Production: https://vnpayment.vn/paymentv2/vpcpay.html
    private $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    
    // URL callback từ VNPay về website sau khi thanh toán xong
    private $vnp_ReturnUrl;

    /**
     * Constructor: Khởi tạo URL callback
     * URL callback sẽ được VNPay gọi sau khi user thanh toán xong
     */
    public function __construct()
    {
        // URL callback từ VNPay về website
        // Loại bỏ dấu / cuối cùng nếu có để tránh double slash
        $baseUrl = rtrim(BASE_URL, '/');
        // Route callback: ?act=vnpay-return (xử lý bởi BookingController->vnpayReturn())
        $this->vnp_ReturnUrl = $baseUrl . '/?act=vnpay-return';
    }

    /**
     * TẠO URL THANH TOÁN VNPAY
     * 
     * Chức năng: Tạo URL thanh toán để redirect user đến VNPay
     * 
     * LUỒNG XỬ LÝ:
     * 1. Lấy các tham số từ $params (txn_ref, amount, order_info...)
     * 2. Chuyển đổi số tiền (VNPay yêu cầu x 100, ví dụ: 100000 VND -> 10000000)
     * 3. Tạo mảng inputData với các tham số bắt buộc theo format VNPay
     * 4. Sắp xếp inputData theo thứ tự alphabet (yêu cầu của VNPay)
     * 5. Tạo hashdata string từ inputData (để tạo checksum)
     * 6. Tạo query string từ inputData
     * 7. Tạo SecureHash (checksum) bằng HMAC SHA512
     * 8. Trả về URL thanh toán đầy đủ với checksum
     * 
     * @param array $params Các tham số thanh toán:
     *   - txn_ref: Mã đơn hàng (booking_id_timestamp)
     *   - amount: Số tiền (VND) - sẽ được nhân 100
     *   - order_info: Thông tin đơn hàng
     *   - order_type: Loại đơn hàng (mặc định: 'other')
     *   - locale: Ngôn ngữ (mặc định: 'vn')
     *   - bank_code: Mã ngân hàng (tùy chọn)
     * @return string URL thanh toán VNPay (đầy đủ với checksum)
     */
    public function createPaymentUrl($params)
    {
        // Lấy các tham số cấu hình
        $vnp_TmnCode = $this->vnp_TmnCode;
        $vnp_HashSecret = $this->vnp_HashSecret;
        $vnp_Url = $this->vnp_Url;
        $vnp_ReturnUrl = $this->vnp_ReturnUrl;

        // Lấy các tham số từ $params hoặc dùng giá trị mặc định
        $vnp_TxnRef = $params['txn_ref'] ?? time(); // Mã đơn hàng (booking_id_timestamp)
        $vnp_OrderInfo = $params['order_info'] ?? 'Thanh toan dat ve xem phim'; // Thông tin đơn hàng
        $vnp_OrderType = $params['order_type'] ?? 'other'; // Loại đơn hàng
        $vnp_Amount = $params['amount'] ?? 0; // Số tiền (VND) - chưa nhân 100
        $vnp_Locale = $params['locale'] ?? 'vn'; // Ngôn ngữ (vn, en)
        $vnp_BankCode = $params['bank_code'] ?? ''; // Mã ngân hàng (tùy chọn, nếu có sẽ thanh toán trực tiếp qua ngân hàng đó)
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'; // IP khách hàng

        // QUAN TRỌNG: VNPay yêu cầu số tiền phải nhân 100 (ví dụ: 100000 VND -> 10000000)
        // Lý do: VNPay lưu số tiền dưới dạng số nguyên (không có phần thập phân)
        $vnp_Amount = $vnp_Amount * 100;

        // Tạo mảng inputData với các tham số bắt buộc theo format VNPay API
        $inputData = array(
            "vnp_Version" => "2.1.0", // Phiên bản API VNPay
            "vnp_TmnCode" => $vnp_TmnCode, // Mã terminal
            "vnp_Amount" => $vnp_Amount, // Số tiền (đã nhân 100)
            "vnp_Command" => "pay", // Lệnh thanh toán
            "vnp_CreateDate" => date('YmdHis'), // Ngày giờ tạo giao dịch (format: YYYYMMDDHHmmss)
            "vnp_CurrCode" => "VND", // Mã tiền tệ (VND)
            "vnp_IpAddr" => $vnp_IpAddr, // IP khách hàng
            "vnp_Locale" => $vnp_Locale, // Ngôn ngữ
            "vnp_OrderInfo" => $vnp_OrderInfo, // Thông tin đơn hàng
            "vnp_OrderType" => $vnp_OrderType, // Loại đơn hàng
            "vnp_ReturnUrl" => $vnp_ReturnUrl, // URL callback sau khi thanh toán xong
            "vnp_TxnRef" => $vnp_TxnRef, // Mã đơn hàng (unique)
        );

        // Thêm mã ngân hàng nếu có (cho phép thanh toán trực tiếp qua ngân hàng cụ thể)
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // QUAN TRỌNG: Sắp xếp dữ liệu theo thứ tự alphabet (yêu cầu của VNPay)
        // Điều này đảm bảo checksum được tính đúng
        ksort($inputData);
        
        // Tạo query string và hashdata string
        $query = ""; // Query string để gắn vào URL
        $i = 0;
        $hashdata = ""; // String để tạo checksum (không bao gồm vnp_SecureHash)
        foreach ($inputData as $key => $value) {
            // Tạo hashdata: key1=value1&key2=value2&... (không có & ở đầu)
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            // Tạo query string: key1=value1&key2=value2&... (có & ở cuối)
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Gắn query string vào URL
        $vnp_Url = $vnp_Url . "?" . $query;
        
        // Tạo SecureHash (checksum) bằng HMAC SHA512
        // Checksum dùng để VNPay xác thực tính hợp lệ của request
        if (isset($vnp_HashSecret)) {
            // hash_hmac('sha512', data, secret): Tạo HMAC SHA512 hash
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            // Gắn checksum vào cuối URL
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Trả về URL thanh toán đầy đủ (có checksum)
        return $vnp_Url;
    }

    /**
     * XÁC THỰC CHECKSUM TỪ VNPAY CALLBACK
     * 
     * Chức năng: Kiểm tra tính hợp lệ của dữ liệu callback từ VNPay bằng cách so sánh checksum
     * 
     * LUỒNG XỬ LÝ:
     * 1. Lấy vnp_SecureHash từ dữ liệu callback
     * 2. Lọc các tham số bắt đầu bằng "vnp_" (trừ vnp_SecureHash)
     * 3. Sắp xếp theo thứ tự alphabet
     * 4. Tạo hashdata string (bỏ qua giá trị rỗng)
     * 5. Tạo checksum mới bằng HMAC SHA512
     * 6. So sánh checksum mới với checksum từ VNPay
     * 
     * BẢO MẬT:
     * - Checksum đảm bảo dữ liệu không bị giả mạo
     * - Nếu checksum không khớp, có thể dữ liệu bị thay đổi hoặc không phải từ VNPay
     * 
     * @param array $data Dữ liệu từ VNPay callback ($_GET)
     * @return bool True nếu checksum hợp lệ, False nếu không hợp lệ
     */
    public function validateResponse($data)
    {
        $vnp_HashSecret = $this->vnp_HashSecret;
        // Lấy checksum từ VNPay callback
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';

        // Nếu không có checksum, không hợp lệ
        if (empty($vnp_SecureHash)) {
            return false;
        }

        // Tạo bản sao của data để không ảnh hưởng đến mảng gốc
        $inputData = [];
        foreach ($data as $key => $value) {
            // Chỉ lấy các key bắt đầu bằng "vnp_" và không phải "vnp_SecureHash"
            // vnp_SecureHash không được tính vào hashdata
            if (substr($key, 0, 4) == "vnp_" && $key != "vnp_SecureHash") {
                $inputData[$key] = $value;
            }
        }

        // QUAN TRỌNG: Sắp xếp dữ liệu theo thứ tự alphabet (giống như khi tạo URL)
        ksort($inputData);

        // Tạo hashdata string từ inputData (bỏ qua giá trị rỗng)
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            // Bỏ qua các giá trị rỗng (theo yêu cầu của VNPay)
            if (strlen($value) > 0) {
                // Tạo hashdata: key1=value1&key2=value2&... (không có & ở đầu)
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
        }

        // Tạo checksum mới bằng HMAC SHA512 (giống như khi tạo URL)
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // So sánh checksum mới với checksum từ VNPay
        // Nếu khớp -> dữ liệu hợp lệ, không bị giả mạo
        // Nếu không khớp -> dữ liệu không hợp lệ, có thể bị thay đổi
        return $vnpSecureHash === $vnp_SecureHash;
    }

    /**
     * XỬ LÝ DỮ LIỆU CALLBACK TỪ VNPAY
     * 
     * Chức năng: Parse và xử lý dữ liệu callback từ VNPay sau khi user thanh toán xong
     * 
     * LUỒNG XỬ LÝ:
     * 1. Lấy các tham số từ $_GET (VNPay gửi về qua URL callback)
     * 2. Chuyển đổi số tiền về đơn vị VND (VNPay trả về x 100)
     * 3. Xác thực checksum để đảm bảo dữ liệu hợp lệ
     * 4. Trả về mảng dữ liệu đã xử lý
     * 
     * CÁC THAM SỐ TỪ VNPAY:
     * - vnp_ResponseCode: Mã phản hồi (00 = thành công, khác 00 = thất bại)
     * - vnp_TxnRef: Mã đơn hàng (booking_id_timestamp)
     * - vnp_Amount: Số tiền (đã nhân 100, cần chia 100)
     * - vnp_TransactionStatus: Trạng thái giao dịch (00 = thành công)
     * - vnp_OrderInfo: Thông tin đơn hàng
     * - vnp_TransactionNo: Mã giao dịch VNPay (unique)
     * - vnp_BankCode: Mã ngân hàng thanh toán
     * - vnp_SecureHash: Checksum để xác thực
     * 
     * @return array Dữ liệu đã xử lý:
     *   - response_code: Mã phản hồi
     *   - txn_ref: Mã đơn hàng
     *   - amount: Số tiền (VND, đã chia 100)
     *   - transaction_status: Trạng thái giao dịch
     *   - order_info: Thông tin đơn hàng
     *   - transaction_no: Mã giao dịch VNPay
     *   - bank_code: Mã ngân hàng
     *   - is_valid: Checksum có hợp lệ không
     *   - raw_data: Dữ liệu gốc từ $_GET
     */
    public function processCallback()
    {
        // Lấy các tham số từ $_GET (VNPay gửi về qua URL callback)
        $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? ''; // Mã phản hồi (00 = thành công)
        $vnp_TxnRef = $_GET['vnp_TxnRef'] ?? ''; // Mã đơn hàng (booking_id_timestamp)
        $vnp_Amount = $_GET['vnp_Amount'] ?? 0; // Số tiền (đã nhân 100)
        $vnp_TransactionStatus = $_GET['vnp_TransactionStatus'] ?? ''; // Trạng thái giao dịch (00 = thành công)
        $vnp_OrderInfo = $_GET['vnp_OrderInfo'] ?? ''; // Thông tin đơn hàng
        $vnp_TransactionNo = $_GET['vnp_TransactionNo'] ?? ''; // Mã giao dịch VNPay (unique, dùng để tra cứu)
        $vnp_BankCode = $_GET['vnp_BankCode'] ?? ''; // Mã ngân hàng thanh toán

        // QUAN TRỌNG: Chuyển đổi số tiền về đơn vị VND (VNPay trả về x 100)
        // Ví dụ: VNPay trả về 10000000 -> chia 100 -> 100000 VND
        $vnp_Amount = $vnp_Amount / 100;

        // Trả về mảng dữ liệu đã xử lý
        return [
            'response_code' => $vnp_ResponseCode, // Mã phản hồi (00 = thành công)
            'txn_ref' => $vnp_TxnRef, // Mã đơn hàng (booking_id_timestamp)
            'amount' => $vnp_Amount, // Số tiền (VND, đã chia 100)
            'transaction_status' => $vnp_TransactionStatus, // Trạng thái giao dịch (00 = thành công)
            'order_info' => $vnp_OrderInfo, // Thông tin đơn hàng
            'transaction_no' => $vnp_TransactionNo, // Mã giao dịch VNPay (unique)
            'bank_code' => $vnp_BankCode, // Mã ngân hàng thanh toán
            'is_valid' => $this->validateResponse($_GET), // Checksum có hợp lệ không (bảo mật)
            'raw_data' => $_GET // Dữ liệu gốc từ $_GET (để debug)
        ];
    }

    /**
     * KIỂM TRA THANH TOÁN CÓ THÀNH CÔNG KHÔNG
     * 
     * Chức năng: Xác định thanh toán thành công hay thất bại dựa trên dữ liệu callback
     * 
     * ĐIỀU KIỆN THÀNH CÔNG:
     * 1. is_valid = true: Checksum hợp lệ (dữ liệu không bị giả mạo)
     * 2. response_code = '00': VNPay phản hồi thành công
     * 3. transaction_status = '00': Giao dịch thành công
     * 
     * CÁC MÃ LỖI THƯỜNG GẶP:
     * - response_code = '00': Thành công
     * - response_code = '07': Trừ tiền thành công nhưng giao dịch bị nghi ngờ
     * - response_code = '24': Giao dịch bị hủy
     * - response_code khác '00': Thất bại (có thể do hết tiền, thẻ bị khóa, ...)
     * 
     * @param array $callbackData Dữ liệu từ callback (từ processCallback())
     * @return bool True nếu thanh toán thành công, False nếu thất bại
     */
    public function isPaymentSuccess($callbackData)
    {
        // Kiểm tra 3 điều kiện:
        // 1. Checksum hợp lệ (is_valid = true)
        // 2. Mã phản hồi = '00' (thành công)
        // 3. Trạng thái giao dịch = '00' (thành công)
        return $callbackData['is_valid'] === true 
            && $callbackData['response_code'] === '00' 
            && $callbackData['transaction_status'] === '00';
    }
}

?>

