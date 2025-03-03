

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>



    <style>
        .fade-enter {
        opacity: 0;
        transform: scale(0.95);
        }

        .fade-enter-active {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .fade-exit {
        opacity: 1;
        transform: scale(1);
        }

        .fade-exit-active {
        opacity: 0;
        transform: scale(0.95);
        transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .border-red-500 {
    border-color: red !important;
  }

.fade-in {
    animation: fadeIn 0.5s ease forwards;
}
.fade-out {
    animation: fadeOut 0.5s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(20px); }
}
.hidden {
    display: none;
}


    </style>


</head>
<body>
    
<div class="font-[sans-serif] flex items-center justify-center min-h-screen px-4">
  <div class="max-w-md w-full p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] rounded-md">
    <div class="w-full px-4 py-4">
      
      <!-- Register Form -->
      <div id="register-form">
        <form>
          <div class="mb-8">
            <h3 class="text-gray-800 text-2xl font-extrabold">Register</h3>
            <p class="text-sm mt-2 text-gray-800">
              Already have an account? 
              <a href="login.php" class="text-blue-900 font-semibold hover:underline ml-1 whitespace-nowrap">Sign in here</a>
            </p>
          </div>

          <div id="error-message" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 hidden" role="alert">
    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 1 1 0 2Z"/>
    </svg>
    <span class="sr-only">Error</span>
    <div>
        <span class="font-medium">Error: </span> Something went wrong. Please check the form.
    </div>
</div>

          <div class="flex gap-2">
            <div class="w-1/2">
              <label class="text-gray-800 text-xs block mb-1">First Name</label>
              <input id="first_name" name="first_name" type="text" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 px-2 py-2 outline-none" placeholder="First name" />
            </div>
            
            <div class="w-1/2">
              <label class="text-gray-800 text-xs block mb-1">Last Name</label>
              <input id="last_name" name="last_name" type="text" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 px-2 py-2 outline-none" placeholder="Last name" />
            </div>
          </div>

          <div class="mt-3">
          
          <label for="mobile" class="text-gray-800 text-xs font-semibold block">Phone Number</label>
        <div class="mt-3 flex items-center border border-gray-300 focus-within:border-blue-600">
            <span class="text-gray-800 text-sm px-3 py-2 bg-gray-100 border-r border-gray-300">+63</span>
            <input id="mobile" name="mobile" type="text" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 px-2 py-2 outline-none" placeholder="Enter Phone Number" maxlength="12" />
        </div>
        </div>


          <div class="mt-3">
            <label class="text-gray-800 text-xs block mb-1">Email</label>
            <div class="relative flex items-center">
                  <input id="email" name="email" type="text" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 pl-2 pr-8 outline-none" placeholder="Enter email" />
                  <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2" viewBox="0 0 682.667 682.667">
                    <defs>
                      <clipPath id="a" clipPathUnits="userSpaceOnUse">
                        <path d="M0 512h512V0H0Z" data-original="#000000"></path>
                      </clipPath>
                    </defs>
                    <g clip-path="url(#a)" transform="matrix(1.33 0 0 -1.33 0 682.667)">
                      <path fill="none" stroke-miterlimit="10" stroke-width="40" d="M452 444H60c-22.091 0-40-17.909-40-40v-39.446l212.127-157.782c14.17-10.54 33.576-10.54 47.746 0L492 364.554V404c0 22.091-17.909 40-40 40Z" data-original="#000000"></path>
                      <path d="M472 274.9V107.999c0-11.027-8.972-20-20-20H60c-11.028 0-20 8.973-20 20V274.9L0 304.652V107.999c0-33.084 26.916-60 60-60h392c33.084 0 60 26.916 60 60v196.653Z" data-original="#000000"></path>
                    </g>
                  </svg>
                </div>
                <div id="email-requirements" class="text-xs mt-1 text-red-500"></div>
          </div>

          <div class="mt-3">
  <label class="text-gray-800 text-xs block mb-1">Password</label>
  <div class="relative flex items-center">
    <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 pl-2 pr-8 outline-none" placeholder="Enter password" />
    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2 cursor-pointer password-toggle" viewBox="0 0 128 128">
      <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"></path>
    </svg>
  </div>
  <div id="password-requirements" class="text-xs mt-1 text-red-500"></div>
</div>

<div class="mt-3">
  <label class="text-gray-800 text-xs block mb-1">Confirm Password</label>
  <div class="relative flex items-center">
    <input id="confirm_password" name="confirm_password" type="password" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 pl-2 pr-8 outline-none" placeholder="Confirm password" />
    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2 cursor-pointer password-toggle" viewBox="0 0 128 128">
      <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"></path>
    </svg>
  </div>
</div>


<div class="mt-5 flex items-center">
  <input id="agree" name="agree" type="checkbox" required class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" />
  <label for="agree" class="ml-2 text-sm text-gray-800">
    I have read, understood and agreed to the resort's 
    <a href="../tac.html" class="text-blue-900 hover:underline" target="_blank" rel="noopener noreferrer">terms and conditions</a>.
  </label>
</div>

          <div class="mt-8">
            <button type="button" id="register-btn" class="w-full shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
              Register
            </button>
          </div>
        </form>
      </div>


<!-- OTP Section (Hidden by Default) -->
<div id="otp-section" class="hidden mt-6">
    <!-- Back Button + Title -->
    <div class="flex items-center space-x-2 mb-4">
        <button id="back-button" class="flex items-center text-blue-600 hover:text-blue-900">
            <span class="material-icons text-sm text-blue-900">arrow_back_ios</span>
            <span class="text-sm font-medium text-blue-900">Back</span>
        </button>
    </div>
    <h3 class="text-gray-800 text-2xl font-extrabold">Email Verification</h3>
    <p class="text-sm text-gray-800" id="otp-message">
        An OTP has been sent to your email. Please enter the 6-digit code below to verify your email address.
    </p>

    <!-- OTP Input Fields -->
    <div class="flex justify-between mt-3">
        <input type="text" class="otp-input w-12 h-12 text-center text-xl border border-gray-300 focus:border-blue-600 outline-none" maxlength="1" />
        <input type="text" class="otp-input w-12 h-12 text-center text-xl border border-gray-300 focus:border-blue-600 outline-none" maxlength="1" />
        <input type="text" class="otp-input w-12 h-12 text-center text-xl border border-gray-300 focus:border-blue-600 outline-none" maxlength="1" />
        <input type="text" class="otp-input w-12 h-12 text-center text-xl border border-gray-300 focus:border-blue-600 outline-none" maxlength="1" />
        <input type="text" class="otp-input w-12 h-12 text-center text-xl border border-gray-300 focus:border-blue-600 outline-none" maxlength="1" />
        <input type="text" class="otp-input w-12 h-12 text-center text-xl border border-gray-300 focus:border-blue-600 outline-none" maxlength="1" />
    </div>

    <!-- Error Message -->
    <p class="text-red-600 text-xs mt-2 hidden" id="otp-error">Invalid OTP. Please try again.</p>

    <p class="text-xs text-gray-600 mt-2">
        Didn't receive the email? 
        <a href="#" class="text-blue-600 hover:underline pointer-events-none text-gray-400" id="resend-otp">
            Resend OTP (<span id="countdown">60</span>s)
        </a>
    </p>

    <button type="button" id="verify-otp" class="w-full mt-4 shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
        Verify OTP
    </button>


</div>


    <div id="valid-section" class="hidden mt-6 text-center">
      <!-- Success GIF -->
      <img src="../src/green_check.png" alt="Success" class="mx-auto w-20 h-20 mb-4 animate-bounce">

      <!-- Success Message -->
      <h2 class="text-2xl font-bold text-green-600 mb-2">Your account has been created!</h2>
      <p class="text-gray-700 mb-6">Redirecting to login in <span id="countdown">10</span> seconds...</p>

      <!-- Login Button -->
      <button type="button" id="redirect-login" class="w-full mt-4 shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
        Go to login
      </button>
    </div>


  </div>

  
</div>


  </div>
</div>




<script>
document.addEventListener("DOMContentLoaded", () => {
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyButton = document.getElementById("verify-otp");
    const errorMessage = document.getElementById("otp-error");
    const resendBtn = document.getElementById("resend-otp");
    const registerBtn = document.getElementById("register-btn");
    const otpSection = document.getElementById("otp-section");
    const validSection = document.getElementById("valid-section");
    const countdownElement = document.getElementById('countdown');

    let countdown = 60;
    let timer;

    function startValidCountdown() {
  const validSection = document.getElementById('valid-section');
  const countdownElement = document.getElementById('countdown');

  if (validSection.style.display === 'block') {                                                                                             
    let seconds = 5;
    countdownElement.textContent = seconds;

    const countdownInterval = setInterval(() => {
      seconds--;
      countdownElement.textContent = seconds;

      if (seconds <= 0) {
        clearInterval(countdownInterval);
        window.location.href = 'login.php';
      }
    }, 1000);
  }
}

// Redirect button event
document.getElementById('redirect-login').addEventListener('click', () => {
  window.location.href = 'login.php';
});



    function startCountdown() {
        clearInterval(timer);
        countdown = 60;
        resendBtn.textContent = `Resend OTP (${countdown}s)`;
        resendBtn.classList.add("pointer-events-none", "text-gray-400");

        timer = setInterval(() => {
            countdown--;
            resendBtn.textContent = `Resend OTP (${countdown}s)`;

            if (countdown <= 0) {
                clearInterval(timer);
                resendBtn.textContent = "Resend OTP";
                resendBtn.classList.remove("pointer-events-none", "text-gray-400");
            }
        }, 1000);
    }

    registerBtn?.addEventListener("click", () => {
        const emailInput = document.getElementById("email");
        if (!emailInput) {
            console.error("Missing email input field");
            return;
        }

        const email = emailInput.value;

        fetch("../api/generate_otp.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                console.log("OTP generated successfully");
            } else {
                console.error("Failed to generate OTP:", data.message);
            }
        })
        .catch(error => console.error("Error generating OTP:", error));

        startCountdown();
    });

    resendBtn?.addEventListener("click", (event) => {
        event.preventDefault();

        const emailInput = document.getElementById("email");
        if (!emailInput) {
            console.error("Missing email input field");
            return;
        }

        const email = emailInput.value;

        fetch("../api/generate_otp.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                console.log("OTP generated successfully");
            } else {
                console.error("Failed to generate OTP:", data.message);
            }
        })
        .catch(error => console.error("Error generating OTP:", error));

        startCountdown();
    });

    otpInputs.forEach((input, index) => {
    input.addEventListener("input", function () {
        // Remove non-digit characters
        this.value = this.value.replace(/\D/g, '');

        // Clear error message when typing
        errorMessage.classList.add("hidden");

        // Auto-focus on the next input if a digit is entered
        if (this.value.length === 1 && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
        }
    });

    input.addEventListener("keydown", function (event) {
        // Move focus to the previous input on backspace
        if (event.key === "Backspace" && this.value === '') {
            if (index > 0) otpInputs[index - 1].focus();
        }
    });

    input.addEventListener("paste", function (event) {
        // Handle pasting OTP digits
        event.preventDefault();
        const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        pasted.split('').forEach((char, i) => {
            if (otpInputs[i]) otpInputs[i].value = char;
        });
        const nextEmpty = [...otpInputs].find(input => input.value === '');
        if (nextEmpty) nextEmpty.focus();

        // Clear error when pasting
        errorMessage.classList.add("hidden");
    });
});


