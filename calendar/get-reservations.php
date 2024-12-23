<?php
include('../db_connection.php');

// Prepare the SQL query to fetch reservation data
$sql = "SELECT reservation_id, title, first_name, last_name, reservation_check_in_date, reservation_check_out_date 
        FROM reservation";

// Execute the query
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Initialize an array to store reservation data
$reservations = array();

// Fetch each row and map it to the array
while ($row = $result->fetch_assoc()) {
    $reservations[] = array(
        'id' => $row['reservation_id'], // Map reservation_id to id
        'title' => $row['title'],
        'firstName' => $row['first_name'],
        'lastName' => $row['last_name'],
        'start' => $row['reservation_check_in_date'],
        'end' => $row['reservation_check_out_date'],
        'status' => $row['title'],
        'backgroundColor' => $row['title'] === 'Pending' ? '#FFA500' : '#008000' // Conditional color coding
    );
}

// Debugging: Print raw fetched data
error_log("Fetched reservations: " . print_r($reservations, true)); // Debug in logs

// Encode the data as JSON
echo json_encode($reservations, JSON_PRETTY_PRINT);

// Close the database connection
$conn->close();
?>
