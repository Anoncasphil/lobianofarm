<?php
header('Content-Type: application/json'); // Ensure JSON response
require_once('../db_connection.php'); // Adjust path as needed

if (isset($_GET['rate_id'])) {
    $rate_id = intval($_GET['rate_id']);

    $stmt = $conn->prepare("SELECT rate_type FROM rates WHERE id = ?");
    $stmt->bind_param("i", $rate_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "rate_type" => $row["rate_type"]]);
    } else {
        echo json_encode(["success" => false, "error" => "Rate ID not found"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Missing rate_id"]);
}
?>