document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const rememberMeCheckbox = document.getElementById('remember-me');
    const form = document.querySelector('form');
    const errorMessage = document.getElementById('error-message');
    const signInBtn = form.querySelector('button[type="button"]');
    const passwordToggle = form.querySelector('.relative .cursor-pointer');

    // Populate fields if data exists in localStorage
    if (localStorage.getItem('rememberedEmail') && localStorage.getItem('rememberedPassword')) {
        emailInput.value = localStorage.getItem('rememberedEmail');
        passwordInput.value = localStorage.getItem('rememberedPassword');
        rememberMeCheckbox.checked = true;
    }

    // Handle login button click
    signInBtn.addEventListener('click', () => {
        const email = emailInput.value;
        const password = passwordInput.value;
        const remember = rememberMeCheckbox.checked;

        // Remember credentials if checked
        if (remember) {
            localStorage.setItem('rememberedEmail', email);
            localStorage.setItem('rememberedPassword', password);
        } else {
            localStorage.removeItem('rememberedEmail');
            localStorage.removeItem('rememberedPassword');
        }
    });

    // Toggle password visibility
    passwordToggle.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.setAttribute("fill", "#555");
            passwordToggle.setAttribute("stroke", "#555");
        } else {
            passwordInput.type = 'password';
            passwordToggle.setAttribute("fill", "#bbb");
            passwordToggle.setAttribute("stroke", "#bbb");
        }
    });

    // Function to show error message
    function showError(message) {
        errorMessage.querySelector('div').innerText = `${message}`;
        errorMessage.classList.remove('hidden');
    }

    // Function to hide error message
    function hideError() {
        errorMessage.classList.add('hidden');
    }

    // Form validation and login
    signInBtn.addEventListener('click', async () => {
        hideError();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validate inputs
        if (!email || !emailPattern.test(email)) {
            showError('Please enter a valid email address.');
            return;
        }

        if (!password) {
            showError('Password cannot be empty.');
            return;
        }

        // Prepare data for API request
        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        // Send request to check user
        try {
            const response = await fetch('../api/check_user.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = '../index.php';
            } else {
                showError(result.message || 'Invalid email or password.');
            }
        } catch (error) {
            showError('An error occurred while connecting to the server.');
            console.error(error);
        }
    });
});