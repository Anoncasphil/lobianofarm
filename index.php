<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="styles/style.css">
	
	<title>Cabuquin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Ad</a>

		<ul class="side-menu">
			<li class="active"><a href="index.php" class="active"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="reservation/reservation.php"><i class='bx bxs-widget icon' ></i> Reservations</a></li>
            <li><a href="calendar/calendar.php"><i class='bx bxs-widget icon' ></i> Calendar</a></li>
			<li><a href="rates/rates.php"><i class="bx bxs-chart icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="addons/addons.php"><i class='bx bxs-widget icon' ></i> Add-ons</a></li>
			<li><a href="events/events.php"><i class='bx bxs-widget icon' ></i> Events</a></li>
			<li><a href="album/album.php"><i class='bx bxs-widget icon' ></i> Album</a></li>

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
							<h2>1500</h2>
							<p>Sales</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="40%"></span>
					<span class="label">40%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>234</h2>
							<p>Sales</p>
						</div>
						<i class='bx bx-trending-down icon down' ></i>
					</div>
					<span class="progress" data-value="60%"></span>
					<span class="label">60%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>465</h2>
							<p>Pageviews</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="30%"></span>
					<span class="label">30%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>235</h2>
							<p>Visitors</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="80%"></span>
					<span class="label">80%</span>
				</div>
			</div>
			<div class="data">

				<!-- sales graph -->
				<div class="content-data">
					<div class="head">
						<h3>Sales Report</h3>
					</div>
					<div class="chart">
						<div id="chart"></div>
					</div>
				</div>

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
							<tr class="bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800">
								<td class="px-6 py-4">
									#001
								</td>
								<td class="px-6 py-4">
									John Doe
								</td>
								<td class="px-6 py-4">
									Dec 5, 2024
								</td>
								<td class="px-6 py-4 font-medium text-green-500 dark:text-green-400">
									Reserved
								</td>
							</tr>
							<tr class="bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800">
								<td class="px-6 py-4">
									#002
								</td>
								<td class="px-6 py-4">
									Jane Smith
								</td>
								<td class="px-6 py-4">
									Dec 10, 2024
								</td>
								<td class="px-6 py-4 font-medium text-orange-500 dark:text-orange-400">
									Pending
								</td>
							</tr>
							<tr class="bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800">
								<td class="px-6 py-4">
									#003
								</td>
								<td class="px-6 py-4">
									Michael Brown
								</td>
								<td class="px-6 py-4">
									Dec 12, 2024
								</td>
								<td class="px-6 py-4 font-medium text-blue-500 dark:text-blue-400">
									Rescheduled
								</td>
							</tr>
							<tr class="bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800">
								<td class="px-6 py-4">
									#004
								</td>
								<td class="px-6 py-4">
									Alice Johnson
								</td>
								<td class="px-6 py-4">
									Dec 15, 2024
								</td>
								<td class="px-6 py-4 font-medium text-orange-500 dark:text-orange-400">
									Pending
								</td>
							</tr>
							<tr class="bg-gray-50 dark:bg-gray-900">
								<td class="px-6 py-4">
									#005
								</td>
								<td class="px-6 py-4">
									Robert Lee
								</td>
								<td class="px-6 py-4">
									Dec 20, 2024
								</td>
								<td class="px-6 py-4 font-medium text-green-500 dark:text-green-400">
									Reserved
								</td>
							</tr>
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