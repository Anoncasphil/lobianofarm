<?php
// Start the session
session_start();

// Include the database connection file
include '../db_connection.php';

// Set the fixed user_id (in this case, 23)
$user_id = 23;

// Query to fetch user data
$query = "SELECT first_name, last_name, email, contact_no FROM user_tbl WHERE user_id = $user_id";
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
