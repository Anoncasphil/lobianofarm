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
    <link rel="stylesheet" href="../styles/album.css">
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
			<li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
			<li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon' ></i> Add-ons</a></li>
			<li><a href="../events/events.php"><i class='bx bxs-calendar-event icon' ></i> Events</a></li>
			<li><a href="../album/album.php" class="active"><i class='bx bxs-photo-album icon' ></i> Album</a></li>
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
        <h1 class="text-2xl font-bold text-gray-700">Album</h1>
        <ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#">Management</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Album</a></li>
			</ul>
    </header>



    <div class="event-container rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <div class="flex justify-end w-full">
            <div class="button-container flex gap-4">
                <button type="button" onclick="toggleAddAlbumModal()" class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
                    <i class="fa-solid fa-plus"></i> Add Picture
                </button>
                <button type="button" class="text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2" id="deleteSelectedButton" onclick="deleteConfirmation()">
                    <i class="fa-solid fa-trash"></i> Delete Selected
                </button>
            </div>

            </div>
        </div>

        <div class="flex flex-col space-y-4" id="albumContainer">
            <?php
            include('../db_connection.php');
            $query = "SELECT * FROM pictures";
            $result = mysqli_query($conn, $query);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $image_name = $row['image_name'];
                    $image_path = $row['image_path'];
                    $upload_time = $row['upload_time'];
                    ?>
                    <div class="flex items-center justify-start space-x-4 border-b py-4 shadow dark:bg-gray-800 dark:border-gray-700 rounded-lg" id="pic-<?php echo $row['id']; ?>">
                        <input type="checkbox" class="select-picture-checkbox" data-id="<?php echo $row['id']; ?>">
                        <img src="../src/uploads/album/<?php echo $image_path; ?>" alt="Image" class="w-16 h-16 object-cover">
                        <div class="flex flex-col text-left w-full">
                            <p class="text-sm font-semibold text-gray-700"><?php echo $image_name; ?></p>
                            <p class="text-xs text-gray-500"><?php echo date('F j, Y', strtotime($upload_time)); ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

	<!-- Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-opacity-50">
		<div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
			<h2 class="text-lg font-medium text-gray-800">Are you sure you want to archive this event?</h2>
			<div class="mt-4 flex justify-end space-x-4">
				<button 
					class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2"
					onclick="closeModal()">No</button>
					<button 
						id="confirmArchive" 
						class="text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2"
						onclick="deleteSelectedPictures();">
						Yes
					</button>

			</div>
		</div>
	</div>

<!-- Success Modal -->
<div id="successModal" class="modal hidden">
    <div class="modal-content">
        <p class="modal-message"></p>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="modal hidden">
    <div class="modal-content">
        <p class="modal-message"></p>
    </div>
</div>

    <!-- Add Picture Modal -->
    <div id="add-picture-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Add Picture
                        </h3>
                        <button type="button" onclick="hideAddPictureModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-picture-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" action="upload-picture.php" method="POST" enctype="multipart/form-data">
                        <div class="grid gap-4 mb-4 grid-cols-1 21Q ">
                            <!-- Picture Name -->
                            <div>
                                <label for="picture-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Picture Name</label>
                                <input type="text" name="picture_name" id="picture-name" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                            </div>
                            <!-- Upload File -->
                            <div>
                                <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Picture</label>
                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" name="picture" type="file" accept="image/png, image/jpeg" required onchange="previewImage(event)">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG or JPG (MAX. 800x400px).</p>
                                <!-- Image Preview -->
                                <div class="mt-2" id="imagePreviewContainer" style="display:none;">
                                    <img id="imagePreview" class="w-full h-[100px] object-cover rounded-lg img-zoom-out" alt="Image Preview">
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="text-white inline-flex items-center bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Add Picture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

		<div id="successModal" class="fixed top-4 right-4 z-50 hidden bg-green-500 text-white rounded-lg px-4 py-3 shadow-lg">
			<p class="modal-message"></p>
		</div>


		<!-- Error Modal -->
		<div id="errorModal" class="fixed top-4 right-4 z-50 hidden bg-red-500 text-white rounded-lg px-4 py-3 shadow-lg">
			<p class="modal-message"></p>
		</div>
        
</main>






</section>
	<script src="../scripts/album.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></script>
	<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="../scripts/script.js"></script>
	<script>
// Function to handle delete confirmation
document.getElementById("confirmArchive").addEventListener("click", function() {
    deleteSelectedPictures(); // Call the function to delete pictures
    // Hide the delete modal
    document.getElementById("deleteModal").classList.add('hidden');
});


function deleteSelectedPictures() {
    const selectedPictures = [];
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');

    // Collect the selected pictures' IDs
    checkboxes.forEach(checkbox => {
        selectedPictures.push(checkbox.getAttribute('data-id'));
    });

    if (selectedPictures.length > 0) {
        // Send AJAX request to delete the selected pictures
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_pictures.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    // Try to parse the response as JSON
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Show success modal
                        showSuccessModal(response.message);
                        window.location.href = "album.php?status=success"; // Redirect immediately
                    } else {
                        showErrorModal(response.message); // Show error modal if deletion failed
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    console.log(xhr.responseText); // Log the raw response to see the actual output
                }
            } else {
                showErrorModal('Request failed with status ' + xhr.status);
            }
        };
        xhr.send('ids=' + selectedPictures.join(','));
    } else {
        showErrorModal('No pictures selected for deletion.');
    }
}

// Function to show success modal
function showSuccessModal(message) {
    const successModal = document.getElementById('successModal');
    successModal.querySelector('.modal-message').textContent = message;
    successModal.classList.remove('hidden'); // Make the modal visible
}




// Function to show the delete confirmation modal
function deleteConfirmation() {
    // Show the confirmation modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeModal() {
    // Hide the modal
    document.getElementById('deleteModal').classList.add('hidden');
}

	</script>

</body>
</html>