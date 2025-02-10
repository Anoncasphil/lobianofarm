<?php
header('Content-Type: application/json');
require_once '../db_connection.php'; // Ensure correct path

if (!isset($_GET['reservation_id']) || !is_numeric($_GET['reservation_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid reservation ID']);
    exit;
}

$reservation_id = intval($_GET['reservation_id']);

// Prepare SQL query
$query = "SELECT r.id AS reservation_id, r.rate_id, rates.name AS rate_name, 
                 rates.price AS rate_price, rates.rate_type, r.check_in_date
          FROM reservations r
          JOIN rates ON rates.id = r.rate_id
          WHERE r.id = ?";

// Prepare and execute query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => 'success', 'reservation' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Reservation not found']);
}

$stmt->close();
$conn->close();
?>
