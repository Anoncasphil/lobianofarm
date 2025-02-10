<?php
require_once('../db_connection.php');

// Get reservation_id from the request
$reservation_id = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : null;

// If no reservation ID is provided, return an error
if ($reservation_id === null) {
    echo json_encode(['status' => 'error', 'message' => 'Reservation ID is required.']);
    exit;
}

// Prepare the query to fetch the rates and addons
$queryRates = "SELECT id AS rate_id, name AS rate_name FROM rates WHERE status = 'active'";
$queryAddons = "SELECT id AS addon_id, name AS addon_name FROM addons WHERE status = 'active'";

// Execute the queries
$ratesResult = $conn->query($queryRates);
$addonsResult = $conn->query($queryAddons);

// Prepare the response data
$response = [
    'rates' => [],
    'addons' => []
];

// Fetch the rates
if ($ratesResult) {
    while ($rate = $ratesResult->fetch_assoc()) {
        $response['rates'][] = $rate;
    }
}

// Fetch the addons
if ($addonsResult) {
    while ($addon = $addonsResult->fetch_assoc()) {
        $response['addons'][] = $addon;
    }
}

// Send the response as JSON
echo json_encode($response);
?>
