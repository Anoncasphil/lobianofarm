<?php
// Retrieve query parameters (check-in and check-out dates) from URL
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : null;
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : null;

// If the dates are not provided, redirect to 404 error page
if (!$checkin || !$checkout) {
    header("HTTP/1.1 404 Not Found");
    header("Location: 404.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Page</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">

  <section id="booking" class="min-h-screen flex flex-col justify-center items-center pt-16">
    <h1 class="text-4xl font-extrabold">Booking Page</h1>

    <!-- Display selected check-in and check-out dates -->
    <div class="mt-8 text-lg">
      <div>
        <strong>Check-in:</strong> <span id="display-checkin"><?php echo date('F j', strtotime($checkin)); ?></span>
      </div>
      <div>
        <strong>Check-out:</strong> <span id="display-checkout"><?php echo date('F j', strtotime($checkout)); ?></span>
      </div>
    </div>

    <div class="mt-8 space-y-4">
      <div class="relative">
        <input type="text" id="guest-name" placeholder="Enter your name" class="px-4 py-2 border rounded-lg w-full" />
      </div>
      <div class="relative">
        <input type="email" id="guest-email" placeholder="Enter your email" class="px-4 py-2 border rounded-lg w-full" />
      </div>

      <!-- Submit Button -->
      <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="submitBooking()">Submit Booking</button>
    </div>
  </section>

  <script>
    // Submit booking function
    function submitBooking() {
      const name = document.getElementById('guest-name').value;
      const email = document.getElementById('guest-email').value;

      if (name && email) {
        alert(`Booking submitted for ${name} (${email})`);
      } else {
        alert("Please fill in your name and email.");
      }
    }
  </script>

</body>
</html>
