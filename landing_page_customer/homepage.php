<?php
// Start the session to access session variables
session_start();

// Include the database connection
include('../db_connection.php'); // Adjust the path if necessary

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in (session exists)
if (isset($_SESSION['user_id'])) {
    // Get the logged-in user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Query to retrieve user info based on user_id
    $sql = "SELECT first_name, last_name, picture FROM user_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    // Check if prepare() failed
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind the user_id to the query
    $stmt->bind_param("i", $user_id);

    // Execute the query
    $stmt->execute();
    $stmt->store_result();

    // Bind the results to variables
    $stmt->bind_result($first_name, $last_name, $picture);

    // Check if user data is found
    if ($stmt->fetch()) {
        // Combine first and last name
        $full_name = $first_name . ' ' . $last_name;
        // You can also use $picture here if you want to display the user's profile picture
    } else {
        // If user not found, handle accordingly (e.g., show a default message)
        echo "User not found.";
    }

    $stmt->close();
} else {
    // If user is not logged in, show a default message or redirect to the login page
    echo "Please log in to view your profile.";
}

// Close the database connection
$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel=stylesheet href="../styles/homepage.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    

</head>
<body>

<!-- Navbar -->
<nav class="bg-white border-blue-200 dark:bg-blue-900 fixed top-0 left-0 w-full z-50">
  <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
    <!-- Logo -->
    <a href="homepage.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="../src/uploads/logo.svg" class="logo" alt="Logo" />
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"></span>
    </a>

    <ul class="flex flex-col font-medium p-4 md:p-0 mr-100 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-blue-800 md:dark:bg-blue-900">
      <li>
        <a href="#home" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Home</a>
      </li>
      <li>
        <a href="#about" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
      </li>
      <li>
        <a href="#album" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Gallery</a>
      </li>
      <li>
        <a href="#services" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Services</a>
      </li>
      <li>
        <a href="#reviews" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact</a>
      </li>
    </ul>
    <!-- Cart, Profile, and Login Buttons -->
    <div class="flex items-center space-x-6 rtl:space-x-reverse">
      <!-- Cart Button -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <button type="button" class="flex items-center text-lg ml-20 dark:bg-blue-900 rounded-lg px-4 py-2 hover:bg-white/10"
          onclick="window.location.href='customer_reservation.php'">
          <span class="sr-only">Open cart</span>
          <span class="material-icons text-white text-2xl">shopping_cart</span>
        </button>
      <?php endif; ?>

      <!-- Profile or Login Button -->
      <div class="relative inline-block text-left">
        <?php if (isset($full_name) && !empty($full_name)): ?>
          <!-- Profile Button (Logged-in) -->
          <button id="profileButton" type="button" class="flex items-center space-x-3 text-sm dark:bg-blue-900 hover:bg-white/10 rounded-lg px-4 py-2" onclick="toggleDropdown()">
            <span class="sr-only">Open user menu</span>
            <span class="text-white font-medium"><?php echo htmlspecialchars($full_name); ?></span>
            <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <!-- Dropdown Menu -->
          <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden">
            <a href="edit_profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
          </div>
        <?php else: ?>
          <!-- Login Button (Not logged-in) -->
          <a href="login.php" id="loginButton" class="flex items-center space-x-3 text-sm bg-white hover:bg-gray-300 text-blue-900 font-semibold rounded-lg px-6 py-3 transition-all duration-300 ease-in-out shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 hover:text-blue-700">
  <span class="sr-only">Login</span>
  <span class="font-semibold">Login</span>
</a>



        <?php endif; ?>
      </div>
    </div>

    <!-- Hamburger menu -->
    <button id="hamburgerButton" data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-100 dark:text-gray-400 hover:bg-white/10" aria-controls="navbar-user" aria-expanded="false">
      <span class="sr-only">Open main menu</span>
      <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
      </svg>
    </button>
  </div>

  <!-- Navigation Links -->
</nav>

