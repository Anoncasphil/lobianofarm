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

// display add rate picture preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            
            // Set the source of the image
            imagePreview.src = e.target.result;
            
            imagePreview.style.width = '100%';
            imagePreview.style.height = '100px';  // Force height via inline style   // Ensure width remains proportional
            imagePreview.className = 'object-cover rounded-lg img-zoom-out'; // Retain other classes
            
            imagePreviewContainer.style.display = 'block'; // Show the image preview
        };
        reader.readAsDataURL(file);
    }
}

// retrieve data from the databse to display in updating rates and also for including picture preview
// retrieve data from the database to display in updating rates and also for including picture preview
function fetchRateData(id) {
    fetch(`../rates/fetch-rate.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        console.log('Data received:', data); // Log the data to see if it's correct

        // Assuming you have elements with the following ids:
        document.getElementById('updatename').value = data.name;
        document.getElementById('updateprice').value = data.price;
        document.getElementById('updatedescription').value = data.description;
        document.getElementById('updatehoursofstay').value = data.hoursofstay;

        // Get the hidden input element for the rate ID and check if it's available
        const updateRateIdElement = document.getElementById('updateRateId');
        if (!updateRateIdElement) {
            console.error('Element with id "updateRateId" not found');
        } else {
            // Set the hidden input field value with the rate ID
            updateRateIdElement.value = id;
        }

        // Get the preview containers for both database image and uploaded image
        const imagePreviewContainerDb = document.getElementById('image-preview-from-db');
        const imagePreviewContainerNew = document.getElementById('image-preview-new');

        // Hide the new image preview container initially
        imagePreviewContainerNew.classList.add('hidden');
        
        // Clear both containers before appending new images
        imagePreviewContainerDb.innerHTML = '';
        imagePreviewContainerNew.innerHTML = '';

        // If a picture exists in the database, show it
        if (data.picture) {
            const imgElementDb = document.createElement('img');
            imgElementDb.src = data.picture; // The base64-encoded image data
            imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
            imgElementDb.alt = "Rate Image from DB";
            imgElementDb.style.height = '100px';
            imagePreviewContainerDb.appendChild(imgElementDb); // Append the image to the container
        }
    })
    .catch(error => console.error('Error fetching rate data:', error));

    // Handle new file upload (on file input change)
    document.getElementById('updatefile_input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Get the preview container for new image
                const imagePreviewContainerNew = document.getElementById('image-preview-new');
                const imagePreviewContainerDb = document.getElementById('image-preview-from-db');

                // Hide the image from the database
                imagePreviewContainerDb.classList.add('hidden');
                
                // Clear the new image preview container before appending the new image
                imagePreviewContainerNew.innerHTML = '';

                // Create a new image element for the uploaded file
                const newImage = document.createElement('img');
                newImage.src = e.target.result; // Set the source to the uploaded image
                newImage.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
                newImage.style.height = '100px'; // Optional, set the desired height
                
                // Show the new image preview
                imagePreviewContainerNew.classList.remove('hidden');
                
                // Append the new image to the preview container
                imagePreviewContainerNew.appendChild(newImage);
            };
            reader.readAsDataURL(file);
        }
    });
}


// archive rate
function archiveRate(rateId) {
    // Create a request to archive the rate
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "archive-rate.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText === 'success') {
                // If the response is successful, hide the archived row and show success modal
                var row = document.getElementById('rate-' + rateId);
                row.style.display = 'none'; // Hide the archived row
                showModal('successModal', 'The rate has been successfully archived.');
            } else {
                // If the response indicates failure, show error modal
                showModal('errorModal', 'Failed to archive the rate. Please try again.');
            }
        }
    };

    // Send the rate ID to the server to mark it as inactive
    xhr.send("id=" + rateId);
}

// Add-ons JS

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

// Fetch Add-on Data for Updating
function fetchAddonData(id) {
    fetch(`fetch-addon.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        console.log('Data received:', data);

        // Populate form fields with the fetched data
        document.getElementById('updateAddonName').value = data.name;
        document.getElementById('updateAddonPrice').value = data.price;
        document.getElementById('updateAddonDescription').value = data.description;

        // Set hidden input for add-on ID
        const updateAddonIdElement = document.getElementById('updateAddonId');
        if (updateAddonIdElement) {
            updateAddonIdElement.value = id;
        } else {
            console.error('Element with id "updateAddonId" not found');
        }

        // Manage image previews
        const imagePreviewContainerDb = document.getElementById('addon-image-preview-from-db');
        const imagePreviewContainerNew = document.getElementById('addon-image-preview-new');

        // Hide new image preview and reset the DB image container
        imagePreviewContainerNew.classList.add('hidden');
        imagePreviewContainerDb.innerHTML = '';
        imagePreviewContainerNew.innerHTML = '';

        if (data.picture) {
            // Show the image from the DB
            const imgElementDb = document.createElement('img');
            imgElementDb.src = data.picture;
            imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
            imgElementDb.alt = "Add-on Image from DB";
            imgElementDb.style.height = '100px';
            imagePreviewContainerDb.appendChild(imgElementDb);
        }
    })
    .catch(error => console.error('Error fetching add-on data:', error));

    // Handle new file upload (when updating image)
    document.getElementById('updateAddonFileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreviewContainerNew = document.getElementById('addon-image-preview-new');
                const imagePreviewContainerDb = document.getElementById('addon-image-preview-from-db');

                // Hide DB image preview and clear the new image preview container
                imagePreviewContainerDb.classList.add('hidden');
                imagePreviewContainerNew.innerHTML = '';

                // Create new image preview for the uploaded file
                const newImage = document.createElement('img');
                newImage.src = e.target.result;
                newImage.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
                newImage.style.height = '100px';

                // Show the new image preview container
                imagePreviewContainerNew.classList.remove('hidden');
                imagePreviewContainerNew.appendChild(newImage);
            };
            reader.readAsDataURL(file);  // Read the uploaded file as a data URL
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





















