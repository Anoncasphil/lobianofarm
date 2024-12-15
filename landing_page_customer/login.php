<?php
// Include your database connection file
include '../db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

<?php
if (isset($_POST["logme"])) {
    $input = $_POST["email"]; // This will now be used for both email or username
    $password = $_POST["password"];
    require_once "../db_connection.php";

    // SQL query to check both email and username
    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE email = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Validation checks for logging in user
    if ($user) {
        if (password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_username"] = $user["username"];
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Invalid credentials');</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }
}
?>

    <!-- php logging in -->
        <form action="login.php" method="post" class="flex flex-col justify-center items-center w-full h-[80%]">
            <h2 id="login_tag" class="text-3xl mb-5 font-bold">Login to Account</h2>
            <p id="input_tag" class="tracking-wide text-base">Please enter your email and password to continue</p>

            <!-- email -->
            <div id="email_container" class="flex flex-col justify-start items-start w-[80%] px-6 mt-10">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="email_input" class="text-left w-[50%] mb-2">
                        Email Address or Username:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="email_input" name="email" placeholder="Enter your Email Address or Username" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" required autocomplete="off">
                </div>
            </div>
    
            <!-- password -->
            <div id="password_container" class="flex flex-col justify-start items-start w-[80%] px-6 mt-10">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="password_input" class="text-left w-[50%] mb-2">
                        Password:
                    </label>
                    <a href="forgetpassword.php" class="text-gray-500">Forget Password?</a>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="password" id="password_input" name="password" placeholder="Enter your Password" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" required autocomplete="off">
                </div>
            </div>
            
            <!-- submit button -->
            <button type="submit" name="logme" class="text-white w-[50%] h-[10%] mt-[10%] bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Login</button>
            <p>Don't have an account? <span><a href="register.php" class="underline text-blue-600 hover:underline">Create Account</a></span></p>



            
        </form>
    </div>
    

    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
</body>
</html>