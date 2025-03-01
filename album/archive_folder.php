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

if (!$folderPath || !is_dir($folderPath)) {
    echo json_encode(["success" => false, "message" => "Folder not found or invalid."]);
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

// If the folder already exists in archive, rename it
$counter = 1;
while (file_exists($newFolderPath)) {
    $newFolderPath = $archiveDir . $folderName . "_$counter";
    $counter++;
}

// Function to copy folder
function copy_folder($source, $destination) {
    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }

    foreach (scandir($source) as $file) {
        if ($file === '.' || $file === '..') continue;

        $srcFile = "$source/$file";
        $destFile = "$destination/$file";

        if (is_dir($srcFile)) {
            copy_folder($srcFile, $destFile);
        } else {
            copy($srcFile, $destFile);
        }
    }
    return true;
}

// Copy the folder instead of renaming
if (copy_folder($folderPath, $newFolderPath)) {
    // Function to delete original folder
    function delete_folder($folder) {
        foreach (scandir($folder) as $file) {
            if ($file === '.' || $file === '..') continue;
            $path = "$folder/$file";
            is_dir($path) ? delete_folder($path) : unlink($path);
        }
        return rmdir($folder);
    }

    if (delete_folder($folderPath)) {
        // Update database to mark as archived
        $updateStmt = $conn->prepare("UPDATE folders SET archived = 1, path = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newFolderPath, $folder_id);
        
        if ($updateStmt->execute()) {
            echo json_encode(["success" => true, "message" => "Folder archived successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update database."]);
        }
        $updateStmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete original folder."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Failed to move folder to archive."]);
}

$conn->close();
?>
