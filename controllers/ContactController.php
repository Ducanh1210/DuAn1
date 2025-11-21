<?php
// controllers/ContactController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ContactController
{
    /**
     * Hiển thị form liên hệ bằng layout client chung (renderClient)
     */
    public function showForm()
    {
        $success = isset($_GET['success']) ? true : false;
        $errors = $_SESSION['contact_errors'] ?? [];
        $old = $_SESSION['contact_old'] ?? [];

        // Xóa session tạm sau khi đọc
        unset($_SESSION['contact_errors'], $_SESSION['contact_old']);

        // Dữ liệu truyền vào view
        $data = [
            'errors' => $errors,
            'old' => $old,
            'success' => $success
        ];

        // Dùng helper renderClient có trong commons/function.php để render với layout client
        // renderClient($viewPathRelativeToViewsClient, $dataArray, $pageTitle)
        renderClient('client/lienhe.php', $data, 'Liên hệ');
        exit;
    }

    /**
     * Xử lý submit form liên hệ.
     */
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . '?act=lienhe');
            exit;
        }

        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];

        if ($full_name === '') {
            $errors['full_name'] = 'Vui lòng nhập họ tên';
        }

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if ($message === '') {
            $errors['message'] = 'Vui lòng nhập nội dung liên hệ';
        }

        // Lưu dữ liệu cũ để refill form khi có lỗi
        $_SESSION['contact_old'] = [
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ];

        if (!empty($errors)) {
            $_SESSION['contact_errors'] = $errors;
            header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . '?act=lienhe');
            exit;
        }

        // Lưu vào DB
        try {
            $conn = connectDB(); // từ commons/function.php
            $sql = "INSERT INTO contact_messages (full_name, email, phone, subject, message)
                    VALUES (:full_name, :email, :phone, :subject, :message)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':full_name' => $full_name,
                ':email' => $email,
                ':phone' => $phone ?: null,
                ':subject' => $subject ?: null,
                ':message' => $message
            ]);

            unset($_SESSION['contact_old'], $_SESSION['contact_errors']);
            header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . '?act=lienhe&success=1');
            exit;
        } catch (\Throwable $e) {
            // Không hiển thị lỗi chi tiết cho user
            $_SESSION['contact_errors'] = ['general' => 'Có lỗi khi lưu dữ liệu. Vui lòng thử lại sau.'];
            header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . '?act=lienhe');
            exit;
        }
    }
}
