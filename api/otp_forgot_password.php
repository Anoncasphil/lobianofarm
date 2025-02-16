<?php
session_start();

ob_clean(); // Clean previous outputs
header('Content-Type: application/json');


error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('log_errors', 1);
ini_set('error_log', '../error_log.log'); // Log errors
ini_set('display_errors', 0); // Hide errors from output


require '../db_connection.php';
require '../vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Read JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);
$email = $data['email'] ?? null;

if (!$email) {
    echo json_encode(["status" => "error", "message" => "Email is required."]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT user_id FROM user_tbl WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    echo json_encode(["status" => "error", "message" => "Email not registered."]);
    exit;
}

// Delete previous OTP for this user if any
$deleteStmt = $conn->prepare("DELETE FROM password_reset_otps WHERE user_id = ?");
$deleteStmt->bind_param("i", $user_id);
$deleteStmt->execute();
$deleteStmt->close();

// Generate a 6-digit OTP
$otp = rand(100000, 999999);
$expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

// Store new OTP in the database
$stmt = $conn->prepare("INSERT INTO password_reset_otps (user_id, otp, expires_at, used) VALUES (?, ?, ?, 0)");
$stmt->bind_param("iss", $user_id, $otp, $expires_at);

if ($stmt->execute()) {
    // Use PHPMailer for sending email
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'lukemia19@gmail.com'; // Your Gmail
        $mail->Password   = 'rskaxydhoqtzjzwm';   // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email Content
        $mail->setFrom('no-reply@yourdomain.com', 'Your Company');
        $mail->addAddress($email);
        $mail->Subject = 'Your Password Reset OTP';
        $mail->Body    = "Hello,\n\nYour OTP for password reset is: $otp\nThis OTP expires in 10 minutes.";
        $mail->isHTML(false);

        if ($mail->send()) {
            echo json_encode(["status" => "success", "message" => "A new OTP has been sent to your email."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send email via PHPMailer."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Email error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database error. Try again."]);
}

$stmt->close();
?>
