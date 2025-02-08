document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email_input');
    const emailError = emailInput.nextElementSibling;
    const passwordInput = document.getElementById('password_input');
    const passwordError = passwordInput.nextElementSibling;
    const credentialsError = document.getElementById('credentials_error');
    const form = document.querySelector('form');

    emailInput.addEventListener('input', function () {
        if (validateEmail(emailInput.value)) {
            emailInput.classList.remove('border-red-500');
            emailError.classList.add('hidden');
        } else {
            emailInput.classList.add('border-red-500');
            emailError.textContent = "Please enter a valid email address ending with @gmail.com.";
            emailError.classList.remove('hidden');
            emailError.classList.add('text-red-500');
        }
    });

    passwordInput.addEventListener('input', function () {
        if (passwordInput.value.length >= 8) {
            passwordInput.classList.remove('border-red-500');
            passwordError.classList.add('hidden');
        } else {
            passwordInput.classList.add('border-red-500');
            passwordError.textContent = "Password must be at least 8 characters.";
            passwordError.classList.remove('hidden');
            passwordError.classList.add('text-red-500');
        }
    });

    form.addEventListener('submit', function (event) {
        let hasErrors = false;

        if (!validateEmail(emailInput.value)) {
            emailInput.classList.add('border-red-500');
            emailError.classList.remove('hidden');
            emailError.classList.add('text-red-500');
            hasErrors = true;
        }

        if (passwordInput.value.length < 8) {
            passwordInput.classList.add('border-red-500');
            passwordError.classList.remove('hidden');
            passwordError.classList.add('text-red-500');
            hasErrors = true;
        }

        if (hasErrors) {
            event.preventDefault();
            credentialsError.classList.remove('hidden');
        } else {
            credentialsError.classList.add('hidden');
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@gmail\.com$/;
        return re.test(String(email).toLowerCase());
    }
});
