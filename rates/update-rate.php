<?php
// Include database connection
require_once '../db_connection.php';
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Admin not authenticated.");
}

$admin_id = $_SESSION['admin_id']; // Get logged-in admin ID

// Fetch admin details from the database
$sql_admin = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

$admin_name = $firstname . " " . $lastname; // Full name of the admin

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $hoursofstay = $_POST['hoursofstay'];
    $checkin_time = $_POST['checkin'];
    $checkout_time = $_POST['checkout'];
    $rate_type = $_POST['type']; 

    // Get current rate details before updating
    $sql_current = "SELECT name, price, description, hoursofstay, checkin_time, checkout_time, rate_type, picture FROM rates WHERE id = ?";
    $stmt_current = $conn->prepare($sql_current);
    $stmt_current->bind_param("i", $id);
    $stmt_current->execute();
    $result = $stmt_current->get_result();
    $current_rate = $result->fetch_assoc();
    $stmt_current->close();

    // Handle file upload (if any)
    $picture = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "../src/uploads/rates/";

        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $original_file_name = basename($_FILES["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

        // Generate a unique file name
        $unique_name = time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Validate file type
        $valid_types = array("jpg", "jpeg", "png");
        if (in_array($imageFileType, $valid_types)) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $unique_name;
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            echo "Only JPG, JPEG, PNG files are allowed.";
            exit;
        }
    }

    // Prepare SQL update query
    if ($picture) {
        $sql = "UPDATE rates SET name = ?, price = ?, description = ?, hoursofstay = ?, checkin_time = ?, checkout_time = ?, picture = ?, rate_type = ? WHERE id = ?";
    } else {
        $sql = "UPDATE rates SET name = ?, price = ?, description = ?, hoursofstay = ?, checkin_time = ?, checkout_time = ?, rate_type = ? WHERE id = ?";
    }

    // Execute update query
    $stmt = $conn->prepare($sql);
    if ($picture) {
        $stmt->bind_param("sdssssssi", $name, $price, $description, $hoursofstay, $checkin_time, $checkout_time, $picture, $rate_type, $id);
    } else {
        $stmt->bind_param("sdsssssi", $name, $price, $description, $hoursofstay, $checkin_time, $checkout_time, $rate_type, $id);
    }

    if ($stmt->execute()) {
        logRateUpdate($admin_id, $admin_name, $id, $current_rate, $name, $price, $description, $hoursofstay, $checkin_time, $checkout_time, $rate_type, $picture);
        echo "Rate updated successfully";
        header("Location: rates.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

/**
 * Log the rate update to the database with human-readable format showing before and after values.
 */
function logRateUpdate($admin_id, $admin_name, $rate_id, $current_rate, $new_name, $new_price, $new_description, $new_hoursofstay, $new_checkin_time, $new_checkout_time, $new_rate_type, $new_picture) {
    include('../db_connection.php'); // Ensure database connection

    if (!$conn) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Initialize log message - use <br> for HTML line breaks instead of \n
    $log_message = "Updated Rate ID: $rate_id.<br>";

    // Check and log changes with HTML breaks
    if (strcasecmp($current_rate['name'], $new_name) !== 0) {
        $log_message .= "- Rate Name: '{$current_rate['name']}' → '$new_name'.<br>";
    }
    
    if ($current_rate['price'] != $new_price) {
        $log_message .= "- Price: '{$current_rate['price']}' → '$new_price'.<br>";
    }

    if (strcasecmp($current_rate['description'], $new_description) !== 0) {
        $log_message .= "- Description was updated.<br>";
    }

    if ($current_rate['hoursofstay'] != $new_hoursofstay) {
        $log_message .= "- Hours of Stay: '{$current_rate['hoursofstay']}' → '$new_hoursofstay'.<br>";
    }

    if (normalizeTime($current_rate['checkin_time']) !== normalizeTime($new_checkin_time)) {
        $log_message .= "- Check-in Time: '{$current_rate['checkin_time']}' → '$new_checkin_time'.<br>";
    }

    if (normalizeTime($current_rate['checkout_time']) !== normalizeTime($new_checkout_time)) {
        $log_message .= "- Check-out Time: '{$current_rate['checkout_time']}' → '$new_checkout_time'.<br>";
    }

    if (strcasecmp($current_rate['rate_type'], $new_rate_type) !== 0) {
        $log_message .= "- Rate Type: '{$current_rate['rate_type']}' → '$new_rate_type'.<br>";
    }

    if ($new_picture !== null) {
        $log_message .= "- Picture was updated.<br>";
    }

    // Only log if changes were made
    if (trim(str_replace('<br>', '', $log_message)) !== "Updated Rate ID: $rate_id.") {
        // Insert log entry into the database
        $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL Prepare Error: " . $conn->error);
        }

        $stmt->bind_param("is", $admin_id, $log_message);

        if (!$stmt->execute()) {
            die("SQL Execute Error: " . $stmt->error);
        }

        $stmt->close();
    }

    $conn->close();
}

/**
 * Normalize time format for comparison (e.g., "07:00:00" and "7:00" should be considered the same).
 */
function normalizeTime($timeStr) {
    if (empty($timeStr)) {
        return '';
    }
    $timeParts = preg_split('/[:\s]/', $timeStr);
    $hours = isset($timeParts[0]) ? (int)$timeParts[0] : 0;
    $minutes = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
    return sprintf("%d:%02d", $hours, $minutes);
}
