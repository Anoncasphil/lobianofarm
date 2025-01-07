<?php
include '../db_connection.php'; // Include your DB connection
ini_set('display_errors', 1); // Enable error display
error_reporting(E_ALL); // Show all errors

// Debugging statement before header
var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $eventId = $_POST['id'];

        // Update the status of the event to inactive
        $sql = "UPDATE events SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $eventId);

        if ($stmt->execute()) {
            // Redirect to events.php after success
            header("Location: events.php");
            exit(); // Ensure the script stops executing after the redirect
        } else {
            echo 'error'; // Error response
        }

        $stmt->close();
    } else {
        echo 'error'; // Error if ID is not provided
    }
}

$conn->close();
?>
