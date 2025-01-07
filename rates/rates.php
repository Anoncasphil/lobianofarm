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
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></script>
	<link rel="stylesheet" href="../styles/style.css">
	<link rel="stylesheet" href="../styles/rate.css">
	
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
			<li><a href="../rates/rates.php" class="active"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
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
	<!-- PHP CONNECTION-->
	<?php
	// Include database connection
	include '../db_connection.php';

	// Fetch data from the 'rates' table
	$sql = "SELECT id, name, price, picture FROM rates";  // Adjust if your table structure differs
	$result = $conn->query($sql);

	// Add rate logic
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_rate'])) {
		// Get form data
		$name = $_POST['name'];
		$price = $_POST['price'];
		
		// Handle the image upload
		if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
			$file = $_FILES['picture'];
			
			// Check file size and type
			if ($file['error'] == 0) {
				$fileTmp = $file['tmp_name'];
				$fileName = $file['name'];
				$fileSize = $file['size'];
				$fileType = $file['type'];
				
				// Check if file is a valid image (JPG, PNG, JPEG)
				$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
				if (in_array($fileType, $allowedTypes)) {
					// Read the image content as binary data
					$imageData = file_get_contents($fileTmp);
					$imageData = mysqli_real_escape_string($conn, $imageData);
					
					// Insert the image into the database
					$sql = "INSERT INTO rates (name, price, picture) VALUES (?, ?, ?)";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param('sd', $name, $price, $imageData);
					
					if ($stmt->execute()) {
						echo "New rate added successfully.";
					} else {
						echo "Error: " . $stmt->error;
					}
					$stmt->close();
				} else {
					echo "Invalid file type. Only JPG, PNG, and JPEG are allowed.";
				}
			} else {
				echo "Error uploading the file.";
			}
		}
	}

	?>

	<div class="main flex-1 p-6">
		<header class="mb-6">
			<h1 class="text-2xl font-bold text-gray-700">Rates</h1>
		</header>


		<div class="table-container bg-white shadow-md rounded-lg p-4">
			<div class="flex justify-between items-center mb-4">
				<h2 class="text-lg font-bold text-gray-700">Rates List</h2>
				<button type="button" onclick="toggleAddRateModal()" class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
					<i class="fa-solid fa-plus"></i> Add Rate
				</button>
			</div>

			<table class="table-auto w-full text-left border-collapse">
				<thead class="bg-gray-100">
					<tr>
						<th class="py-3 px-4 border-b text-gray-600">ID</th>
						<th class="py-3 px-4 border-b text-gray-600">NAME</th>
						<th class="py-3 px-4 border-b text-gray-600">Price</th>
						<th class="py-3 px-4 border-b text-gray-600">Hours of Stay</th>
						<th class="py-3 px-4 border-b text-gray-600">Image</th>
						<th class="py-3 px-4 border-b text-gray-600">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "SELECT id, name, price, description, picture, hoursofstay FROM rates WHERE status = 'active'";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							echo "<tr id='rate-" . $row['id'] . "'>";
							echo "<td class='py-2 px-4 border-b text-gray-700'>" . $row['id'] . "</td>";
							echo "<td class='py-2 px-4 border-b text-gray-700'>" . $row['name'] . "</td>";
							echo "<td class='py-2 px-4 border-b text-gray-700'>₱" . number_format($row['price'], 2) . "</td>";
							echo "<td class='py-2 px-4 border-b text-gray-700'>" . $row['hoursofstay'] . "</td>";
							echo "<td class='py-2 px-4 border-b text-gray-700'>";
							
							// Check if picture exists and is not empty
							if (!empty($row['picture'])) {
								// Convert the LONGBLOB to base64
								$base64Image = base64_encode($row['picture']);
								$imageSrc = 'data:image/jpeg;base64,' . $base64Image;

								echo "<img src='" . $imageSrc . "' alt='Rate Image' class='w-20 h-auto object-cover rounded-lg'>";
							}

							echo "</td>";
							echo "<td class='py-2 px-4 border-b text-gray-700'>
								<button 
										type='button' 
										class='text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2' 
										onclick='showDetails(" . $row['id'] . ");'>
										<i class='fa-solid fa-box-archive'></i> View
									</button>
								<button 
									type='button' 
									class='text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2' 
									onclick='fetchRateData(" . $row['id'] . "); toggleUpdateRateModal();'>
									<i class='fa-solid fa-pen-to-square'></i> Update
								</button>
									<button 
										type='button' 
										class='text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2' 
										onclick='archiveConfirmation(" . $row['id'] . ");'>
										<i class='fa-solid fa-box-archive'></i> Archive
									</button>
							</td>";
							echo "</tr>";
						}
					} else {
						echo "<tr><td colspan='6' class='py-2 px-4 border-b text-gray-700'>No records found</td></tr>";
					}
					?>


				</tbody>
			</table>
		</div>
	</div>

