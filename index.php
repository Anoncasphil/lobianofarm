<?php
// Start the session to access session variables
session_start();

// Include the database connection
include('db_connection.php'); // Adjust the path if necessary

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in (session exists)
if (isset($_SESSION['user_id'])) {
    // Get the logged-in user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Query to retrieve user info based on user_id
    $sql = "SELECT first_name, last_name  FROM user_tbl WHERE user_id = ?";
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
    $stmt->bind_result($first_name, $last_name);

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
  
  <link rel="icon" type="image/png" href="src/uploads/logo.svg">


  <!-- Tailwind CSS for styling -->
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  
  <!-- jQuery for DOM manipulation -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Swiper CSS and JS for carousel functionality -->
  <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  
  <!-- Flatpickr CSS for date picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  
  <!-- Fancybox CSS for image lightbox -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
  
  <!-- Google Material Icons for icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


  
  <!-- AOS CSS for animations on scroll -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

  <style>
    .logo {
    height: 3rem; /* Adjust this as needed */
    width: auto;
    object-fit: contain;
  }


  /* Animation Enhancement */
  .rate-card {
    opacity: 0; /* Ensure cards are hidden initially */
    transform: translateY(30px); /* Start from below */
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
  }

  /* Make them visible when in viewport */
  .rate-card.aos-animate {
    opacity: 1;
    transform: translateY(0);
  }

  /* Hide overflow on y-axis for html and body */
  body {
    
  }

  .section {
    overflow-y: hidden;
}


  
  </style>

</head>
<body>

<!-- Navbar -->
<nav class="border-blue-200 bg-blue-900 fixed top-0 left-0 w-full z-50" data-aos="fade-down" data-aos-duration="1200">
  <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
    <!-- Logo -->
    <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="src/uploads/logo.svg" class="logo" alt="Logo" />
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
      <li><a href="#home" class="block py-2 px-3 text-white hover:text-blue-500">Home</a></li>
      <li><a href="#services" class="block py-2 px-3 text-white hover:text-blue-500">Services</a></li>
      <li><a href="#about" class="block py-2 px-3 text-white hover:text-blue-500">About</a></li>
      <li><a href="#album" class="block py-2 px-3 text-white hover:text-blue-500">Album</a></li>
      <li><a href="#reviews" class="block py-2 px-3 text-white hover:text-blue-500">Reviews</a></li>
      <li><a href="#contact" class="block py-2 px-3 text-white hover:text-blue-500">Contact</a></li>
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
            <a href="landing_page_customer/customer_reservation.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Reservations</a>
            <a href="landing_page_customer/edit_profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <hr class="border-gray-300">
            <a href="landing_page_customer/logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="/landing_page_customer/login.php" class="flex items-center space-x-3 text-sm bg-white hover:bg-gray-300 text-blue-900 font-semibold rounded-lg px-6 py-3 transition-all duration-300 ease-in-out shadow-md hover:shadow-lg">
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
        <a href="landing_page_customer/customer_reservation.php" class="block py-2 text-gray-700 hover:bg-gray-200">Reservations</a>
        <hr class="border-gray-300 my-2">
      <?php endif; ?>
      <a href="#home" class="block py-2 text-gray-700 hover:bg-gray-200">Home</a>
      <a href="#services" class="block py-2 text-gray-700 hover:bg-gray-200">Services</a>
      <a href="#about" class="block py-2 text-gray-700 hover:bg-gray-200">About</a>
      <a href="#album" class="block py-2 text-gray-700 hover:bg-gray-200">Album</a>
      <a href="#reviews" class="block py-2 text-gray-700 hover:bg-gray-200">Reviews</a>
      <a href="#contact" class="block py-2 text-gray-700 hover:bg-gray-200">Contact</a>
      <hr class="border-gray-300 my-2">
      <?php if (isset($full_name) && !empty($full_name)): ?>
        <a href="landing_page_customer/logout.php" class="block py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      <?php else: ?>
        <a href="/landing_page_customer/login.php" class="block py-2 text-gray-700 hover:bg-gray-200">Login</a>
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





<section
    id="home"
    class="bg-cover bg-center relative mb-16 py-16 min-h-[50vh] flex flex-col justify-center items-center"
    style="background-image: url('src/uploads/resort.png');"
>
    <!-- Overlay -->
    <div class="absolute inset-0 bg-blue-950 opacity-60"></div>

    <div class="max-w-screen-xl mx-auto text-center relative z-10 px-4">
        <!-- Logo -->
        <img src="src/uploads/logo.svg" 
             alt="Lobiano's Farm Resort Logo" 
             class="h-32 mx-auto mb-6"
             data-aos="zoom-in" 
             data-aos-duration="1200" 
        />

        <!-- Text Section -->
        <div id="hero-text" class="text-center mb-8">
            <h1 class="text-5xl font-extrabold text-white mb-4"
                data-aos="fade-up"
                data-aos-duration="1200">
                Lobiano's Farm Resort
            </h1>
            <p class="text-xl text-white mx-auto max-w-2xl"
               data-aos="fade-up"
               data-aos-delay="200"
               data-aos-duration="1200">
                Explore our services, discover amazing features, and connect with us to know more.
            </p>
        </div>

        <!-- Check-In Form -->
        <div id="check-in-form" class="flex justify-center w-full"
             data-aos="fade-up"
             data-aos-delay="300"
             data-aos-duration="1200">
            <div class="inline-flex flex-col sm:flex-row border rounded-lg overflow-hidden shadow-lg border-yellow-500">
                <!-- Check-In Date Input -->
                <input
                    type="text"
                    id="check-in"
                    name="check-in"
                    class="px-6 py-3 w-56 sm:w-48 border-r-2 border-yellow-500 opacity-90 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-black"
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
<section id="rates-section" class="bg-white flex flex-col items-center justify-center mt-12 px-4">
  <div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Rates Section -->
    <div class="">
      <h2 class="text-3xl font-extrabold text-center text-gray-900 header-rate" data-aos="fade-up">
        Our Rates
      </h2>
      <p class="mt-4 text-lg text-center text-gray-600 text-rate" data-aos="fade-up">
        Check out our affordable pricing plans designed for everyone.
      </p>

      <!-- Scrollable Horizontal Rates Cards -->
      <div class="mt-8 overflow-x-auto w-full px-5 py-2 scrollable-container" id="rates-container">
    <div class="flex space-x-6 min-w-max sm:min-w-full sm:justify-center justify-start snap-x snap-mandatory">



          <?php
            // Include database connection
            include 'db_connection.php';

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

                    echo "
                    <div class='flex-none mb-5 mt-5 w-[320px] sm:w-[300px] h-[450px] rounded-lg shadow-lg relative rate-card hover:scale-103 hover:shadow-lg transition-all duration-500 ease-in-out opacity-0 flex flex-col justify-between snap-center' 
                        data-aos='fade-up' 
                        data-aos-anchor-placement='bottom-middle' 
                        data-aos-duration='800' 
                        style='will-change: transform, opacity;' 
                        onclick=\"openModal('$picture', '$name', '$description', '$hours_of_stay', '$check_in_time', '$check_out_time', '$price')\">
                        
                        <img class='rounded-lg w-full h-[200px] object-cover' src='src/uploads/rates/$picture' alt='$name'>
                    
                        <div class='p-5 flex flex-col justify-between flex-grow'>
                            <h2 class='text-2xl font-bold text-gray-800'>$name</h2>
                    
                            <div class='text-gray-600 mt-2 flex items-center'>
                                <span class='material-icons mr-2'>schedule</span>
                                <p class='text-gray-600 font-medium'>{$hours_of_stay} hours</p>
                            </div>
                    
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
                    
                            <p class='text-gray-800 font-semibold text-xl mt-4'>₱" . number_format($price, 2) . "</p>
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

<!-- Add this CSS for smooth scrolling -->
<style>
.scrollable-container {
    overflow-x: auto;
    white-space: nowrap;
    display: flex;
    justify-content: flex-start; /* Ensures cards start from the left */
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}

.scrollable-container > div {
    scroll-snap-align: start;
}

  .scrollable-container::-webkit-scrollbar {
    display: none; /* Hide scrollbar for Chrome, Safari */
  }
  .scrollable-container {
    display: flex;
    flex-wrap: nowrap;
  }
  .rate-card {
    scroll-snap-align: center;
  }
</style>



<!-- Add-ons Section -->
<section id="addons-section" class="bg-white flex flex-col items-center justify-center mt-12 px-4">
<div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-16 ml-5">
    <h2 class="text-3xl font-extrabold text-center text-gray-900 header-rate" data-aos="fade-up">
    Our Add-ons
</h2>
<p class="mt-4 text-lg text-center text-gray-600 text-rate" data-aos="fade-up">
    Check out our affordable pricing plans designed for everyone.
</p>


        <!-- Scrollable Horizontal Add-ons Cards -->
        <div class="mt-8 overflow-x-auto w-full px-5 py-2 scrollable-container flex justify-center" id="addons-container">
        <div class="flex space-x-6 min-w-max">

            <?php
                // Include database connection
                include 'db_connection.php';

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
<div class='flex-none mb-5 mt-5 max-w-sm rounded-lg shadow-lg hover:shadow-lg relative addons-card animate-card hover:scale-103 hover:shadow-lg transition-all duration-500 ease-in-out opacity-0' 
                        data-aos='fade-up' 
                        data-aos-anchor-placement='bottom-bottom' 
                        data-aos-duration='800' 
                        style='will-change: transform, opacity;' 
    onclick='openAddonModal(\"$picture\", \"$name\", \"$description\", \"$price\")'>

    <img class='rounded-lg w-[300px] h-[250px] object-cover' src='src/uploads/addons/$picture' alt='$name'>

    <div class='p-5'>
        <h2 class='text-2xl font-bold text-gray-800'>$name</h2>
        <p class='text-gray-800 font-semibold text-xl mt-4'>₱" . number_format($price, 2) . "</p>
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
<section id="video-tours" 
         class="bg-white px-4 delay-[300ms] duration-[800ms] taos:translate-y-[100%] taos:opacity-0" 
         data-taos-offset="300"
         data-aos="fade-up" 
         data-aos-duration="800" 
         data-aos-anchor-placement="top-bottom">
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
<section id="reviews" 
         class="bg-white min-h-screen flex flex-col items-center justify-center pt-16" 
         data-taos-offset="300"
         data-aos="fade-up" 
         data-aos-duration="800" 
         data-aos-anchor-placement="top-bottom">
  <div class="elfsight-app-d8f6591a-6262-46fe-a54f-6d6eefaf6b74" data-elfsight-app-lazy></div>
</section>


<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("../api/fetch_reviews.php") // Adjust path if necessary
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const reviewsContainer = document.getElementById("reviews-container");
                reviewsContainer.innerHTML = ""; // Clear existing content

                data.reviews.forEach(review => {
                    const reviewElement = document.createElement("div");
                    reviewElement.classList.add(
                        "bg-gray-100", "p-6", "rounded-lg", "shadow", "w-72", "min-w-[18rem]",
                        "overflow-hidden", "whitespace-normal"
                    );

                    reviewElement.innerHTML = `
                        <h3 class="text-xl font-bold text-gray-900 truncate">${review.title}</h3>
                        <p class="text-gray-600 mt-2 line-clamp-3">${review.review_text}</p>
                        <p class="text-sm text-gray-500 mt-2">⭐ ${review.rating}/5</p>
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

<style>
/* Hide scrollbar */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Ensures text doesn't overflow */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Limits to 3 lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>


<!-- Contact Us Section -->
<section id="contacts" 
         class="bg-white delay-[300ms] duration-[800ms] taos:translate-y-[100%] taos:opacity-0" 
         data-taos-offset="300"
         data-aos="fade-up" 
         data-aos-duration="800" 
         data-aos-anchor-placement="top-bottom">
  <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
      <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900">Contact Us</h2>
      <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 sm:text-xl">
        Got a technical issue? Want to send feedback about a beta feature? Need details about our Business plan? Let us know.
      </p>
      <form action="#" class="space-y-8">
          <div data-aos="fade-up" data-aos-duration="800">
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
              <input type="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="name@flowbite.com" required>
          </div>
          <div data-aos="fade-up" data-aos-duration="800">
              <label for="subject" class="block mb-2 text-sm font-medium text-gray-900">Subject</label>
              <input type="text" id="subject" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Let us know how we can help you" required>
          </div>
          <div class="sm:col-span-2" data-aos="fade-up" data-aos-duration="800">
              <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Your message</label>
              <textarea id="message" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg shadow-sm border border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Leave a comment..."></textarea>
          </div>
          <button type="submit" class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-fit hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300" data-aos="fade-up" data-aos-duration="800">
            Send message
          </button>
      </form>
  </div>
</section>





<section id="reserve" class="bg-gray-100 flex items-center justify-center pt-16 px-4 mb-100 hidden">
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
        src="src/uploads/about/resort.png" 
        alt="Reservation Steps Image" 
        class="rounded-2xl shadow-lg w-full h-auto object-cover"
      />
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
<footer 
    class="bg-blue-900 py-8" 
    data-aos="fade-up" 
    data-aos-duration="1200"
>
  <div class="text-center">
    <p 
      class="text-sm text-gray-400" 

    >
      © 2025 Your Company. All Rights Reserved.
    </p>
    
    <div 
      class="mt-4 space-x-4" 

    >
      <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a>
      <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
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
        
        <!-- Scrollable description -->
        <p id="modal-description" class="text-gray-600 mt-4 max-w-2xl text-lg" style="max-height: 300px; overflow-y: auto;"></p>

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


<script src="scripts/homepage_animations.js"></script>
<script src="scripts/newhomes.js"></script>
<!-- Elfsight WhatsApp Chat | Untitled WhatsApp Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-b2701a5e-2312-4201-92bf-10db53498839" data-elfsight-app-lazy></div>
<!-- Elfsight Google Reviews | Untitled Google Reviews -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    AOS.init({
        once: false, // Ensures animations trigger every time on scroll
        duration: 1200, 
        easing: 'ease-in-out', 
        offset: 100, // Adjust when animation starts (higher = later)
        anchorPlacement: 'bottom-bottom' // Triggers when the element's bottom reaches the viewport bottom
    });
});

</script>



    
</body>
</html>