<?php
// Include the database connection file
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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $name = htmlspecialchars(trim($_POST['name']));
        $description = htmlspecialchars(trim($_POST['description']));
        
        // Ensure date is in the correct format (mm/dd/yyyy)
        $date = $_POST['date'];

        // Convert the date format from mm/dd/yyyy to yyyy-mm-dd
        $dateArray = explode('/', $date); // Split the date string by "/"
        if (count($dateArray) === 3) {
            $formattedDate = $dateArray[2] . '-' . $dateArray[0] . '-' . $dateArray[1]; // Convert to yyyy-mm-dd
        } else {
            throw new Exception('Invalid date format. Please use mm/dd/yyyy format.');
        }

        $status = 'active'; // Default status

        // Handle file upload
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['picture']['tmp_name'];
            $fileName = $_FILES['picture']['name'];
            $fileSize = $_FILES['picture']['size'];
            $fileType = $_FILES['picture']['type'];

            $allowedFileTypes = ['image/jpeg', 'image/png'];
            $uploadDir = '../src/uploads/events/';

            // Validate file type
            if (in_array($fileType, $allowedFileTypes)) {
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid('event_', true) . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;

                // Create the upload directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Move the file to the upload directory
                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $picture = $newFileName;
                } else {
                    throw new Exception('Failed to move the uploaded file.');
                }
            } else {
                throw new Exception('Invalid file type. Only JPG and PNG are allowed.');
            }
        } else {
            throw new Exception('No file uploaded or there was an upload error.');
        }

        // Prepare SQL statement using MySQLi
        $sql = "INSERT INTO events (name, description, picture, date, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the prepared statement
        $stmt->bind_param('sssss', $name, $description, $picture, $formattedDate, $status);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the ID of the newly inserted event
            $event_id = $conn->insert_id;
            
            // Log the event addition
            logEventAddition($admin_id, $admin_name, $event_id, $name, $formattedDate);
            
            // Redirect to events.php after successfully adding the event
            header("Location: events.php");
            exit; // Make sure no further code is executed after the redirect
        } else {
            throw new Exception('Failed to add the event to the database.');
        }

        // Close the prepared statement
        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}

/**
 * Log the event addition to the activity_logs table.
 */
function logEventAddition($admin_id, $admin_name, $event_id, $event_name, $event_date) {
    include('../db_connection.php'); // Include your database connection file

    // Set timezone
    date_default_timezone_set('Asia/Manila');
    
    // Format date for display
    $formatted_date = date('F d, Y', strtotime($event_date));

    // Log message with HTML line breaks
    $log_message = "Added a new event: '$event_name' (ID: $event_id).<br>";
    $log_message .= "- Event Date: $formatted_date.<br>";

    // Insert log entry
    $sql = "INSERT INTO activity_logs (admin_id, timestamp, changes) VALUES (?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $admin_id, $log_message);
    $stmt->execute();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
