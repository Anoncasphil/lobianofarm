function toggleAddEventModal() {
    const modal = document.getElementById('add-event-modal');
    modal.classList.toggle('hidden');
}

function hideAddEventModal() {
    const modal = document.getElementById('add-event-modal');
    modal.classList.add('hidden');
}

function openUpdateEventModal() {
    const modal = document.getElementById('update-event-modal');
    modal.classList.toggle('hidden');
}


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
            imagePreview.style.height = '100px'; 
            imagePreview.className = 'object-cover rounded-lg img-zoom-out';

            imagePreviewContainer.style.display = 'block'; 
        };
        reader.readAsDataURL(file);
    }
}

function toggleUpdateEventModal(eventId) {
    // Fetch event data from the server (AJAX request)
    fetch('get-event-details.php?id=' + eventId)
        .then(response => response.json())
        .then(data => {
            // Populate form fields with event data
            document.getElementById('updateName').value = data.name;
            document.getElementById('updateDate').value = data.date; // Date format might need adjustment
            document.getElementById('updateDescription').value = data.description;

            // Set the eventId in the hidden input field
            document.getElementById('eventId').value = eventId; // Set the hidden input field with the eventId

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
                imgElementDb.src = data.picture; // The base64-encoded image or URL
                imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
                imgElementDb.alt = "Event Image from DB";
                imgElementDb.style.height = '100px';
                imagePreviewContainerDb.appendChild(imgElementDb); // Append the image to the container
                // Show the database image preview container
                imagePreviewContainerDb.classList.remove('hidden');
            }

            // Handle new file upload (on file input change)
            document.getElementById('updatefile_input').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
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

            // Show the modal
            const modal = document.getElementById('update-event-modal');
            modal.classList.remove('hidden');  // Remove hidden class to show the modal
        })
        .catch(error => console.error('Error fetching event data:', error));
}




// Close the modal (you may want to trigger this with a close button inside the modal)
function closeUpdateEventModal() {
    const modal = document.getElementById('update-event-modal');
    modal.classList.add('hidden');  // Add hidden class to close the modal
}


// Handle image preview when the user selects a file
function previewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    if (file) {
        // Create an image URL to preview the selected image
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result; // Set the preview image source
            previewContainer.style.display = 'block'; // Show preview container
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none'; // Hide preview if no image is selected
    }
}

function archiveEvent(eventId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "archive-event.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText === 'success') {
                var row = document.getElementById('event-' + eventId);
                row.style.display = 'none'; // Hide the event row
                showModal('successModal', 'The event has been successfully archived.');

                // Redirect the page after a short delay
                setTimeout(function() {
                    window.location.href = 'events.php'; // Client-side redirect
                }, 1500); // 1.5-second delay
            } else {
                showModal('errorModal', 'Failed to archive the event. Please try again.');
            }
        }
    };

    xhr.send("id=" + eventId);
}



