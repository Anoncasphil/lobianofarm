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
    // Add 1 day to the check-in and check-out dates
    $checkInDate = new DateTime($row['reservation_check_in_date']);
    $checkOutDate = new DateTime($row['reservation_check_out_date']);

    // Format the new dates back to string
    $checkInDateFormatted = $checkInDate->format('Y-m-d');
    $checkOutDateFormatted = $checkOutDate->format('Y-m-d');

    $reservations[] = array(
        'id' => $row['reservation_id'], // Map reservation_id to id
        'title' => $row['title'],
        'firstName' => $row['first_name'],
        'lastName' => $row['last_name'],
        'start' => $checkInDateFormatted,
        'end' => $checkOutDateFormatted,
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
