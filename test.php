<?php
// Include database connection
include 'db_connection.php';

// Set the ID to 13 directly
$id = 13;

// SQL query to fetch the data based on the ID
$sql = "SELECT * FROM rates WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No record found!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Rate</title>
</head>
<body>
    <h2>Update Rate</h2>
    <form action="update_rate.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required><br><br>

        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" value="<?php echo $row['price']; ?>" required><br><br>

        <label for="hoursofstay">Hours of Stay:</label><br>
        <input type="number" id="hoursofstay" name="hoursofstay" value="<?php echo $row['hoursofstay']; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required><?php echo $row['description']; ?></textarea><br><br>

        <label for="picture">Upload New Image:</label><br>
        <input type="file" name="picture" id="picture"><br><br>

        <?php
        // Display current image if available
        if (!empty($row['picture'])) {
            echo "<img src='data:image/jpeg;base64," . base64_encode($row['picture']) . "' alt='Rate Image' width='100'><br><br>";
        }
        ?>

        <button type="submit">Update</button>
    </form>
</body>
</html>
