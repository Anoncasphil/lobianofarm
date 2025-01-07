<?php
// Include the database connection file
include('../db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the addon ID from the POST request
    $addonId = $_POST['addon_id'];

    // Prepare the query to update the status to 'active'
    $query = "UPDATE addons SET status = 'active' WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters and execute the query
        $stmt->bind_param("i", $addonId);  // 'i' denotes the integer type
        if ($stmt->execute()) {
            echo "Addon restored successfully.";
        } else {
            echo "Failed to restore addon.";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the query.";
    }

    // Close the database connection
    $conn->close();
}
?>
