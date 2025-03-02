<?php
// filepath: /c:/xampp_main/htdocs/lobianofarm/api/resubmit_payment.php
session_start();
require_once '../db_connection.php';

// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/payment_errors.log');

// Helper function for logging
function debug_log($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Log incoming request
debug_log('Resubmit payment request received');
debug_log('POST data: ' . json_encode($_POST));
debug_log('FILES data: ' . json_encode($_FILES));

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data - FIXED: Accept either 'id' or 'reservation_id' from the form
    $reservation_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_POST['reservation_id']) ? intval($_POST['reservation_id']) : 0);
    $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : '';
    
    debug_log("Processing reservation ID: $reservation_id, Reference: $reference_number");
    
    // Validate inputs
    if (empty($reservation_id)) {
        echo json_encode(['success' => false, 'message' => 'Missing reservation ID']);
        debug_log("Error: Missing reservation ID");
        exit;
    }
    
    if (empty($reference_number)) {
        echo json_encode(['success' => false, 'message' => 'Missing reference number']);
        debug_log("Error: Missing reference number");
        exit;
    }
    
    // Verify that this reservation belongs to the current user
    $check_query = "SELECT id FROM reservations WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('ii', $reservation_id, $_SESSION['user_id']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid reservation or unauthorized access']);
        debug_log("Error: Invalid reservation or unauthorized access");
        exit;
    }
    
    // Rest of the code remains the same...
    // ...
}