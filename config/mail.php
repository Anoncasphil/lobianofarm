<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';


// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Use Gmail's SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'lukemia19@gmail.com'; // Your Gmail email
    $mail->Password = 'rskaxydhoqtzjzwm'; // Your app password (Not your Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Default sender
    $mail->setFrom('no-reply@gmail.com', '888 Lobiano\'s Farm Resort');

} catch (Exception $e) {
    die(json_encode(["status" => "error", "message" => "❌ Mailer Error: " . $mail->ErrorInfo]));
}
?>
