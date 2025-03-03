<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Make sure no further code is executed
}
// Include the database connection
include('../db_connection.php'); // Adjust the path if necessary

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve user info based on user_id
$sql = "SELECT first_name, last_name FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Check if prepare() failed
if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
}

// Bind the user_id to the query
$stmt->bind_param("i", $_SESSION['user_id']);

// Execute the query
$stmt->execute();
$stmt->store_result();

// Bind the results to variables
$stmt->bind_result($first_name, $last_name);

// Check if user data is found
if ($stmt->fetch()) {
    // Combine first and last name
    $full_name = $first_name . ' ' . $last_name;
    // Use the picture if it exists, otherwise set a default
    $user_picture = !empty($picture) ? 'userpicture/' . $picture : 'default-avatar.jpg'; // Adjust the path for the profile picture
} else {
    // If user not found, handle accordingly (e.g., redirect to login)
    header("Location: ../adlogin.php");
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobiano's Farm Resort</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../styles/booking.css">
    <link rel="stylesheet" href="../styles/customer_reservation.css">
    <style>
        /* Apply styles to the whole page's scrollbar */
        html {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.5) transparent; /* Default opacity 20% */
        }

        ::-webkit-scrollbar {
            width: 8px; /* Width for vertical scrollbar */
            height: 8px; /* Height for horizontal scrollbar */
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2); /* Dark color for the scrollbar thumb with 20% opacity */
            border-radius: 4px;
            transition: background-color 0.3s, opacity 0.3s, transform 0.3s; /* Smooth transition */
            transform: scale(1.1); /* Zoom effect */
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.5); /* Slightly darker when hovered */
            opacity: 0.8; /* Ensure opacity stays at 80% when thumb is hovered */
            transform: scale(1.1); /* Zoom effect on thumb hover */
        }

        .logo {
            height: 3rem; /* Adjust this as needed */
            width: auto;
            object-fit: contain;
        }

        /* Minimalist scrollbar styles */
        .scrollable-container {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.1) transparent; /* Default opacity 20% */
            scroll-behavior: smooth; /* Enable smooth scrolling */
        }

        .scrollable-container:hover {
            scrollbar-color: rgba(0, 0, 0, 0.5) transparent; /* On hover, opacity 80% */
        }

        .scrollable-container::-webkit-scrollbar {
            height: 8px; /* Height for horizontal scrollbar */
        }

        .scrollable-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollable-container::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2); /* Dark color for the scrollbar thumb with 20% opacity */
            border-radius: 4px;
            transition: background-color 0.3s, opacity 0.3s, transform 0.3s; /* Smooth transition */
        }

        .scrollable-container:hover::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.8); /* On hover, 80% opacity for the thumb */
            opacity: 0.8; /* Set opacity to 80% */
            transform: scale(1.1); /* Zoom effect on hover */
        }

        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.5); /* Slightly darker when hovered */
            opacity: 0.8; /* Ensure opacity stays at 80% when thumb is hovered */
            transform: scale(1.1); /* Zoom effect on thumb hover */
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="border-blue-200 bg-blue-900 fixed top-0 left-0 w-full z-50" data-aos="fade-down" data-aos-duration="1200">
  <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
    <!-- Logo -->
    <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="../src/uploads/logo.svg" class="logo" alt="Logo" />
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
      <li><a href="../index.php#home" class="block py-2 px-3 text-white hover:text-blue-500">Home</a></li>
      <li><a href="../index.php#services" class="block py-2 px-3 text-white hover:text-blue-500">Services</a></li>
      <li><a href="../index.php#about" class="block py-2 px-3 text-white hover:text-blue-500">About</a></li>
      <li><a href="../index.php#album" class="block py-2 px-3 text-white hover:text-blue-500">Album</a></li>
      <li><a href="../index.php#reviews" class="block py-2 px-3 text-white hover:text-blue-500">Reviews</a></li>
      <li><a href="../index.php#contact" class="block py-2 px-3 text-white hover:text-blue-500">Contact</a></li>
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
            <a href="customer_reservation.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Reservations</a>
            <a href="edit_profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
            <hr class="border-gray-300">
            <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="flex items-center space-x-3 text-sm bg-white hover:bg-gray-300 text-blue-900 font-semibold rounded-lg px-6 py-3 transition-all duration-300 ease-in-out shadow-md hover:shadow-lg">
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
        <a href="customer_reservation.php" class="block py-2 text-gray-700 hover:bg-gray-200">Reservations</a>
        <hr class="border-gray-300 my-2">
      <?php endif; ?>
      <a href="../index.php#home" class="block py-2 text-gray-700 hover:bg-gray-200">Home</a>
      <a href="../index.php#services" class="block py-2 text-gray-700 hover:bg-gray-200">Services</a>
      <a href="../index.php#about" class="block py-2 text-gray-700 hover:bg-gray-200">About</a>
      <a href="../index.php#album" class="block py-2 text-gray-700 hover:bg-gray-200">Album</a>
      <a href="../index.php#reviews" class="block py-2 text-gray-700 hover:bg-gray-200">Reviews</a>
      <a href="../index.php#contact" class="block py-2 text-gray-700 hover:bg-gray-200">Contact</a>
      <hr class="border-gray-300 my-2">
      <?php if (isset($full_name) && !empty($full_name)): ?>
        <a href="logout.php" class="block py-2 text-gray-700 hover:bg-gray-200">Logout</a>
      <?php else: ?>
        <a href="login.php" class="block py-2 text-gray-700 hover:bg-gray-200">Login</a>
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

