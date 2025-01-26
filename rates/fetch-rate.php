<?php
error_reporting(E_ALL);  // Enable all error reporting
ini_set('display_errors', 1);  // Display errors on the page

require '../db_connection.php'; // Include database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    $sql = "SELECT name, price, description, hoursofstay, checkin_time, checkout_time, picture FROM rates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Error in SQL prepare: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Get the picture file path from the database
        $picturePath = $row['picture'] ? "../src/uploads/rates/" . $row['picture'] : null;

        // Prepare the response
        $response = [
            'name' => $row['name'],
            'price' => $row['price'],
            'description' => $row['description'],
            'hoursofstay' => $row['hoursofstay'],
            'checkin_time' => $row['checkin_time'],
            'checkout_time' => $row['checkout_time'],
            'picture' => $picturePath
        ];

        echo json_encode($response); // Return JSON
    } else {
        echo json_encode(['error' => 'No data found']);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
