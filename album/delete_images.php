<?php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['image_ids']) && !empty($_POST['image_paths'])) {
    $imageIds = $_POST['image_ids'];
    $imagePaths = $_POST['image_paths'];

    // Convert IDs to a comma-separated list for SQL
    $idsString = implode(",", array_map('intval', $imageIds));

    // Delete records from the database
    $deleteQuery = "DELETE FROM images WHERE id IN ($idsString)";
    if ($conn->query($deleteQuery)) {
        // Delete files from the folder
        foreach ($imagePaths as $path) {
            $fullPath = $path;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
