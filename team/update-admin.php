<?php
// Include database connection (replace with your actual database connection details)
include('../db_connection.php'); 

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $adminId = $_POST['adminId'];
    $firstname = $_POST['updatefname'];
    $lastname = $_POST['updatelname'];
    $email = $_POST['updateemail'];
    $role = $_POST['updaterole'];
    $password = $_POST['updatepassword'];
    
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
            echo "Admin details updated successfully!";
            header("Location: team.php");
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
?>
