<?php
include('../db_connection.php');

// Fetch reschedule request data
$query = "SELECT * FROM reschedule_request WHERE reservation_id = ?"; 
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $reservation_id); // Bind reservation_id parameter
$stmt->execute();
$result = $stmt->get_result();

// Fetch the first result (modify as needed)
$request_data = $result->fetch_assoc();

// Fetch status options (you can modify these options as per your database)
$status_query = "SELECT status FROM status_options"; // Assuming there's a table called 'status_options'
$status_result = $conn->query($status_query);
$status_options = [];
while ($row = $status_result->fetch_assoc()) {
    $status_options[] = $row['status'];
}

$response = [
    'request_data' => $request_data,
    'status_options' => $status_options
];

echo json_encode($response); // Return data as JSON
?>
