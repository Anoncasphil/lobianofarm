<?php
// Include the database connection file
include '../db_connection.php';
session_start();
header('Content-Type: application/json');

// Retrieve POST data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

// Prepare the statement to prevent SQL injection
$stmt = $conn->prepare('SELECT user_id, first_name, last_name, password FROM user_tbl WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verify the password
    if (password_verify($password, $row['password'])) {
        // Start session and set session variables
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['email'] = $email;

        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email not found.']);
}

// Close resources
$stmt->close();
$conn->close();
?>
