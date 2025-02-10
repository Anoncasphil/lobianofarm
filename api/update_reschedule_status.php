<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../db_connection.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["request_id"]) || !isset($data["status"])) {
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit();
}

$request_id = intval($data["request_id"]);
$status = $data["status"];

$query = "SELECT * FROM reschedule_request WHERE request_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Reschedule request not found"]);
    exit();
}

$reschedule = $result->fetch_assoc();
$reservation_id = $reschedule["reservation_id"];
$new_check_in_date = $reschedule["check_in_date"];
$new_check_out_date = $reschedule["check_out_date"];

// ✅ If Approved, update reservation dates
if ($status === "Approved") {
    $update_reservation_query = "UPDATE reservations 
                                 SET check_in_date = ?, check_out_date = ? 
                                 WHERE id = ?";
    $stmt = $conn->prepare($update_reservation_query);
    $stmt->bind_param("ssi", $new_check_in_date, $new_check_out_date, $reservation_id);

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Error updating reservations: " . $stmt->error]);
        exit();
    }
}

// ✅ Update reschedule_request status
$update_status_query = "UPDATE reschedule_request SET status = ? WHERE request_id = ?";
$stmt = $conn->prepare($update_status_query);
$stmt->bind_param("si", $status, $request_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Request updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database update failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
