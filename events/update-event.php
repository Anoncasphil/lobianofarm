<?php
// Include database connection
include '../db_connection.php';
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Admin not authenticated.");
}

$admin_id = $_SESSION['admin_id']; // Get logged-in admin ID

// Fetch admin details from the database
$sql_admin = "SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

$admin_name = $firstname . " " . $lastname; // Full name of the admin

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the eventId is received and not empty
    if (isset($_POST['eventId']) && !empty($_POST['eventId'])) {
        $eventId = $_POST['eventId']; // Event ID for updating
    } else {
        die("Event ID is required.");
    }

    // Get current event details before updating for comparison
    $sql_current = "SELECT name, date, description, picture FROM events WHERE id = ?";
    $stmt_current = $conn->prepare($sql_current);
    $stmt_current->bind_param("i", $eventId);
    $stmt_current->execute();
    $result = $stmt_current->get_result();
    $current_event = $result->fetch_assoc();
    $stmt_current->close();

    // Retrieve form data
    $eventName = $_POST['updateName'];
    $eventDate = $_POST['updateDate'];
    $eventDescription = $_POST['updateDescription'];

    // Start building the SQL query and track changes
    $updateFields = [];
    $params = [];
    $changes = [];

    // Add fields to update if they have been changed
    if (!empty($eventName) && $eventName !== $current_event['name']) {
        $updateFields[] = "name = ?";
        $params[] = $eventName;
        $changes[] = "Name changed from '{$current_event['name']}' to '$eventName'";
    }

    // Convert date format from mm/dd/yyyy to yyyy-mm-dd
    if (!empty($eventDate)) {
        $dateArray = explode('/', $eventDate); // Split the date into parts
        if (count($dateArray) == 3) {
            $formattedDate = $dateArray[2] . '-' . $dateArray[0] . '-' . $dateArray[1]; // Convert to yyyy-mm-dd
            
            // Compare with current date
            if ($formattedDate !== $current_event['date']) {
                $updateFields[] = "date = ?";
                $params[] = $formattedDate;
                
                // Format dates for display in the log
                $old_date = date('F d, Y', strtotime($current_event['date']));
                $new_date = date('F d, Y', strtotime($formattedDate));
                $changes[] = "Date changed from '$old_date' to '$new_date'";
            }
        } else {
            echo "<p>Invalid date format. Please use mm/dd/yyyy.</p>";
            exit;
        }
    }

    if (!empty($eventDescription) && $eventDescription !== $current_event['description']) {
        $updateFields[] = "description = ?";
        $params[] = $eventDescription;
        $changes[] = "Description was updated";
    }

    // Handle file upload
    $new_picture = null;
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
                        $new_picture = $newFileName;
                        $changes[] = "Picture was updated";
                        
                        // Delete old picture if exists
                        if (!empty($current_event['picture'])) {
                            $old_picture_path = $uploadDir . $current_event['picture'];
                            if (file_exists($old_picture_path)) {
                                unlink($old_picture_path);
                            }
                        }
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
            // Log the update if there were changes
            if (!empty($changes)) {
                logEventUpdate($admin_id, $admin_name, $eventId, $eventName ?: $current_event['name'], $changes);
            }
            
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

/**
 * Log the event update to the activity_logs table.
 */
function logEventUpdate($admin_id, $admin_name, $event_id, $event_name, $changes) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone
    date_default_timezone_set('Asia/Manila');

    // Initialize log message with HTML line breaks
    $log_message = "Updated the event: $event_name (ID: $event_id).<br>";
    
    // Add each change with a line break
    foreach($changes as $change) {
        $log_message .= "- " . $change . ".<br>";
    }

    // Insert log entry
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
