<?php
// Include the database connection file
include('../db_connection.php'); // Ensure the file path and extension are correct

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define the target directory
    $targetDir = "../src/uploads/album/";
    
    // Create the directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Get the file details
    $fileName = basename($_FILES['picture']['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png']; // Allowed file types

    // Generate a unique file name to avoid overwriting
    $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . "_" . time() . "." . $fileType;
    $targetFilePath = $targetDir . $uniqueFileName;

    // Sanitize inputs
    $pictureName = htmlspecialchars($_POST['picture_name']);

    // Validate file type
    if (in_array($fileType, $allowedTypes)) {
        // Check for upload errors
        if ($_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath)) {
                // Save details to the database
                $query = "INSERT INTO pictures (image_name, image_path) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $pictureName, $uniqueFileName);

                if ($stmt->execute()) {
                    header("Location: album.php?status=success");
                } else {
                    echo "Error: Failed to save file details to the database. " . $conn->error;
                }
                $stmt->close();
            } else {
                echo "Error: There was an issue moving the uploaded file.";
            }
        } else {
            echo "Error: File upload failed with error code " . $_FILES['picture']['error'];
        }
    } else {
        echo "Error: Only JPG, JPEG, and PNG file types are allowed.";
    }
} else {
    echo "Error: Invalid request.";
}

// Close the database connection
$conn->close();
?>
