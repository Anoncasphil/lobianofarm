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
	<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

		<?php
include '../db_connection.php';

// Get filter value (0 = Active, 1 = Archived)
$filter = isset($_GET['filter']) ? intval($_GET['filter']) : 0;

// Fetch folders based on filter
$sql = "SELECT * FROM folders WHERE archived = $filter ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
        <main class="relative">

    <!-- Main Container -->
    <div class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg mx-auto mt-6">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Album Management</h1>
            <button onclick="toggleAddFolderModal()" class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Add Folder
            </button>
        </header>

        <!-- Folder Toggle Tabs -->
        <div class="flex space-x-4 mb-4">
            <button onclick="filterFolders(0)" class="px-4 py-2 rounded-lg text-sm font-medium <?= $filter == 0 ? 'bg-blue-900 text-white' : 'bg-gray-200' ?>">
                Active Folders
            </button>
            <button onclick="filterFolders(1)" class="px-4 py-2 rounded-lg text-sm font-medium <?= $filter == 1 ? 'bg-red-900 text-white' : 'bg-gray-200' ?>">
                Archived Folders
            </button>
        </div>

		<div id="info-alert" class="flex items-center p-3 mb-3 mt-5 text-sm text-blue-800 rounded-lg bg-blue-200 hidden">
          <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <div>
            <span class="font-medium" id="alert-title">Info alert!</span> 
            <span id="alert-message"></span>
          </div>
        </div>


<!-- Folder List -->
<div id="folderList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="folder-item bg-gray-100 p-4 rounded-lg flex justify-between items-center cursor-pointer transition hover:bg-gray-200"
             onclick="redirectToUpload(<?php echo $row['id']; ?>)">
            <div>
                <h2 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($row['name']); ?></h2>
                <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Edit Button -->
                <button onclick="event.stopPropagation(); openEditFolderModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['description']); ?>');" 
                        class="text-gray-500 hover:text-blue-600">
                    <span class="material-icons">edit</span>
                </button>

                <?php if ($filter == 0): ?>
                    <!-- Archive/Delete Button -->
                    <button onclick="event.stopPropagation(); openArchiveModal(event, <?php echo $row['id']; ?>);" 
                            class="text-gray-500 hover:text-red-600">
                        <span class="material-icons">delete</span>
                    </button>
                <?php else: ?>
                    <!-- Restore Button -->
					<button onclick="event.stopPropagation(); toggleModal(event, 'restoreModal', <?php echo $row['id']; ?>);"
        class="text-gray-500 hover:text-green-600">
    <span class="material-icons">restore</span>
</button>

<button onclick="event.stopPropagation(); toggleModal(event, 'permanentDeleteModal', <?php echo $row['id']; ?>);"
        class="text-gray-500 hover:text-red-800">
    <span class="material-icons">delete_forever</span>
</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>


    <!-- Add Folder Modal -->
    <div id="addFolderModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Add Folder</h2>
            <form id="addFolderForm">
                <label class="block text-sm font-medium">Folder Name</label>
                <input type="text" name="name" id="folderName" required class="w-full p-2 border rounded-lg mb-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" id="folderDescription" class="w-full p-2 border rounded-lg mb-4"></textarea>
                <div class="flex justify-end">
                    <button type="button" onclick="toggleAddFolderModal()" class="px-4 py-2 text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-700">Add</button>
                </div>
            </form>
        </div>
    </div>

<!-- Edit Folder Modal -->
<div id="editFolderModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Edit Folder</h2>
        <form id="editFolderForm" onsubmit="updateFolder(event)">
            <input type="hidden" id="editFolderId">

            <label class="block text-sm font-medium">Folder Name</label>
            <input type="text" id="editFolderName" required class="w-full p-2 border rounded-lg mb-2">

            <label class="block text-sm font-medium">Description</label>
            <textarea id="editFolderDescription" class="w-full p-2 border rounded-lg mb-4"></textarea>

            <div class="flex justify-end">
                <button type="button" onclick="closeEditFolderModal()" class="px-4 py-2 text-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>



    <!-- Archive Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-800">Are you sure you want to archive this folder?</h2>
            <div class="mt-4 flex justify-end space-x-4">
                <button class="text-gray-700 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300" onclick="closeModal()">No</button>
                <button id="confirmArchive" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700" onclick="archiveFolder();">
                    Yes
                </button>
            </div>
        </div>
    </div>

       <!-- Restore Confirmation Modal -->
       <div id="restoreModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-800">Restore this folder?</h2>
            <div class="mt-4 flex justify-end space-x-4">
			<button class="text-gray-700 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300" onclick="toggleModal(event, 'restoreModal')">No</button>
                <button id="confirmRestore" class="bg-green-900 text-white px-4 py-2 rounded-lg hover:bg-green-700" onclick="restoreFolder();">
                    Yes
                </button>
            </div>
        </div>
    </div>

    <!-- Permanent Delete Modal -->
    <div id="permanentDeleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-800">Permanently delete this folder? This action cannot be undone.</h2>
            <div class="mt-4 flex justify-end space-x-4">
                <button class="text-gray-700 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300" onclick="toggleModal(event, 'permanentDeleteModal')">No</button>
                <button id="confirmDelete" class="bg-red-900 text-white px-4 py-2 rounded-lg hover:bg-red-700" onclick="deleteFolder();">
                    Yes
                </button>
            </div>
        </div>
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
    // Archive folder logic
let selectedFolderId = null;

function openArchiveModal(event, folderId) {
    event.stopPropagation(); // Prevent unintended actions
    selectedFolderId = folderId;
    $("#deleteModal").removeClass("hidden");
}

function closeModal() {
    $("#deleteModal").addClass("hidden");
    selectedFolderId = null;
}

function archiveFolder() {
    if (!selectedFolderId) {
        showMessage("Error: No folder selected.");
        return;
    }

    $.post("archive_folder.php", { folder_id: selectedFolderId }, function (response) {
        try {
            let res = typeof response === "object" ? response : JSON.parse(response);
            if (res.success) {
                localStorage.setItem("folderSuccessMessage", res.message);
                location.reload();
            } else {
                showMessage("Error: " + res.message);
            }
        } catch (error) {
            console.error("JSON Parsing Error:", error, response);
            showMessage("Invalid response from server.");
        }
    }).fail(function (xhr) {
        console.error("AJAX Error:", xhr.responseText);
        showMessage("Failed to archive folder.");
    });

    closeModal();
}
    </script>
    <script src="file_manager.js" defer></script>
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