<?php
    include('../db_connection.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>888 Lobiano's Farm</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/main_page.css">
    <link rel="icon" href="../src/images/logo.png" type="image/x-icon">
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

    
    </div>
    <!--  END ALBUM -->

    <section id="event_section" class="hidden sm:flex flex-col self-center w-[80%] h-screen ">    
        <div id="event_header" class=" w-full h-[13%] mt-10">
            <h1 class="text-3xl font-bold">Event</h1>
            <p class="mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
        </div>

        <div id="event_pic_container" class="display sm:flex flex-row w-[100%] h-[80%] gap-2">

            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="../src/images/bday.jpg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Birthday Events</p>
                </div>
            </div>
            

            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="../src/images/party.jpg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Party Events</p>
                </div>
            </div>

            
            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="../src/images/team building.jpg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Team Building</p>
                </div>
            </div>

            
            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="../src/images/wedding.jpeg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Wedding</p>
                </div>
            </div>
            
        </div>
    </section>

    <div class="flex justify-center w-full h-[100vh] bg-white">
        <section id="rates_section" class="flex flex-col self-center w-[80%] h-[75vh] overflow-x-auto overflow-y-hide top-0">    
            <div id="rates_header" class=" w-full h-[13%] mt-[5%] mb-8">
                <h1 class="text-3xl font-bold">Rates</h1>
                <p class="mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
            </div>

            <div id="rate_pic_container" class="flex flex-col w-full h-[80%] gap-y-2 sm:gap-x-5">
                <div id="rate_container" class="flex flex-row w-full h-full overflow-x-auto gap-5 scroll-smooth scrollbar-hide sm:scrollbar-default">
                    
                    <div id="rate_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[95%] shrink-0">
                        <div id="rate_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                            <img src="../src/images/RP1.jpg" class="sm:w-full h-full rounded-xl">
                        </div>
                        <div id="rate_description" class="flex flex-col w-full h-[40%]">
                            <h1 id="rates_name" class="text-xl font-bold">Standard Stay</h1>
                            <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> 12 hours</p>
                            <p id="rates_price" class="text-lg">₱15,000</p>
                            <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                        </div>

                        <button id="book_btn" class="w-[80%] h-[15%] bg-[#37863B] rounded-lg hover:bg-[#307533] text-white">Book</button>

                    </div>

                    <div id="rate_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[95%] shrink-0">
                        <div id="rate_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                            <img src="../src/images/RP1.jpg" class="sm:w-full h-full rounded-xl">
                        </div>
                        <div id="rate_description" class="flex flex-col w-full h-[40%]">
                            <h1 id="rates_name" class="text-xl font-bold">Standard Stay</h1>
                            <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> 12 hours</p>
                            <p id="rates_price" class="text-lg">₱15,000</p>
                            <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                        </div>

                        <button id="book_btn" class="w-[80%] h-[15%] bg-[#37863B] rounded-lg hover:bg-[#307533] text-white">Book</button>

                    </div>

                    <div id="rate_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[95%] shrink-0">
                        <div id="rate_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                            <img src="../src/images/RP1.jpg" class="sm:w-full h-full rounded-xl">
                        </div>
                        <div id="rate_description" class="flex flex-col w-full h-[40%]">
                            <h1 id="rates_name" class="text-xl font-bold">Standard Stay</h1>
                            <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> 12 hours</p>
                            <p id="rates_price" class="text-lg">₱15,000</p>
                            <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                        </div>

                        <button id="book_btn" class="w-[80%] h-[15%] bg-[#37863B] rounded-lg hover:bg-[#307533] text-white">Book</button>

                    </div>

                    <div id="rate_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[95%] shrink-0">
                        <div id="rate_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                            <img src="../src/images/RP1.jpg" class="sm:w-full h-full rounded-xl">
                        </div>
                        <div id="rate_description" class="flex flex-col w-full h-[40%]">
                            <h1 id="rates_name" class="text-xl font-bold">Standard Stay</h1>
                            <p id="rates_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> 12 hours</p>
                            <p id="rates_price" class="text-lg">₱15,000</p>
                            <p id="rates_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                        </div>

                        <button id="book_btn" class="w-[80%] h-[15%] bg-[#37863B] rounded-lg hover:bg-[#307533] text-white">Book</button>

                    </div>
                </div>

            </div>
            

        </section>
    </div>

    <!-- START AMENITIES -->

    <section id="amenities_section" class="flex flex-col self-center w-[80%] h-[75vh]">    
        <div id="amenities_header" class=" w-full h-[13%] mt-[3%] mb-8">
            <h1 class="text-3xl font-bold">Add-Ons</h1>
            <p class="mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
        </div>

        <div id="amenities_pic_container" class="flex flex-col w-full h-[80%] gap-y-2 sm:gap-x-5">
            <div id="amenities_container" class="flex flex-row w-full h-full overflow-x-auto gap-5 scroll-smooth scrollbar-hide sm:scrollbar-default">
                
                <div id="amenities_card" class="flex flex-col justify-center items-center w-[35%] sm:w-[25%] h-[85%] shrink-0">
                    <div id="amenities_card_pic" class="flex items-center justify-center relative w-full h-[30%] sm:h-[60%] rounded-xl">
                        <img src="../src/images/RP1.jpg" class="sm:w-full h-full rounded-xl">
                    </div>
                    <div id="amenities_description" class="flex flex-col w-full h-[40%]">
                        <h1 id="amenities_name" class="text-xl font-bold">Standard Stay</h1>
                        <p id="amenities_hour" class="text-sm"><i class="fa-solid fa-clock text-gray-500"></i> 12 hours</p>
                        <p id="amenities_price" class="text-lg">₱15,000</p>
                        <p id="amenities_tag" class="text-xs text-gray-500">Includes taxes & fees</p>
                    </div>
                </div>

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
                <a href="register.html" class="p-3 bg-white rounded-md border-gray-300 border-2 hover:shadow-lg">Sign up</a>
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
</body>
</html>
