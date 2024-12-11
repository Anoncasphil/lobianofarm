<?php
error_reporting(E_ALL);  // Enable all error reporting
ini_set('display_errors', 1);  // Display errors on the page

require '../db_connection.php'; // Include database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    $sql = "SELECT name, price, description, hoursofstay, picture FROM rates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Error in SQL prepare: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($row['picture']) {
            $imageData = base64_encode($row['picture']);
            $imageType = 'image/png'; // Adjust based on the image format
            $response = [
                'name' => $row['name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'hoursofstay' => $row['hoursofstay'],
                'picture' => 'data:' . $imageType . ';base64,' . $imageData
            ];
        } else {
            $response = [
                'name' => $row['name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'hoursofstay' => $row['hoursofstay'],
                'picture' => null
            ];
        }

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
