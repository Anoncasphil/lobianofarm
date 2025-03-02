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
        logRateUpdate($admin_id, $admin_name, $id, $name);
        echo "Rate updated successfully";
        header("Location: rates.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

/**
 * Log the rate update to a file
 */
function logRateUpdate($admin_id, $admin_name, $rate_id, $rate_name) {
    $log_dir = "../adminlogs/";
    $log_file = $log_dir . "logs.txt";

    // Create directory if it doesn't exist
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0777, true);
    }

    // Format log entry
    $timestamp = date("Y-m-d H:i:s");
    $log_entry = "[$timestamp] Admin ID: $admin_id | Name: $admin_name | Updated Rate ID: $rate_id | Rate Name: $rate_name\n";

    // Append log entry to the file
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}
?>
    