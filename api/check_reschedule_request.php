<?php
include 'db_connection.php';

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    try {
        $stmt = $pdo->prepare('SELECT status FROM reschedules WHERE reservation_id = :reservation_id ORDER BY request_date DESC LIMIT 1');
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->execute();
        $reschedule = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reschedule) {
            echo json_encode([
                'status' => 'success',
                'reschedule_status' => $reschedule['status']
            ]);
        } else {
            echo json_encode([
                'status' => 'no_request',
                'reschedule_status' => ''
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Reservation ID not provided']);
}
?>
