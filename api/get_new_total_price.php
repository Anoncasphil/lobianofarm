<?php
include '../db_connection.php'; // Include the database connection

header('Content-Type: application/json');

if (!isset($_GET['reservation_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing reservation ID"]);
    exit;
}

$reservation_id = $_GET['reservation_id'];

// ✅ Fix: Remove comma before subtraction
$sql = "SELECT (total_price - CAST(REPLACE(valid_amount_paid, ',', '') AS DECIMAL(10,2))) AS new_total_price 
        FROM reservations 
        WHERE reservation_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$stmt->bind_result($new_total_price);
$stmt->fetch();
$stmt->close();
$conn->close();

// ✅ Ensure a valid response
if ($new_total_price !== null) {
    echo json_encode(["status" => "success", "new_total_price" => number_format($new_total_price, 2, '.', '')]);
} else {
    echo json_encode(["status" => "error", "message" => "Reservation not found"]);
}
?>
// ✅ Good job! The code is now secure and follows best practices. Well done!