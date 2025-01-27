<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/booking.css">
    <link href="../dist/output.css" rel="stylesheet">

</head>
<body>

  <!-- Navbar -->
  <nav class="bg-white border-blue-200 dark:bg-blue-900 fixed top-0 left-0 w-full z-50">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <!-- Logo -->
      <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="../src/uploads/logo.svg" class="logo" alt="Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"></span>
      </a>

      <!-- Profile and Hamburger -->
      <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" src="../src/uploads/team/img_677c4eca1c8992.47415664.jpg" alt="user photo">
        </button>
        <!-- Hamburger menu -->
        <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
        <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-blue-800 md:dark:bg-blue-900">
          <li>
            <a href="#home" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Home</a>
          </li>
          <li>
            <a href="#about" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Services</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact</a>
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
            <input type="text" id="first-name" class="peer p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " />
            <label for="first-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
          </div>
          <div class="relative">
            <input type="text" id="last-name" class="peer p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " />
            <label for="last-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
          </div>
        </div>

        <!-- Email and Mobile Number -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="relative">
            <input type="email" id="email" class="peer p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " />
            <label for="email" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
          </div>
          <div class="relative">
            <input type="text" id="mobile-number" class="peer p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " />
            <label for="mobile-number" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
          </div>
        </div>
      </div>

<!-- Rates -->
<div id="rates" class="flex-4 bg-white p-6 rounded-3xl mt-5 shadow-lg">
  <h2 class="text-3xl font-extrabold text-gray-700">RATES</h2>
  <p class="mt-2 text-gray-600">Please choose your preferred rate.</p>

  <!-- Card View Section with Minimalistic Scrollbar -->
  <div class="pb-6 mt-6 overflow-x-auto flex space-x-6 scrollbar-none">
    <!-- Card 1 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Rate 1</h3>
      <p class="text-gray-500">Description for Rate 1.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$50</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('rate1')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 2 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Rate 2</h3>
      <p class="text-gray-500">Description for Rate 2.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$75</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('rate2')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 3 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Rate 3</h3>
      <p class="text-gray-500">Description for Rate 3.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$100</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('rate3')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 4 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Rate 4</h3>
      <p class="text-gray-500">Description for Rate 4.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$125</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('rate4')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 5 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Rate 5</h3>
      <p class="text-gray-500">Description for Rate 5.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$150</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('rate5')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>
  </div>
</div>

<!-- Addons -->
<div id="addon" class="flex-4 bg-white p-6 rounded-3xl mt-5 shadow-lg">
  <h2 class="text-3xl font-extrabold text-gray-700">Add-ons</h2>
  <p class="mt-2 text-gray-600">Please choose your preferred add-ons.</p>

  <!-- Card View Section with Minimalistic Scrollbar -->
  <div class="pb-6 mt-6 overflow-x-auto flex space-x-6 scrollbar-none">
    <!-- Card 1 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Add-ons 1</h3>
      <p class="text-gray-500">Description for Add-ons 1.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$50</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('Add-ons1')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 2 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Add-ons 2</h3>
      <p class="text-gray-500">Description for Add-ons 2.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$75</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('Add-ons2')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 3 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Add-ons 3</h3>
      <p class="text-gray-500">Description for Add-ons 3.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$100</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('Add-ons3')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 4 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Add-ons 4</h3>
      <p class="text-gray-500">Description for Add-ons 4.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$125</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('Add-ons4')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>

    <!-- Card 5 -->
    <div class="relative bg-gray-100 p-4 rounded-lg shadow-md w-1/3 flex-shrink-0">
      <h3 class="text-xl font-semibold text-gray-800">Add-ons 5</h3>
      <p class="text-gray-500">Description for Add-ons 5.</p>
      <div class="mt-4">
        <span class="text-lg font-bold text-gray-800">$150</span>
        <p class="text-sm text-gray-400">per night</p>
      </div>
      <!-- Info Icon -->
      <button onclick="openModal('Add-ons5')" class="absolute top-2 right-2 text-gray-600 hover:text-blue-500">
        <i class="fas fa-info-circle text-2xl"></i> <!-- Info Icon -->
      </button>
    </div>
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
      <input type="date" id="check-in-date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " />
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
  <div class="mt-6 p-4 border border-gray-300 rounded-md shadow-sm">
    <h3 class="text-lg font-bold text-gray-700 mb-4">Summary</h3>
    <div class="grid grid-cols-2 gap-4">
      <!-- Item Column -->
      <div>
        <p class="text-gray-600 font-medium">Item</p>
        <ul class="text-gray-700">
          <li>Rate</li>
          <li>Add-ons</li>
          <li>Total</li>
        </ul>
      </div>
      <!-- Price Column -->
      <div class="text-right">
        <p class="text-gray-600 font-medium">Price</p>
        <ul class="text-gray-700">
          <li>$50</li>
          <li>$20</li>
          <li class="font-bold">$70</li>
        </ul>
      </div>
    </div>
  </div>

<!-- Proceed to Payment Button -->
<div class="mt-6 flex justify-center">
  <button class="bg-blue-600 text-white font-bold py-3 px-6 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
    Proceed to Payment
  </button>
</div>


  </div>
</section>

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

<script src="../scripts/booking.js"></script>
<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    
</body>
</html>