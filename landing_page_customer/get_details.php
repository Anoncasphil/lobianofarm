<?php
include("../db_connection.php");
header('Content-Type: application/json');

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];
    $table = $type === 'rate' ? 'rates' : 'addons';
    
    $stmt = $conn->prepare("SELECT name, price, description, picture FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();
    
    if ($details) {
        $details['picture'] = base64_encode($details['picture']);
        echo json_encode($details);
    } else {
        echo json_encode(['error' => 'Details not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>