<script>
  // Dropdown toggle for profile button
  document.getElementById('profileButton').addEventListener('click', function() {
    var dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('hidden');
  });

  // Close dropdown if clicked outside
  document.addEventListener('click', function(event) {
    var profileButton = document.getElementById('profileButton');
    var dropdownMenu = document.getElementById('dropdownMenu');
    
    if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
      dropdownMenu.classList.add('hidden');
    }
  });

  // Hamburger menu toggle
  document.getElementById('hamburgerButton').addEventListener('click', function() {
    var navbarUser = document.getElementById('navbar-user');
    navbarUser.classList.toggle('hidden');
  });
</script>




<!-- Hero Section -->
<section
  id="home"
  class="bg-cover bg-center relative py-16 mb-16"
  style="background-image: url('../src/uploads/resort.png');"
>
  <!-- Overlay -->
  <div class="absolute inset-0 bg-blue-950 opacity-90"></div>

  <div class="max-w-screen-xl mx-auto p-0 h-auto flex flex-col justify-start relative z-10">
    <!-- Left Section: Text -->
    <div id="hero-text" class="mt-30 text-left lg:mr-0 lg:w-1/2 ml-4 pl-0">
      <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white">
        Welcome to Our Website
      </h1>
      <p class="mt-4 text-xl text-gray-600 dark:text-gray-400 max-w-96">
        Explore our services, discover amazing features, and connect with us to know more.
      </p>
    </div>

    <!-- Check-In Form centered below the text -->
    <div id="check-in-form" class="flex justify-center mt-8 mb-8 w-full">
      <div class="inline-flex flex-col sm:flex-row border border-yellow-300 rounded-lg overflow-hidden shadow-sm dark:border-yellow-500">
        <!-- Check-In Date Input -->
        <input
          type="text"
          id="check-in"
          name="check-in"
          class="px-6 py-3 w-56 sm:w-48 border-r-2 border-yellow-500 opacity-90 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-white dark:text-black"
          placeholder="Check-In"
          required
        />
        <!-- Book Button -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <button
            type="submit"
            id="book-btn"
            class="px-6 py-3 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          >
            Book
          </button>
        <?php else: ?>
          <button
            type="button"
            id="book-btn"
            class="px-6 py-3 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            onclick="window.location.href='login.php'"
          >
            Book
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>


<!-- Rates & Add-ons Section -->
<section id="rates-section" class="bg-white flex flex-col items-center justify-center py-16 px-4 mb-16">
  <div class="max-w-screen-xl mx-auto">
    
    <!-- Rates Section -->
    <div class="mr-5">
      <h2 class="text-3xl font-extrabold text-center text-gray-900 header-rate">Our Rates</h2>
      <p class="mt-4 text-lg text-center text-gray-600 text-rate">Check out our affordable pricing plans designed for everyone.</p>

      <!-- Scrollable Horizontal Rates Cards -->
      <div class="mt-8 overflow-x-auto w-full px-5 py-2 scrollable-container" id="rates-container">
        <div class="flex space-x-6 min-w-max">
          <?php
            // Include database connection
            include '../db_connection.php';

            // Fetch rates from the database
            $sql = "SELECT * FROM rates WHERE status = 'active'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $hours_of_stay = $row['hoursofstay'];
                    $description = $row['description'];
                    $picture = $row['picture'];
                    $check_in_time = isset($row['checkin_time']) ? date("g:i A", strtotime($row['checkin_time'])) : 'Not specified';
                    $check_out_time = isset($row['checkout_time']) ? date("g:i A", strtotime($row['checkout_time'])) : 'Not specified';

                    // Add data attributes for modal
                    echo "
                    <div class='flex-none mb-5 mt-5 max-w-sm rounded-2xl shadow-lg relative rate-card animate-card' onclick=\"openModal('$picture', '$name', '$description', '$hours_of_stay', '$check_in_time', '$check_out_time', '$price')\">
                        <img class='rounded-t-2xl w-[300px] h-[250px] object-cover' src='../src/uploads/rates/$picture' alt='$name'>

                        <div class='p-5'>
                            <h2 class='text-2xl font-bold text-gray-800'>$name</h2>

                            <div class='text-gray-600 mt-2 flex items-center'>
                                <span class='material-icons mr-2'>schedule</span>
                                <p class='text-gray-600 font-medium'>{$hours_of_stay} hours</p>
                            </div>

                            <!-- Time Box with Arrow -->
                            <div class='flex items-center mt-4'>
                                <div class='bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg py-2 font-medium px-4'>
                                    $check_in_time
                                </div>
                                <div class='mx-3'>
                                    <svg class='w-6 h-6 text-gray-500' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 7l5 5-5 5M6 7l5 5-5 5'></path>
                                    </svg>
                                </div>
                                <div class='bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg py-2 font-medium px-4'>
                                    $check_out_time
                                </div>
                            </div>

                            <p class='text-gray-800 font-semibold text-xl mt-4'>₱$price</p>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-gray-600'>No active rates available.</p>";
            }

            // Close the database connection
            $conn->close();
          ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Add-ons Section -->
