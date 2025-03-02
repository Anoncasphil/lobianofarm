<?php

$host = "localhost"; // XAMPP default host
$username = "root"; // Default XAMPP MySQL user
$password = ""; // Default password is empty in XAMPP
$database = "login"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Uncomment this line for debugging
// echo "Connected successfully";

?>