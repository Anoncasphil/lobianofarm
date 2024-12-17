<?php
include('../db_connection.php');

$sql = "SELECT title, first_name, last_name, reservation_check_in_date, reservation_check_out_date 
        FROM reservation";
$result = $conn->query($sql);

$reservations = array();
while($row = $result->fetch_assoc()) {
    $reservations[] = array(
        'title' => $row['title'],
        'firstName' => $row['first_name'],
        'lastName' => $row['last_name'],
        'start' => $row['reservation_check_in_date'],
        'end' => $row['reservation_check_out_date'],
        'status' => $row['title'],
        'backgroundColor' => $row['title'] === 'Pending' ? '#FFA500' : '#008000'

    );
}

echo json_encode($reservations);
$conn->close();
?>