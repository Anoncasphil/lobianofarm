<?php
// Include database connection
include '../db_connection.php';

if (isset($_GET['userid'])) {
    $rateId = $_GET['userid'];

    // Prepare SQL query to fetch rate details
    $sql = "SELECT checkin_time, checkout_time, hoursofstay FROM rates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rate = $result->fetch_assoc();
        echo json_encode($rate); // Return rate details as JSON
    } else {
        echo json_encode(["error" => "Rate not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Rate ID is required"]);
}

// Close the connection
$conn->close();
?>
