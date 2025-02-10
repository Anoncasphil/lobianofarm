<?php
// Start session to access session variables (if necessary)
session_start();

// Include database connection file
include('../db_connection.php');

// Initialize response array
$response = array();

// Check if the necessary POST data is provided
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the user is logged in
    if (isset($_SESSION['user_id'])) {
        // Retrieve user_id from session (logged-in user's ID)
        $user_id = $_SESSION['user_id'];
        
        // Retrieve notification details from POST data
        $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : null;
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $message = isset($_POST['message']) ? $_POST['message'] : null;
        $type = isset($_POST['type']) ? $_POST['type'] : 'info'; // Default type is 'info'
        
        // Log the POST data for debugging purposes
        error_log('POST data: ' . print_r($_POST, true));

        // Validate required fields
        if (!$reservation_id || !$title || !$message) {
            $response['status'] = 'failure';
            $response['message'] = 'Reservation ID, title, and message are required fields.';
            echo json_encode($response);
            exit();
        }

        // Insert the notification into the notifications table
        $sql = "INSERT INTO notifications (user_id, title, message, type, status, reservation_id)
                VALUES (?, ?, ?, ?, 'unread', ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param('isssi', $user_id, $title, $message, $type, $reservation_id);
            
            // Execute the query
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Notification successfully created.';
            } else {
                $response['status'] = 'failure';
                $response['message'] = 'Failed to create notification. Error: ' . $stmt->error;
            }
        } else {
            $response['status'] = 'failure';
            $response['message'] = 'Database error: Failed to prepare SQL query. Error: ' . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $response['status'] = 'failure';
        $response['message'] = 'User not logged in.';
    }

    // Close the database connection
    $conn->close();
    
    // Return the response as JSON
    echo json_encode($response);
}
?>
