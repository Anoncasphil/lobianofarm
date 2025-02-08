<?php
include('../db_connection.php'); // Include your DB connection file

// Start output buffering to prevent unwanted output before JSON response
ob_start();
header('Content-Type: application/json');
error_reporting(0); // Temporarily suppress errors for debugging

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

        // Prepare SQL to fetch reservation_addons details
        $reservation_addons_sql = "SELECT addon_id FROM reservation_addons WHERE reservation_id = ?";
        $reservation_addons_stmt = $conn->prepare($reservation_addons_sql);
        $reservation_addons_stmt->bind_param('i', $reservation_id);
        $reservation_addons_stmt->execute();
        $reservation_addons_result = $reservation_addons_stmt->get_result();
        $addon_ids = [];
        while ($row = $reservation_addons_result->fetch_assoc()) {
            $addon_ids[] = $row['addon_id'];
        }

        // Fetch addons details
        $addons = [];
        if (!empty($addon_ids)) {
            // Use placeholders to prevent SQL injection
            $placeholders = implode(',', array_fill(0, count($addon_ids), '?'));
            $addons_sql = "SELECT id, name, price FROM addons WHERE id IN ($placeholders)";
            $addons_stmt = $conn->prepare($addons_sql);

            // Bind parameters dynamically
            $addon_types = str_repeat('i', count($addon_ids)); // 'i' for integers
            $addons_stmt->bind_param($addon_types, ...$addon_ids);
            $addons_stmt->execute();
            $addons_result = $addons_stmt->get_result();
            while ($addon = $addons_result->fetch_assoc()) {
                $addons[] = $addon;
            }
        }

        // Prepare SQL to fetch rate details (Check if `rate_id` is NULL)
        $rate = null;
        if (!empty($reservation['rate_id'])) {
            $rate_sql = "SELECT id, name, price FROM rates WHERE id = ?";
            $rate_stmt = $conn->prepare($rate_sql);
            $rate_stmt->bind_param('i', $reservation['rate_id']);
            $rate_stmt->execute();
            $rate_result = $rate_stmt->get_result();
            $rate = $rate_result->fetch_assoc();
        }

        // Prepare the response data
        $response = [
            'status' => 'success',
            'data' => [
                'id' => $reservation['id'],
                'user_id' => $reservation['user_id'],
                'first_name' => $reservation['first_name'],
                'last_name' => $reservation['last_name'],
                'email' => $reservation['email'],
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
                'rate' => $rate ? [
                    'id' => $rate['id'],
                    'name' => $rate['name'],
                    'price' => (float)$rate['price']
                ] : null,
                'addons' => !empty($addons) ? $addons : null // If no addons, return null
            ]
        ];

        // Clean buffer and return JSON response
        ob_end_clean();
        echo json_encode($response);
        exit;
    } else {
        throw new Exception('Reservation not found.');
    }
} catch (Exception $e) {
    // Clean buffer and return JSON error response
    ob_end_clean();
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
} finally {
    // Close statements and database connection
    if (isset($stmt)) $stmt->close();
    if (isset($reservation_addons_stmt)) $reservation_addons_stmt->close();
    if (isset($addons_stmt)) $addons_stmt->close();
    if (isset($rate_stmt)) $rate_stmt->close();
    $conn->close();
}
?>
