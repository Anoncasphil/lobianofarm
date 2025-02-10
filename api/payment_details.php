<?php
// Include the database connection file
include('../db_connection.php');

// Check if reservation_id is passed in the GET request
if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // SQL query to fetch the required data
    $query = "SELECT reference_number, payment_receipt, valid_amount_paid FROM reservations WHERE id = ?";

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->bind_result($fetched_reference_number, $fetched_payment_receipt, $fetched_valid_amount_paid);

        // Fetch the data
        if ($stmt->fetch()) {
            // Prepare the response array
            $response = [
                'reference_number' => $fetched_reference_number,
                'payment_receipt' => $fetched_payment_receipt,
                'valid_amount_paid' => $fetched_valid_amount_paid
            ];
        } else {
            // No record found
            $response = ['error' => 'No data found'];
        }

        $stmt->close();
    } else {
        $response = ['error' => 'Database query failed'];
    }

    // Close the database connection
    $conn->close();
} else {
    $response = ['error' => 'Reservation ID is required'];
}

// Return the data as a JSON response
echo json_encode($response);
?>
