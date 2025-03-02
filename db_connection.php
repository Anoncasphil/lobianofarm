<?php
<<<<<<< HEAD

$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

=======
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP is empty
$database = "u157210740_lobianofarm";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

>>>>>>> e2bfe4a4a8298d63e39d6a3cccced7fa11ddbb28
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

<<<<<<< HEAD
?>
=======
// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// echo "Connected successfully"; // Uncomment for testing
?>
>>>>>>> e2bfe4a4a8298d63e39d6a3cccced7fa11ddbb28
