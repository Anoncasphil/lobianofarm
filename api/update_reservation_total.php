<?php
// Include database connection
include '../db_connection.php'; // Ensure you have the correct database connection file

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if (isset($_POST['reservation_id']) && isset($_POST['total_price'])) {
    $reservation_id = $_POST['reservation_id'];
    $total_price = $_POST['total_price'];

    // Debugging: Ensure values are set and are valid
    if (!is_numeric($reservation_id) || !is_numeric($total_price)) {
        echo json_encode(["status" => "error", "message" => "Invalid data type for reservation_id or total_price."]);
        exit;
    }

    // Update the reservation's total_price in the reservations table
    $updateQuery = "UPDATE reservations SET total_price = ? WHERE id = ?"; // 'total_price' column
    
    // Prepare the update query
    $stmt = $conn->prepare($updateQuery);
    if ($stmt === false) {
        // If the prepare statement fails, show the error
        echo json_encode(["status" => "error", "message" => "Failed to prepare updateQuery: " . $conn->error]);
        exit;
    }

    // Bind the parameters
    $stmt->bind_param("di", $total_price, $reservation_id); // 'd' for decimal (float), 'i' for integer

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Total price updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating total price: " . $stmt->error]);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Required data not provided."]);
}

// Close the database connection
$conn->close();
?>