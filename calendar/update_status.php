<?php
include('../db_connection.php');
header('Content-Type: application/json');

try {
    // Decode the incoming JSON request
    $data = json_decode(file_get_contents('php://input'), true);

    // Log the received data for debugging purposes
    error_log("Received data: " . print_r($data, true));

    // Prepare the SQL query to update the status
    $sql = "UPDATE reservations 
            SET status = 'Confirmed' 
            WHERE id = ? 
            AND user_id = ? 
            AND first_name = ? 
            AND last_name = ? 
            AND check_in_date = ? 
            AND status = 'Pending'";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("iisss", 
        $data['reservationId'], 
        $data['userId'], 
        $data['firstName'], 
        $data['lastName'], 
        $data['checkInDate']
    );

    // Execute the statement
    $success = $stmt->execute();
    $rowsAffected = $stmt->affected_rows; // Get the number of affected rows

    // Log the execution results
    error_log("Query executed. Success: $success, Rows affected: $rowsAffected");

    // Return the response as JSON
    echo json_encode([
        'success' => $success,
        'rowsAffected' => $rowsAffected,
        'data' => $data,
        'newTitle' => 'Confirmed' // Include the new title in the response
    ]);

} catch (Exception $e) {
    // Log any error that occurs
    error_log("Error: " . $e->getMessage());
    // Return the error message as JSON
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
