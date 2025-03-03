<?php
include '../db_connection.php'; // Your database connection file

header('Content-Type: application/json');

// Check if database connection is successful
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Query to get all reservations (Pending & Confirmed)
$query = "
    SELECT r.check_in_date, rt.rate_type 
    FROM reservations r
    JOIN rates rt ON r.rate_id = rt.id
    WHERE r.status IN ('Pending', 'Confirmed')
";

// Execute the query
$result = $conn->query($query);

// Check if query execution was successful
if (!$result) {
    echo json_encode(['error' => 'Query failed', 'message' => $conn->error]);
    exit;
}

// Initialize the reserved dates array
$reservedDates = [
    'reservedDaytime' => [],
    'reservedNighttime' => [],
    'reservedWholeDay' => []
];

// Fetch the results and categorize by rate type
while ($row = $result->fetch_assoc()) {
    $date = $row['check_in_date'];
    $rate_type = $row['rate_type'];

    if ($rate_type === 'WholeDay') {
        $reservedDates['reservedWholeDay'][] = $date;
    } elseif ($rate_type === 'Daytime') {
        $reservedDates['reservedDaytime'][] = $date;
    } elseif ($rate_type === 'Nighttime') {
        $reservedDates['reservedNighttime'][] = $date;
    }
}

// Return the reserved dates as a JSON response
echo json_encode($reservedDates);
?>
