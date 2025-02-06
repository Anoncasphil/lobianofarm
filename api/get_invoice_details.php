<?php
  include '../db_connection.php';

  // Enable error reporting for debugging
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Check if reservation_id is valid
    if (empty($reservation_id) || !is_numeric($reservation_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Reservation ID.']);
        exit;
    }

    // Prepare SQL query to fetch reservation details
    $query = "SELECT * FROM reservations WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $reservation_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reservation = $result->fetch_assoc();

            // Fetch associated addons for the reservation
            $addonsQuery = "
                SELECT ra.addon_id, a.name AS addon_name, a.price AS addon_price, a.description AS addon_description, a.picture AS addon_picture
                FROM reservation_addons ra
                JOIN addons a ON ra.addon_id = a.id
                WHERE ra.reservation_id = ?";
            $addonsStmt = $conn->prepare($addonsQuery);
            if ($addonsStmt) {
                $addonsStmt->bind_param('i', $reservation_id);
                $addonsStmt->execute();
                $addonsResult = $addonsStmt->get_result();

                $addons = [];
                while ($addon = $addonsResult->fetch_assoc()) {
                    $addons[] = $addon;
                }

                // Fetch rates details for the reservation (assuming a reservation_id is associated with the rate in some way)
                $ratesQuery = "
                    SELECT r.id AS rate_id, r.name AS rate_name, r.price AS rate_price, r.description AS rate_description
                    FROM rates r
                    JOIN reservations res ON res.rate_id = r.id
                    WHERE res.id = ?";
                $ratesStmt = $conn->prepare($ratesQuery);
                if ($ratesStmt) {
                    $ratesStmt->bind_param('i', $reservation_id);
                    $ratesStmt->execute();
                    $ratesResult = $ratesStmt->get_result();

                    $rates = [];
                    while ($rate = $ratesResult->fetch_assoc()) {
                        $rates[] = $rate;
                    }

                    // Return data as JSON with rates and addons included, along with reservation_id
                    echo json_encode([
                
                        'reservation_id' => $reservation_id, // Include reservation ID in the response
                        'invoice_date' => $reservation['invoice_date'],
                        'invoice_number' => $reservation['invoice_number'],
                        'total_price' => $reservation['total_price'],
                        'payment_receipt' => $reservation['payment_receipt'],
                        'status' => $reservation['status'],
                        'payment_status' => $reservation['payment_status'],
                        'contact_number' => $reservation['contact_number'],
                        'first_name' => $reservation['first_name'],
                        'last_name' => $reservation['last_name'],
                        'email' => $reservation['email'],
                        'mobile_number' => $reservation['mobile_number'],
                        'checkin_date' => $reservation['check_in_date'],  // Include check-in date
                        'checkout_date' => $reservation['check_out_date'], // Include check-out date
                        'checkin_time' => $reservation['check_in_time'],   // Include check-in time
                        'checkout_time' => $reservation['check_out_time'], // Include check-out time
                        'addons' => $addons,
                        'rates' => $rates
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch rates. Error: ' . $conn->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch addons. Error: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Reservation not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare reservation query. Error: ' . $conn->error]);
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Reservation ID not provided.']);
  }

  $conn->close();
?>
