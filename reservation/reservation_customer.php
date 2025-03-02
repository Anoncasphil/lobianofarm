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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="../scripts/reservation_customer.js" defer></script>

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




<div id="alert-modal" class="max-w-screen-xl mx-auto flex gap-8 mt-10 items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-200 dark:text-blue-900 hidden" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium" id="alert-title">Info alert!</span> 
              <span id="alert-message-modal"></span>
            </div>
          </div>


          <div id="error-modal" class="max-w-screen-xl mx-auto flex gap-8 mt-10 items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-200  hidden" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium" id="alert-title">Info alert!</span> 
              <span id="error-message-modal"></span>
            </div>
          </div>

<div class="max-w-screen-xl mx-auto flex gap-8 mt-10">


 
<div class="flex-4">
    
<form id="reservation-form">



<div class="bg-blue-900 text-white p-4 rounded-t-lg">
    <span class="font-semibold">Reservation Code:</span>
    <span id="reservation-code" class=" font-semibold ml-2">123456</span>
</div>

<div id="basic-details" class="flex-4 bg-white p-6 rounded-lg shadow-lg mt-[-20px] z-10">

    <h2 class="text-xl font-semibold text-gray-900">Details</h2>
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <input type="hidden" name="user_id" id="user_id" />
  <!-- First Name -->
  <div class="relative">
    <input type="text" id="first-name-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="firstname" />
    <label for="first-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">First Name</label>
  </div>
  <!-- Last Name -->
  <div class="relative">
    <input type="text" id="last-name-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" " value="lastname"/>
    <label for="last-name" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Last Name</label>
  </div>
</div>

<!-- Email and Mobile Number Section -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
  <!-- Email -->
  <div class="relative">
    <input type="email" id="email-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="email" />
    <label for="email-p" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Email</label>
  </div>
  <!-- Mobile Number -->
  <div class="relative">
    <input type="text" id="mobile-number-p" class="peer font-semibold p-3 pt-5 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent text-blue-950" placeholder=" "  value="mobilenumber" />
    <label for="mobile-number" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-medium transition-all duration-200">Mobile Number</label>
  </div>
</div>


</div>

<div id="Invoice" class="bg-white p-8 rounded-lg mt-5 shadow-xl max-w-4xl mx-auto">
  <!-- Invoice Header with Buttons -->
  <div class="flex justify-between items-center">
  <h2 class="text-xl font-semibold text-gray-900">Invoice</h2>
  <div class="flex space-x-4"> <!-- Updated flex container with space between buttons -->
    <button type="button" onclick="toggleModal('editModal')" class="bg-blue-900 text-white w-24 font-bold text-sm py-2 px-3 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
      Edit
    </button>
    
    <!-- View Payment Button -->
    <button type="button" onclick="openPaymentModal()" class="bg-blue-900 text-white w-24 font-bold text-sm py-2 px-3 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
    Payment
    </button>
  </div>
</div>



  <!-- Invoice Date and Number -->
  <div class="mt-5 flex justify-between text-sm text-gray-600">
    <p>Date: <span id="invoice-date" class="font-medium text-gray-800"></span></p>
    <p>Invoice No: <span id="invoice-no" class="font-medium text-gray-800"></span></p>
  </div>

  <!-- Invoice Items Table -->
  <div class="mt-8 overflow-x-auto">
    <table class="w-full table-auto border-separate border-spacing-0.5">
      <thead>
        <tr class="bg-gray-100">
          <th class="text-left py-2 px-4 font-medium text-gray-700">Category</th>
          <th class="text-left py-2 px-4 font-medium text-gray-700">Item</th>
          <th class="text-left py-2 px-4 font-medium text-gray-700">Price</th>
        </tr>
      </thead>
      <tbody id="invoice-items">
        <!-- Items will be inserted dynamically -->
      </tbody>
    </table>
  </div>

  <!-- Total Price Section -->
  <div class="mt-6 flex justify-between items-center border-t pt-4">
    <span class="text-xl font-semibold text-white dark:text-gray-900"></span>
    <span id="total-price" class="text-medium font-bold text-gray-500">₱0.00</span>
  </div>

  <div class="flex justify-between items-center pt-4">
    <span class="text-sm font-semibold text-white dark:text-gray-900"></span>
    <span id="valid_amount_paid" class="text-sm font-bold text-gray-500">₱0.00</span>
  </div>

  <div class="flex justify-between items-center pt-4">
    <span class="text-xl font-semibold text-white dark:text-gray-900">Total</span>
    <span id="new_total_amount" class="text-xl font-bold text-blue-900">₱0.00</span>
  </div>
