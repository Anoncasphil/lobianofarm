<?php
    include('../db_connection.php');
    session_start();
    $sql_rates = "SELECT * FROM rates WHERE status='active'";
    $result_rates = $conn->query($sql_rates);

    $sql_addons = "SELECT * FROM addons WHERE status='active'";
    $result_addons = $conn->query($sql_addons);

?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>888 Lobiano's Farm</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/main_page.css">
    <link rel="icon" href="../src/images/logo.png" type="image/x-icon">
    <style>
/* Hide the scrollbar */
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}

.hide-scrollbar {
    -ms-overflow-style: none; /* IE 10+ */
    scrollbar-width: none; /* Firefox */
}

/* Add smooth scrolling */
#rate_pic_container {
    scroll-behavior: smooth;
}
</style>
</head>
<body id="top_section" class="flex flex-col">
    <section id="header_section" class="flex flex-col justify-self-center self-center h-[100vh] sm:h-[100vh] w-full mb-[5%] bg-[url('../src/images/main_bg.jpg')] bg-center bg-no-repeat bg-cover overflow-hidden z-20">
        <!-- HEADER -->
        <div id="header_option" class="flex flex-row h-per10 w-full bg-green-400 justify-between items-center bg-transparent mt-5 px-[2%]">
            <div id="logo_container" class="flex flex-row ml-5">
                <a class="text-white text-xl flex items-center gap-2">
                    <img src="../src/images/logo.png" class="w-10 h-10" alt="Logo">
                    888 Lobiano's Farm
                </a>
                
            </div>

            <div id="button_container" class="flex mr-10">
                <div class="relative">
                    <button id="menu_toggle" class="block">
                        <i class="fa-solid fa-bars flex self-center text-2xl text-white"></i>
                    </button>
                    <div
                        id="menu_container"
                        class="hidden absolute top-12 right-0 bg-white rounded-md shadow-md w-[150px]">
                        <?php error_log("Session data: " . print_r($_SESSION, true)); ?>
                            <ul class="flex flex-col text-center">
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <li class="p-2">
                                        <a href="profile.php" class="text-black hover:text-blue-500">Profile</a>
                                    </li>
                                    <li class="p-2">
                                        <a href="logout.php" class="text-black hover:text-blue-500">Logout</a>
                                    </li>
                                <?php else: ?>
                                    <li class="p-2">
                                        <a href="login.php" class="text-black hover:text-blue-500">Login</a>
                                    </li>
                                    <li class="p-2">
                                        <a href="register.php" class="text-black hover:text-blue-500">Register</a>
                                    </li>
                        <?php endif; ?>
                            </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END HEADER -->

        <!-- SLOGAN -->
        <div id="slogan_container" class="flex flex-col justify-center self-center items-center w-70per h-per45 text-white mt-10 mb-10 sm:mb-20">
            <div id="main_slogan_container" class="text-5xl">
                <p id="main_slogan_text" class="text-center font-bold leading-tight">
                    Swim in Style,<br>Customized For<br>Your Comfort
                </p>
            </div>
            <div id="sub_tag_container" class="mt-3">
                <p id="sub_slogan_text" class="text-center">
                    Discover unbeatable deals on our swimming pool resort. Start<br>planning your dream retreat today!
                </p>
            </div>
        </div>
        <!-- END SLOGAN -->

        <!-- CALENDAR BUTTON -->


        <div id="calendar_button_container" class="block sm:flex sm:justify-center sm:items-center bg-black p-2 w-[70%] sm:w-[25%] self-center gap-2 bg-opacity-50 rounded-2xl">
            <!-- Date Input -->
            <div id="date_input" class="relative w-full sm:w-[70%] mb-2 sm:mb-0">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input id="datepicker-autohide" datepicker datepicker-autohide type="text" class="cursor-pointer bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-white dark:border-gray-300 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Check Available Dates" autocomplete="off">
            </div>
        
            <!-- Book Link -->
            <div id="book_link" class="flex items-center bg-[#37863B] h-[42px] w-full sm:w-[25%] justify-center rounded-md">
                <a href="../reservation/reservation.php" class="text-white">Book <i class="fa-sharp fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>
            
        <!-- END CALENDAR BUTTON -->
    </section>

    <section id="about_section" class="hidden sm:flex flex-row items-center justify-center self-center w-full h-screen bg-[#f4ece2]">
        <div class="flex w-[90%] h-full" data-aos="zoom-in">
            <div id="about_pic" class="flex justify-center w-[100%] sm:w-[45%] h-[80%] sm:mr-[5%] rounded-2xl self-center">
                <img src="/images/RP1.jpg" class="h-full w-auto sm:rounded-2xl">
            </div>
    
            <!-- About Us Text -->
            <div id="about_us_txt" class="flex flex-col justify-center w-[100%] sm:w-[50%] h-full sm:h-[90%] rounded-xl p-6 sm:p-10">
                <div id="about_us_tag" class="mb-5">
                    <h1 class="text-2xl font-bold text-orange-500">About Us</h1>
                </div>
    
                <div id="about_us_slogan" class="mb-5 text-black">
                    <h1 class="text-4xl sm:text-6xl font-semibold leading-snug">Creating unforgettable memories...</h1>
                </div>
    
                <div id="about_us_description" class="mb-5 text-black">
                    <p class="text-base sm:text-lg leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
    
                <!-- Fixed Button -->
                <a 
                href="about_us.html" 
                id="about_us_link" 
                class="bg-orange-400 hover:bg-[#766641] hover:drop-shadow-lg text-white font-medium py-2 px-4 rounded-lg self-start">
                Learn more about us
            </a>
            </div>
        </div>
    </section>
    
    </div>
    <!--  END ALBUM -->

    <?php
