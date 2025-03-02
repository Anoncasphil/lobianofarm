<?php
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
        $folder_id = $conn->insert_id; // Get new folder ID
        
        // Only create the folder if the database insert is successful
        if (!file_exists($folderPath)) {
            if (mkdir($folderPath, 0777, true)) { 
                // Log the folder creation
                logFolderAction($admin_id, $admin_name, $folder_id, $name, "Added a new album folder");
                
                $response = ["success" => true, "message" => "Folder added and directory created"];
            } else {
                $response = ["success" => false, "message" => "Database added but failed to create directory"];
            }
        } else {
            // Log the folder creation
            logFolderAction($admin_id, $admin_name, $folder_id, $name, "Added a new album folder");
            
            $response = ["success" => true, "message" => "Folder added successfully"];
        }
    } else {
        $response = ["success" => false, "message" => "Database error"];
    }

    $stmt->close();
    
    ob_end_clean(); // Clear unwanted output
    echo json_encode($response);
    exit;
}

/**
 * Log folder action to activity logs
 */
function logFolderAction($admin_id, $admin_name, $folder_id, $folder_name, $action) {
    global $conn;
    
    // Set timezone
    date_default_timezone_set('Asia/Manila');
    
    // Create log message
    $log_message = "$action: '$folder_name' (ID: $folder_id).";
    
    // Insert into activity_logs table
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
