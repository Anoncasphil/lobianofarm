<?php

?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/src/styles.css">
    <link rel="stylesheet" href="/src/main_page.css">

</head>
<body id="top_section" class=" flex flex-col">
    <section id="header_section" class="flex flex-col justify-self-center self-center h-[70vh] sm:h-screen w-full bg-[url('/images/main_bg.jpg')] bg-center bg-no-repeat bg-cover overflow-hidden z-20">
        <!-- HEADER -->
        <div id="header_option" class="flex flex-row h-per10 w-full bg-green-400 justify-between bg-transparent mt-5">
            <div id="logo_container" class="flex flex-row ml-5">
                <a class="text-white text-xl flex items-center gap-2">
                    <img src="/images/logo.png" class="w-10 h-10" alt="Logo">
                    888 Lobiano's Farm
                </a>
                
            </div>
            <div id="button_container" class="flex items-center justify-end mr-4 sm:mr-10">
                <div class="relative">
                    <!-- Menu Toggle Button -->
                    <button id="menu_toggle" class="block">
                        <i class="fa-solid fa-bars text-2xl text-white"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        id="menu_container"
                        class="hidden absolute top-12 right-0 bg-white rounded-md shadow-md w-max min-w-[150px]">
                        <ul class="flex flex-col text-center">
                            <li class="p-2">
                                <a href="login.html" class="text-black hover:text-blue-500">Login</a>
                            </li>
                            <li class="p-2">
                                <a href="register.html" class="text-black hover:text-blue-500">Register</a>
                            </li>
                            <li class="p-2">
                                <a href="about_us.html" class="text-black hover:text-blue-500">About us</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- END HEADER -->
    
        <!-- SLOGAN -->
        <div id="slogan_container" class="flex flex-col justify-center self-center items-center w-70per h-per45 text-white mt-10 mb-10 sm:mb-20">
            <div id="main_slogan_container" class="text-5xl w-[40%]">
                <p id="main_slogan_text" class="text-center font-bold leading-tight border-b-2 border-white p-3">
                    About Us
                </p>
            </div>
            <div id="sub_tag_container" class="mt-[5%]">
                <p id="sub_slogan_text" class="text-center text-xl">
                    Discover unbeatable deals on our swimming pool resort. Start<br>planning your dream retreat today!
                </p>
            </div>
        </div>
        <!-- END SLOGAN -->
    </section>

    <section id="about_section" class="sm:flex flex-wrap items-center justify-center w-full bg-[#f4ece2]">
        <!-- First About Container -->
        <div id="about_container" class="flex flex-wrap w-[90%] h-auto sm:h-auto mb-10">
            <!-- About Picture -->
            <div id="about_pic" class="flex justify-center w-full sm:w-[45%] h-[80%] sm:mr-[5%] rounded-2xl self-center">
                <img src="/images/RP1.jpg" class="h-full w-auto sm:rounded-2xl">
            </div>
        
            <!-- About Us Text -->
            <div id="about_us_txt" class="flex flex-col justify-center w-full sm:w-[50%] h-auto sm:h-[90%] rounded-xl p-6 sm:p-10">
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
            </div>
        </div>
    
        <!-- Second About Container -->
        <div id="about_container" class="flex flex-row-reverse flex-wrap w-[90%] h-auto sm:h-auto mb-10">
            <!-- About Picture -->
            <div id="about_pic" class="flex justify-center w-full sm:w-[45%] h-[80%] sm:mr-[5%] rounded-2xl self-center">
                <img src="/images/RP1.jpg" class="h-full w-auto sm:rounded-2xl">
            </div>
        
            <!-- About Us Text -->
            <!-- <div id="about_us_txt" class="flex flex-col justify-center w-full sm:w-[50%] h-auto sm:h-[90%] rounded-xl p-6 sm:p-10">
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
            </div> -->
        </div>
    </section>
    
    
    
    
    


    <section id="event_section" class="hidden sm:flex flex-col self-center w-[80%] h-screen ">    
        <div id="event_header" class=" w-full h-[13%] mt-10">
            <h1 class="text-3xl font-bold">Event</h1>
            <p class="mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
        </div>

        <div id="event_pic_container" class="display sm:flex flex-row w-[100%] h-[80%] gap-2">

            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="/images/bday.jpg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Birthday Events</p>
                </div>
            </div>
            

            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="/images/party.jpg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Party Events</p>
                </div>
            </div>

            
            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="/images/team building.jpg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Team Building</p>
                </div>
            </div>

            
            <div id="event_card" class="flex flex-col w-[25%] h-[50%] sm:h-[100%]">
                <div id="event_picture" class="w-full h-[90%] rounded-2xl overflow-hidden">
                    <img src="/images/wedding.jpeg" class="w-full h-full object-cover drop-shadow-2xl">
                </div>
                
                <div id="event_title" class="flex justify-center items-center h-[10%]">
                    <p class="text-center font-bold text-lg">Wedding</p>
                </div>
            </div>
            
        </div>
    </section>

    <!-- START VIDEO TOUR -->
     <div class="flex justify-center w-full h-screen bg-slate-600">
        <section id="video_section" class="flex self-center items-center w-[80%] h-[90vh]">
                <div class="flex flex-row w-full h-full">
                    <div id="timeline_container" class="w-[70%] h-full">
                        <p class="text-2xl font-bold text-orange-500 mb-5">Our Process</p>
                        <p></p>
                        
                        <ol class="relative border-s border-gray-200 dark:border-gray-700 w-[80%] h-[80%]">                  
                            <li class="mb-10 ms-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none  text-white">Process 1</time>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application UI code in Tailwind CSS</h3>
                                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Get access to over 20+ pages including a dashboard layout, charts, kanban board, calendar, and pre-order E-commerce & Marketing pages.</p>
                            </li>
                            <li class="mb-10 ms-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none  text-white">Process 2</time>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Marketing UI design in Figma</h3>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">All of the pages and components are first designed in Figma and we keep a parity between the two versions even as we update the project.</p>
                            </li>
                            <li class="mb-10 ms-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none   text-white">Process 3</time>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">E-Commerce UI code in Tailwind CSS</h3>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">Get started with dozens of web components and interactive elements built on top of Tailwind CSS.</p>
                            </li>
                            <li class="mb-10 ms-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none text-white ">Process 4</time>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">E-Commerce UI code in Tailwind CSS</h3>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">Get started with dozens of web components and interactive elements built on top of Tailwind CSS.</p>
                            </li>
                        </ol>
                    </div>
                    <div id="timeline_description" class="flex justify-center items-center w-[60%] h-full">
                        <img src="/images/wedding.jpeg" class="w-[80%] h-[70%]">
                    </div>
            </div>

        </section>
    </div>

    <footer class="flex flex-col lg:flex-row justify-between w-full h- bg-gradient-to-r from-blue-500 to-cyan-500 z-30">
        <div id="left_side" class="flex flex-col w-full lg:w-[35%] h-[auto] px-4 lg:px-16 gap-4 mt-8 lg:mt-[3%]">
            <div class="flex flex-col gap-4">
                <p class="text-xl text-white">Make an Enquiry:</p>
                <h1 class="text-2xl sm:text-3xl text-white">Contact us now to start your adventure</h1>
                <button id="reserve_button" class="bg-orange-400 hover:bg-[#766641] hover:drop-shadow-lg w-[80%] lg:w-[30%] rounded-full p-3 text-white">Reserve now!</button>
            </div>
        </div>
        
        <div id="right_side" class="flex flex-col lg:flex-row w-full lg:w-[40%] text-white mt-8 lg:mt-[3%]">
            <div id="socials_container" class="flex flex-col w-full lg:w-[35%] px-4 lg:px-16 gap-4">
                <p class="text-xl mb-4 text-white">Connect:</p>
                <a href="#" class="text-white">Instagram</a>
                <a href="#" class="text-white">Facebook</a>
            </div>
            
            <div id="contact_container" class="flex flex-col w-full lg:w-[50%] px-4 lg:px-16 gap-4">
                <p class="text-xl mb-4 text-white">Contact us:</p>
                <p>09190000000</p>
                <p>john.doe@email.com</p>
                <p>Privacy</p>
            </div>
        </div>
    </footer>
    


    <a href="#header_section" class="fixed flex item-center justify-center bottom-10 right-10 w-14 h-14 bg-black rounded-full z-20 "><i class="fa-solid fa-arrow-up text-white mt-[30%] text-2xl"></i></a>

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://kit.fontawesome.com/26528a6def.js" crossorigin="anonymous"></script>
    <script src="/js/main_page.js"></script>

    <!-- END SCRIPT -->
</body>
</html>