</div>


<!-- Edit Invoice Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-5000000">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div id="info-error-modal" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-200 dark:text-blue-900 hidden" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"></svg>
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="pl-3">
              <span class="font-medium" id="alert-title">Info alert!</span> 
              <span id="error-message-modal" class=""></span>
            </div>
          </div>
    <h3 class="text-lg font-semibold mb-4">Edit Invoice</h3>

    <!-- Hidden Rate ID Input -->
    <input type="hidden" id="rateId" value="" />

    <label for="rateSelected" class="block text-gray-700 font-medium mb-2">Selected Rate</label>
    <select id="rateSelected" class="w-full px-3 py-2 border rounded-md mb-4">
      <!-- Options will be populated dynamically -->
    </select>

<div id="rateDisplay-div" class="w-full relative">
    <input type="text" id="rateInput" class="w-full px-3 py-2 border rounded-md mb-4 shadow-sm text-gray-700 bg-white" readonly>
    <span id="rateDisplay" class="absolute inset-0 px-3 pt-4 border rounded-md bg-gray-100 text-gray-700 shadow-sm flex items-center"></span>
</div>




    <!-- Addons Dropdown -->
    <label for="addonsSelect" class="block text-gray-700 font-medium mb-2">Add Addons</label>
    <div class="flex items-center space-x-2 mb-4">
      <select id="addonsSelect" class="w-full px-3 py-2 border rounded-md">
        <option value="">-- Select an Addon --</option>
        <!-- Addons will be populated dynamically -->
      </select>
      <button id="addAddonBtn" type="button" class="px-4 py-2 bg-blue-900 text-white hover:bg-blue-700 rounded-md">
        Add
      </button>
    </div>

    <!-- Addons Selected -->
    <label for="addonsSelected" class="block text-gray-700 font-medium mb-2">Addons Selected</label>
    <div id="addonsDisplay" class="space-y-4">
      <!-- Dynamically added addons will appear here -->
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end mt-4 space-x-2">
      <button onclick="toggleModal('editModal')" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-sm border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700">Cancel</button>
      <button type="button" onclick="toggleModal('validation-modal')" class="bg-blue-900 text-white px-4 py-2 rounded-md">Edit</button>
    </div>
  </div>
</div>





<!-- Modal -->
<div id="validation-modal" tabindex="-1" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 left-0 w-full h-full flex justify-center items-center z-50000000">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-sm shadow-sm dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-sm text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-900 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure about the changes you made?</h3>
                <button id="confirmButton" data-modal-hide="popup-modal" type="button" class="text-white bg-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-md text-sm inline-flex items-center px-5 py-2.5 text-center"
                    onclick="confirmAction()">
                    Yes, I'm sure
                </button>


                <button data-modal-hide="no-validation" onclick="toggleModal('validation-modal')" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-md border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700">No, cancel</button>
            </div>
        </div>
    </div>
</div>









<!-- View Payment Modal -->
<div id="payment-modal" class="hidden fixed inset-0 flex items-center justify-center bg-black/20 z-50000000">
  <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative z-60">
    <h3 class="text-lg font-semibold mb-4">Payment Details</h3>
    <label class="text-gray-700 font-medium">Reference Number:</label>
    <input type="text" id="referenceNumber" class="w-full p-2 border rounded-md mb-2" readonly>
    
    <!-- Amount Paid -->
    <label class="text-gray-700 font-medium">Amount Paid:</label>
    <input type="text" id="validAmountPaid" class="w-full p-2 border rounded-md mb-4" readonly>
    
    <!-- Payment Receipt Image -->
    <div class="text-center">
      <a id="receiptLink" href="#" data-lightbox="receipt">
        <img id="paymentReceipt" src="" alt="Payment Receipt" class="w-32 h-32 object-cover mx-auto cursor-pointer border rounded-md">
      </a>
    </div>
    
    <div class="flex justify-end mt-4">
      <button onclick="toggleModal('payment-modal')" class="px-4 py-2 bg-blue-900 text-white hover:bg-blue-700 rounded-sm">Close</button>
    </div>
  </div>
</div>



</div>


<div id="sidebar-div" class="flex-2 bg-white p-6 rounded-lg shadow-lg">



<div>

