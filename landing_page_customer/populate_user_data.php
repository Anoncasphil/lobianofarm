<?php
// Start the session
session_start();

// Include the database connection file
include '../db_connection.php';

// Check if the user_id is stored in the session
if (!isset($_SESSION['user_id'])) {
    // If user_id is not set in the session, return an error
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in.'
    ]);
    exit;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Query to fetch user data
$query = "SELECT first_name, last_name, email, phone_number FROM user_tbl WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

// Check if query was successful
if (!$result) {
    // Return the error in JSON format
    echo json_encode([
        'success' => false,
        'message' => 'Query Failed: ' . mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Return the user data as JSON
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
} else {
    // User not found
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
}

// Close the database connection
mysqli_close($conn);
?>
