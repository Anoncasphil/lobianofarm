<?php
session_start();
date_default_timezone_set('Asia/Manila');
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['otp_email'] ?? '';
    $enteredOTP = $_POST['otp'] ?? '';

    if (empty($email) || empty($enteredOTP)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing email or OTP']);
        exit;
    }

    // Fetch OTP from the database
    $stmt = $conn->prepare("SELECT otp_code, otp_expires_at FROM otp_code WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $otpData = $result->fetch_assoc();

    if ($otpData) {
        $storedOTP = $otpData['otp_code'];
        $expiresAt = strtotime($otpData['otp_expires_at']);
        $now = time();

        if ($enteredOTP === $storedOTP && $now <= $expiresAt) {
            echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully']);
        } elseif ($now > $expiresAt) {
            echo json_encode(['status' => 'error', 'message' => 'OTP expired']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No OTP found for this email']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
