<?php
session_start();  // Start session

include '../db_connection.php';  // Include the DB connection file

// Check if user is logged in and retrieve user_id from session
if (!isset($_SESSION['user_id'])) {
    echo 'User not logged in.';
    exit;
}

$user_id = $_SESSION['user_id'];

// Collect the form data
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];
$check_in_time = $_POST['check_in_time'];
$check_out_time = $_POST['check_out_time'];
$reference_number = $_POST['reference_number'];  
$invoice_date = $_POST['invoice_date'];
$invoice_number = $_POST['invoice_number'];
$total_price = $_POST['total_price'];
$contact_number = $_POST['mobile_number'];
$rate_id = $_POST['rate_id'];  // Collect rate_id from the POST data
$first_name = $_POST['first_name'];  // Collect first name
$last_name = $_POST['last_name'];    // Collect last name
$email = $_POST['email'];            // Collect email
$mobile_number = $_POST['mobile_number']; // Collect mobile number

// Handle the payment receipt file upload
if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../src/uploads/customerpayment/';  // Directory where payment receipts are saved
    $file_name = $_FILES['payment_receipt']['name'];
    $file_tmp = $_FILES['payment_receipt']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    // Create a unique file name using timestamp and random number
    $unique_file_name = 'payment_receipt_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($file_tmp, $upload_dir . $unique_file_name)) {
        $payment_receipt = $unique_file_name;  // Store the file name in the database
    } else {
        echo 'Error uploading payment receipt file.';
        exit;
    }
} else {
    echo 'No payment receipt uploaded or error with the file.';
    exit;
}

// Escape input data to prevent SQL injection
$check_in_date = mysqli_real_escape_string($conn, $check_in_date);
$check_out_date = mysqli_real_escape_string($conn, $check_out_date);
$check_in_time = mysqli_real_escape_string($conn, $check_in_time);
$check_out_time = mysqli_real_escape_string($conn, $check_out_time);
$reference_number = mysqli_real_escape_string($conn, $reference_number);
$invoice_date = mysqli_real_escape_string($conn, $invoice_date);
$invoice_number = mysqli_real_escape_string($conn, $invoice_number);
$total_price = mysqli_real_escape_string($conn, $total_price);
$contact_number = mysqli_real_escape_string($conn, $contact_number);
$rate_id = mysqli_real_escape_string($conn, $rate_id);  // Escape rate_id
$first_name = mysqli_real_escape_string($conn, $first_name);  // Escape first_name
$last_name = mysqli_real_escape_string($conn, $last_name);    // Escape last_name
$email = mysqli_real_escape_string($conn, $email);            // Escape email
$mobile_number = mysqli_real_escape_string($conn, $mobile_number); // Escape mobile_number
$addon_ids = isset($_POST['addon_ids']) ? json_decode($_POST['addon_ids'], true) : [];

// Prepare SQL query to insert into reservations table
$sql = "INSERT INTO reservations 
        (user_id, check_in_date, check_out_date, check_in_time, check_out_time, reference_number, invoice_date, invoice_number, total_price, payment_receipt, status, payment_status, contact_number, rate_id, first_name, last_name, email, mobile_number) 
        VALUES ('$user_id', '$check_in_date', '$check_out_date', '$check_in_time', '$check_out_time', '$reference_number', '$invoice_date', '$invoice_number', '$total_price', '$payment_receipt', 'Pending', 'Pending', '$contact_number', '$rate_id', '$first_name', '$last_name', '$email', '$mobile_number')";

// Execute the query for the reservation
if (mysqli_query($conn, $sql)) {
    $reservation_id = mysqli_insert_id($conn);  // Get the ID of the newly inserted reservation

    // Insert each addon into the reservation_addons table
    foreach ($addon_ids as $addon_id) {
        $addon_id = mysqli_real_escape_string($conn, $addon_id);
        $addon_sql = "INSERT INTO reservation_addons (reservation_id, addon_id) VALUES ('$reservation_id', '$addon_id')";
        mysqli_query($conn, $addon_sql);
    }

    echo "Reservation successfully added with addons!";
} else {
    echo "Error executing query: " . mysqli_error($conn);
}
?>
