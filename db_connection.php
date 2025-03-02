<?php
<<<<<<< HEAD
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
=======
$host = "localhost"; 
$dbname = "u157210740_lobianofarm";
$username = "u157210740_lobianofarm";
$password = "Acast_1209"; // Change this immediately for security
>>>>>>> bc1c3ee90714e176e9e452587bda8c4e5634db03

$conn = new mysqli($host, $username, $password, $dbname);

<<<<<<< HEAD
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
=======
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
>>>>>>> bc1c3ee90714e176e9e452587bda8c4e5634db03
