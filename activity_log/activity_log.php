<?php
session_start(); // Start the session

// Check if the session is set for the user
if (!isset($_SESSION['admin_id'])) {
    // If not set, redirect to login page
    header("Location: ../adlogin.php");
    exit;
}

include('../db_connection.php'); // Include database connection

// Get filter parameter if it exists
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
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
    <title>Admin Activity Log</title>
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
        <li><a href="../rates/rates.php"><i class="bx bxs-star icon"></i> Rates</a></li>
        <li><a href="../addons/addons.php"><i class='bx bxs-cart-add icon'></i> Add-ons</a></li>
        <!-- <li><a href="../events/events.php"><i class='bx bxs-calendar-event icon'></i> Events</a></li> -->
        <li><a href="../album/album.php"><i class='bx bxs-photo-album icon'></i> Album</a></li>

        <?php if ($_SESSION['role'] === 'superadmin'): ?>
            <li><a href="../team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
            <li><a href="activity_log.php" class="active"><i class='bx bxs-log icon'></i> Activity Log</a></li>
        <?php endif; ?>
    </ul>
</section>
<!-- SIDEBAR -->

<!-- NAVBAR -->
<section id="content">
    <nav>
        <i class='bx bx-menu toggle-sidebar'></i>
        <span class="divider"></span>
        <div class="relative">
            <?php
            // Fetch logged-in admin details
            if (isset($_SESSION['admin_id'])) {
                $admin_id = $_SESSION['admin_id'];

                $query = "SELECT * FROM admin_tbl WHERE admin_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $admin_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $admin = $result->fetch_assoc();
                    $firstname = $admin['firstname'];
                    $lastname = $admin['lastname'];
                    $role = ucfirst($admin['role']); // Capitalize role
                    $profile_picture = '../src/uploads/team/' . $admin['profile_picture'];
                } else {
                    header('Location: adlogin.php');
                    exit;
                }
            } else {
                header('Location: adlogin.php');
                exit;
            }
            ?>

            <!-- Profile Display -->
            <div class="profile flex items-center space-x-4 cursor-pointer">
                <img class="w-10 h-10 rounded-full" src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture">
                <div>
                    <h4 class="text-sm font-medium text-gray-800"><?= htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) ?></h4>
                    <span class="text-xs text-gray-500"><?= htmlspecialchars($role) ?></span>
                </div>
            </div>

            <!-- Logout -->
            <ul class="profile-link absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 hidden">
                <li>
                    <a href="logout.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-100">
                        <i class='bx bxs-log-out-circle text-xl mr-2'></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        <div class="container mx-auto px-4 py-8">
            <div class="activity-log-container">
                <h1 class="text-2xl font-bold mb-6">Activity Log</h1>
                
                <!-- Filter Form -->
                <div class="filter-container mb-6">
                    <form method="get" class="flex items-center space-x-4">
                        <label for="filter" class="font-medium">Filter by:</label>
                        <select name="filter" id="filter" class="border rounded px-3 py-2" onchange="this.form.submit()">
                            <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All Activities</option>
                            <option value="rate" <?= $filter == 'rate' ? 'selected' : '' ?>>Rates</option>
                            <option value="addons" <?= $filter == 'addons' ? 'selected' : '' ?>>Add-ons</option>
                            <option value="admin" <?= $filter == 'admin' ? 'selected' : '' ?>>Admin Accounts</option>
                            <option value="reservation" <?= $filter == 'reservation' ? 'selected' : '' ?>>Reservations</option>
                            <option value="events" <?= $filter == 'events' ? 'selected' : '' ?>>Events</option>
                            <option value="album" <?= $filter == 'album' ? 'selected' : '' ?>>Album</option>
                        </select>
                    </form>
                </div>

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
                            // Set timezone
                            date_default_timezone_set('Asia/Manila');

                            // Base SQL query
                            $sql = "SELECT al.timestamp, a.firstname, a.lastname, al.changes 
                                    FROM activity_logs al
                                    LEFT JOIN admin_tbl a ON al.admin_id = a.admin_id";
                            
                            // Add filter condition if not 'all'
                            if ($filter != 'all') {
                                // Map filters to appropriate search patterns
                                switch ($filter) {
                                    case 'reservation':
                                        // Enhanced reservation filtering with more terms and reservation code pattern
                                        $sql .= " WHERE (al.changes LIKE ? OR al.changes LIKE ? OR al.changes LIKE ? OR al.changes LIKE ? OR al.changes LIKE ? OR al.changes LIKE ?)";
                                        // Match terms like "reservation", "Reservation", "booking", reservation codes like "#123456", status changes, etc.
                                        $filterParams = [
                                            "%reservation%", 
                                            "%Reservation%", 
                                            "%booking%",
                                            "%#%",        // Capture reservation codes (e.g., #123456)
                                            "%status changed%", 
                                            "%check-in%"  // For check-in/check-out mentions
                                        ];
                                        $types = "ssssss";
                                        break;
                                    case 'events':
                                        // Match both singular and plural, case insensitive
                                        $sql .= " WHERE (al.changes LIKE ? OR al.changes LIKE ?)";
                                        $filterParams = ["%event%", "%Events%"];
                                        $types = "ss";
                                        break;
                                    case 'rate':
                                        // Match both singular and plural, case insensitive
                                        $sql .= " WHERE (al.changes LIKE ? OR al.changes LIKE ?)";
                                        $filterParams = ["%rate%", "%pricing%"];
                                        $types = "ss";
                                        break;
                                    case 'addons':
                                        // Match variations
                                        $sql .= " WHERE (al.changes LIKE ? OR al.changes LIKE ? OR al.changes LIKE ?)";
                                        $filterParams = ["%addon%", "%add-on%", "%additional%"];
                                        $types = "sss";
                                        break;
                                    case 'admin':
                                        // Match admin-related terms
                                        $sql .= " WHERE (al.changes LIKE ? OR al.changes LIKE ?)";
                                        $filterParams = ["%admin%", "%account%"];
                                        $types = "ss";
                                        break;
                                    case 'album':
                                        // Match album-related terms
                                        $sql .= " WHERE (al.changes LIKE ? OR al.changes LIKE ? OR al.changes LIKE ?)";
                                        $filterParams = ["%album%", "%photo%", "%image%"];
                                        $types = "sss";
                                        break;
                                    default:
                                        // Default simple filter
                                        $sql .= " WHERE al.changes LIKE ?";
                                        $filterParams = ["%$filter%"];
                                        $types = "s";
                                }
                            }
                            
                            // Add order by
                            $sql .= " ORDER BY al.timestamp DESC";
                            
                            // Prepare and execute the query
                            $stmt = $conn->prepare($sql);
                            
                            if ($filter != 'all') {
                                // Dynamically bind parameters
                                $stmt->bind_param($types, ...$filterParams);
                            }
                            
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $admin_name = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                                    $date_formatted = date("M d Y g:i a", strtotime($row['timestamp']));
                                    $changes = $row['changes']; // Don't use htmlspecialchars to allow HTML tags

                                    echo "<tr>";
                                    echo "<td class='log-date'>$date_formatted</td>";
                                    echo "<td class='log-admin'>$admin_name</td>";
                                    echo "<td class='log-changes'>$changes</td>"; // Display with HTML formatting
                                    echo "</tr>";
                                }
                            } else {
                                echo '<tr><td colspan="3" class="activity-log-empty">No activity logs found for the selected filter.</td></tr>';
                            }

                            $stmt->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</section>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="../scripts/script.js"></script>
</body>
</html>
