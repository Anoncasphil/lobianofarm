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
	<link rel="stylesheet" href="../styles/style.css">
	<link rel="stylesheet" href="../styles/activity_log.css">
	<title>Admin</title>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">

	<a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>

    <ul class="side-menu">
            <li><a href="../admindash.php"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>

            <li class="divider" data-text="management">Management</li>
            <li><a href="../reservation/reservation_admin.php"><i class='bx bx-list-ol icon'></i> Reservations</a></li>
            <li><a href="../calendar/calendar.php"><i class='bx bxs-calendar icon'></i> Calendar</a></li>
			<li><a href="../sales.php"><i class='bx bx-line-chart icon'></i> Sales</a></li>
            <li><a href="../rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
            <li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon'></i> Add-ons</a></li>
            <li><a href="../events/events.php"><i class='bx bxs-calendar-event icon'></i> Events</a></li>
            <li><a href="../album/album.php"><i class='bx bxs-photo-album icon'></i> Album</a></li>
 
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
                <li><a href="../team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
                <li><a href="../activity_log/activity_log.php" class="active"><i class='bx bxs-log icon'></i> Activity Log</a></li>
            <?php endif; ?>
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
            <div class="container mx-auto px-4 py-8">
                <div class="activity-log-container">
                    <h1 class="text-2xl font-bold mb-6">Activity Log</h1>
                    
                    <div class="table-responsive">
                        <table class="activity-log-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Admin</th>
                                    <th>Changes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('../db_connection.php'); // Include your database connection file
                                
                                // Set timezone to ensure correct time display
                                date_default_timezone_set('Asia/Manila');
                                
                                // Query to get logs from the database with admin and rate information
                                $sql = "SELECT al.id, al.timestamp, a.firstname, a.lastname, r.name as rate_name, al.changes 
                                        FROM activity_logs al
                                        LEFT JOIN admin_tbl a ON al.admin_id = a.admin_id
                                        LEFT JOIN rates r ON al.rate_id = r.id
                                        ORDER BY al.timestamp DESC";
                                        
                                $result = $conn->query($sql);
                                
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $admin_name = $row['firstname'] . ' ' . $row['lastname'];
                                        $date_formatted = date("M d Y g:i a", strtotime($row['timestamp']));
                                        
                                        // Try to decode changes as JSON, if it fails, treat as plain text
                                        $changes_array = json_decode($row['changes'], true);
                                        
                                        echo '<tr class="activity-log-row">';
                                        echo '<td class="log-date">' . $date_formatted . '</td>';
                                        echo '<td class="log-admin">' . $admin_name . '</td>';
                                        echo '<td class="log-changes">';
                                        
                                        if (is_array($changes_array)) {
                                            echo '<div class="change-entries">';
                                            // Display changes in the structured format with each category on a new line
                                            foreach ($changes_array as $category => $detail) {
                                                echo '<div class="change-category">';
                                                echo '<div class="change-detail">'.nl2br($detail).'</div>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        } else {
                                            // Fallback for old format - Convert periods to line breaks for better readability
                                            $changes_text = $row['changes'];
                                            $changes_text = str_replace('. ', ".<br>", $changes_text);
                                            echo '<div class="legacy-format">' . $changes_text . '</div>';
                                        }
                                        
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="3" class="activity-log-empty">No activity logs found.</td></tr>';
                                }
                                
                                $conn->close();
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
	<script src="../scripts/script.js"></script>
</body>
</html>