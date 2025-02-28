<?php
include '../db_connection.php';

// Get filter value (0 = Active, 1 = Archived)
$filter = isset($_GET['filter']) ? intval($_GET['filter']) : 0;

// Fetch folders based on filter
$sql = "SELECT * FROM folders WHERE archived = $filter ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 h-screen p-6">

    <!-- Main Container -->
    <div class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg mx-auto mt-6">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Album Management</h1>
            <button onclick="toggleAddFolderModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Add Folder
            </button>
        </header>

        <!-- Folder Toggle Tabs -->
        <div class="flex space-x-4 mb-4">
            <button onclick="filterFolders(0)" class="px-4 py-2 rounded-lg text-sm font-medium <?= $filter == 0 ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">
                Active Folders
            </button>
            <button onclick="filterFolders(1)" class="px-4 py-2 rounded-lg text-sm font-medium <?= $filter == 1 ? 'bg-red-600 text-white' : 'bg-gray-200' ?>">
                Archived Folders
            </button>
        </div>

        <!-- Folder List -->
        <div id="folderList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="folder-item bg-gray-100 p-4 rounded-lg flex justify-between items-center cursor-pointer transition hover:bg-gray-200"
                     onclick="redirectToUpload(<?php echo $row['id']; ?>)">
                    <div>
                        <h2 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                    <div>
                        <button onclick="openArchiveModal(event, <?php echo $row['id']; ?>);" class="text-gray-500 hover:text-red-600">
                            <span class="material-icons">delete</span>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Add Folder Modal -->
    <div id="addFolderModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Add Folder</h2>
            <form id="addFolderForm">
                <label class="block text-sm font-medium">Folder Name</label>
                <input type="text" name="name" id="folderName" required class="w-full p-2 border rounded-lg mb-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" id="folderDescription" class="w-full p-2 border rounded-lg mb-4"></textarea>
                <div class="flex justify-end">
                    <button type="button" onclick="toggleAddFolderModal()" class="px-4 py-2 text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-800">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-800">Are you sure you want to archive this folder?</h2>
            <div class="mt-4 flex justify-end space-x-4">
                <button class="text-gray-700 bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300" onclick="closeModal()">No</button>
                <button id="confirmArchive" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700" onclick="archiveFolder();">
                    Yes
                </button>
            </div>
        </div>
    </div>

    <!-- External Script -->
    <script src="file_manager.js" defer></script>

    <script>
        function filterFolders(filter) {
    window.location.href = '?filter=' + filter;
}

    </script>

</body>
</html>
