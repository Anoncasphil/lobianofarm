<?php
session_start();
include("../db_connection.php");

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    $stmt = $conn->prepare("SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name, email, contact_no AS mobile_number FROM user_tbl WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        throw new Exception('User not found');
    }

    echo json_encode($user);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>