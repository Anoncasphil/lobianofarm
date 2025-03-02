<?php
// filepath: /c:/xampp_main/htdocs/lobianofarm/api/resubmit_payment.php

// Start session and include database connection
session_start();
require_once '../db_connection.php';

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/payment_errors.log');

// Create logs directory if it doesn't exist
$log_dir = '../logs/';
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}

// Log the request for debugging
file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "API Request\n", FILE_APPEND);
file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "FILES: " . print_r($_FILES, true) . "\n", FILE_APPEND);

// Ensure proper JSON response header
header('Content-Type: application/json');

// Process the request
try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit;
    }

    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    // Get form data
    $reservation_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : '';
    
    // Log important values
    file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "ID: $reservation_id, Ref: $reference_number\n", FILE_APPEND);
    
    // Validate inputs
    if (empty($reservation_id)) {
        echo json_encode(['success' => false, 'message' => 'Missing reservation ID']);
        exit;
    }
    
    if (empty($reference_number)) {
        echo json_encode(['success' => false, 'message' => 'Missing reference number']);
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
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();
    
    // Process file upload
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['payment_receipt'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        
        if (!in_array($file_ext, $allowed_extensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG and PNG files are allowed']);
            exit;
        }
        
        // Check file size (5MB max)
        if ($file_size > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds the maximum limit (5MB)']);
            exit;
        }
        
        // UPDATED: Use the customerpayment directory
        $upload_dir = '../src/uploads/customerpayment/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
            file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "Created directory: $upload_dir\n", FILE_APPEND);
        }
        
        // Use the same naming pattern as submit_reservation.php
        $unique_file_name = 'payment_receipt_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
        $upload_path = $upload_dir . $unique_file_name;
        
        // Move uploaded file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "File uploaded to: $upload_path\n", FILE_APPEND);
            
            // Update reservation with new payment info
            $update_query = "UPDATE reservations SET 
                            reference_number = ?,
                            payment_receipt = ?,
                            payment_status = 'Pending',
                            updated_at = NOW()
                            WHERE id = ?";
            
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('ssi', $reference_number, $unique_file_name, $reservation_id);
            
            if ($update_stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Payment proof submitted successfully. Our team will verify your payment.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error: ' . $conn->error
                ]);
                file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "DB Error: " . $conn->error . "\n", FILE_APPEND);
            }
            
            $update_stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to upload file. Please check folder permissions.'
            ]);
            file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "Upload failed\n", FILE_APPEND);
        }
    } else {
        $error_code = isset($_FILES['payment_receipt']) ? $_FILES['payment_receipt']['error'] : 'No file uploaded';
        echo json_encode([
            'success' => false,
            'message' => 'No payment receipt uploaded or upload error code: ' . $error_code
        ]);
        file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "File upload error: $error_code\n", FILE_APPEND);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
}
?>