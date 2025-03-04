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

  <script src="https://cdn.jsdelivr.net/npm/@flowbite/web"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>
  
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
  html, body {
  overflow-x: hidden;
  width: 100%;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  position: relative; /* Ensures no unexpected overflow */
}

.section {
    overflow-y: hidden;
}
.swiper-container {
  width: 100%;
  overflow: hidden;
}

.swiper-slide {
  width: 100%;
  overflow: hidden;
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
        <a href="landing_page_customer/login.php" class="flex items-center space-x-3 text-sm bg-white hover:bg-gray-300 text-blue-900 font-semibold rounded-lg px-6 py-3 transition-all duration-300 ease-in-out shadow-md hover:shadow-lg">
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
<section id="rates-section" class="bg-white flex flex-col items-center justify-center pt-14 px-4">
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

<?php
include 'db_connection.php';

// Fetch categories from folders table
$sql = "SELECT id, name, path FROM folders WHERE archived = 0";
$result = $conn->query($sql);
?>

<!-- Album Section -->
<section id="album" class="relative">

    <!-- Category Buttons -->
    <div class="flex items-center justify-center py-4 md:py-8 flex-wrap z-20 relative" data-aos="fade-up">
        <?php 
        $firstCategoryId = null; // Store the first category ID to display it by default
        while ($row = $result->fetch_assoc()):
            if (!$firstCategoryId) {
                $firstCategoryId = $row['id']; // Set the first category ID
            }
        ?>
        <button type="button" class="category-button text-gray-900 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-900 rounded-full text-base font-medium px-5 py-2.5 text-center me-3 mb-3" data-id="<?php echo $row['id']; ?>" onclick="toggleCategory(<?php echo $row['id']; ?>)">
            <?php echo htmlspecialchars($row['name']); ?>
        </button>
        <?php endwhile; ?>
    </div>

    <!-- Categories content (Hidden initially) -->
    <div id="categories-content">
        <?php
        // Reset pointer to the first result for displaying content.
        mysqli_data_seek($result, 0);
        while ($row = $result->fetch_assoc()):
            // Remove the ../ from the path
            $path = str_replace('../', '', $row['path']);
        ?>
        <div id="category-<?php echo $row['id']; ?>" class="category-content" style="display: none;" data-aos="fade-up">
            <div class="relative">

               <!-- Full-screen carousel -->
<div id="indicators-carousel-28-<?php echo $row['id']; ?>" class="relative w-full h-[100vh] md:h-[100vh] overflow-hidden" data-carousel="static">
    <div class="relative h-full overflow-hidden rounded-lg">
        <?php
        $files = glob($path . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $firstItem = true;
        foreach ($files as $index => $file):
            if (file_exists($file)): ?>
                <div class="duration-700 ease-in-out <?php echo $firstItem ? 'block' : 'hidden'; ?>" data-carousel-item <?php echo $firstItem ? 'data-carousel-item="active"' : ''; ?>>
                    <img src="<?php echo htmlspecialchars($file); ?>" class="absolute block w-full h-full object-fill top-0 left-0" alt="Image <?php echo $index + 1; ?>">
                </div>
                <?php $firstItem = false; ?>
            <?php else: ?>
                <p>File does not exist: <?php echo htmlspecialchars($file); ?></p>
            <?php endif;
        endforeach; ?>
    </div>

    <!-- Slider indicators -->
    <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
        <?php foreach ($files as $index => $file): ?>
            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide <?php echo $index + 1; ?>" data-carousel-slide-to="<?php echo $index; ?>"></button>
        <?php endforeach; ?>
    </div>

    <!-- Slider controls -->
    <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"></path>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
</div>


                <!-- Overlay category name (at the bottom of the image with white background) -->
                <div class="absolute bottom-0 left-0 w-full bg-white bg-opacity-80 text-gray-900 font-bold text-4xl p-4 z-10">
                    <h2 class="text-4xl"><?php echo htmlspecialchars($row['name']); ?></h2>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</section>



<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the first category and display it by default
            const firstCategoryId = document.querySelector('.category-button').getAttribute('data-id');
            toggleCategory(firstCategoryId);
        });

        function toggleCategory(id) {
            // Hide all categories first
            const allCategories = document.querySelectorAll('.category-content');
            allCategories.forEach(function (category) {
                category.style.display = 'none';
            });

            // Show the selected category
            const selectedCategory = document.getElementById('category-' + id);
            if (selectedCategory) {
                selectedCategory.style.display = 'block';
            }
        }
    </script>



