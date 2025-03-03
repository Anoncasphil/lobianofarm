<?php
// Database connection settings
$servername = "localhost"; // Usually localhost for XAMPP
$username = "root"; // Default username in XAMPP
$password = ""; // Default password is empty for XAMPP
$dbname = "login"; // The name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close connection
// $conn->close();
?>
