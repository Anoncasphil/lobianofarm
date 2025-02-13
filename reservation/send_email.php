<?php
include('../db_connection.php');

function getReservations() {
    global $conn;
    
    // Updated SQL query to include reservation_code
    $sql = "SELECT 
        r.id,
        r.user_id,
        u.first_name,
        u.last_name,
        r.check_in_date,
        r.check_out_date,
        r.status,
        r.reservation_code  -- Added reservation_code to the query
    FROM reservations r
    JOIN user_tbl u ON r.user_id = u.user_id
    ORDER BY r.check_in_date DESC";
    
    $result = $conn->query($sql);
    $reservations = [];
    
    while($row = $result->fetch_assoc()) {
        // Format the check-in date
        $row['formatted_date'] = date('M d, Y', strtotime($row['check_in_date']));
        
        // Add reservation_code to the row
        $reservations[] = $row;
    }
    
    return $reservations;
}
?>
