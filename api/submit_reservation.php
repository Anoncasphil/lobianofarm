<?php
header("Content-Type: application/json");
require_once "../db_connection.php"; // Ensure this contains your database connection

// Start the session to retrieve the admin_id
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid data received."]);
    exit;
}

$reservationId = $data['reservation_id'] ?? null; // Use an ID to identify the existing reservation
$checkInDate = $data['check_in_date'] ?? null;
$checkOutDate = $data['check_out_date'] ?? null;
$status = $data['status'] ?? null;
$firstName = $data['first_name'] ?? null;
$lastName = $data['last_name'] ?? null;
$email = $data['email'] ?? null;
$mobileNumber = $data['mobile_number'] ?? null;

if (!$reservationId || !$checkInDate || !$checkOutDate || !$status || !$firstName || !$lastName || !$email || !$mobileNumber) {
    echo json_encode(["status" => "error", "message" => "All fields are required, including reservation ID."]);
    exit;
}

try {
    // Fetch the admin ID from the session
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(["status" => "error", "message" => "Admin not logged in."]);
        exit;
    }

    $adminId = $_SESSION['admin_id'];

    // Retrieve the admin's first name and last name from the admin_tbl
    $stmt = $conn->prepare("SELECT firstname, lastname FROM admin_tbl WHERE admin_id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Admin not found."]);
        exit;
    }
    
    $stmt->bind_result($adminFirstName, $adminLastName);
    $stmt->fetch();
    $stmt->close();

    // Check if the reservation exists and fetch current values
    $stmt = $conn->prepare("SELECT check_in_date, check_out_date, status, first_name, last_name, email, mobile_number FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Reservation not found."]);
        exit;
    }

    $stmt->bind_result($currentCheckInDate, $currentCheckOutDate, $currentStatus, $currentFirstName, $currentLastName, $currentEmail, $currentMobileNumber);
    $stmt->fetch();
    $stmt->close();

    // Initialize an empty string to store the changes
    $changes = [];

    // Compare current values with new values and store changes
    if ($currentCheckInDate !== $checkInDate) {
        $changes[] = "Check-in date changed from $currentCheckInDate to $checkInDate";
    }
    if ($currentCheckOutDate !== $checkOutDate) {
        $changes[] = "Check-out date changed from $currentCheckOutDate to $checkOutDate";
    }
    if ($currentStatus !== $status) {
        $changes[] = "Status changed from $currentStatus to $status";
    }
    if ($currentFirstName !== $firstName) {
        $changes[] = "First name changed from $currentFirstName to $firstName";
    }
    if ($currentLastName !== $lastName) {
        $changes[] = "Last name changed from $currentLastName to $lastName";
    }
    if ($currentEmail !== $email) {
        $changes[] = "Email changed from $currentEmail to $email";
    }
    if ($currentMobileNumber !== $mobileNumber) {
        $changes[] = "Mobile number changed from $currentMobileNumber to $mobileNumber";
    }

    // If there are any changes, proceed with the update and log the changes
    if (!empty($changes)) {
        // Update the reservation
        $stmt = $conn->prepare("UPDATE reservations SET check_in_date = ?, check_out_date = ?, status = ?, first_name = ?, last_name = ?, email = ?, mobile_number = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $checkInDate, $checkOutDate, $status, $firstName, $lastName, $email, $mobileNumber, $reservationId);

        if ($stmt->execute()) {
            // Insert the changes into the activity_logs table
            $changeDetails = implode(", ", $changes); // Combine all changes into one string
            $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, changes) VALUES (?, ?)");
            $stmt->bind_param("is", $adminId, $changeDetails);
            $stmt->execute();

            echo json_encode(["status" => "success", "message" => "Reservation updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "success", "message" => "No changes detected."]);
    }

    $conn->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
}
?>