verifyButton?.addEventListener("click", () => {
    const otpInput = Array.from(otpInputs).map(input => input.value).join('');
    if (!otpInput || otpInput.length !== 6) {
        errorMessage.textContent = "OTP input is missing or incomplete.";
        errorMessage.classList.remove("hidden");
        console.warn("⚠️ OTP input is incomplete or missing.");
        return;
    }

    fetch("../api/verify_otp.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `otp=${encodeURIComponent(otpInput)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            console.log("✅ OTP is valid. Proceeding with registration...");
            errorMessage.classList.add("hidden");

            const formData = new URLSearchParams();
            const fields = ["first_name", "last_name", "mobile", "email", "password", "confirm_password", "agree"];

            fields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    formData.append(field, field === "agree" ? (element.checked ? "on" : "") : element.value);
                } else {
                    console.warn(`⚠️ Missing field: ${field}`);
                }
            });

            fetch("../api/add_user.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(regData => {
                if (regData.status === "success") {
                    console.log("🎉 User registered successfully!");

                    otpSection.classList.add('fade-out');
                        setTimeout(() => {
                            otpSection.style.display = "none";
                            otpSection.classList.remove('fade-out');
                            validSection.style.display = "block";
                            validSection.classList.add('fade-in');
                            startValidCountdown();
                        }, 500);
                } else {
                    errorMessage.textContent = regData.message;
                    errorMessage.classList.remove("hidden");
                    console.error("❌ Registration failed:", regData.message);
                }
            })
            .catch(err => {
                errorMessage.textContent = "Error submitting registration. Please try again.";
                errorMessage.classList.remove("hidden");
                console.error("❌ Error submitting registration:", err);
            });
        } else {
            errorMessage.textContent = "Invalid OTP. Please try again.";
            errorMessage.classList.remove("hidden");
            console.warn("⚠️ Invalid OTP:", data.message);
        }
    })
    .catch(error => {
        errorMessage.textContent = "Error verifying OTP. Please try again.";
        errorMessage.classList.remove("hidden");
        console.error("❌ Error verifying OTP:", error);
    });
});


});


