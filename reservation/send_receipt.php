<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!class_exists('PHPMailer\PHPMailer\Exception')) {
    require 'phpmailer/src/Exception.php';
}
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    require 'phpmailer/src/PHPMailer.php';
}
if (!class_exists('PHPMailer\PHPMailer\SMTP')) {
    require 'phpmailer/src/SMTP.php';
}

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "lukemia19@gmail.com";  // Your Gmail email
$mail->Password = "rskaxydhoqtzjzwm";  // Your app-specific password or Gmail password

$mail->isHTML(true);

// Email body content (insert the HTML here)
$htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <section class="bg-gray-100 pt-16 px-6 md:px-8 min-h-screen">
    <div class="max-w-screen-xl mx-auto flex gap-8 mt-10">
      <div class="flex-4">
        <form id="reservation-form">
          <div id="basic-details" class="flex-4 bg-white p-6 rounded-3xl shadow-lg">
            <h2 class="text-3xl font-extrabold text-gray-700">RESERVATION DETAILS</h2>
            <p class="mt-2 text-gray-600">Here are your reservation details, please review the following fields.</p>
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="hidden" name="user_id" id="user_id" />
                <div class="relative">
                    <input type="text" id="first-name-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="firstname" disabled/>
                    <label for="first-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
                </div>
                <div class="relative">
                    <input type="text" id="last-name-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="lastname" disabled/>
                    <label for="last-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="relative">
                    <input type="email" id="email-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="email" readonly/>
                    <label for="email-p" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
                </div>
                <div class="relative">
                    <input type="text" id="mobile-number-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="mobilenumber" disabled/>
                    <label for="mobile-number" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <input type="date" id="check-in-date" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled/>
                    <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Date</label>
                </div>
                <div class="relative">
                    <input type="date" id="check-out-date" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
                    <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Date</label>
                </div>
                <div class="relative">
                    <input type="time" id="check-in-time" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
                    <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Time</label>
                </div>
                <div class="relative">
                    <input type="time" id="check-out-time" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
                    <label for="check-out-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Time</label>
                </div>
            </div>
          </div>
        </form>
      </div>

      <div id="Invoice" class="bg-white p-8 rounded-3xl mt-5 shadow-xl max-w-4xl mx-auto">
        <h2 class="text-3xl font-extrabold text-gray-700 ">Invoice</h2>
        <div class="mt-5 flex justify-between text-sm text-gray-600">
            <p>Date: <span id="invoice-date" class="font-medium text-gray-800"></span></p>
            <p>Invoice No: <span id="invoice-no" class="font-medium text-gray-800"></span></p>
        </div>
        <div class="mt-8 overflow-x-auto">
            <table class="w-full table-auto border-separate border-spacing-0.5">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left py-2 px-4 font-medium text-gray-700">Category</th>
                        <th class="text-left py-2 px-4 font-medium text-gray-700">Item</th>
                        <th class="text-left py-2 px-4 font-medium text-gray-700">Price</th>
                    </tr>
                </thead>
                <tbody id="invoice-items">
                    <!-- Items will be inserted dynamically -->
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-between items-center border-t pt-4">
            <span class="text-xl font-bold text-gray-700">Total</span>
            <span id="total-price" class="text-xl font-bold text-blue-600">₱0.00</span>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
HTML;

$mail->Body = $htmlContent;

$mail->Subject = "Lobiano's Farm Resort Reservation Details";
$mail->addAddress("antoineochea20@gmail.com");

try {
    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
