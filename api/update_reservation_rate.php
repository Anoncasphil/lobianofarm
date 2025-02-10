<?php
// Include database connection
include '../db_connection.php';

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if (isset($_POST['reservation_id']) && isset($_POST['new_rate_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_rate_id = $_POST['new_rate_id'];

    // Ensure values are numeric or perform necessary validation
    if (!is_numeric($reservation_id) || !is_numeric($new_rate_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid data types."]);
        exit;
    }

    // Proceed with the database query
    $updateQuery = "UPDATE reservations SET rate_id = ? WHERE id = ?";

    // Prepare the update query
    $stmt = $conn->prepare($updateQuery);
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare update query: " . $conn->error]);
        exit;
    }

    // Bind the parameters
    $stmt->bind_param("ii", $new_rate_id, $reservation_id); // 'ii' for two integers

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Rate updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating rate: " . $stmt->error]);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Required data not provided."]);
}

// Close the database connection
$conn->close();
?>
