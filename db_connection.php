<?php
$host = "localhost";
$user = "root"; // Default XAMPP MySQL user
$pass = ""; // No password by default
$dbname = "login"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
