<?php
// Include database connection
include('../db_connection.php');

// Set header to allow access from other domains (CORS)
header('Content-Type: application/json');

try {
    // Query to fetch only active rates
    $query = "
        SELECT r.id  , r.name, r.price, r.rate_type
        FROM rates r
        WHERE r.status = 'active'
    ";
    $result = $conn->query($query);

    // Check if we have any results
    if ($result->num_rows > 0) {
        // Initialize an empty array to hold the rates
        $rates = [];

        // Fetch each row and store it in the rates array
        while ($row = $result->fetch_assoc()) {
            $rates[] = [
                'id' => (string) $row['id'], // Ensure ID is a string
                'name' => $row['name'],
                'price' => $row['price'],
                'rate_type' => $row['rate_type']
            ];
        }

        // Return the rates as a JSON response
        echo json_encode(['status' => 'success', 'rates' => $rates]);

    } else {
        // Return an error if no active rates are found
        echo json_encode(['status' => 'error', 'message' => 'No active rates found']);
    }

} catch (Exception $e) {
    // Return an error if there's a problem with the database query
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>
