<?php
header('Content-Type: application/json');
session_start(); // Start session early

require '../db_connection.php';

// Get JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);
$email = $data['email'] ?? null;
$enteredOtp = $data['otp'] ?? null;

if (!$email || !$enteredOtp) {
    echo json_encode(["success" => false, "message" => "Email and OTP are required."]);
    exit;
}

// Store email in session for reset_password.php
$_SESSION['reset_email'] = $email;

// Fetch the latest OTP from the database
$stmt = $conn->prepare("SELECT otp, expires_at, used FROM password_reset_otps 
                        WHERE user_id = (SELECT user_id FROM user_tbl WHERE email = ?) 
                        ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "OTP not found."]);
    exit;
}

$storedOtp = $row['otp'];
$expiresAt = strtotime($row['expires_at']);
$isUsed = $row['used'];

// Validate OTP
if ($isUsed) {
    echo json_encode(["success" => false, "message" => "This OTP has already been used."]);
    exit;
}

if ($storedOtp !== $enteredOtp) {
    echo json_encode(["success" => false, "message" => "Invalid OTP."]);
    exit;
}

if (time() > $expiresAt) {
    echo json_encode(["success" => false, "message" => "OTP expired."]);
    exit;
}

// Mark OTP as used
$updateStmt = $conn->prepare("UPDATE password_reset_otps 
                              SET used = 1 
                              WHERE otp = ? 
                              AND user_id = (SELECT user_id FROM user_tbl WHERE email = ?)");
$updateStmt->bind_param("ss", $enteredOtp, $email);
$updateStmt->execute();
$updateStmt->close();

// Return success
echo json_encode(["success" => true, "message" => "OTP verified."]);
?>
