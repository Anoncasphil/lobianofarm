<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include('../db_connection.php'); // Adjust path if necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize
    $disable_date = filter_var($_POST['disable_date'], FILTER_SANITIZE_STRING); // Single date for disabling
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);   // Reason for disabling
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);             // Status (Disabled)

    // Log received data for debugging purposes
    error_log("Received data: " . print_r($_POST, true));

    // Prepare SQL statement to insert data (adjusted for single disable_date)
    $stmt = $conn->prepare("INSERT INTO disable_dates (disable_date, reason, status) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $disable_date, $reason, $status); // 'sss' because all columns are strings

    // Execute the query and return a response
    if ($stmt->execute()) {
        // Success response
        echo json_encode(['success' => true]);
    } else {
        // Error response with detailed error logging
        error_log("Database error: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database insert failed']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
