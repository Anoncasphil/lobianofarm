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
$invoice_no = $_POST['invoice_no'] ?? '';
$invoice_items = $_POST['invoice_items'] ?? '';
$total_price = $_POST['total_price'] ?? '';

// Prepare the email body content
$email_body = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Reservation Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 15px;
        }

        p {
            text-align: center;
            color: #888;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 30px;
        }

        .details-grid {
            margin-top: 15px;
        }

        .details-grid label {
            font-weight: bold;
            color: #333;
            display: block;
        }

        .details-grid input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
            font-size: 14px;
            cursor: not-allowed;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .total-section {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .total-amount {
            color: #007bff;
            text-align: right;
        }

        .invoice-number {
            color: #e74c3c;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 40px;
            color: #888;
        }
    </style>
</head>
<body>

<div class='email-wrapper'>
    <div class='container'>
        <h2>Reservation Details</h2>
        <p>Here are your reservation details.</p>

        <!-- Customer Information Section -->
        <div class='section-title'>Customer Information</div>
        <div class='details-grid'>
            <label>First Name</label>
            <input type='text' id='first-name-p' value='$first_name' disabled>
            
            <label>Last Name</label>
            <input type='text' id='last-name-p' value='$last_name' disabled>
            
            <label>Email</label>
            <input type='email' id='email-p' value='$email' disabled>
            
            <label>Mobile Number</label>
            <input type='text' id='mobile-number-p' value='$mobile_number' disabled>
        </div>

        <!-- Reservation Dates Section -->
        <div class='section-title'>Reservation Dates</div>
        <div class='details-grid'>
            <label>Check-in Date</label>
            <input type='date' id='check-in-date' value='$check_in_date' disabled>
            
            <label>Check-out Date</label>
            <input type='date' id='check-out-date' value='$check_out_date' disabled>
            
            <label>Check-in Time</label>
            <input type='time' id='check-in-time' value='$check_in_time' disabled>
            
            <label>Check-out Time</label>
            <input type='time' id='check-out-time' value='$check_out_time' disabled>
        </div>

        <!-- Invoice Section -->
        <div class='section-title'>Invoice</div>
        <div class='details-grid'>
            <label>Invoice Date</label>
            <input type='text' id='invoice-date' value='$invoice_date' disabled>
            
            <label>Invoice Number</label>
            <input type='text' id='invoice-no' class='invoice-number' value='$invoice_no' disabled>
        </div>

        <!-- Items Table Section -->
        <div class='section-title'>Items</div>
        <table class='table'>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody id='invoice-items'>
                $invoice_items
            </tbody>
        </table>

        <!-- Total Amount Section -->
        <div class='total-section'>
            Total: <span id='total-price' class='total-amount'>₱$total_price</span>
        </div>

        <!-- Footer Section -->
        <div class='footer'>
            <p>&copy; 2025 888 Lobiano's Farm. All rights reserved.</p>
        </div>
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
