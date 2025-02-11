<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../db_connection.php';

$data_raw = file_get_contents("php://input");
file_put_contents("email_debug.log", "[" . date("Y-m-d H:i:s") . "] Raw Input: " . $data_raw . PHP_EOL, FILE_APPEND);

$data = json_decode($data_raw, true);

if (!$data || !isset($data['request_id'], $data['status'])) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON received"]);
    exit;
}

if (!$request_id || !is_numeric($request_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid request ID"]);
    exit;
}
$status = $data['status'];

// Fetch the email based on request_id
$sql = "SELECT r.id, r.first_name, r.last_name, r.email, r.check_in_date, r.check_out_date
        FROM reservations r
        JOIN reschedule_request rr ON r.id = rr.reservation_id
        WHERE rr.request_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "No reservation found for this request ID"]);
    exit;
}

// ✅ Extract user details
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email']; 
$check_in_date = $user['check_in_date'];
$check_out_date = $user['check_out_date'];

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "User email not found"]);
    exit;
}

// ✅ Format dates from YYYY-MM-DD to Month Day, Year
$formatted_check_in_date = date("F j, Y", strtotime($check_in_date));
$formatted_check_out_date = date("F j, Y", strtotime($check_out_date));

// ✅ Send email using PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'lukemia19@gmail.com'; // Your Gmail email
    $mail->Password = 'rskaxydhoqtzjzwm'; // Your app password (Not your Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('no-reply@noreply.com', '888 Lobiano\'s Farm Resort');
    $mail->addAddress($email, "$first_name $last_name");

    $mail->isHTML(true);
    $mail->Subject = "Reschedule Request $status";

// ✅ Create email body with formatted dates
$body = "
    <div style='max-width: 600px; margin: auto; padding: 20px; border-radius: 10px; background-color: #f9f9f9; font-family: Arial, sans-serif; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);'>
        <div style='text-align: center; padding: 15px; background-color: #1e3a8a; color: white; border-radius: 10px 10px 0 0;'>
            <h2 style='margin: 0;'>Reservation Update</h2>
        </div>
        <div style='padding: 20px; text-align: center; color: #333;'>
            <p style='font-size: 16px;'>Dear <strong>{$first_name} {$last_name}</strong>,</p>
            <p style='font-size: 16px;'>Your reservation at <strong>888 Lobiano's Farm Resort</strong> has been <strong>{$status}</strong>.</p>
            <table style='width: 100%; margin-top: 15px; border-collapse: collapse; text-align: left; border: 1px solid #ddd; font-size: 16px;'>
                <tr style='background-color: #1e3a8a; color: white;'>
                    <th style='padding: 12px; border: 1px solid #ddd;'>Field</th>
                    <th style='padding: 12px; border: 1px solid #ddd;'>Details</th>
                </tr>
                <tr>
                    <td style='padding: 12px; border: 1px solid #ddd;'>Check-in Date</td>
                    <td style='padding: 12px; border: 1px solid #ddd; font-weight: bold;'>{$formatted_check_in_date}</td>
                </tr>
                <tr>
                    <td style='padding: 12px; border: 1px solid #ddd;'>Check-out Date</td>
                    <td style='padding: 12px; border: 1px solid #ddd; font-weight: bold;'>{$formatted_check_out_date}</td>
                </tr>
                <tr>
                    <td style='padding: 12px; border: 1px solid #ddd;'>Reschedule Request</td>
                    <td style='padding: 12px; border: 1px solid #ddd; font-weight: bold; color: #d9534f;'>{$status}</td>
                </tr>
            </table>
            <p style='font-size: 16px; margin-top: 20px;'>If you have any questions, feel free to contact us.</p>
            <p style='font-size: 16px;'>Thank you for choosing <strong>888 Lobiano's Farm Resort</strong>!</p>
        </div>
        <div style='text-align: center; padding: 15px; background-color: #1e3a8a; color: white; border-radius: 0 0 10px 10px; font-size: 14px;'>
            &copy; 2025 888 Lobiano's Farm Resort | All Rights Reserved
        </div>
    </div>";

    $mail->Body = $body;

    if (!$mail->send()) {
        file_put_contents("email_debug.log", "[" . date("Y-m-d H:i:s") . "] PHPMailer Error: " . $mail->ErrorInfo . PHP_EOL, FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "Email sending failed", "error" => $mail->ErrorInfo]);
    } else {
        echo json_encode(["status" => "success", "message" => "Email sent successfully"]);
    }
} catch (Exception $e) {
    file_put_contents("email_debug.log", "[" . date("Y-m-d H:i:s") . "] PHPMailer Exception: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "PHPMailer Exception", "error" => $e->getMessage()]);
}
?>