<div class="max-w-7xl mx-auto my-8 px-6 mt-30">
    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Your Reservations</h2>

    <!-- Tabs for reservation statuses -->
    <div class="flex justify-center mb-6">
        <button class="tab-button px-4 py-2 mx-2 bg-blue-500 text-white rounded-md" data-tab="pending">Pending</button>
        <button class="tab-button px-4 py-2 mx-2 bg-gray-200 text-gray-800 rounded-md" data-tab="upcoming">Confirmed</button>
        <button class="tab-button px-4 py-2 mx-2 bg-gray-200 text-gray-800 rounded-md" data-tab="completed">Completed</button>
        <button class="tab-button px-4 py-2 mx-2 bg-gray-200 text-gray-800 rounded-md" data-tab="cancelled">Cancelled</button>
    </div>

    <!-- Tab contents -->
    <div id="pending" class="tab-content">
    <?php
    // Query to retrieve pending reservations, ordered by ascending check-in date and time
    $sql = "SELECT r.id as reservation_id, r.first_name, r.last_name, r.check_in_date, r.check_in_time, r.status,
    rt.id as rate_id, rt.name as rate_name, rt.picture as rate_picture, rt.price as rate_price,
    rt.price as total_price
    FROM reservations r
    JOIN rates rt ON r.rate_id = rt.id
    WHERE r.user_id = ? AND r.status = 'Pending' 
    ORDER BY r.check_in_date ASC, r.check_in_time ASC";  // Order by check-in date and time, ascending

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data is available
    if ($result->num_rows > 0) {
        // Loop through reservations
        while ($row = $result->fetch_assoc()) {
            $full_name = $row["first_name"] . " " . $row["last_name"];
            $check_in = date('F j, Y - g:i A', strtotime($row["check_in_date"] . ' ' . $row["check_in_time"])); // Format date
            $status_class = 'bg-orange-200 text-orange-800'; // Pending: Orange
            $status_text = 'Pending';
            $rate_picture = $row["rate_picture"];
            $rate_name = $row["rate_name"];
            $rate_id = $row["rate_id"];
            $total_price = $row["total_price"];

            // Calculate downpayment and new total
            $downpayment = $total_price / 2;
            $new_total =  $total_price - $downpayment;

            // Reservation display code
            echo "<div class='mb-8 bg-white shadow-lg rounded-lg cursor-pointer hover:scale-105 transform transition-all w-[500px] mx-auto' onclick='storeReservationId(" . $row["reservation_id"] . ")'>"; 

            // Reservation Card
            echo "<div class='p-6'>
                <div class='flex items-center justify-between'>
                    <!-- Picture -->
                    <img src='../src/uploads/rates/" . $rate_picture . "' alt='Rate Image' class='w-24 h-24 object-cover rounded-md shadow-lg'>

                    <!-- Rate Name & Status -->
                    <div class='ml-4 flex-1'>
                        <div class='flex items-center justify-between mt-[-100]'>
                            <h3 class='text-xl font-semibold text-gray-800'>" . $rate_name . "</h3>
                            <span class='px-3 py-1 rounded-full " . $status_class . "'>" . $status_text . "</span>
                        </div>

                        <!-- Date and Time -->
                        <span class='text-sm text-gray-500 mt-2 block'>" . $check_in . "</span>
                    </div>
                </div>
            </div>
            </div>";
        }
    } else {
        echo "<p class='text-center text-gray-500 py-4'>No pending reservations found.</p>";
    }

    $stmt->close();
    ?>
