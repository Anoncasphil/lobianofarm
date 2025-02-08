<?php
include '../db_connection.php';

header('Content-Type: application/json');

if (!isset($_GET['rate_id'])) {
    echo json_encode(["error" => "Missing rate ID"]);
    exit;
}

$rate_id = intval($_GET['rate_id']);

$query = "SELECT rate_type FROM rates WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rate_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["rate_type" => $row['rate_type']]);
} else {
    echo json_encode(["error" => "Rate not found"]);
}

$stmt->close();
$conn->close();
?>
