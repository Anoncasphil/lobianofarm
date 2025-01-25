<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/newhome.css">

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

<!-- Hero Section -->
<section
  id="home"
  class="bg-cover bg-center min-h-[50vh] pt-16 relative"
  style="background-image: url('../src/uploads/album/resort.png');"
>
  <!-- Overlay -->
  <div class="absolute inset-0 bg-blue-950 opacity-90"></div>

  <div class="max-w-screen-xl mx-auto m-0 p-0 h-full flex flex-col justify-start relative z-10">
    <!-- Left Section: Text -->
    <div class="mt-30 text-left lg:mr-0 lg:w-1/2 ml-4 pl-0">
      <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white">
        Welcome to Our Website
      </h1>
      <p class="mt-4 text-xl text-gray-600 dark:text-gray-400 max-w-96">
        Explore our services, discover amazing features, and connect with us to know more.
      </p>
    </div>
    
    <!-- Check-In Form centered below the text -->
    <div class="flex justify-center mt-15 mb-15 w-full">
      <div class="inline-flex flex-col sm:flex-row border border-yellow-300 rounded-lg overflow-hidden shadow-sm dark:border-yellow-500">
        <!-- Check-In Date -->
        <input
          type="text"
          id="check-in"
          name="check-in"
          class="px-6 py-3 w-56 sm:w-48 border-r-2 border-yellow-500 opacity-90 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white dark:text-black"
          placeholder="Check-In"
          required
        />
        <!-- Book Button -->
        <button
          type="submit"
          id="book-btn"
          class="px-6 py-3 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
          Book
        </button>
      </div>
    </div>
  </div>
</section>


<!-- About Us Section -->
<section id="about" class="bg-white flex items-center justify-center pt-16 px-4">
  <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-center gap-8">
    <!-- Image Section -->
    <div class="w-full md:w-1/2 flex justify-center">
      <img 
        src="../src/uploads/about/resort.png" 
        alt="About Us Image" 
        class="rounded-2xl shadow-lg w-full h-auto object-cover"
      />
    </div>
    <!-- Text Section -->
    <div class="w-full md:w-1/2 text-center md:text-left md:mx-8">
      <h2 class="text-3xl font-extrabold text-gray-900 mb-4">About Us</h2>
      <p class="text-lg text-gray-700 mb-4 text-justify">
        Welcome to 888 Lobiano's Farm Resort, where nature meets comfort. Our goal is to provide a serene escape where guests can unwind, reconnect with nature, and create unforgettable memories. Whether you're here for a weekend retreat or a special celebration, our resort offers a perfect blend of tranquility and adventure.
      </p>
      <a 
        href="#learn-more"
        class="inline-block px-6 py-3 bg-blue-600 text-white text-lg font-medium rounded-lg shadow-md hover:bg-blue-700 transition-all"
      >
        Learn More
      </a>
    </div>
  </div>
</section>

<section id="album" class="bg-white min-h-screen flex items-center justify-center pt-16 px-4">
  <div class="max-w-screen-xl mx-auto text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Our Album</h2>
    <p class="mt-4 text-lg text-gray-600 text-gray-700">Explore some of the beautiful moments captured at our resort.</p>

    <!-- Gallery Container -->
    <div class="grid gap-4 mt-12">
      <?php
        $directory = "../src/uploads/album/";  // Specify the path to the image folder
        $images = glob($directory . "*.jpg");  // Adjust the file type if needed (e.g., .png, .jpeg)

        $mainImage = array_shift($images);  // Get the first image for the main image (optional)
        $imageName = basename($mainImage);  // Get the image file name for the main image
        echo '<div>';
        // Main Image
        echo '<img id="main-image" class="h-auto max-w-full rounded-lg" src="' . $directory . $imageName . '" alt="' . $imageName . '">';
        echo '</div>';

        // Small Images Below
        echo '<div class="grid grid-cols-5 gap-4">';
        foreach ($images as $image) {
          $imageName = basename($image);  // Get the image file name
          echo '<div>';
          echo '<img class="h-auto max-w-full rounded-lg" src="' . $directory . $imageName . '" alt="' . $imageName . '">';
          echo '</div>';
        }
        echo '</div>';
      ?>
    </div>
  </div>
</section>

<script>
  // Get all the images in the directory
  const images = <?php echo json_encode($images); ?>;
  let currentIndex = 0;

  function changeImage() {
    currentIndex = (currentIndex + 1) % images.length;  // Loop back to the first image
    const newImage = images[currentIndex];
    document.getElementById('main-image').src = newImage;  // Change the source of the main image
  }

  // Change the image every 2 seconds
  setInterval(changeImage, 2000);
</script>



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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    
</body>
</html>