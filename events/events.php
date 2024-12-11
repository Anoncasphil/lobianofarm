<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></script>
	<link rel="stylesheet" href="../styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
	
	<title>AdminSite</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
		<ul class="side-menu">
			<li><a href="../index.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

            <li class="divider" data-text="management">Management</li>
			<li><a href="../reservation/reservation.php"><i class='bx bxs-widget icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-widget icon' ></i> Calendar</a></li>
			<li><a href="../rates/rates.php"><i class="bx bxs-chart icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-widget icon' ></i> Add-ons</a></li>
			<li><a href="../events/events.php" class="active"><i class='bx bxs-widget icon' ></i> Events</a></li>
			<li><a href="../album/album.php"><i class='bx bxs-widget icon' ></i> Album</a></li>

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
					<img class="w-10 h-10 rounded-full" src="../src/images/profile.jpg" alt="Profile Picture">
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

		
        <main class="relative">

        <?php
            // Include the database connection file
            include('../db_connection.php');

            // Fetch events from the database
            $sql = "SELECT id, name, description, picture, date, status FROM events WHERE status = 'active' ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop through each event and display it in a card
                while ($row = $result->fetch_assoc()) {
                    // Prepare event details
                    $eventId = $row['id'];
                    $eventName = $row['name'];
                    $eventDescription = $row['description'];
                    $eventDate = $row['date'];
                    $eventPicture = $row['picture'] ? 'data:image/jpeg;base64,' . base64_encode($row['picture']) : 'default-image.jpg';
                    ?>
                    
                    <div class="w-80 h-96 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col">
                        <a href="#">
                            <img class="h-48 w-full object-cover rounded-t-lg" src="<?php echo $eventPicture; ?>" alt="Event Image" />
                        </a>
                        <div class="p-5 flex flex-col items-center text-center flex-grow">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo $eventName; ?></h5>
                            </a>
                            <p class="mb-4 text-sm text-gray-700 dark:text-gray-400"><?php echo $eventDescription; ?></p>
                            <p class="mb-4 text-sm text-gray-700 dark:text-gray-400"><?php echo date('F d, Y', strtotime($eventDate)); ?></p>

                            <div class="flex space-x-2 mt-auto">
                                <!-- Update Event Button -->
                                <button type="button" onclick="toggleUpdateEventModal(<?php echo $eventId; ?>)" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Archive Event Button -->
                                <button type="button" onclick="archiveEvent(<?php echo $eventId; ?>)" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<p>No events found.</p>";
            }

            // Close the database connection
            $conn->close();
        ?>



    <!-- Add Event Main modal -->
		<div id="add-event-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
			<div class="relative p-4 w-full max-w-md max-h-full">
				<!-- Modal content -->
				<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
					<!-- Modal header -->
					<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							Add Event
						</h3>
						<button type="button" onclick="hideAddEventModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-event-modal">
							<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
							</svg>
							<span class="sr-only">Close modal</span>
						</button>
					</div>
					<!-- Modal body -->
					<form class="p-4 md:p-5" action="add-event.php" method="POST" enctype="multipart/form-data">
						<div class="grid gap-4 mb-4 grid-cols-2">
							<div class="col-span-2">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
								<input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
							</div>

                            <div class="relative w-full max-w-sm ">

                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                <input datepicker id="default-datepicker" type="text" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                            </div>


							<div class="col-span-2">
								<label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Description</label>
								<textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write product description here"></textarea>
							</div>

                        

                            <div class="col-span-2">
								<label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Upload file</label>
								<input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
									aria-describedby="file_input_help" id="file_input" name="picture" type="file" accept="image/png, image/jpeg" required onchange="previewImage(event)">
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG or JPG (MAX. 800x400px).</p>
								
								<div class="mt-2" id="imagePreviewContainer" style="display:none;">
									<img id="imagePreview" class="w-full h-[100px] object-cover rounded-lg img-zoom-out" alt="Image Preview" />
								</div>
							</div>


						</div>
						<button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
							<svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
							Add
						</button>
					</form>
				</div>
			</div>
		</div>
</main>

</section>

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="../scripts/script.js"></script>
	<script src="event.js"></script>


</body>
</html>