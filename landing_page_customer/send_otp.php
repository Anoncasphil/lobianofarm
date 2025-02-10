<?php
require_once "../db_connection.php"; // Adjust the path to your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    $otpExpiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes')); // OTP expires in 60 seconds

    // Delete expired OTPs
    $deleteStmt = $conn->prepare("DELETE FROM otp_codes WHERE otp_expires_at < NOW()");
    if (!$deleteStmt->execute()) {
        error_log("Delete failed: (" . $deleteStmt->errno . ") " . $deleteStmt->error);
        echo json_encode(['success' => false, 'error' => 'Database error: Delete failed']);
        exit;
    } else {
        error_log("Deleted rows: " . $deleteStmt->affected_rows);
    }
    $deleteStmt->close();

    // Insert or update the OTP in the otp_codes table
    $stmt = $conn->prepare("INSERT INTO otp_codes (email, otp_code, otp_expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE otp_code = VALUES(otp_code), otp_expires_at = VALUES(otp_expires_at)");
    $stmt->bind_param("sss", $email, $otp, $otpExpiresAt);
    if (!$stmt->execute()) {
        error_log("Insert/Update failed: (" . $stmt->errno . ") " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Database error: Insert/Update failed']);
        exit;
    }
    $stmt->close();

    // Send OTP via email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'lukemia19@gmail.com'; // SMTP username
        $mail->Password = 'rskaxydhoqtzjzwm'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('noreply@gmail.com', '888 Lobiano\'s Farm');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: <b>$otp</b>";

        $mail->send();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'error' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
}
?>
