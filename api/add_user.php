<?php
// Include database connection
require_once '../db_connection.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and retrieve form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $agree = isset($_POST['agree']) ? $_POST['agree'] : null;

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($email) || empty($password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }


    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO user_tbl (first_name, last_name, phone_number, email, password, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssss", $first_name, $last_name, $phone_number, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User registered successfully!']);
    } else {
        // Check for duplicate email
        if ($stmt->errno === 1062) {
            echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to register user. Please try again later.']);
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
