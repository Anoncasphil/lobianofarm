<?php
// Include database connection
include '../db_connection.php'; // Ensure you have the correct database connection file

// Check if POST data is set
if (isset($_POST['reservation_id']) && isset($_POST['addons'])) {
    $reservation_id = $_POST['reservation_id'];
    $addons = json_decode($_POST['addons']); // Decoding the JSON array sent from JavaScript

    // Begin transaction to ensure data consistency
    $conn->begin_transaction();

    try {
        // Step 1: Delete existing addons for the reservation
        $deleteQuery = "DELETE FROM reservation_addons WHERE reservation_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->close();

        // Step 2: Insert new addons for the reservation
        if (!empty($addons)) {
            $insertQuery = "INSERT INTO reservation_addons (reservation_id, addon_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);

            // Loop through the selected addons and insert them
            foreach ($addons as $addon_id) {
                $stmt->bind_param("ii", $reservation_id, $addon_id);
                $stmt->execute();
            }

            $stmt->close();
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Addons updated successfully."]);
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Error updating addons: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Required data not provided."]);
}

// Close database connection
$conn->close();
?>