<!-- Modal -->
<div id="detailsModal" class="fixed inset-0 hidden bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full p-6 flex flex-col md:flex-row relative">
        <!-- Left side: Image -->
        <div class="flex-shrink-0 w-full md:w-1/3 mb-4 md:mb-0">
            <img id="modalPicture" src="" alt="Image" class="object-cover w-full rounded-lg h-64 md:h-auto md:w-full" />
        </div>

        <!-- Right side: Details -->
        <div class="flex flex-col justify-between p-4 leading-normal w-full md:w-2/3">
            <h5 id="modalTitle" class="text-xl font-semibold text-gray-800 mb-2">Details</h5>
            <p><strong class="text-sm text-gray-600">Price:</strong> <span id="modalPrice" class="text-gray-700"></span></p>
            <p><strong class="text-sm text-gray-600">Hours of Stay:</strong> <span id="modalHoursOfStay" class="text-gray-700"></span></p>
            <p><strong class="text-sm text-gray-600">Description:</strong></p>
            <p id="modalDescription" class="text-gray-700 mt-2"></p>
        </div>

        <!-- Close Button -->
        <button onclick="closeDetailsModal()" class="absolute top-2 right-2 text-2xl text-gray-600 hover:text-gray-800 focus:outline-none">
            &times;
        </button>
    </div>
</div>



		<div id="rateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
			<div class="bg-white rounded-lg p-6 w-96">
				<h2 id="modalTitle" class="text-lg font-semibold mb-2"></h2>
				<p id="modalDescription" class="text-gray-700 mb-4"></p>
				<p id="modalPrice" class="text-gray-700 mb-4"></p>
				<p id="modalHours" class="text-gray-700 mb-4"></p>
				<img id="modalImage" class="w-40 h-auto rounded-lg mb-4 hidden">
				<button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded-lg">Close</button>
			</div>
		</div>

		<!-- Modal -->
		<div id="archiveModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-opacity-50">
			<div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
				<h2 class="text-lg font-medium text-gray-800">Are you sure you want to archive this event?</h2>
				<div class="mt-4 flex justify-end space-x-4">
					<button 
						class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2"
						onclick="closeModal()">No</button>
					<button 
						id="confirmArchive" 
						class="text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2">
						Yes
					</button>
				</div>
			</div>
		</div>

		<!-- Success Modal -->
		<div id="successModal" class="fixed top-4 right-4 z-50 hidden bg-green-500 text-white rounded-lg px-4 py-3 shadow-lg">
			<p class="modal-message"></p>
		</div>


		<!-- Error Modal -->
		<div id="errorModal" class="fixed top-4 right-4 z-50 hidden bg-red-500 text-white rounded-lg px-4 py-3 shadow-lg">
			<p class="modal-message"></p>
		</div>


				
		<!-- Add Rate Main modal -->
		<div id="add-rate-modal" onsubmit="addRate(event)" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
			<div class="relative p-4 w-full max-w-md max-h-full">
				<!-- Modal content -->
				<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
					<!-- Modal header -->
					<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							Add New Rate
						</h3>
						<button type="button" onclick="hideRateModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-rate-modal">
							<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
							</svg>
							<span class="sr-only">Close modal</span>
						</button>
					</div>
					<!-- Modal body -->
					<form class="p-4 md:p-5" action="add-rate.php" method="POST" enctype="multipart/form-data">
						<div class="grid gap-4 mb-4 grid-cols-2">
							<div class="col-span-2">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
								<input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
							</div>
							<div class="col-span-2 sm:col-span-1">
								<label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
								<input type="number" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="₱15000" required="">
							</div>
							<div class="col-span-2 sm:col-span-1">
								<label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hours of Stay</label>
								<input type="number" name="hours" id="hours" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="12" required="">
							</div>

							<div class="col-span-2">
								<label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rate Description</label>
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

		<!-- Update Rate Main modal -->
		<div id="update-rate-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
			<div class="relative p-4 w-full max-w-md max-h-full">
				<!-- Modal content -->
				<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
					<!-- Modal header -->
					<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							Update Rate
						</h3>
						<button type="button" onclick="hideUpdateRateModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="update-rate-modal">
							<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
							</svg>
							<span class="sr-only">Close modal</span>
						</button>
					</div>
					<!-- Modal body -->
					<form class="p-4 md:p-5" onsubmit="" action="update-rate.php" method="POST" enctype="multipart/form-data">

						<div class="grid gap-4 mb-4 grid-cols-2">

							<input type="hidden" name="id" id="updateRateId"/>

							<div class="col-span-2">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
								<input type="text" name="name" id="updatename" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"  required="">
							</div>
							<div class="col-span-2 sm:col-span-1">
								<label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
								<input type="number" name="price" id="updateprice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"  required="">
							</div>
							<div class="col-span-2 sm:col-span-1">
								<label for="hoursofstay" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hours of Stay</label>
								<input type="number" name="hoursofstay" id="updatehoursofstay" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"  required="">
							</div>

							<div class="col-span-2">
								<label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rate Description</label>
								<textarea id="updatedescription" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
							</div>

							<div class="col-span-2">
								<label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Upload file</label>
								<input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
									aria-describedby="file_input_help" id="updatefile_input" name="picture" type="file">
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG or JPG (MAX. 800x400px).</p>
								
								<!-- Preview container for the image from the database -->
								<div class="mt-2" id="image-preview-from-db">
									<img id="updateImagePreviewFromDb" class="w-full h-auto object-cover rounded-lg img-zoom-out" />
								</div>
    
								<!-- Preview container for the new uploaded image -->
								<div class="mt-2 hidden" id="image-preview-new">
									<img id="updateImagePreviewNew" class="w-full h-auto object-cover rounded-lg img-zoom-out" />
								</div>
							</div>
						</div>
						<button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
							<svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
							Update
						</button>
					</form>
				</div>
			</div>
		</div>
	</main>
