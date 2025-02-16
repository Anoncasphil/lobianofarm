<?php
session_start();
date_default_timezone_set('Asia/Manila');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db_connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }

    // Generate OTP
    $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // Insert or update OTP in database
    $stmt = $conn->prepare("INSERT INTO otp_code (email, otp_code, otp_expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE otp_code = VALUES(otp_code), otp_expires_at = VALUES(otp_expires_at)");
    $stmt->bind_param('sss', $email, $otp, $expires_at);

    if ($stmt->execute()) {
        $_SESSION['otp_email'] = $email;

        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Change to your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'lukemia19@gmail.com'; // Your Gmail email
            $mail->Password = 'rskaxydhoqtzjzwm'; // Your app password (Not your Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@noreply.com', 'Your App Name');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "Your OTP code is <strong>$otp</strong>. It will expire in 60 seconds.";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to generate OTP.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
