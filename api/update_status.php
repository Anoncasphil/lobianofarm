<?php
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['reservationId']) || !isset($data['title'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$reservation_id = $data['reservationId'];
$title = $data['title'];

$query = "UPDATE reservations SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $title, $reservation_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}
?>