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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="../scripts/booking.js" defer></script>
    <script src="../scripts/customer_reservation_details.js"></script>
    <link rel="stylesheet" href="../styles/booking.css">
    <link rel="stylesheet" href="../styles/customer_reservation_details.css">
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
          <a href="homepage.php#album" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Gallery</a>
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

  <div id="reservation-status" class="p-8 rounded-lg mt-5 max-w-4xl mx-auto">
    <div class="space-y-6 rounded-lg border border-gray-700 bg-gray-900 p-6 shadow-lg dark:border-gray-200 dark:bg-white">
      <h3 class="text-xl font-semibold text-white dark:text-gray-900">Reservation Status</h3>

      <ol class="relative ms-3 border-s border-gray-700 dark:border-gray-200" id="steps">
        <li class="mb-10 ms-6 status-step" id="pending">
          <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 ring-8 ring-gray-900 dark:bg-gray-100 dark:ring-white">
            <svg class="h-4 w-4 text-white dark:text-gray-700 pending-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/>
            </svg>
          </span>
          <h4 class="mb-0.5 text-base font-semibold dark:text-gray-900">Reservation is being validated</h4>
          <p class="text-sm font-normal text-gray-400 dark:text-gray-600">Admin is reviewing your reservation details.</p>
        </li>

        <li class="mb-10 ms-6 status-step" id="confirmed">
          <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-700 ring-8 ring-gray-900 dark:bg-gray-200 dark:ring-white">
            <svg class="h-4 w-4 confirmed-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/>
            </svg>
          </span>
          <h4 class="mb-0.5 text-base font-semibold text-white dark:text-gray-900">Reservation is confirmed</h4>
          <p class="text-sm font-normal text-gray-400 dark:text-gray-600">Your reservation has been approved.</p>
        </li>

        <li class="ms-6 text-white dark:text-gray-900 status-step" id="completed">
          <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-700 ring-8 ring-gray-900 dark:bg-gray-200 dark:ring-white">
            <svg class="h-4 w-4 confirmed-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/>
            </svg>
          </span>
          <h4 class="mb-0.5 font-semibold">Reservation complete</h4>
          <p class="text-sm text-gray-400 dark:text-gray-600">Your reservation process is complete. Enjoy your stay!</p>
        </li>
      </ol>
      
      <div class="gap-4 sm:flex sm:items-center">
        <button type="button" class="w-full rounded-lg border border-gray-700 bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800 hover:text-gray-300 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-700 dark:border-gray-200 dark:bg-white dark:text-gray-700 dark:hover:bg-gray-100 dark:hover:text-gray-900 dark:focus:ring-gray-300">
          Cancel Reservation
        </button>

        <a href="#" class="mt-4 flex w-full items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-medium text-blue-900 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900 dark:text-white dark:hover:bg-blue-700 dark:focus:ring-blue-600 sm:mt-0">
          Reschedule Reservation
        </a>
      </div>
    </div>

<!-- 4-Column Wide Div -->
 
<div class="flex-4">
<form id="reservation-form">
  <div id="basic-details" class="bg-white p-8 rounded-lg mt-5 shadow-xl max-w-4xl mx-auto">
    <h2 class="text-xl font-semibold text-white dark:text-gray-900">Reservation Details</h2>
    <p class="text-sm font-normal text-gray-400 dark:text-gray-600">Here are your reservation details, please review the following fields.</p>

    <input type="hidden" id="reservation-id" />


<!-- Personal Information Section -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <input type="hidden" name="user_id" id="user_id" />
  <!-- First Name -->
  <div class="relative">
    <input type="text" id="fname-details" class=" font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="firstname" disabled/>
    <label for="first-name" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
  </div>
  <!-- Last Name -->
  <div class="relative">
    <input type="text" id="lname-details" class="font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="lastname" disabled/>
    <label for="last-name" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
  </div>
</div>

<!-- Email and Mobile Number Section -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
  <!-- Email -->
  <div class="relative">
    <input type="email" id="email-details" class="font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="email" readonly/>
    <label for="email-p" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
  </div>
  <!-- Mobile Number -->
  <div class="relative">
    <input type="text" id="contact-details" class=" font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="mobilenumber" disabled/>
    <label for="mobile-number" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
  </div>
</div>

 <!-- Check-In and Check-Out Dates and Times -->
 <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
  <!-- Check-In Date -->
  <div class="relative">
    <input type="date" id="checkin-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled/>
    <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Date</label>
  </div>

    <!-- Check-Out Date (non-interactable) -->
    <div class="relative">
    <input type="date" id="checkout-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
    <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Date</label>
  </div>

  <!-- Check-In Time (auto-filled by system) -->
  <div class="relative">
    <input type="time" id="checkin-time-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
    <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Time</label>
  </div>

  <!-- Check-Out Time (auto-filled by system) -->
  <div class="relative">
    <input type="time" id="checkout-time-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
    <label for="check-out-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Time</label>
  </div>
</div>




</div>

<div id="invoice-details" class="bg-white p-8 rounded-lg mt-5 shadow-xl max-w-4xl mx-auto">
  <!-- Invoice Title -->
  <h2 class="text-xl font-semibold text-white dark:text-gray-900">Invoice</h2>
  
  <!-- Invoice Date and Number -->
  <div class="mt-5 flex justify-between text-sm text-gray-600">
    <p>Date: <span id="invoice-date-details" class="font-medium text-gray-800"></span></p>
    <p>Invoice No: <span id="invoice-no-details" class="font-medium text-gray-800"></span></p>
  </div>

  <!-- Invoice Items Table -->
  <div class="mt-8 overflow-x-auto">
    <table class="w-full table-auto border-separate border-spacing-0.5">
      <thead>
        <tr class="bg-gray-100">
          <th class="text-left py-2 px-4 font-medium text-gray-800">Category</th>
          <th class="text-left py-2 px-4 font-medium text-gray-800">Item</th>
          <th class="text-left py-2 px-4 font-medium text-gray-800">Price</th>
        </tr>
      </thead>
      <tbody id="invoice-items-details">
        <!-- Items will be inserted dynamically -->
      </tbody>
    </table>
  </div>

<!-- Total Price Section -->
<div class="mt-6 flex justify-between items-center border-t pt-4">
  <span class="text-xl font-semibold text-white dark:text-gray-900"></span>
  <span id="total-price-details" class="text-medium font-bold text-gray-500">₱29500.00</span>
</div>
<div class="flex justify-between items-center pt-4">
  <span class="text-sm font-semibold text-white dark:text-gray-900"></span>
  <span id="downpayment" class="text-sm font-bold text-gray-500"></span>
</div>

<div class="flex justify-between items-center pt-4">
  <span class="text-xl font-semibold text-white dark:text-gray-900">Total</span>
  <span id="new-total" class="text-xl font-bold text-blue-900">₱0.00</span>
</div>

<!-- Add script at the end -->


  
</div>
</div>
  </form>
</section>


    
</body>
</html>