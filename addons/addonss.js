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

function showDetails(id) {
    // Make an AJAX request to fetch the details
    fetch(`fetch-details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error); // Handle error
            } else {
                // Populate the modal with the data
                document.getElementById('modalTitle').textContent = data.name;
                document.getElementById('modalPrice').textContent = `$${data.price}`;
                document.getElementById('modalDescription').textContent = data.description;

                // If there is an image, display it; otherwise, keep it hidden
                if (data.picture) {
                    document.getElementById('modalPicture').src = data.picture;
                    document.getElementById('modalPicture').style.display = 'block';
                } else {
                    document.getElementById('modalPicture').style.display = 'none';
                }

                // Show the modal
                document.getElementById('detailsModal').classList.remove('hidden');
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeModal() {
    // Hide the modal when closed
    document.getElementById('detailsModal').classList.add('hidden');
}

function archiveConfirmation(addonId) {
    // Show the modal and attach the addon ID to the confirm button
    const modal = document.getElementById('archiveModal');
    modal.classList.remove('hidden');
    const confirmButton = document.getElementById('confirmArchive');
    confirmButton.onclick = function () {
        archiveAddon(addonId);
        closeModal();
    };
}

function archiveAddon(addonId) {
    // Create a request to archive the addon
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "archive-addon.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Server Response: ", xhr.responseText);
            if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
                const row = document.getElementById('addon-' + addonId);
                if (row) {
                    row.style.display = 'none';
                }
                showModal('successModal', 'The addon has been successfully archived.');
            } else {
                showModal('errorModal', 'Failed to archive the addon. Please try again.');
            }
        }
    };

    // Send the addon ID to the server to mark it as archived
    xhr.send("id=" + addonId);
}

function closeModal() {
    // Hide the modal
    const modal = document.getElementById('archiveModal');
    modal.classList.add('hidden');
}

function showModal(modalId, message) {
    console.log(`Showing modal: ${modalId} with message: ${message}`);
    
    // Find the modal by ID
    const modal = document.getElementById(modalId);
    
    // Make sure the modal exists and is not already visible
    if (modal) {
        const messageContainer = modal.querySelector('.modal-message');
        
        // Check if the modal message container exists and set the message
        if (messageContainer) {
            messageContainer.textContent = message;
        }

        // Remove the 'hidden' class to show the modal
        modal.classList.remove('hidden');
        
        // Automatically hide the modal after 3 seconds
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 3000);
    } else {
        console.log(`Modal with ID ${modalId} not found.`);
    }
}

function closeDetailsModal() {
    // Hide the modal
    const modal = document.getElementById('detailsModal');
    modal.classList.add('hidden');
}

function closeModal() {
    // Hide the modal
    const modal = document.getElementById('archiveModal');
    modal.classList.add('hidden');
}

 // Function to toggle between active and inactive add-ons table visibility
 function toggleAddonsTableVisibility() {
        var activeAddonsTable = document.getElementById("activeAddonsTable");
        var inactiveAddonsTable = document.getElementById("inactiveAddonsTable");
        var toggleButton = document.getElementById("toggleAddonsButton");
        
        if (activeAddonsTable.style.display === "none") {
            activeAddonsTable.style.display = "block";
            inactiveAddonsTable.style.display = "none";
            toggleButton.innerHTML = '<i class="fa-solid fa-toggle-off"></i> Show Inactive Add-ons'; // Change button text
        } else {
            activeAddonsTable.style.display = "none";
            inactiveAddonsTable.style.display = "block";
            toggleButton.innerHTML = '<i class="fa-solid fa-toggle-on"></i> Show Active Add-ons'; // Change button text
        }
    }

    // Function to restore an addon
function restoreAddon(addonId) {
    // Send the request to restore the addon
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "restore_addon.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Reload the page immediately after the request succeeds
            location.reload();
        } else {
            console.error("Request failed with status " + xhr.status);
        }
    };
    xhr.send("addon_id=" + addonId);
}