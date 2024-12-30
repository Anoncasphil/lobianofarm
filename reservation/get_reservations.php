<?php
include('../db_connection.php');

function getReservations() {
    global $conn;
    
    $sql = "SELECT 
        reservation_id,
        first_name,
        last_name,
        reservation_check_in_date,
        title 
    FROM reservation 
    ORDER BY reservation_check_in_date DESC";
    
    $result = $conn->query($sql);
    $reservations = [];
    
    while($row = $result->fetch_assoc()) {
        $row['formatted_date'] = date('M d, Y', strtotime($row['reservation_check_in_date']));
        $reservations[] = $row;
    }
    
    return $reservations;
}
?>