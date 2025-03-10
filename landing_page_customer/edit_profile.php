<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Make sure no further code is executed
}

// Include the database connection
include('../db_connection.php'); // Adjust the path if necessary

// Initialize variables
$first_name = $last_name = $email = $phone_number = $password = $confirm_password = "";
$first_name_err = $last_name_err = $email_err = $phone_number_err = $password_err = $confirm_password_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter your phone number.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter a valid phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate password
    if (!empty(trim($_POST["password"]))) {
        if (strlen(trim($_POST["password"])) < 6) {
            $password_err = "Password must be at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm your password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }
    }

    // Check for errors before updating the database
    if (empty($first_name_err) && empty($last_name_err) && empty($phone_number_err) && empty($password_err) && empty($confirm_password_err)) {
        if (!empty($password)) {
            // Prepare an update statement with password
            $sql = "UPDATE user_tbl SET first_name = ?, last_name = ?, phone_number = ?, password = ? WHERE user_id = ?";
        } else {
            // Prepare an update statement without password
            $sql = "UPDATE user_tbl SET first_name = ?, last_name = ?, phone_number = ? WHERE user_id = ?";
        }

        if ($stmt = $conn->prepare($sql)) {
            if (!empty($password)) {
                // Bind variables to the prepared statement as parameters with password
                $stmt->bind_param("ssssi", $param_first_name, $param_last_name, $param_phone_number, $param_password, $param_user_id);
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Create a password hash
            } else {
                // Bind variables to the prepared statement as parameters without password
                $stmt->bind_param("sssi", $param_first_name, $param_last_name, $param_phone_number, $param_user_id);
            }

            // Set parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_phone_number = $phone_number;
            $param_user_id = $_SESSION['user_id'];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Update successful, redirect to homepage
                header("Location: ../index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
} else {
    // Fetch user data from the database
    $sql = "SELECT first_name, last_name, email, phone_number FROM user_tbl WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_user_id);

        // Set parameters
        $param_user_id = $_SESSION['user_id'];

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if user exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($first_name, $last_name, $email, $phone_number);
                $stmt->fetch();
            } else {
                // User doesn't exist, redirect to login page
                header("Location: login.php");
                exit();
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Edit Profile</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="first_name" class="block text-gray-700 font-semibold">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 <?php echo (!empty($first_name_err)) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($first_name); ?>">
                    <span class="text-red-500 text-sm"><?php echo $first_name_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-gray-700 font-semibold">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 <?php echo (!empty($last_name_err)) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($last_name); ?>">
                    <span class="text-red-500 text-sm"><?php echo $last_name_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600 <?php echo (!empty($email_err)) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    <span class="text-red-500 text-sm"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 font-semibold">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 <?php echo (!empty($phone_number_err)) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($phone_number); ?>">
                    <span class="text-red-500 text-sm"><?php echo $phone_number_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 <?php echo (!empty($password_err)) ? 'border-red-500' : ''; ?>">
                    <span class="text-red-500 text-sm"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700 font-semibold">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 <?php echo (!empty($confirm_password_err)) ? 'border-red-500' : ''; ?>">
                    <span class="text-red-500 text-sm"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="window.history.back();" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600">Back</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
