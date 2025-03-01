<?php
// remove_disabled_date.php
header('Content-Type: application/json');
include 'db.php'; // Assuming a DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date']; // Date to re-enable

    $stmt = $conn->prepare("DELETE FROM disable_dates WHERE disable_date = ?");
    $stmt->bind_param("s", $date);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Date re-enabled successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to re-enable date']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
