<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== '' && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user_tbl SET first_name=?, last_name=?, email=?, contact_no=?, password=? WHERE user_id=?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $mobile, $hashed_password, $_SESSION['user_id']);
    } else {
        $stmt = $conn->prepare("UPDATE user_tbl SET first_name=?, last_name=?, email=?, contact_no=? WHERE user_id=?");
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $mobile, $_SESSION['user_id']);
    }

    if ($stmt->execute()) {
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['user_email'] = $email;
        header("Location: profile.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>
            
            <?php if(isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    Profile updated successfully!
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">First Name</label>
                        <input type="text" name="first_name" value="<?php echo $_SESSION['first_name']; ?>" 
                               class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block mb-2">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo $_SESSION['last_name']; ?>" 
                               class="w-full p-2 border rounded" required>
                    </div>
                </div>

                <div>
                    <label class="block mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" 
                           class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label class="block mb-2">Mobile Number</label>
                    <input type="text" name="mobile" value="<?php echo $_SESSION['contact_no'] ?? ''; ?>" 
                           class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label class="block mb-2">New Password (leave blank to keep current)</label>
                    <input type="password" name="new_password" class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="w-full p-2 border rounded">
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="main_page_logged.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>