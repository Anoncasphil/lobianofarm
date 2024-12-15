<?php

// Get the token from the URL parameter
$token = $_POST["token"];

// Hash the token to match it in the database
$token_hash = hash("sha256", $token);


$mysqli = require __DIR__ . "/../db_connection.php"; // this will now hold the connection

// Check if the connection is valid
if (!$conn instanceof mysqli) {
    die("Database connection failed.");
}

// Prepare the SQL query to fetch the user with the provided reset token hash
$sql = "SELECT * FROM user_tbl WHERE reset_token_hash = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);  // Note the use of $mysqli here, not $conn
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

// Bind the parameter and execute the query
$stmt->bind_param("s", $token_hash);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user exists
if ($user === null) {
    echo "<script>alert('Session not found.'); window.location.href = 'login.php';</script>";
    exit();
}

// Check if the reset token has expired
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    echo "<script>alert('This session has expired.'); window.location.href = 'login.php';</script>";
    exit();
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
$newstmt = $conn->prepare($newsql);
$newstmt->bind_param("ss", $hashedPassword, $token_hash);
$newstmt->execute();

if ($conn->affected_rows) {
    echo "<script>alert('Password reset successfully.'); window.location.href = 'login.php';</script>";
} else {
    echo "<script>alert('Failed to reset password. Try again later.'); window.history.back();</script>";
}
?>
