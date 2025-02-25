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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.js"></script>
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
          <a href="../index.php#home" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Home</a>
        </li>
        <li>
          <a href="../index.php#about" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
        </li>
        <li>
          <a href="../index.php#album" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Gallery</a>
        </li>
        <li>
          <a href="../index.php#rates" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Services</a>
        </li>
        <li>
          <a href="../index.php#reviews" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Reviews</a>
        </li>
      </ul>
    </div>
  </div>
</nav>



  <section class="bg-gray-100 pt-16 px-6 md:px-8 min-h-screen">
    
  <div class="max-w-screen-xl mx-auto flex gap-8 mt-10">

  

  <div id="reservation-status" class="p-8 rounded-lg mt-5 max-w-4xl mx-auto">

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
        <button type="button" id="cancel-btn" onclick="toggleModal('cancel-reservation')" class=" w-full rounded-lg border border-gray-700 bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800 hover:text-gray-300 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-700 dark:border-gray-200 dark:bg-white dark:text-gray-700 dark:hover:bg-gray-100 dark:hover:text-gray-900 dark:focus:ring-gray-300 ">
          Cancel Reservation
        </button>

        <button type="button" id="reschedule-btn" class="mt-4 flex w-full items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-medium text-blue-900 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900 dark:text-white dark:hover:bg-blue-700 dark:focus:ring-blue-600 sm:mt-0">
          Reschedule Reservation
        </button>

        <button type="button" id="review-btn" class="mt-4 flex w-full items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-medium text-blue-900 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900 dark:text-white dark:hover:bg-blue-700 dark:focus:ring-blue-600 sm:mt-0">
          Review Reservation
        </button>
      </div>
    </div>
   
    
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
    <input type="text" id="fname-details" class="font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="firstname" readonly/>
    <label for="first-name" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
  </div>
  <!-- Last Name -->
  <div class="relative">
    <input type="text" id="lname-details" class="font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="lastname" readonly/>
    <label for="last-name" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
  </div>
</div>

<!-- Email and Mobile Number Section -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
  <!-- Email -->
  <div class="relative">
    <input type="email" id="email-details" class="font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="email" readonly/>
    <label for="email-p" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
  </div>
  <!-- Mobile Number -->
  <div class="relative">
    <input type="text" id="contact-details" class="font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="mobilenumber" readonly/>
    <label for="mobile-number" class="absolute left-3 top-0 mt-3 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
  </div>
</div>

 <!-- Check-In and Check-Out Dates and Times -->
 <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
  <!-- Check-In Date -->
  <div class="relative">
    <input type="date" id="checkin-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " readonly/>
    <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Date</label>
  </div>

    <!-- Check-Out Date (non-interactable) -->
    <div class="relative">
    <input type="date" id="checkout-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " readonly />
    <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Date</label>
  </div>

  <!-- Check-In Time (auto-filled by system) -->
  <div class="relative">
    <input type="time" id="checkin-time-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " readonly />
    <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Time</label>
  </div>

  <!-- Check-Out Time (auto-filled by system) -->
  <div class="relative">
    <input type="time" id="checkout-time-details" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " readonly />
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
  <span id="new-total-display" class="text-xl font-bold text-blue-900">₱0.00</span>
</div>


<!-- Add script at the end -->


  
</div>
</div>
  </form>
</section>


