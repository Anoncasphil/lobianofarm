<?php
// get_reserved_dates.php
include '../db_connection.php';  // Include the DB connection file

// Query to fetch the check_in_date and check_out_date from reservations table
$sql = "SELECT check_in_date, check_out_date FROM reservations WHERE status = 'Pending' OR status = 'Confirmed'";
$result = mysqli_query($conn, $sql);

$reserved_dates = [];

while ($row = mysqli_fetch_assoc($result)) {
    $reserved_dates[] = [
        'start' => $row['check_in_date'],
        'end' => $row['check_out_date']
    ];
}

// Return the reserved dates in JSON format
echo json_encode($reserved_dates);
?>
