<?php
include '../db_connection.php'; // Include database connection
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo 'unauthorized';
    exit;
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $id = $_POST['id']; 
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = 'active'; // Status is automatically set to 'active'

    // Get current addon details for comparison and logging
    $sql_current = "SELECT name, price, description, picture FROM addons WHERE id = ?";
    $stmt_current = $conn->prepare($sql_current);
    $stmt_current->bind_param('i', $id);
    $stmt_current->execute();
    $stmt_current->bind_result($current_name, $current_price, $current_description, $current_picture);
    $stmt_current->fetch();
    $stmt_current->close();

    // Track changes for logging
    $changes = array();
    if ($current_name != $name) {
        $changes[] = "Name changed from '$current_name' to '$name'";
    }
    if ($current_price != $price) {
        $changes[] = "Price changed from ₱" . number_format($current_price, 2) . " to ₱" . number_format($price, 2);
    }
    if ($current_description != $description) {
        $changes[] = "Description was updated";
    }

    // Initialize update arrays
    $updateFields = [];
    $updateValues = [];

    // Prepare values for updating
    foreach (['name' => $name, 'price' => $price, 'description' => $description, 'status' => $status] as $field => $value) {
        if (!empty($value)) {
            $updateFields[] = "$field = ?";
            $updateValues[] = $value;
        }
    }

    // Handle image upload
    $picture_updated = false;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $uniqueFileName = 'addon_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $destPath = '../src/uploads/addons/' . $uniqueFileName;

        // Check if there's an existing picture to delete
        if ($current_picture) {
            $existingFilePath = '../src/uploads/addons/' . $current_picture;
            if (file_exists($existingFilePath)) {
                unlink($existingFilePath); // Delete the old image file
            }
        }

        // Validate and upload the new image
        if (getimagesize($fileTmpPath)) {
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $updateFields[] = "picture = ?";
                $updateValues[] = $uniqueFileName; // Store only the file name in the database
                $changes[] = "Image was updated";
                $picture_updated = true;
            } else {
                exit("Failed to upload image.");
            }
        } else {
            exit("Invalid image file.");
        }
    }

    // Update record if there are fields to update
    if ($updateFields) {
        $sql = "UPDATE addons SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $updateValues[] = $id; // Bind the ID for WHERE clause

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $types = str_repeat('s', count($updateValues) - 1) . 'i'; // 's' for string, 'i' for integer (ID)
        $stmt->bind_param($types, ...$updateValues);

        if ($stmt->execute()) {
            // Log the update action if there were changes
            if (count($changes) > 0) {
                logAddonUpdate($admin_id, $admin_name, $id, $name, $changes);
            }
            header('Location: addons.php'); // Redirect to the addons page
            exit();
        } else {
            exit("Error updating addon: " . $stmt->error);
        }
        $stmt->close();
    } else {
        exit("No fields to update.");
    }
}

/**
 * Log the addon update action to the database
 */
function logAddonUpdate($admin_id, $admin_name, $addonId, $addon_name, $changes) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Initialize log message with HTML line breaks
    $log_message = "Updated the addon: $addon_name (ID: $addonId).<br>";
    
    // Add each change with a line break
    foreach($changes as $index => $change) {
        $log_message .= "- " . $change . ".<br>";
    }

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

$conn->close(); // Close the database connection
?>