</div>



<div id="upcoming" class="tab-content hidden">
    <?php
    // Query to retrieve upcoming reservations, ordered by check-in date and time, ascending
    $sql = "SELECT r.id as reservation_id, r.first_name, r.last_name, r.check_in_date, r.check_in_time, r.status,
    rt.id as rate_id, rt.name as rate_name, rt.picture as rate_picture, rt.price as rate_price,
    rt.price as total_price
    FROM reservations r
    JOIN rates rt ON r.rate_id = rt.id
    WHERE r.user_id = ? AND r.status = 'Confirmed'
    ORDER BY r.check_in_date ASC, r.check_in_time ASC";  // Order by check-in date and time, ascending

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data is available
    if ($result->num_rows > 0) {
        // Loop through reservations
        while ($row = $result->fetch_assoc()) {
            $full_name = $row["first_name"] . " " . $row["last_name"];
            $check_in = date('F j, Y - g:i A', strtotime($row["check_in_date"] . ' ' . $row["check_in_time"])); // Format date
            $status_class = 'bg-blue-200 text-blue-800'; // Upcoming: Blue
            $status_text = 'Confirmed';
            $rate_picture = $row["rate_picture"];
            $rate_name = $row["rate_name"];
            $rate_id = $row["rate_id"];
            $total_price = $row["total_price"];

            // Calculate downpayment and new total
            $downpayment = $total_price / 2;
            $new_total =  $total_price - $downpayment;

            // Reservation display code
            echo "<div class='mb-8 bg-white shadow-lg rounded-lg cursor-pointer hover:scale-105 transform transition-all w-[500px] mx-auto' onclick='storeReservationId(" . $row["reservation_id"] . ")'>"; 

            // Reservation Card
            echo "<div class='p-6'>
                <div class='flex items-center justify-between'>
                    <!-- Picture -->
                    <img src='../src/uploads/rates/" . $rate_picture . "' alt='Rate Image' class='w-24 h-24 object-cover rounded-md shadow-lg'>

                    <!-- Rate Name & Status -->
                    <div class='ml-4 flex-1'>
                        <div class='flex items-center justify-between mt-[-100]'>
                            <h3 class='text-xl font-semibold text-gray-800'>" . $rate_name . "</h3>
                            <span class='px-3 py-1 rounded-full " . $status_class . "'>" . $status_text . "</span>
                        </div>

                        <!-- Date and Time -->
                        <span class='text-sm text-gray-500 mt-2 block'>" . $check_in . "</span>
                    </div>
                </div>


            </div>
            </div>";
        }
    } else {
        echo "<p class='text-center text-gray-500 py-4'>No upcoming reservations found.</p>";
    }

    $stmt->close();
    ?>
</div>


