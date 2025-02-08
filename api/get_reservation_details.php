<?php
require_once '../db_connection.php'; // Ensure database connection

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "No reservation ID provided"]);
    exit;
}

$reservation_id = intval($_GET['id']);

$query = "SELECT r.check_in_date, r.check_out_date, r.check_in_time, r.check_out_time, 
                 r.rate_id, rt.hoursofstay, rt.rate_type
          FROM reservations r
          JOIN rates rt ON r.rate_id = rt.id
          WHERE r.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $reservation = $result->fetch_assoc();
    echo json_encode($reservation);
} else {
    echo json_encode(["error" => "No reservation found"]);
}

$stmt->close();
$conn->close();
?>
