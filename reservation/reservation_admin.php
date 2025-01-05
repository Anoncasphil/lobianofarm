<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="../styles/style.css">
	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>

		<ul class="side-menu">
			<li><a href="index.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li class="active"><a href="reservation/reservation_admin.php" class="active"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
			<li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<li><a href="../events/events.php"><i class='bx bxs-calendar-event icon' ></i> Events</a></li>
			<li><a href="../album/album.php"><i class='bx bxs-photo-album icon' ></i> Album</a></li>
			<li><a href="../team/team.php"><i class='bx bxs-buildings icon' ></i> Team</a></li>

			<li class="divider" data-text="table and forms">Table and forms</li>
			<li><a href="#"><i class='bx bx-table icon' ></i> Tables</a></li>
			<li>
				<a href="#"><i class='bx bxs-notepad icon' ></i> Forms <i class='bx bx-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="#">Basic</a></li>
					<li><a href="#">Select</a></li>
					<li><a href="#">Checkbox</a></li>
					<li><a href="#">Radio</a></li>
				</ul>
			</li>
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
			<a href="#" class="nav-link">
				<i class='bx bxs-bell icon' ></i>
				<span class="badge">5</span>
			</a>
			<span class="divider"></span>
			<div class="relative">
				<!-- Profile Dropdown Trigger -->
				<div class="profile flex items-center space-x-4 cursor-pointer">
					<img class="w-10 h-10 rounded-full" src="src/images/profile.jpg" alt="Profile Picture">
					<div>
						<h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">Antoine Philipp Ochea</h4>
						<span class="text-xs text-gray-500 dark:text-gray-400">Admin</span>
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
						<a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-700">
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
						<?php include('get_reservations.php'); ?>
                            <tbody>
                                <?php 
                                $reservations = getReservations();
                                foreach($reservations as $reservation) {
                                    $statusColor = match($reservation['title']) {
                                        'Approved' => 'text-green-500 dark:text-green-400',
                                        'Pending' => 'text-orange-500 dark:text-orange-400',
                                        'Rescheduled' => 'text-blue-500 dark:text-blue-400',
                                        default => 'text-gray-500'
                                    };
                                ?>
                                <tr class="bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800">
                                    <td class="px-6 py-4">
                                        #<?php echo str_pad($reservation['reservation_id'], 3, '0', STR_PAD_LEFT); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo $reservation['first_name'] . ' ' . $reservation['last_name']; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo $reservation['formatted_date']; ?>
                                    </td>
                                    <td class="px-6 py-4 font-medium <?php echo $statusColor; ?>">
                                        <?php echo $reservation['title']; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
					</table>
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