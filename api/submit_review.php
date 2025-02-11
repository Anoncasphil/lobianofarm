<?php
// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data and decode it
    $data = json_decode(file_get_contents('php://input'), true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }

    // Check if all required fields are provided
    if (!isset($data['user_id'], $data['rating'], $data['title'], $data['review_text'], $data['created_at'], $data['updated_at'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Include the database connection
    include('../db_connection.php'); // Adjust the path to your database connection if necessary

    // Check if the database connection is successful
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
        exit;
    }

    // Sanitize the input to avoid SQL injection
    $user_id = $data['user_id'];
    $rating = $data['rating'];
    $title = $data['title'];
    $review_text = $data['review_text'];
    // Convert ISO 8601 datetime to MySQL format
    $created_at = date('Y-m-d H:i:s', strtotime($data['created_at']));
    $updated_at = date('Y-m-d H:i:s', strtotime($data['updated_at']));

    // Prepare the SQL query to insert the review
    $sql = "INSERT INTO reviews (user_id, title, review_text, rating, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error preparing the SQL statement: ' . $conn->error]);
        exit;
    }
    // Bind parameters to the SQL statement (updated types)
    $stmt->bind_param("isssss", $user_id, $title, $review_text, $rating, $created_at, $updated_at);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error submitting review: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
