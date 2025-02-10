<?php
// Database connection details
include('../db_connection.php');

// Assume these are passed in as GET parameters or from a session variable
$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;  // Validate and sanitize user_id
$reservation_id = isset($_GET['reservation_id']) ? (int) $_GET['reservation_id'] : null;  // Validate reservation_id

// Ensure user_id and reservation_id are provided
if (!$user_id || !$reservation_id) {
    echo json_encode(["status" => null, "hasRequest" => false, "message" => "User ID or Reservation ID is missing."]);
    exit;
}

// Query to get reservation status and check if a reschedule request exists
$sql = "SELECT r.status, rr.request_id 
        FROM reservations r
        LEFT JOIN reschedule_request rr ON r.id = rr.reservation_id
        WHERE r.user_id = ? AND r.id = ?";

$stmt = $conn->prepare($sql);

// Check if statement preparation failed
if ($stmt === false) {
    die("Error preparing the SQL query: " . $conn->error);
}

$stmt->bind_param('ii', $user_id, $reservation_id);  // Bind parameters

$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if no rows were returned
if ($result->num_rows == 0) {
    echo json_encode(["status" => null, "hasRequest" => false, "message" => "No matching reservation or reschedule request found."]);
    exit;
}

// Fetch the result
$row = $result->fetch_assoc();

// Check if there is a matching reservation and reschedule request
$response = [
    'status' => $row['status'] ?? 'No status available',  // Ensure status is returned
    'hasRequest' => $row['request_id'] !== null, // If there's a reschedule request
    'reservation_id' => $reservation_id,  // Print the reservation ID
    'user_id' => $user_id  // Print the user ID
];

// Return the response as JSON
echo json_encode($response);

// Close the connection
$stmt->close();
$conn->close();
?>
