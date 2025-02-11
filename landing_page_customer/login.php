<?php
include '../db_connection.php';
session_start(); // Move session_start() to top of file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/normal.css">
    <link rel="stylesheet" href="../styles/error.css">
    <link rel="icon" href="../src/images/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="flex overflow-hidden flex-row items-center justify-between m-0">
<!-- Admin Login Button -->

    <!-- Alerts Section -->
    <section class="absolute top-0 left-1/2 transform -translate-x-1/2 mt-5">
        <div class="container mt-5">
            <div class="row">
                <div class="col-sm-12">
                    <div id="success-message" class="alert fade alert-simple alert-success alert-dismissible text-left font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered hidden" style="padding: 15px; border-radius: 10px; background-color:rgb(207, 248, 216); color: #238845;">
                        <i class="start-icon far fa-check-circle faa-tada animated"></i>
                        Account Logged in.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div id="error-message" class="alert fade alert-simple alert-danger alert-dismissible text-left font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered hidden" role="alert" data-brk-library="component__alert" style="padding: 15px; border-radius: 10px;background-color:rgb(247, 216, 216); color: #DC143C;">
                        <i class="start-icon far fa-times-circle faa-pulse animated"></i>
                        <strong class></strong> Invalid credentials.
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            $input = $_POST["email"];
            $password = $_POST["password"];

            // Debug incoming data
            error_log("Login attempt - Email: " . $input);

            $stmt = $conn->prepare("SELECT user_id, email, password, first_name, last_name, contact_no FROM user_tbl WHERE email = ?");
            $stmt->bind_param("s", $input);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    // Debug user data
                    error_log("User data found: " . print_r($user, true));

                    if (password_verify($password, $user["password"])) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['contact_no'] = $user['contact_no']; // Add this line
                        
                        // Debug session data
                        error_log("Session data set: " . print_r($_SESSION, true));
                        
                        echo "<script>
                            document.getElementById('success-message').classList.remove('hidden');
                            setTimeout(() => {
                                window.location.href = 'homepage.php';
                            }, 3000);
                        </script>";
                        exit();
                    } else {
                        echo "<script>
                            document.getElementById('error-message').classList.remove('hidden');
                            setTimeout(() => {
                                document.getElementById('error-message').classList.add('hidden');
                            }, 3000);
                        </script>";
                    }
                } else {
                    echo "<script>
                        document.getElementById('error-message').classList.remove('hidden');
                        setTimeout(() => {
                            document.getElementById('error-message').classList.add('hidden');
                        }, 3000);
                    </script>";
                }
            } else {
                echo "<script>
                    document.getElementById('error-message').classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('error-message').classList.add('hidden');
                    }, 3000);
                </script>";
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
                        Email Address:
                    </label>
                </div>
                <div class="relative w-full">
                <i class="fa-solid fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="email" id="email_input" name="email" placeholder="Email Address:" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" 
                           required autocomplete="off">
                    <small class="text-red-500 mt-1 hidden error-message">Please enter a valid email address ending with @gmail.com.</small>
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
                    <small class="text-red-500 mt-1 hidden error-message">Password must be at least 8 characters.</small>
                </div>
            </div>
            
            <!-- submit button -->
            <button type="submit" name="logme" class="text-white w-[50%] h-[10%] mt-[10%] bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Login</button>
            <p>Don't have an account? <span><a href="register.php" class="underline text-blue-600 hover:underline">Create Account</a></span></p>
            <p>Go to <span><a href="../adlogin.php" class="underline text-blue-600 hover:underline">Admin</a></span></p>
        </form>
    </div>

    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
    <script src="../scripts/login.js"></script>
</body>
</html>
