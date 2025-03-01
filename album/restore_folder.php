<?php
header('Content-Type: application/json');
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folder_id'])) {
    $folder_id = intval($_POST['folder_id']);

    // Get folder info from the database
    $sql = "SELECT * FROM folders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $folder_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $folder = $result->fetch_assoc();
    $stmt->close();

    if (!$folder) {
        echo json_encode(["success" => false, "message" => "Folder not found."]);
        exit;
    }

    $oldPath = "../src/uploads/album/archive/" . basename($folder['path']);
    $newPath = "../src/uploads/album/active/" . basename($folder['path']);

    // Function to copy folder recursively
    function copyFolder($source, $destination) {
        if (!is_dir($source)) {
            return false; // Source folder doesn't exist
        }
        
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        foreach (scandir($source) as $file) {
            if ($file === '.' || $file === '..') continue;
            $srcFile = $source . DIRECTORY_SEPARATOR . $file;
            $destFile = $destination . DIRECTORY_SEPARATOR . $file;

            if (is_dir($srcFile)) {
                copyFolder($srcFile, $destFile);
            } else {
                copy($srcFile, $destFile);
            }
        }
        return true;
    }

    // Function to delete folder recursively
    function deleteFolder($folderPath) {
        if (!is_dir($folderPath)) {
            return false; // Folder doesn't exist
        }

        foreach (scandir($folderPath) as $file) {
            if ($file === '.' || $file === '..') continue;
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                deleteFolder($filePath);
            } else {
                unlink($filePath);
            }
        }
        return rmdir($folderPath);
    }

    // Attempt to copy and delete the folder
    if (copyFolder($oldPath, $newPath)) {
        if (deleteFolder($oldPath)) {
            // Update database
            $updateSql = "UPDATE folders SET archived = 0, path = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $newPath, $folder_id);

            if ($updateStmt->execute()) {
                echo json_encode(["success" => true, "message" => "Folder restored successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Database update failed."]);
            }
            $updateStmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete original folder after copying."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to copy folder."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
