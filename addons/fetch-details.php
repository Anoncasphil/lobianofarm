<?php
error_reporting(E_ALL);  // Enable all error reporting
ini_set('display_errors', 1);  // Display errors on the page

require '../db_connection.php'; // Include the database connection

// Check if 'id' is set and it's a valid number
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Prepare the SQL query to fetch data based on 'id'
    $sql = "SELECT name, price, description, picture, status FROM addons WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Check for errors in preparing the SQL statement
    if (!$stmt) {
        die('Error in SQL prepare: ' . $conn->error);
    }

    // Bind parameters to the query
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if a picture exists and convert it to base64
        if ($row['picture']) {
            $imageData = base64_encode($row['picture']); // Convert image binary to base64
            $imageType = 'image/png'; // Adjust this based on your actual image format, e.g., 'image/jpeg'

            $response = [
                'name' => $row['name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'status' => $row['status'],
                'picture' => 'data:' . $imageType . ';base64,' . $imageData // Base64-encoded image
            ];
        } else {
            // If there's no image, set the picture to null
            $response = [
                'name' => $row['name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'status' => $row['status'],
                'picture' => null // No picture available
            ];
        }

        // Return the data as JSON
        echo json_encode($response);
    } else {
        // If no data is found for the given ID
        echo json_encode(['error' => 'No data found']);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the case where the ID is not provided or invalid
    echo json_encode(['error' => 'Invalid request']);
}
?>
