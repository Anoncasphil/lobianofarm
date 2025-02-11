<?php
// cancel_reservation.php

include '../db_connection.php'; // Ensure this file contains your database connection settings

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Get the data from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Ensure the reservation_id is correctly passed
$reservation_id = $data['reservation_id'];

if ($reservation_id) {
    // Prepare the SQL query to update the reservation status to 'Cancelled'
    $query = "UPDATE reservations SET status = 'Cancelled' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reservation cancelled successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel reservation']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid reservation ID']);
}
?>
