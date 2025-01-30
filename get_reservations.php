<?php
include('db_connection.php');

function getReservations() {
    global $conn;
    
    $sql = "SELECT 
        id AS reservation_id,
        user_tbl.first_name,
        user_tbl.last_name,
        reservations.check_in_date AS reservation_check_in_date,
        reservations.status AS title 
    FROM reservations
    JOIN user_tbl ON reservations.user_id = user_tbl.user_id
    ORDER BY reservations.check_in_date ASC";
    
    $result = $conn->query($sql);
    $reservations = [];
    
    while($row = $result->fetch_assoc()) {
        $row['formatted_date'] = date('M d, Y', strtotime($row['reservation_check_in_date']));
        $reservations[] = $row;
    }
    
    return $reservations;
}
?>