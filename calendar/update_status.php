<?php
include('../db_connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$firstName = $data['firstName'];
$lastName = $data['lastName'];
$checkInDate = $data['checkInDate'];

$sql = "UPDATE reservation 
        SET status = 'Reserved' 
        WHERE first_name = ? 
        AND last_name = ? 
        AND reservation_check_in_date = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $firstName, $lastName, $checkInDate);
$result = $stmt->execute();

echo json_encode(['success' => $result]);
$stmt->close();
$conn->close();
?>