document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email_input');
    const emailError = emailInput.nextElementSibling;
    const firstnameInput = document.getElementById('firstname_input');
    const firstnameError = firstnameInput.nextElementSibling;
    const lastnameInput = document.getElementById('lastname_input');
    const lastnameError = lastnameInput.nextElementSibling;
    const contactInput = document.getElementById('contact_input');
    const contactError = contactInput.nextElementSibling;
    const passwordInput = document.getElementById('password_input');
    const passwordError = passwordInput.nextElementSibling;
    const verifyPasswordInput = document.getElementById('verify_password_input');
    const verifyPasswordError = verifyPasswordInput.nextElementSibling;
    const otpInput = document.getElementById('otp_input');
    const otpError = otpInput.nextElementSibling;
    const sendOtpButton = document.getElementById('send_otp_button');
    const otpTimer = document.getElementById('timer');
    const form = document.querySelector('form');

    let timerInterval;

    sendOtpButton.addEventListener('click', async function() {
        if (validateEmail(emailInput.value)) {
            try {
                const response = await fetch('send_otp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(emailInput.value)}`
                });
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('success-message').innerHTML = '<i class="start-icon far fa-check-circle faa-tada animated"></i><strong class="font__weight-semibold">Success!</strong> An OTP has been sent to your email.';
                    document.getElementById('success-message').classList.remove('hidden');
                    document.getElementById('error-message').classList.add('hidden');
                    setTimeout(() => {
                        document.getElementById('success-message').classList.add('hidden');
                    }, 3000);
                    startOtpTimer();
                } else {
                    document.getElementById('error-message').innerHTML = '<i class="start-icon far fa-times-circle faa-pulse animated"></i><strong class="font__weight-semibold">Oh snap!</strong> Failed to send OTP. Please try again.';
                    document.getElementById('error-message').classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('error-message').classList.add('hidden');
                    }, 3000);
                    console.error('Error:', data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('error-message').innerHTML = '<i class="start-icon far fa-times-circle faa-pulse animated"></i><strong class="font__weight-semibold">Oh snap!</strong> An error occurred while sending OTP. Please try again.';
                document.getElementById('error-message').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('error-message').classList.add('hidden');
                }, 3000);
            }
        } else {
            emailInput.classList.add('border-red-500');
            emailError.textContent = "Please enter a valid email address.";
            emailError.classList.remove('hidden');
        }
    });

    function startOtpTimer() {
        let timeLeft = 60;
        otpTimer.textContent = `Time left: ${timeLeft}s`;
        otpTimer.style.display = 'inline';

        clearInterval(timerInterval);
        timerInterval = setInterval(async () => {
            timeLeft--;
            otpTimer.textContent = `Time left: ${timeLeft}s`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                otpTimer.style.display = 'none';
                document.getElementById('error-message').innerHTML = '<i class="start-icon far fa-times-circle faa-pulse animated"></i><strong class="font__weight-semibold">Oh snap!</strong> OTP expired. Please try again.';
                document.getElementById('error-message').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('error-message').classList.add('hidden');
                }, 3000);
                await removeExpiredOtp(emailInput.value);
            }
        }, 1000);
    }

    async function removeExpiredOtp(email) {
        try {
            const response = await fetch('remove_expired_otp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}`
            });
            const data = await response.json();
            if (!data.success) {
                console.error('Failed to remove expired OTP:', data.error);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    emailInput.addEventListener('input', async function() {
        if (validateEmail(emailInput.value)) {
            try {
                const response = await fetch('check_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(emailInput.value)}`
                });
                const data = await response.json();
                
                if (data.exists) {
                    emailInput.classList.add('border-red-500');
                    emailError.textContent = "Email is already existing.";
                    emailError.classList.remove('hidden');
                } else {
                    emailInput.classList.remove('border-red-500');
                    emailError.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            emailInput.classList.add('border-red-500');
            emailError.textContent = "Please enter a valid email address.";
            emailError.classList.remove('hidden');
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

    form.addEventListener('submit', async function (event) {
        event.preventDefault(); // Prevent default form submission

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

        if (!hasErrors) {
            try {
                const formData = new FormData(form);
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('success-message').innerHTML = '<i class="start-icon far fa-check-circle faa-tada animated"></i><strong class="font__weight-semibold">Congratulations!</strong> You have successfully registered your account.';
                    document.getElementById('success-message').classList.remove('hidden');
                    document.getElementById('error-message').classList.add('hidden');
                    setTimeout(() => {
                        document.getElementById('success-message').classList.add('hidden');
                        window.location.href = 'login.php';
                    }, 3000);
                } else {
                    document.getElementById('error-message').innerHTML = '<i class="start-icon far fa-times-circle faa-pulse animated"></i><strong class="font__weight-semibold">Oh snap!</strong> ' + data.error;
                    document.getElementById('error-message').classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('error-message').classList.add('hidden');
                    }, 3000);
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('error-message').innerHTML = '<i class="start-icon far fa-times-circle faa-pulse animated"></i><strong class="font__weight-semibold">Oh snap!</strong> An error occurred during registration. Please try again.';
                document.getElementById('error-message').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('error-message').classList.add('hidden');
                }, 3000);
            }
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
});

document.getElementById("contact_input").addEventListener("input", function (event) {
    let input = this.value.replace(/\D/g, ""); // Remove non-numeric characters
    this.value = input.slice(0, 11); // Restrict to max 11 digits

    // Show error if input is less than 11 digits
    const errorMessage = document.getElementById("contact_error");
    if (this.value.length < 11) {
        errorMessage.classList.remove("hidden");
    } else {
        errorMessage.classList.add("hidden");
    }
});

document.getElementById("contact_input").addEventListener("keydown", function (event) {
    // Prevent entering 'e', 'E', '+', and '-'
    if (event.key === "e" || event.key === "E" || event.key === "+" || event.key === "-") {
        event.preventDefault();
    }
});