// Utility function to show a modal with a message
function showModal(modalId, message) {
    const modal = document.getElementById(modalId);
    const modalMessage = modal.querySelector('.modal-message');
    modalMessage.textContent = message;
    modal.classList.remove('hidden');
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
}

// Rate Modals
function toggleAddRateModal() {
    const modal = document.getElementById('add-rate-modal');
    modal.classList.toggle('hidden');
}

function hideRateModal() {
    const modal = document.getElementById('add-rate-modal');
    modal.classList.add('hidden');
}

// Update Rate Modal
function toggleUpdateRateModal() {
    const modal = document.getElementById('update-rate-modal');
    modal.classList.toggle('hidden'); // Show or hide the modal
}

function hideUpdateRateModal() {
    const modal = document.getElementById('update-rate-modal');
    modal.classList.add('hidden'); // Hide the modal
}

// Display Add Rate Picture Preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            imagePreview.src = e.target.result;
            imagePreview.style.width = '100%';
            imagePreview.style.height = '100px';
            imagePreview.className = 'object-cover rounded-lg img-zoom-out';
            imagePreviewContainer.style.display = 'block'; // Show the image preview
        };
        reader.readAsDataURL(file);
    }
}

// Fetch Rate Data for Updating
function fetchRateData(id) {
    fetch(`../rates/fetch-rate.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        console.log('Data received:', data);

        // Populate fields with fetched data
        document.getElementById('updatename').value = data.name;
        document.getElementById('updateprice').value = data.price;
        document.getElementById('updatedescription').value = data.description;
        document.getElementById('updatehoursofstay').value = data.hoursofstay;

        const updateRateIdElement = document.getElementById('updateRateId');
        if (updateRateIdElement) {
            updateRateIdElement.value = id;
        } else {
            console.error('Element with id "updateRateId" not found');
        }

        // Clear existing image previews
        const imagePreviewContainerDb = document.getElementById('image-preview-from-db');
        const imagePreviewContainerNew = document.getElementById('image-preview-new');
        imagePreviewContainerDb.innerHTML = ''; // Clear previous image
        imagePreviewContainerNew.innerHTML = ''; // Clear new image preview container

        if (data.picture) {
            const imgElementDb = document.createElement('img');
            imgElementDb.src = data.picture;
            imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
            imgElementDb.alt = "Rate Image from DB";
            imgElementDb.style.height = '100px';
            imagePreviewContainerDb.appendChild(imgElementDb);
        }
    })
    .catch(error => console.error('Error fetching rate data:', error));
}

// Handle new file upload for update rate
document.getElementById('updatefile_input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreviewContainerNew = document.getElementById('image-preview-new');
            const imagePreviewContainerDb = document.getElementById('image-preview-from-db');

            imagePreviewContainerDb.classList.add('hidden');
            imagePreviewContainerNew.innerHTML = ''; // Clear previous new image

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

// Toggle Add Add-on Modal
function toggleAddAddonModal() {
    const modal = document.getElementById('add-addon-modal');
    modal.classList.toggle('hidden');
}

function hideAddonModal() {
    const modal = document.getElementById('add-addon-modal');
    modal.classList.add('hidden');
}

// Toggle Update Add-on Modal
function toggleUpdateAddonModal() {
    const modal = document.getElementById('update-addon-modal');
    modal.classList.toggle('hidden');
}

function hideUpdateAddonModal() {
    const modal = document.getElementById('update-addon-modal');
    modal.classList.add('hidden');
}

// Display Add Add-on Picture Preview
function previewAddonImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('addonImagePreview');
            const imagePreviewContainer = document.getElementById('addonImagePreviewContainer');

            imagePreview.src = e.target.result;
            imagePreview.style.width = '100%';
            imagePreview.style.height = '100px';
            imagePreview.className = 'object-cover rounded-lg img-zoom-out';

            imagePreviewContainer.style.display = 'block'; // Show the image preview
        };
        reader.readAsDataURL(file);
    }
}

// Fetch Add-on Data for Updating
function fetchAddonData(id) {
    fetch(`fetch-addon.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        console.log('Data received:', data);

        document.getElementById('updateAddonName').value = data.name;
        document.getElementById('updateAddonPrice').value = data.price;
        document.getElementById('updateAddonDescription').value = data.description;

        const updateAddonIdElement = document.getElementById('updateAddonId');
        if (updateAddonIdElement) {
            updateAddonIdElement.value = id;
        } else {
            console.error('Element with id "updateAddonId" not found');
        }

        // Clear previous image previews
        const imagePreviewContainerDb = document.getElementById('addon-image-preview-from-db');
        const imagePreviewContainerNew = document.getElementById('addon-image-preview-new');
        imagePreviewContainerDb.innerHTML = ''; // Clear previous image
        imagePreviewContainerNew.innerHTML = ''; // Clear new image preview container

        if (data.picture) {
            const imgElementDb = document.createElement('img');
            imgElementDb.src = data.picture;
            imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
            imgElementDb.alt = "Add-on Image from DB";
            imgElementDb.style.height = '100px';
            imagePreviewContainerDb.appendChild(imgElementDb);
        }
    })
    .catch(error => console.error('Error fetching add-on data:', error));
}

// Handle new file upload for update add-on
document.getElementById('updateAddonFileInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreviewContainerNew = document.getElementById('addon-image-preview-new');
            const imagePreviewContainerDb = document.getElementById('addon-image-preview-from-db');

            imagePreviewContainerDb.classList.add('hidden');
            imagePreviewContainerNew.innerHTML = ''; // Clear previous new image

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

function archiveConfirmation(rateId) {
    // Show the modal and attach the rate ID to the confirm button
    const modal = document.getElementById('archiveModal');
    modal.classList.remove('hidden');
    const confirmButton = document.getElementById('confirmArchive');
    confirmButton.onclick = function () {
        archiveRate(rateId);
        closeModal();
    };
}

function archiveRate(rateId) {
    // Create a request to archive the rate
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "archive-rate.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
        console.log("Server Response: ", xhr.responseText);
        if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
            const row = document.getElementById('rate-' + rateId);
            if (row) {
                row.style.display = 'none';
            }
            showModal('successModal', 'The rate has been successfully archived.');
        } else {
            showModal('errorModal', 'Failed to archive the rate. Please try again.');
        }
    }
};


    // Send the rate ID to the server to mark it as inactive
    xhr.send("id=" + rateId);
}

function closeModal() {
    // Hide the modal
    const modal = document.getElementById('archiveModal');
    modal.classList.add('hidden');
}

function showModal(modalId, message) {
    console.log(`Showing modal: ${modalId} with message: ${message}`);
    const modal = document.getElementById(modalId);
    if (modal) {
        const messageContainer = modal.querySelector('.modal-message');
        if (messageContainer) {
            messageContainer.textContent = message;
        }
        modal.classList.remove('hidden');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 3000);
    }
}

