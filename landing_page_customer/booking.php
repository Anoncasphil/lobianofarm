<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Make sure no further code is executed
}
// Include the database connection
include('../db_connection.php'); // Adjust the path if necessary

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve user info based on user_id
$sql = "SELECT first_name, last_name FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Check if prepare() failed
if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
}

// Bind the user_id to the query
$stmt->bind_param("i", $_SESSION['user_id']);

// Execute the query
$stmt->execute();
$stmt->store_result();

// Bind the results to variables
$stmt->bind_result($first_name, $last_name);

// Check if user data is found
if ($stmt->fetch()) {
    // Combine first and last name
    $full_name = $first_name . ' ' . $last_name;
} else {
    // If user not found, handle accordingly (e.g., redirect to login)
    header("Location: login.php");
    exit;
}

$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../scripts/bookings.js" defer></script>
    <link rel="stylesheet" href="../styles/booking.css">
    <link rel="stylesheet" href="../styles/homepage.css">
    <link href="../dist/output.css" rel="stylesheet">

</head>
<body>

<!-- Navbar -->
<nav class="border-blue-200 bg-blue-900 fixed top-0 left-0 w-full z-50" data-aos="fade-down" data-aos-duration="1200">
  <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
    <!-- Logo -->
    <a href="../index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="../src/uploads/logo.svg" class="logo" alt="Logo" />
      <span class="self-center text-2xl font-semibold whitespace-nowrap text-white"></span>
    </a>

    <!-- Hamburger menu (Mobile) -->
    <button id="hamburgerButton" type="button" class="md:hidden p-2 w-10 h-10 text-white rounded-lg hover:bg-white/10">
      <span class="sr-only">Open main menu</span>
      <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
      </svg>
    </button>

    <!-- Desktop Navigation Links -->
    <ul id="navbarUser" class="hidden md:flex flex-row font-medium p-4 md:p-0 space-x-8 md:bg-blue-900">
      <li><a href="../index.php#home" class="block py-2 px-3 text-white hover:text-blue-500">Home</a></li>
      <li><a href="../index.php#services" class="block py-2 px-3 text-white hover:text-blue-500">Services</a></li>
      <li><a href="../index.php#about" class="block py-2 px-3 text-white hover:text-blue-500">About</a></li>
      <li><a href="../index.php#album" class="block py-2 px-3 text-white hover:text-blue-500">Album</a></li>
      <li><a href="../index.php#reviews" class="block py-2 px-3 text-white hover:text-blue-500">Reviews</a></li>
      <li><a href="../index.php#contact" class="block py-2 px-3 text-white hover:text-blue-500">Contact</a></li>
    </ul>

    <!-- Profile/Login (Desktop) -->
    <div class="hidden md:flex items-center space-x-6">
      <?php if (isset($full_name) && !empty($full_name)): ?>
        <div class="relative inline-block text-left">
          <button id="profileButton" type="button" class="flex items-center space-x-3 text-sm bg-blue-900 hover:bg-white/10 rounded-lg px-4 py-4">
            <span class="text-white font-medium"><?php echo htmlspecialchars($full_name); ?></span>
            <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <!-- Dropdown Menu -->
          <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden">
            <a href="customer_reservation.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Reservations</a>
            <a href="edit_profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <hr class="border-gray-300">
            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="flex items-center space-x-3 text-sm bg-white hover:bg-gray-300 text-blue-900 font-semibold rounded-lg px-6 py-3 transition-all duration-300 ease-in-out shadow-md hover:shadow-lg">
          <span class="font-semibold">Login</span>
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Mobile Dropdown -->
  <div id="mobileMenu" class="hidden md:hidden absolute top-16 left-0 w-full bg-white shadow-lg rounded-lg">
    <div class="p-4">
      <?php if (isset($full_name) && !empty($full_name)): ?>
        <p class="text-lg font-semibold text-blue-900"><?php echo htmlspecialchars($full_name); ?></p>
        <a href="customer_reservation.php" class="block py-2 text-gray-700 hover:bg-gray-200">Reservations</a>
        <hr class="border-gray-300 my-2">
      <?php endif; ?>
      <a href="../index.php#home" class="block py-2 text-gray-700 hover:bg-gray-200">Home</a>
      <a href="../index.php#services" class="block py-2 text-gray-700 hover:bg-gray-200">Services</a>
      <a href="../index.php#about" class="block py-2 text-gray-700 hover:bg-gray-200">About</a>
      <a href="../index.php#album" class="block py-2 text-gray-700 hover:bg-gray-200">Album</a>
      <a href="../index.php#reviews" class="block py-2 text-gray-700 hover:bg-gray-200">Reviews</a>
      <a href="../index.php#contact" class="block py-2 text-gray-700 hover:bg-gray-200">Contact</a>
      <hr class="border-gray-300 my-2">
      <?php if (isset($full_name) && !empty($full_name)): ?>
        <a href="logout.php" class="block py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      <?php else: ?>
        <a href="login.php" class="block py-2 text-gray-700 hover:bg-gray-200">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- JavaScript -->
