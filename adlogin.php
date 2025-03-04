<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Login</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600">

    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-lg">
        <h2 class="text-gray-800 text-center text-3xl font-extrabold">Admin Login</h2>
        
        <form method="POST" action="admin-login.php" class="mt-6 space-y-4">
            
            <!-- Email Field -->
            <div>
                <label class="text-gray-700 text-sm font-semibold">Email</label>
                <div class="relative mt-1">
                    <input name="email" type="email" required 
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-4 top-3 w-5 h-5 text-gray-400" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 12.713L0 5.25V18h24V5.25l-12 7.463zM12 10l11.736-7H.264L12 10z"/>
                    </svg>
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label class="text-gray-700 text-sm font-semibold">Password</label>
                <div class="relative mt-1">
                    <input id="password" name="password" type="password" required 
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
                    <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg" class="absolute right-4 top-3 w-5 h-5 text-gray-400 cursor-pointer" viewBox="0 0 128 128">
                        <path fill="currentColor" d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"/>
                    </svg>
                </div>
            </div>

            <!-- Sign In Button -->
            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-3 text-white font-bold rounded-lg bg-blue-900 hover:bg-blue-700 transition duration-300">
                    Sign in
                </button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <?php if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])): ?>
        <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6">
                <h3 class="text-red-500 text-xl"><?= htmlspecialchars($_SESSION['error_message']) ?></h3>
                <button id="closeModal" class="mt-4 px-4 py-2 bg-red-500 text-white rounded-lg">Close</button>
            </div>
        </div>

        <?php
        // Clear the error message after displaying it
        unset($_SESSION['error_message']);
        ?>
    <?php endif; ?>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        });

        // Close the modal when clicking the close button
        if (document.getElementById('closeModal')) {
            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('errorModal').style.display = 'none';
            });
        }
    </script>

</body>
</html>
