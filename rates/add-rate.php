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
$price = $_POST['price'];
$description = $_POST['description'];
$hoursofstay = $_POST['hours'];
$checkin_time = $_POST['checkin'];
$checkout_time = $_POST['checkout'];
$rate_type = $_POST['type'];  // Capture the new field "type" (mapped to "rate_type" in the database)

// Set the status to "active" by default
$status = isset($_POST['status']) ? $_POST['status'] : 'active'; // Check if status is set, otherwise default to "active"

// Define the upload directory
$target_dir = "../src/uploads/rates/";

// Check if the directory exists, if not, create it
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the directory with write permissions
}

// Get the file extension
$original_file_name = basename($_FILES["picture"]["name"]);
$imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

// Generate a unique file name using timestamp and random number
$unique_name = time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
$target_file = $target_dir . $unique_name;  // Only the file name and directory, no full path

// Check if the file is a valid image
$valid_types = array("jpg", "jpeg", "png");
if (in_array($imageFileType, $valid_types)) {
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
        $picture = $unique_name;  // Only store the file name in the database
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }
} else {
    echo "Sorry, only JPG, JPEG, PNG files are allowed.";
    exit;
}

// Insert data into the database, including the new "rate_type" field
$stmt = $conn->prepare("INSERT INTO rates (name, price, description, hoursofstay, checkin_time, checkout_time, picture, status, rate_type) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdsssssss", $name, $price, $description, $hoursofstay, $checkin_time, $checkout_time, $picture, $status, $rate_type);

if ($stmt->execute()) {
    $rate_id = $conn->insert_id; // Get the ID of the newly inserted rate
    
    // Log the rate addition
    logRateAddition($admin_id, $admin_name, $rate_id, $name, $price, $hoursofstay, $checkin_time, $checkout_time, $rate_type);
    
    echo "New record created successfully";
    header("Location: rates.php");  // Redirect to rates page after success
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

/**
 * Log the rate addition to the database
 */
function logRateAddition($admin_id, $admin_name, $rate_id, $name, $price, $hoursofstay, $checkin_time, $checkout_time, $rate_type) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Create a simplified change log with just the addition information
    $changes = array(
        'Addition' => "Added a new Rate: $name."
    );

    // Convert to JSON for storage
    $changes_json = json_encode($changes);

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, rate_id, timestamp, changes) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $admin_id, $rate_id, $changes_json);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>
