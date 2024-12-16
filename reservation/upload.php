<?php
session_start(); // Start the session to access and manage session variables
include("../db_connection.php"); // Include your database connection file

if (isset($_FILES['file_farm'])) {
    $file = $_FILES['file_farm'];

    $file_name = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    $fileError = $file['error'];

    $fileExt = explode('.', $file_name);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'svg');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // Adjust size limit if necessary
                // Generate a unique file name
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = '../src/uploads/payment_proof/' . $fileNameNew;

                // Move the uploaded file to the designated folder
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // If file is uploaded successfully, insert the uniqid into the database
                    $email = $_SESSION['email']; // Assuming the user's email is stored in session

                    // Prepare the SQL query to update the reservation table with the payment proof
                    $stmt = $conn->prepare("UPDATE reservation SET payment_proof = ? WHERE email = ?");
                    $stmt->bind_param("ss", $fileNameNew, $email);

                    if ($stmt->execute()) {
                        echo "<script>alert('File uploaded successfully and payment proof saved!'); window.location.href='send_payment.php';</script>";
                        exit();
                    } else {
                        echo "<script>alert('Failed to save payment proof in the database.'); window.location.href='send_payment.php';</script>";
                    }
                    $stmt->close();
                } else {
                    echo "<script>alert('Failed to move the uploaded file.'); window.location.href='send_payment.php';</script>";
                }
            } else {
                echo "<script>alert('Your file is too big!'); window.location.href='send_payment.php';</script>";
            }
        } else {
            echo "<script>alert('There was an error uploading your file!'); window.location.href='send_payment.php';</script>";
        }
    } else {
        echo "<script>alert('You cannot upload files of this type!'); window.location.href='send_payment.php';</script>";
    }
} else {
    echo "<script>alert('No file was uploaded.'); window.location.href='send_payment.php';</script>";
}
?>
