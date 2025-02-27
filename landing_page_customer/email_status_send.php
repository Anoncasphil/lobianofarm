<?php
header("Content-Type: application/json");

// Include database connection
include("../db_connection.php"); 

require '../vendor/autoload.php'; // Ensure PHPMailer is installed in 'vendor'

// Read JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

// Check if reservation_id exists and is valid
if (!isset($data['reservation_id']) || empty($data['reservation_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid reservation ID."]);
    exit;
}

$reservation_id = intval($data['reservation_id']); // Ensure it's an integer

// Fetch reservation details
$query = "SELECT * FROM reservations WHERE id = ?";
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

// Fetch invoice details from reservations table
$invoice_date = isset($existing['invoice_date']) ? formatDate($existing['invoice_date']) : "N/A";
$invoice_number = isset($existing['invoice_number']) ? htmlspecialchars($existing['invoice_number']) : "N/A";

// Format dates
function formatDate($date) {
    return date("F j, Y", strtotime($date)); // Example: February 12, 2025
}

$check_in_date = formatDate($existing['check_in_date']);
$check_out_date = formatDate($existing['check_out_date']);

// Validate user data (to prevent errors)
$first_name = isset($data['first_name']) ? htmlspecialchars($data['first_name']) : "Guest";
$last_name = isset($data['last_name']) ? htmlspecialchars($data['last_name']) : "";
$email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null;

if (!$email) {
    echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    exit;
}

// Prepare email
require "../config/mail.php";  // Your PHPMailer setup

$mail->isHTML(true);
$mail->Subject = "Reservation and Invoice Details - 888 Lobiano's Farm Resort";

// Create email body with formatted dates and invoice details
$body = "
    <div style='max-width: 600px; margin: auto; padding: 20px; border-radius: 10px; background-color: #f9f9f9; font-family: Arial, sans-serif;'>
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white; border-radius: 10px 10px 0 0;'>
            <h2>Reservation Details</h2>
        </div>
        <div style='padding: 20px; text-align: center;'>
            <p style='font-size: 16px; color: #333;'>Dear {$first_name} {$last_name},</p>
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
        </div>
        
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white;'>
            <h3>Invoice Details</h3>
        </div>
        <div style='padding: 20px;'>
            <p style='font-size: 14px; color: #333;'>Date: <strong id='invoice-date'>{$invoice_date}</strong></p>
            <p style='font-size: 14px; color: #333;'>Invoice No: <strong id='invoice-no'>{$invoice_number}</strong></p>
            <table style='width: 100%; margin-top: 10px; border-collapse: collapse; text-align: left; border: 1px solid #ddd;'>
                <thead>
                    <tr style='background-color: #f0f0f0;'>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Category</th>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Item</th>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Price</th>
                    </tr>
                </thead>
                <tbody id='invoice-items'>
                    <!-- Items will be inserted dynamically -->
                </tbody>
            </table>
            <div style='margin-top: 10px; text-align: right;'>
                <p style='font-size: 16px; font-weight: bold; color: #333;'>Total Price: <span id='total-price'>₱0.00</span></p>
                <p style='font-size: 14px; font-weight: bold; color: #555;'>Valid Amount Paid: <span id='valid_amount_paid'>₱0.00</span></p>
                <p style='font-size: 18px; font-weight: bold; color: #1e3a8a;'>Total: <span id='new_total_amount'>₱0.00</span></p>
            </div>
        </div>
        
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white; border-radius: 0 0 10px 10px; font-size: 14px;'>
            &copy; 2025 888 Lobiano's Farm Resort | All Rights Reserved
        </div>
    </div>";

$mail->Body = $body;
$mail->addAddress($email);

// Send email
if ($mail->send()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $mail->ErrorInfo]);
}
?>
