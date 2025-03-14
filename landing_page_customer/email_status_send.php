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

// Get reservation status
$status = isset($existing['status']) ? $existing['status'] : 'Pending';

// Get the appropriate status color
$status_color = "#FFA500"; // Default orange for Pending
if ($status == "Confirmed") {
    $status_color = "#008000"; // Green for Confirmed
} else if ($status == "Completed") {
    $status_color = "#0000FF"; // Blue for Completed
} else if ($status == "Cancelled") {
    $status_color = "#FF0000"; // Red for Cancelled
}

// Fetch invoice details from reservations table
$invoice_date = isset($existing['invoice_date']) ? formatDate($existing['invoice_date']) : "N/A";
$invoice_number = isset($existing['invoice_number']) ? htmlspecialchars($existing['invoice_number']) : "N/A";

// Fetch rate and addons details
$rate_query = "SELECT * FROM rates WHERE id = ?";
$rate_stmt = $conn->prepare($rate_query);
$rate_stmt->bind_param("i", $existing['rate_id']);
$rate_stmt->execute();
$rate_result = $rate_stmt->get_result();
$rate = $rate_result->fetch_assoc();

$addons_query = "SELECT a.name, a.price FROM reservation_addons ra JOIN addons a ON ra.addon_id = a.id WHERE ra.reservation_id = ?";
$addons_stmt = $conn->prepare($addons_query);
$addons_stmt->bind_param("i", $reservation_id);
$addons_stmt->execute();
$addons_result = $addons_stmt->get_result();
$addons = $addons_result->fetch_all(MYSQLI_ASSOC);

// Calculate total pax and price (including extra pax)
$total_pax = intval($existing['total_pax']); // Assuming total pax is stored in the database
$extra_pax = intval($existing['extra_pax']);
$total_pax += $extra_pax; // Add extra pax to the total pax count

// Calculate total price (rate + extra pax + addons)
$total_price = floatval($rate['price']);
$extra_pax_price = floatval($existing['extra_pax_price']);
$total_price += $extra_pax_price;

foreach ($addons as $addon) {
    $total_price += floatval($addon['price']);
}

// Fetch valid amount paid and calculate new total
$valid_amount_paid = isset($existing['valid_amount_paid']) ? floatval($existing['valid_amount_paid']) : 0.00;
$new_total = $total_price - $valid_amount_paid;

// Format dates
function formatDate($date) {
    return date("F j, Y", strtotime($date)); // Example: February 12, 2025
}

function formatTime($time) {
    return date("g:i A", strtotime($time)); // Example: 2:30 PM
}

$check_in_date = formatDate($existing['check_in_date']);
$check_out_date = formatDate($existing['check_out_date']);
$check_in_time = isset($existing['check_in_time']) ? formatTime($existing['check_in_time']) : "N/A";
$check_out_time = isset($existing['check_out_time']) ? formatTime($existing['check_out_time']) : "N/A";

// Combine check-in and check-out times into a single time range
$time_range = "{$check_in_time} - {$check_out_time}";

// Fetch reservation code
$reservation_code = isset($existing['reservation_code']) ? htmlspecialchars($existing['reservation_code']) : "N/A";

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
        <div style='padding: 20px;'>
            <p style='font-size: 16px; color: #333;text-align: center;'>Dear {$first_name} {$last_name},</p>
            <p style='font-size: 16px; color: #333;text-align: center;'>Here are the details of your reservation at <strong>888 Lobiano's Farm Resort:</strong><br></p>
            <div style='text-align: center; padding: 5px; background-color: #1e3a8a; color: white;text-align: center;'>
                <h3>Reservation Code: <strong>{$reservation_code}</strong></h3>
            </div>

            <div style='text-align: center; margin: 20px 0;'>
                <span style='font-size: 16px; color: #333; font-weight: bold;'>Status: </span>
                <span style='background-color: {$status_color}; color: white; padding: 5px 10px; border-radius: 20px; font-weight: bold;'>{$status}</span>
            </div>

            <p style='font-size: 16px; color: #333;text-align: left;'><strong>Stay Duration</strong>:</p>
            <table style='width: 100%; margin-top: 10px; border-collapse: collapse; text-align: left; border: 1px solid #ddd;'>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-in Date:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$check_in_date}</strong></td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-out Date:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$check_out_date}</strong></td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-in Time:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$check_in_time}</strong></td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-out Time:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$check_out_time}</strong></td>
                </tr>
            </table>
        </div>

        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white;'>
            <h3>Invoice Details</h3>
        </div>
        <div style='padding: 20px;'>
            <p style='font-size: 14px; color: #333;'>Date: <strong>{$invoice_date}</strong></p>
            <p style='font-size: 14px; color: #333;'>Invoice No: <strong>{$invoice_number}</strong></p>
            <table style='width: 100%; margin-top: 10px; border-collapse: collapse; text-align: left; border: 1px solid #ddd;'>
                <thead>
                    <tr style='background-color: #f0f0f0;'>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Category</th>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Item</th>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Price</th>
                    </tr>
                </thead>
                <tbody id='invoice-items'>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd;'>Rate</td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>{$rate['name']}</td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>{$rate['price']}</td>
                    </tr>";
foreach ($addons as $addon) {
    $body .= "
                    <tr>
                        <td style='padding: 10px; border: 1px solid #ddd;'>Add-on</td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>{$addon['name']}</td>
                        <td style='padding: 10px; border: 1px solid #ddd;'>{$addon['price']}</td>
                    </tr>";
}
$body .= "
                </tbody>
            </table>
            <div style='margin-top: 10px; text-align: right;'>
                <p style='font-size: 16px; font-weight: bold; color: #333;text-align: left;'>Total Pax: <span>{$total_pax} person(s)</span></p>
                <p style='font-size: 16px; font-weight: bold; color: #333;text-align: left;'>Subtotal: <span>₱" . number_format($total_price, 2) . "</span></p>
                <p style='font-size: 14px; font-weight: bold; color: #555;text-align: left;'>Amount Paid: <span>₱" . number_format($valid_amount_paid, 2) . "</span></p>
                <p style='font-size: 18px; font-weight: bold; color: #1e3a8a;text-align: left;'>Total: <span>₱" . number_format($new_total, 2) . "</span></p>
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
