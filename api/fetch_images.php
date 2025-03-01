<?php
include 'db_connection.php'; // Adjusted to use the correct path for db_connection.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch folder_id from POST data
    $folder_id = intval($_POST['folder_id']);

    if ($folder_id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid folder ID."]);
        exit;
    }

    // Fetch folder path from the folders table using the folder_id
    $stmt = $conn->prepare("SELECT path FROM folders WHERE id = ?");
    $stmt->bind_param("i", $folder_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $folder = $result->fetch_assoc();

    // If folder is not found, show error message
    if (!$folder) {
        echo json_encode(["success" => false, "message" => "Folder not found with the ID: $folder_id"]);
        exit;
    }

    // Get folder path from the folder record (no need for ../ anymore)
    $folderPath = $folder['path'];

    // Fetch images from the images table for the given folder_id
    $stmt = $conn->prepare("SELECT image_path FROM images WHERE folder_id = ?");
    $stmt->bind_param("i", $folder_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = [];
    while ($row = $result->fetch_assoc()) {
        // Adjust image path to use the correct folder path without ../
        $images[] = $folderPath . "/" . $row['image_path']; // Combine folder path and image path
    }

    // Return images as JSON
    if (count($images) > 0) {
        echo json_encode(["success" => true, "images" => $images]);
    } else {
        echo json_encode(["success" => false, "message" => "No images found for this folder."]);
    }

    $stmt->close();
    $conn->close();
}
?>
