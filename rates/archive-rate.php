<?php
include '../db_connection.php'; // Include the database connection
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $rateId = $_POST['id'];

        // Get rate details before archiving
        $sql_rate = "SELECT name FROM rates WHERE id = ?";
        $stmt_rate = $conn->prepare($sql_rate);
        $stmt_rate->bind_param("i", $rateId);
        $stmt_rate->execute();
        $stmt_rate->bind_result($rate_name);
        $stmt_rate->fetch();
        $stmt_rate->close();
        
        // Update the status of the rate to inactive
        $sql = "UPDATE rates SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $rateId);

        if ($stmt->execute()) {
            // Log the archive action
            logRateArchive($admin_id, $admin_name, $rateId, $rate_name);
            echo 'success'; // Success response
        } else {
            echo 'error'; // Error response
        }

        $stmt->close();
    }
}

/**
 * Log the rate archive action to the database
 */
function logRateArchive($admin_id, $admin_name, $rate_id, $rate_name) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Create a structured change log
    $changes = array(
        'Archive' => "Archived the Rate named as: $rate_name."
    );

    // Convert to JSON for storage
    $changes_json = json_encode($changes);

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, rate_id, timestamp, changes) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $admin_id, $rate_id, $changes_json);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
