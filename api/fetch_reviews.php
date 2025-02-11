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

// Fetch ALL reviews with reviewer names
$sql = "SELECT r.user_id, u.first_name, u.last_name, r.title, r.review_text, r.rating, r.created_at 
        FROM reviews r
        JOIN user_tbl u ON r.user_id = u.user_id
        ORDER BY r.created_at DESC";

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
