<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // If you're using Composer for PHPMailer

// Get the form values from the POST request
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$mobile_number = $_POST['mobile_number'] ?? '';
$check_in_date = $_POST['check_in_date'] ?? '';
$check_out_date = $_POST['check_out_date'] ?? '';
$check_in_time = $_POST['check_in_time'] ?? '';
$check_out_time = $_POST['check_out_time'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$invoice_no = $_POST['invoice_no'] ?? ''; // This is the correct parameter name from the form
$invoice_items = $_POST['invoice_items'] ?? '';
$total_price = $_POST['total_price'] ?? '';
$reservation_code = $_POST['reservation_code'] ?? 'N/A'; // Ensure 'N/A' is the default if not provided
$status = $_POST['status'] ?? 'Pending'; // Add reservation status with default as Pending
$amount_paid = $_POST['amount_paid'] ?? '0.00'; // Add valid amount paid

// Get the extra pax details
$extra_pax = $_POST['extra_pax'] ?? 0;
$extra_pax_price = $_POST['extra_pax_price'] ?? 0;

// Calculate remaining balance
$total_price_value = floatval($total_price);
$amount_paid_value = floatval($amount_paid);
$new_total = $_POST['new_total'] ?? '0.00'; // Use the value from the form


// Format dates and times
function formatDate($date) {
    if (empty($date)) return '';
    return date("M d Y", strtotime($date)); // Format: Jan 15 2024
}

function formatTime($time) {
    if (empty($time)) return '';
    return date("g:iA", strtotime($time)); // Format: 7:00AM
}

// Apply formatting
$formatted_check_in_date = formatDate($check_in_date);
$formatted_check_out_date = formatDate($check_out_date);
$formatted_check_in_time = formatTime($check_in_time);
$formatted_check_out_time = formatTime($check_out_time);
$formatted_invoice_date = formatDate($invoice_date);

// Process invoice items to ensure proper styling and each addon on its own row
$styled_invoice_items = str_replace('<td', '<td style="padding: 10px; border: 1px solid #ddd;"', $invoice_items);

// If there are extra pax, add them to the invoice table
if ($extra_pax > 0) {
    $styled_invoice_items .= "
    <tr>
        <td style='padding: 10px; border: 1px solid #ddd;'>Extra Pax</td>
        <td style='padding: 10px; border: 1px solid #ddd;'>Extra Pax ({$extra_pax} pax)</td>
        <td style='padding: 10px; border: 1px solid #ddd;'>₱" . number_format($extra_pax_price, 2) . "</td>
    </tr>";
}

// Get the appropriate status color
$status_color = "#FFA500"; // Default orange for Pending
if ($status == "Confirmed") {
    $status_color = "#008000"; // Green for Confirmed
} else if ($status == "Completed") {
    $status_color = "#0000FF"; // Blue for Completed
} else if ($status == "Cancelled") {
    $status_color = "#FF0000"; // Red for Cancelled
}

// Prepare the email body content with improved formatting
$email_body = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Reservation Details</title>
</head>
<body>
    <div style='max-width: 600px; margin: auto; padding: 20px; border-radius: 10px; background-color: #f9f9f9; font-family: Arial, sans-serif;'>
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white; border-radius: 10px 10px 0 0;'>
            <h2>Reservation Details</h2>
        </div>
        
        <div style='padding: 20px;'>
            <p style='font-size: 16px; color: #333;text-align: center;'>Dear <strong>{$first_name} {$last_name}</strong>,</p>
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
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$formatted_check_in_date}</strong></td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-out Date:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$formatted_check_out_date}</strong></td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-in Time:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$formatted_check_in_time}</strong></td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd;'>Check-out Time:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'><strong>{$formatted_check_out_time}</strong></td>
                </tr>
            </table>
        </div>
        
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white;'>
            <h3>Invoice Details</h3>
        </div>

        <div style='padding: 20px;'>
            <p style='font-size: 14px; color: #333;'>Date: <strong>{$formatted_invoice_date}</strong></p>
            <p style='font-size: 14px; color: #333;'>Invoice No: <strong>{$invoice_no}</strong></p>
            <table style='width: 100%; margin-top: 10px; border-collapse: collapse; text-align: left; border: 1px solid #ddd;'>
                <thead>
                    <tr>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Category</th>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Item</th>
                        <th style='padding: 10px; border: 1px solid #ddd;'>Price</th>
                    </tr>
                </thead>
                <tbody id='invoice-items'>
                    {$styled_invoice_items}
                </tbody>
            </table>
        </div>
        
        <div style='padding: 20px;'>
            <p style='font-size: 16px; font-weight: bold; color: #333;'>Subtotal: <span>₱" . number_format($total_price_value, 2) . "</span></p>
            <p style='font-size: 14px; font-weight: bold; color: #555;'>Amount Paid: <span>₱" . number_format($amount_paid_value, 2) . "</span></p>
            <p style='font-size: 18px; font-weight: bold; color: #1e3a8a; text-align: left;'>Total: <span>₱" . number_format($new_total, 2) . "</span></p>
        </div>
        
        <div style='text-align: center; padding: 10px; background-color: #1e3a8a; color: white; border-radius: 0 0 10px 10px;'>
            <p>&copy; 2025 888 Lobiano's Farm. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
";

// Send email
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to use
    $mail->SMTPAuth = true;
    $mail->Username = "lukemia19@gmail.com";  // Your Gmail email
    $mail->Password = "rskaxydhoqtzjzwm";  // Your app-specific password or Gmail password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('no-reply@gmail.com', '888 Lobiano\'s Farm Resort');
    $mail->addAddress($email, "$first_name $last_name"); // Send to user

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Reservation Confirmation';
    $mail->Body = $email_body;

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Message has been sent']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
}
?>