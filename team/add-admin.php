<?php
// Include the database connection file
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Handle file upload
    if (isset($_FILES['picture'])) {
        $file_name = $_FILES['picture']['name'];
        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_error = $_FILES['picture']['error'];
        $file_size = $_FILES['picture']['size'];

        // Define the upload directory and allowed file types
        $upload_dir = '../src/uploads/team/';
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check if the file type is allowed
        if (in_array($file_extension, $allowed_extensions)) {
            // Check if there are no upload errors
            if ($file_error === 0) {
                // Generate a unique name for the file to avoid overwriting
                $new_file_name = uniqid('img_', true) . '.' . $file_extension;
                $file_path = $upload_dir . $new_file_name;

                // Move the uploaded file to the server
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $profile_picture = $new_file_name;
                } else {
                    echo "Error uploading the file.";
                    exit;
                }
            } else {
                echo "There was an error with the file upload.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }
    } else {
        $profile_picture = null; // If no picture is uploaded, set as null
    }

    // Encrypt the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new admin
    $query = "INSERT INTO admin_tbl (firstname, lastname, email, password, role, profile_picture, status) 
              VALUES ('$fname', '$lname', '$email', '$hashed_password', '$role', '$profile_picture', 'active')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "Admin added successfully!";
        // You can redirect to another page if necessary
        header("Location: team.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
