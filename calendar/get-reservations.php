<?php
include('../db_connection.php');

// Prepare the SQL query to fetch reservation data
$sql = "SELECT id, user_id, check_in_date, check_out_date, check_in_time, check_out_time, status, first_name, last_name, CONCAT('../src/uploads/customerpayment/', payment_receipt) AS payment_receipt 
        FROM reservations";

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
        'userId' => $row['user_id'], // Include user id
        'firstName' => $row['first_name'], // Include first name
        'lastName' => $row['last_name'], // Include last name
        'title' => $row['status'], // Use status as title
        'checkInTime' => $row['check_in_time'], // Include check-in time
        'checkOutTime' => $row['check_out_time'], // Include check-out time 
        'start' => $checkInDateFormatted,
        'end' => $checkOutDateFormatted,
        'status' => $row['status'], // Include status
        'backgroundColor' => $row['status'] === 'Pending' ? '#FFA500' : '#008000', // Conditional color coding  
        'paymentReceipt' => $row['payment_receipt'] // Include payment receipt
    );
}

// Debugging: Print raw fetched data
error_log("Fetched reservations: " . print_r($reservations, true)); // Debug in logs

// Encode the data as JSON
echo json_encode($reservations, JSON_PRETTY_PRINT);

// Close the database connection
$conn->close();
?>
