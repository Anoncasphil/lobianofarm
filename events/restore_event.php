<?php
// Include your database connection file
include('../db_connection.php');  // Adjust the path to go one level up

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the request is POST and event_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    // Get the event ID from the POST request
    $eventId = $_POST['event_id'];

    if ($eventId) {
        // Prepare the SQL query to update the status to 'active' for the selected event ID
        $query = "UPDATE events SET status = 'active' WHERE id = ?";

        // Prepare the statement
        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bind the event ID parameter to the prepared statement
            mysqli_stmt_bind_param($stmt, "i", $eventId);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Return a success response
                echo json_encode(["status" => "success", "message" => "Event successfully restored."]);
            } else {
                // Return an error response if query execution fails
                echo json_encode(["status" => "error", "message" => "Error executing query: " . mysqli_error($conn)]);
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);
        } else {
            // Return an error response if query preparation fails
            echo json_encode(["status" => "error", "message" => "Error preparing the query: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No event ID provided."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

// Close the database connection
mysqli_close($conn);
?>
