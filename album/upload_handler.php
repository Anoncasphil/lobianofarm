<?php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch the folder_id from POST data
    $folder_id = intval($_POST['folder_id']);

    // Check if folder_id is valid
    if ($folder_id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid folder ID."]);
        exit;
    }

    // Validate if file is uploaded
    if (empty($_FILES['file'])) {
        echo json_encode(["success" => false, "message" => "Image is required."]);
        exit;
    }

    // Validate file type (only image/png and image/jpeg)
    $allowedTypes = ['image/png', 'image/jpeg'];
    $file = $_FILES['file'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Invalid file type. Only PNG and JPG are allowed."]);
        exit;
    }

    // Fetch folder path from the folders table using the folder_id
    $stmt = $conn->prepare("SELECT path FROM folders WHERE id = ?");
    $stmt->bind_param("i", $folder_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $folder = $result->fetch_assoc();

    // If folder is not found, show error message
    if (!$folder) {
        echo json_encode(["success" => false, "message" => "Folder not found with the ID: $folder_id"]);
        exit;
    }

    // Get folder path from the folder record
    $folderPath = $folder['path'];

    // Sanitize folder path by removing spaces
    $folderPath = str_replace(' ', '_', $folderPath);

    // Check if folder path is not empty
    if (empty($folderPath)) {
        echo json_encode(["success" => false, "message" => "Folder path is empty in the database for ID: $folder_id"]);
        exit;
    }

    // Define upload directory using the sanitized folder path
    $uploadDir =  $folderPath;  // Prepend with ../ to ensure it maps correctly to the server

    // Check if folder exists on the server, and if not, create it
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode(["success" => false, "message" => "Failed to create folder at $uploadDir"]);
            exit;
        }
    }

    // Generate unique file name and sanitize it by removing spaces
    $fileName = time() . '_' . basename($file['name']);
    $fileName = str_replace(' ', '_', $fileName);  // Replace spaces with underscores
    $filePath = $uploadDir . "/" . $fileName;  // Using the folder path as is

    // Move uploaded file to the folder
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Store the relative file path for database storage
        $relativeFilePath = $folderPath . "/" . $fileName;

        // Insert the image details into the images table
        $stmt = $conn->prepare("INSERT INTO images (folder_id, image_path) VALUES (?, ?)");
        $stmt->bind_param("is", $folder_id, $relativeFilePath);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Image uploaded successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "File upload failed. Error code: " . $_FILES['file']['error']]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