<script>
  // Toggle Mobile Menu
  document.getElementById('hamburgerButton').addEventListener('click', function () {
    var mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
  });

  // Toggle Profile Dropdown (Desktop)
  document.getElementById('profileButton')?.addEventListener('click', function (event) {
    event.stopPropagation();
    document.getElementById('dropdownMenu').classList.toggle('hidden');
  });

  // Close dropdown if clicked outside (Desktop)
  document.addEventListener('click', function (event) {
    var dropdownMenu = document.getElementById('dropdownMenu');
    if (dropdownMenu && !event.target.closest('#profileButton')) {
      dropdownMenu.classList.add('hidden');
    }
  });
</script>


  <section class="bg-gray-100 pt-16 px-6 md:px-8 min-h-screen">
  <div class="max-w-screen-xl mx-auto flex gap-8 mt-10">

    <!-- 4-Column Wide Div -->
     <div class="flex-4">
      <div id="basic-details" class="flex-4 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-extrabold text-gray-700">BASIC DETAILS</h2>
        <p class="mt-2 text-gray-600">Please enter your basic details to proceed with your reservation.</p>

        <!-- First Name and Last Name -->
      <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="relative">
          <input type="text" id="first-name" class="peer font-semibold p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="firstname" required/>
          <label for="first-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
        </div>
        <div class="relative">
          <input type="text" id="last-name" class="peer p-3 pt-5 font-semibold w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="lastname" required/>
          <label for="last-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
        </div>
      </div>

      <!-- Email and Mobile Number -->
      <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="relative">
          <input type="email" id="email" class="peer p-3 pt-5 font-semibold w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="email" required>
          <label for="email" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
        </div>
        <div class="relative">
          <input type="text" id="mobile-number" class="peer p-3 pt-5 font-semibold w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="mobilenumber" required/>
          <label for="mobile-number" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
        </div>

        <!-- add additional field here -->
      </div>
    </div>

    


