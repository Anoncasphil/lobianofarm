<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include('../db_connection.php');

// Function to fetch all disabled dates and reasons
function getDisabledDatesWithReason() {
    global $conn;
    
    // Query to fetch disabled dates and their associated reasons
    $sql = "SELECT disable_date, reason FROM disable_dates";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        $disabledDates = [];
        
        // Fetch each row and add the date and reason to the array
        while ($row = $result->fetch_assoc()) {
            $disabledDates[] = [
                'date' => $row['disable_date'],
                'reason' => $row['reason']
            ];
        }
        
        return $disabledDates;
    } else {
        return [];
    }
}

// Get all disabled dates and reasons
$disabledDates = getDisabledDatesWithReason();

// Return the result as a JSON object
echo json_encode(['disableDates' => $disabledDates]);

$conn->close();
?>
