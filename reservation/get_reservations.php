<?php
include('../db_connection.php');

function getReservations($statusFilter = '') {
    global $conn;
    
    // Start building the SQL query
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
    JOIN user_tbl u ON r.user_id = u.user_id";
    
    // Apply the status filter if provided
    if ($statusFilter) {
        $sql .= " WHERE r.status = ?";
    }
    
    // Order by check-in date in descending order
    $sql .= " ORDER BY r.check_in_date DESC";
    
    // Prepare the query
    $stmt = $conn->prepare($sql);
    
    // Bind the status parameter if a filter is set
    if ($statusFilter) {
        $stmt->bind_param("s", $statusFilter); // "s" stands for string
    }
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    $reservations = [];
    
    // Fetch and format the results
    while($row = $result->fetch_assoc()) {
        // Format the check-in date
        $row['formatted_date'] = date('M d, Y', strtotime($row['check_in_date']));
        
        // Add reservation_code to the row
        $reservations[] = $row;
    }
    
    // Close the statement
    $stmt->close();
    
    return $reservations;
}
?>