<!-- Rates -->
<div id="rates" class="flex-4 bg-white p-6 rounded-lg mt-5 shadow-lg">
  <h2 class="text-3xl font-extrabold text-gray-700">RATES</h2>
  <p class="mt-2 text-gray-600">Please choose your preferred rate.</p>

  <!-- Card View Section with Minimalistic Scrollbar -->
  <div class=" mt-6 overflow-x-auto flex space-x-6 scrollbar-none">
    <!-- Card 1 -->
    <?php
    // Include database connection
    include '../db_connection.php';

    // Fetch rates from the database
    $sql = "SELECT * FROM rates WHERE status = 'active'";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        echo "<div class='pb-6 overflow-x-auto flex space-x-6 scrollbar-hide scrollable-container'>";

        while ($row = $result->fetch_assoc()) {
          $id = $row['id'];
          $name = $row['name'];
          $price = $row['price'];
          $hours_of_stay = $row['hoursofstay'];
          $description = $row['description'];
          $picture = $row['picture'];
          $check_in_time = isset($row['checkin_time']) ? date("g:i A", strtotime($row['checkin_time'])) : 'Not specified';
          $check_out_time = isset($row['checkout_time']) ? date("g:i A", strtotime($row['checkout_time'])) : 'Not specified';

            // Generate the rate card with dynamic data
            echo "
            <div class='flex-none max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm relative rate-card hover:scale-105 hover:shadow-2xl transition duration-300' data-id='$id'>
                <a href='#'>
                    <img class='rounded-t-lg w-[284.18px] h-[160px] object-fill' src='../src/uploads/rates/$picture' alt='$name' />
                </a>
                <div class='p-5'>
                    <a href='#'>
                        <h5 class='mb-3 text-2xl font-semibold tracking-tight dark:text-blue-950'>$name</h5>
                    </a>
                    <div class='mb-2'>
                        <span class='text-lg font-medium text-gray-700'>₱$price</span>
                    </div>
                    <div class='mb-5'>
                        <span class='text-sm text-gray-600 mt-[-2]'>
                            <i class='fas fa-clock'></i> $hours_of_stay hours
                        </span>
                    </div>
<button onclick=\"openModal('$picture', '$name', '$description', '$hours_of_stay', '$check_in_time', '$check_out_time', '$price')\" class='absolute top-2 right-2 text-white hover:text-blue-500'>
  <i class='fas fa-info-circle text-2xl'></i>
</button>
                    <div class='mt-4 text-center'>
                        <button onclick='selectRate(\"$id\", \"$name\", \"$price\")' class='select-button bg-blue-600 text-white w-full font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200' data-id='$id' data-price='$price' data-name='$name'>
                            Select
                        </button>
                    </div>
                </div>
            </div>
            ";
        }

        echo "</div>";
    } else {
        echo "No active rates available.";
    }

    // Close the database connection
    $conn->close();
?>

  </div>
</div>



<!-- addons -->
<div id="addons" class="flex-4 bg-white p-6 mb-10 rounded-lg mt-5 shadow-lg">
  <h2 class="text-3xl font-extrabold text-gray-700">Add-ons</h2>
  <p class="mt-2 text-gray-600">Please choose your preferred add-ons.</p>

  <!-- Card View Section with Minimalistic Scrollbar -->
  <div class=" mt-6 overflow-x-auto flex space-x-6 scrollbar-none">
    <!-- Card 1 -->
    <?php
        // Include database connection
        include '../db_connection.php';

        // Fetch addons from the database
        $sql = "SELECT * FROM addons WHERE status = 'active'";
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Add a wrapper with overflow-x-auto and flex to make the cards scrollable
            echo "<div class='pb-6 overflow-x-auto flex space-x-6 scrollbar-hide scrollable-container'>"; // Apply scrollable-container class here
            
        // Assuming the result from the database is already fetched into $result
        while ($row = $result->fetch_assoc()) {
          $id = $row['id'];
          $name = $row['name'];
          $price = number_format($row['price'], 2); // Format the price with commas
          $description = $row['description'];
          $picture = $row['picture'];
          
          // Generate the rate card with dynamic data and JavaScript functionality
          echo "
          <div class='flex-none max-w-[284.18] bg-white border border-gray-200 rounded-lg shadow-sm relative addon-card hover:scale-105 hover:shadow-2xl transition duration-300' data-id='$id'>
              <a href='#'>
                  <img class='rounded-t-lg w-[284.18px] h-[160px] object-fill' src='../src/uploads/addons/$picture' alt='$name' />

              </a>
              <div class='p-5'>
                  <a href='#'>
                      <h5 class='mb-3 text-2xl font-semibold tracking-tight dark:text-blue-950'>$name</h5>
                  </a>
                  <div class='mb-2'>
                      <span class='text-lg font-medium text-gray-700'>₱$price</span>
                  </div>
                  <button onclick='openAddonModal(\"$picture\", \"$name\", \"$description\", \"$price\")' class='absolute top-2 right-2 text-white hover:text-blue-500'>
                      <i class='fas fa-info-circle text-2xl'></i>
                  </button>
                  <div class='mt-4 text-center'>
                      <button onclick='toggleAddonSelection(\"$id\", \"$name\", \"$price\")' class='select-button mt-8 bg-blue-600 text-white w-full font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200'>
                          Select
                      </button>
                  </div>
              </div>
          </div>
          ";
        }
          
            // Close the scrollable wrapper
            echo "</div>";
        } else {
            echo "No active rates available.";
        }

        // Close the database connection
        $conn->close();
      ?>
  </div>