// Assuming a database connection is already established
$query = "SELECT name, picture FROM events WHERE status = 'active' ORDER BY created_at DESC LIMIT 4";

// Execute the query
$result = mysqli_query($conn, $query);

// Check for query execution error
if (!$result) {
    echo "<script>console.error('Error executing query: " . mysqli_error($conn) . "');</script>";
    die("Error executing query: " . mysqli_error($conn));
}
?>
<!-- Event Section -->
<section id="event_section" class="flex flex-col items-center w-full py-10 px-4">
    <div id="event_header" class="text-center mt-10">
        <h1 class="text-3xl font-bold text-black">Events</h1>
        <p class="mt-3 text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>

    <div id="event_pic_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-screen-xl mx-auto mt-8">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $picture = $row['picture']; // This should be the file name stored in the database

                // Assuming the picture field contains the image filename
                $imagePath = "../src/uploads/events/" . $picture;

                // Check if the picture file exists
                if (!empty($picture) && file_exists($imagePath)) {
                    echo '
                    <div class="flex flex-col w-full bg-white rounded-lg shadow-md overflow-hidden mt-4">
                        <div class="w-full h-[300px] bg-cover bg-center" style="background-image: url(' . $imagePath . ');">
                        </div>
                        <div class="flex items-center justify-center h-[60px] bg-white text-black px-4 py-2">
                            <p class="text-center text-xl font-semibold">' . $name . '</p>
                        </div>
                    </div>';
                } else {
                    // If no picture is available or the file doesn't exist, display a placeholder image
                    echo '
                    <div class="flex flex-col w-full bg-white rounded-lg shadow-md overflow-hidden mt-4">
                        <div class="w-full h-[300px] bg-gray-300 flex justify-center items-center">
                            <p class="text-gray-700">No Image Available</p>
                        </div>
                        <div class="flex items-center justify-center h-[60px] bg-white text-black px-4 py-2">
                            <p class="text-center text-xl font-semibold">' . $name . '</p>
                        </div>
                    </div>';
                    echo "<script>console.error('No image available for event: " . $name . "');</script>";
                }
            }
        } else {
            echo '<p class="text-center text-white">No events available.</p>';
            echo "<script>console.error('No active events found in the database.');</script>";
        }
        ?>
    </div>
</section>

<?php
// Assuming a database connection is already established
$query = "SELECT name, picture, hoursofstay, price, id FROM rates ORDER BY created_at DESC LIMIT 4"; // Limit to 4 rates

// Execute the query
$result_rates = mysqli_query($conn, $query);

