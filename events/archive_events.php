<?php
// Include database connection
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

// Check if the event IDs are passed
if (isset($_POST['event_ids'])) {
    // Get the array of event IDs
    $eventIds = json_decode($_POST['event_ids']);
    
    // Array to store event names for logging
    $archived_events = [];

    // Get event details before archiving
    $placeholders = str_repeat('?,', count($eventIds) - 1) . '?';
    $sql_events = "SELECT id, name FROM events WHERE id IN ($placeholders)";
    $stmt_events = $conn->prepare($sql_events);
    
    // Create parameter binding array
    $types = str_repeat('i', count($eventIds));
    $stmt_events->bind_param($types, ...$eventIds);
    $stmt_events->execute();
    $result = $stmt_events->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $archived_events[$row['id']] = $row['name'];
    }
    $stmt_events->close();

    // Loop through each event ID and set the status to 'inactive'
    $success_count = 0;
    foreach ($eventIds as $eventId) {
        $query = "UPDATE events SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $eventId); // Bind event ID to the query
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $success_count++;
        }
        $stmt->close();
    }

    // Log the archive action if any events were successfully archived
    if ($success_count > 0) {
        logEventsArchive($admin_id, $admin_name, $eventIds, $archived_events);
        echo "Events archived successfully.";
    } else {
        echo "No events were archived.";
    }
} else {
    echo "No event IDs provided.";
}

/**
 * Log the events archiving action to the database
 */
function logEventsArchive($admin_id, $admin_name, $eventIds, $event_names) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Initialize log message
    if (count($eventIds) == 1) {
        $eventId = $eventIds[0];
        $event_name = $event_names[$eventId];
        $log_message = "Archived the event: $event_name (ID: $eventId).";
    } else {
        $log_message = "Archived multiple events:<br>";
        foreach ($eventIds as $id) {
            if (isset($event_names[$id])) {
                $log_message .= "- {$event_names[$id]} (ID: $id)<br>";
            }
        }
    }

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
