<?php
header('Content-Type: application/json');

include '../db_connection.php';

// Get the request payload
$data = json_decode(file_get_contents('php://input'), true);

// If data is not valid, return an error
if (!isset($data['ids']) || !isset($data['type'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$ids = $data['ids'];
$type = $data['type'];

if ($type === 'rate') {
    // Fetch the rate details from the database
    $sql = "SELECT * FROM rates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ids);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rate = $result->fetch_assoc();
        echo json_encode($rate);
    } else {
        echo json_encode(['error' => 'Rate not found']);
    }
} elseif ($type === 'addons') {
    // Fetch the add-ons details from the database
    $ids = implode(',', array_map('intval', $ids));  // sanitize ids
    $sql = "SELECT * FROM addons WHERE id IN ($ids)";
    $result = $conn->query($sql);

    $addons = [];
    while ($addon = $result->fetch_assoc()) {
        $addons[] = $addon;
    }

    echo json_encode($addons);
} else {
    echo json_encode(['error' => 'Invalid type']);
}
?>
