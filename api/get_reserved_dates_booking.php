<?php
include '../db_connection.php'; // Your database connection file

header('Content-Type: application/json');

// Query to get all reservations (Pending & Confirmed)
$query = "
    SELECT r.check_in_date, rt.rate_type 
    FROM reservations r
    JOIN rates rt ON r.rate_id = rt.id
    WHERE r.status IN ('Pending', 'Confirmed')
";

$result = $conn->query($query);

$reservedDates = [
    'reservedDaytime' => [],
    'reservedNighttime' => [],
    'reservedWholeDay' => []
];

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

echo json_encode($reservedDates);
?>
