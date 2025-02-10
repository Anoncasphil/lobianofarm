<?php
// Assuming you have a database connection file
include('../db_connection.php');

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];
    
    // Query to fetch the reservation code
    $sql = "SELECT reservation_code FROM reservations WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $reservation_id);
        $stmt->execute();
        $stmt->bind_result($reservation_code);
        $stmt->fetch();
        
        if ($reservation_code) {
            echo json_encode(['status' => 'success', 'reservation_code' => $reservation_code]);
        } else {
            echo json_encode(['status' => 'failure', 'message' => 'Invalid reservation ID.']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'Database error.']);
    }
    
    $conn->close();
}
?>
