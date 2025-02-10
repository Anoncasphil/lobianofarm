<?php
header("Content-Type: application/json");
require_once "../db_connection.php"; // Ensure this contains your database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid data received."]);
    exit;
}

$reservationId = $data['reservation_id'] ?? null; // Use an ID to identify the existing reservation
$checkInDate = $data['check_in_date'] ?? null;
$checkOutDate = $data['check_out_date'] ?? null;
$status = $data['status'] ?? null;
$firstName = $data['first_name'] ?? null;
$lastName = $data['last_name'] ?? null;
$email = $data['email'] ?? null;
$mobileNumber = $data['mobile_number'] ?? null;

if (!$reservationId || !$checkInDate || !$checkOutDate || !$status || !$firstName || !$lastName || !$email || !$mobileNumber) {
    echo json_encode(["status" => "error", "message" => "All fields are required, including reservation ID."]);
    exit;
}

try {
    // Check if the reservation exists
    $stmt = $conn->prepare("SELECT id FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Reservation not found."]);
        exit;
    }

    $stmt->close();

    // Update the existing reservation
    $stmt = $conn->prepare("UPDATE reservations SET check_in_date = ?, check_out_date = ?, status = ?, first_name = ?, last_name = ?, email = ?, mobile_number = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $checkInDate, $checkOutDate, $status, $firstName, $lastName, $email, $mobileNumber, $reservationId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Reservation updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
}
?>
