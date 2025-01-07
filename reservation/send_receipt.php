<?php
session_start();
include("../db_connection.php");
require __DIR__ . "/../landing_page_customer/mailer.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['first_name'])) {
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $total_amount = $_SESSION['total_amount'];
    $reservation_check_in_date = $_SESSION['reservation_check_in_date'];
    $reservation_check_out_date = $_SESSION['reservation_check_out_date'];
    $addons = isset($_SESSION['addons']) ? $_SESSION['addons'] : 'None'; // Check if addons are set
} else {
    echo "<script>alert('No reservation data found!'); window.location.href='reservation.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = require __DIR__ . "/../landing_page_customer/mailer.php";

    $mail->setFrom("noreply@gmail.com", "888 Lobiano's Farm");
    $mail->addAddress($email);
    $mail->Subject = "Reservation Receipt";
    $mail->Body = <<<END
        <h1>Reservation Receipt</h1>
        <p>Dear $first_name $last_name,</p>
        <p>Thank you for your reservation. Here are the details:</p>
        <ul>
            <li>Check-In Date: $reservation_check_in_date</li>
            <li>Check-Out Date: $reservation_check_out_date</li>
            <li>Add-ons: $addons</li>
            <li>Total Amount: ₱$total_amount</li>
        </ul>
        <p>We look forward to your stay!</p>
END;

    try {
        $mail->send();
        echo "<script>alert('Receipt has been sent to your email.'); window.location.href='../landing_page_customer/main_page_logged.php';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}
?>
