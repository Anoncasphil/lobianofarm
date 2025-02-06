<?php
// Include database connection
require_once '../db_connection.php';

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
$sql = "INSERT INTO rates (name, price, description, hoursofstay, checkin_time, checkout_time, picture, status, rate_type)
        VALUES ('$name', '$price', '$description', '$hoursofstay', '$checkin_time', '$checkout_time', '$picture', '$status', '$rate_type')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location: rates.php");  // Redirect to rates page after success
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
