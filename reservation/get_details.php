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
    $sql = "SELECT * FROM reservations WHERE id = ?";
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

        // Prepare SQL to fetch addons details
        $addons_sql = "SELECT a.name, a.price FROM addons a
                       JOIN reservation_addons ra ON a.id = ra.addon_id
                       WHERE ra.reservation_id = ?";
        $addons_stmt = $conn->prepare($addons_sql);
        $addons_stmt->bind_param('i', $reservation_id);
        $addons_stmt->execute();
        $addons_result = $addons_stmt->get_result();
        $addons = [];
        while ($addon = $addons_result->fetch_assoc()) {
            $addons[] = $addon;
        }

        // Prepare the response data
        $response = [
            'status' => 'success',
            'data' => [
                'reservation_id' => $reservation['id'],
                'user_id' => $reservation['user_id'],
                'check_in_date' => $reservation['check_in_date'],
                'check_out_date' => $reservation['check_out_date'],
                'check_in_time' => $reservation['check_in_time'],
                'check_out_time' => $reservation['check_out_time'],
                'reference_number' => $reservation['reference_number'],
                'invoice_date' => $reservation['invoice_date'],
                'invoice_number' => $reservation['invoice_number'],
                'total_price' => (float)$reservation['total_price'],
                'payment_receipt' => $reservation['payment_receipt'],
                'status' => $reservation['status'],
                'payment_status' => $reservation['payment_status'],
                'contact_number' => $reservation['contact_number'],
                'created_at' => $reservation['created_at'],
                'updated_at' => $reservation['updated_at'],
                'rate' => [
                    'id' => $rate['id'],
                    'name' => $rate['name'],
                    'price' => (float)$rate['price']
                ],
                'addons' => $addons
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
    $addons_stmt->close();
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
