<?php
include '../db_connection.php';

$folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;
if ($folder_id == 0) {
    die("Invalid folder ID.");
}

// Fetch folder details
$folderQuery = $conn->prepare("SELECT name FROM folders WHERE id = ?");
$folderQuery->bind_param("i", $folder_id);
$folderQuery->execute();
$folderResult = $folderQuery->get_result();
$folder = $folderResult->fetch_assoc();

if (!$folder) {
    die("Folder not found.");
}

// Fetch images
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
            <button onclick="toggleUploadModal()" class="px-3 py-1.5 text-sm font-medium bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                + Add Image
            </button>
        </header>

        <div id="info-alert" class="flex items-center p-3 mb-3 mt-5 text-sm text-blue-800 rounded-lg bg-blue-200 hidden">
          <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <div>
            <span class="font-medium" id="alert-title">Info alert!</span> 
            <span id="alert-message"></span>
          </div>
        </div>

<!-- Select All & Delete Buttons -->
<div id="selectControls" class="flex justify-between items-center mt-4 hidden">
    <div>
        <input type="checkbox" id="selectAll" class="w-5 h-5 cursor-pointer">
        <label for="selectAll" class="ml-2 text-gray-700">Select All</label>
    </div>
    <button id="deleteSelectedBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
        onclick="deleteSelectedImages()">Delete Selected</button>
</div>


<!-- Image Gallery -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php 
    $imageCount = 0; // Track number of images
    while ($image = $images->fetch_assoc()): 
        $imageCount++;
    ?>
        <div class="relative group bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 transition-transform transform hover:scale-105">
            
            <!-- Select Checkbox -->
            <input type="checkbox" class="absolute top-2 left-2 w-5 h-5 cursor-pointer select-image opacity-0 group-hover:opacity-100 transition"
                value="<?php echo $image['id']; ?>" data-path="<?php echo htmlspecialchars($image['image_path']); ?>"
                onchange="toggleDeleteButton()">

            <!-- Image Display -->
            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" class="w-full h-52 object-cover">
        </div>
    <?php endwhile; ?>

    <script>
        // Show "Select All" only if there are images
        document.addEventListener("DOMContentLoaded", function() {
            let imageCount = <?php echo $imageCount; ?>;
            if (imageCount > 0) {
                document.getElementById("selectControls").classList.remove("hidden");
            }
        });
    </script>
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
                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input class="w-full text-sm border border-gray-300 bg-gray-50 cursor-pointer focus:ring-2 focus:ring-blue-500 p-2 mb-3"
                    id="file_input" 
                    name="file" 
                    type="file" 
                    accept="image/png, image/jpeg" 
                    required 
                    onchange="previewImage(event)">

                <!-- Hidden input field for folder ID -->
                <input type="hidden" name="folder_id" id="folder_id_input" value="<?php echo $folder_id; ?>">

                <!-- Image Preview -->
                <div class="mt-2 hidden" id="imagePreviewContainer">
                    <img id="imagePreview" class="w-full h-40 object-cover rounded-md shadow">
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" onclick="toggleUploadModal()" class="px-4 py-2 mr-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Upload</button>
                </div>
            </form>
        </div>
    </div>
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
    <script>

document.addEventListener("DOMContentLoaded", function () {
    const folderId = new URLSearchParams(window.location.search).get("folder_id");
    console.log("Fetched folder ID:", folderId);

    if (folderId) {
        document.getElementById("folder_id_input").value = folderId;
    } else {
        console.error("No folder ID found in URL.");
    }

    // Show stored success message after reload
    let successMessage = localStorage.getItem("successMessage");
    if (successMessage) {
        showAlert("Success!", successMessage, "green");
        localStorage.removeItem("successMessage"); // Clear message after showing
    }
});

function toggleUploadModal() {
    document.getElementById("uploadModal").classList.toggle("hidden");
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

document.getElementById("uploadForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);
    let folderId = document.getElementById("folder_id_input").value;

    if (!folderId) {
        showAlert("Error!", "Folder ID is missing!", "red");
        return;
    }

    $.ajax({
        url: "upload_handler.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {
            console.log("Uploading...");
        },
        success: function (response) {
            try {
                let res = JSON.parse(response);
                if (res.success) {
                    toggleUploadModal(); // Close modal immediately
                    localStorage.setItem("successMessage", res.message); // Store message
                    location.reload(); // Reload the page
                } else {
                    showAlert("Error!", res.message, "red");
                }
            } catch (error) {
                console.error("JSON Error:", error, response);
                showAlert("Error!", "Unexpected server response.", "red");
            }
        },
        error: function (xhr) {
            console.error("AJAX Error:", xhr.responseText);
            showAlert("Error!", "Failed to upload image.", "red");
        }
    });
});

