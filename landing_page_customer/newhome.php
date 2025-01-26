<?php
session_start();
require_once '../db_connection.php';

// Fetch rates
$rates_query = $conn->query("SELECT * FROM rates WHERE status = 'active'");
$rates = $rates_query->fetch_all(MYSQLI_ASSOC);

// Fetch addons
$addons_query = $conn->query("SELECT * FROM addons WHERE status = 'active'");
$addons = $addons_query->fetch_all(MYSQLI_ASSOC);

// Calculate average rating and total reviews first
$rating_query = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews");
$rating_query->execute();
$stats = $rating_query->get_result()->fetch_assoc();
$avg_rating = number_format($stats['avg_rating'] ?? 0, 1);
$total_reviews = $stats['total_reviews'] ?? 0;

// Then fetch reviews
$reviews_query = $conn->prepare("SELECT r.*, u.first_name, u.last_name 
                               FROM reviews r 
                               JOIN user_tbl u ON r.user_id = u.user_id 
                               ORDER BY r.created_at DESC");
$reviews_query->execute();
$reviews = $reviews_query->get_result();
?>

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
  <section id="about" class="bg-white dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center pt-16">
    <div class="text-center">
      <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">About Us</h2>
      <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Learn more about our mission, vision, and what makes us unique.</p>
    </div>
    <div id="rate_pic_container" class="flex flex-row overflow-x-auto gap-8 w-full max-w-screen-xl mx-auto mt-8 pb-4">
        <?php foreach($rates as $rate): ?>
            <div id="rates_card" class="flex-shrink-0 flex flex-col w-[350px] rounded-lg shadow-md overflow-hidden">
                <div id="rates_card_pic" class="w-full h-[300px] bg-cover bg-center" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($rate['picture']); ?>');">
                </div>
                <div class="flex flex-col items-center justify-center h-[100px] bg-white text-black px-4 py-2">
                    <h1 class="text-xl font-semibold"><?php echo $rate['name']; ?></h1>
                    <p class="text-sm text-gray-500"><i class="fa-solid fa-clock"></i> <?php echo $rate['hoursofstay']; ?> hours</p>
                    <p class="text-lg font-bold"><?php echo '₱' . number_format($rate['price'], 2); ?></p>
                </div>
                <div class="flex justify-center items-center py-3 bg-green-500 text-white">
                    <button type="button" id="view_details_btn" class="w-[80%] bg-green-500 rounded-lg text-sm" data-id="<?php echo $rate['id']; ?>" data-type="rate">
                        View Details
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
  </section>

<!-- Add-ons Section -->
<section id="addons_section" class="flex flex-col dark:bg-gray-900 items-center w-full py-10 px-4">
    <div class="text-center">
      <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">About Us</h2>
      <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Learn more about our mission, vision, and what makes us unique.</p>
    </div>

    <div id="addons_pic_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-screen-xl mx-auto mt-8">
        <?php foreach($addons as $addon): ?>
            <div id="addons_card" class="flex flex-col w-full bg-white rounded-lg shadow-md overflow-hidden mt-4">
                <div id="addons_card_pic" class="w-full h-[300px] bg-cover bg-center" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($addon['picture']); ?>');">
                </div>
                <div class="flex flex-col items-center justify-center h-[100px] bg-white text-black px-4 py-2">
                    <h1 class="text-xl font-semibold"><?php echo $addon['name']; ?></h1>
                    <p class="text-lg font-bold"><?php echo '₱' . number_format($addon['price'], 2); ?></p>
                </div>
                <div class="flex justify-center items-center py-3 bg-green-500 text-white">
                    <button type="button" id="view_details_btn" class="w-[80%] bg-green-500 rounded-lg text-sm" data-id="<?php echo $addon['id']; ?>" data-type="addon">
                        View Details
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
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
<section id="reviews_section" class="flex flex-col items-center w-full py-10 px-4">
    <div id="review_header" class="w-full max-w-screen-xl mx-auto mb-8">
        <h1 class="text-3xl font-bold">Reviews</h1>
        <p class="mt-3">Discover what guests are saying.</p>
    </div>

    <div id="review_stats" class="flex justify-between items-center w-full max-w-screen-xl mx-auto mb-8">
        <div class="flex items-center gap-4">
            <h2 class="text-2xl font-bold"><?php echo $avg_rating; ?></h2>
            <p>Based on <?php echo $total_reviews; ?> reviews</p>
        </div>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="submit_review.php" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg">Submit a review</a>
        <?php else: ?>
            <a href="register.php" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg">Sign up</a>
        <?php endif; ?>
    </div>

    <div id="review_card_container" class="flex flex-row overflow-x-auto gap-8 w-full max-w-screen-xl mx-auto">
        <?php while($review = $reviews->fetch_assoc()): ?>
            <div id="review_card" class="flex-shrink-0 flex flex-col w-[350px] rounded-2xl shadow-xl bg-white min-h-fit">
                <div id="review_text_container" class="flex-grow w-full px-5 py-3">
                    <h3 class="font-bold mt-3"><?php echo htmlspecialchars($review['title']); ?></h3>
                    <p class="py-[3%] break-words min-h-fit"><?php echo htmlspecialchars($review['review_text']); ?></p>
                </div>
                <div id="user_info_review" class="flex flex-row justify-between items-center p-5 w-full border-t-2">
                    <p id="reviewer_name"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></p>
                    <div id="star_review" class="flex flex-row justify-center items-center">
                        <p><?php echo $review['rating']; ?>.0</p>
                        <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Footer Section -->
<footer class="bg-gray-900 dark:bg-gray-800 py-8">
  <div class="text-center">
    <p class="text-sm text-gray-400">© 2025 Your Company. All