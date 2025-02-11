<?php
// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('../db_connection.php'); // Adjust path if necessary

// Check if the connection is established
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Fetch reviews from the database
$sql = "SELECT user_id, title, review_text, rating, created_at FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);

$reviews = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return JSON response
echo json_encode(['success' => true, 'reviews' => $reviews]);
?>
