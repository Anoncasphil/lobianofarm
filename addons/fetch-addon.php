<?php
error_reporting(E_ALL);  // Enable all error reporting
ini_set('display_errors', 1);  // Display errors on the page

require '../db_connection.php'; // Include database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT name, price, description, picture, status FROM addons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Prepare response
        $response = [
            'name' => $row['name'],
            'price' => $row['price'],
            'description' => $row['description'],
            'status' => $row['status'],
            'picture' => $row['picture'] ? '../src/uploads/addons/' . $row['picture'] : null
        ];

        // Check if file exists
        if ($response['picture'] && !file_exists($response['picture'])) {
            $response['picture'] = null;
        }

        echo json_encode($response); // Return JSON response
    } else {
        echo json_encode(['error' => 'No data found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>