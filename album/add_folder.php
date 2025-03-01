<?php
include '../db_connection.php';

// Ensure JSON response
header('Content-Type: application/json');
ob_start(); // Start output buffering

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($name)) {
        echo json_encode(["success" => false, "message" => "Folder name is required"]);
        exit;
    }

    // Base directory where folders will be stored
    $baseDir = "../src/uploads/album/active/";

    // Ensure the active directory exists
    if (!file_exists($baseDir)) {
        mkdir($baseDir, 0777, true);
    }

    // Sanitize folder name for filesystem use, remove spaces
    $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name); // Replace invalid characters
    $folderName = str_replace(' ', '_', $folderName); // Replace spaces with underscores
    $folderPath = $baseDir . $folderName; // Full folder path

    // Insert into database with folder path
    $stmt = $conn->prepare("INSERT INTO folders (name, description, path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $folderPath);

    if ($stmt->execute()) {
        // Only create the folder if the database insert is successful
        if (!file_exists($folderPath)) {
            if (mkdir($folderPath, 0777, true)) { 
                $response = ["success" => true, "message" => "Folder added and directory created"];
            } else {
                $response = ["success" => false, "message" => "Database added but failed to create directory"];
            }
        } else {
            $response = ["success" => true, "message" => "Folder added successfully"];
        }
    } else {
        $response = ["success" => false, "message" => "Database error"];
    }

    $stmt->close();
    $conn->close();

    ob_end_clean(); // Clear unwanted output
    echo json_encode($response);
    exit;
}
?>