</div>



</div>


<!-- 2-Column Wide Div -->
<div id="right-div" class="flex-2 bg-white p-6 rounded-lg shadow-lg h-full">

<div id="info-alert" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:text-blue-900 hidden" role="alert">
  <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div>
    <span class="font-medium" id="alert-title">Info alert!</span> 
    <span id="alert-message"></span>
  </div>
</div>

<div id="info-alert-field" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:text-blue-900 hidden" role="alert">
  <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div>
    <span class="font-medium" id="alert-title-field">Info alert!</span> 
    <span id="alert-message-field"></span>
  </div>
</div>



  <h2 class="text-2xl font-extrabold text-gray-700">Reservation Dates</h2>
  <p class="mt-2 text-gray-600">The system will automatically fill some fields based on your selected rate.</p>

  <!-- Check-In and Check-Out -->
  <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Check-In Date -->

  <div class="relative">
    <!-- Change input type to 'date' for testing, Flatpickr should open -->
    <input type="date" id="check-in-date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" required/>
    <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-In Date </label>
  </div>

      
      <!-- Check-Out Date (non-interactable) -->
  <div class="relative">
      <input type="date" id="check-out-date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
      <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-Out Date </label>
  </div>
</div>

  <!-- Check-In and Check-Out Times -->
  <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Check-In Time (auto-filled by system) -->
    <div class="relative">
      <input type="time" id="check-in-time" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
      <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-In Time </label>
    </div>
    <!-- Check-Out Time (auto-filled by system) -->
    <div class="relative">
      <input type="time" id="check-out-time" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
      <label for="check-out-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-Out Time </label>
    </div>
  </div>

<!-- Item and Price Section -->
<div class="mt-6 p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
  <div class="w-full max-w-xl mx-auto">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Summary</h3>

    <!-- Grid Layout for Items and Prices -->
    <div class="grid grid-cols-2 gap-6">
      
      <!-- Item Column -->
      <div class="flex flex-col items-center">
        <p class="text-gray-600 font-semibold text-lg">Item</p>
        <ul id="selected-items" class="text-gray-700 text-center space-y-2">
          <!-- Selected rate and addons will be inserted here -->
        </ul>
      </div>
      
      <!-- Price Column -->
      <div class="text-center">
        <p class="text-gray-600 font-semibold text-lg">Price</p>
        <ul id="selected-prices" class="text-gray-700 space-y-2">
          <!-- Selected rates and addons prices will be inserted here -->
        </ul>
      </div>
    </div>

        <!-- Extra Pax Section -->
<div class="mt-6 border-t pt-4">
  <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-gray-800 font-medium">Extra pax (₱250 per head)</p>
        <p class="text-xs text-gray-500 mt-1">Additional guests beyond standard occupancy</p>
      </div>
      
      <div class="flex items-center space-x-3">
        <button type="button" id="decrease-pax" class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
          </svg>
        </button>
        
        <span id="pax-count" class="text-xl font-semibold text-gray-800 w-6 text-center">0</span>
        
        <button type="button" id="increase-pax" class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Extra pax subtotal -->
    <div id="extra-pax-section" class="hidden mt-3 pt-3 border-t border-blue-200">
      <div class="flex justify-between items-center">
        <p class="text-gray-700">Extra guests (<span id="pax-count-display">0</span>)</p>
        <p class="text-gray-700 font-medium">₱<span id="extra-pax-cost">0.00</span></p>
      </div>
    </div>
  </div>
</div>

    <!-- Total Section Below the Grid -->
    <div class="mt-8 border-t pt-6">
      <div class="flex justify-between items-center">
        <p class="text-gray-700 font-semibold text-xl">Total</p>
        <p id="total-price" class="text-gray-900 font-bold text-xl">₱0.00</p>
      </div>
    </div>

    <!-- Hidden rate ID and addon ID fields -->
    <form id="summary-form" action="/submit-booking" method="POST" class="hidden">
      <input type="text" id="rate-id-field" name="rate_id" />
      <input type="text" id="addon-ids-field" name="addon_ids" />
      <input type="hidden" id="extra-pax-field" name="extra_pax" value="0" />
    </form>
  </div>
