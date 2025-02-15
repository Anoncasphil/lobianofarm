<?php
require '../db_connection.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user_tbl WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['exists' => true, 'message' => 'Email is already in use.']);
    } else {
        echo json_encode(['exists' => false, 'message' => 'Email is available.']);
    }
} else {
    echo json_encode(['exists' => false, 'message' => 'No email provided.']);
}
?>
