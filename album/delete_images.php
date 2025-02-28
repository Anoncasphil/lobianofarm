<?php
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_ids'])) {
    $image_ids = $_POST['image_ids'];
    if (!is_array($image_ids) || empty($image_ids)) {
        echo json_encode(["success" => false, "message" => "No images selected."]);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($image_ids), '?'));
    $stmt = $conn->prepare("DELETE FROM images WHERE id IN ($placeholders)");

    $types = str_repeat('i', count($image_ids));
    $stmt->bind_param($types, ...$image_ids);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete images."]);
    }
}
?>
