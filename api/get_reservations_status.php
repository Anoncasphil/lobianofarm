<?php
// Database connection
$servername = "localhost"; // Replace with your actual MySQL hostname
$username = "u157210740_lobianofarm"; // Your MySQL username
$password = "Acast_1209"; // Replace with your actual password
$database = "u157210740_lobianofarm"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' parameter is provided
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Reservation ID not provided']);
    exit;
}

$reservationId = $_GET['id'];

// Prepare the SQL statement to fetch the reservation status
$query = "SELECT status FROM reservations WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn->error]);
    exit;
}

// Bind parameters to the SQL query
$stmt->bind_param('i', $reservationId);

// Execute the query
if ($stmt->execute()) {
    // Fetch the result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Reservation found, fetch the status
        $row = $result->fetch_assoc();
        echo json_encode(['status' => $row['status']]);
    } else {
        echo json_encode(['error' => 'Reservation not found']);
    }
} else {
    echo json_encode(['error' => 'Query execution failed']);
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
