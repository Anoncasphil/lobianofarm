<?php
// Include database connection
require_once '../db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $hoursofstay = $_POST['hoursofstay'];
    $checkin_time = $_POST['checkin'];
    $checkout_time = $_POST['checkout'];
    $rate_type = $_POST['type'];  // Get the new field "type" mapped to "rate_type" in the database

    // Handle file upload (if any)
    $picture = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "../src/uploads/rates/";

        // Check if the directory exists, if not, create it
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $original_file_name = basename($_FILES["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

        // Generate a unique file name using timestamp and random number
        $unique_name = time() . '_' . rand(1000, 9999) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Check if the file is a valid image
        $valid_types = array("jpg", "jpeg", "png");
        if (in_array($imageFileType, $valid_types)) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $unique_name;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            exit;
        }
    }

    // Prepare the SQL query for updating the rate, including "rate_type"
    if ($picture) {
        $sql = "UPDATE rates SET name = '$name', price = '$price', description = '$description', hoursofstay = '$hoursofstay', checkin_time = '$checkin_time', checkout_time = '$checkout_time', picture = '$picture', rate_type = '$rate_type' WHERE id = '$id'";
    } else {
        $sql = "UPDATE rates SET name = '$name', price = '$price', description = '$description', hoursofstay = '$hoursofstay', checkin_time = '$checkin_time', checkout_time = '$checkout_time', rate_type = '$rate_type' WHERE id = '$id'";
    }

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Rate updated successfully";
        header("Location: rates.php");  // Redirect to the rates page after success
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
