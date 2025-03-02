<?php
// Include the database connection file
include('../db_connection.php');
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo 'unauthorized';
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get logged-in admin ID

// Fetch admin details from the database
$sql_admin = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

$admin_name = $firstname . " " . $lastname; // Full name of the admin

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the addon ID from the POST request
    $addonId = $_POST['addon_id'];
    
    // Get addon details before restoring
    $sql_addon = "SELECT name FROM addons WHERE id = ?";
    $stmt_addon = $conn->prepare($sql_addon);
    $stmt_addon->bind_param("i", $addonId);
    $stmt_addon->execute();
    $stmt_addon->bind_result($addon_name);
    $stmt_addon->fetch();
    $stmt_addon->close();

    // Prepare the query to update the status to 'active'
    $query = "UPDATE addons SET status = 'active' WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters and execute the query
        $stmt->bind_param("i", $addonId);  // 'i' denotes the integer type
        if ($stmt->execute()) {
            // Log the restore action
            logAddonRestore($admin_id, $admin_name, $addonId, $addon_name);
            echo "success"; // Simple success response for JavaScript to handle
        } else {
            echo "error"; // Error response for JavaScript to handle
        }
        $stmt->close();
    } else {
        echo "error"; // Error response if query preparation fails
    }
}

/**
 * Log the addon restoration action to the database
 */
function logAddonRestore($admin_id, $admin_name, $addonId, $addon_name) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Log message
    $log_message = "Restored the addon: $addon_name (ID: $addonId).";

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Close the database connection
$conn->close();
?>
