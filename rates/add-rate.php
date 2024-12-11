<?php
include '../db_connection.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $name = $_POST['name'];
    $price = $_POST['price'];
    $hours = $_POST['hours'];
    $description = $_POST['description'];

    // Handle the file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];

        // Ensure the file is a valid image
        $imageInfo = getimagesize($fileTmpPath);
        if ($imageInfo !== false) {
            $fileContent = file_get_contents($fileTmpPath);  // Get the image content in binary form

            // Prepare the SQL query to insert data into the rates table
            $sql = "INSERT INTO rates (name, price, description, hoursofstay, picture) 
                    VALUES (?, ?, ?, ?, ?)";

            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssis", $name, $price, $description, $hours, $fileContent);

            // Execute the query
            if ($stmt->execute()) {
                header('Location: rates.php');  // Redirect with success status
                exit();
            } else {
                echo "Error adding rate: " . $stmt->error;  // Display error if query fails
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Invalid image file.";  // Handle invalid image type
        }
    } else {
        echo "Error: File not uploaded.";  // Handle file upload error
    }
}

$conn->close();  // Close the database connection
?>
