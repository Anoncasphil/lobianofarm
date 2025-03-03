<?php
// Include your database connection
include '../db_connection.php';
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$logged_admin_id = $_SESSION['admin_id']; // Get logged-in admin ID

// Fetch admin details from the database
$sql_admin = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $logged_admin_id);
$stmt->execute();
$stmt->bind_result($admin_firstname, $admin_lastname);
$stmt->fetch();
$stmt->close();

$admin_name = $admin_firstname . " " . $admin_lastname; // Full name of the admin

// Check if the admin_ids are posted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_ids'])) {
    // Decode the admin IDs from the JSON string
    $adminIds = json_decode($_POST['admin_ids'], true);

    // Check if adminIds are valid
    if (!empty($adminIds)) {
        // Get admin details before deletion for logging
        $deletedAdmins = [];
        $adminIdsStr = implode(",", $adminIds);
        $query = "SELECT admin_id, firstname, lastname, role FROM admin_tbl WHERE admin_id IN ($adminIdsStr)";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $deletedAdmins[] = [
                    'id' => $row['admin_id'],
                    'name' => $row['firstname'] . ' ' . $row['lastname'],
                    'role' => $row['role']
                ];
            }
        }

        // Prepare the query to delete the selected admins
        $query = "DELETE FROM admin_tbl WHERE admin_id IN ($adminIdsStr)";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Log the deletion of admins
            logAdminDeletion($logged_admin_id, $admin_name, $deletedAdmins);
            
            echo "Selected admins have been deleted successfully.";
        } else {
            echo "Error deleting admins: " . mysqli_error($conn);
        }
    } else {
        echo "No admin IDs received.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);

/**
 * Log the admin deletion action to the database
 */
function logAdminDeletion($admin_id, $admin_name, $deleted_admins) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Initialize log message with HTML line breaks
    $log_message = "Deleted " . count($deleted_admins) . " admin account(s):<br>";
    
    // Add details of each deleted admin
    foreach($deleted_admins as $deleted) {
        $log_message .= "- " . $deleted['name'] . " (ID: " . $deleted['id'] . ", Role: " . $deleted['role'] . ")<br>";
    }

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}
?>
