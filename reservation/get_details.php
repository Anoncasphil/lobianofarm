<?php
include('../db_connection.php'); // Include your DB connection file

header('Content-Type: application/json'); // Set response type to JSON

try {
    // Check if the `id` parameter exists and is numeric
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid or missing reservation ID.');
    }

    // Get and sanitize the reservation ID
    $reservation_id = intval($_GET['id']);

    // Prepare the SQL statement to fetch reservation data
    $sql = "SELECT * FROM reservation WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reservation_id); // Binding the reservation ID
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the reservation exists
    if ($result->num_rows > 0) {
        // Fetch the reservation data
        $reservation = $result->fetch_assoc();

        // Prepare SQL to fetch rate details
        $rate_sql = "SELECT * FROM rates WHERE id = ?";
        $rate_stmt = $conn->prepare($rate_sql);
        $rate_stmt->bind_param('i', $reservation['rate_id']);
        $rate_stmt->execute();
        $rate_result = $rate_stmt->get_result();
        $rate = $rate_result->fetch_assoc();

        // Prepare SQL to fetch addons details (only if there's an addon)
        $addons = null;
        if ($reservation['addons_id']) {
            $addons_sql = "SELECT * FROM addons WHERE id = ?";
            $addons_stmt = $conn->prepare($addons_sql);
            $addons_stmt->bind_param('i', $reservation['addons_id']);
            $addons_stmt->execute();
            $addons_result = $addons_stmt->get_result();
            $addons = $addons_result->fetch_assoc();
        }

        // Prepare the response data
        $response = [
            'status' => 'success',
            'data' => [
                'reservation_id' => $reservation['reservation_id'],
                'first_name' => $reservation['first_name'],
                'last_name' => $reservation['last_name'],
                'email' => $reservation['email'],
                'phone_number' => $reservation['mobile_number'],
                'check_in_date' => $reservation['reservation_check_in_date'],
                'check_out_date' => $reservation['reservation_check_out_date'],
                'total_amount' => (float)$reservation['total_amount'], // Use total_amount directly
                'rate_name' => $rate['name'],
                'rate_price' => (float)$rate['price'], // Rate price
                'addons_name' => $addons ? $addons['name'] : null, // Addon name (if any)
                'addons_price' => $addons ? (float)$addons['price'] : null, // Addon price (if any)
                'payment_proof' => '../src/uploads/payment_proof/' . $reservation['payment_proof']
            ]
        ];

        echo json_encode($response); // Send the response as JSON
    } else {
        // If no reservation found
        echo json_encode([
            'status' => 'error',
            'message' => 'Reservation not found.'
        ]);
    }

    // Close the statements and connection
    $rate_stmt->close();
    if ($addons) $addons_stmt->close();
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Handle exceptions
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
