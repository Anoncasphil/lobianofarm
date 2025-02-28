<?php
header('Content-Type: application/json'); 
include '../db_connection.php';

if (!isset($_POST['folder_id'])) {
    echo json_encode(["success" => false, "message" => "Folder ID is missing."]);
    exit;
}

$folder_id = intval($_POST['folder_id']); 

// Fetch folder path
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

// Define archive directory
$archiveDir = "../src/uploads/album/archive/";

// Ensure the archive directory exists
if (!file_exists($archiveDir)) {
    mkdir($archiveDir, 0777, true);
}

// Get the folder name from the existing path
$folderName = basename($folderPath);
$newFolderPath = $archiveDir . $folderName;

// Move the folder to the archive directory
if (rename($folderPath, $newFolderPath)) {
    // Update database to mark as archived and update the path
    $updateStmt = $conn->prepare("UPDATE folders SET archived = 1, path = ? WHERE id = ?");
    $updateStmt->bind_param("si", $newFolderPath, $folder_id);
    
    if ($updateStmt->execute()) {
        echo json_encode(["success" => true, "message" => "Folder archived successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update database."]);
    }
    $updateStmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Failed to move folder to archive."]);
}

$conn->close();
?>
