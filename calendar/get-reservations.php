<?php
include('../db_connection.php');

// Prepare the SQL query to fetch reservation data along with user first name and last name
$sql = "SELECT r.id, r.user_id, u.first_name, u.last_name, r.check_in_date, r.check_out_date, r.status 
        FROM reservations r
        JOIN user_tbl u ON r.user_id = u.user_id";

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
    $checkInDate = new DateTime($row['check_in_date']);
    $checkOutDate = new DateTime($row['check_out_date']);
    
    // Ensure the check-out date is not before the check-in date
    if ($checkInDate == $checkOutDate) {
        $checkOutDate = clone $checkInDate;
    }

    // Format the new dates back to string
    $checkInDateFormatted = $checkInDate->format('Y-m-d');
    $checkOutDateFormatted = $checkOutDate->format('Y-m-d');

    $reservations[] = array(
        'id' => $row['id'], // Map id
        'user_id' => $row['user_id'],
        'first_name' => $row['first_name'],
        'last_name' => $row['last_name'],
        'start' => $checkInDateFormatted,
        'end' => $checkOutDateFormatted,
        'status' => $row['status'],
        'backgroundColor' => $row['status'] === 'Pending' ? '#FFA500' : '#008000' // Conditional color coding  
    );
}

// Debugging: Print raw fetched data
error_log("Fetched reservations: " . print_r($reservations, true)); // Debug in logs

// Encode the data as JSON
echo json_encode($reservations, JSON_PRETTY_PRINT);

// Close the database connection
$conn->close();
?>
