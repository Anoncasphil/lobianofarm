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
	<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="../styles/style.css">
	<link rel="stylesheet" href="../styles/events.css">
	<link rel="stylesheet" href="../styles/rate.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

	
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand"><i class='bx bxs-smile icon'></i> Admin</a>
		<ul class="side-menu">
			<li><a href="../admindash.php"><i class='bx bxs-dashboard icon' ></i> Dashboard</a></li>

			<li class="divider" data-text="management">Management</li>
			<li><a href="../reservation/reservation_admin.php"><i class='bx bx-list-ol icon' ></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-calendar icon' ></i> Calendar</a></li>
			<li><a href="../sales.php"><i class='bx bx-line-chart icon'></i> Sales</a></li>
			<li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<li><a href="../events/events.php"><i class='bx bxs-calendar-event icon' ></i> Events</a></li>
			<li><a href="../album/album.php"><i class='bx bxs-photo-album icon' ></i> Album</a></li>
			<?php if ($_SESSION['role'] === 'superadmin'): ?>
			<li><a href="../team/team.php" class="active"><i class='bx bxs-buildings icon' ></i> Team</a></li>
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

		
        <main class="relative">
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Team</h1>
		<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#">Management</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Team</a></li>
			</ul>
    </header>

    <div class="event-container rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <!-- Button Container -->
            <div class="flex justify-end w-full space-x-2">
                <!-- Add Admin Button -->
                <button type="button" onclick="toggleAddAdminModal()" class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
                    <i class="fa-solid fa-plus"></i> Add Admin
                </button>

                <!-- Delete Selected Button -->
                <button type="button" onclick="deleteConfirmation()" class="text-white bg-red-500 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </div>
        </div>
    </div>

		<!-- Confirmation Modal -->
	<div id="confirmationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-opacity-50">
		<div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
			<h2 class="text-lg font-medium text-gray-800">Are you sure you want to remove this user?</h2>
			<div class="mt-4 flex justify-end space-x-4">
				<button 
					class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2"
					onclick="closeModal()">No</button>
				<button 
					id="confirmDelete" 
					class="text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2"
					onclick="deleteSelectedAdmins()">Yes</button>
			</div>
		</div>
	</div>

	<!-- Success Modal -->
	<div id="successModal" class="fixed top-4 right-4 z-50 hidden bg-green-500 text-white rounded-lg px-4 py-3 shadow-lg">
		<p class="modal-message">User removed successfully!</p>
	</div>

	<!-- Error Modal -->
	<div id="errorModal" class="fixed top-4 right-4 z-50 hidden bg-red-500 text-white rounded-lg px-4 py-3 shadow-lg">
		<p class="modal-message">An error occurred. Please try again.</p>
	</div>

    <?php
        // Include your database connection
        include '../db_connection.php';

        // Query to fetch all admins
        $query = "SELECT * FROM admin_tbl";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>";  // Start of grid container

            // Loop through each admin and display in the card format
            while ($row = mysqli_fetch_assoc($result)) {
                // Set the profile picture URL, or use a default if null
                $profilePicture = $row['profile_picture'] ? "../src/uploads/team/" . $row['profile_picture'] : '/docs/images/people/default-profile.jpg';
                
                // Set the admin's full name
                $fullName = $row['firstname'] . ' ' . $row['lastname'];
                
                // Set the admin's role and capitalize the first letter
                $role = ucfirst(strtolower($row['role']));
                
                // Set the admin's email
                $email = $row['email'];
                
                echo "
                <!-- MAIN CONTENT -->
                <div class='w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700'>
                    <div class='flex justify-end px-4 pt-4'>
                        <input type='checkbox' class='delete-checkbox' value='".$row['admin_id']."' />
                    </div>
                    <div class='flex flex-col items-center pb-4'>
                        <img class='w-24 h-24 mb-3 rounded-full shadow-lg' src='$profilePicture' alt='$fullName'/>
                        <h5 class='mb-1 text-xl font-medium text-gray-900 dark:text-white'>$fullName</h5>
                        <span class='text-sm text-gray-500 dark:text-gray-400'>$role</span>
                        <span class='text-sm text-gray-500 dark:text-gray-400'>$email</span>
                    </div>
                    <!-- Centering the Update button -->
                    <div class='flex justify-center mb-4'>
                        <button type='button' onclick=\"toggleUpdateAdminModal(" . $row['admin_id'] . "); openUpdateAdminModal()\" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                            <i class='fa-solid fa-pen-to-square'></i> Update
                        </button>
                    </div>
                </div>";
            }

            echo "</div>";  // End of grid container
        } else {
            echo "No admins found.";
        }

        mysqli_close($conn);
    ?>









    <!-- Add Admin Main modal -->
		<div id="add-admin-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
			<div class="relative p-4 w-full max-w-md max-h-full">
				<!-- Modal content -->
				<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
					<!-- Modal header -->
					<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							Add Event
						</h3>
						<button type="button" onclick="hideAddAdminModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-admin-modal">
							<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
							</svg>
							<span class="sr-only">Close modal</span>
						</button>
					</div>
					<!-- Modal body -->
					<form class="p-4 md:p-5" action="add-admin.php" method="POST" enctype="multipart/form-data">
						<div class="grid gap-4 mb-4 grid-cols-2">

							<div class="col-span-2 sm:col-span-1"">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
								<input type="text" name="fname" id="fname" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

                            <div class="col-span-2 sm:col-span-1"">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
								<input type="text" name="lname" id="lname" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

                            <div class="col-span-2 sm:col-span-1"">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
								<input type="email" name="email" id="email" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <div class="relative">
                                    <!-- Boxicons icon aligned to the right -->
                                    <i class="bx bx-show absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" id="togglePassword"></i>
                                    <input type="password" name="password" id="password" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                </div>
                            </div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    <option value="admin">Admin</option>
                                    <option value="superadmin">Superadmin</option>
                                </select>
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

        <!-- Update Admin Main modal -->
		<div id="update-admin-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
			<div class="relative p-4 w-full max-w-md max-h-full">
				<!-- Modal content -->
				<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
					<!-- Modal header -->
					<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							Add Event
						</h3>
						<button type="button" onclick="closeUpdateAdminModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="update-admin-modal">
							<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
							</svg>
							<span class="sr-only">Close modal</span>
						</button>
					</div>
					<!-- Modal body -->
					<form class="p-4 md:p-5" action="update-admin.php" method="POST" enctype="multipart/form-data">
						<div class="grid gap-4 mb-4 grid-cols-2">

                            <input type="hidden" name="adminId" id="adminId">

							<div class="col-span-2 sm:col-span-1"">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
								<input type="text" name="updatefname" id="updatefname" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

                            <div class="col-span-2 sm:col-span-1"">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
								<input type="text" name="updatelname" id="updatelname" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

                            <div class="col-span-2 sm:col-span-1"">
								<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
								<input type="email" name="updateemail" id="updateemail" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
							</div>

                            <div class="col-span-2 sm:col-span-1">
                                <label for="updatepassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <div class="relative">
                                    <!-- Boxicons icon aligned to the right -->
                                    <i class="bx bx-show absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" id="togglePasswordUpdate"></i>
                                    <input type="password" name="updatepassword" id="updatepassword" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                            </div>


                            <div class="col-span-2 sm:col-span-1">
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                <select id="updaterole" name="updaterole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    <option value="admin">Admin</option>
                                    <option value="superadmin">Superadmin</option>
                                </select>
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
</main>

</section>
	<script src="../scripts/team.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="../scripts/script.js"></script>
	<script>
		 // Show the confirmation modal
		 function deleteConfirmation() {
        document.getElementById("confirmationModal").classList.remove("hidden");
    }

    // Close the modal
    function closeModal() {
        document.getElementById("confirmationModal").classList.add("hidden");
    }

	
	</script>
</body>
</html>