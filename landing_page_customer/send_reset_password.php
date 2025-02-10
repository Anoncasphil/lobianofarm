<?php
// Include the database connection
require __DIR__ . "/../db_connection.php";

$email = $_POST["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Verify that $conn is a valid connection
if (!$conn instanceof mysqli) {
    die("Database connection failed.");
}

$sql = "UPDATE user_tbl SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

if ($stmt->affected_rows) {

    $mail =  require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@gmail.com", "888 Lobiano's Farm");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

     Click <a href="https://localhost/Admin/landing_page_customer/reset-password.php?token=$token">here</a> to reset your password.


    END;
    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}
echo "<script>
    alert('A link has been sent to the given address.');
    window.location.href = 'login.php';
</script>";

?>