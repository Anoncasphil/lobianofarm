<?php
// Include your database connection
include '../db_connection.php';

// Check if the admin_ids are posted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_ids'])) {
    // Decode the admin IDs from the JSON string
    $adminIds = json_decode($_POST['admin_ids'], true);

    // Check if adminIds are valid
    if (!empty($adminIds)) {
        // Prepare the query to delete the selected admins
        $adminIdsStr = implode(",", $adminIds); // Convert array to a comma-separated string
        $query = "DELETE FROM admin_tbl WHERE admin_id IN ($adminIdsStr)"; // Assuming 'admin_tbl' and 'admin_id' columns

        // Execute the query
        if (mysqli_query($conn, $query)) {
            echo "Selected admins have been deleted successfully.";
        } else {
            echo "Error deleting admins: " . mysqli_error($conn);
        }
    } else {
        echo "No admin IDs received.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
