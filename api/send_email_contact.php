<?php
// Load PHPMailer classes using Composer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Path to the Composer autoload file (adjust if needed)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING);
    $last_name = filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Create a new PHPMailer instance
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
        $mail->setFrom('lobianofarmresort@gmail.com', 'Lobiano Farm Resort'); // Sender's email
        $mail->addAddress('antoineochea0321@gmail.com', 'Antoine'); // Add recipient's email

        // Content
        $mail->isHTML(false);  // Set email format to plain text
        $mail->Subject = "Contact Form Submission from $first_name $last_name";
        $mail->Body    = "You have received a new message from $first_name $last_name.\n\n" .
                         "Email: $email\n\n" .
                         "Message:\n$message";

        // Send the email
        if ($mail->send()) {
            // Redirect to index.php upon success
            header("Location: ../index.php");  // Adjust the path if needed
            exit;  // Ensure no further code is executed after the redirect
        }
    } catch (Exception $e) {
        echo "Failed to send email. Error: {$mail->ErrorInfo}";
    }
}
?>
