<?php
session_start();
require '../db_connection.php';

if (!isset($_SESSION['reset_email'])) {
    echo "Session expired. Please try again.";
    exit;
}

$email = $_SESSION['reset_email'];
$newPassword = $_POST['new_password'] ?? null;
$confirmPassword = $_POST['confirm_password'] ?? null;

if (!$newPassword || !$confirmPassword) {
    echo "All fields are required.";
    exit;
}

if ($newPassword !== $confirmPassword) {
    echo "Passwords do not match.";
    exit;
}

// Hash the password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update the password
$stmt = $conn->prepare("UPDATE user_tbl SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashedPassword, $email);
if ($stmt->execute()) {
    // Clear session
    unset($_SESSION['reset_email']);
    echo "Password reset successfully. <a href='login.php'>Login</a>";
} else {
    echo "Failed to reset password.";
}
$stmt->close();
$conn->close();
?>
