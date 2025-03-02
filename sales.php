<?php
// filepath: /c:/xampp_main/htdocs/lobianofarm/sales.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the session is set for the user
if (!isset($_SESSION['admin_id'])) {
    header("Location: adlogin.php");
    exit;
}

require_once 'db_connection.php';

// Default to overall if no period is specified (changed from monthly)
$period = isset($_GET['period']) ? $_GET['period'] : 'overall';

// Get current date information for queries
$current_date = date('Y-m-d');
$current_year = date('Y');

// Function to get overall sales (all time)
function getOverallSales($conn) {
    // Get all sales data grouped by month and year
    $query = "SELECT 
              DATE_FORMAT(check_out_date, '%Y-%m') AS month_year,
              DATE_FORMAT(check_out_date, '%b %Y') AS month_name,
              SUM(total_price) AS total_sales,
              COUNT(*) AS reservation_count
              FROM reservations 
              WHERE status IN ('Completed', 'completed', 'COMPLETED')
              GROUP BY month_year, month_name
              ORDER BY month_year ASC";  
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sales_data = [];
    $total_sales = 0;
    
    while ($row = $result->fetch_assoc()) {
        $month_sales = (float)($row['total_sales'] ?? 0);
        $total_sales += $month_sales;
        
        $sales_data[] = [
            'period' => $row['month_name'],
            'value' => $row['month_year'],
            'sales' => $month_sales,
            'count' => $row['reservation_count']
        ];
    }
    
    return [
        'period_data' => $sales_data,
        'total_sales' => $total_sales
    ];
}

// Function to get yearly sales data
function getYearlySales($conn) {
    $current_year = date('Y');
    
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    
    $sales_data = [];
    $total_year_sales = 0;
    
    foreach ($months as $month_num => $month_name) {
        $start_date = sprintf('%04d-%02d-01', $current_year, $month_num);
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $query = "SELECT SUM(total_price) as month_sales, COUNT(*) as reservation_count
                 FROM reservations 
                 WHERE status IN ('Completed', 'completed', 'COMPLETED')
                 AND check_out_date BETWEEN ? AND ?";
                 
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $month_sales = (float)($row['month_sales'] ?? 0);
        $reservation_count = $row['reservation_count'] ?? 0;
        $total_year_sales += $month_sales;
        
        $sales_data[] = [
            'period' => $month_name,
            'value' => $month_num,
            'sales' => $month_sales,
            'count' => $reservation_count
        ];
    }
    
    return [
        'period_data' => $sales_data,
        'total_sales' => $total_year_sales
    ];
}

// Get data based on selected period
switch($period) {
    case 'overall':
        $sales_data = getOverallSales($conn);
        break;
    case 'yearly':
        $sales_data = getYearlySales($conn);
        break;
    default:
        $sales_data = getOverallSales($conn);
        break;
}

// Debug info to check what's happening
if ($_SESSION['role'] === 'superadmin') {
    $debug_query = "SELECT id, status, check_out_date, total_price 
                   FROM reservations 
                   WHERE status IN ('Completed', 'completed', 'COMPLETED')
                   LIMIT 10";
    $debug_result = $conn->query($debug_query);
    $debug_rows = [];
    if ($debug_result) {
        while($row = $debug_result->fetch_assoc()) {
            $debug_rows[] = $row;
        }
    }
}

// Check if we have data (just check for data, don't require sales > 0)
$has_data = !empty($sales_data['period_data']);