<section id="addons-section" class="bg-white flex flex-col items-center justify-center py-16 px-4 mb-16">
  <div class="max-w-screen-xl mx-auto">

    <div class="mb-10 mr-5">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 heading-addon">Our Add-ons</h2>
        <p class="mt-4 text-lg text-center text-gray-600 text-addon">Check out our affordable pricing plans designed for everyone.</p>

        <!-- Scrollable Horizontal Add-ons Cards -->
        <div class="mt-8 overflow-x-auto w-full px-5 py-2 scrollable-container" id="addons-container">
          <div class="flex space-x-6 min-w-max">
            <?php
                // Include database connection
                include '../db_connection.php';

                // Fetch add-ons from the database
                $sql = "SELECT * FROM addons WHERE status = 'active'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $price = $row['price'];
                        $description = $row['description'];
                        $picture = $row['picture'];

                        // Add data attributes for modal
                        echo "
                        <div class='flex-none mb-5 mt-5 max-w-sm rounded-2xl shadow-lg relative addons-card animate-card' onclick='openAddonModal(\"$picture\", \"$name\", \"$description\", \"$price\")'>
                            <img class='rounded-t-2xl w-[300px] h-[250px] object-cover' src='../src/uploads/addons/$picture' alt='$name'>

                            <div class='p-5'>
                                <h2 class='text-2xl font-bold text-gray-800'>$name</h2>

                                <p class='text-gray-800 font-semibold text-xl mt-4'>₱$price</p>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p class='text-gray-600'>No active add-ons available.</p>";
                }

                // Close the database connection
                $conn->close();
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  </div>
</section>






<!-- About Us Section -->
<section id="about" class="bg-white flex items-center justify-center px-4 mb-16">
  <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-center gap-8">
    <!-- Image Section -->
    <div class="w-full md:w-1/2 flex justify-lef">
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

