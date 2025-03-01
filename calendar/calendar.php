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
	<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    
	<link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="calendar.css">
	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>

		<ul class="side-menu">
			<li><a href="../admindash.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="../reservation/reservation_admin.php"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php" class="active"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
			<li><a href="../sales.php"><i class='bx bx-line-chart icon'></i> Sales</a></li>
			<li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<li><a href="../events/events.php"><i class='bx bxs-calendar-event icon' ></i> Events</a></li>
			<li><a href="../album/album.php"><i class='bx bxs-photo-album icon' ></i> Album</a></li>
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
				<li><a href="../team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
			<?php endif; ?>

			<!-- <li class="divider" data-text="table and forms">Table and forms</li>
			<li><a href="#"><i class='bx bx-table icon' ></i> Tables</a></li>
			<li>
				<a href="#"><i class='bx bxs-notepad icon' ></i> Forms <i class='bx bx-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="#">Basic</a></li>
					<li><a href="#">Select</a></li>
					<li><a href="#">Checkbox</a></li>
					<li><a href="#">Radio</a></li>
				</ul>
			</li> -->
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- NAVBAR -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu toggle-sidebar' ></i>
			<form action="#">
			</form>
			<span class="divider"></span>
			<div class="relative">
			<?php
					include('../db_connection.php'); // Include your database connection file

					// Check if the user is logged in
					if (isset($_SESSION['admin_id'])) {
						$admin_id = $_SESSION['admin_id']; // Get the logged-in user's ID

						// Query to get the logged-in user's data from the admin_tbl
						$query = "SELECT * FROM admin_tbl WHERE admin_id = ?";
						$stmt = $conn->prepare($query);

						if ($stmt === false) {
							die('MySQL prepare error: ' . $conn->error);
						}

						$stmt->bind_param("i", $admin_id); // Bind the admin ID
						$stmt->execute();

						// Check if query executed successfully
						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							$admin = $result->fetch_assoc();
							$firstname = $admin['firstname'];
							$lastname = $admin['lastname'];
							$role = ucfirst($admin['role']); // Capitalize the first letter of the role
							// Prepend the directory path to the profile picture
						$profile_picture = '../src/uploads/team/' . $admin['profile_picture'];
						} else {
							// If no user found, redirect to login
							header('Location: ../adlogin.php');
							exit;
						}
					} else {
						// If not logged in, redirect to login page
						header('Location: ../adlogin.php');
						exit;
					}
					?>

					<!-- HTML to display the profile information -->
					<div class="profile flex items-center space-x-4 cursor-pointer">
						<img class="w-10 h-10 rounded-full" src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture">

						<div>
							<h4 class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) ?></h4>
							<span class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($role) ?></span>
						</div>
					</div>
			
				<!-- Profile Dropdown Menu -->
				<ul class="profile-link absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 hidden">
					<li>
						<a href="../logout.php" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-700">
							<i class='bx bxs-log-out-circle text-xl mr-2'></i> 
							Logout
						</a>
					</li>
				</ul>
			</div>
			
		</nav>
		<!-- NAVBAR -->




		<main>
    <div id="calendarWrapper">

	

	<div id="info-alert" class="flex items-center p-3 mb-3 mt-5 text-sm text-blue-800 rounded-lg bg-blue-200 hidden">
          <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <div>
            <span class="font-medium" id="alert-title">Info alert!</span> 
            <span id="alert-message"></span>
          </div>
        </div>

        <div class="flex justify-center mb-4">
            <button id="disableDateButton" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                Disable Date
            </button>
        </div>
<!-- Modal Structure -->
<div id="date-info" tabindex="-1" aria-hidden="true" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-96">
        <!-- Close Button (X) -->
        <button type="button" id="close-btn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center absolute top-2 right-2">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
        
        <!-- Modal Content -->
        <h2 class="text-2xl font-semibold mb-4" id="disable-date"></h2>
        <p id="disable-reason" class="text-lg mb-6"></p>
        <button id="reenable-btn" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">Re-enable</button>
    </div>
</div>



        <!-- Make the calendar width bigger -->
        <div id="calendar" class="w-full max-w-4xl mx-auto"></div>
    </div>

<!-- Main Modal -->
<div id="disable-dates-modal" tabindex="-1" aria-hidden="true" class="hidden bg-black/50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-lg">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Disable Date
                </h3>
                <button type="button" id="close-btn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="disable-date-form" method="POST" class="p-4 md:p-5">
                <div id="info-alert-modal" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-200 hidden" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-medium" id="alert-title">Info alert!</span>
                    <span id="alert-message-modal"></span>
                </div>

                <!-- Disable Date -->
                <div class="relative mb-4">
                    <input type="date" id="disable-date" name="disable_date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" required />
                    <label for="disable_date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Disable Date <span class="text-red-500">*</span> </label>
                </div>

                <!-- Reason for Disabling -->
                <div class="col-span-2 mb-5">
                    <label for="disable_reason" class="block mb-2 text-sm font-medium text-black">Reason</label>
                    <textarea id="disable_reason" name="reason" rows="4" class="block p-2.5 w-full text-sm text-black bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write reason here"></textarea>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="status" value="Disabled">

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="button" id="submit-btn" class="mt-4 flex w-full items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-medium text-blue-900 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900 dark:text-white dark:hover:bg-blue-700 dark:focus:ring-blue-600 sm:mt-0">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Disable
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




