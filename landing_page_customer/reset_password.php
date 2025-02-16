<?php
session_start();
if (!isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php'); // Redirect if no session
    exit;
}
$email = $_SESSION['reset_email'];
?>

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

                        <!-- New Password Section -->
            <div id="new-password-section" class="mt-6">
                <h3 class="text-gray-800 text-2xl font-extrabold">Set a New Password</h3>
                <p class="text-sm text-gray-800">Create a strong password to secure your account.</p>
                
                <div id="error-message" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 hidden" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 1 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Error</span>
                   <div>
                      <span class="font-medium">Error: </span> Something went wrong. Please check the form.
                  </div>
                </div>
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

            <div id="valid-section" class="hidden mt-6 text-center">
               <!-- Success GIF -->
              <img src="../src/green_check.png" alt="Success" class="mx-auto w-20 h-20 mb-4 animate-bounce">

              <!-- Success Message -->
              <h2 class="text-2xl font-bold text-green-600 mb-2">Your account password has been reset successfully.</h2>
              <p class="text-gray-700 mb-6">Redirecting to login in <span id="countdown">5</span> seconds...</p>

              <!-- Login Button -->
              <button type="button" id="redirect-login" class="w-full mt-4 shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
                Go to login
              </button>
            </div>

        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
  const toggles = document.querySelectorAll(".password-toggle");
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm_password");
  const passwordRequirements = document.getElementById("password-requirements");
  const errorMessage = document.getElementById("error-message");
  const submitButton = document.getElementById("submit-password");
  const validSection = document.getElementById("valid-section");
  const newPasswordSection = document.getElementById("new-password-section");
  const countdownElement = document.getElementById("countdown");
  const redirectButton = document.getElementById("redirect-login");

  function showError(message) {
    errorMessage.classList.remove("hidden");
    errorMessage.querySelector("div").innerHTML = `<span class="font-medium">Error: </span> ${message}`;
  }

  function hideError() {
    errorMessage.classList.add("hidden");
  }

  function validatePassword() {
    const password = passwordInput.value.trim();
    const confirmPassword = confirmPasswordInput.value.trim();
    let messages = [];
    let isValid = true;

    if (password.length < 8) {
      messages.push("Password must be at least 8 characters long.");
    }
    if (!/[A-Z]/.test(password)) {
      messages.push("Password must contain at least one uppercase letter.");
    }
    if (!/[a-z]/.test(password)) {
      messages.push("Password must contain at least one lowercase letter.");
    }
    if (!/\d/.test(password)) {
      messages.push("Password must contain at least one number.");
    }
    if (!/[\W_]/.test(password)) {
      messages.push("Password must contain at least one special character.");
    }
    if (password !== confirmPassword && confirmPassword.length > 0) {
      messages.push("Passwords do not match.");
    }

    if (messages.length > 0) {
      passwordRequirements.innerHTML = messages.join("<br>");
      passwordRequirements.style.color = "red";
    } else {
      hideError();
      passwordRequirements.innerHTML = "Password meets all requirements!";
      passwordRequirements.style.color = "green";
    }
  }

  passwordInput.addEventListener("input", validatePassword);
  confirmPasswordInput.addEventListener("input", validatePassword);

  function showSuccess() {
    if (passwordInput.value.trim() === "" || confirmPasswordInput.value.trim() === "") {
      showError("Please fill in all fields.");
      return;
    }

    hideError();
    newPasswordSection.classList.add("hidden");
    validSection.classList.remove("hidden");
    validSection.classList.add("fade-in");

    const formData = new FormData();
    formData.append('new_password', passwordInput.value);
    formData.append('confirm_password', confirmPasswordInput.value);

    fetch("../api/reset_password_process.php", { 
      method: "POST", 
      body: formData 
    })
    .then(response => response.text())
    .then(data => {
      if (data.includes("Password reset successfully")) {
        let timeLeft = 5;
        const countdownInterval = setInterval(() => {
          countdownElement.textContent = timeLeft;
          timeLeft--;
          if (timeLeft < 0) {
            clearInterval(countdownInterval);
            window.location.href = "login.php";
          }
        }, 1000);
      } else {
        showError(data);
      }
    })
    .catch(err => {
      showError("An error occurred while resetting your password.");
    });
  }

  submitButton.addEventListener("click", showSuccess);
  redirectButton.addEventListener("click", () => window.location.href = "login.php");
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



</body>
</html>