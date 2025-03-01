<?php
header('Content-Type: application/json');
include '../db_connection.php';

if (!isset($_POST['folder_id'])) {
    echo json_encode(["success" => false, "message" => "Folder ID is missing."]);
    exit;
}

$folder_id = intval($_POST['folder_id']);

// Fetch folder path from the database
$stmt = $conn->prepare("SELECT path FROM folders WHERE id = ?");
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$stmt->bind_result($folderPath);
$stmt->fetch();
$stmt->close();

if (!$folderPath) {
    echo json_encode(["success" => false, "message" => "Folder not found."]);
    exit;
}

// Function to delete folder and its contents recursively
function deleteFolderRecursively($folderPath) {
    if (!file_exists($folderPath)) {
        return false; // Folder does not exist
    }

    if (is_dir($folderPath)) {
        $files = array_diff(scandir($folderPath), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
            is_dir($filePath) ? deleteFolderRecursively($filePath) : unlink($filePath);
        }
        return rmdir($folderPath); // Remove the now-empty folder
    } else {
        return unlink($folderPath); // Delete if it's a file
    }
}

// Attempt to delete the folder from the filesystem
if (deleteFolderRecursively($folderPath)) {
    // Remove folder record from the database
    $deleteStmt = $conn->prepare("DELETE FROM folders WHERE id = ?");
    $deleteStmt->bind_param("i", $folder_id);

    if ($deleteStmt->execute()) {
        echo json_encode(["success" => true, "message" => "Folder deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete folder from database."]);
    }
    $deleteStmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete folder from the server."]);
}

$conn->close();
?>