// For overview stats - case-insensitive query with more flexible status matching
$total_query = "SELECT COUNT(*) as total_count, SUM(total_price) as total_sales FROM reservations WHERE status IN ('Completed', 'completed', 'COMPLETED')";
$total_result = $conn->query($total_query);
$total_data = $total_result->fetch_assoc();
$total_completed_reservations = $total_data['total_count'] ?? 0;
$total_completed_sales = $total_data['total_sales'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <title>Sales Dashboard</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-smile icon'></i>Admin</a>
        <ul class="side-menu">
            <li><a href="admindash.php"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>

            <li class="divider" data-text="management">Management</li>
            <li><a href="reservation/reservation_admin.php"><i class='bx bx-list-ol icon'></i> Reservations</a></li>
            <li><a href="calendar/calendar.php"><i class='bx bxs-calendar icon'></i> Calendar</a></li>
            <li class="active"><a href="sales.php" class="active"><i class='bx bx-line-chart icon'></i> Sales</a></li>
            <li><a href="rates/rates.php"><i class="bx bxs-star icon min-w-[48px] flex justify-center items-center mr-2"></i>Rates</a></li>
            <li><a href="addons/addons.php"><i class='bx bxs-cart-add icon'></i> Add-ons</a></li>
            <li><a href="events/events.php"><i class='bx bxs-calendar-event icon'></i> Events</a></li>
            <li><a href="album/album.php"><i class='bx bxs-photo-album icon'></i> Album</a></li>
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
                <li><a href="team/team.php"><i class='bx bxs-buildings icon'></i> Team</a></li>
                <li><a href="../activity_log/activity_log.php"><i class='bx bxs-log icon'></i> Activity Log</a></li>
            <?php endif; ?>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
            </form>
            <span class="divider"></span>
            <div class="relative">
                <!-- Profile Dropdown Trigger -->
                <?php
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
                        $profile_picture = 'src/uploads/team/' . $admin['profile_picture'];
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
            <h1 class="title">Sales Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="admindash.php">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Sales</a></li>
            </ul>

            

            <!-- Total Sales Highlight Box -->
            <div class="content-data">
                <div class="text-center py-4 bg-white rounded-lg shadow">
                    <h3 class="text-lg text-gray-600">Total Sales (Completed Reservations)</h3>
                    <div class="text-4xl font-bold text-blue-600 mt-2">₱<?= number_format($total_completed_sales, 2) ?></div>
                    <div class="text-sm text-gray-500 mt-1">From <?= $total_completed_reservations ?> completed reservations</div>
                </div>
            </div>

            <!-- Period Selection -->
            <div class="mt-6 flex justify-between items-center">
                <div class="flex space-x-3">
                    <a href="?period=overall" class="<?= $period === 'overall' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' ?> px-4 py-2 rounded-lg hover:bg-blue-500 hover:text-white transition-colors">Overall</a>
                    <a href="?period=yearly" class="<?= $period === 'yearly' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' ?> px-4 py-2 rounded-lg hover:bg-blue-500 hover:text-white transition-colors">Yearly</a>
                </div>
                <div class="text-xl font-semibold text-gray-700">
                    <?= ucfirst($period) ?> Total: ₱<?= number_format($sales_data['total_sales'], 2) ?>
                </div>
            </div>

            <!-- Sales Chart -->
            <div class="content-data mt-4">
                <div id="sales-chart" class="h-96 bg-white rounded-lg shadow p-4"></div>
            </div>

            <!-- Sales Table -->
            <div class="content-data mt-6">
                <div class="head">
                    <h3><?= ucfirst($period) ?> Sales Breakdown</h3>
                </div>
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-700 dark:text-gray-300">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-100 dark:bg-gray-800 dark:text-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    <?php if ($period === 'overall'): ?>
                                        Month/Year
                                    <?php else: ?>
                                        Month
                                    <?php endif; ?>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <?php if ($period === 'overall'): ?>
                                        Period
                                    <?php else: ?>
                                        Month Number
                                    <?php endif; ?>
                                </th>
                                <th scope="col" class="px-6 py-3">Reservations</th>
                                <th scope="col" class="px-6 py-3">Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($has_data): ?>
                                <?php foreach ($sales_data['period_data'] as $data): ?>
                                    <tr class="bg-gray-50 border-b dark:bg-gray-900 dark:border-gray-800">
                                        <td class="px-6 py-4"><?= htmlspecialchars($data['period']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($data['value']) ?></td>
                                        <td class="px-6 py-4"><?= $data['count'] ?></td>
                                        <td class="px-6 py-4 font-medium">₱<?= number_format($data['sales'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No sales data available for this period</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if ($has_data): ?>
                        <tfoot>
                            <tr class="font-semibold text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700">
                                <th scope="row" class="px-6 py-3 text-base" colspan="2">Total</th>
                                <td class="px-6 py-3"><?= array_sum(array_column($sales_data['period_data'], 'count')) ?></td>
                                <td class="px-6 py-3">₱<?= number_format($sales_data['total_sales'], 2) ?></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- NAVBAR -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar
        const toggleSidebar = document.querySelector('.toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('hide');
        });

        // Profile dropdown
        const profileButton = document.querySelector('.profile');
        const profileDropdown = document.querySelector('.profile-link');
        
        profileButton.addEventListener('click', function() {
            profileDropdown.classList.toggle('hidden');
        });

        // Sales Chart
        const hasData = <?= $has_data ? 'true' : 'false' ?>;
        
        if (hasData) {
            const options = {
                chart: {
                    type: 'bar', // Both overall and yearly use bar charts
                    height: 350,
                    fontFamily: "'Segoe UI', 'Helvetica', 'Arial', sans-serif",
                    toolbar: {
                        show: true,
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: [{
                    name: 'Sales Amount',
                    data: [
                        <?php 
                        $comma = "";
                        foreach ($sales_data['period_data'] as $data): 
                            echo $comma . number_format($data['sales'], 2, '.', '');
                            $comma = ", ";
                        endforeach; 
                        ?>
                    ]
                }],
                xaxis: {
                    categories: [
                        <?php 
                        $comma = "";
                        foreach ($sales_data['period_data'] as $data): 
                            echo $comma . "'" . $data['period'] . "'";
                            $comma = ", ";
                        endforeach; 
                        ?>
                    ],
                    labels: {
                        rotate: <?= ($period === 'overall') ? '-45' : '0' ?>,
                        style: {
                            fontSize: '<?= ($period === 'overall' && count($sales_data['period_data']) > 8) ? '10px' : '12px' ?>'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Amount (₱)'
                    },
                    labels: {
                        formatter: function(val) {
                            return '₱' + val.toLocaleString('en-PH');
                        }
                    }
                },
                colors: ['#2563EB'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '60%',
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: <?= ($period === 'overall' && count($sales_data['period_data']) > 12) ? 'false' : 'true' ?>,
                    formatter: function(val) {
                        return '₱' + parseFloat(val).toLocaleString('en-PH');
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                title: {
                    text: '<?= ucfirst($period) ?> Sales Overview',
                    align: 'left'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '₱' + parseFloat(val).toLocaleString('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 0 // Bar charts for both remaining views
                },
                markers: {
                    size: 0 // No markers for bar charts
                },
                // For overall view with many data points
                <?php if ($period === 'overall' && count($sales_data['period_data']) > 20): ?>
                chart: {
                    zoom: {
                        enabled: true
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 400
                        },
                        xaxis: {
                            labels: {
                                rotate: -90,
                                offsetY: 0
                            }
                        }
                    }
                }]
                <?php endif; ?>
            };

            const chart = new ApexCharts(document.querySelector("#sales-chart"), options);
            chart.render();
        } else {
            document.getElementById("sales-chart").innerHTML = 
                '<div class="flex items-center justify-center h-full">' +
                '<div class="text-center p-6">' +
                '<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />' +
                '</svg>' +
                '<h3 class="mt-2 text-sm font-medium text-gray-900">No sales data available</h3>' +
                '<p class="mt-1 text-sm text-gray-500">There are no completed reservations with sales data in the selected time period.</p>' +
                '</div>' +
                '</div>';
        }
    });
    </script>
</body>
</html>