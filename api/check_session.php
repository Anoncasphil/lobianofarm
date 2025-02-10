<?php
session_start();

// Check if session data exists
if (isset($_SESSION['reservation_temp'])) {
    // Return the session data as a JSON response
    echo json_encode([
        'status' => 'success',
        'session_data' => $_SESSION['reservation_temp']
    ]);
} else {
    // If no session data, return an error message
    echo json_encode([
        'status' => 'error',
        'message' => 'No session data found.'
    ]);
}
?>