</main>








		<!-- MAIN -->
	</section>

	<!-- NAVBAR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
    // Get modal and button elements
    const modal = document.getElementById('disable-dates-modal');
    const openModalButton = document.getElementById('disableDateButton'); // The button that triggers the modal
    const closeModalButton = document.getElementById('close-btn'); // Close button inside the modal

    // Open the modal when the button is clicked
    openModalButton.addEventListener('click', function () {
        modal.classList.remove('hidden'); // Show the modal by removing the 'hidden' class
    });

    // Close the modal when the close button is clicked
    closeModalButton.addEventListener('click', function () {
        modal.classList.add('hidden'); // Hide the modal by adding the 'hidden' class
    });

    // Close the modal when clicking outside of the modal content
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
});

	</script>

<script>
// Fetch reserved dates from the server
async function fetchReservedDates() {
    try {
        const response = await fetch("../api/get_reserved_dates_booking.php"); // Update if you have a new endpoint
        const data = await response.json();

        if (!data || !data.reservedDates) {
            console.error("Unexpected response structure for reserved dates:", data);
            return [];
        }

        console.log("Fetched reserved dates:", data.reservedDates); // Log for debugging
        return data.reservedDates || [];
    } catch (error) {
        console.error("Error fetching reserved dates:", error);
        return [];
    }
}

// Fetch disabled dates from the server
async function fetchDisabledDates() {
    try {
        const response = await fetch("../api/get_disabled_dates.php"); // Update if you have a new endpoint
        const data = await response.json();

        if (!data || !data.disableDates) {
            console.error("Unexpected response structure for disabled dates:", data);
            return [];
        }

        console.log("Fetched disabled dates:", data.disableDates); // Log for debugging
        return data.disableDates || [];
    } catch (error) {
        console.error("Error fetching disabled dates:", error);
        return [];
    }
}

// Initialize Flatpickr with reserved dates disabled
async function initializeFlatpickrReserved() {
    const reservedDates = await fetchReservedDates();

    // Convert the reserved dates to Date objects
    const reservedDateObjects = reservedDates.map(date => new Date(date));

    console.log("Reserved Dates to disable:", reservedDateObjects); // Log for debugging

    // Initialize Flatpickr and disable the reserved dates
    flatpickr("#disable-date", {
        dateFormat: "Y-m-d", // Set the format for the date picker
        minDate: "today",    // Disable past dates
        disable: reservedDateObjects, // Disable only reserved dates
    });
}

// Initialize Flatpickr with disabled dates
async function initializeFlatpickrDisabled() {
    const disabledDates = await fetchDisabledDates();

    // Convert the disabled dates to Date objects
    const disabledDateObjects = disabledDates.map(date => new Date(date));

    console.log("Disabled Dates to disable:", disabledDateObjects); // Log for debugging

    // Initialize Flatpickr and disable the disabled dates
    flatpickr("#disable-date", {
        dateFormat: "Y-m-d", // Set the format for the date picker
        minDate: "today",    // Disable past dates
        disable: disabledDateObjects, // Disable only disabled dates
    });
}

// Call initialize functions after DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    initializeFlatpickrReserved();   // Initialize reserved dates
    initializeFlatpickrDisabled();   // Initialize disabled dates
});

// Open the modal
function openModal() {
    document.getElementById('disable-dates-modal').classList.remove('hidden');
}

// Open the modal
function openInfoModal() {
    document.getElementById('disable-date-modal').classList.remove('hidden');
}

// Close the modal
document.getElementById('close-btn').addEventListener('click', function () {
    document.getElementById('disable-dates-modal').classList.add('hidden');
});

// Close the modal
document.getElementById('close-btn').addEventListener('click', function () {
    document.getElementById('disable-date-modal').classList.add('hidden');
});

// Handle form submission via AJAX
document.getElementById('submit-btn').addEventListener('click', function (e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(document.getElementById('disable-date-form'));

    // Log form data for debugging purposes
    for (let [key, value] of formData.entries()) {
        console.log(key + ": " + value);
    }

    // Submit the form data using Fetch API
    fetch('../api/submit_disable_date.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Show success or failure message
        if (data.success) {
            // Store success message in localStorage before reload
            localStorage.setItem('successMessage', 'The disable date has been saved successfully.');

            // Reload the page
            location.reload();
        } else {
            document.getElementById('alert-title').innerText = 'Error';
            document.getElementById('alert-message').innerText = 'An error occurred while saving the disable date: ' + data.message;
            document.getElementById('info-alert').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById('alert-title').innerText = 'Error';
        document.getElementById('alert-message').innerText = 'There was an issue with the request.';
        document.getElementById('info-alert').classList.remove('hidden');
    });
});

// On page load, check for stored message in localStorage
document.addEventListener("DOMContentLoaded", function () {
    const successMessage = localStorage.getItem('successMessage');

    if (successMessage) {
        // Display the success message
        document.getElementById('alert-title').innerText = 'Success';
        document.getElementById('alert-message').innerText = successMessage;
        document.getElementById('info-alert').classList.remove('hidden');

        // Hide the message after 3 seconds
        setTimeout(() => {
            document.getElementById('info-alert').classList.add('hidden');
            // Clear the success message from localStorage
            localStorage.removeItem('successMessage');
        }, 3000); // 3 seconds delay
    }
});


</script>

    
	<script src="../scripts/script.js"></script>
    <script src="../scripts/calendar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>