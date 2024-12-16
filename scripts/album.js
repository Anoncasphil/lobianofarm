function toggleAddAlbumModal() {
    const modal = document.getElementById('add-picture-modal');
    modal.classList.toggle('hidden');
}

// Function to hide modal
function hideAddPictureModal() {
    document.getElementById('add-picture-modal').classList.add('hidden');
}

// Function to preview the image before upload
function previewImage(event) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');
    const file = event.target.files[0];
    if (file) {
        previewContainer.style.display = 'block';
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}

function toggleDeleteMode() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.style.display = checkbox.style.display === 'none' ? 'inline-block' : 'none';
    });
}

function deleteSelectedPictures() {
    const selectedPictures = [];
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');

    checkboxes.forEach(checkbox => {
        selectedPictures.push(checkbox.getAttribute('data-id'));
    });

    if (selectedPictures.length > 0) {
        // Send AJAX request to delete the selected pictures
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_pictures.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Pictures deleted successfully');
                location.reload(); // Refresh the page
            } else {
                alert('Error deleting pictures');
            }
        };
        xhr.send('ids=' + selectedPictures.join(','));
    } else {
        alert('No pictures selected for deletion.');
    }
}

