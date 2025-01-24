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
    $reservation_id = $_SESSION['reservation_id']; // Ensure reservation_id is set

    // Fetch rate details for the reservation
    $sql_rate = "SELECT rates.name FROM reservation 
                 JOIN rates ON reservation.rate_id = rates.id 
                 WHERE reservation.reservation_id = ?";
    $stmt_rate = $conn->prepare($sql_rate);
    $stmt_rate->bind_param("i", $reservation_id);
    $stmt_rate->execute();
    $result_rate = $stmt_rate->get_result();
    $rate = $result_rate->fetch_assoc();
    $stmt_rate->close();

    // Fetch addons for the reservation
    $sql_addons = "SELECT addons.name FROM reservation_addons 
                   JOIN addons ON reservation_addons.addon_id = addons.id 
                   WHERE reservation_addons.reservation_id = ?";
    $stmt_addons = $conn->prepare($sql_addons);
    $stmt_addons->bind_param("i", $reservation_id);
    $stmt_addons->execute();
    $result_addons = $stmt_addons->get_result();
    $addons = $result_addons->fetch_all(MYSQLI_ASSOC);
    $stmt_addons->close();

    // Prepare rate and addons for email
    $rate_name = $rate ? $rate['name'] : 'None';
    $addons_list = empty($addons) ? 'None' : implode(', ', array_column($addons, 'name'));
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
            <li>Rate: $rate_name</li>
            <li>Add-ons: $addons_list</li>
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