// Check for query execution error
if (!$result_rates) {
    echo "<script>console.error('Error executing query: " . mysqli_error($conn) . "');</script>";
    die("Error executing query: " . mysqli_error($conn));
}
?>
<!-- Rates Section -->
<section id="rates_section" class="flex flex-col items-center w-full py-10 px-4">
    <div id="rates_header" class="text-center mt-10">
        <h1 class="text-3xl font-bold text-black">Rates</h1>
        <p class="mt-3 text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>

    <div id="rate_pic_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-screen-xl mx-auto mt-8">
        <?php
        if (mysqli_num_rows($result_rates) > 0) {
            while ($rate = mysqli_fetch_assoc($result_rates)) {
                // Fetch and encode image data as base64
                $imageData = base64_encode($rate['picture']);
        ?>
                <!-- RATES CARD START -->
                <div id="rates_card" class="flex flex-col w-full bg-white rounded-lg shadow-md overflow-hidden mt-4">
                    <div id="rates_card_pic" class="w-full h-[300px] bg-cover bg-center" style="background-image: url('data:image/jpeg;base64,<?php echo $imageData; ?>');">
                    </div>
                    <div class="flex flex-col items-center justify-center h-[100px] bg-white text-black px-4 py-2">
                        <h1 class="text-xl font-semibold"><?php echo $rate['name']; ?></h1>
                        <p class="text-sm text-gray-500"><i class="fa-solid fa-clock"></i> <?php echo $rate['hoursofstay']; ?> hours</p>
                        <p class="text-lg font-bold"><?php echo '₱' . number_format($rate['price'], 2); ?></p>
                        <p class="text-xs text-gray-500">Includes taxes & fees</p>
                    </div>
                    <div class="flex justify-center items-center py-3 bg-[#37863B] text-white">
                        <button type="button" id="view_details_btn" class="w-[80%] bg-[#37863B] rounded-lg text-sm" data-id="<?php echo $rate['id']; ?>" data-type="rate">
                            View Details
                        </button>
                    </div>
                </div>
                <!-- RATES CARD END -->
        <?php
            }
        } else {
            echo '<p class="text-center text-white">No rates available.</p>';
            echo "<script>console.error('No rates found in the database.');</script>";
        }
        ?>
    </div>