function showAlert(title, message, type = "blue") {
    let alertBox = document.getElementById("info-alert");
    let alertTitle = document.getElementById("alert-title");
    let alertMessage = document.getElementById("alert-message");

    alertTitle.innerText = title;
    alertMessage.innerText = message;

    alertBox.className = `flex items-center p-3 mb-3 mt-5 text-sm text-${type}-800 rounded-lg bg-${type}-200`;

    alertBox.classList.remove("hidden");

    setTimeout(() => {
        alertBox.classList.add("hidden");
    }, 3000);
}
</script>

<script>
 document.addEventListener("DOMContentLoaded", function () {
    let deleteBtn = document.getElementById("deleteSelectedBtn");
    let checkboxes = document.querySelectorAll(".select-image");
    let selectAllCheckbox = document.getElementById("selectAll");

    // Ensure delete button starts hidden
    if (deleteBtn) {
        deleteBtn.style.display = "none";
    }

    // Attach event listeners to all checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", toggleDeleteButton);
    });

    // Attach event listener to "Select All" checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function () {
            checkboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            toggleDeleteButton(); // Update delete button visibility
        });
    }
});

function toggleDeleteButton() {
    let selectedImages = document.querySelectorAll(".select-image:checked");
    let deleteBtn = document.getElementById("deleteSelectedBtn");

    if (deleteBtn) {
        deleteBtn.style.display = selectedImages.length > 0 ? "block" : "none"; // Show/hide button
    }
}

</script>

    <script>

function showAlert(title, message, type = "blue") {
    let alertBox = document.getElementById("info-alert");
    let alertTitle = document.getElementById("alert-title");
    let alertMessage = document.getElementById("alert-message");

    alertTitle.innerText = title;
    alertMessage.innerText = message;
    
    // Set background color based on type
    alertBox.className = `flex items-center p-3 mb-3 mt-5 text-sm text-${type}-800 rounded-lg bg-${type}-200`;

    alertBox.classList.remove("hidden"); // Show alert box

    // Auto-hide after 3 seconds
    setTimeout(() => {
        alertBox.classList.add("hidden");
    }, 3000);
}

function deleteSelectedImages() {
    let selectedImages = document.querySelectorAll(".select-image:checked");
    if (selectedImages.length === 0) {
        showAlert("Warning!", "No images selected for deletion.", "yellow");
        return;
    }

    let imageIds = [];
    let imagePaths = [];

    selectedImages.forEach(img => {
        imageIds.push(img.value);
        imagePaths.push(img.getAttribute("data-path"));
    });

    $.ajax({
        url: "delete_images.php",
        type: "POST",
        data: { image_ids: imageIds, image_paths: imagePaths },
        success: function (response) {
            try {
                let res = JSON.parse(response);
                if (res.success) {
                    // Store success message in localStorage before reload
                    localStorage.setItem("postReloadMessage", JSON.stringify({
                        title: "Success!",
                        message: "Images deleted successfully.",
                        type: "green"
                    }));

                    location.reload(); // Reload the page
                } else {
                    showAlert("Error!", res.message, "red");
                }
            } catch (error) {
                console.error("JSON Error:", error, response);
                showAlert("Error!", "Unexpected server response.", "red");
            }
        },
        error: function (xhr) {
            console.error("AJAX Error:", xhr.responseText);
            showAlert("Error!", "Failed to delete images.", "red");
        }
    });
}

// Show alert message after reload if stored
document.addEventListener("DOMContentLoaded", function () {
    let storedMessage = localStorage.getItem("postReloadMessage");

    if (storedMessage) {
        let { title, message, type } = JSON.parse(storedMessage);
        showAlert(title, message, type);

        // Remove the message from localStorage after displaying
        localStorage.removeItem("postReloadMessage");
    }
});


    </script>

    
</body>
</html>
