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
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="../styles/booking.css">
    <link href="../dist/output.css" rel="stylesheet">
	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>

		<ul class="side-menu">
			<li><a href="../admindash.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="../reservation/reservation_admin.php"class="active"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
			<li><a href="../sales.php"><i class='bx bx-line-chart icon'></i> Sales</a></li>
			<li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<!-- <li><a href="../events/events.php"><i class='bx bxs-calendar-event icon' ></i> Events</a></li> -->
			<li><a href="../album/album.php"><i class='bx bxs-photo-album icon' ></i> Album</a></li>
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
				<li><a href="../team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
                <li><a href="../activity_log/activity_log.php"><i class='bx bxs-log icon'></i> Activity Log</a></li>
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

<!-- MAIN -->
<main class="relative">
    <!-- PHP CONNECTION -->
    <?php include ('get_reservations.php'); ?>

    <div class="main flex-1 p-6">
        <header class="mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Recent Reservations</h1>
            <ul class="breadcrumbs">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#">Management</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Reservations</a></li>
            </ul>
        </header>

        <div class="flex justify-between items-center mb-4">
            <!-- Filter Dropdown -->
			<form method="GET" action="" class="flex flex-wrap items-center gap-3">
    <!-- Status Filter Dropdown -->
    <div class="flex items-center gap-2">
        <label for="status_filter" class="text-gray-700 font-medium">Filter:</label>
        <select name="status_filter" id="status_filter" class="px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
            <option value="">All Statuses</option>
            <option value="Pending" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Confirmed" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
            <option value="Completed" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Apply</button>
    </div>

    <!-- Search Box -->
    <div class="flex items-center gap-2">
        <label for="search_code" class="text-gray-700 font-medium">Search:</label>
        <input type="text" name="search_code" id="search_code" class="px-4 py-2 border rounded-md focus:ring focus:ring-blue-300" placeholder="Reservation Code" value="<?php echo isset($_GET['search_code']) ? htmlspecialchars($_GET['search_code']) : ''; ?>">
        <button type="submit" class="bg-green-900 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">Search</button>
    </div>
</form>


            <!-- Search Box -->

        </div>

        <div class="table-container bg-white shadow-md rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-700">Reservations List</h2>
            </div>

            <table class="table-auto w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 border-b text-gray-600 hidden">ID</th>
                        <th class="py-3 px-4 border-b text-gray-600">Reservation Code</th>
                        <th class="py-3 px-4 border-b text-gray-600">Name</th>
                        <th class="py-3 px-4 border-b text-gray-600">Date</th>
                        <th class="py-3 px-4 border-b text-gray-600">Status</th>
                        <th class="py-3 px-4 border-b text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Get search code if present
                    $searchCode = isset($_GET['search_code']) ? $_GET['search_code'] : '';
                    $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
                    $reservations = getReservations($statusFilter); // Pass the filter to the function

                    // Filter by reservation code if search term is provided
                    if ($searchCode) {
                        $reservations = array_filter($reservations, function($reservation) use ($searchCode) {
                            return strpos(strtolower($reservation['reservation_code']), strtolower($searchCode)) !== false;
                        });
                    }

                    // Set up pagination variables
                    $limit = 10; // Number of reservations per page
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Slice the reservations array for the current page
                    $pagedReservations = array_slice($reservations, $offset, $limit);

                    // Sort reservations by check-in date (ascending)
                    usort($pagedReservations, function($a, $b) {
                        $dateA = strtotime($a['check_in_date']);
                        $dateB = strtotime($b['check_in_date']);
                        return $dateA - $dateB; // Ascending order
                    });

                    // Loop through reservations and display them
                    if (!empty($pagedReservations)) {
                        foreach ($pagedReservations as $reservation) {
                            $userDetails = getUserDetails($reservation['user_id'], $conn);
                            $userName = htmlspecialchars($userDetails['first_name']) . ' ' . htmlspecialchars($userDetails['last_name']);
                            echo "<tr class='bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800'>";
                            echo "<td class='py-2 px-4 border-b text-gray-700 hidden'>" . htmlspecialchars($reservation['id']) . "</td>";
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
                                case 'Cancelled':
                                    echo "<span class='bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300'>Cancelled</span>";
                                    break;
                                default:
                                    echo "<span class='bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-gray-900 dark:text-gray-300'>Unknown</span>";
                                    break;
                            }
                            echo "</td>";
                            echo "<td class='py-2 px-4 border-b'>
                                <button 
                                        type='button' 
                                        class='reserve-button text-white bg-green-900 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5 mb-1' 
                                        data-id='" . $reservation['id'] . "' 
                                        onclick='storeReservationAndRedirect(this)'>
                                        <i class='fa-solid fa-eye'></i>
                                    </button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='py-2 px-4 border-b text-gray-700'>No reservations found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div>
                    <?php 
                    // Calculate the total number of pages
                    $totalPages = ceil(count($reservations) / $limit);
                    if ($totalPages > 1) {
                        echo "<nav class='pagination'>";
                        echo "<ul class='flex space-x-2'>";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $activeClass = $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700';
                            echo "<li><a href='?page=$i' class='px-4 py-2 rounded-md $activeClass'>$i</a></li>";
                        }
                        echo "</ul>";
                        echo "</nav>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>




		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
	<script src="../scripts/reservation_list.js"></script>
    <script src="../scripts/script.js"></script>
    
	
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

function formatDate($dateString) {
    return date('F j, Y', strtotime($dateString));
}
?>

