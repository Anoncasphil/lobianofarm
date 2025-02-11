<?php
$servername = "localhost"; // Replace with your actual MySQL hostname
$username = "u157210740_lobianofarm"; // Your MySQL username
$password = "Acast_1209"; // Replace with your actual password
$database = "u157210740_lobianofarm"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
