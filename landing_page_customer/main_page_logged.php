<?php
    include('../db_connection.php');
    session_start();
    $sql_rates = "SELECT * FROM rates WHERE status='active'";
    $result_rates = $conn->query($sql_rates);

    $sql_addons = "SELECT * FROM addons WHERE status='active'";
    $result_addons = $conn->query($sql_addons);

    function updateReviewStats($conn) {
        $sql = "SELECT 
                COUNT(*) as total_reviews,
                ROUND(AVG(rating), 1) as avg_rating
                FROM reviews";
        
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    // Get updated stats
    $reviewStats = updateReviewStats($conn);
    $avg_rating = $reviewStats['avg_rating'] ?? '0.0';
    $total_reviews = $reviewStats['total_reviews'] ?? '0';
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

            <div id="button_container" class="flex items-center mr-10 gap-4">
                <?php if(isset($_SESSION['first_name']) && isset($_SESSION['last_name'])): ?>
                    <span class="text-white text-lg">
                        <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                    </span>
                <?php endif; ?>
                
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
                                        <a href="profile.php" class="text-black hover:text-blue-500">Settings</a>
                                    </li>
                                    <li class="p-2">
                                        <a href="history.php" class="text-black hover:text-blue-500">History</a>
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
            <div id="book_link" class="flex items-center bg-green-500 h-[42px] w-full sm:w-[25%] justify-center rounded-md">
                <a href="../reservation/reservation.php" class="text-white">Book <i class="fa-sharp fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>
            
        <!-- END CALENDAR BUTTON -->
    </section>

    <section id="about_section" class="hidden sm:flex flex-row items-center justify-center self-center w-full h-screen bg-white">
    <div class="flex w-[90%] h-full" data-aos="zoom-in">
        <div id="about_pic" class="flex justify-center w-[100%] sm:w-[45%] h-[80%] sm:mr-[5%] rounded-2xl self-center">
        <img src="../src/images/resort.png" class="h-full w-full object-contain sm:rounded-2xl">
    </div>


        <!-- About Us Text -->
        <div id="about_us_txt" class="flex flex-col justify-center w-[100%] sm:w-[50%] h-full sm:h-[90%] rounded-xl p-6 sm:p-10">
            <div id="about_us_tag" class="mb-5">
                <h1 class="text-2xl font-bold text-green-500">About Us</h1>
            </div>

            <div id="about_us_slogan" class="mb-5 text-black">
                <h1 class="text-4xl sm:text-6xl font-semibold leading-snug">Creating unforgettable memories...</h1>
            </div>

            <div id="about_us_description" class="mb-5 text-black">
                <p class="text-base sm:text-lg leading-relaxed">
                Escape to 888 Lobiano's Farm Resort, a serene retreat nestled in nature’s beauty. Our resort offers a perfect blend of relaxation and adventure, with cozy accommodations, scenic views, and a variety of activities for all. Whether you're looking to unwind, explore, or create lasting memories, we provide the ideal setting. Experience comfort, tranquility, and exceptional hospitality at 888 Lobiano's Farm Resort — your peaceful escape from the everyday.
                </p>
            </div>

            <!-- Fixed Button -->
            <a 
            href="about_us.html" 
            id="about_us_link" 
            class="bg-green-500 text-white hover:bg-green-700 hover:drop-shadow-lg font-medium py-2 px-4 rounded-lg self-start">
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
    <div id="event_header" class="text-left mr-[720px] mt-10">
        <h1 class="text-3xl font-bold text-black">Events</h1>
        <p class="mt-3 text-black">Join us for exciting events that make every moment at the resort unforgettable!</p>
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
    <div id="rates_header" class="text-left mr-[810px] mt-10">
        <h1 class="text-3xl font-bold text-black">Rates</h1>
        <p class="mt-3 text-black">Explore our competitive rates for a memorable and affordable stay</p>
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
                    </div>
                    <div class="flex justify-center items-center py-3 bg-green-500 text-white">
                        <button type="button" id="view_details_btn" class="w-[80%] bg-green-500 rounded-lg text-sm" data-id="<?php echo $rate['id']; ?>" data-type="rate">
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





<?php
// Assuming a database connection is already established
$query = "SELECT name, picture, price, id FROM addons ORDER BY created_at DESC LIMIT 4"; // Limit to 4 add-ons

// Execute the query
$result_addons = mysqli_query($conn, $query);

// Check for query execution error
if (!$result_addons) {
    echo "<script>console.error('Error executing query: " . mysqli_error($conn) . "');</script>";
    die("Error executing query: " . mysqli_error($conn));
}
?>
<!-- Add-Ons Section -->
<section id="addons_section" class="flex flex-col items-center w-full py-10 px-4">
    <div id="addons_header" class="text-left mr-[690px] mt-10">
        <h1 class="text-3xl font-bold text-black">Add-Ons</h1>
        <p class="mt-3 text-black">Enhance your stay with our exclusive add-ons designed for comfort and convenience.</p>
    </div>

    <div id="addons_pic_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-screen-xl mx-auto mt-8">
        <?php
        if (mysqli_num_rows($result_addons) > 0) {
            while ($addon = mysqli_fetch_assoc($result_addons)) {
                // Fetch and encode image data as base64
                $imageData = base64_encode($addon['picture']);
        ?>
                <!-- ADD-ONS CARD START -->
                <div id="addons_card" class="flex flex-col w-full bg-white rounded-lg shadow-md overflow-hidden mt-4">
                    <div id="addons_card_pic" class="w-full h-[300px] bg-cover bg-center" style="background-image: url('data:image/jpeg;base64,<?php echo $imageData; ?>');">
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
                <!-- ADD-ONS CARD END -->
        <?php
            }
        } else {
            echo '<p class="text-center text-white">No add-ons available.</p>';
            echo "<script>console.error('No add-ons found in the database.');</script>";
        }
        ?>
    </div>
</section>
<!-- END ADD-ONS -->




    <!-- END AMENITIES -->


    <!-- START VIDEO TOUR -->
    <div class="flex justify-center bg-white text-white h-screen">
        <section id="video_section" class="flex flex-col self-center w-[80%] h-[90vh]">
            <div id="video_header" class=" w-full h-[13%] mt-[3%] mb-8">
                <h1 class="text-3xl text-black font-bold">Video Tour</h1>
                <p class="mt-5 text-black">Embark on a digital journey through our exclusive haven. Welcome to your virtual tour of luxury and leisure at our private pool resort.</p>
            </div>

            <div id="video_container" class="w-full h-full">
    <iframe 
        width="420" 
        height="345" 
        class="w-full h-full" 
        src="https://www.youtube.com/embed/mx4AFPTWoFo" 
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
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
                    <h1 id="overall_ratings" class="text-2xl mr-3 font-bold"><?php echo $avg_rating; ?></h1>

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
                <p>Based on reviews: <span id="reviews_count"><?php echo $total_reviews; ?></span></p>
            </div>


            <div id="sign_up" class="flex justify-center items-center w-[100%] sm:w-[35%] h-full">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="submit_review.php" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg whitespace-nowrap">Submit a review</a>
                <?php else: ?>
                    <a href="register.php" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg whitespace-nowrap">Sign up</a>                <?php endif; ?>
            </div>
        </div>

        <?php
            // Add at top of file after session_start()
            $stmt = $conn->prepare("SELECT r.*, u.first_name, u.last_name 
                                FROM reviews r 
                                JOIN user_tbl u ON r.user_id = u.user_id 
                                ORDER BY r.created_at DESC");
            $stmt->execute();
            $reviews = $stmt->get_result();

            // Calculate average rating
            $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews");
            $stmt->execute();
            $stats = $stmt->get_result()->fetch_assoc();
            $avg_rating = number_format($stats['avg_rating'], 1);
            $total_reviews = $stats['total_reviews'];
            ?>

            <!-- Replace static review cards with: -->
            <div id="review_card_container" class="flex flex-row overflow-x-auto gap-8 w-full mt-[3%] pb-4">
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
                                <svg id="star" class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
    </section>

    <!-- END REVIEWS -->

    <a href="#header_section" class="fixed flex item-center justify-center bottom-10 right-10 w-14 h-14 bg-black rounded-full z-20 "><i class="fa-solid fa-arrow-up text-white mt-[30%] text-2xl"></i></a>



    <footer class="flex justify-between w-full h-64 bg-gradient-to-r from-blue-500 to-cyan-500 z-30 mt-[5%]">
            <div id="left_side" class="flex flex-col w-[35%] h-full px-16 gap-4 ">
                <div class="flex flex-col gap-4 mt-[10%]">
                    <p class="text-xl text-white">Make an Enquiry:</p>
                    <h1 class="text-3xl text-white">Contact us now to start your adventure</h1>
                    <button id="reserve_button" class="bg-green-400 hover:bg-green-800 hover:drop-shadow-lg w-[30%] rounded-full p-3 text-white">Reserve now!</button>
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
    <script src="../scripts/reviews.js"></script>
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
        <form action="submit_review.php" method="POST" class="bg-white w-[80%] max-w-4xl h-[80%] rounded-lg p-6 flex flex-col">
            <h2 class="text-2xl font-bold mb-4">Leave a Review</h2>
            <div id="starRating" class="flex mb-4">
                <input type="hidden" name="rating" id="ratingValue" value="1">
                <span class="star selected" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            <input id="reviewTitle" name="title" type="text" class="w-full p-4 border rounded-lg mb-4" placeholder="Review Title" required>
            <textarea id="reviewText" name="review_text" class="w-full h-2/3 p-4 border rounded-lg mb-4" placeholder="Write your review here..." required></textarea>
            <div class="flex flex-row gap-2 self-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Submit</button>
                <button type="button" id="closeReviewModal" class="bg-red-500 text-white px-4 py-2 rounded-lg">Close</button>
            </div>
        </form>
    </div>

</body>
</html>
