<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Screen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">

    <!-- Loading Screen -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
        <!-- Spinner -->
        <div class="animate-spin rounded-full h-24 w-24 border-t-4 border-blue-500 border-solid"></div>
    </div>

    <div class="flex justify-center items-center">
        <h1 class="text-2xl text-gray-800 font-semibold">Page Content Here</h1>
    </div>

    <script>
        // Simulating a loading process
        setTimeout(() => {
            document.querySelector('div').style.display = 'none'; // Hide the loading screen after 3 seconds
        }, 3000);
    </script>
</body>

</html>