<section id="album" class="bg-white flex items-center justify-center py-16 px-4 mb-16">
  <div class="max-w-screen-xl mx-auto text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Our Album</h2>
    <p class="mt-4 text-lg text-gray-600 text-gray-700">Explore some of the beautiful moments captured at our resort.</p>

    <!-- Swiper Container -->
    <div class="w-full relative">
      <div class="swiper centered-slide-carousel swiper-container">
        <div class="swiper-wrapper">
          <?php
            $directory = "../src/uploads/album/";  // Specify the path to the image folder
            $images = glob($directory . "*.jpg");  // Adjust the file type if needed (e.g., .png, .jpeg)

            // Loop through the images and create swiper-slide for each image
            foreach ($images as $image) {
              $imageName = basename($image);  // Get the image file name
              echo '<div class="swiper-slide overflow-visible mt-10">';
              echo '<div class="relative w-full h-100">';  // Set a fixed height and width container
              echo '<img class="object-cover w-full h-full rounded-lg shadow-lg filter" src="' . $directory . $imageName . '" alt="' . $imageName . '">'; // Apply blur and set object-cover
              echo '</div>';
              echo '</div>';
            }
          ?>
        </div>
        <!-- Swiper Pagination -->
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </div>
</section>


  <script>
    // Initialize Swiper after DOM is loaded
    document.addEventListener("DOMContentLoaded", function() {
      var swiper = new Swiper(".centered-slide-carousel", {
        centeredSlides: true,
        loop: true,
        spaceBetween: 30,  // Adds space between slides
        slidesPerView: 3,  // Display 3 slides (previous, center, next)
        slideToClickedSlide: true,
        autoplay: {
          delay: 3000,  // Change slide every 3 seconds
          disableOnInteraction: false,  // Continue autoplay after user interaction
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          1920: {
            slidesPerView: 4,
            spaceBetween: 30
          },
          1028: {
            slidesPerView: 2,
            spaceBetween: 10
          },
          990: {
            slidesPerView: 1,
            spaceBetween: 0
          }
        }
      });
    });
  </script>



<!-- Video Tour Section -->
<section id="video-tour" class="bg-white px-4">
  <div class="max-w-screen-xl mx-auto text-center">
    <h2 class="text-3xl font-extrabold text-gray-900">Video Tour</h2>
    <p class="mt-4 text-lg text-gray-700">
      Watch a tour of our facilities and see what we offer.
    </p>
    <div class="mt-8">
      <!-- Responsive Video Container -->
      <div class="relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
        <iframe class="absolute top-0 left-0 w-full h-full"
                src="https://www.youtube.com/embed/mx4AFPTWoFo"
                title="Video Tour"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>
</section>



<!-- Reviews Section -->
<section id="reviews" class="bg-white dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center pt-16">
  <div class="text-center">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Reviews</h2>
    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Hear from our satisfied customers about their experiences.</p>
  </div>

  <!-- Reviews will be loaded here -->
  <div id="reviews-container" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-6xl px-4"></div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("../api/fetch_reviews.php") // Adjust path if necessary
        .then(response => response.json())
        .then(data => {
            console.log(data); // Debug: Check if reviews are received
            if (data.success) {
                const reviewsContainer = document.getElementById("reviews-container");
                reviewsContainer.innerHTML = ""; // Clear existing content

                data.reviews.forEach(review => {
                    const reviewElement = document.createElement("div");
                    reviewElement.classList.add("bg-gray-100", "dark:bg-gray-800", "p-6", "rounded-lg", "shadow");

                    reviewElement.innerHTML = `
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">${review.first_name} ${review.last_name}</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">${review.title}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">${review.review_text}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">⭐ ${review.rating}/5</p>
                        <p class="text-xs text-gray-400 mt-2">${new Date(review.created_at).toLocaleString()}</p>
                    `;

                    reviewsContainer.appendChild(reviewElement);
                });
            } else {
                document.getElementById("reviews-container").innerHTML = "<p class='text-red-500'>No reviews available.</p>";
            }
        })
        .catch(error => {
            console.error("Error fetching reviews:", error);
        });
});

</script>







