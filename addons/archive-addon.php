<?php
include '../db_connection.php'; // Include your DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $addonId = $_POST['id'];

        // Update the status of the addon to inactive
        $sql = "UPDATE addons SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $addonId);

        if ($stmt->execute()) {
            echo 'success'; // Success response
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
