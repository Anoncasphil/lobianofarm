<?php
// Include database connection
include('../db_connection.php');

// Set header to allow access from other domains (CORS)
header('Content-Type: application/json');

try {
    // Query to fetch addons
    $query = "SELECT id, name, price FROM addons WHERE status = 'active'";
    $result = $conn->query($query);

    // Check if we have any results
    if ($result->num_rows > 0) {
        // Initialize an empty array to hold the addons
        $addons = [];

        // Fetch each row and store it in the addons array
        while ($row = $result->fetch_assoc()) {
            $addons[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price']
            ];
        }

        // Return the addons as a JSON response
        echo json_encode(['status' => 'success', 'addons' => $addons]);

    } else {
        // Return an error if no addons are found
        echo json_encode(['status' => 'error', 'message' => 'No addons found']);
    }

} catch (Exception $e) {
    // Return an error if there's a problem with the database query
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>
