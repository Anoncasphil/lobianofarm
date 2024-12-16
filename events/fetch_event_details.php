<?php
// fetch_event_details.php
include('../db_connection.php');

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Query to get event details
    $query = "SELECT name, picture, date, description FROM events WHERE id = $eventId";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Return event details as a JSON response
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Event not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
