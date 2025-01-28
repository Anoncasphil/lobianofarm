<?php
include('../db_connection.php');

function getReservations() {
    global $conn;
    
    $sql = "SELECT 
        r.id,
        r.user_id,
        CONCAT(u.first_name, ' ', u.last_name) AS full_name,
        r.check_in_date,
        r.check_out_date,
        r.status 
    FROM reservations r
    JOIN user_tbl u ON r.user_id = u.user_id
    ORDER BY r.check_in_date DESC";
    
    $result = $conn->query($sql);
    $reservations = [];
    
    while($row = $result->fetch_assoc()) {
        $row['formatted_date'] = date('M d, Y', strtotime($row['check_in_date']));
        $reservations[] = $row;
    }
    
    return $reservations;
}
?>