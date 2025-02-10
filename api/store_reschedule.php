<?php
// Start the session to access session variables
session_start();

// Include the database connection file
include('../db_connection.php');

// Initialize an array to hold the response
$response = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure debugging outputs are removed from the final response

    // Check if the user_id is set in the session
    if (isset($_SESSION['user_id'])) {
        // Retrieve user_id from the session (this is the user making the request)
        $user_id = $_SESSION['user_id'];
        
        // Retrieve and sanitize input from POST data
        $reservation_id = $_POST['reservation_id'] ?? null;
        $check_in_date = $_POST['check_in_date'] ?? null;
        $check_out_date = $_POST['check_out_date'] ?? null;
        $reason = $_POST['description'] ?? null; // Matching 'description' from the form

        // Log the individual variables to debug
        error_log("reservation_id: $reservation_id");
        error_log("check_in_date: $check_in_date");
        error_log("check_out_date: $check_out_date");
        error_log("reason: $reason");

        // Default status is Pending
        $status = 'Pending';

        // Ensure all required fields are provided
        if (!$reservation_id || !$check_in_date || !$check_out_date || !$reason) {
            $response['status'] = 'failure';
            $response['message'] = 'All fields must be provided.';
            echo json_encode($response);  // Ensure proper JSON response
            exit();
        }

        // Validate date format
        $check_in_date_obj = DateTime::createFromFormat('Y-m-d', $check_in_date);
        $check_out_date_obj = DateTime::createFromFormat('Y-m-d', $check_out_date);

        // Check if dates are valid
        if (!$check_in_date_obj || !$check_out_date_obj) {
            $response['status'] = 'failure';
            $response['message'] = 'Invalid date format. Please use YYYY-MM-DD.';
            echo json_encode($response);  // Ensure proper JSON response
            exit();
        }

        // Format dates for DB insertion
        $check_in_date = $check_in_date_obj->format('Y-m-d');
        $check_out_date = $check_out_date_obj->format('Y-m-d');

        // Prepare the SQL query to insert the data into reschedule_request table
        $sql = "INSERT INTO reschedule_request (reservation_id, user_id, check_in_date, check_out_date, reason, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters (i = integer, s = string)
            $stmt->bind_param("iissss", $reservation_id, $user_id, $check_in_date, $check_out_date, $reason, $status);

            // Execute the statement
            if ($stmt->execute()) {
                // Success: Send a success response
                $response['status'] = 'success';
                $response['message'] = 'Request successfully submitted!';
            } else {
                // Failure: Send a failure response
                $response['status'] = 'failure';
                $response['message'] = 'Error executing the reschedule query: ' . $stmt->error;
            }

            // Close the reschedule statement
            $stmt->close();
        } else {
            // Error preparing the query
            $response['status'] = 'error';
            $response['message'] = 'Error preparing the reschedule query: ' . $conn->error;
        }
    } else {
        // If user_id is not found in the session
        $response['status'] = 'failure';
        $response['message'] = 'You must be logged in to submit a reschedule request.';
    }
} else {
    // If the form is not submitted via POST
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Please submit the form properly.';
}

// Close the connection
$conn->close();

// Return the response as JSON
echo json_encode($response);  // Ensure proper JSON response
?>