</section>





    <!-- START AMENITIES -->

    <section id="amenities_section" class="flex flex-col self-center w-[80%] h-[75vh]">    
        <div id="amenities_header" class=" w-full h-[13%] mt-[3%] mb-8">
            <h1 class="text-3xl font-bold">Add-Ons</h1>
            <p class="mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
        </div>

        <div id="amenities_pic_container" class="flex flex-col w-full h-[80%] gap-y-2 sm:gap-x-5">
            <div id="amenities_container" class="flex flex-row w-full h-full overflow-x-auto gap-5 scroll-smooth scrollbar-hide sm:scrollbar-default">
                
            <?php while($addons = $result_addons->fetch_assoc()) { 
                            // Fetch and encode image data as base64
                            $imageData = base64_encode($addons['picture']); // Update column name here
                            ?>
                        <!-- AMENITIES CARD START -->
                            <div id="addons_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[90%] shrink-0 shadow-lg rounded-2xl">
                                <div id="addons_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                                <!-- Embed the Base64 image directly in the src -->
                                <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" loading="lazy" class="w-full h-[160px] rounded-xl" alt="Amenity Image">
                                </div>
                                <!-- Amenities Description(name, price, hours, tag) -->
                                <div id="addons_description" class="flex flex-col w-full h-[40%]">
                                    <h1 id="addons_name" class="text-xl font-bold"><?php echo $addons['name']; ?></h1>
                                    <p id="addons_price" class="text-lg"><?php echo '₱' . number_format($addons['price'], 2); ?></p>
                                    <p id="addons_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                                </div>
                                <div class="flex w-full h-[30%] justify-center items-center">
                                    <button type="button" id="view_details_btn" class="w-[80%] h-[70%] bg-[#37863B] rounded-lg" data-id="<?php echo $addons['id']; ?>" data-type="addon">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        <?php } ?>

            </div>          
           
        </div>

    </section>


    <!-- END AMENITIES -->


    <!-- START VIDEO TOUR -->
    <div class="flex justify-center bg-[#1e344b] text-white h-screen">
        <section id="video_section" class="flex flex-col self-center w-[80%] h-[90vh]">
            <div id="video_header" class=" w-full h-[13%] mt-[3%] mb-8">
                <h1 class="text-3xl font-bold">Video Tour</h1>
                <p class="mt-5">Embark on a digital journey through our exclusive haven. Welcome to your virtual tour of luxury and leisure at our private pool resort.</p>
            </div>

            <div id="video_container" class="w-full h-full">
                <iframe width="420" height="345" class="w-full h-full" src="https://www.youtube.com/embed/tgbNymZ7vqY?playlist=tgbNymZ7vqY&loop=1">
                </iframe>
            </div>
        </section>
    </div>
    <!-- END VIDEO TOUR -->


    <!-- START REVIEWS -->
     
    <section class="flex flex-col self-center h-[80vh] w-[80%] mt-[1%]">
        <div id="review_header" class=" w-full h-[13%] mt-[5%] mb-8">
            <h1 class="text-3xl font-bold">Reviews</h1>
            <p class="mt-5">Discover what guests are saying.</p>
        </div>

        <div id="overall_reviews" class="flex flex-row w-full sm:w-[30%] h-[10%]">
            <div id="reviews_num" class="flex flex-col w-[50%] h-full">
                <div id="upper_left" class="flex flex-row w-[50%] h-[50%]">
                    <h1 id="overall_ratings" class="text-2xl mr-3 font-bold">5.0</h1>

                    <div id="overall_stars" class="flex items-center">
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                    </div>


                </div>
                <p>Based on reviews: <span id="reviews_count">15</span></p>
            </div>


            <div id="sign_up" class="flex justify-center items-center w-[100%] sm:w-[35%] h-full">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="submit_review.php" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg whitespace-nowrap">Submit a review</a>
                <?php else: ?>
                    <a href="register.php" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg whitespace-nowrap">Sign up</a>                <?php endif; ?>
            </div>
        </div>

        <div id="review_card_container" class="block sm:flex flex-row justify-between w-full mt-[3%]">
            <!-- REVIEW CARD START -->
            <div id="review_card" class="flex flex-col w-full sm:w-[30%] mb-[5%] rounded-2xl shadow-xl bg-white h-min overflow-hidden">
                <div id="review_text_container" class="w-full h-auto px-5">
                    <p class="border-b-2 py-[3%]">Lorem ipsum dolor sit amet. Et repellat voluptas aut iste quia nam fugiat harum et nihil debitis! Aut fugiat rerum aut aperiam perferendis aut omnis laboriosam aut quos sint aut praesentium vero in excepturi assumenda ut voluptas aliquid.</p>
                </div>
                <div id="user_info_review" class="flex flex-row justify-between items-center h-[18%] p-5 w-full top-0">
                    <p id="reviewer_name">ASTA DAMN</p>
                    <div id="star_review" class="flex flex-row justify-center items-center">
                        <p>5.0</p>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div id="review_card" class="flex flex-col w-full sm:w-[30%] mb-[5%] rounded-2xl shadow-xl bg-white h-min overflow-hidden">
                <div id="review_text_container" class="w-full h-auto px-5">
                    <p class="border-b-2 py-[3%]">Lorem ipsum dolor sit amet. Et repellat voluptas aut iste quia nam fugiat harum et nihil debitis! Aut fugiat rerum aut aperiam perferendis aut omnis laboriosam aut quos sint aut praesentium vero in excepturi assumenda ut voluptas aliquid.</p>
                </div>
                <div id="user_info_review" class="flex flex-row justify-between items-center h-[18%] p-5 w-full top-0">
                    <p id="reviewer_name">ASTA DAMN</p>
                    <div id="star_review" class="flex flex-row justify-center items-center">
                        <p>5.0</p>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div id="review_card" class="flex flex-col w-full sm:w-[30%] mb-[5%] rounded-2xl shadow-xl bg-white h-min overflow-hidden">
                <div id="review_text_container" class="w-full h-auto px-5">
                    <p class="border-b-2 py-[3%]">Lorem ipsum dolor sit amet. Et repellat voluptas aut iste quia nam fugiat harum et nihil debitis! Aut fugiat rerum aut aperiam perferendis aut omnis laboriosam aut quos sint aut praesentium vero in excepturi assumenda ut voluptas aliquid.</p>
                </div>
                <div id="user_info_review" class="flex flex-row justify-between items-center h-[18%] p-5 w-full top-0">
                    <p id="reviewer_name">ASTA DAMN</p>
                    <div id="star_review" class="flex flex-row justify-center items-center">
                        <p>5.0</p>
                        <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                    </div>
                </div>
            </div>

            

            <!-- REVIEW CARD END -->
        </div>
        
        <div id="review_page_number" class="flex flex-row self-center justify-center mt-[1%]">
            <div id="page_number_container" class="flex justify-between gap-3">
                <a class="border-2 border-black px-2 py-2 rounded-lg" href="#">1</a>
                <a class="border-2 border-black px-2 py-2 rounded-lg" href="#">2</a>
                <a class="border-2 border-black px-2 py-2 rounded-lg" href="#">...</a>
                <a class="border-2 border-black px-2 py-2 rounded-lg" href="#">5</a>
            </div>
        </div>

    </section>

    <!-- END REVIEWS -->

    <a href="#header_section" class="fixed flex item-center justify-center bottom-10 right-10 w-14 h-14 bg-black rounded-full z-20 "><i class="fa-solid fa-arrow-up text-white mt-[30%] text-2xl"></i></a>



    <footer class="flex justify-between w-full h-64 bg-gradient-to-r from-blue-500 to-cyan-500 z-30 mt-[5%]">
            <div id="left_side" class="flex flex-col w-[35%] h-full px-16 gap-4 ">
                <div class="flex flex-col gap-4 mt-[10%]">
                    <p class="text-xl text-white">Make an Enquiry:</p>
                    <h1 class="text-3xl text-white">Contact us now to start your adventure</h1>
                    <button id="reserve_button" class="bg-orange-400 hover:bg-[#766641] hover:drop-shadow-lg w-[30%] rounded-full p-3 text-white">Reserve now!</button>
                </div>
            </div>
        <div id="right_side" class="flex flex-roww-[40%] h-full text-white">
           <div id="socials_container" class="flex flex-col w-[35%] h-48 px-16 gap-4 mt-[10%]">
                <div class="flex flex-col">
                    <p class="text-xl mb-4 text-white">Connect:</p>
                    <a href="#" class="">Instagram</a>
                    <a>Facebook</a>
                </div>
           </div>
           <div id="contact_container" class="flex flex-col w-[50%] h-48 px-16 gap-4 mt-[10%]">
            <div class=" flex flex-col">
                <p class="text-xl mb-4 text-white">Contact us:</p>
                <p>09190000000</p>
                <p>john.doe@email.com</p>
                <p>Privacy</p>
            </div>
       </div>
        </div>
    </footer>

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
    <script src="../scripts/main_page.js"></script>
    <!-- END SCRIPT -->

        <!-- Add before closing body tag -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-[80%] max-w-4xl h-[80%] rounded-lg p-6 flex">
            <div class="w-1/2 pr-4">
                <img id="modalImage" src="" class="w-full h-full object-cover rounded-lg">
            </div>
            <div class="w-1/2 pl-4 flex flex-col">
                <h2 id="modalTitle" class="text-2xl font-bold mb-4 border-b-2 pb-3"></h2>
                <p id="modalPrice" class="text-xl mb-2"></p>
                <p id="modalDescription" class="text-gray-600"></p>
                <button id="closeModal" class="mt-auto bg-red-500 text-white px-4 py-2 rounded-lg self-end">Close</button>
            </div>
        </div>
    </div>

    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-[80%] max-w-4xl h-[80%] rounded-lg p-6 flex flex-col">
            <h2 class="text-2xl font-bold mb-4">Leave a Review</h2>
            <div id="starRating" class="flex mb-4">
                <span class="star selected" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            <textarea id="reviewText" class="w-full h-2/3 p-4 border rounded-lg mb-4" placeholder="Write your review here..."></textarea>
            <div class="flex flex-row gap-2 self-end">
                <button id="submitReview" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Submit</button>
                <button id="closeReviewModal" class="bg-red-500 text-white px-4 py-2 rounded-lg">Close</button>
            </div>
        </div>
    </div>

</body>
</html>
