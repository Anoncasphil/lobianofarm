// Toggle Add Add-on Modal
function toggleAddAdminModal() {
    const modal = document.getElementById('add-admin-modal');
    modal.classList.toggle('hidden');
}

function hideAddAdminModal() {
    const modal = document.getElementById('add-admin-modal');
    modal.classList.add('hidden');
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

function deleteSelectedAdmins() {
    // Get all selected checkboxes with the class 'delete-checkbox'
    let selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
    let adminIds = [];

    selectedCheckboxes.forEach(function(checkbox) {
        adminIds.push(checkbox.value);
    });

    if (adminIds.length > 0) {
        // Send selected admin IDs to the server for deletion
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_admins.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            // Hide the confirmation modal after the user confirmed
            closeModal();

            if (xhr.status == 200) {
                console.log("Admins deleted: " + xhr.responseText);
                // Show success modal
                const successModal = document.getElementById("successModal");
                successModal.classList.remove("hidden");

                // Hide the success modal after 3 seconds
                setTimeout(() => {
                    successModal.classList.add("hidden");
                    // Optionally, reload the page to reflect changes
                    location.reload();
                }, 3000);
            } else {
                console.error("Failed to delete admins.");
                // Show error modal
                const errorModal = document.getElementById("errorModal");
                errorModal.classList.remove("hidden");

                // Hide the error modal after 3 seconds
                setTimeout(() => {
                    errorModal.classList.add("hidden");
                }, 3000);
            }
        };
        xhr.send("admin_ids=" + JSON.stringify(adminIds));  // Send admin IDs as a JSON string
    } else {
        alert("Please select at least one admin to delete.");
    }
}


function openUpdateAdminModal() {
    const modal = document.getElementById('update-admin-modal');
    modal.classList.toggle('hidden');
}

function toggleUpdateAdminModal(adminId) {
    // Fetch admin data from the server (AJAX request)
    fetch('get-admin-details.php?id=' + adminId)
        .then(response => response.json())
        .then(data => {
            // Populate form fields with admin data
            document.getElementById('updatefname').value = data.firstname;
            document.getElementById('updatelname').value = data.lastname;
            document.getElementById('updateemail').value = data.email;
            document.getElementById('updaterole').value = data.role; // Assume 'role' is either 'admin' or 'superadmin'

            // Set the adminId in the hidden input field
            document.getElementById('adminId').value = adminId; // Set the hidden input field with the adminId

            // Handle the admin's profile picture
            const imagePreviewContainerDb = document.getElementById('image-preview-from-db');
            const imagePreviewContainerNew = document.getElementById('image-preview-new');

            // Hide the new image preview container initially
            imagePreviewContainerNew.classList.add('hidden');

            // Clear both containers before appending new images
            imagePreviewContainerDb.innerHTML = '';
            imagePreviewContainerNew.innerHTML = '';

            // If a picture exists in the database, show it
            if (data.profile_picture) {
                const imgElementDb = document.createElement('img');
                imgElementDb.src = data.profile_picture; // The profile picture URL
                imgElementDb.className = "w-full h-auto object-cover rounded-lg img-zoom-out";
                imgElementDb.alt = "Admin Profile Picture";
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
            const modal = document.getElementById('update-admin-modal');
            modal.classList.remove('hidden');  // Remove hidden class to show the modal
        })
        .catch(error => console.error('Error fetching admin data:', error));
}

// Close the modal (you may want to trigger this with a close button inside the modal)
function closeUpdateAdminModal() {
    const modal = document.getElementById('update-admin-modal');
    modal.classList.add('hidden');  // Add hidden class to close the modal
}

// Toggle password visibility for both add and update modals
// Toggle password visibility for both add and update modals
const togglePasswordAdd = document.getElementById('togglePassword');
const passwordFieldAdd = document.getElementById('password');
const togglePasswordUpdate = document.getElementById('togglePasswordUpdate');
const passwordFieldUpdate = document.getElementById('updatepassword');

// Toggle password visibility for add modal
togglePasswordAdd.addEventListener('click', function() {
    // Toggle the type between password and text
    const type = passwordFieldAdd.type === 'password' ? 'text' : 'password';
    passwordFieldAdd.type = type;

    // Toggle the icon from show to hide
    this.classList.toggle('bx-show');
    this.classList.toggle('bx-hide');
});

// Toggle password visibility for update modal
togglePasswordUpdate.addEventListener('click', function() {
    // Toggle the type between password and text
    const type = passwordFieldUpdate.type === 'password' ? 'text' : 'password';
    passwordFieldUpdate.type = type;

    // Toggle the icon from show to hide
    this.classList.toggle('bx-show');
    this.classList.toggle('bx-hide');
});

