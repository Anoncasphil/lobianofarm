<?php
// Include database connection
include('../db_connection.php');

// Check if the event IDs are passed
if (isset($_POST['event_ids'])) {
    // Get the array of event IDs
    $eventIds = json_decode($_POST['event_ids']);

    // Loop through each event ID and set the status to 'inactive'
    foreach ($eventIds as $eventId) {
        $query = "UPDATE events SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $eventId); // Bind event ID to the query
        $stmt->execute();
    }

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Events archived successfully.";
    } else {
        echo "No events were archived.";
    }
} else {
    echo "No event IDs provided.";
}
?>
