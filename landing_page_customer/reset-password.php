<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../db_connection.php";

$sql = "SELECT * FROM user_tbl WHERE reset_token_hash = ?";

$stmt =$mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    echo "<Session not found.'); window.location.href = 'login.php';</script>";
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    echo "<script>alert('This session has expired.'); window.location.href = 'login.php';</script>";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password?</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/normal.css">
    <link rel="icon" href="../src/images/logo.png" type="image/x-icon">
</head>
<body class="flex overflow-hidden flex-row items-center justify-between m-0">

    <div id="slogan_container" class="flex h-2/5 w-3/5 items-center justify-center flex-col text-center m-0 text-white">
        <div id="main_slogan" class="flex w-[full] flex-row ">
            <img src="../src/images/logo.png" class="w-10 h-10" alt="Logo"><h2 class="text-[30px]">888 Lobiano's Farm</h2>
        </div>
        <div id="slogan">
            <h1 class="text-[50px] ">Swim In Style,<br>Customized For<br>Your Comfort</h1>
        </div>
        <p>Discover unbeatable deals on our swimming pool resort. Start planning your dream retreat today!</p>
    </div>

    <!-- login -->
    <div id="login_form" class="flex flex-col justify-center rounded-tl-3xl rounded-bl-3xl w-[40%] h-full bg-white">

    
    <!-- php logging in -->
        <form action="process_reset_password.php" method="POST" class="flex flex-col justify-center items-center w-full h-[80%]">
            <h2 id="login_tag" class="text-3xl mb-5 font-bold">Forgot Password?</h2>
            <p id="input_tag" class="tracking-wide text-base">Enter your credentials to continue.</p>

            <!-- New Password -->
             <!-- Hidden token for OTP -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
            <div id="password_container" class="flex flex-col justify-start items-start w-[80%] px-6 mt-5">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="password_input" class="text-left w-[50%] mb-2">
                        New Password:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="password" id="password_input" name="password" placeholder="Enter your Password" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="8" required>
                </div>
            </div>

            <!-- retype password -->
            <div id="verify_password_container" class="flex flex-col justify-start items-start w-[80%] px-6 mt-5">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="verify_password_input" class="text-left w-[50%] mb-2">
                        Re-enter Password:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-check absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="password" id="verify password_input" name="repeat_password" placeholder="Retype your Password" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" required>
                </div>
            </div>
            
            <!-- submit button -->
            <button class="text-white w-[50%] h-[10%] mt-[10%] bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Continue</button>
            <span><a href="login.php" class="underline text-blue-600 hover:underline">Back to Login</a>
   
        </form>
    </div>
    

    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
</body>
</html>