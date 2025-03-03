<?php
// Include database connection
include('../db_connection.php');

header('Content-Type: application/json');

// Handle form submission and validate data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['disable_date'] ?? ''; // Assuming disable_date is the name of the input

    if (empty($date)) {
        echo json_encode(['success' => false, 'message' => 'Date is required.']);
        exit;
    }

    // Process the date (e.g., save it to the database)
    // Here, we just simulate a successful submission
    $success = true;

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'The disable date has been saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'There was an issue saving the disable date.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
