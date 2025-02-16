<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>

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


<body>

<div class="font-[sans-serif] flex items-center justify-center min-h-screen px-4">
    <div class="max-w-md w-full p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] rounded-md">
        <div class="w-full px-4 py-4">
            <!-- Forgot Password Section -->
            <div id="forgot-password-section" class="mt-6">
                <!-- Back Button + Title -->
                <div class="flex items-center space-x-2 mb-4">
                    <a href="login.php" id="back-button-forgot" class="flex items-center text-blue-600 hover:text-blue-700 transition duration-200">
                        <span class="material-icons text-sm text-blue-900 transition duration-200">arrow_back_ios</span>
                        <span class="text-sm font-medium text-blue-900 transition duration-200">Back</span>
                    </a>
                </div>

                <h3 class="text-gray-800 text-2xl font-extrabold mb-5">Forgot Password</h3>
                <p class="text-sm text-gray-800" id="forgot-password-message">
                    Please enter your email address below. We will send you a code to reset your password.
                </p>

                <div class="mt-3 mb-3">
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

                <!-- Error Message -->
                <p class="text-red-600 text-xs mt-2 hidden" id="forgot-password-error">Invalid email address. Please try again.</p>

                <button 
                    type="button" 
                    id="send-reset-code" 
                    class="w-full mt-4 shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
                    Send Reset Code
                </button>
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
                <h3 class="text-gray-800 text-2xl font-extrabold">Forgot Password</h3>
                <p class="text-sm text-gray-800" id="otp-message">
                    An OTP has been sent to your email. Please enter the 6-digit code below to reset your password.
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

                        <!-- New Password Section -->
            <div id="new-password-section" class="hidden mt-6">
                <h3 class="text-gray-800 text-2xl font-extrabold">Set a New Password</h3>
                <p class="text-sm text-gray-800">Create a strong password to secure your account.</p>

                <!-- Password Field -->
                <div class="mt-3">
                    <label class="text-gray-800 text-xs block mb-1">New Password</label>
                    <div class="relative flex items-center">
                        <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 pl-2 pr-8 outline-none" placeholder="Enter new password" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2 cursor-pointer password-toggle" viewBox="0 0 128 128">
                            <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"></path>
                        </svg>
                    </div>
                    <div id="password-requirements" class="text-xs mt-1 text-red-500"></div>
                </div>

                <!-- Confirm Password Field -->
                <div class="mt-3">
                    <label class="text-gray-800 text-xs block mb-1">Confirm Password</label>
                    <div class="relative flex items-center">
                        <input id="confirm_password" name="confirm_password" type="password" required class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 pl-2 pr-8 outline-none" placeholder="Confirm new password" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2 cursor-pointer password-toggle" viewBox="0 0 128 128">
                            <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"></path>
                        </svg>
                    </div>
                    <div id="confirm-password-error" class="text-xs mt-1 text-red-500 hidden">Passwords do not match.</div>
                </div>

                <!-- Submit Button -->
                <button type="button" id="submit-password" class="w-full mt-5 shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
                    Submit
                </button>
            </div>

        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const emailError = document.getElementById("email-requirements");
    const sendResetCodeBtn = document.getElementById("send-reset-code");
    const forgotPasswordSection = document.getElementById("forgot-password-section");
    const otpSection = document.getElementById("otp-section");
    const backButton = document.getElementById("back-button");

    const otpInputs = document.querySelectorAll(".otp-input");
    const otpError = document.getElementById("otp-error");
    const verifyOtpBtn = document.getElementById("verify-otp");
    const resendBtn = document.getElementById("resend-otp");
    const countdownSpan = document.getElementById("countdown");

    let countdown = 60;
    let timer;

// Send Reset Code
sendResetCodeBtn.addEventListener("click", async function () {
    const email = emailInput.value.trim();
    startCountdown();

    if (!email) {
        emailError.textContent = "Email field cannot be empty.";
        emailError.style.color = "red";
        return;
    }

    // Show OTP Section Immediately
    forgotPasswordSection.classList.add("fade-out");
    setTimeout(() => {
        forgotPasswordSection.classList.add("hidden");
        forgotPasswordSection.classList.remove("fade-out");

        otpSection.classList.remove("hidden");
        otpSection.classList.add("fade-in");
    }, 500);

    // Send OTP Request
    try {
        const response = await fetch("../api/otp_forgot_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();
        console.log("Server Response:", data);

        if (data.status === "success") {
            emailError.style.color = "green";
            emailError.textContent = data.message; 
        } else {
            emailError.style.color = "red";
            emailError.textContent = data.message || "Failed to send OTP.";
        }
    } catch (error) {
        console.error("Error:", error);
        emailError.style.color = "red";
        emailError.textContent = "Something went wrong. Please try again.";
    }
});







    // Back to Forgot Password
    backButton.addEventListener("click", function () {
        otpSection.classList.add("fade-out");
        setTimeout(() => {
            otpSection.classList.add("hidden");
            otpSection.classList.remove("fade-out");

            forgotPasswordSection.classList.remove("hidden");
            forgotPasswordSection.classList.add("fade-in");
        }, 500);
    });

    // OTP Input Navigation
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    // Show/Hide Error Message
    function showError(message) {
        otpError.textContent = message;
        otpError.classList.remove("hidden");
        otpInputs.forEach(input => input.classList.add("border-red-500"));
    }

    function hideError() {
        otpError.classList.add("hidden");
        otpInputs.forEach(input => input.classList.remove("border-red-500"));
    }

    // Verify OTP
    verifyOtpBtn.addEventListener("click", async () => {
        hideError();
        const enteredOtp = Array.from(otpInputs).map(input => input.value).join("");

        if (enteredOtp.length < 6) {
            showError("Please enter all 6 digits.");
            return;
        }

        try {
            let response = await fetch("../api/otp_verify_fp.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: emailInput.value, otp: enteredOtp })
            });

            let data = await response.json();

            if (data.success) {
                window.location.href = "reset_password.php";
            } else {
                showError(data.message || "Invalid OTP. Please try again.");
            }
        } catch (error) {
            console.error("Error:", error);
            showError("Something went wrong. Please try again.");
        }
    });


function startCountdown() {
    clearInterval(timer); // Clear any previous interval
    countdown = 60; // Reset countdown
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

resendBtn?.addEventListener("click", async (event) => {
    event.preventDefault();
    startCountdown(); // Restart countdown

    const emailInput = document.getElementById("email");
    if (!emailInput) {
        console.error("Missing email input field");
        return;
    }

    const email = emailInput.value;

    try {
        let response = await fetch("../api/generate_otp.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}`
        });

        let data = await response.json();

        if (data.status === "success") {
            console.log("OTP generated successfully");
        } else {
            console.error("Failed to generate OTP:", data.message);
        }
    } catch (error) {
        console.error("Error generating OTP:", error);
    }
});                                                         


});


</script>




</body>
</html>