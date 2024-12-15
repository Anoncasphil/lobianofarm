<?php
// get-event-details.php
include('../db_connection.php'); // Include your database connection

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Prepare SQL query to fetch event data
    $sql = "SELECT id, name, date, description, picture FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();

        // Check if picture is stored as a relative path and prepend the correct folder
        if ($event['picture']) {
            $event['picture'] = '../src/uploads/events/' . $event['picture'];  // Modify the path according to your file structure
        }

        // Convert the date format from 'YYYY-MM-DD' to 'MM/DD/YYYY'
        if ($event['date']) {
            $date = new DateTime($event['date']);
            $event['date'] = $date->format('m/d/Y'); // Convert to mm/dd/yyyy format
        }

        echo json_encode($event);  // Return event details as JSON
    } else {
        echo json_encode(['error' => 'Event not found']);  // Return error as JSON
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid event ID']);  // Return error as JSON
}
?>
