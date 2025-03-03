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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

<!-- 4-Column Wide Div -->
 
<div class="flex-4">
<form id="reservation-form">
  <div id="basic-details" class="flex-4 bg-white p-6 rounded-3xl shadow-lg">
    <h2 class="text-3xl font-extrabold text-gray-700">RESERVATION DETAILS</h2>
    <p class="mt-2 text-gray-600">Here are your reservation details, please review the following fields.</p>

<!-- Personal Information Section -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <input type="hidden" name="user_id" id="user_id" />
  <!-- First Name -->
  <div class="relative">
    <input type="text" id="first-name-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="firstname" disabled/>
    <label for="first-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
  </div>
  <!-- Last Name -->
  <div class="relative">
    <input type="text" id="last-name-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="lastname" disabled/>
    <label for="last-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
  </div>
</div>

<!-- Email and Mobile Number Section -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
  <!-- Email -->
  <div class="relative">
    <input type="email" id="email-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="email" readonly/>
    <label for="email-p" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
  </div>
  <!-- Mobile Number -->
  <div class="relative">
    <input type="text" id="mobile-number-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="mobilenumber" disabled/>
    <label for="mobile-number" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
  </div>
</div>

 <!-- Check-In and Check-Out Dates and Times -->
 <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
  <!-- Check-In Date -->
  <div class="relative">
    <input type="date" id="check-in-date" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled/>
    <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Date</label>
  </div>

    <!-- Check-Out Date (non-interactable) -->
    <div class="relative">
    <input type="date" id="check-out-date" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
    <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Date</label>
  </div>

  <!-- Check-In Time (auto-filled by system) -->
  <div class="relative">
    <input type="time" id="check-in-time" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
    <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Time</label>
  </div>

  <!-- Check-Out Time (auto-filled by system) -->
  <div class="relative">
    <input type="time" id="check-out-time" class="p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
    <label for="check-out-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Time</label>
  </div>
</div>




</div>

<div id="Invoice" class="bg-white p-8 rounded-3xl mt-5 shadow-xl max-w-4xl mx-auto">
  <!-- Invoice Title -->
  <h2 class="text-3xl font-extrabold text-gray-700 ">Invoice</h2>
  
  <!-- Invoice Date and Number -->
  <div class="mt-5 flex justify-between text-sm text-gray-600">
    <p>Date: <span id="invoice-date" class="font-medium text-gray-800"></span></p>
    <p>Invoice No: <span id="invoice-no" class="font-medium text-gray-800"></span></p>
  </div>

  <!-- Invoice Items Table -->
  <div class="mt-8 overflow-x-auto">
    <table class="w-full table-auto border-separate border-spacing-0.5">
      <thead>
      <tr class="bg-gray-100">
        <th class="bg-white text-left custom-padding font-medium text-gray-700"></th>
        <th class="bg-gray text-left py-2 px-4 font-medium text-gray-700">Category</th>
        <th class="bg-gray text-left py-2 px-4 font-medium text-gray-700">Item</th>
        <th class="bg-gray text-left py-2 px-4 font-medium text-gray-700">Price</th>
      </tr>
      </thead>
      <tbody id="invoice-items">
        <!-- Items will be inserted dynamically -->
      </tbody>
    </table>
  </div>

  <!-- Extra Pax Row -->
  <div class="mt-4">
    <table class="w-full table-auto border-separate border-spacing-0.5">
      <tbody>
        <tr id="extra-pax-row" class="hidden">
          <td></td>
          <td class="py-2 px-4 text-gray-700">Extra Pax</td>
          <td class="py-2 px-4 text-gray-700"><span id="extra-pax-count">0</span> guest(s)</td>
          <td class="py-2 px-4 font-medium text-gray-900">₱<span id="extra-pax-price">0.00</span></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Total Price Section -->
  <div class="mt-6 flex justify-between items-center border-t pt-4">
    <span class="text-xl font-semibold text-gray-700">Subtotal</span>
    <span id="total-price" class="text-medium font-bold text-gray-500">₱0.00</span>
  </div>

  <div class="flex justify-between items-center pt-4">
    <span class="text-sm font-semibold text-gray-700">Amount Paid</span>
    <span id="amount-paid-display" class="text-sm font-bold text-gray-500"></span>
  </div>

  <div class="flex justify-between items-center pt-4">
    <span class="text-xl font-semibold text-gray-700">Total</span>
    <span id="new-total" class="text-xl font-bold text-blue-900">₱0.00</span>
  </div>
</div>
</div>

<!-- 2-Column Wide Div -->
<div id="payment-div" class="flex-2 bg-white p-6 rounded-3xl shadow-lg h--[105px]">

<div id="info-alert-payment" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 dark:text-blue-900 hidden" role="alert">
  <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div>
    <span class="font-medium" id="alert-title-payment">Info alert!</span> 
    <span id="alert-message-payment"></span>
  </div>
