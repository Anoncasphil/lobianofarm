<?php
include '../db_connection.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle the file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];  // Allowed image file extensions

        // Ensure the file is a valid image
        if (in_array($fileExtension, $validExtensions)) {
            // Generate a unique name for the image
            $uniqueFileName = uniqid('addon_', true) . '.' . $fileExtension;

            // Define the upload path
            $uploadPath = '../src/uploads/addons/' . $uniqueFileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                // Prepare the SQL query to insert data into the addons table
                $sql = "INSERT INTO addons (name, price, description, picture) 
                        VALUES (?, ?, ?, ?)";

                // Prepare and execute the statement
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $name, $price, $description, $uniqueFileName);

                // Execute the query
                if ($stmt->execute()) {
                    // Redirect to addons.php without success parameter
                    header('Location: addons.php');  
                    exit();
                } else {
                    echo "Error adding add-on: " . $stmt->error;  // Display error if query fails
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error moving the uploaded file.";  // Handle file move error
            }
        } else {
            echo "Invalid image file type. Only JPG, JPEG, PNG, and GIF are allowed.";  // Handle invalid file extension
        }
    } else {
        echo "Error: File not uploaded.";  // Handle file upload error
    }
}

$conn->close();  // Close the database connection
?>
