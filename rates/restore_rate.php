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
    // Get the rate ID from the POST request
    $rateId = $_POST['rate_id'];
    
    // Get rate details before restoring
    $sql_rate = "SELECT name FROM rates WHERE id = ?";
    $stmt_rate = $conn->prepare($sql_rate);
    $stmt_rate->bind_param("i", $rateId);
    $stmt_rate->execute();
    $stmt_rate->bind_result($rate_name);
    $stmt_rate->fetch();
    $stmt_rate->close();

    // Prepare the query to update the status to 'active'
    $query = "UPDATE rates SET status = 'active' WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters and execute the query
        $stmt->bind_param("i", $rateId);  // 'i' denotes the integer type
        if ($stmt->execute()) {
            // Log the restore action
            logRateRestore($admin_id, $admin_name, $rateId, $rate_name);
            echo "success"; // Simple success response for JavaScript to handle
        } else {
            echo "error"; // Error response for JavaScript to handle
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the query.";
    }
}

/**
 * Log the rate restoration action to the database
 */
function logRateRestore($admin_id, $admin_name, $rate_id, $rate_name) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Create a structured change log
    $changes = array(
        'Restore' => "Restored the Rate named as: $rate_name."
    );

    // Convert to JSON for storage
    $changes_json = json_encode($changes);

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, rate_id, timestamp, changes) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $admin_id, $rate_id, $changes_json);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Close the database connection
$conn->close();
?>
