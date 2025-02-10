<?php
require_once "../db_connection.php"; // Adjust the path to your database connection file

$email = $firstname = $middlename = $lastname = $contact_no = $password = $passwordRepeat = $otp = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);  
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $contact_no = trim($_POST['contactno']);
    $password = $_POST['password'];
    $passwordRepeat = $_POST['repeat_password'];
    $otp = trim($_POST['otp']);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT); 

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

    if (empty($otp)) {
        $errors[] = "OTP is required.";
    } else {
        // Validate OTP
        $stmt = $conn->prepare("SELECT otp_code, otp_expires_at FROM otp_codes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($otpCode, $otpExpiresAt);
        $stmt->fetch();
        $stmt->close();

        if ($otpCode !== $otp) {
            $errors[] = "Invalid OTP. Please try again.";
        } elseif (strtotime($otpExpiresAt) < time()) {
            $errors[] = "OTP has expired. Please try again.";
        }
    }

    // Insert user data if no errors
    if (empty($errors)) {
        try {
            $role = 'customer'; 
            // Insert values into the database
            $stmt = $conn->prepare("INSERT INTO user_tbl (role, email, password, first_name, middle_name, last_name, contact_no) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $role, $email, $passwordHash, $firstname, $middlename, $lastname, $contact_no);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => implode("\\n", $errors)]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>888 Lobiano's Farm</title>

    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/normal.css">
    <link rel="stylesheet" href="../styles/register.css">
    <link rel="icon" href="../src/images/logo.png" type="image/x-icon">
    <style>
        /* Hide number input arrows in WebKit (Chrome, Safari, Edge) */
        #contact_input::-webkit-outer-spin-button,
        #contact_input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hide number input arrows in Firefox */
        #contact_input {
            -moz-appearance: textfield;
        }
        .border-red-500 {
            border-color: #f56565; /* Tailwind CSS red-500 color */
        }
        .hidden {
            display: none;
        }
        .relative-container {
            position: relative;
        }
        .error-message {
            position: absolute;
            bottom: -20px; /* Adjust this value as needed */
            left: 0;
        }
        .input-wrapper {
            position: relative;
            margin-bottom: 0.5rem; /* Reduced from 1.5rem */
        }
        .error-message {
            position: absolute;
            left: 0;
            top: 100%;
            font-size: 0.875rem;
            color: #f56565;
            margin-top: 0.25rem;
        }
        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
        }
    </style>
</head>
<body class="flex overflow-hidden flex-row items-center justify-between m-0">

    <!-- Alerts Section -->
    <section class="absolute top-0 left-1/2 transform -translate-x-1/2 mt-5">
        <div class="container mt-5">
            <div class="row">
                <div class="col-sm-12">
                    <div id="success-message" class="alert fade alert-simple alert-success alert-dismissible text-left font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered hidden" style="padding: 15px; border-radius: 10px; background-color:rgb(207, 248, 216); color: #238845;">
                        <i class="start-icon far fa-check-circle faa-tada animated"></i>
                        <strong class="font__weight-semibold">Congratulations!</strong> You have successfully registered your account.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div id="error-message" class="alert fade alert-simple alert-danger alert-dismissible text-left font__family-montserrat font__size-16 font__weight-light brk-library-rendered rendered hidden" role="alert" data-brk-library="component__alert" style="padding: 15px; border-radius: 10px;background-color:rgb(247, 216, 216); color: #DC143C;">
                        <i class="start-icon far fa-times-circle faa-pulse animated"></i>
                        <strong class="font__weight-semibold">Oh snap!</strong> Change a few things up and try submitting again.
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
    <div id="login_form" class="flex flex-col justify-center rounded-tl-3xl rounded-bl-3xl h-full w-[40%] bg-white overflow-y-auto">

    <!-- inside the form -->
    <form action="register.php" autocomplete="off" method="post" class="flex flex-col justify-center items-center w-full h-full overflow-x-auto">
        <h2 id="login_tag" class="text-3xl mb-5 mt-[10%] font-bold">Create an Account</h2>
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
                <input type="email" id="email_input" name="email" 
                    placeholder="Email Address" 
                    class="w-full border border-gray-500 rounded-lg p-2 pl-10"
                    required autocomplete="off" value="<?php echo htmlspecialchars($email); ?>">
                <small id="email_error" class="text-red-500 mt-1 hidden error-message">Please enter a valid email address.</small>
            </div>
        </div>

        <!-- First Name-->
        
        <div class="flex flex-row w-[80%] gap-2">
            <div id="firstname_container" class="flex flex-col justify-start items-start w-[80%] px-3 mt-5 relative-container">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="firstname_input" class="text-left w-[50%] mb-2">
                        First Name:
                    </label>
                </div>
                <div class="relative w-full">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="firstname_input" name="firstname" placeholder="First Name" 
                        class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3" value="<?php echo htmlspecialchars($firstname); ?>">
                    <small id="firstname_error" class="text-red-500 mt-1 hidden error-message">First Name is required.</small>
                </div>
            </div>
            <!-- Middle Name -->
            <div id="middlename_container" class="flex flex-col justify-start items-start w-[80%] px-3 mt-5 relative-container">
                <div class="relative flex flex-row justify-between w-full">
                    <label for="middlename_input" class="text-left w-[80%] mb-2">
                        Middle Name:
                    </label>
                </div>
                <div class="relative w-full ">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="middlename_input" name="middlename" placeholder="Middle Name" 
                        class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3" value="<?php echo htmlspecialchars($middlename); ?>">
                </div>
            </div>
        </div>
        <!-- Last Name -->
        <div id="lastname_container" class="flex flex-col justify-start items-start w-[80%] mt-5 relative-container">
            <div class="relative flex flex-row justify-between w-full">
                <label for="lastname_input" class="text-left w-[50%] mb-2">
                    Last Name:
                </label>
            </div>
            <div class="relative w-full">
                <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="text" id="lastname_input" name="lastname" placeholder="Last Name" 
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3" value="<?php echo htmlspecialchars($lastname); ?>">
                <small id="lastname_error" class="text-red-500 mt-1 hidden error-message">Last Name is required.</small>
            </div>
        </div>

        <!-- Contact No. -->
        <div id="contact_container" class="flex flex-col justify-start items-start w-[80%] mt-5 relative-container">
            <div class="relative flex flex-row justify-between w-full">
                <label for="contact_input" class="text-left w-[50%] mb-2">
                    Contact No:
                </label>
            </div>
            <div class="relative w-full">
                <i class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="number" id="contact_input" name="contactno" placeholder="Contact No:" 
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="11" maxlength="11" required value="<?php echo htmlspecialchars($contact_no); ?>">
                <small id="contact_error" class="text-red-500 mt-1 hidden error-message">Contact number must be exactly 11 digits.</small>
            </div>
        </div>

        <!-- password -->
        <div id="password_container" class="flex flex-col justify-start items-start w-[80%] mt-5 relative-container">
            <div class="relative flex flex-row justify-between w-full">
                <label for="password_input" class="text-left w-[50%] mb-2">
                    Password:
                </label>
            </div>
            <div class="relative w-full">
                <i class="fa-solid fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="password" id="password_input" name="password" placeholder="Password" 
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="8" required value="<?php echo htmlspecialchars($password); ?>">
                <small id="password_error" class="text-red-500 mt-1 hidden error-message">Password must be at least 8 characters.</small>
            </div>
        </div>

        <!-- retype password -->
        <div id="verify_password_container" class="flex flex-col justify-start items-start w-[80%] mt-5 relative-container">
            <div class="relative flex flex-row justify-between w-full">
                <label for="verify_password_input" class="text-left w-[50%] mb-2">
                    Re-enter Password:
                </label>
            </div>
            <div class="relative w-full">
                <i class="fa-solid fa-check absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="password" id="verify_password_input" name="repeat_password" placeholder="Retype your Password" 
                    class="w-full border border-gray-500 rounded-lg p-2 pl-10" required value="<?php echo htmlspecialchars($passwordRepeat); ?>">
                <small id="verify_password_error" class="text-red-500 mt-1 hidden error-message">Passwords do not match.</small>
            </div>
        </div>

        <!-- OTP Code -->
        <div id="otp_container" class="flex flex-col justify-start items-start w-[80%] mt-5 relative-container">
            <div class="relative flex flex-row justify-between w-full items-center">
                <label for="otp_input" class="text-left w-[30%] mb-2">
                    OTP Code:       <button type="button" id="send_otp_button" class="text-blue-500">Send OTP</button>
                </label>
                
                <span id="timer" class="text-gray-500 ml-2"></span>
            </div>
            <div class="relative w-full">
                <i class="fa-solid fa-key absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="text" id="otp_input" name="otp" placeholder="Enter OTP Code" 
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="6" maxlength="6" required value="<?php echo htmlspecialchars($otp); ?>">
                <small id="otp_error" class="text-red-500 mt-1 hidden error-message">OTP code must be exactly 6 digits.</small>
            </div>
           
        </div>

        <!-- submit button -->
        <button type="submit" name="regisme"class="text-white w-[50%] h-[35px] mt-[3%] bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Sign Up</button>
        <p class="mb-5">Have an account already? <span><a href="login.php" class="underline text-blue-600 hover:underline">Login</a></span></p>

    </form>
</div>

<script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
<script src="../scripts/register.js"></script>
</body>
</html>