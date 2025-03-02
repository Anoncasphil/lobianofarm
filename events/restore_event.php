<?php
// Include your database connection file
include('../db_connection.php');
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
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

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the request is POST and event_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    // Get the event ID from the POST request
    $eventId = $_POST['event_id'];

    if ($eventId) {
        // Get event details before restoring
        $sql_event = "SELECT name FROM events WHERE id = ?";
        $stmt_event = $conn->prepare($sql_event);
        $stmt_event->bind_param("i", $eventId);
        $stmt_event->execute();
        $stmt_event->bind_result($event_name);
        $stmt_event->fetch();
        $stmt_event->close();
        
        // Prepare the SQL query to update the status to 'active' for the selected event ID
        $query = "UPDATE events SET status = 'active' WHERE id = ?";

        // Prepare the statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the event ID parameter to the prepared statement
            $stmt->bind_param("i", $eventId);

            // Execute the query
            if ($stmt->execute()) {
                // Log the restore action
                logEventRestore($admin_id, $admin_name, $eventId, $event_name);
                
                // Return a success response
                echo json_encode(["status" => "success", "message" => "Event successfully restored."]);
            } else {
                // Return an error response if query execution fails
                echo json_encode(["status" => "error", "message" => "Error executing query: " . $stmt->error]);
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            // Return an error response if query preparation fails
            echo json_encode(["status" => "error", "message" => "Error preparing the query: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No event ID provided."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

/**
 * Log the event restoration action to the database
 */
function logEventRestore($admin_id, $admin_name, $eventId, $event_name) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Log message
    $log_message = "Restored the event: $event_name (ID: $eventId).";

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

// Close the database connection
mysqli_close($conn);
?>
