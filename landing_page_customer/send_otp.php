<?php
    require_once "../db_connection.php"; // Adjust the path to your database connection file

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/PHPMailer/src/Exception.php';
    require __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require __DIR__ . '/PHPMailer/src/SMTP.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $otpExpiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expires in 10 minutes

        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT email FROM user_tbl WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Insert the email and OTP into the database if it doesn't exist
            $stmt = $conn->prepare("INSERT INTO user_tbl (email, otp_code, otp_expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $otp, $otpExpiresAt);
            if (!$stmt->execute()) {
                error_log("Insert failed: (" . $stmt->errno . ") " . $stmt->error);
                echo json_encode(['success' => false, 'error' => 'Database error: Insert failed']);
                exit;
            }
        } else {
            // Update the OTP in the database if the email already exists
            $stmt = $conn->prepare("UPDATE user_tbl SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
            if (!$stmt) {
                error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
                echo json_encode(['success' => false, 'error' => 'Database error: Prepare failed']);
                exit;
            }
            $stmt->bind_param("sss", $otp, $otpExpiresAt, $email);
            if (!$stmt->execute()) {
                error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                echo json_encode(['success' => false, 'error' => 'Database error: Execute failed']);
                exit;
            }
        }
        $stmt->close();

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'lukemia19@gmail.com'; // SMTP username
            $mail->Password = 'rskaxydhoqtzjzwm'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('noreply@gmail.com', '888 Lobiano\'s Farm');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your OTP code is: <b>$otp</b>";

            $mail->send();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            echo json_encode(['success' => false, 'error' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    }
?>
