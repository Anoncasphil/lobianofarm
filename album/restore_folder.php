<?php
header('Content-Type: application/json');
include '../db_connection.php';
session_start(); // Start session for admin tracking

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get logged-in admin ID

// Fetch admin details
$sql_admin = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

$admin_name = $firstname . " " . $lastname; // Full name of admin

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

    $folderName = $folder['name'];
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
                // Log the restore action
                logFolderRestore($admin_id, $admin_name, $folder_id, $folderName);
                
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

/**
 * Log folder restore action to activity logs
 */
function logFolderRestore($admin_id, $admin_name, $folder_id, $folder_name) {
    global $conn;
    
    // Set timezone
    date_default_timezone_set('Asia/Manila');
    
    // Create log message
    $log_message = "Restored the album folder: '$folder_name' (ID: $folder_id).";
    
    // Insert into activity_logs table
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

?>
