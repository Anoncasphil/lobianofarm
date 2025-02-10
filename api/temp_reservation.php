<?php
session_start();

// Check if session data exists
if (isset($_SESSION['reservation_temp'])) {
    $reservation_data = $_SESSION['reservation_temp'];

    // Debugging: Log the reservation data to see what's inside
    error_log(print_r($reservation_data, true));  // This will log the session data to your server's error log

    // Check if all necessary fields are set and have the correct types
    if (
        isset($reservation_data['reservation_id']) &&
        isset($reservation_data['invoice_date']) &&
        isset($reservation_data['invoice_number']) &&
        isset($reservation_data['reference_number']) &&
        isset($reservation_data['total_price']) && is_numeric($reservation_data['total_price']) &&
        isset($reservation_data['valid_amount_paid']) && is_numeric($reservation_data['valid_amount_paid']) &&
        isset($reservation_data['payment_receipt']) &&
        isset($reservation_data['status']) &&
        isset($reservation_data['contact_number']) &&
        isset($reservation_data['first_name']) &&
        isset($reservation_data['last_name']) &&
        isset($reservation_data['email']) &&
        isset($reservation_data['mobile_number']) &&
        isset($reservation_data['checkin_date']) &&
        isset($reservation_data['checkout_date']) &&
        isset($reservation_data['checkin_time']) &&
        isset($reservation_data['checkout_time']) &&
        isset($reservation_data['addons']) && is_array($reservation_data['addons']) &&
        isset($reservation_data['rates']) && is_array($reservation_data['rates'])
    ) {
        // All the necessary data is available, return it as a success response
        echo json_encode([
            'status' => 'success',
            'data' => $reservation_data
        ]);
    } else {
        // Missing or invalid fields, notify the frontend
        echo json_encode([
            'status' => 'error',
            'message' => 'Some necessary data is missing or invalid'
        ]);
    }
} else {
    // If reservation data is not found in session
    echo json_encode([
        'status' => 'error',
        'message' => 'No reservation data found in session'
    ]);
}
?>