<!-- About Us Section -->
<section id="about" class="bg-white flex items-center justify-center px-4 py-30 mb-16">
  <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-start gap-8 mb-8">
    
    <!-- Image Section -->
    <div class="w-full md:w-1/2 flex justify-start"
         data-aos="fade-up" 
         data-aos-anchor-placement="top-bottom"
         data-aos-duration="1000"
         data-aos-delay="200">
      <img 
        src="src/uploads/about/resort.png" 
        alt="About Us Image" 
        class="rounded-lg shadow-lg w-full h-auto object-cover"
        style="height: 450px; object-fit: cover;" 
      />
    </div>

    <!-- Text Section -->
    <div class="w-full md:w-1/2 text-left md:mx-8 px-6 py-4 flex items-center"
         data-aos="fade-up" 
         data-aos-anchor-placement="top-bottom"
         data-aos-duration="1000"
         data-aos-delay="300">
      <div>
        <h2 class="text-3xl font-extrabold text-gray-900 mb-4">About Us</h2>
        <p class="mt-4 text-lg text-gray-600">
          Welcome to 888 Lobiano's Farm Resort, where nature meets comfort. Our goal is to provide a serene escape where guests can unwind, reconnect with nature, and create unforgettable memories. Whether you're here for a weekend retreat or a special celebration, our resort offers a perfect blend of tranquility and adventure.
        </p>

      </div>
    </div>

  </div>
</section>


<style>
  @media (max-width: 768px) {
    #about .flex {
      flex-direction: column;
    }
    #about img {
      height: auto;
    }
  }
</style>

<!-- Location Section -->
<section id="locations" class="bg-white flex items-center justify-center px-6 md:px-12 mb-16" 
         data-aos="fade-up" 
         data-aos-anchor-placement="top-bottom"
         data-aos-duration="800">
  
  <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-center gap-8 mb-8">
    
    <!-- Text Section -->
    <div class="w-full md:w-1/2 text-center md:text-left md:mx-8 px-6 py-4" 
         data-aos="fade-up" 
         data-aos-anchor-placement="top-bottom"
         data-aos-duration="800"
         data-aos-delay="200">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Our Location</h2>
      <p class="mt-4 text-base md:text-lg lg:text-xl text-center md:text-left text-gray-600">
        We are located at Lobiano's Farm 888, nestled in the serene countryside. It's the perfect getaway for relaxation, adventure, and making lasting memories. Come visit us for a tranquil escape amidst nature's beauty.
      </p>
      <a 
        href="https://www.google.com/maps?q=Lobiano's+Farm+888" 
        target="_blank"
        class="inline-block mt-4 px-6 py-3 bg-blue-600 text-white text-lg font-medium rounded-lg shadow-md hover:bg-blue-700 transition-all"
      >
        View on Google Maps
      </a>
    </div>

    <!-- Map Section -->
    <div class="w-full md:w-1/2" 
         data-aos="fade-up" 
         data-aos-anchor-placement="top-bottom"
         data-aos-duration="800"
         data-aos-delay="300">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1586.8824792813755!2d120.89532436717096!3d14.14926979329477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd79eaf08918e9%3A0xf195c7d93933a8a1!2sLobiano%60s%20Farm%20888!5e1!3m2!1sen!2sph!4v1739810756694!5m2!1sen!2sph" 
        class="w-full h-[250px] md:h-[350px] lg:h-[450px] rounded-lg shadow-lg"
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

  </div>