<div id="completed" class="tab-content hidden">
    <?php
    // Query to retrieve completed reservations, ordered by check-in date and time, ascending
    $sql = "SELECT r.id as reservation_id, r.first_name, r.last_name, r.check_in_date, r.check_in_time, r.status,
    rt.id as rate_id, rt.name as rate_name, rt.picture as rate_picture, rt.price as rate_price,
    rt.price as total_price
    FROM reservations r
    JOIN rates rt ON r.rate_id = rt.id
    WHERE r.user_id = ? AND r.status = 'Completed'
    ORDER BY r.check_in_date ASC, r.check_in_time ASC";  // Order by check-in date and time, ascending

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data is available
    if ($result->num_rows > 0) {
        // Loop through reservations
        while ($row = $result->fetch_assoc()) {
            $full_name = $row["first_name"] . " " . $row["last_name"];
            $check_in = date('F j, Y - g:i A', strtotime($row["check_in_date"] . ' ' . $row["check_in_time"])); // Format date
            $status_class = 'bg-blue-200 text-blue-800'; // Completed: Blue
            $status_text = 'Completed';
            $rate_picture = $row["rate_picture"];
            $rate_name = $row["rate_name"];
            $rate_id = $row["rate_id"];
            $total_price = $row["total_price"];

            // Calculate downpayment and new total
            $downpayment = $total_price / 2;
            $new_total =  $total_price - $downpayment;

            // Reservation display code
            echo "<div class='mb-8 bg-white shadow-lg rounded-lg cursor-pointer hover:scale-105 transform transition-all w-[500px] mx-auto' onclick='storeReservationId(" . $row["reservation_id"] . ")'>"; 

            // Reservation Card
            echo "<div class='p-6'>
                <div class='flex items-center justify-between'>
                    <!-- Picture -->
                    <img src='../src/uploads/rates/" . $rate_picture . "' alt='Rate Image' class='w-24 h-24 object-cover rounded-md shadow-lg'>

                    <!-- Rate Name & Status -->
                    <div class='ml-4 flex-1'>
                        <div class='flex items-center justify-between mt-[-100]'>
                            <h3 class='text-xl font-semibold text-gray-800'>" . $rate_name . "</h3>
                            <span class='px-3 py-1 rounded-full " . $status_class . "'>" . $status_text . "</span>
                        </div>

                        <!-- Date and Time -->
                        <span class='text-sm text-gray-500 mt-2 block'>" . $check_in . "</span>
                    </div>
                </div>


            </div>
            </div>";
        }
    } else {
        echo "<p class='text-center text-gray-500 py-4'>No completed reservations found.</p>";
    }

    $stmt->close();
    ?>
</div>


    <div id="cancelled" class="tab-content hidden">
        <?php
        // Query to retrieve cancelled reservations
        $sql = "SELECT r.id as reservation_id, r.first_name, r.last_name, r.check_in_date, r.check_in_time, r.status,
        rt.id as rate_id, rt.name as rate_name, rt.picture as rate_picture, rt.price as rate_price,
        rt.price as total_price
        FROM reservations r
        JOIN rates rt ON r.rate_id = rt.id
        WHERE r.user_id = ? AND r.status = 'Cancelled'";  // Filter by user_id and status

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if data is available
        if ($result->num_rows > 0) {
            // Loop through reservations
            while ($row = $result->fetch_assoc()) {
                $full_name = $row["first_name"] . " " . $row["last_name"];
                $check_in = date('F j, Y - g:i A', strtotime($row["check_in_date"] . ' ' . $row["check_in_time"])); // Format date
                $status_class = 'bg-red-200 text-red-800'; // Cancelled: Red
                $status_text = 'Cancelled';
                $rate_picture = $row["rate_picture"];
                $rate_name = $row["rate_name"];
                $rate_id = $row["rate_id"];
                $total_price = $row["total_price"];

                // Calculate downpayment and new total
                $downpayment = $total_price / 2;
                $new_total =  $total_price - $downpayment;

                // Reservation display code
                echo "<div class='mb-8 bg-white shadow-lg rounded-lg cursor-pointer hover:scale-105 transform transition-all w-[500px] mx-auto' onclick='storeReservationId(" . $row["reservation_id"] . ")'>"; 

                // Reservation Card
                echo "<div class='p-6'>
                    <div class='flex items-center justify-between'>
                        <!-- Picture -->
                        <img src='../src/uploads/rates/" . $rate_picture . "' alt='Rate Image' class='w-24 h-24 object-cover rounded-md shadow-lg'>

                        <!-- Rate Name & Status -->
                        <div class='ml-4 flex-1'>
                            <div class='flex items-center justify-between mt-[-100]'>
                                <h3 class='text-xl font-semibold text-gray-800'>" . $rate_name . "</h3>
                                <span class='px-3 py-1 rounded-full " . $status_class . "'>" . $status_text . "</span>
                            </div>

                            <!-- Date and Time -->
                            <span class='text-sm text-gray-500 mt-2 block'>" . $check_in . "</span>
                        </div>
                    </div>

                </div>
                </div>";
            }
        } else {
            echo "<p class='text-center text-gray-500 py-4'>No cancelled reservations found.</p>";
        }

        $stmt->close();
        ?>
    </div>
</div>
<!-- Elfsight Facebook Chat | Untitled Facebook Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-ba949789-bf48-4f26-a7e1-ceb2bc7e1123" data-elfsight-app-lazy></div>
<script src="../scripts/customer_reservation.js"></script>
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-b2701a5e-2312-4201-92bf-10db53498839" data-elfsight-app-lazy></div>
</body>
</html>