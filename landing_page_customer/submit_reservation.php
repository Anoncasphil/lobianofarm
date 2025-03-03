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
$new_total = $_POST['new_total'];  // New total price after adjustments
$amount_paid = $_POST['amount_paid'];  // Valid amount paid for the reservation
$reservation_code = $_POST['reservation_code'];
$rate_id = $_POST['rate_id'];
$rate_price = $_POST['rate_price'];
$extra_pax = $_POST['extra_pax'];
$extra_pax_price = $_POST['extra_pax_price'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$mobile_number = $_POST['mobile_number'];
$contact_number = $_POST['mobile_number'];

// Handle the payment receipt file upload
if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../src/uploads/customerpayment/';  
    $file_name = $_FILES['payment_receipt']['name'];
    $file_tmp = $_FILES['payment_receipt']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $unique_file_name = 'payment_receipt_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;

    if (move_uploaded_file($file_tmp, $upload_dir . $unique_file_name)) {
        $payment_receipt = $unique_file_name;  
    } else {
        echo 'Error uploading payment receipt file.';
        exit;
    }
} else {
    echo 'No payment receipt uploaded or error with the file.';
    exit;
}

$addon_ids = isset($_POST['addon_ids']) ? json_decode($_POST['addon_ids'], true) : [];

// Prepare the SQL query to insert into the reservations table
$sql = "INSERT INTO reservations 
        (user_id, check_in_date, check_out_date, check_in_time, check_out_time, reference_number, invoice_date, invoice_number, total_price, new_total, valid_amount_paid, payment_receipt, status, payment_status, contact_number, rate_id, rate_price, extra_pax, extra_pax_price, first_name, last_name, email, mobile_number, reservation_code) 
        VALUES ('$user_id', '$check_in_date', '$check_out_date', '$check_in_time', '$check_out_time', '$reference_number', '$invoice_date', '$invoice_number', '$total_price', '$new_total', '$amount_paid', '$payment_receipt', 'Pending', 'Pending', '$contact_number', '$rate_id', '$rate_price', '$extra_pax', '$extra_pax_price', '$first_name', '$last_name', '$email', '$mobile_number', '$reservation_code')";

// Execute the query for the reservation
if (mysqli_query($conn, $sql)) {
    $reservation_id = mysqli_insert_id($conn);  

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
