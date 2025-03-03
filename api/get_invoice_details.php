<?php
include '../db_connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check database connection
if (!$conn) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . mysqli_connect_error()]));
}

// Check if reservation_id is provided
if (!isset($_GET['reservation_id']) || empty($_GET['reservation_id']) || !is_numeric($_GET['reservation_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Reservation ID.']);
    exit;
}

$reservation_id = $_GET['reservation_id'];

// Fetch reservation details along with rate name
$query = "SELECT res.*, r.name AS rate_name 
          FROM reservations res
          JOIN rates r ON res.rate_id = r.id
          WHERE res.id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('i', $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reservation = $result->fetch_assoc();

        // Fetch associated addons
        $addonsQuery = "
            SELECT ra.addon_id, a.name AS addon_name, a.price AS addon_price, a.description AS addon_description, a.picture AS addon_picture
            FROM reservation_addons ra
            JOIN addons a ON ra.addon_id = a.id
            WHERE ra.reservation_id = ?";
        $addonsStmt = $conn->prepare($addonsQuery);
        $addons = [];

        if ($addonsStmt) {
            $addonsStmt->bind_param('i', $reservation_id);
            $addonsStmt->execute();
            $addonsResult = $addonsStmt->get_result();
            while ($addon = $addonsResult->fetch_assoc()) {
                $addons[] = $addon;
            }
        }

        // Calculate new total
        $valid_amount_paid = number_format($reservation['valid_amount_paid'], 2, '.', ',');
        $new_total = floatval(str_replace(',', '', $reservation['total_price'])) - floatval(str_replace(',', '', $valid_amount_paid));

        // Return JSON response
        echo json_encode([
            'reservation_id' => $reservation_id,
            'invoice_date' => $reservation['invoice_date'],
            'invoice_number' => $reservation['invoice_number'],
            'reference_number' => $reservation['reference_number'],
            'total_price' => $reservation['total_price'],
            'valid_amount_paid' => $valid_amount_paid,
            'new_total_amount' => number_format($new_total, 2, '.', ''),
            'payment_receipt' => $reservation['payment_receipt'],
            'status' => $reservation['status'],
            'contact_number' => $reservation['contact_number'],
            'first_name' => $reservation['first_name'],
            'last_name' => $reservation['last_name'],
            'email' => $reservation['email'],
            'mobile_number' => $reservation['mobile_number'],
            'checkin_date' => $reservation['check_in_date'],
            'checkout_date' => $reservation['check_out_date'],
            'checkin_time' => $reservation['check_in_time'],
            'checkout_time' => $reservation['check_out_time'],
            'rate_name' => $reservation['rate_name'], // ✅ Fetching rate name from rates table
            'rate_price' => $reservation['rate_price'], // ✅ Fetching rate price from reservations table
            'extra_pax' => $reservation['extra_pax'],
            'extra_pax_price' => $reservation['extra_pax_price'],
            'addons' => $addons
        ]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Reservation not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare reservation query. Error: ' . $conn->error]);
}

// Close database connection
$conn->close();
?>
