<?php
require_once "../db_connection.php"; // Adjust the path to your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Remove expired OTP
    $stmt = $conn->prepare("DELETE FROM otp_codes WHERE email = ?");
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Delete failed: (" . $stmt->errno . ") " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Database error: Delete failed']);
    }
    $stmt->close();
}
?>