<section id="reserve" class="bg-gray-100 flex items-center justify-center pt-16 px-4">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center gap-8">
    <!-- Text Section -->
    <div id="reserve-text" class="w-full md:w-1/2 text-center md:text-left opacity-0 transform translate-x-[-50px] ml-8 ">
      <h2 class="text-3xl font-extrabold text-gray-900 mb-4">How to Reserve</h2>
      <ol class="list-decimal text-gray-700 mb-10 text-justify space-y-3 ml-5">
        <li>View available dates, select your preferred date, and click <strong>"Book"</strong> to go to the reservation page.</li>
        <li>Fill in your details and choose your preferred rates and add-ons.</li>
        <li>Click <strong>"Proceed to Payment"</strong> to review your reservation and invoice.</li>
        <li>Scan the QR code to pay 50% of the total amount.</li>
        <li>Enter the payment reference number and upload the payment proof.</li>
        <li>Click <strong>"Submit"</strong>. Your reservation will be reviewed, and you will receive a confirmation email.</li>
      </ol>
    </div>


    

    <!-- Image Section -->
    <div id="reserve-image" class="w-full md:w-1/2 flex justify-center opacity-0 transform translate-x-[50px] mr-8">
      <img 
        src="../src/uploads/about/resort.png" 
        alt="Reservation Steps Image" 
        class="rounded-2xl shadow-lg w-full h-auto object-cover"
      />
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="bg-white flex items-center justify-center py-16 px-4 mb-16">
  <div class="max-w-screen-xl mx-auto">
    <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">Frequently Asked Questions</h2>
    <div class="space-y-4">
      <!-- FAQ Item 1 -->
      <div class="border border-gray-200 rounded-lg shadow-sm">
        <button class="w-full text-left px-4 py-3 text-gray-900 font-medium focus:outline-none" onclick="toggleFaq('faq1')">
          Question 1
      </button>
        <div id="faq1" class="hidden px-4 py-3 text-gray-700">
          Answer 1
        </div>
      </div>
      <!-- FAQ Item 2 -->
      <div class="border border-gray-200 rounded-lg shadow-sm">
        <button class="w-full text-left px-4 py-3 text-gray-900 font-medium focus:outline-none" onclick="toggleFaq('faq2')">
          Question 2
        </button>
        <div id="faq2" class="hidden px-4 py-3 text-gray-700">
          ANSWER 2
        </div>
      </div>
      <!-- FAQ Item 3 -->
      <div class="border border-gray-200 rounded-lg shadow-sm">
        <button class="w-full text-left px-4 py-3 text-gray-900 font-medium focus:outline-none" onclick="toggleFaq('faq3')">
          Question 3
        </button>
        <div id="faq3" class="hidden px-4 py-3 text-gray-700">
          ANSWER 3
        </div>
      </div>
      <!-- FAQ Item 4 -->
      <div class="border border-gray-200 rounded-lg shadow-sm">
        <button class="w-full text-left px-4 py-3 text-gray-900 font-medium focus:outline-none" onclick="toggleFaq('faq4')">
          Question 4
        </button>
        <div id="faq4" class="hidden px-4 py-3 text-gray-700">
          ANSWER 4
        </div>
      </div>
      <!-- FAQ Item 5 -->
      <div class="border border-gray-200 rounded-lg shadow-sm">
        <button class="w-full text-left px-4 py-3 text-gray-900 font-medium focus:outline-none" onclick="toggleFaq('faq5')">
          Question 5
        </button>
        <div id="faq5" class="hidden px-4 py-3 text-gray-700">
          ANSWER 5
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function toggleFaq(faqId) {
    const faqElement = document.getElementById(faqId);
    if (faqElement.classList.contains('hidden')) {
      faqElement.classList.remove('hidden');
    } else {
      faqElement.classList.add('hidden');
    }
  }
</script>

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


<!-- Check-in Date Modal -->
<div id="checkInModal" class="fixed mt-20 right-4 z-50 hidden bg-red-500 text-white rounded-lg px-4 py-3 shadow-lg">
  <div class="flex justify-between items-center">
    <p id="modalMessage" class="modal-message">Please select a check-in date.</p>
    <button onclick="closeModal()" class="text-white">X</button>
  </div>
</div>


<!-- Modal for displaying detailed information -->
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



<!-- Check-in Date Modal -->
<div id="checkInModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
    <div class="flex justify-between items-center">
      <p id="modalMessage" class="text-gray-700">Please select a check-in date.</p>
      <button onclick="closeModal()" class="text-gray-700 font-bold">X</button>
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


<script src="../scripts/homepage_animations.js"></script>
<script src="../scripts/newhomes.js"></script>
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-b2701a5e-2312-4201-92bf-10db53498839" data-elfsight-app-lazy></div>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    
</body>
</html>