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

if (!isset($_POST['folder_id'])) {
    echo json_encode(["success" => false, "message" => "Folder ID is missing."]);
    exit;
}

$folder_id = intval($_POST['folder_id']);

// Fetch folder path and name
$stmt = $conn->prepare("SELECT path, name FROM folders WHERE id = ?");
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$result = $stmt->get_result();
$folder_data = $result->fetch_assoc();
$stmt->close();

if (!$folder_data || !is_dir($folder_data['path'])) {
    echo json_encode(["success" => false, "message" => "Folder not found or invalid."]);
    exit;
}

$folderPath = $folder_data['path'];
$folderName = $folder_data['name'];

// Define archive directory
$archiveDir = "../src/uploads/album/archive/";

// Ensure the archive directory exists
if (!file_exists($archiveDir)) {
    mkdir($archiveDir, 0777, true);
}

// Get the folder name from the existing path
$folderBaseName = basename($folderPath);
$newFolderPath = $archiveDir . $folderBaseName;

// If the folder already exists in archive, rename it
$counter = 1;
while (file_exists($newFolderPath)) {
    $newFolderPath = $archiveDir . $folderBaseName . "_$counter";
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
            // Log the archive action
            logFolderArchive($admin_id, $admin_name, $folder_id, $folderName);
            
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

/**
 * Log folder archive action to activity logs
 */
function logFolderArchive($admin_id, $admin_name, $folder_id, $folder_name) {
    global $conn;
    
    // Set timezone
    date_default_timezone_set('Asia/Manila');
    
    // Create log message
    $log_message = "Archived the album folder: '$folder_name' (ID: $folder_id).";
    
    // Insert into activity_logs table
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
