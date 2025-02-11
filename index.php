<?php
session_start(); // Start the session

// Check if the session is set for the user
if (!isset($_SESSION['admin_id'])) {
    // If not set, redirect to login page
    header("Location: adlogin.php");
    exit; // Ensure no further code is executed
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="styles/style.css">
	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>

		<ul class="side-menu">
			<li class="active"><a href="index.php" class="active"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="reservation/reservation_admin.php"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="calendar/calendar.php"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
			<li><a href="rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<li><a href="events/events.php"><i class='bx bxs-calendar-event icon' ></i> Events</a></li>
			<li><a href="album/album.php"><i class='bx bxs-photo-album icon' ></i> Album</a></li>
			<?php if ($_SESSION['role'] === 'superadmin'): ?>
				<li><a href="team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
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
				<!-- Profile Dropdown Trigger -->
				<?php
					include('db_connection.php'); // Include your database connection file

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
						$profile_picture = 'src/uploads/team/' . $admin['profile_picture'];
						} else {
							// If no user found, redirect to login
							header('Location: adlogin.php');
							exit;
						}
					} else {
						// If not logged in, redirect to login page
						header('Location: adlogin.php');
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
						<a href="logout.php" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-700">
							<i class='bx bxs-log-out-circle text-xl mr-2'></i> 
							Logout
						</a>
					</li>
				</ul>
			</div>
			
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<h1 class="title">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>
			<div class="info-data">
			<div class="card">
    <div class="head">
			<div>
				<h2>
					<?php
					// PHP connection and function to get reservation count
					include('get_reservations.php');
					$reservations = getReservations();
					echo count($reservations); // Display the count of reservations
					?>
				</h2>
				<p>Reservations</p>
			</div>
			<i class='bx bx-trending-up icon'></i>
		</div>
	</div>

				<?php
require_once 'db_connection.php';

// Fetch confirmed reservations
$query = "SELECT SUM(total_price) as total_sales FROM reservations WHERE status = 'Confirmed'";
$result = $conn->query($query);
$total_sales = $result->fetch_assoc()['total_sales'] ?? 0;
?>

<div class="card">
    <div class="head">
        <div>
            <h2>₱<?php echo number_format($total_sales, 2); ?></h2>
            <p>Sales</p>
        </div>
        <i class='bx bx-trending-down icon down'></i>
    </div>
</div>

				<!-- <div class="card">
					<div class="head">
						<div>
							<h2>465</h2>
							<p>Pageviews</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="30%"></span>
					<span class="label">30%</span>
				</div> -->



				<?php
require_once 'db_connection.php';

// Fetch pending reservations count
$pending_query = "SELECT COUNT(*) as pending_count FROM reservations WHERE status = 'Pending'";
$pending_result = $conn->query($pending_query);
$pending_count = $pending_result->fetch_assoc()['pending_count'] ?? 0;

// Fetch approved reservations count
$approved_query = "SELECT COUNT(*) as approved_count FROM reservations WHERE status = 'Confirmed'";
$approved_result = $conn->query($approved_query);
$approved_count = $approved_result->fetch_assoc()['approved_count'] ?? 0;

// Fetch total reservations count
$total_query = "SELECT COUNT(*) as total_count FROM reservations";
$total_result = $conn->query($total_query);
$total_count = $total_result->fetch_assoc()['total_count'] ?? 0;

// Calculate percentage of pending reservations
$pending_percentage = $total_count > 0 ? ($pending_count / $total_count) * 100 : 0;

// Calculate percentage of approved reservations
$approved_percentage = $total_count > 0 ? ($approved_count / $total_count) * 100 : 0;
?>

<div class="card">
    <div class="head">
        <div>
            <h2><?php echo $pending_count; ?></h2>
            <p>Pending Reservations</p>
        </div>
        <i class='bx bx-time-five icon'></i>
    </div>
    <span class="progress" data-value="<?php echo $pending_percentage; ?>%"></span>
    <span class="label"><?php echo number_format($pending_percentage, 2); ?>%</span>
</div>

<div class="card">
    <div class="head">
        <div>
            <h2><?php echo $approved_count; ?></h2>
            <p>Approved Reservations</p>
        </div>
        <i class='bx bx-check-circle icon'></i>
    </div>
    <span class="progress" data-value="<?php echo $approved_percentage; ?>%"></span>
    <span class="label"><?php echo number_format($approved_percentage, 2); ?>%</span>
</div>
			</div>

			

			<div class="data">

				<!-- sales graph -->
				<!-- <div class="content-data">
					<div class="head">
						<h3>Sales Report</h3>
					</div>
					<div class="chart">
						<div id="chart"></div>
					</div>
				</div> -->

			<!-- Reservation Table -->
			<div class="content-data">
				<div class="head">
					<h3>Recent Reservations</h3>
				</div>
				

				<div class="relative overflow-x-auto sm:rounded-lg">
				<table class="w-full text-sm text-left rtl:text-right text-gray-700 dark:text-gray-300">
                <thead class="text-xs text-gray-800 uppercase bg-gray-100 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Reservation ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $reservations = getReservations();
                    if (!empty($reservations)) {
                        foreach ($reservations as $reservation) {
                            $statusColor = match($reservation['title']) {
                                'Confirmed' => 'text-green-500 dark:text-green-400',
                                'Pending' => 'text-orange-500 dark:text-orange-400',
                                'Rescheduled' => 'text-blue-500 dark:text-blue-400',
                                default => 'text-gray-500'
                            };

                            echo "<tr class='bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800'>";
                            echo "<td class='px-6 py-4'>#" . str_pad($reservation['reservation_id'], 3, '0', STR_PAD_LEFT) . "</td>";
                            echo "<td class='px-6 py-4'>" . htmlspecialchars($reservation['first_name']) . " " . htmlspecialchars($reservation['last_name']) . "</td>";
                            echo "<td class='px-6 py-4'>" . htmlspecialchars($reservation['formatted_date']) . "</td>";
                            echo "<td class='px-6 py-4 font-medium " . $statusColor . "'>" . htmlspecialchars($reservation['title']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='px-6 py-4'>No reservations found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
				</div>
				
			</div>
		</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="scripts/script.js"></script>
</body>
</html>