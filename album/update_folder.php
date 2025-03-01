<?php
require '../db_connection.php'; // Ensure database connection is included

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data
    $folderId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $folderName = isset($_POST['name']) ? trim($_POST['name']) : '';
    $folderDescription = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Validate inputs
    if ($folderId <= 0 || empty($folderName)) {
        echo json_encode(["success" => false, "message" => "Invalid folder data."]);
        exit;
    }

    // Fetch current folder path
    $stmt = $conn->prepare("SELECT path FROM folders WHERE id = ?");
    $stmt->bind_param("i", $folderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Folder not found."]);
        exit;
    }

    $folderData = $result->fetch_assoc();
    $oldFolderPath = $folderData['path'];
    $stmt->close();

    // Get parent directory of the current folder
    $parentDirectory = dirname($oldFolderPath);
    $newFolderPath = $parentDirectory . '/' . $folderName;

    // Rename the folder in the filesystem
    if (is_dir($oldFolderPath) && !file_exists($newFolderPath)) {
        if (!rename($oldFolderPath, $newFolderPath)) {
            echo json_encode(["success" => false, "message" => "Failed to rename folder."]);
            exit;
        }
    }

    // Update the database with the new folder name and path
    $stmt = $conn->prepare("UPDATE folders SET name = ?, description = ?, path = ? WHERE id = ?");
    $stmt->bind_param("sssi", $folderName, $folderDescription, $newFolderPath, $folderId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Folder updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update folder in database."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
