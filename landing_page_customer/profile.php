<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="profile-container">
        <h1>User Profile</h1>
        <div class="user-info">
            <p><strong>Name:</strong> <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $_SESSION['user_email']; ?></p>
        </div>
    </div>
</body>
</html>