</section>







  


<!-- Video Tour Section -->
<section id="video-tours" 
         class="bg-white px-4 delay-[300ms] duration-[800ms] taos:translate-y-[100%] taos:opacity-0" 
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
  data-aos="fade-up" 
  data-aos-duration="800">
  <!-- Elfsight Google Reviews | Untitled Google Reviews -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-33205b19-1dd7-4748-a273-3b0d08cd13c1" data-elfsight-app-lazy></div>
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

<section class="bg-white">
    <div class="container px-6 py-12 mx-auto">
        <div data-aos="fade-up">
            <p class="font-medium text-blue-900">Contact us</p>

            <h1 class="mt-2 text-2xl font-semibold text-gray-800 md:text-3xl">Chat to our friendly team</h1>

            <p class="mt-3 text-gray-500">We’d love to hear from you. Please fill out this form or shoot us an email.</p>
        </div>

        <div class="grid grid-cols-1 gap-12 mt-10 lg:grid-cols-2">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                <div data-aos="fade-up">
                    <span class="inline-block p-3 text-blue-900 rounded-full bg-blue-100/80">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </span>

                    <h2 class="mt-4 text-base font-medium text-gray-800">Email</h2>
                    <p class="mt-2 text-sm text-gray-500">Our friendly team is here to help.</p>
                    <p class="mt-2 text-sm text-blue-900">lobianofarm@gmail.com</p>
                </div>



                <div data-aos="fade-up" data-aos-delay="200">
                    <span class="inline-block p-3 text-blue-900 rounded-full bg-blue-100/80">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </span>
                    
                    <h2 class="mt-4 text-base font-medium text-gray-800">Office</h2>
                    <p class="mt-2 text-sm text-gray-500">Come say hello at resort.</p>
                    <p class="mt-2 text-sm text-blue-900">4VXW+QMV, Kaykwit Road, Indang, Cavite</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="300">
                    <span class="inline-block p-3 text-blue-900 rounded-full bg-900-100/80">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </span>
                    
                    <h2 class="mt-4 text-base font-medium text-gray-800">Phone</h2>
                    <p class="mt-2 text-sm text-gray-500">Mon-Fri from 8am to 5pm.</p>
                    <p class="mt-2 text-sm text-blue-900">+63 9693213556</p>
                </div>
            </div>

            <div class="p-4 py-6 rounded-lg bg-gray-50 md:p-8" data-aos="fade-up" data-aos-delay="400">
                <form id="contact-form" method="POST" action="api/send_email_contact.php">
                    <div class="-mx-2 md:items-center md:flex">
                        <div class="flex-1 px-2">
                            <label class="block mb-2 text-sm text-gray-600">First Name</label>
                            <input type="text" name="first_name" placeholder="John " class="block w-full px-5 py-2.5 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                        </div>

                        <div class="flex-1 px-2 mt-4 md:mt-0">
                            <label class="block mb-2 text-sm text-gray-600">Last Name</label>
                            <input type="text" name="last_name" placeholder="Doe" class="block w-full px-5 py-2.5 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2 text-sm text-gray-600">Email address</label>
                        <input type="email" name="email" placeholder="johndoe@example.com" class="block w-full px-5 py-2.5 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                    </div>

                    <div class="w-full mt-4">
                        <label class="block mb-2 text-sm text-gray-600">Message</label>
                        <textarea name="message" class="block w-full h-32 px-5 py-2.5 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg md:h-56 focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Message"></textarea>
                    </div>

                    <button class="w-full px-6 py-3 mt-4 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-900 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                        Send message
                    </button>
                </form>
            </div>
        </div>
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
<footer class="bg-blue-900 py-8">
  <div class="text-center">
    <p class="text-sm text-gray-400">
      © 2025 Your Company. All Rights Reserved.
    </p>
    
    <div class="mt-4 space-x-4">
      <a href="" class="text-gray-400 hover:text-white">Privacy Policy</a>
      <a href="tac.html" class="text-gray-400 hover:text-white">Terms of Service</a>
    </div>
  </div>
