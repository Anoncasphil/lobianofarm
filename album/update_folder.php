<?php
require '../db_connection.php'; // Ensure database connection is included
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

    // Fetch current folder data for comparison
    $stmt = $conn->prepare("SELECT name, description, path FROM folders WHERE id = ?");
    $stmt->bind_param("i", $folderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Folder not found."]);
        exit;
    }

    $folderData = $result->fetch_assoc();
    $oldFolderPath = $folderData['path'];
    $oldFolderName = $folderData['name'];
    $oldFolderDesc = $folderData['description'];
    $stmt->close();

    // Track changes for logging
    $changes = [];
    if ($folderName !== $oldFolderName) {
        $changes[] = "Name changed from '{$oldFolderName}' to '{$folderName}'";
    }
    if ($folderDescription !== $oldFolderDesc) {
        $changes[] = "Description was updated";
    }

    // Get parent directory of the current folder
    $parentDirectory = dirname($oldFolderPath);
    $newFolderPath = $parentDirectory . '/' . $folderName;

    // Check if folder name is actually changing
    $folderPathChanged = $newFolderPath !== $oldFolderPath;
    
    // Only attempt to rename the folder if the name changed
    if ($folderPathChanged) {
        if (is_dir($oldFolderPath) && !file_exists($newFolderPath)) {
            if (!rename($oldFolderPath, $newFolderPath)) {
                echo json_encode(["success" => false, "message" => "Failed to rename folder on disk."]);
                exit;
            }
            $changes[] = "Folder path updated successfully";
        } elseif (file_exists($newFolderPath) && $newFolderPath !== $oldFolderPath) {
            echo json_encode(["success" => false, "message" => "A folder with this name already exists."]);
            exit;
        }
    }

    // Update the database with the new folder name and path
    $stmt = $conn->prepare("UPDATE folders SET name = ?, description = ?, path = ? WHERE id = ?");
    $stmt->bind_param("sssi", $folderName, $folderDescription, $newFolderPath, $folderId);

    if ($stmt->execute()) {
        // Log the update if there were changes
        if (!empty($changes)) {
            logFolderUpdate($admin_id, $admin_name, $folderId, $folderName, $changes);
        }
        
        echo json_encode(["success" => true, "message" => "Folder updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update folder in database."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

/**
 * Log folder update action to activity logs
 */
function logFolderUpdate($admin_id, $admin_name, $folder_id, $folder_name, $changes) {
    global $conn;
    
    // Set timezone
    date_default_timezone_set('Asia/Manila');
    
    // Create log message
    $log_message = "Updated the album folder: '$folder_name' (ID: $folder_id).<br>";
    
    // Add each change with a line break
    foreach($changes as $change) {
        $log_message .= "- " . $change . ".<br>";
    }
    
    // Insert into activity_logs table
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
