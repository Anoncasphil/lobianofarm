<?php
header("Content-Type: application/json");

// Include database connection
include("../db_connection.php"); 

require '../vendor/autoload.php'; // Ensure PHPMailer is installed in 'vendor'

// Read JSON data from request
$data = json_decode(file_get_contents("php://input"), true);
$reservation_id = $data['reservation_id'];

// Fetch reservation details
$query = "SELECT check_in_date, check_out_date, status FROM reservations WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();

// If reservation not found
if (!$existing) {
    echo json_encode(["status" => "error", "message" => "Reservation not found."]);
    exit;
}

// Format dates
function formatDate($date) {
    return date("F j, Y", strtotime($date)); // Example: February 12, 2025
}

$check_in_date = formatDate($existing['check_in_date']);
$check_out_date = formatDate($existing['check_out_date']);

// Prepare email
require "../config/mail.php";  // Your PHPMailer setup

$mail->isHTML(true);
$mail->Subject = "Reservation Details - 888 Lobiano's Farm Resort";

// Create email body with formatted dates
$body = "
    <div style='max-width: 600px; margin: auto; padding: 20px; border-radius: 10px; background-color: #f9f9f9; font-family: Arial, sans-serif;'>
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white; border-radius: 10px 10px 0 0;'>
            <h2>Reservation Details</h2>
        </div>
        <div style='padding: 20px; text-align: center;'>
            <p style='font-size: 16px; color: #333;'>Dear {$data['first_name']} {$data['last_name']},</p>
            <p style='font-size: 16px; color: #333;'>Here are the details of your reservation at <strong>888 Lobiano's Farm Resort</strong>:</p>
            <table style='width: 100%; margin-top: 10px; border-collapse: collapse; text-align: left; border: 1px solid #ddd;'>
                <tr style='background-color: #1e3a8a; color: white;'>
                    <th style='padding: 10px; border: 1px solid #ddd;'>Field</th>
                    <th style='padding: 10px; border: 1px solid #ddd;'>Value</th>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-in Date</td>
                    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>{$check_in_date}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-out Date</td>
                    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>{$check_out_date}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Status</td>
                    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>{$existing['status']}</td>
                </tr>
            </table>
            <p style='font-size: 16px; color: #333; margin-top: 20px;'>For any inquiries, feel free to contact us.</p>
            <p style='font-size: 16px; color: #333;'>Thank you for choosing <strong>888 Lobiano's Farm Resort</strong>!</p>
        </div>
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white; border-radius: 0 0 10px 10px; font-size: 14px;'>
            &copy; 2025 888 Lobiano's Farm Resort | All Rights Reserved
        </div>
    </div>";

$mail->Body = $body;
$mail->addAddress($data['email']);

// Send email
if ($mail->send()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $mail->ErrorInfo]);
}
?>
