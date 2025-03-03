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

// Initialize variables
$name = $_POST['name'];
$original_price = $_POST['temp_price'];
$discount_percentage = isset($_POST['discount_percentage']) ? $_POST['discount_percentage'] : 0;
$has_discount = isset($_POST['has_discount']) ? 1 : 0;
$price = $_POST['price']; // Final price after discount
$description = $_POST['description'];
$hoursofstay = $_POST['hours'];
$checkin_time = $_POST['checkin'];
$checkout_time = $_POST['checkout'];
$rate_type = $_POST['type'];

// Set status to "active" by default
$status = isset($_POST['status']) ? $_POST['status'] : 'active';

// Define the upload directory
$target_dir = "../src/uploads/rates/";

// Create the directory if it doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Get the file extension
$original_file_name = basename($_FILES["picture"]["name"]);
$imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

// Generate a unique file name
$unique_name = time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
$target_file = $target_dir . $unique_name;

// Validate and move the file
$valid_types = array("jpg", "jpeg", "png");
if (in_array($imageFileType, $valid_types)) {
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
        $picture = $unique_name;
    } else {
        die("Error uploading file.");
    }
} else {
    die("Invalid file type.");
}

// Insert data into rates table
$stmt = $conn->prepare("INSERT INTO rates (name, original_price, discount_percentage, has_discount, price, description, hoursofstay, checkin_time, checkout_time, picture, status, rate_type) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdidsdssssss", $name, $original_price, $discount_percentage, $has_discount, $price, $description, $hoursofstay, $checkin_time, $checkout_time, $picture, $status, $rate_type);

if ($stmt->execute()) {
    $rate_id = $conn->insert_id; // Get the ID of the newly inserted rate

    // Log the rate addition
    logRateAddition($admin_id, $admin_name, $rate_id, $name);

    // Ensure no further code execution and properly redirect
    header("Location: rates.php");
    exit; // Ensure that no further code is executed after the redirect
} else {
    die("Error: " . $stmt->error);
}

$stmt->close();
$conn->close();

/**
 * Log the rate addition to the activity_logs table.
 */
function logRateAddition($admin_id, $admin_name, $rate_id, $rate_name) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone
    date_default_timezone_set('Asia/Manila');

    // Log message
    $changes = "Added a new rate: '$rate_name' (ID: $rate_id)";

    // Insert log entry
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $changes);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>
