<?php
session_start(); // Start the session to manage user login status
include_once('db_connection.php'); // Include the database connection

$error_message = ''; // Initialize error message variable

// Check if the form is submitted
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch the user details by email
    $sql = "SELECT * FROM admin_tbl WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if email exists in the database
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $admin['password'])) {
            // Successful login
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['firstname'] = $admin['firstname'];
            $_SESSION['lastname'] = $admin['lastname'];
            $_SESSION['role'] = $admin['role'];

            // Redirect to the admin dashboard or team page after login
            header("Location: index.php"); // Redirect to index.php
            exit(); // Ensure the script stops here after redirection
        } else {
            // Invalid password
            $error_message = "Invalid password!";
        }
    } else {
        // Invalid email
        $error_message = "No user found with this email!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
