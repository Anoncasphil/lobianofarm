<?php
include '../db_connection.php'; // Make sure to include your DB connection here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $rateId = $_POST['id'];
        
        // Update the status of the rate to inactive
        $sql = "UPDATE rates SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $rateId);

        if ($stmt->execute()) {
            echo 'success'; // Success response
        } else {
            echo 'error'; // Error response
        }

        $stmt->close();
    }
}

$conn->close();
?>