<!-- Main Modal -->
<div id="reschedule-modal" tabindex="-1" aria-hidden="true" class="hidden bg-black/20 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Reschedule Request
                </h3>
                <button type="button" id="close-btn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->

            <div id="reservation-id-display"></div>

            
            <form id="reschedule-form" method="POST" class="p-4 md:p-5">

              <div id="info-alert-modal" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:text-blue-900 hidden" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium" id="alert-title">Info alert!</span> 
              <span id="alert-message-modal"></span>
            </div>
          </div>

    <div class="grid gap-4 mb-4 grid-cols-2">

    
        <input type="hidden" id="reservation_id" name="reservation_id" value="">

        <div class="relative">
            <!-- Check-In Date (with Flatpickr) -->
            <input type="date" id="check-in-date" name="check_in_date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" required />
            <label for="check_in_date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-In Date <span class="text-red-500">*</span> </label>
        </div>

        <!-- Check-Out Date (disabled, auto-filled based on Check-In Date) -->
        <div class="relative">
            <input type="date" id="check-out-date" name="check_out_date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" disabled />
            <label for="check_out_date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-Out Date </label>
        </div>
    </div>

    <!-- Time Inputs (Disabled) -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="relative">
            <!-- Check-In Time (auto-filled) -->
            <input type="time" id="check-in-time" name="check_in_time" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" disabled />
            <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-In Time </label>
        </div>
        <div class="relative">
            <!-- Check-Out Time (auto-filled) -->
            <input type="time" id="check-out-time" name="check_out_time" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" disabled />
            <label for="check-out-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-Out Time </label>
        </div>
    </div>

    <!-- Reason for Reschedule -->
    <div class="col-span-2 mb-5">
        <label for="schedule_reason" class="block mb-2 text-sm font-medium text-black">Reason</label>
        <textarea id="schedule_reason" name="description" rows="4" class="block p-2.5 w-full text-sm text-black bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write reason here"></textarea>
    </div>

    <!-- Hidden Fields -->
    <input type="hidden" name="status" value="Pending">

    <!-- Submit Button -->
    <div class="flex justify-end">
        <button type="button" id="submit-btn" class="mt-4 flex w-full items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-medium text-blue-900 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900 dark:text-white dark:hover:bg-blue-700 dark:focus:ring-blue-600 sm:mt-0">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Send
        </button>
    </div>
</form>



        </div>
    </div>
</div>


<!-- Review Modal -->
<div id="review-modal" tabindex="-1" aria-hidden="true" class="hidden bg-black/20 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Leave a Review
                </h3>
                <button type="button" id="close-review-btn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="review-form" method="POST" class="p-4 md:p-5">
                <input type="hidden" id="review-id" name="review_id" value="">
                <input type="hidden" id="user-id" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                <input type="hidden" id="created-at" name="created_at" value="">
                <input type="hidden" id="updated-at" name="updated_at" value="">

                <!-- Rating -->
                <div class="mb-4">
                    <label for="rating" class="block mb-2 text-sm font-medium text-gray-900">Rating</label>
                    <div id="rating" class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-rating="1">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.84-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                        <svg class="w-6 h-6 text-gray-400 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-rating="2">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.84-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                        <svg class="w-6 h-6 text-gray-400 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-rating="3">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.84-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                        <svg class="w-6 h-6 text-gray-400 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-rating="4">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.84-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                        <svg class="w-6 h-6 text-gray-400 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-rating="5">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.84-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
                    <input type="text" id="title" name="title" class="p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" required>
                </div>

                <!-- Review Text -->
                <div class="mb-4">
                    <label for="review-text" class="block mb-2 text-sm font-medium text-gray-900">Review</label>
                    <textarea id="review-text" name="review_text" rows="4" class="block p-2.5 w-full text-sm text-black bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your review here" required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" id="submit-review-btn" class="mt-4 flex w-full items-center justify-center rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<!-- Modal -->
<div id="cancel-reservation" tabindex="-1" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 left-0 w-full h-full flex bg-black/20 justify-center items-center z-50000000">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm ">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-900 w-12 h-12 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 ">Are you sure about the changes you made?</h3>
                <h3 class="mb-5 text-lg font-normal text-gray-500 ">You would have to wait for your payment to be refunded manually</h3>
                <button id="submitBTN" type="button" class="text-white bg-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" onclick="cancelReservation()">
                    Yes, I'm sure
                </button>
                <button data-modal-hide="no-validation" onclick="toggleModal('cancel-reservation')" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">No, cancel</button>
            </div>
        </div>
    </div>
</div>

    <script src="../scripts/booking.js" defer></script>
    <script src="../scripts/customer_reservation_details.js" defer></script>
    <script src="../scripts/flatpickr.js" defer></script>   
    <script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-b2701a5e-2312-4201-92bf-10db53498839" data-elfsight-app-lazy></div>
    <script>
    var userId = <?php echo json_encode($_SESSION['user_id']); ?>;
    var reservation_id = <?php echo json_encode($_GET['reservation_id']); ?>; // Or set this dynamically
</script>
</body>
</html>