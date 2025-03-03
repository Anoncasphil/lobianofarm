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
        // Send success response

        // Get the reservation details for the notification
        $notification_query = "SELECT r.reservation_code, r.first_name, r.last_name 
                               FROM reservations r WHERE r.id = ?";
        $notification_stmt = $conn->prepare($notification_query);
        $notification_stmt->bind_param("i", $reservation_id);
        $notification_stmt->execute();
        $result = $notification_stmt->get_result();
        $reservation = $result->fetch_assoc();

        // Prepare the notification content for the admin
        $title = "Reservation Cancelled";
        $message = "The reservation with code #" . $reservation['reservation_code'] . " has been cancelled by the user " . $reservation['first_name'] . " " . $reservation['last_name'] . ".";
        $type = "reservation";
        $status = "unread"; // New notification is "unread"

        // Insert the notification into the notifications table for the admin
        $notif_query = "INSERT INTO notifications (reservation_id, title, message, type, status) 
                        VALUES (?, ?, ?, ?, ?)";
        $notif_stmt = $conn->prepare($notif_query);
        $notif_stmt->bind_param("issss", $reservation_id, $title, $message, $type, $status);
        
        if ($notif_stmt->execute()) {
            // Send success response
            echo json_encode([
                'success' => true, 
                'message' => 'Reservation cancelled successfully and admin notification sent!',
                'show_alert' => true,
                'alert_message' => 'The reservation has been cancelled and the admin has been notified.'
            ]);
        } else {
            // If notification fails, send failure response
            echo json_encode([
                'success' => false, 
                'message' => 'Reservation cancelled, but failed to send admin notification',
                'show_alert' => false
            ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to cancel reservation',
            'show_alert' => false
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid reservation ID',
        'show_alert' => false
    ]);
}
?>
