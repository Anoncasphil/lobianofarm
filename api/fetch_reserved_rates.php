<?php
include '../db_connection.php';

$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT rate_id FROM reservations WHERE check_in_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$bookedRates = [];
while ($row = $result->fetch_assoc()) {
    $bookedRates[] = $row['rate_id'];
}

echo json_encode($bookedRates);
$stmt->close();
$conn->close();
?>
