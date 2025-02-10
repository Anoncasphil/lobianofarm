<?php
include('../db_connection.php');
header('Content-Type: application/json');

// Check for a valid database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Reservation ID is missing']);
    exit;
}

// Query for reservation details (including rate_type and check_in_date)
$reservationStmt = $conn->prepare("SELECT r.id AS reservation_id, r.rate_id, rates.name AS rate_name, rates.price AS rate_price, rates.rate_type, r.check_in_date
FROM reservations r
JOIN rates ON rates.id = r.rate_id
WHERE r.id = ?");
$reservationStmt->bind_param('i', $reservation_id);
$reservationStmt->execute();
$reservationResult = $reservationStmt->get_result();

if ($reservationResult->num_rows > 0) {
    $reservationData = $reservationResult->fetch_assoc();

    // Fetch associated addons
    $addonsStmt = $conn->prepare("SELECT ra.addon_id, a.name AS addon_name, a.price AS addon_price, a.description AS addon_description, a.picture AS addon_picture
    FROM reservation_addons ra
    JOIN addons a ON ra.addon_id = a.id
    WHERE ra.reservation_id = ?");
    $addonsStmt->bind_param('i', $reservation_id);
    $addonsStmt->execute();
    $addonsResult = $addonsStmt->get_result();

    $addons = [];
    while ($addon = $addonsResult->fetch_assoc()) {
        $addons[] = $addon;
    }

    $addonsStmt->close(); // Close the addons statement

    echo json_encode([
        'status' => 'success',
        'reservation' => $reservationData,
        'addons' => $addons
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Reservation not found']);
}

$reservationStmt->close(); // Close the reservation statement
$conn->close(); // Close the database connection
?>
