function toggleUploadModal() {
    document.getElementById('uploadModal').classList.toggle('hidden');
}

function toggleDeleteButton() {
    let checkboxes = document.querySelectorAll('.select-image:checked');
    let deleteBtn = document.getElementById('deleteSelectedBtn');
    deleteBtn.classList.toggle('hidden', checkboxes.length === 0);
}

function deleteSelectedImages() {
    let selectedImages = [];
    document.querySelectorAll('.select-image:checked').forEach(checkbox => {
        selectedImages.push(checkbox.value);
    });

    if (selectedImages.length === 0) {
        alert("No images selected.");
        return;
    }

    if (!confirm("Are you sure you want to delete the selected images?")) {
        return;
    }

    $.ajax({
        type: "POST",
        url: "delete_images.php",
        data: { image_ids: selectedImages },
        success: function (response) {
            let res = JSON.parse(response);
            if (res.success) {
                location.reload();
            } else {
                alert("Delete failed: " + res.message);
            }
        },
        error: function () {
            alert("Error deleting images.");
        }
    });
}

$(document).ready(function () {
    // Fetch the folder ID from localStorage
    const folderId = localStorage.getItem('selectedFolderId');
    
    // Check if folderId exists, else show an error
    if (!folderId) {
        alert("Folder ID is missing. Please select a folder first.");
        window.location.href = "your-folder-selection-page.php"; // Redirect to folder selection page
        return;
    }

    // Handle form submission for image upload
    $('#uploadForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting traditionally

        // Create FormData to append the file and other fields
        let formData = new FormData();
        formData.append('file', $('#file_input')[0].files[0]); // Append the selected file
        formData.append('folder_id', folderId); // Append folder_id from localStorage
        formData.append('name', $('#imageName').val()); // Append image name
        formData.append('description', $('#imageDescription').val()); // Append image description

        // Send the data via AJAX
        $.ajax({
            url: 'upload_handler.php', // Your PHP script to handle the upload
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                let res = JSON.parse(response);
                if (res.success) {
                    alert(res.message);
                    // You can redirect to another page or update the UI as needed
                } else {
                    alert("Upload failed: " + res.message);
                }
            },
            error: function () {
                alert("Error uploading image.");
            }
        });
    });

    // Image preview when user selects a file
    $('#file_input').change(function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
});


    
    