</div>
<!-- QR Code Section -->
<div class="flex flex-col justify-center items-center mt-5">
  <span id="reservation-code" class="text-xl font-bold text-gray-700">Reservation Code:</span>
  <span id="code" class="text-xl font-bold text-gray-700 mt-2"></span>
</div>

<div class="flex justify-center">
  <img src="../src/uploads/paymentqr/qr.jpg" alt="Payment QR Code" class="w-80 h-auto max-w-xl object-contain shadow-lg rounded-md" />
</div>

<div class="flex flex-col justify-center items-center mt-5">
  <span id="downpayment" class="text-xl font-bold text-blue-600">₱0.00</span>
  <span class="text-xl font-bold text-gray-700 mt-2">Downpayment (50%)</span>
  <p class="text-sm font-bold text-gray-500 mt-2">Minimum payment is 50% of the reservation total. (50%)</p>
</div>

<!-- Reference Number Input -->
<div class="relative mt-5 w-[80%] mx-auto">
  <input type="number" id="reference-number" class="peer font-semibold p-3 pt-5 w-full border justify-center border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " required/>
  <label for="reference-number" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Reference Number<span class="text-red-500"> *</span></label>
</div>

<!-- Amount Paid Input -->
<div class="relative mt-5 w-[80%] mx-auto">
  <input type="number" id="amount-paid-input" class="peer font-semibold p-3 pt-5 w-full border justify-center border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " required/>
  <label for="amount-paid-input" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Amount Paid<span class="text-red-500"> *</span></label>
</div>


<!-- File Attachment Section -->
<div class="flex items-center justify-center w-full mt-5">
  <label for="dropzone-file" class="flex flex-col items-center justify-center w-80 h-30 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-whitedark:hover:bg-gray-600 relative overflow-hidden">
    <!-- Background Image Preview -->
    <div id="preview" class="absolute inset-0 bg-center bg-cover bg-opacity-40"></div>

    <!-- Background Overlay on top of the image -->
    <div class="absolute inset-0 bg-white/20"></div> <!-- Dark overlay -->

    <!-- Text Content that will stay on top of the preview image -->
    <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10">
      <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
      </svg>
      <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload payment</span> or drag and drop<span class="text-red-500"> *</span></p>
      <p class="text-xs text-gray-500">SVG, PNG, JPG (MAX. 800x400px)</p>
    </div>

    <!-- File input hidden -->
    <input id="dropzone-file" type="file" accept=".png,.jpg,.jpeg" class="hidden" required/>
  </label>
</div>



  <!-- Proceed to Payment Button -->
  <div class="mt-6 flex justify-center">
    <button type="button" onclick="submitReservation()" id="submitButton" class="bg-blue-600 text-white font-bold py-3 w-80 px-6 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
    Submit
    </button>
</div>


  </div>

  </form>
</section>

<!-- Modal -->
<div id="success-modal" class="fixed inset-0 flex items-center justify-center bg-gray-800/30 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <!-- Success GIF -->
        <img src="../src/green_check.png" alt="Success" class="mx-auto w-20 h-20 mb-4 animate-bounce">

        <!-- Success Message -->
        <h2 class="text-2xl font-bold text-green-600 mb-2">Reservation Successful</h2>
        <p class="text-gray-700 mb-4">An email was sent to you for your reference. Please wait while we confirm your reservation.</p>
        <p class="text-gray-700 mb-4">Redirecting to Home Page in <span id="countdown-timer">5</span> seconds...</p>

        <!-- Go Home Button -->
        <button onclick="redirectHome()" class="w-full mt-4 shadow-xl py-2 px-4 text-sm tracking-wide rounded-md text-white bg-blue-900 hover:bg-blue-700 focus:outline-none">
            Go Home
        </button>
    </div>
</div>
<!--Erormodal-->
<div id="error-modal" class="modal hidden">
    <div class="modal-content">
        <p id="error-modal-message"></p>
        <button onclick="closeErrorModal()">Close</button>
    </div>
</div>

<style>
.modal {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Dim background */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    width: 300px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.hidden {
    display: none !important;
}
</style>






<!-- Modal -->
<div id="validation-modal" class="fixed top-4 right-4 bg-red-600 text-white p-4 rounded-lg shadow-md hidden">
  <p id="modal-message" class="text-center">Please fill in the required fields</p>
</div>


<!-- Elfsight Facebook Chat | Untitled Facebook Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-ba949789-bf48-4f26-a7e1-ceb2bc7e1123" data-elfsight-app-lazy></div>
</script>
<script src="../scripts/payment.js" defer></script>
<script src="../scripts/bookings.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-b2701a5e-2312-4201-92bf-10db53498839" data-elfsight-app-lazy></div>
    
</body>
</html>