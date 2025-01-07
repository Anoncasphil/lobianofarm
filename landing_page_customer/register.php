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
    </style>
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
    <div id="login_form" class="flex flex-col justify-center rounded-tl-3xl rounded-bl-3xl h-full w-[40%] bg-white overflow-y-auto">

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
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errorMessages = " . json_encode($errors) . ";
                    errorMessages.forEach(function(errorMessage) {
                        const errorElement = document.createElement('small');
                        errorElement.classList.add('text-red-500', 'mt-1');
                        errorElement.innerText = errorMessage;
                        document.querySelector('form').appendChild(errorElement);
                    });
                });
            </script>";
        } else {
            // Insert values into the database
            try {
                $stmt = $conn->prepare(
                    "INSERT INTO user_tbl (email, password, first_name, middle_name, last_name, contact_no, role) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $defaultRole = 'customer'; // Set the default role
                $stmt->bind_param("sssssss", $email, $passwordHash, $firstname, $middlename, $lastname, $contact_no, $defaultRole);
            
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
    <form action="register.php" autocomplete="off" method="post" class="flex flex-col justify-center items-center w-full h-full overflow-x-auto">
        <h2 id="login_tag" class="text-3xl mb-5 mt-[10%] font-bold">Create an Account</h2>
        <p id="input_tag" class="tracking-wide text-base mb-5">Create a account to continue</p>

        <!-- email -->
        <div id="email_container" class="flex flex-col justify-start items-start w-[80%] relative-container">
            <div class="relative flex flex-row justify-between w-full">
                <label for="email_input" class="text-left w-[50%] mb-2">
                    Email Address:
                </label>
            </div>
            <div class="relative w-full">
                <i class="fa-solid fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="email" id="email_input" name="email" placeholder="Email Address" 
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" required autocomplete="off">
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
                        class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3">
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
                        class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3">
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
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minLength="3">
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
                <input type="text" id="contact_input" name="contactno" placeholder="Contact No:" 
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="11" required>
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
                       class="w-full border border-gray-500 rounded-lg p-2 pl-10" minlength="8" required>
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
                    class="w-full border border-gray-500 rounded-lg p-2 pl-10" required>
                <small id="verify_password_error" class="text-red-500 mt-1 hidden error-message">Passwords do not match.</small>
            </div>
        </div>
    
        <!-- submit button -->
        <button type="submit" name="regisme"class="text-white w-[50%] h-[35px] mt-[3%] bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Sign Up</button>
        <p class="mb-5">Have an account already? <span><a href="login.php" class="underline text-blue-600 hover:underline">Login</a></span></p>

    </form>
</div>

<script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email_input');
    const emailError = document.getElementById('email_error');
    const firstnameInput = document.getElementById('firstname_input');
    const firstnameError = document.getElementById('firstname_error');
    const lastnameInput = document.getElementById('lastname_input');
    const lastnameError = document.getElementById('lastname_error');
    const contactInput = document.getElementById('contact_input');
    const contactError = document.getElementById('contact_error');
    const passwordInput = document.getElementById('password_input');
    const passwordError = document.getElementById('password_error');
    const verifyPasswordInput = document.getElementById('verify_password_input');
    const verifyPasswordError = document.getElementById('verify_password_error');
    const form = document.querySelector('form');

    emailInput.addEventListener('input', function () {
        // Validate email input
        if (!validateEmail(emailInput.value)) {
            emailInput.classList.add('border-red-500');
            emailError.classList.remove('hidden');
        } else {
            emailInput.classList.remove('border-red-500');
            emailError.classList.add('hidden');
        }
    });

    firstnameInput.addEventListener('input', function () {
        // Validate first name input
        if (firstnameInput.value.trim() === '') {
            firstnameInput.classList.add('border-red-500');
            firstnameError.classList.remove('hidden');
        } else {
            firstnameInput.classList.remove('border-red-500');
            firstnameError.classList.add('hidden');
        }
    });

    lastnameInput.addEventListener('input', function () {
        // Validate last name input
        if (lastnameInput.value.trim() === '') {
            lastnameInput.classList.add('border-red-500');
            lastnameError.classList.remove('hidden');
        } else {
            lastnameInput.classList.remove('border-red-500');
            lastnameError.classList.add('hidden');
        }
    });

    contactInput.addEventListener('input', function () {
        // Validate contact number input
        if (!/^[0-9]{11}$/.test(contactInput.value)) {
            contactInput.classList.add('border-red-500');
            contactError.classList.remove('hidden');
        } else {
            contactInput.classList.remove('border-red-500');
            contactError.classList.add('hidden');
        }
    });

    passwordInput.addEventListener('input', function () {
        // Validate password input
        if (passwordInput.value.length < 8) {
            passwordInput.classList.add('border-red-500');
            passwordError.classList.remove('hidden');
        } else {
            passwordInput.classList.remove('border-red-500');
            passwordError.classList.add('hidden');
        }
    });

    verifyPasswordInput.addEventListener('input', function () {
        // Validate password match
        if (passwordInput.value !== verifyPasswordInput.value) {
            verifyPasswordInput.classList.add('border-red-500');
            verifyPasswordError.classList.remove('hidden');
        } else {
            verifyPasswordInput.classList.remove('border-red-500');
            verifyPasswordError.classList.add('hidden');
        }
    });

    form.addEventListener('submit', function (event) {
        // Prevent form submission if there are validation errors
        let hasErrors = false;

        if (!validateEmail(emailInput.value)) {
            emailInput.classList.add('border-red-500');
            emailError.classList.remove('hidden');
            hasErrors = true;
        }

        if (firstnameInput.value.trim() === '') {
            firstnameInput.classList.add('border-red-500');
            firstnameError.classList.remove('hidden');
            hasErrors = true;
        }

        if (lastnameInput.value.trim() === '') {
            lastnameInput.classList.add('border-red-500');
            lastnameError.classList.remove('hidden');
            hasErrors = true;
        }

        if (!/^[0-9]{11}$/.test(contactInput.value)) {
            contactInput.classList.add('border-red-500');
            contactError.classList.remove('hidden');
            hasErrors = true;
        }

        if (passwordInput.value.length < 8) {
            passwordInput.classList.add('border-red-500');
            passwordError.classList.remove('hidden');
            hasErrors = true;
        }

        if (passwordInput.value !== verifyPasswordInput.value) {
            verifyPasswordInput.classList.add('border-red-500');
            verifyPasswordError.classList.remove('hidden');
            hasErrors = true;
        }

        if (hasErrors) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
});
</script>
</body>
</html>