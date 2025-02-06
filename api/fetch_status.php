<?php
include '../db_connection.php';

$sql = "SELECT status FROM reservations WHERE id = ?"; // Use the correct reservation ID
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET['reservation_id']); // Get reservation ID from URL
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => $row['status']]);
} else {
    echo json_encode(['status' => 'Pending']); // Default status
}

$stmt->close();
$conn->close();
?>
