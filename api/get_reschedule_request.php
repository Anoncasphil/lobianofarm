<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../db_connection.php");

if (!isset($_GET["reservation_id"])) {
    echo json_encode(["status" => "error", "message" => "Reservation ID required"]);
    exit();
}

$reservation_id = intval($_GET["reservation_id"]);

$sql = "SELECT * FROM reschedule_request WHERE reservation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $reschedule_request = $result->fetch_assoc();
    echo json_encode(["status" => "success", "reschedule_request" => $reschedule_request]);
} else {
    echo json_encode(["status" => "no_request", "message" => "No reschedule request found"]);
}

$stmt->close();
$conn->close();
?>
