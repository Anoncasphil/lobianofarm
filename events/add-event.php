<?php
// Include the database connection file
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    
    // Handle the file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $file = $_FILES['picture'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];

        // Validate file type (allow only images)
        $allowed_types = ['image/jpeg', 'image/png'];
        if (!in_array($file_type, $allowed_types)) {
            die('Only JPG and PNG images are allowed.');
        }

        // Validate file size (Max 2MB)
        if ($file_size > 2 * 1024 * 1024) {
            die('File size must not exceed 2MB.');
        }

        // Read the image file as binary data
        $file_data = file_get_contents($file_tmp_name);
    } else {
        $file_data = null; // If no file is uploaded, set as NULL
    }

    // Prepare the SQL query to insert the data into the events table
    $sql = "INSERT INTO events (name, description, picture, date, status) 
            VALUES (?, ?, ?, ?, 'active')";

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: s = string, b = blob
        $stmt->bind_param("ssbs", $name, $description, $file_data, $date);

        // Execute the query
        if ($stmt->execute()) {
            echo "Event added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
