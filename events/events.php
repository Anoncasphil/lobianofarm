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
	<link rel="stylesheet" href="../styles/events.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand"><i class='bx bxs-smile icon'></i> Admin</a>
		<ul class="side-menu">
			<li><a href="../index.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="../reservation/reservation_admin.php"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
			<li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<li><a href="../events/events.php" class="active"><i class='bx bxs-calendar-event icon' ></i> Events</a></li>
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

		
        <main class="relative">

		<header class="mb-6">
			<h1 class="text-2xl font-bold text-gray-700">Events</h1>
		</header>


		<div class="event-container rounded-lg p-4">
			<div class="flex justify-between items-center mb-4">
				<!-- Button Container -->
				<div class="flex justify-end w-full space-x-2"> <!-- space-x-2 adds a gap between the buttons -->
					<!-- Add Event Button -->
					<button type="button" onclick="toggleAddEventModal()" class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
						<i class="fa-solid fa-plus"></i> Add Event
					</button>

					<!-- Archive Selected Button -->
					<button type="button" onclick="archiveSelectedEvents()" class="text-white bg-gray-500 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
						<i class="fa-solid fa-archive"></i> Archive Selected
					</button>
				</div>
			</div>
		</div>



		<?php
    // Include database connection
    include('../db_connection.php');

    // Fetch the active events from the database
    $query = "SELECT id, name, picture, date, description FROM events WHERE status = 'active'";
    $result = mysqli_query($conn, $query);

    // Check if data was fetched
    if (mysqli_num_rows($result) > 0) {
        // Loop through each row and display the event details
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="event-list-item bg-white border border-gray-200 rounded-lg shadow-md p-4 mb-4 flex items-center space-x-4 cursor-pointer" onclick="openEventModal(<?php echo $row['id']; ?>)">
                <!-- Selection Box -->
                <input type="checkbox" class="delete-checkbox" value="<?php echo $row['id']; ?>" />

                <!-- Event Picture -->
                <img src="../src/uploads/events/<?php echo $row['picture']; ?>" alt="Event Picture" class="w-20 h-20 object-cover rounded-lg">

                <!-- Event Name and Date -->
                <div class="event-details ml-4 flex-grow">
                    <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="text-gray-500"><?php echo date('F d, Y', strtotime($row['date'])); ?></p>
                </div>

                <!-- Update Button Inside the Card -->
                <button type="button" onclick="toggleUpdateEventModal(<?php echo $row['id']; ?>); openUpdateEventModal()" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    <i class="fa-solid fa-pen-to-square"></i> Update
                </button>
            </div>
            <?php
        }
    } else {
        echo "<p>No events found.</p>";
    }
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
								<input type="text" name="name" id="name" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

							<div class="relative max-w-sm">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
							<input id="date" name="date" autocomplete="off" datepicker datepicker-title="" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
							</div>

							<div class="col-span-2">
								<label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Description</label>
								<textarea id="description" name="description" autocomplete="off" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
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
						<div class="flex justify-end">
							<button type="submit" class="text-white inline-flex items-center bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
								<svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
								</svg>
								Add
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>


		 <!-- Update Event Main modal -->
		 <div id="update-event-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
			<div class="relative p-4 w-full max-w-md max-h-full">
				<!-- Modal content -->
				<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
					<!-- Modal header -->
					<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							Update Event
						</h3>
						<button type="button" onclick="closeUpdateEventModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="update-event-modal">
							<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
							</svg>
							<span class="sr-only">Close modal</span>
						</button>
					</div>
					<!-- Modal body -->
					<form class="p-4 md:p-5" action="update-event.php" method="POST" enctype="multipart/form-data">
						<div class="grid gap-4 mb-4 grid-cols-2">

						<input type="hidden" name="eventId" id="eventId">

							<div class="col-span-2">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
								<input type="text" name="updateName" id="updateName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

							<div class="relative max-w-sm">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
							<input id="updateDate" name="updateDate" autocomplete="off" datepicker datepicker-title="" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
							</div>


							<div class="col-span-2">
								<label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Description</label>
								<textarea id="updateDescription" name="updateDescription" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
							</div>

                            <div class="col-span-2">
								<label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Upload file</label>
								<input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
									aria-describedby="file_input_help" id="updatefile_input" name="updatePicture" type="file" accept="image/png, image/jpeg" onchange="previewImage(event)">
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG or JPG (MAX. 800x400px).</p>
								
								<div class="mt-2" id="image-preview-from-db">
									<img id="updateImagePreviewFromDb" class="w-full h-auto object-cover rounded-lg img-zoom-out" />
								</div>
    
								<!-- Preview container for the new uploaded image -->
								<div class="mt-2 hidden" id="image-preview-new">
									<img id="updateImagePreviewNew" class="w-full h-auto object-cover rounded-lg img-zoom-out" />
								</div>
							</div>


						</div>
						<div class="flex justify-end">
							<button type="submit" class="text-white inline-flex items-center bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
								<svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
								</svg>
								Update
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div id="eventModal" class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center">
			<div class="modal-content bg-white p-6 rounded-lg w-1/3">
				<span class="close-modal absolute top-0 right-0 p-2 text-gray-600 cursor-pointer">
					<i class="fa-solid fa-times"></i>
				</span>

				<h2 class="text-2xl font-semibold" id="eventName"></h2>
				<p id="eventDate" class="text-gray-600"></p>
				<div class="my-4">
					<img id="eventImage" src="" alt="Event Image" class="w-full h-64 object-cover rounded-lg">
				</div>
				<p id="eventDescription" class="text-gray-800"></p>
			</div>
		</div>
</main>

</section>
	<script src="../scripts/event.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></script>
	<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="../scripts/script.js"></script>
	<script>
    // Ensure the function is defined correctly
    function archiveSelectedEvents() {
        // Get all selected checkboxes
        let selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
        let eventIds = [];

        selectedCheckboxes.forEach(function(checkbox) {
            eventIds.push(checkbox.value);
        });

        if (eventIds.length > 0) {
            // Send selected event IDs to the server for archiving
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "archive_events.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status == 200) {
                    console.log("Events archived: " + xhr.responseText);
                    // Optionally, reload the page to reflect changes
                    location.reload();
                } else {
                    console.error("Failed to archive events.");
                }
            };
            xhr.send("event_ids=" + JSON.stringify(eventIds));
        } else {
            alert("Please select at least one event to archive.");
        }
    }
	
	
</script>

</body>
</html>