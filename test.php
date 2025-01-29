<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        .swiper-wrapper {
            width: 100%;
            height: max-content !important;
            padding-bottom: 64px !important;
            -webkit-transition-timing-function: linear !important;
            transition-timing-function: linear !important;
            position: relative;
        }
        .swiper-pagination-bullet {
            background: #4f46e5;
        }
        .swiper-button-prev, .swiper-button-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
        }
        .swiper-button-prev {
            left: -30px; /* Moves the left button more outside */
        }
        .swiper-button-next {
            right: -30px; /* Moves the right button more outside */
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Rates & Add-ons Section -->
<section id="rates-addons" class="bg-white min-h-screen flex flex-col items-center justify-center pt-16 px-4">
  <div class="max-w-screen-xl mx-auto text-center">
    
    <!-- Rates Section -->
    <div class="mb-16 relative z-10">
      <h2 class="text-3xl font-extrabold text-gray-900">Our Rates</h2>
      <p class="mt-4 text-lg text-gray-600">Check out our affordable pricing plans designed for everyone.</p>
      
      <!-- Scrollable Horizontal Rates Cards with Swiper -->
      <div class="w-full relative">
        <div class="swiper multiple-slide-carousel swiper-container relative">
          <div class="swiper-wrapper mb-16">
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
                    $picture = $row['picture'];

                    echo "
                    <div class='swiper-slide'>
                      <div class='bg-indigo-50 rounded-2xl h-96 flex justify-center items-center'>
                        <div class='text-center'>
                          <img class='rounded-lg w-full h-60 object-cover mx-auto' src='../src/uploads/rates/$picture' alt='$name'>
                          <h3 class='text-xl font-bold text-gray-900 mt-4'>$name</h3>
                          <p class='text-gray-600 mt-2'>Stay for $hours_of_stay hours.</p>
                          <p class='text-gray-900 font-semibold text-xl mt-4'>₱$price</p>
                          <button onclick='selectRate(\"$id\", \"$name\", \"$price\")' class='mt-4 bg-blue-600 text-white w-full font-bold py-3 px-4 rounded-md hover:bg-blue-700 transition duration-200'>
                              Select
                          </button>
                        </div>
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

          <!-- Navigation Buttons -->
          <div class="absolute flex justify-center items-center m-auto left-0 right-0 w-fit bottom-12">
            <button id="slider-button-left" class="swiper-button-prev group !p-2 flex justify-center items-center border border-solid border-indigo-600 !w-12 !h-12 transition-all duration-500 rounded-full hover:bg-indigo-600 !-translate-x-16">
              <svg class="h-5 w-5 text-indigo-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M10.0002 11.9999L6 7.99971L10.0025 3.99719" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
            <button id="slider-button-right" class="swiper-button-next group !p-2 flex justify-center items-center border border-solid border-indigo-600 !w-12 !h-12 transition-all duration-500 rounded-full hover:bg-indigo-600 !translate-x-16">
              <svg class="h-5 w-5 text-indigo-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M5.99984 4.00012L10 8.00029L5.99748 12.0028" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add-ons Section -->
    <div class="relative z-10">
      <h2 class="text-3xl font-extrabold text-gray-900">Add-ons</h2>
      <p class="mt-4 text-lg text-gray-600">Explore our additional services to enhance your experience.</p>

      <!-- Add-ons Cards -->
      <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-100 p-6 rounded-2xl shadow-lg">
          <h3 class="text-xl font-bold text-gray-900">Breakfast</h3>
          <p class="text-gray-600 mt-2">Start your day with a delicious meal.</p>
          <p class="text-gray-900 font-semibold mt-4">$10</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-2xl shadow-lg">
          <h3 class="text-xl font-bold text-gray-900">Massage</h3>
          <p class="text-gray-600 mt-2">Relax with a soothing massage session.</p>
          <p class="text-gray-900 font-semibold mt-4">$30</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-2xl shadow-lg">
          <h3 class="text-xl font-bold text-gray-900">Kayaking</h3>
          <p class="text-gray-600 mt-2">Enjoy an adventure on the water.</p>
          <p class="text-gray-900 font-semibold mt-4">$20</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-2xl shadow-lg">
          <h3 class="text-xl font-bold text-gray-900">Bonfire Night</h3>
          <p class="text-gray-600 mt-2">Experience a cozy bonfire under the stars.</p>
          <p class="text-gray-900 font-semibold mt-4">$15</p>
        </div>
      </div>
    </div>

  </div>
</section>


</body>
</html>
