<?php
session_start();
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $title = $_POST['title'];
    $review_text = $_POST['review_text'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, title, review_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $rating, $title, $review_text);
    
    if ($stmt->execute()) {
        header("Location: main_page_logged.php?review=success");
        exit();
    } else {
        header("Location: main_page_logged.php?review=error");
        exit();
    }
}
?>