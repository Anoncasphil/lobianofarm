<?php
// Include the database connection file
include('../db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the rate ID from the POST request
    $rateId = $_POST['rate_id'];

    // Prepare the query to update the status to 'active'
    $query = "UPDATE rates SET status = 'active' WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters and execute the query
        $stmt->bind_param("i", $rateId);  // 'i' denotes the integer type
        if ($stmt->execute()) {
            echo "Rate restored successfully.";
        } else {
            echo "Failed to restore rate.";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the query.";
    }

    // Close the database connection
    $conn->close();
}
?>
