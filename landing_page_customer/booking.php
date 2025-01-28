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
$sql = "SELECT first_name, last_name, picture FROM user_tbl WHERE user_id = ?";
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
$stmt->bind_result($first_name, $last_name, $picture);

// Check if user data is found
if ($stmt->fetch()) {
    // Combine first and last name
    $full_name = $first_name . ' ' . $last_name;
    // Use the picture if it exists, otherwise set a default
    $user_picture = !empty($picture) ? 'userpicture/' . $picture : 'default-avatar.jpg'; // Adjust the path for the profile picture
} else {
    // If user not found, handle accordingly (e.g., redirect to login)
    header("Location: ../adlogin.php");
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
    <script src="../scripts/booking.js" defer></script>
    <link rel="stylesheet" href="../styles/booking.css">
    <link href="../dist/output.css" rel="stylesheet">

</head>
<body>

  <!-- Navbar -->
<nav class="bg-white border-blue-200 dark:bg-blue-900 fixed top-0 left-0 w-full z-50">
  <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
    <!-- Logo -->
    <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="../src/uploads/logo.svg" class="logo" alt="Logo" />
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"></span>
    </a>

    <!-- Cart, Profile, and Hamburger -->
    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

    <button type="button" class="flex text-lg dark:bg-blue-900 rounded-lg md:me-0 px-4 py-4 p-2 hover:bg-white/10">
      <span class="sr-only">Open cart</span>
      <!-- Cart Icon using Google Material Icons with fixed size -->
      <span class="material-icons text-white text-2xl bg-opacity-100 hover:bg-opacity-10">
          shopping_cart
      </span>
    </button>

    <!-- Profile button -->
    <button type="button" class="flex items-center ml-2 space-x-3 text-sm dark:bg-blue-900 hover:bg-white/10 rounded-lg px-4 py-2">
        <span class="sr-only">Open user menu</span>
        <!-- Display user profile picture -->
        <img class="w-10 h-10 rounded-full" src="../src/uploads/<?php echo htmlspecialchars($user_picture); ?>" alt="User Photo">
        <!-- Display user first name and last name to the right -->
        <span class="text-white font-medium"><?php echo htmlspecialchars($full_name); ?></span>
    </button>


      <!-- Hamburger menu -->
      <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-100 dark:text-gray-400 hover:bg-white/10" aria-controls="navbar-user" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
      </button>
    </div>

    <!-- Navigation Links -->
    <div class="items-center mr-110 justify-center hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
      <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-blue-800 md:dark:bg-blue-900">
        <li>
          <a href="homepage.php#home" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Home</a>
        </li>
        <li>
          <a href="homepage.php#about" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
        </li>
        <li>
          <a href="homepage.php#home#album" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Gallery</a>
        </li>
        <li>
          <a href="homepage.php#services" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Services</a>
        </li>
        <li>
          <a href="homepage.php#reviews" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


  <section class="bg-gray-100 pt-16 px-6 md:px-8 min-h-screen">
  <div class="max-w-screen-xl mx-auto flex gap-8 mt-10">

    <!-- 4-Column Wide Div -->
     <div class="flex-4">
      <div id="basic-details" class="flex-4 bg-white p-6 rounded-3xl shadow-lg">
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
      </div>
    </div>


<!-- Rates -->
<div id="rates" class="flex-4 bg-white p-6 rounded-3xl mt-5 shadow-lg">
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
            // Add a wrapper with overflow-x-auto and flex to make the cards scrollable
            echo "<div class='pb-6 overflow-x-auto flex space-x-6 scrollbar-hide scrollable-container'>"; // Apply scrollable-container class here
            
        // Assuming the result from the database is already fetched into $result
        while ($row = $result->fetch_assoc()) {
          $id = $row['id'];
          $name = $row['name'];
          $price = $row['price'];
          $hours_of_stay = $row['hoursofstay'];
          $picture = $row['picture'];
          
          // Generate the rate card with dynamic data and JavaScript functionality
          echo "
          <div class='flex-none max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm relative rate-card' data-id='$id'>
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
                  <button onclick='openModal(\"$id\")' class='absolute top-2 right-2 text-white hover:text-blue-500'>
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

<!-- addons -->
<div id="addons" class="flex-4 bg-white p-6 mb-10 rounded-3xl mt-5 shadow-lg">
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
          $price = $row['price'];
          $description = $row['description'];
          $picture = $row['picture'];
          
          // Generate the rate card with dynamic data and JavaScript functionality
          echo "
          <div class='flex-none max-w-[284.18] bg-white border border-gray-200 rounded-lg shadow-sm relative addon-card' data-id='$id'>
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
                  <button onclick='openModal(\"$id\")' class='absolute top-2 right-2 text-white hover:text-blue-500'>
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
<div id="right-div" class="flex-2 bg-white p-6 rounded-3xl shadow-lg h-full">
  <h2 class="text-2xl font-extrabold text-gray-700">Reservation Dates</h2>
  <p class="mt-2 text-gray-600">The system will automatically fill some fields based on your selected rate.</p>

  <!-- Check-In and Check-Out -->
  <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Check-In Date -->
    <div class="relative">
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
<div class="mt-6 p-4 border border-gray-300 rounded-md shadow-sm flex justify-center">
  <div class="w-full max-w-xl">
    <h3 class="text-lg font-bold text-gray-700 mb-4 text-center">Summary</h3>
    <div class="grid grid-cols-2 gap-4 justify-center items-center">
      <!-- Item Column -->
      <div class="flex justify-center items-center">
        <div>
          <p class="text-gray-600 font-bold text-center">Item</p>
          <ul id="selected-items" class="text-gray-700 text-center">
            <!-- Selected rate and addons will be inserted here -->
          </ul>
        </div>
      </div>
      <!-- Price Column -->
      <div class="text-center">
        <p class="text-gray-600 font-bold">Price</p>
        <ul id="selected-prices" class="text-gray-700">
          <!-- Selected rates and addons prices will be inserted here -->
        </ul>
      </div>
    </div>

    <!-- Total Below the Grid -->
    <div class="mt-10 text-center">
      <div class="grid grid-cols-2 gap-4 justify-items-center items-center">
        <p class="text-gray-700 font-bold">Total</p>
        <p id="total-price" class="text-gray-700 font-bold text-right">₱0.00</p>
      </div>
    </div>

    <!-- Hidden rate ID field and addon ID field -->
    <form id="summary-form" action="/submit-booking" method="POST">
      <input type="text" id="rate-id-field" name="rate_id" hidden />
      <input type="text" id="addon-ids-field" name="addon_ids" hidden />
      <!-- Hidden field will be added dynamically when a rate is selected -->
    </form>
  </div>
</div>








<!-- Proceed to Payment Button -->
<div class="mt-6 flex justify-center">
  <button onclick="storeSelections(), redirectToPayment()" class= "bg-blue-600 text-white font-bold py-3 w-full px-6 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
    Proceed to Payment
  </button>
</div>


  </div>
</section>

<script>
  
</script>

<!-- Modal for displaying more details -->
<div id="modal" class="fixed inset-0 flex items-center justify-center hidden">
  <div class="bg-white p-6 rounded-xl shadow-lg max-w-md w-full">
    <h2 id="modalTitle" class="text-3xl font-extrabold text-gray-700">Rate Details</h2>
    <p id="modalDescription" class="mt-2 text-gray-600">Detailed description of the selected rate.</p>
    <div class="mt-4">
      <span id="modalPrice" class="text-lg font-bold text-gray-800">$0</span>
      <p class="text-sm text-gray-400">per night</p>
    </div>
    <button onclick="closeModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg">Close</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    
</body>
</html>