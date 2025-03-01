<?php
header('Content-Type: application/json');
include('../db_connection.php');

if (isset($_POST['date'])) {
    $date = $_POST['date'];
    
    // Prepare the query to remove the disabled date from the database
    $sql = "DELETE FROM disable_dates WHERE disable_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove the disabled date.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Date parameter is missing.']);
}

$conn->close();
?>
