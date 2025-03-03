<?php
// Include database connection
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $adminId = $_POST['adminId'];
    $firstname = $_POST['updatefname'];
    $lastname = $_POST['updatelname'];
    $email = $_POST['updateemail'];
    $role = $_POST['updaterole'];
    $password = $_POST['updatepassword'];
    
    // Get current admin details for comparison and logging
    $sql_current = "SELECT firstname, lastname, email, role, profile_picture FROM admin_tbl WHERE admin_id = ?";
    $stmt_current = $conn->prepare($sql_current);
    $stmt_current->bind_param('i', $adminId);
    $stmt_current->execute();
    $stmt_current->bind_result($current_firstname, $current_lastname, $current_email, $current_role, $current_picture);
    $stmt_current->fetch();
    $stmt_current->close();
    
    // Track changes for logging
    $changes = array();
    if ($current_firstname != $firstname && !empty($firstname)) {
        $changes[] = "First name changed from '$current_firstname' to '$firstname'";
    }
    if ($current_lastname != $lastname && !empty($lastname)) {
        $changes[] = "Last name changed from '$current_lastname' to '$lastname'";
    }
    if ($current_email != $email && !empty($email)) {
        $changes[] = "Email changed from '$current_email' to '$email'";
    }
    if ($current_role != $role && !empty($role)) {
        $changes[] = "Role changed from '$current_role' to '$role'";
    }
    if (!empty($password)) {
        $changes[] = "Password was updated";
    }
    
    // Handle password hashing if provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    }

    // Handle profile picture upload
    $profilePicture = $_FILES['updatePicture']['name'];
    $profilePictureTmp = $_FILES['updatePicture']['tmp_name'];
    $uploadDir = '../src/uploads/team/'; // Change this to your desired upload directory
    $profilePicturePath = '';

    // Check if a new profile picture is uploaded
    if ($profilePicture) {
        // Generate a unique filename to prevent overwriting
        $profilePicturePath = $uploadDir . time() . '_' . $profilePicture;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($profilePictureTmp, $profilePicturePath)) {
            // Success, profile picture uploaded
            $changes[] = "Profile picture was updated";
        } else {
            echo "Failed to upload profile picture.";
        }
    }

    // Prepare SQL query for updating admin details
    $sql = "UPDATE admin_tbl SET";
    $params = [];
    $types = "";

    // Check and append fields to the SQL query if they have changed
    if (!empty($firstname)) {
        $sql .= " firstname = ?,"; 
        $params[] = $firstname;
        $types .= "s"; // Add string type
    }
    if (!empty($lastname)) {
        $sql .= " lastname = ?,"; 
        $params[] = $lastname;
        $types .= "s"; // Add string type
    }
    if (!empty($email)) {
        $sql .= " email = ?,"; 
        $params[] = $email;
        $types .= "s"; // Add string type
    }
    if (!empty($role)) {
        $sql .= " role = ?,"; 
        $params[] = $role;
        $types .= "s"; // Add string type
    }
    if (!empty($password)) {
        $sql .= " password = ?,"; 
        $params[] = $hashedPassword;
        $types .= "s"; // Add string type
    }
    if ($profilePicture) {
        $sql .= " profile_picture = ?,"; 
        $params[] = $profilePicturePath;
        $types .= "s"; // Add string type
    }

    // Remove the trailing comma from the SQL query
    $sql = rtrim($sql, ",");

    // Add condition for the WHERE clause
    $sql .= " WHERE admin_id = ?";

    // Append the admin_id to the parameters
    $params[] = $adminId;
    $types .= "i"; // Add integer type for admin_id

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        if ($stmt->execute()) {
            // Log the update if there were changes
            if (count($changes) > 0) {
                $admin_full_name = "$current_firstname $current_lastname";
                logAdminUpdate($logged_admin_id, $admin_name, $adminId, $admin_full_name, $changes);
            }
            
            // Redirect to team.php after successful update
            header("Location: team.php");
            exit;
        } else {
            echo "Error updating admin details: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing SQL statement.";
    }

    // Close connection
    $conn->close();
} else {
    echo "Invalid request method.";
}

/**
 * Log the admin update action to the database
 */
function logAdminUpdate($admin_id, $admin_name, $updated_admin_id, $updated_admin_name, $changes) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone to ensure correct time
    date_default_timezone_set('Asia/Manila');

    // Initialize log message with HTML line breaks
    $log_message = "Updated admin account: $updated_admin_name (ID: $updated_admin_id).<br>";
    
    // Add each change with a line break
    foreach($changes as $change) {
        $log_message .= "- " . $change . ".<br>";
    }

    // Insert log entry into the database
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}
?>
