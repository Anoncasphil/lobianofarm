<?php
// get_image.php
include("../db_connection.php");

$imageId = $_GET['image_id']; // Get the image ID from the query string

// Fetch the image from the database based on its ID
$sql = "SELECT picture FROM rates WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $imageId);
$stmt->execute();
$stmt->bind_result($imageData);
$stmt->fetch();

// Set the appropriate headers and output the image data
header("Content-Type: image/jpeg"); // Change the content type based on your image format
echo $imageData;
$stmt->close();
$conn->close();
?>
