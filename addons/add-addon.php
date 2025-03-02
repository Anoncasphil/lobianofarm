<?php
include '../db_connection.php';  // Include database connection
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle the file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];  // Allowed image file extensions

        // Ensure the file is a valid image
        if (in_array($fileExtension, $validExtensions)) {
            // Generate a unique name for the image
            $uniqueFileName = uniqid('addon_', true) . '.' . $fileExtension;

            // Define the upload path
            $uploadPath = '../src/uploads/addons/' . $uniqueFileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                // Prepare the SQL query to insert data into the addons table
                $sql = "INSERT INTO addons (name, price, description, picture) 
                        VALUES (?, ?, ?, ?)";

                // Prepare and execute the statement
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $name, $price, $description, $uniqueFileName);

                // Execute the query
                if ($stmt->execute()) {
                    $addon_id = $conn->insert_id; // Get the ID of the newly inserted addon
                    
                    // Log the addon addition
                    logAddonAddition($admin_id, $admin_name, $addon_id, $name);
                    
                    // Redirect to addons.php without success parameter
                    header('Location: addons.php');  
                    exit();
                } else {
                    echo "Error adding add-on: " . $stmt->error;  // Display error if query fails
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error moving the uploaded file.";  // Handle file move error
            }
        } else {
            echo "Invalid image file type. Only JPG, JPEG, PNG, and GIF are allowed.";  // Handle invalid file extension
        }
    } else {
        echo "Error: File not uploaded.";  // Handle file upload error
    }
}

/**
 * Log the addon addition to the activity_logs table.
 */
function logAddonAddition($admin_id, $admin_name, $addon_id, $addon_name) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone
    date_default_timezone_set('Asia/Manila');

    // Log message
    $changes = "Added a new addon: '$addon_name' (ID: $addon_id)";

    // Insert log entry
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $changes);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

$conn->close();  // Close the database connection
?>
