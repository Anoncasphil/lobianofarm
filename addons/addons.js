// Toggle Add Add-on Modal
function toggleAddAddonModal() {
    const modal = document.getElementById('add-addon-modal');
    modal.classList.toggle('hidden');
}

function hideAddAddonModal() {
    const modal = document.getElementById('add-addon-modal');
    modal.classList.add('hidden');
}

function toggleUpdateAddonModal() {
    const modal = document.getElementById('update-addon-modal');
    modal.classList.toggle('hidden'); // Show or hide the modal
}

function hideUpdateAddonModal() {
    const modal = document.getElementById('update-addon-modal');
    modal.classList.add('hidden'); // Hide the modal
}



function previewAddonImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('addonImagePreview');
            const imagePreviewContainer = document.getElementById('addonImagePreviewContainer');

            // Set the source of the image
            imagePreview.src = e.target.result;

            imagePreview.style.width = '100%';
            imagePreview.style.height = '100px'; 
            imagePreview.className = 'object-cover rounded-lg img-zoom-out';

            imagePreviewContainer.style.display = 'block'; 
        };
        reader.readAsDataURL(file);
    }
}

function fetchAddonData(id) {
    fetch(`../addons/fetch-addon.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            console.log('Data received:', data); // Debugging

            // Populate the modal fields with fetched data
            document.getElementById('updateAddonName').value = data.name;
            document.getElementById('updateAddonPrice').value = data.price;
            document.getElementById('updateAddonDescription').value = data.description;

            // Set the hidden input field for the addon ID
            const updateAddonIdElement = document.getElementById('updateAddonId');
            if (!updateAddonIdElement) {
                console.error('Element with id "updateAddonId" not found');
            } else {
                updateAddonIdElement.value = id;
            }

            // Manage image previews
            const imagePreviewContainerDb = document.getElementById('addon-image-preview-from-db');
            const imagePreviewContainerNew = document.getElementById('addon-image-preview-new');

            // Hide the new image preview initially
            imagePreviewContainerNew.classList.add('hidden');

            // Clear both containers before appending new images
            imagePreviewContainerDb.innerHTML = '';
            imagePreviewContainerNew.innerHTML = '';

            // If an image exists in the database, display it
            if (data.picture) {
                const imgElementDb = document.createElement('img');
                imgElementDb.src = data.picture; // Use the base64-encoded image URL
                imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
                imgElementDb.alt = "Addon Image from DB";
                imgElementDb.style.height = '100px';
                imagePreviewContainerDb.appendChild(imgElementDb);
            }
        })
        .catch(error => console.error('Error fetching addon data:', error));

    // Handle new file uploads
    document.getElementById('updateAddonFileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreviewContainerNew = document.getElementById('addon-image-preview-new');
                const imagePreviewContainerDb = document.getElementById('addon-image-preview-from-db');

                // Hide the image from the database
                imagePreviewContainerDb.classList.add('hidden');

                // Clear the new image preview container
                imagePreviewContainerNew.innerHTML = '';

                // Create and show the new uploaded image preview
                const newImage = document.createElement('img');
                newImage.src = e.target.result;
                newImage.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
                newImage.style.height = '100px';

                imagePreviewContainerNew.classList.remove('hidden');
                imagePreviewContainerNew.appendChild(newImage);
            };
            reader.readAsDataURL(file);
        }
    });
}


// Archive Add-on
function archiveAddon(addonId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "archive-addon.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText === 'success') {
                var row = document.getElementById('addon-' + addonId);
                row.style.display = 'none';
                showModal('successModal', 'The add-on has been successfully archived.');
            } else {
                showModal('errorModal', 'Failed to archive the add-on. Please try again.');
            }
        }
    };

    xhr.send("id=" + addonId);
}