</section>

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="../scripts/script.js"></script>
	<script src="../scripts/modalscript.js" defer></script>

	<script>
		function archiveConfirmation(rateId) {
    // Show the modal and attach the rate ID to the confirm button
    const modal = document.getElementById('archiveModal');
    modal.classList.remove('hidden');
    const confirmButton = document.getElementById('confirmArchive');
    confirmButton.onclick = function () {
        archiveRate(rateId);
        closeModal();
    };
}

function archiveRate(rateId) {
    // Create a request to archive the rate
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "archive-rate.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
        console.log("Server Response: ", xhr.responseText);
        if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
            const row = document.getElementById('rate-' + rateId);
            if (row) {
                row.style.display = 'none';
            }
            showModal('successModal', 'The rate has been successfully archived.');
        } else {
            showModal('errorModal', 'Failed to archive the rate. Please try again.');
        }
    }
};


    // Send the rate ID to the server to mark it as inactive
    xhr.send("id=" + rateId);
}

function closeModal() {
    // Hide the modal
    const modal = document.getElementById('archiveModal');
    modal.classList.add('hidden');
}

function showModal(modalId, message) {
    console.log(`Showing modal: ${modalId} with message: ${message}`);
    const modal = document.getElementById(modalId);
    if (modal) {
        const messageContainer = modal.querySelector('.modal-message');
        if (messageContainer) {
            messageContainer.textContent = message;
        }
        modal.classList.remove('hidden');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 3000);
    }
}

function showDetails(id) {
    // Fetch data from the database using AJAX
    fetch('fetch-rate.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            // Populate modal fields with data
            document.getElementById('modalTitle').textContent = data.name
            document.getElementById('modalPrice').textContent = data.price;
            document.getElementById('modalHoursOfStay').textContent = data.hoursofstay;
            document.getElementById('modalDescription').textContent = data.description;
            document.getElementById('modalPicture').src = data.picture;

            // Show the modal
            document.getElementById('detailsModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error fetching data:', error));
}

function closeDetailsModal() {
    // Hide the modal
    document.getElementById('detailsModal').classList.add('hidden');
}
wwa
function closeModal() {
    // Hide the modal
    const modal = document.getElementById('archiveModal');
    modal.classList.add('hidden');
}

	</script>

</body>
</html>