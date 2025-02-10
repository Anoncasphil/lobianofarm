<?php
header("Content-Type: application/json");
require_once("../db_connection.php"); // Adjust the path as necessary

// Get the reservation ID from the query parameters
$reservation_id = isset($_GET['reservation_id']) ? intval($_GET['reservation_id']) : 0;

if ($reservation_id === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid reservation ID"]);
    exit();
}

// Query to fetch reservation code from the reservations table
$sql = "SELECT reservation_code FROM reservations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);

if ($stmt->execute()) {
    $stmt->bind_result($reservation_code);
    if ($stmt->fetch()) {
        echo json_encode(["status" => "success", "reservation_code" => $reservation_code]);
    } else {
        echo json_encode(["status" => "error", "message" => "Reservation not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database query failed"]);
}

$stmt->close();
$conn->close();
?>
