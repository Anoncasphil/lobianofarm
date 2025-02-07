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
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="../styles/style.css">
	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>

		<ul class="side-menu">
			<li><a href="../index.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="../reservation/reservation_admin.php"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
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
						<a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
							<i class='bx bxs-user-circle text-xl mr-2'></i> 
							Profile
						</a>
					</li>
					<li>
						<a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
							<i class='bx bxs-cog text-xl mr-2'></i> 
							Settings
						</a>
					</li>
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

<!-- MAIN -->
<main class="relative">
    <!-- PHP CONNECTION -->
    <?php include('get_reservations.php'); ?>

    <div class="main flex-1 p-6">
        <header class="mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Recent Reservations</h1>
            <ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#">Management</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Rates</a></li>
			</ul>
        </header>


        <div class="table-container bg-white shadow-md rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-700">Reservations List</h2>
            </div>

            <table class="table-auto w-full text-left border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="py-3 px-4 border-b text-gray-600">Reservation Code</th>
            <th class="py-3 px-4 border-b text-gray-600">Name</th>
            <th class="py-3 px-4 border-b text-gray-600">Date</th>
            <th class="py-3 px-4 border-b text-gray-600">Status</th>
            <th class="py-3 px-4 border-b text-gray-600">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $reservations = getReservations();
        if (!empty($reservations)) {
            usort($reservations, function($a, $b) {
                return $a['id'] - $b['id'];
            });

            foreach ($reservations as $reservation) {
                $userDetails = getUserDetails($reservation['user_id'], $conn);
                $userName = htmlspecialchars($userDetails['first_name']) . ' ' . htmlspecialchars($userDetails['last_name']);
                echo "<tr class='bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800'>";
                echo "<td class='py-2 px-4 border-b text-gray-700'>" . htmlspecialchars($reservation['reservation_code']) . "</td>";
                echo "<td class='py-2 px-4 border-b text-gray-700'>" . $userName . "</td>";
                echo "<td class='py-2 px-4 border-b text-gray-700'>" . htmlspecialchars($reservation['check_in_date']) . "</td>";
                echo "<td class='py-2 px-4 border-b'>";
                switch ($reservation['status']) {
                    case 'Pending':
                        echo "<span class='bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300'>Pending</span>";
                        break;
                    case 'Confirmed':
                        echo "<span class='bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300'>Confirmed</span>";
                        break;
                    case 'Completed':
                        echo "<span class='bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300'>Completed</span>";
                        break;
                    default:
                        echo "<span class='bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-gray-900 dark:text-gray-300'>Unknown</span>";
                        break;
                }
                echo "</td>";
                echo "<td class='py-2 px-4 border-b'>
                    <button 
                            type='button' 
                            class='view-button text-white  bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 mb-1' 
                            data-id='" . $reservation['id'] . "'>
                            <i class='fa-solid fa-eye'></i>
                        </button>
                        <button 
                            type='button' 
                            class='confirm-button text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5 mb-1 ml-2' 
                            data-id='" . $reservation['id'] . "'>
                            <i class='bx bxs-check-square'></i>
                        </button>
                        <button 
                            type='button' 
                            class='deny-button text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 mb-1 ml-2' 
                            data-id='" . $reservation['id'] . "'>
                            <i class='bx bx-no-entry'></i>
                        </button>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='py-2 px-4 border-b text-gray-700'>No reservations found</td></tr>";
        }
        ?>
    </tbody>
</table>



        </div>
    </div>

<!-- Modal -->
<div id="reservationModal" class="fixed inset-0 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl h-[85vh] overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Reservation Details</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <div class="flex flex-col h-full">
            <!-- Tabs -->
            <div class="tabs flex space-x-4 px-6 py-2 border-b border-gray-100">
                <button class="tab-button active text-blue-500 font-medium" data-tab="details">Details</button>
                <button class="tab-button text-gray-500 hover:text-blue-500 font-medium" data-tab="invoice">Invoice</button>
                <button class="tab-button text-gray-500 hover:text-blue-500 font-medium" data-tab="payment">Payment</button>
                <button class="tab-button text-gray-500 hover:text-blue-500 font-medium" data-tab="reschedule">Reschedule</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content-container flex-1 overflow-auto px-6 py-4">
                <div id="details" class="tab-content space-y-4 h-full">
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Reservation ID:</strong> <span id="modal-reservation-id" class="text-gray-600"></span></p>
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Name:</strong> <span id="modal-name" class="text-gray-600"></span></p>
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Email:</strong> <span id="modal-email" class="text-gray-600"></span></p>
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Phone Number:</strong> <span id="modal-phone-number" class="text-gray-600"></span></p>
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Check-In Date:</strong> <span id="modal-check-in" class="text-gray-600"></span></p>
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Check-Out Date:</strong> <span id="modal-check-out" class="text-gray-600"></span></p>
                    <p class="text-sm text-gray-700"><strong class="font-semibold">Total Amount:</strong> <span id="modal-total-amount" class="text-gray-600"></span></p>
                </div>

                <div id="invoice" class="tab-content hidden space-y-4 h-full">
                    <div class="flex justify-between text-sm text-gray-700">
                        <div class="font-semibold">Name</div>
                        <div class="font-semibold">Price</div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <div id="modal-rate-name"></div>
                        <div id="modal-rate-price"></div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <div id="modal-addons-name"></div>
                        <div id="modal-addons-price"></div>
                    </div>
                    <div class="mt-4 flex justify-between text-lg text-gray-800">
                        <div class="font-semibold">Total</div>
                        <div id="modal-total-price"></div>
                    </div>
                </div>

                <div id="payment" class="tab-content hidden flex justify-center items-center h-full">
                    <img id="modal-payment-proof" class="max-w-xs max-h-48 object-contain" alt="Payment Proof" />
                </div>

                <div id="reschedule" class="tab-content hidden h-full">
                    <div id="right-div" class="flex-2 bg-white p-6 rounded-3xl shadow-lg h-full">
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Check-In Date -->

                            <div class="relative">
    <!-- Change input type to 'date' for testing, Flatpickr should open -->
                                <input type="date" id="check-in-date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" required/>
                                <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-In Date </label>
                            </div>

                                
                                <!-- Check-Out Date (non-interactable) -->
                            <div class="relative">
                                <input type="date" id="check-out-date" class="p-3 pt-5 w-full max-w-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " disabled />
                                <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium"> Check-Out Date </label>
                            </div>
                        </div>

                        <!-- Check-In and Check-Out Times -->
                       
                </div>
            </div>
        </div>
    </div>
</div>




</main>

		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="../scripts/reservation_list.js"></script>
	
</body>
</html>

<?php
// Function to get user details by user_id
function getUserDetails($user_id, $conn) {
    $query = "SELECT first_name, last_name FROM user_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>