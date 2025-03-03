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

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $reservation_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : '';

    file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "ID: $reservation_id, Ref: $reference_number\n", FILE_APPEND);

    if (empty($reservation_id) || empty($reference_number)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Verify reservation ownership and fetch reservation_code
    $check_query = "SELECT id, reservation_code FROM reservations WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('ii', $reservation_id, $_SESSION['user_id']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid reservation or unauthorized access']);
        exit;
    }

    $reservation_data = $check_result->fetch_assoc();
    $reservation_code = $reservation_data['reservation_code']; // Get reservation_code
    $check_stmt->close();

    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['payment_receipt'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_ext, $allowed_extensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed']);
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds the maximum limit (5MB)']);
            exit;
        }

        $upload_dir = '../src/uploads/customerpayment/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $unique_file_name = 'payment_receipt_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
        $upload_path = $upload_dir . $unique_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $update_query = "UPDATE reservations SET 
                            reference_number = ?, 
                            payment_receipt = ?, 
                            payment_status = 'Pending', 
                            updated_at = NOW() 
                            WHERE id = ?";

            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('ssi', $reference_number, $unique_file_name, $reservation_id);

            if ($update_stmt->execute()) {
                // Insert admin notification (ensure user_id is valid)
                $admin_message = "A new payment proof has been submitted for reservation code: $reservation_code. Please verify.";
                
                // Using NULL for user_id is invalid based on your table structure. Set it to an admin user ID or make it NULL allowed.
                $admin_user_id = 1;  // Set this to an admin user_id if applicable.
                
                $admin_notification_sql = "INSERT INTO notifications (user_id, reservation_id, title, message, type, status) 
                                           VALUES (?, ?, 'Payment Verification Needed', ?, 'payment', 'unread')";
                $admin_stmt = $conn->prepare($admin_notification_sql);
                $admin_stmt->bind_param('iis', $admin_user_id, $reservation_id, $admin_message);

                if ($admin_stmt->execute()) {
                    file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "Notification inserted successfully for reservation code: $reservation_code\n", FILE_APPEND);
                } else {
                    file_put_contents($log_dir . 'debug.log', date('[Y-m-d H:i:s] ') . "Failed to insert notification: " . $conn->error . "\n", FILE_APPEND);
                }

                $admin_stmt->close();

                echo json_encode([
                    'success' => true,
                    'message' => 'Payment proof submitted successfully. Our team will verify your payment.'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
            }

            $update_stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file. Please check folder permissions.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No payment receipt uploaded.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
