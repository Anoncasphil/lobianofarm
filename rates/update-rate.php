<?php
include '../db_connection.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $id = $_POST['id'];  // Get the rate ID
    $name = $_POST['name'];
    $price = $_POST['price'];
    $hours = $_POST['hoursofstay'];
    $description = $_POST['description'];

    // Initialize variables for updating fields
    $updateFields = [];
    $updateValues = [];

    // Add fields to update if they are provided
    if (!empty($name)) {
        $updateFields[] = "name = ?";
        $updateValues[] = $name;
    }

    if (!empty($price)) {
        $updateFields[] = "price = ?";
        $updateValues[] = $price;
    }

    if (!empty($description)) {
        $updateFields[] = "description = ?";
        $updateValues[] = $description;
    }

    if (!empty($hours)) {
        $updateFields[] = "hoursofstay = ?";
        $updateValues[] = $hours;
    }

    // Handle the file upload if a new picture is provided
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];

        // Ensure the file is a valid image
        $imageInfo = getimagesize($fileTmpPath);
        if ($imageInfo !== false) {
            $fileContent = file_get_contents($fileTmpPath);  // Get the image content in binary form
            $updateFields[] = "picture = ?";
            $updateValues[] = $fileContent;
        } else {
            echo "Invalid image file.";  // Handle invalid image type
            exit();
        }
    }

    // Only proceed if there are fields to update
    if (count($updateFields) > 0) {
        // Prepare the SQL query with dynamic updates
        $sql = "UPDATE rates SET " . implode(", ", $updateFields) . " WHERE id = ?";

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters dynamically
        $updateValues[] = $id; // Always bind the ID last for WHERE clause
        $types = str_repeat('s', count($updateValues) - 1) . 'i'; // Assuming all fields except ID are strings, adjust types accordingly
        $stmt->bind_param($types, ...$updateValues);

        // Execute the query
        if ($stmt->execute()) {
            header('Location: rates.php');  // Redirect to the rates page
            exit();
        } else {
            echo "Error updating rate: " . $stmt->error;  // Display error if query fails
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "No fields to update.";  // Handle case where no fields are selected for update
    }
}

$conn->close();  // Close the database connection
?>
