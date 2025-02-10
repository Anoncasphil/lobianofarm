<?php
session_start();

// Retrieve the incoming data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the data is received correctly
if (!$data['reservation_id'] || !$data['selected_rate_id'] || empty($data['addon_ids']) || empty($data['addon_names'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or incomplete data provided']);
    exit;
}

$reservation_id = $data['reservation_id'];
$selected_rate_id = $data['selected_rate_id'];
$addon_ids = $data['addon_ids'];
$addon_names = $data['addon_names'];

// Fetch rate details and addon details if needed
$rates = [];  // Assuming rates is an array of rate data
$addons = [];  // Assuming addons is an array of addon data

// Store the data in the session
$_SESSION['reservation_temp'] = [
    'reservation_id' => $reservation_id,
    'selected_rate_id' => $selected_rate_id,
    'addons' => array_map(function($id, $name) {
        return ['addon_id' => $id, 'addon_name' => $name];
    }, $addon_ids, $addon_names),
    'rates' => $rates
];

// Log session data to check if it's saved correctly
error_log(print_r($_SESSION['reservation_temp'], true));  // This will log session data to the error log

// Respond back to the client
echo json_encode([
    'status' => 'success',
    'message' => 'Reservation saved in session'
]);
?>