</script>






<script>
    // Back Button Click Event
    document.getElementById('back-button').addEventListener('click', () => {
        document.getElementById('otp-section').classList.add('hidden');
        document.getElementById('register-form').classList.remove('hidden');
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const mobileInput = document.getElementById("mobile");

    mobileInput.addEventListener("input", function (e) {
        let value = this.value.replace(/\D/g, ""); // Remove non-numeric characters

        if (value.length > 10) {
            value = value.slice(0, 10); // Ensure max 10 digits
        }

        // Format input as "000 000 0000"
        let formattedValue = value.replace(/(\d{3})(\d{3})(\d{4})/, "$1 $2 $3");

        this.value = formattedValue;
    });
});
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".password-toggle");

    toggles.forEach((toggle) => {
      toggle.addEventListener("click", function () {
        // Get the associated input field
        const input = this.previousElementSibling;
        if (input && input.type === "password") {
          input.type = "text"; // Show password
          this.setAttribute("fill", "#555"); // Darker color to indicate it's visible
          this.setAttribute("stroke", "#555");
        } else {
          input.type = "password"; // Hide password
          this.setAttribute("fill", "#bbb"); // Reset color
          this.setAttribute("stroke", "#bbb");
        }
      });
    });
  });
</script>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const firstNameInput = document.querySelector('input[name="first_name"]');
    const lastNameInput = document.querySelector('input[name="last_name"]');
    const mobileInput = document.querySelector('input[name="mobile"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    const registerButton = document.getElementById("register-btn");
    const passwordRequirements = document.getElementById("password-requirements");
    const emailRequirements = document.getElementById("email-requirements");
    const errorDiv = document.getElementById("error-message"); 
    const registerForm = document.getElementById("register-form");
    const otpSection = document.getElementById("otp-section");
    const otpMessage = document.getElementById("otp-message");
    const backButton = document.getElementById("back-button"); // Added back button reference

    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    let emailIsValid = false;
    let debounceTimeout;

    emailInput.addEventListener("input", function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(async function () {
            const email = emailInput.value.trim();
            if (email === "") {
                emailRequirements.innerHTML = "";
                emailInput.style.borderBottom = "1px solid #ccc";
                emailIsValid = false;
                return;
            }

            if (!validateEmail(email)) {
                emailRequirements.innerHTML = "Invalid email format (example: user@example.com)";
                emailRequirements.style.color = "red";
                emailInput.style.borderBottom = "1px solid red";
                emailIsValid = false;
                return;
            }

            try {
                const response = await fetch(`../api/check_email.php?email=${encodeURIComponent(email)}`);
                const result = await response.json();

                if (result.exists) {
                    emailRequirements.innerHTML = "Email is already in use.";
                    emailRequirements.style.color = "red";
                    emailInput.style.borderBottom = "1px solid red";
                    emailIsValid = false;
                } else {
                    emailRequirements.innerHTML = "Email is valid.";
                    emailRequirements.style.color = "green";
                    emailInput.style.borderBottom = "1px solid green";
                    emailIsValid = true;
                }
            } catch (error) {
                console.error("Error checking email:", error);
                emailIsValid = false;
            }
        }, 300);
    });

    function validatePassword() {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        let messages = [];
        let isValid = true;

        if (password.length < 8) {
            messages.push("Password must be at least 8 characters long.");
            isValid = false;
        }
        if (!/[A-Z]/.test(password)) {
            messages.push("Password must contain at least one uppercase letter.");
            isValid = false;
        }
        if (!/[a-z]/.test(password)) {
            messages.push("Password must contain at least one lowercase letter.");
            isValid = false;
        }
        if (!/\d/.test(password)) {
            messages.push("Password must contain at least one number.");
            isValid = false;
        }
        if (!/[\W_]/.test(password)) {
            messages.push("Password must contain at least one special character.");
            isValid = false;
        }
        if (password !== confirmPassword && confirmPassword.length > 0) {
            messages.push("Passwords do not match.");
            isValid = false;
        }

        if (!isValid) {
            passwordRequirements.innerHTML = messages.join("<br>");
            passwordRequirements.style.color = "red";
            passwordInput.style.border = "1px solid red";
            confirmPasswordInput.style.border = "1px solid red";
        } else {
            passwordRequirements.innerHTML = "Password meets all requirements!";
            passwordRequirements.style.color = "green";
            passwordInput.style.border = "1px solid green";
            confirmPasswordInput.style.border = "1px solid green";
        }

        return isValid;
    }

    function validateForm() {
    let isValid = true;
    let emptyFields = false;
    const agreeCheckbox = document.getElementById("agree");

    // Checkbox validation
    if (!agreeCheckbox.checked) {
        errorDiv.innerHTML = `<span class="font-medium">Error:</span> You must agree to the terms and conditions.`;
        errorDiv.style.display = "block";
        agreeCheckbox.classList.add("border-red-500");
        isValid = false;
    } else {
        errorDiv.style.display = "none";
        agreeCheckbox.classList.remove("border-red-500");
    }

    // Field validation
    [firstNameInput, lastNameInput, mobileInput].forEach(input => {
        if (input.value.trim() === "") {
            emptyFields = true;
            input.style.border = "1px solid red";
        } else {
            input.style.border = "";
        }
    });

    if (emptyFields) {
        errorDiv.innerHTML = `<span class="font-medium">Error:</span> Please fill in the required fields.`;
        errorDiv.style.display = "block";
        isValid = false;
    } else {
        errorDiv.style.display = "none";
    }

    if (!validatePassword()) {
        isValid = false;
    }

    // Check both format and availability
    if (!validateEmail(emailInput.value.trim()) || !emailIsValid) {
        emailRequirements.innerHTML = "Invalid or already used email.";
        emailRequirements.style.color = "red";
        emailInput.style.borderBottom = "1px solid red";
        isValid = false;
    }

    return isValid;
}

// Handle Register Button Click
registerButton.addEventListener("click", function (event) {
    event.preventDefault();

    if (!validateForm()) {
        return;
    }

    const userEmail = emailInput.value.trim();
    otpMessage.innerHTML = `An OTP was sent to <strong>${userEmail}</strong>.`;

    // Animate out the register form
    registerForm.classList.add('fade-out');
    setTimeout(() => {
        registerForm.style.display = "none";
        registerForm.classList.remove('fade-out');
        otpSection.style.display = "block";
        otpSection.classList.add('fade-in');
    }, 500);
});

// Handle Back Button Click
backButton.addEventListener("click", function () {
    // Animate out the OTP section
    otpSection.classList.add('fade-out');
    setTimeout(() => {
        otpSection.style.display = "none";
        otpSection.classList.remove('fade-out');
        registerForm.style.display = "block";
        registerForm.classList.add('fade-in');
    }, 500);
});


    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", validatePassword);

    [firstNameInput, lastNameInput, mobileInput].forEach(input => {
        input.addEventListener("input", function () {
            this.style.border = "";
            errorDiv.style.display = "none";
        });
    });
});
</script>





</body>
</html>