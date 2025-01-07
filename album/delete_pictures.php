<?php
include('../db_connection.php');

// Check if the form is submitted
if (isset($_POST['ids'])) {
    $ids = $_POST['ids']; // Get the IDs of selected pictures
    $idsArray = explode(',', $ids);

    // Prepare SQL query to delete pictures from the database
    $query = "SELECT image_path FROM pictures WHERE id IN (" . implode(',', array_fill(0, count($idsArray), '?')) . ")";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('i', count($idsArray)), ...$idsArray);
    $stmt->execute();
    $result = $stmt->get_result();

    // Delete files from the server and database
    while ($row = $result->fetch_assoc()) {
        $imagePath = "../src/uploads/album/" . $row['image_path'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete file
        }
    }

    // Delete records from the database
    $deleteQuery = "DELETE FROM pictures WHERE id IN (" . implode(',', array_fill(0, count($idsArray), '?')) . ")";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param(str_repeat('i', count($idsArray)), ...$idsArray);
    $deleteStmt->execute();

    // Return a JSON response with status
    echo json_encode([
        'status' => 'success',
        'message' => 'Pictures deleted successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No pictures selected.'
    ]);
}

$conn->close();
?>
