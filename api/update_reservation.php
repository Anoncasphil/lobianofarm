<?php
session_start();
include('../db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Get admin name for logging
$admin_query = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($admin_query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_name = $admin['firstname'] . ' ' . $admin['lastname'];
$stmt->close();

// Get data from POST request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['reservation_id']) && isset($data['status'])) {
    $reservation_id = $data['reservation_id'];
    $new_status = $data['status'];
    
    // Get current status for comparison
    $current_query = "SELECT reservation_code, status FROM reservations WHERE reservation_id = ?";
    $stmt = $conn->prepare($current_query);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();
    $old_status = $reservation['status'];
    $reservation_code = $reservation['reservation_code'];
    $stmt->close();
    
    // Only proceed if status is actually changing
    if ($old_status != $new_status) {
        // Update reservation status
        $update_query = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $new_status, $reservation_id);
        
        if ($stmt->execute()) {
            // Log the status change
            logStatusChange($conn, $admin_id, $admin_name, $reservation_id, $reservation_code, $old_status, $new_status);
            
            echo json_encode(['status' => 'success', 'message' => 'Reservation status updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update reservation status']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No change in status']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
}

$conn->close();

/**
 * Log reservation status change to activity logs
 */
function logStatusChange($conn, $admin_id, $admin_name, $reservation_id, $reservation_code, $old_status, $new_status) {
    // Set timezone
    date_default_timezone_set('Asia/Manila');
    
    // Format the log message with HTML - improved for better filtering
    $log_message = "Reservation status changed for reservation #$reservation_code.<br>";
    
    // Apply different styling based on status change
    $old_status_class = getStatusClass($old_status);
    $new_status_class = getStatusClass($new_status);
    
    $log_message .= "- Status changed from '<span class='$old_status_class'>$old_status</span>' to '<span class='$new_status_class'>$new_status</span>'<br>";
    $log_message .= "- Changed by: $admin_name";
    
    // Insert into activity_logs table
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

/**
 * Get appropriate CSS class based on status
 */
function getStatusClass($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'font-medium text-yellow-600';
        case 'confirmed':
            return 'font-medium text-green-600';
        case 'completed':
            return 'font-medium text-blue-600';
        case 'cancelled':
            return 'font-medium text-red-600';
        default:
            return 'font-medium text-gray-600';
    }
}
?>
