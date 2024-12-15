<?php
// Include your database connection
include '../db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the eventId is received and not empty
    if (isset($_POST['eventId']) && !empty($_POST['eventId'])) {
        $eventId = $_POST['eventId']; // Event ID for updating
    } else {
        die("Event ID is required.");
    }

    // Retrieve other form data
    $eventName = $_POST['updateName'];
    $eventDate = $_POST['updateDate'];
    $eventDescription = $_POST['updateDescription'];

    // Start building the SQL query
    $updateFields = [];
    $params = [];

    // Add fields to update if they have been changed
    if (!empty($eventName)) {
        $updateFields[] = "name = ?";
        $params[] = $eventName;
    }

    // Convert date format from mm/dd/yyyy to yyyy-mm-dd
    if (!empty($eventDate)) {
        $dateArray = explode('/', $eventDate); // Split the date into parts
        if (count($dateArray) == 3) {
            $formattedDate = $dateArray[2] . '-' . $dateArray[0] . '-' . $dateArray[1]; // Convert to yyyy-mm-dd
            $updateFields[] = "date = ?";
            $params[] = $formattedDate;
        } else {
            echo "<p>Invalid date format. Please use mm/dd/yyyy.</p>";
            exit;
        }
    }

    if (!empty($eventDescription)) {
        $updateFields[] = "description = ?";
        $params[] = $eventDescription;
    }

    // Handle file upload
    if (!empty($_FILES['updatePicture']['name'])) {
        $uploadDir = '../src/uploads/events/';

        // Check if the uploads directory exists, create it if not
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Creates the directory with proper permissions
        }

        $fileName = $_FILES['updatePicture']['name'];
        $fileTmpName = $_FILES['updatePicture']['tmp_name'];
        $fileSize = $_FILES['updatePicture']['size'];
        $fileError = $_FILES['updatePicture']['error'];

        // Validate file upload
        if ($fileError === 0) {
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExt, $allowed)) {
                if ($fileSize < 2000000) { // Max file size of 2MB
                    $newFileName = uniqid('', true) . '.' . $fileExt;
                    $fileDestination = $uploadDir . $newFileName;

                    // Move the uploaded file to the designated folder
                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        // Add file field to update list
                        $updateFields[] = "picture = ?";
                        $params[] = $newFileName;
                    } else {
                        echo "<p>Error uploading file. Please try again.</p>";
                    }
                } else {
                    echo "<p>File size exceeds the limit of 2MB.</p>";
                }
            } else {
                echo "<p>Invalid file type. Only JPG, JPEG, and PNG are allowed.</p>";
            }
        } else {
            echo "<p>Error uploading the file. Please try again.</p>";
        }
    }

    // Ensure there are fields to update
    if (!empty($updateFields)) {
        // Add event ID to parameters
        $params[] = $eventId;

        // Build SQL query with the dynamically created update fields
        $sql = "UPDATE events SET " . implode(", ", $updateFields) . " WHERE id = ?";

        // Prepare the statement and bind parameters
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing SQL statement: " . $conn->error);
        }

        $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

        if ($stmt->execute()) {
            // Redirect to events.php after successful update
            header("Location: events.php");
            exit;
        } else {
            echo "<p>No changes were made or failed to update event. Please try again.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>No updates were made. Please fill in at least one field.</p>";
    }
}

$conn->close();
?>
