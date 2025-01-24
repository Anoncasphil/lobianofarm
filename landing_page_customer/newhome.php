<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>
<body>

  <!-- Navbar -->
  <nav class="bg-white border-gray-200 dark:bg-gray-900 fixed top-0 left-0 w-full z-50">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <!-- Logo -->
      <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"></span>
      </a>

      <!-- Profile and Hamburger -->
      <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" src="https://via.placeholder.com/40" alt="user photo">
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
        <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li>
            <a href="#" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
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

<!-- Hero Section -->
<section id="home" class="bg-gray-50 dark:bg-gray-800 min-h-screen flex items-center justify-center pt-16">
  <div class="text-center">
    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Welcome to Our Website</h1>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Explore our services, discover amazing features, and connect with us to know more.</p>
    <div class="mt-12">
      <div class="inline-flex border border-gray-300 rounded-lg overflow-hidden shadow-sm dark:border-gray-600">
        <!-- Check-In Date -->
        <input
          type="text"
          id="check-in"
          name="check-in"
          class="px-4 py-2 w-40 border-r border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          placeholder="Check-In"
          required
        />
        <!-- Check-Out Date -->
        <input
          type="text"
          id="check-out"
          name="check-out"
          class="px-4 py-2 w-40 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          placeholder="Check-Out"
          required
        />
        <!-- Book Button -->
        <button
          type="submit"
          id="book-btn"
          class="px-6 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
          Book
        </button>
      </div>
    </div>
  </div>
</section>


<!-- About Us Section -->
<section id="about" class="bg-white dark:bg-gray-900 min-h-screen flex items-center justify-center pt-16">
  <div class="text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">About Us</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Learn more about our mission, vision, and what makes us unique.</p>
  </div>
</section>

<section id="album" class="bg-gray-50 dark:bg-gray-800 py-16">
  <div class="max-w-screen-xl mx-auto text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Our Album</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Explore some of the beautiful moments captured at our resort.</p>

    <!-- Slideshow Container -->
    <div class="relative mt-12 overflow-hidden rounded-lg shadow-lg max-w-screen-lg mx-auto">
      <div id="slideshow" class="flex transition-transform duration-500 ease-in-out">
        <!-- Slide 1 -->
        <div class="min-w-full">
          <img src="https://via.placeholder.com/1200x600" alt="Beautiful Resort View" class="w-full h-auto object-cover">
          <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-50 text-white p-4">
            <h3 class="text-lg font-semibold">Beautiful Resort View</h3>
          </div>
        </div>
        <!-- Slide 2 -->
        <div class="min-w-full">
          <img src="https://via.placeholder.com/1200x600" alt="Relaxing Poolside" class="w-full h-auto object-cover">
          <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-50 text-white p-4">
            <h3 class="text-lg font-semibold">Relaxing Poolside</h3>
          </div>
        </div>
        <!-- Slide 3 -->
        <div class="min-w-full">
          <img src="https://via.placeholder.com/1200x600" alt="Sunset by the Beach" class="w-full h-auto object-cover">
          <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-50 text-white p-4">
            <h3 class="text-lg font-semibold">Sunset by the Beach</h3>
          </div>
        </div>
        <!-- Slide 4 -->
        <div class="min-w-full">
          <img src="https://via.placeholder.com/1200x600" alt="Mountain Hiking Adventure" class="w-full h-auto object-cover">
          <div class="absolute bottom-0 left-0 w-full bg-black bg-opacity-50 text-white p-4">
            <h3 class="text-lg font-semibold">Mountain Hiking Adventure</h3>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-70">
        &larr;
      </button>
      <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full hover:bg-opacity-70">
        &rarr;
      </button>
    </div>
  </div>
</section>


<!-- Rates Section -->
<section id="rates" class="bg-gray-50 dark:bg-gray-800 min-h-screen flex items-center justify-center pt-16">
  <div class="text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Our Rates</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Check out our affordable pricing plans designed for everyone.</p>
  </div>
</section>

<!-- Add-ons Section -->
<section id="addons" class="bg-white dark:bg-gray-900 min-h-screen flex items-center justify-center pt-16">
  <div class="text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Add-ons</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Explore our additional services to enhance your experience.</p>
  </div>
</section>

<!-- Video Tour Section -->
<section id="video-tour" class="bg-gray-50 dark:bg-gray-800 min-h-screen flex items-center justify-center pt-16">
  <div class="text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Video Tour</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Watch a tour of our facilities and see what we offer.</p>
    <div class="mt-8">
      <iframe class="w-full max-w-3xl mx-auto aspect-video rounded-lg" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
</section>

<!-- Reviews Section -->
<section id="reviews" class="bg-white dark:bg-gray-900 min-h-screen flex items-center justify-center pt-16">
  <div class="text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Reviews</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Hear from our satisfied customers about their experiences.</p>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-gray-900 dark:bg-gray-800 py-8">
  <div class="text-center">
    <p class="text-sm text-gray-400">© 2025 Your Company. All Rights Reserved.</p>
    <div class="mt-4 space-x-4">
      <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a>
      <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
      <a href="#" class="text-gray-400 hover:text-white">Contact Us</a>
    </div>
  </div>
</footer>




<script src="../scripts/newhome.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
</body>
</html>