<?php
include '../db_connection.php';

$folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;
if ($folder_id == 0) {
    die("Invalid folder ID.");
}

$folderQuery = $conn->prepare("SELECT name FROM folders WHERE id = ?");
$folderQuery->bind_param("i", $folder_id);
$folderQuery->execute();
$folderResult = $folderQuery->get_result();
$folder = $folderResult->fetch_assoc();

if (!$folder) {
    die("Folder not found.");
}

$imageQuery = $conn->prepare("SELECT * FROM images WHERE folder_id = ? ORDER BY uploaded_at DESC");
$imageQuery->bind_param("i", $folder_id);
$imageQuery->execute();
$images = $imageQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Images - <?php echo htmlspecialchars($folder['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50 p-8 flex flex-col items-center">
    <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-md">
        <!-- Header -->
        <header class="flex justify-between items-center border-b pb-4">
            <h1 class="text-xl font-semibold text-gray-800">
                Upload Images to "<?php echo htmlspecialchars($folder['name']); ?>"
            </h1>
            <div class="flex items-center space-x-2">
                <button id="deleteSelectedBtn" onclick="deleteSelectedImages()" class="hidden px-3 py-1.5 text-sm font-medium bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    Delete Selected
                </button>
                <button onclick="toggleUploadModal()" class="px-3 py-1.5 text-sm font-medium bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    + Add Image
                </button>
            </div>
        </header>

        <!-- Image Gallery -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <?php while ($image = $images->fetch_assoc()): ?>
                <div class="relative bg-white p-3 rounded-lg shadow-sm border hover:shadow-md transition">
                    <!-- Checkbox -->
                    <input type="checkbox" class="absolute top-2 right-2 w-5 h-5 cursor-pointer select-image" value="<?php echo $image['id']; ?>" onchange="toggleDeleteButton()">
                    
                    <img src="<?php echo '../' . htmlspecialchars($image['image_path']); ?>" class="w-full h-40 object-cover rounded-md">
                    <h3 class="text-sm font-medium mt-2 text-gray-900"><?php echo htmlspecialchars($image['name']); ?></h3>
                    <p class="text-xs text-gray-600 mt-1"><?php echo htmlspecialchars($image['description']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center border-b pb-3">
                <h2 class="text-lg font-semibold text-gray-800">Add Image</h2>
                <button onclick="toggleUploadModal()" class="text-gray-500 hover:text-gray-900 transition">✖</button>
            </div>

          <!-- Upload Form -->
          <form id="uploadForm" class="mt-4" enctype="multipart/form-data">
    <label class="block text-sm font-medium text-gray-700">Image Name</label>
    <input type="text" name="name" id="imageName" required class="w-full p-2 border border-gray-300 bg-gray-50 rounded-md focus:ring-2 focus:ring-blue-500 mb-3">

    <label class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" id="imageDescription" class="w-full p-2 border border-gray-300 bg-gray-50 rounded-md focus:ring-2 focus:ring-blue-500 mb-3"></textarea>

    <label class="block text-sm font-medium text-gray-700">Upload Image</label>
    <input class="w-full text-sm border border-gray-300 bg-gray-50 cursor-pointer focus:ring-2 focus:ring-blue-500 p-2 mb-3"
        id="file_input" 
        name="file" 
        type="file" 
        accept="image/png, image/jpeg" 
        required 
        onchange="previewImage(event)">

    <!-- Hidden input field for folder ID -->
    <input type="hidden" name="folder_id" id="folder_id_input">

    <!-- Hidden input field for another folder ID if needed (assuming you need more than one) -->
    <input type="hidden" name="another_folder_id" id="another_folder_id_input">

    <!-- Image Preview -->
    <div class="mt-2 hidden" id="imagePreviewContainer">
        <img id="imagePreview" class="w-full h-40 object-cover rounded-md shadow">
    </div>

    <div class="flex justify-end mt-4">
        <button type="button" onclick="toggleUploadModal()" class="px-4 py-2 mr-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Upload</button>
    </div>
</form>

<script>
    // This script sets the folder_id in the hidden input when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const folderId = localStorage.getItem('selectedFolderId');
        console.log("Fetched folder ID:", folderId); // Debugging log
        
        // Set the folder ID in the hidden input field
        if (folderId) {
            document.getElementById('folder_id_input').value = folderId;
        } else {
            console.log("No folder ID found in localStorage.");
        }

        // If you have another folder id to set
        const anotherFolderId = localStorage.getItem('anotherFolderId');
        if (anotherFolderId) {
            document.getElementById('another_folder_id_input').value = anotherFolderId;
        }
    });
</script>

        </div>
    </div>

    <script>
        function toggleUploadModal() {
            document.getElementById('uploadModal').classList.toggle('hidden');
        }

        function previewImage(event) {
            const imagePreview = document.getElementById("imagePreview");
            const container = document.getElementById("imagePreviewContainer");

            if (event.target.files.length > 0) {
                let src = URL.createObjectURL(event.target.files[0]);
                imagePreview.src = src;
                container.classList.remove("hidden");
            }
        }
    </script>
</body>
</html>