</footer>





<!-- Check-in Date Modal -->
<div id="checkInModal" class="fixed mt-20 right-4 z-50 hidden bg-red-500 text-white rounded-lg px-4 py-3 shadow-lg">
  <div class="flex justify-between items-center">
    <p id="modalMessage" class="modal-message">Pleasse selects a check-in date.</p>
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




<!-- Check-in Date Modal
<div id="checkInModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
    <div class="flex justify-between items-center">
      <p id="modalMessage" class="text-gray-700">Please select a check-in date.</p>
      <button onclick="closeModal()" class="text-gray-700 font-bold">X</button>
    </div>
  </div>
</div> -->

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

<script src="scripts/newhomes.js"></script>

<!-- Elfsight Facebook Chat | Untitled Facebook Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-ba949789-bf48-4f26-a7e1-ceb2bc7e1123" data-elfsight-app-lazy></div>
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
        duration: 500, 
        easing: 'ease-in-out', 
        anchorPlacement: 'bottom-bottom' // Triggers when the element's bottom reaches the viewport bottom
    });
});

</script>

<!-- Modal -->
<div id="custom-modal">
    <div class="modal-content">
        <h2>Check-in Date Required</h2>
        <p>Please select a check-in date before proceeding.</p>
        <button id="close-modals">OK</button>
    </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM Loaded. Initializing...");

    const bookButton = document.getElementById("book-btn");
    const modal = document.getElementById("custom-modal");
    const closeModal = document.getElementById("close-modals");
    const checkInDateInput = document.getElementById("check-in");

    if (!bookButton || !modal || !closeModal || !checkInDateInput) {
        console.error("❌ One or more modal elements not found!");
        return;
    }

    console.log("✅ All modal elements found!");

    // Ensure modal is hidden on page load
    modal.style.display = "none";

    // Show modal if no check-in date is selected
    bookButton.addEventListener("click", function (event) {
        event.preventDefault();
        console.log("🟢 Book button clicked!");

        const selectedDate = checkInDateInput.value;
        console.log("Selected Date:", selectedDate);

        if (selectedDate) {
            localStorage.setItem("selectedDate", JSON.stringify({ checkIn: selectedDate }));
            console.log("✅ Redirecting to booking page...");
            window.location.href = "landing_page_customer/booking.php";
        } else {
            console.log("⚠️ No check-in date selected. Showing modal...");
            modal.style.display = "flex";
            document.body.classList.add("modal-open"); // Prevent scrolling
        }
    });

    // Close modal when clicking "OK"
    closeModal.addEventListener("click", function () {
        console.log("🔴 Closing modal...");
        modal.style.display = "none";
        document.body.classList.remove("modal-open");
    });

    // Close modal if clicking outside modal content
    modal.addEventListener("click", function (event) {
        if (event.target === modal) {
            console.log("🔴 Closing modal (clicked outside)...");
            modal.style.display = "none";
            document.body.classList.remove("modal-open");
        }
    });
});
</script>
<style>
/* Modal Background */
#custom-modal {
    display: none; /* Ensure it is hidden by default */
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6); /* Dark overlay */
    backdrop-filter: blur(3px);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* Modal Content */
.modal-content {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 320px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease-in-out;
}

/* Modal Title */
.modal-content h2 {
    font-size: 20px;
    margin-bottom: 10px;
}

/* Modal Text */
.modal-content p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

/* OK Button */
#close-modals {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}

#close-modals:hover {
    background: #0056b3;
}

/* Prevent scrolling when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

</style>
</body>
</html>