</div>

<!-- Proceed to Payment Button -->
<div class="mt-6 flex justify-center">
        <button onclick="handleProceed()" class="bg-blue-600 text-white font-bold py-3 w-full px-6 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
            Proceed to Payment
        </button>
    </div>
</div>


    <script>
 function validateForm() {
    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
    const mobileNumber = document.getElementById('mobile-number').value;
    const checkInDate = document.getElementById('check-in-date').value;
    const rateId = selectedRate ? selectedRate.id : null;

    if (!firstName || !lastName || !email || !mobileNumber || !checkInDate || !rateId) {
        showModal("Please select at least one rate.");
        return false;
    }
    return true;
}

function handleProceed() {
    if (validateForm()) {
        storeSelections();
        redirectToPayment();
    }
}

function showModal(message) {
    document.getElementById("modal-message").textContent = message;
    document.getElementById("error-modal").style.display = "flex";
}

function closeModal() {
    document.getElementById("error-modal").style.display = "none";
}

    </script>

  </div>
</section>

<script>
</script>

<div id="rate-modal" class="fixed inset-0 bg-black/30 flex justify-center items-center hidden z-50 opacity-0 transition-opacity duration-500 ease-in-out">
  <div class="bg-white p-10 rounded-lg max-w-3xl w-full">
    <div class="flex">
      <!-- Image on the left -->
      <div class="flex-none w-2/5">
        <img id="modal-picture" class="rounded-lg w-full h-full object-cover" src="" alt="Rate Picture">
      </div>

      <!-- Details on the right -->
      <div class="ml-8 flex-1">
        <h2 id="modal-name" class="text-3xl font-bold text-gray-800"></h2>

        <div class="text-gray-600 mt-4 flex items-center">
          <span class="material-icons mr-2 text-xl">schedule</span>
          <p id="modal-hours" class="text-gray-700 font-medium text-lg"></p>
        </div>

        <div class="flex items-center mt-4">
          <div class="bg-gray-50 border border-gray-300 text-gray-700 text-lg rounded-lg py-3 font-medium px-5" id="modal-checkin-time"></div>
          <div class="mx-4">
            <svg class="w-7 h-7 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 7l5 5-5 5"></path>
            </svg>
          </div>
          <div class="bg-gray-50 border border-gray-300 text-gray-700 text-lg rounded-lg py-3 font-medium px-5" id="modal-checkout-time"></div>
        </div>

        <p class="text-gray-800 font-semibold text-2xl mt-4">₱<span id="modal-price"></span></p>
        <p id="modal-description" class="text-gray-600 mt-4 max-w-2xl text-lg"></p>

        <!-- Close button -->
        <button id="close-modal" class="mt-8 px-6 py-3 bg-blue-600 text-white rounded-lg w-full text-lg hover:bg-blue-700">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for displaying Add-on Details -->
<div id="addon-modal" class="fixed inset-0 bg-black/30 flex justify-center items-center hidden z-50">
  <div class="bg-white p-10 rounded-lg max-w-3xl w-full">
    <div class="flex">
      <!-- Image on the left -->
      <div class="flex-none w-2/5">
        <img id="addon-modal-picture" class="rounded-lg w-full h-full object-cover" src="" alt="Addon Picture">
      </div>

      <!-- Details on the right -->
      <div class="ml-8 flex-1">
        <h2 id="addon-modal-name" class="text-3xl font-bold text-gray-800"></h2>

        <p class="text-gray-800 font-semibold text-2xl mt-4">₱<span id="addon-modal-price"></span></p>
        <p id="addon-modal-description" class="text-gray-600 mt-4 max-w-2xl text-lg"></p>

        <!-- Close button -->
        <button id="close-addon-modal" class="mt-8 px-6 py-3 bg-blue-600 text-white rounded-lg w-full text-lg hover:bg-blue-700">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Elfsight Facebook Chat | Untitled Facebook Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-ba949789-bf48-4f26-a7e1-ceb2bc7e1123" data-elfsight-app-lazy></div>

<script src="../scripts/booking.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-b2701a5e-2312-4201-92bf-10db53498839" data-elfsight-app-lazy></div>
    
</body>
</html>