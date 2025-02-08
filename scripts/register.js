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
    const form = document.querySelector('form');

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
                    alert('OTP has been sent to your email.');
                } else {
                    alert('Failed to send OTP. Please try again.');
                    console.error('Error:', data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while sending OTP. Please try again.');
            }
        } else {
            emailInput.classList.add('border-red-500');
            emailError.textContent = "Please enter a valid email address.";
            emailError.classList.remove('hidden');
        }
    });

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