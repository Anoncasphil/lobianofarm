<?php
// Retrieve query parameters (check-in date) from URL
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : null;

// Validate the check-in date
if (!$checkin || !strtotime($checkin)) {
    header("HTTP/1.1 404 Not Found");
    header("Location: 404.php");
    exit();
}

// Convert the check-in date to a readable format
$checkinDate = date('F j, Y', strtotime($checkin));
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

    <!-- Display selected check-in date -->
    <div class="mt-8 text-lg text-center">
      <div>
        <strong>Check-in:</strong> <span id="display-checkin"><?php echo htmlspecialchars($checkinDate); ?></span>
      </div>
    </div>

    <div class="mt-8 space-y-4 w-80">
      <!-- Input fields for name and email -->
      <div class="relative">
        <input
          type="text"
          id="guest-name"
          placeholder="Enter your name"
          class="px-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
      </div>
      <div class="relative">
        <input
          type="email"
          id="guest-email"
          placeholder="Enter your email"
          class="px-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
      </div>

      <!-- Submit Button -->
      <button
        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        onclick="submitBooking()"
      >
        Submit Booking
      </button>
    </div>
  </section>

  <script>
    // Submit booking function
    function submitBooking() {
      const name = document.getElementById('guest-name').value.trim();
      const email = document.getElementById('guest-email').value.trim();

      // Validate input fields
      if (!name || !email) {
        alert("Please fill in your name and email.");
        return;
      }

      // Example action: Alert the user
      alert(`Booking submitted for ${name} (${email}).\nCheck-in date: ${document.getElementById('display-checkin').textContent}`);
    }
  </script>

</body>
</html>
