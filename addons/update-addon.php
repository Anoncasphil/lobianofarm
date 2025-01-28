<?php
include '../db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $id = $_POST['id']; 
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = 'active'; // Status is automatically set to 'active'

    // Initialize update arrays
    $updateFields = [];
    $updateValues = [];

    // Prepare values for updating
    foreach (['name' => $name, 'price' => $price, 'description' => $description, 'status' => $status] as $field => $value) {
        if (!empty($value)) {
            $updateFields[] = "$field = ?";
            $updateValues[] = $value;
        }
    }

    // Handle image upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $uniqueFileName = 'addon_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $destPath = '../src/uploads/addons/' . $uniqueFileName;

        // Check if there's an existing picture to delete
        $sql = "SELECT picture FROM addons WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($existingPicture);
        $stmt->fetch();
        $stmt->close();

        // If there's an existing picture, delete it from the server
        if ($existingPicture) {
            $existingFilePath = '../src/uploads/addons/' . $existingPicture;
            if (file_exists($existingFilePath)) {
                unlink($existingFilePath); // Delete the old image file
            }
        }

        // Validate and upload the new image
        if (getimagesize($fileTmpPath)) {
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $updateFields[] = "picture = ?";
                $updateValues[] = $uniqueFileName; // Store only the file name in the database
            } else {
                exit("Failed to upload image.");
            }
        } else {
            exit("Invalid image file.");
        }
    }

    // Update record if there are fields to update
    if ($updateFields) {
        $sql = "UPDATE addons SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $updateValues[] = $id; // Bind the ID for WHERE clause

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $types = str_repeat('s', count($updateValues) - 1) . 'i'; // 's' for string, 'i' for integer (ID)
        $stmt->bind_param($types, ...$updateValues);

        if ($stmt->execute()) {
            header('Location: addons.php'); // Redirect to the addons page
            exit();
        } else {
            exit("Error updating addon: " . $stmt->error);
        }
        $stmt->close();
    } else {
        exit("No fields to update.");
    }
}

$conn->close(); // Close the database connection
?>
