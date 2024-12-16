<?php
// Include database connection
include '../db_connection.php'; // Ensure this file contains your database connection code

// Check if the 'id' parameter is passed in the URL
// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $adminId = $_GET['id'];

    // Prepare SQL query to fetch admin details from the database
    $sql = "SELECT * FROM admin_tbl WHERE admin_id = ?";
    
    // Use prepared statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $adminId); // Bind the admin ID parameter to the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a matching admin was found
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Check if the admin has a profile picture and append the path
            if ($admin['profile_picture']) {
                $admin['profile_picture'] = '../src/uploads/team/' . $admin['profile_picture'];
            } else {
                $admin['profile_picture'] = ''; // In case no profile picture is found
            }

            // Return admin details as a JSON response
            echo json_encode($admin);
        } else {
            echo json_encode(['error' => 'Admin not found']);
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error preparing the query']);
    }

} else {
    echo json_encode(['error' => 'Admin ID is required']);
}

// Close the database connection
$conn->close();
?>
