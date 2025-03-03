<?php
// Include database connection
include('../db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $hoursofstay = $_POST['hoursofstay'];
    $rate_type = $_POST['type'];
    $checkin_time = $_POST['checkin'];
    $checkout_time = $_POST['checkout'];
    $price = $_POST['price']; // Final price after any discount
    $description = $_POST['description'];

    // Check if the discount checkbox is checked and get the discount percentage
    $has_discount = isset($_POST['add_discount_checkbox']) ? 1 : 0;
    $discount_percentage = isset($_POST['discount']) ? $_POST['discount'] : 0;

    // Retrieve original price from the 'tempprice' field
    $original_price = $_POST['tempprice']; // This will be the original price before any discount

    // Calculate the final price after applying the discount
    if ($has_discount) {
        // If there's a discount, calculate final price
        $price = $original_price * (1 - ($discount_percentage / 100));
    } else {
        // If no discount, keep the price as the original price
        $price = $original_price;
    }

    // Get the current picture from the database to retain it if no new file is uploaded
    $sql = "SELECT picture FROM rates WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($current_picture);
        $stmt->fetch();
        $stmt->close();
    }

    // File upload handling
    $picture = $current_picture; // Default to the current picture
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $target_dir = "../src/uploads/rates/"; // Directory to upload images
        $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));

        // Generate a unique name for the image
        $unique_name = uniqid('rate_', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Check if the file is a valid image (PNG, JPG)
        if (in_array($imageFileType, ['jpg', 'png'])) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $unique_name; // Store the unique name of the uploaded image
            }
        }
    }

    // Prepare SQL query for update
    $sql = "UPDATE rates SET
            name = ?, 
            price = ?, 
            original_price = ?, 
            discount_percentage = ?, 
            has_discount = ?, 
            description = ?, 
            hoursofstay = ?, 
            checkin_time = ?, 
            checkout_time = ?, 
            picture = ?, 
            rate_type = ?, 
            updated_at = CURRENT_TIMESTAMP
            WHERE id = ?";

    // Prepare statement and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sdiississssi", 
            $name, 
            $price, 
            $original_price, 
            $discount_percentage, 
            $has_discount, 
            $description, 
            $hoursofstay, 
            $checkin_time, 
            $checkout_time, 
            $picture, 
            $rate_type, 
            $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to rates.php after a successful update
            header("Location: rates.php");
            exit(); // Ensure that no further code is executed after redirection
        } else {
            echo "Error updating rate: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
