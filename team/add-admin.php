<?php
// Include the database connection file
include('../db_connection.php');
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo 'unauthorized';
    exit;
}

$logged_admin_id = $_SESSION['admin_id']; // Get logged-in admin ID

// Fetch admin details from the database
$sql_admin = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $logged_admin_id);
$stmt->execute();
$stmt->bind_result($admin_firstname, $admin_lastname);
$stmt->fetch();
$stmt->close();

$admin_name = $admin_firstname . " " . $admin_lastname; // Full name of the admin

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Handle file upload
    if (isset($_FILES['picture'])) {
        $file_name = $_FILES['picture']['name'];
        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_error = $_FILES['picture']['error'];
        $file_size = $_FILES['picture']['size'];

        // Define the upload directory and allowed file types
        $upload_dir = '../src/uploads/team/';
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check if the file type is allowed
        if (in_array($file_extension, $allowed_extensions)) {
            // Check if there are no upload errors
            if ($file_error === 0) {
                // Generate a unique name for the file to avoid overwriting
                $new_file_name = uniqid('img_', true) . '.' . $file_extension;
                $file_path = $upload_dir . $new_file_name;

                // Move the uploaded file to the server
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $profile_picture = $new_file_name;
                } else {
                    echo "Error uploading the file.";
                    exit;
                }
            } else {
                echo "There was an error with the file upload.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }
    } else {
        $profile_picture = null; // If no picture is uploaded, set as null
    }

    // Encrypt the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new admin
    $query = "INSERT INTO admin_tbl (firstname, lastname, email, password, role, profile_picture, status) 
              VALUES (?, ?, ?, ?, ?, ?, 'active')";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $fname, $lname, $email, $hashed_password, $role, $profile_picture);

    // Execute the query
    if ($stmt->execute()) {
        $new_admin_id = $conn->insert_id; // Get ID of newly created admin
        
        // Log admin creation
        logAdminCreation($logged_admin_id, $admin_name, $new_admin_id, "$fname $lname", $role);
        
        echo "Admin added successfully!";
        // You can redirect to another page if necessary
        header("Location: team.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}

/**
 * Log the admin creation action to the database
 */
function logAdminCreation($admin_id, $admin_name, $new_admin_id, $new_admin_name, $role) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Create log message
    $log_message = "Added new admin account: $new_admin_name (ID: $new_admin_id) with role: $role";

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}
?>
