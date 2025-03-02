<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session

include('../db_connection.php'); // Database connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Admin not logged in']);
    exit;
}

$admin_id = $_SESSION['admin_id'];
error_log("Admin ID: " . $admin_id); // Debugging admin_id

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $disable_date = filter_var($_POST['disable_date'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    error_log("Received data: Disable Date: $disable_date, Reason: $reason, Status: $status");

    // Insert into disable_dates
    $stmt = $conn->prepare("INSERT INTO disable_dates (disable_date, reason, status) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $disable_date, $reason, $status);

    if ($stmt->execute()) {
        // Fetch admin details
        $stmt_admin = $conn->prepare("SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?");
        $stmt_admin->bind_param('i', $admin_id);
        $stmt_admin->execute();
        $stmt_admin->bind_result($firstname, $lastname);
        $stmt_admin->fetch();
        $stmt_admin->close();

        error_log("Admin Name: $firstname $lastname"); // Debugging name fetch

        // Construct activity log message
        $changes = "Admin $firstname $lastname disabled the date $disable_date for reason: $reason.";

        // Insert into activity_logs (without rate_id)
        $stmt_log = $conn->prepare("INSERT INTO activity_logs (admin_id, changes) VALUES (?, ?)");
        $stmt_log->bind_param('is', $admin_id, $changes);

        if ($stmt_log->execute()) {
            error_log("Activity log inserted successfully.");
        } else {
            error_log("Activity log insert error: " . $stmt_log->error);
        }

        $stmt_log->close();

        echo json_encode(['success' => true]);
    } else {
        error_log("Database insert error: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database insert failed']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
