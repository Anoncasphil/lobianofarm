<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT 
                name, price, original_price, discount_percentage, has_discount, 
                description, hoursofstay, checkin_time, checkout_time, 
                picture, rate_type, status 
            FROM rates WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(['error' => 'Error in SQL prepare: ' . $conn->error]));
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Calculate final price based on discount
        $finalPrice = $row['has_discount'] ? ($row['price'] * (1 - $row['discount_percentage'] / 100)) : $row['price'];

        // Get picture path
        $picturePath = $row['picture'] ? "../src/uploads/rates/" . $row['picture'] : null;

        // Prepare response
        $response = [
            'name' => $row['name'],
            'price' => $row['price'],
            'original_price' => $row['original_price'],
            'discount_percentage' => $row['discount_percentage'],
            'has_discount' => $row['has_discount'], // 1 or 0
            'final_price' => number_format($finalPrice, 2), // Final price
            'description' => $row['description'],
            'hoursofstay' => $row['hoursofstay'],
            'checkin_time' => $row['checkin_time'],
            'checkout_time' => $row['checkout_time'],
            'rate_type' => $row['rate_type'],
            'status' => $row['status'],
            'picture' => $picturePath
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'No data found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