<div class="relative mt-10">
  <label for="status-dropdown" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Status</label>
  <select id="status-dropdown" class="p-3 pt-5 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-950 transition" onchange="updateStatusColor(this)">
    <option value="pending">Pending</option>
    <option value="confirmed">Confirmed</option>
    <option value="completed">Completed</option>
    <option value="cancelled">Cancelled</option>
  </select>
  <div id="selected-status" class="mt-5 ml-2 text-sm font-medium text-gray-600"></div>
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

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10">

  
        <input class="hidden" type="text" id="rate-id-field" name="rate_id" />

        <div class="relative">
          <input type="date" id="check-in-date" class="p-3 pt-5 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-950" required />
          <label for="check-in-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Date</label>
        </div>


        <div class="relative">
          <input type="date" id="check-out-date" class="p-3 pt-5 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-950" disabled />
          <label for="check-out-date" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Date</label>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
        <div class="relative">
          <input type="time" id="check-in-time" class="p-3 pt-5 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-950" disabled />
          <label for="check-in-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-In Time</label>
        </div>
        <div class="relative">
          <input type="time" id="check-out-time" class="p-3 pt-5 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-950" disabled />
          <label for="check-out-time" class="px-2 bg-white absolute left-3 top-[-10px] text-gray-600 text-sm font-medium">Check-Out Time</label>
        </div>
      </div>

      <div id="reschedule-request" class="p-4 mb-4 mt-5 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 " role="alert">
    <div class="flex items-center">
        <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <h3 class="text-lg font-medium pl-3">Reschedule Request</h3>
    </div>

    <div id="reschedule-message" class="mt-2 mb-4 text-sm">
    </div>

    <div class="flex">
        <button id="acceptRequest" type="button" class="text-white bg-blue-900 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-sm text-xs px-3 py-1.5 me-2 text-center inline-flex items-center">
            Accept
        </button>
        <button id="declineRequest" type="button" class="text-white ml-5 bg-gray-700 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-sm text-xs px-3 py-1.5 text-center">
            Decline
        </button>
    </div>
</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let reservationId = localStorage.getItem("reservationID_admin");

        if (!reservationId) {
            console.error("No reservation ID found in localStorage.");
            return;
        }

        fetch(`../api/get_reschedule_request.php?reservation_id=${reservationId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === "success" && data.request) {
                    const request = data.request;
                    document.getElementById("reschedule-message").textContent = 
                        `Customer wants to reschedule date from ${request.check_in_date} to ${request.check_out_date}.`;

                    document.getElementById("reschedule-request").classList.remove("hidden");

                    // Add event listeners to accept and decline buttons
                    document.getElementById("acceptRequest").addEventListener("click", function () {
                        updateRequestStatus(request.request_id, "Approved");
                    });

                    document.getElementById("declineRequest").addEventListener("click", function () {
                        updateRequestStatus(request.request_id, "Denied");
                    });
                } else {
                    console.warn("No reschedule request found.");
                }
            })
            .catch(error => {
                console.error("Error fetching reschedule request:", error);
            });

        function updateRequestStatus(requestId, status) {
            fetch("../api/update_reschedule_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ request_id: requestId, status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    console.log(`Request ${status} successfully.`);
                    document.getElementById("reschedule-request").classList.add("hidden");
                } else {
                    console.error("Error updating request:", data.message);
                }
            })
            .catch(error => {
                console.error("Error updating request:", error);
            });
        }
    });

    
</script>


        <div class="mt-6 flex justify-center">
    <button type="button"  onclick="toggleModal('submit-validation')" id="applyButton" class="bg-blue-900 text-white w-full font-bold py-3 w-80 px-6 rounded-md shadow-lg hover:bg-blue-700 transition duration-200">
    Submit
    </button>
</div>
    </div>
    
</div>

<!-- Modal -->
<div id="submit-validation" tabindex="-1" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 left-0 w-full h-full flex bg-black/20 justify-center items-center z-50000000">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-900 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure about the changes you made?</h3>
                <button id="submitBTN" data-modal-hide="popup-modal" type="button" class="text-white bg-blue-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
                    >
                    Yes, I'm sure
                </button>


                <button data-modal-hide="no-validation" onclick="toggleModal('submit-validation')" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
            </div>
        </div>
    </div>
</div>

</main>


		<!-- MAIN -->
	</section>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="../scripts/script.js"></script>
    
	
</body>
</html>

