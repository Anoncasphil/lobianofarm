<?php
session_start();
include('../db_connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>888 Lobiano's Farm</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/normal.css">
    <link rel="icon" href="../src/images/logo.png" type="image/x-icon">
</head>
<body class="flex flex-row items-center h-full justify-between m-0 overflow-y-auto">

    <div id="slogan_container" class="flex h-2/5 w-3/5 items-center justify-center flex-col text-center m-0 text-white">
        <div id="main_slogan" class="flex w-[full] flex-row ">
            <img src="../src/imageslogo.png" class="w-10 h-10" alt="Logo"><h2 class="text-[30px]">888 Lobiano's Farm</h2>
        </div>
        <div id="slogan">
            <h1 class="text-[50px] ">Swim In Style,<br>Customized For<br>Your Comfort</h1>
        </div>
        <p>Discover unbeatable deals on our swimming pool resort. Start planning your dream retreat today!</p>
    </div>


    <!-- login -->
    <div id="login_form" class="flex flex-col justify-center rounded-tl-3xl rounded-bl-3xl w-[40%] bg-white overflow-y-auto">

    <!-- php registration -->
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);  
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $contact_no = trim($_POST['contactno']);
    $password = $_POST['password'];
    $passwordRepeat = $_POST['repeat_password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT); 

    $errors = []; 

    // Validation checks
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($firstname)) {
        $errors[] = "First Name is required.";
    }

    if (empty($lastname)) {
        $errors[] = "Last Name is required.";
    }

    if (empty($contact_no)) {
        $errors[] = "Contact number is required.";
    } elseif (!preg_match('/^[0-9]{11}$/', $contact_no)) {
        $errors[] = "Contact number must be exactly 11 digits.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($password !== $passwordRepeat) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    if (empty($errors)) {
        require_once "../db_connection.php";

        try {
            $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errors[] = "Email address is already registered.";
            }
        } catch (Exception $e) {
            $errors[] = "Error checking email: " . $e->getMessage();
        }
    }

    // Display errors if any
    if (!empty($errors)) {
        $errorMessages = implode("\\n", $errors);
        echo "<script>alert('" . addslashes($errorMessages) . "');</script>";
    } else {
        // Insert values into the database
        try {
            $stmt = $conn->prepare("INSERT INTO user_tbl (email, username, password, first_name, middle_name, last_name, contact_no) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $email, $username, $passwordHash, $firstname, $middlename, $lastname, $contact_no);

            if ($stmt->execute()) {
                echo "<script>alert('User has registered successfully.'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

?>

    <!-- inside the form -->
        <form action="register.php" autocomplete="off" method="post" class="flex flex-col justify-center items-center w-full h-[120vh] overflow-y-auto">
            <h2 id="login_tag" class="text-3xl mb-5 font-bold mt-5">Create an Account</h2>
            <p id="input_tag" class="tracking-wide text-base mb-5">Create a account to continue</p>

            <!-- email -->
            <div id="email_container" class="flex flex-col justify-start items-start w-[80%]">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="email_input" class="text-left w-[50%] mb-2">
                        Email Address:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="email" id="email_input" name="email" placeholder="Email Address" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" required autcomplete="off">
                </div>
            </div>

            <!-- First Name-->
            
            <div class="flex flex-row w-[80%] gap-2">
                <div id="firstname_container" class="flex flex-col justify-start items-start w-[80%] px-3 mt-5">
                    <div class="relative flex flex-row justify-between w-full">
                        <label for="firstname_input" class="text-left w-[50%] mb-2">
                            First Name:
                        </label>
                    </div>
                    <div class="relative w-full">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        <input type="text" id="firstname_input" name="firstname" placeholder="First Name" 
                            class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3">
                    </div>
                </div>
                <!-- Middle Name -->
                <div id="middlename_container" class="flex flex-col justify-start items-start w-[80%] px-3 mt-5">
                    <div class="relative flex flex-row justify-between w-full">
                        <label for="middlename_input" class="text-left w-[80%] mb-2">
                            Middle Name:
                        </label>
                    </div>
                    <div class="relative w-full ">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        <input type="text" id="middlename_input" name="middlename" placeholder="Middle Name" 
                            class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3">
                    </div>
                </div>
            </div>
            <!-- Last Name -->
            <div id="lastname_container" class="flex flex-col justify-start items-start w-[80%] mt-5">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="lastname_input" class="text-left w-[50%] mb-2">
                        Last Name:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="lastname_input" name="lastname" placeholder="Last Name" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3">
                </div>
            </div>

            <!-- Contact No. -->
            <div id="contact_container" class="flex flex-col justify-start items-start w-[80%] mt-5">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="contact_input" class="text-left w-[50%] mb-2">
                        Contact No:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="contact_input" name="contactno" placeholder="Contact No:" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="11">
                </div>
            </div>

            <!-- password -->
            <div id="password_container" class="flex flex-col justify-start items-start w-[80%] mt-5">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="password_input" class="text-left w-[50%] mb-2">
                        Password:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="password" id="password_input" name="password" placeholder="Password" 
                           class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="8" required>
                </div>
            </div>

            <!-- retype password -->
            <div id="verify_password_container" class="flex flex-col justify-start items-start w-[80%] mt-5">
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
            <button type="submit" name="regisme"class="text-white w-[50%] h-[10%] mt-[3%] bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Sign Up</button>
            <p class="mb-5">Have an account already? <span><a href="login.php" class="underline text-blue-600 hover:underline">Login</a></span></p>

        </form>
    </div>
    

    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
</body>
</html>