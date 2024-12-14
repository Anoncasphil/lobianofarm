<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../db_connection.php";

$sql = "SELECT * FROM user_tbl WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    echo "<script>alert('Session not found.'); window.location.href = 'login.php';</script>";
    exit;
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    echo "<script>alert('This session has expired.'); window.location.href = 'login.php';</script>";
    exit;
}

// new params ewan
$password = $_POST['password'];
$passwordRepeat = $_POST['repeat_password'];

$errors = [];
if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
}

if ($password !== $passwordRepeat) {
    $errors[] = "Passwords do not match.";
}

if (!empty($errors)) {
    $errorMessages = implode("\\n", $errors);
    echo "<script>alert('$errorMessages'); window.history.back();</script>";
    exit;
}

// Input the new stuff my g
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$newsql = "UPDATE user_tbl SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE reset_token_hash = ?";
$newstmt = $mysqli->prepare($newsql);
$newstmt->bind_param("ss", $hashedPassword, $token_hash);
$newstmt->execute();

if ($mysqli->affected_rows) {
    echo "<script>alert('Password reset successfully.'); window.location.href = 'login.php';</script>";
} else {
    echo "<script>alert('Failed to reset password. Try again later.'); window.history.back();</script>";
}
?>
