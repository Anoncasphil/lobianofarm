<?php
$host = "localhost"; 
$dbname = "u157210740_lobianofarm";
$username = "u157210740_lobianofarm";
$password = "Acast_1209"; // Change this immediately for security

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
