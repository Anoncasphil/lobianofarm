<?php
session_start(); // Start the session

// Check if the session is set for the user
if (!isset($_SESSION['admin_id'])) {
    // If not set, redirect to login page
    header("Location: ../adlogin.php");
    exit; // Ensure no further code is executed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    
	<link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="calendar.css">
	
	<title>Admin</title>
	<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    #calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
    }

    .calendar-header {
        font-weight: bold;
        color: #333;
        padding: 10px;
        background-color: #f4f4f4;
    }

    .calendar-day {
        padding: 15px;
        background-color: #fff;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border-radius: 4px;
        position: relative;
        height: 120px; /* Adjust height for space */
    }

    .calendar-day:hover {
        background-color: #e2e2e2;
    }

    .reserved {
        background-color: #ff7f7f;
        color: white;
        font-weight: bold;
    }

    .current-day {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
    }

    .status-text {
        font-size: 12px;
        color: #fff;
        position: absolute;
        bottom: 5px;
        left: 5px;
        padding: 3px 5px;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 3px;
        font-weight: normal;
    }

    .month-nav button {
        background-color: #4CAF50;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .month-nav button:hover {
        background-color: #45a049;
    }
</style>



</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>
        <ul class="side-menu">
            <li><a href="../index.php"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="management">Management</li>
            <li><a href="../reservation/reservation_admin.php"><i class='bx bx-list-ol icon'></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php" class="active"><i class='bx bxs-calendar icon'></i> Calendar</a></li>
            <li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
            <li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon'></i> Add-ons</a></li>
            <li><a href="../events/events.php"><i class='bx bxs-calendar-event icon'></i> Events</a></li>
            <li><a href="../album/album.php"><i class='bx bxs-photo-album icon'></i> Album</a></li>
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
                <li><a href="../team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
            <?php endif; ?>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#"></form>
            <span class="divider"></span>
            <div class="relative">
                <?php
                    include('../db_connection.php');
                    if (isset($_SESSION['admin_id'])) {
                        $admin_id = $_SESSION['admin_id'];
                        $query = "SELECT * FROM admin_tbl WHERE admin_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $admin_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $admin = $result->fetch_assoc();
                            $firstname = $admin['firstname'];
                            $lastname = $admin['lastname'];
                            $role = ucfirst($admin['role']);
                            $profile_picture = '../src/uploads/team/' . $admin['profile_picture'];
                        } else {
                            header('Location: ../adlogin.php');
                            exit;
                        }
                    } else {
                        header('Location: ../adlogin.php');
                        exit;
                    }
                ?>
                <div class="profile flex items-center space-x-4 cursor-pointer">
                    <img class="w-10 h-10 rounded-full" src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture">
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) ?></h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($role) ?></span>
                    </div>
                </div>
                <ul class="profile-link absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 hidden">
                    <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class='bx bxs-user-circle text-xl mr-2'></i> Profile</a></li>
                    <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"><i class='bx bxs-cog text-xl mr-2'></i> Settings</a></li>
                    <li><a href="../logout.php" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-700"><i class='bx bxs-log-out-circle text-xl mr-2'></i> Logout</a></li>
                </ul>
            </div>
        </nav>
		</section>
        <!-- NAVBAR -->

        <!-- MAIN -->
		<main class="container">
		<div id="calendar" class="calendar">				
    <div class="text-center mb-8">
        <h2 class="text-3xl font-semibold">Calendar for Current Month</h2>
        <p id="current-month" class="text-xl mb-4"></p>
        <div class="month-nav flex justify-between items-center">
            <button id="prev-month">Prev</button>
            <button id="next-month">Next</button>
        </div>

    </div>
					</div>

        <!-- Days of the week will go here -->
    </div>
</main>
        <!-- MAIN -->

    <!-- NAVBAR -->

    <script>
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let currentDay = new Date().getDate();

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
let reservedDates = [];

async function fetchReservedDates() {
    try {
        const response = await fetch('../api/get_reserved_dates.php');
        const data = await response.json();
        reservedDates = data.map(item => ({
            start: new Date(item.start),
            end: new Date(item.end),
            status: item.status // Assuming status is part of the response
        }));
    } catch (error) {
        console.error('Error fetching reserved dates:', error);
    }
}

function renderCalendar() {
    document.getElementById('current-month').innerText = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear}`;

    const calendar = document.getElementById('calendar');
    calendar.innerHTML = ''; // Clear the calendar

    // Render the days of the week
    daysOfWeek.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.classList.add('calendar-header');
        dayElement.innerText = day;
        calendar.appendChild(dayElement);
    });

    // Get the first day of the current month
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Add empty spaces for days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        calendar.appendChild(emptyDay);
    }

    // Add actual days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.classList.add('calendar-day');
        if (day === currentDay) {
            dayElement.classList.add('current-day');
        }

        // Check if the day is reserved
        const currentDate = new Date(currentYear, currentMonth, day);
        const reserved = reservedDates.find(reserved => {
            return currentDate >= reserved.start && currentDate <= reserved.end;
        });

        // If reserved, add the status inside the block
        if (reserved) {
            dayElement.classList.add('reserved');

            // Create a status box inside the date block
            const statusText = document.createElement('span');
            statusText.classList.add('status-text');
            statusText.innerText = reserved.status; // "Pending" or "Confirmed"
            dayElement.appendChild(statusText);
        }

        // Add the date at the top
        const dateText = document.createElement('div');
        dateText.innerText = day;
        dayElement.appendChild(dateText);

        calendar.appendChild(dayElement);
    }
}

// Change the month
document.getElementById('prev-month').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
});

document.getElementById('next-month').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
});

// Initialize the calendar and fetch reserved dates
fetchReservedDates().then(() => {
    renderCalendar();
});



    </script>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="../scripts/script.js"></script>
  <script src="../scripts/calendar.js"></script>
</body>

</html>