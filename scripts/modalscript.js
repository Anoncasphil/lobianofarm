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

function fetchRateData(id) {
    fetch(`../rates/fetch-rate.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        console.log('✅ Data received:', data);

        // Select elements
        const nameField = document.getElementById('updatename');
        const tempPriceField = document.getElementById('updatetemp_price'); // Original price
        const descriptionField = document.getElementById('updatedescription');
        const hoursOfStayField = document.getElementById('updatehoursofstay');
        const checkinField = document.getElementById('updatecheckin');
        const checkoutField = document.getElementById('updatecheckout');
        const discountField = document.getElementById('updatediscount');
        const discountCheckbox = document.getElementById('updateadd_discount_checkbox');
        const discountContainer = document.getElementById("updatediscount_container");
        const rateTypeSelect = document.getElementById('update-rate-type');
        const rateIdField = document.getElementById('updateRateId');

        // Validate data exists
        if (!data || Object.keys(data).length === 0) {
            console.error("❌ Error: No data received!");
            return;
        }

        // Populate fields
        if (nameField) nameField.value = data.name || ''; 
        if (tempPriceField) tempPriceField.value = data.original_price || ''; // Use original price
        if (descriptionField) descriptionField.value = data.description || ''; 
        if (hoursOfStayField) hoursOfStayField.value = data.hoursofstay || ''; 
        if (checkinField) checkinField.value = data.checkin_time ? data.checkin_time.slice(0, 5) : ''; 
        if (checkoutField) checkoutField.value = data.checkout_time ? data.checkout_time.slice(0, 5) : ''; 

        // Set discount dropdown & checkbox
        if (discountField) discountField.value = data.discount_percentage || ''; 
        if (discountCheckbox) {
            discountCheckbox.checked = data.has_discount == 1;
            if (discountContainer) discountContainer.classList.toggle("hidden", !data.has_discount);
        }

        if (rateIdField) rateIdField.value = id;

        // Set the dropdown selection manually for rate_type
        if (rateTypeSelect) {
            const validRateTypes = ["Daytime", "Nighttime", "WholeDay"];
            
            // Log the rate_type to verify the value received from the database
            console.log("✅ rate_type from DB:", data.rate_type);
            
            // Normalize the rate_type value to match the case exactly as in the dropdown
            const normalizedRateType = data.rate_type;
            
            // Check and log if the value matches any of the valid types
            if (validRateTypes.includes(normalizedRateType)) {
                rateTypeSelect.value = normalizedRateType;
                console.log(`✅ Setting rate type to: ${normalizedRateType}`);
            } else {
                rateTypeSelect.value = 'Daytime'; // Default value
                console.log("❌ Invalid rate type from DB, defaulting to 'Daytime'");
            }
        } else {
            console.error('❌ Element with id "update-rate-type" not found');
        }

        console.log("✅ Fields populated successfully!");

        // Calculate final price
        setTimeout(calculateFinalPrice, 50);
    })
    .catch(error => console.error('❌ Error fetching rate data:', error));
}



// Ensure final price updates when discount or original price changes
document.addEventListener("DOMContentLoaded", function () {
    const discountCheckbox = document.getElementById("updateadd_discount_checkbox");
    const discountDropdown = document.getElementById("updatediscount");
    const tempPriceField = document.getElementById("updatetemp_price");

    if (discountCheckbox) {
        discountCheckbox.addEventListener("change", function() {
            document.getElementById("updatediscount_container").classList.toggle("hidden", !this.checked);
            calculateFinalPrice();
        });
    }

    if (discountDropdown) discountDropdown.addEventListener("change", calculateFinalPrice);
    if (tempPriceField) tempPriceField.addEventListener("input", calculateFinalPrice);
});

function calculateFinalPrice() {
    const tempPriceField = document.getElementById("updatetemp_price");
    const discountCheckbox = document.getElementById("updateadd_discount_checkbox");
    const discountField = document.getElementById("updatediscount");
    const priceField = document.getElementById("updateprice");

    // Check if elements exist and log any missing ones
    if (!tempPriceField) {
        console.error("❌ Error: 'updatetemp_price' element not found.");
    }
    if (!discountCheckbox) {
        console.error("❌ Error: 'updateadd_discount_checkbox' element not found.");
    }
    if (!discountField) {
        console.error("❌ Error: 'updatediscount' element not found.");
    }
    if (!priceField) {
        console.error("❌ Error: 'updateprice' element not found.");
    }

    // If any element is missing, stop the function
    if (!tempPriceField || !discountCheckbox || !discountField || !priceField) {
        return;
    }

    let tempPrice = parseFloat(tempPriceField.value) || 0;
    let discount = discountCheckbox.checked ? parseFloat(discountField.value) || 0 : 0;

    priceField.value = (tempPrice - (tempPrice * discount / 100)).toFixed(2);
    console.log("✅ Final price updated:", priceField.value);
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
                // Add timeout to hide the modal or reload page after 1500ms
                setTimeout(() => {
                    // You can either hide the modal or reload the page after timeout
                    // For consistency with restoreRate, we'll just let the default modal timeout handle it
                }, 1500);
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

function showDetails(id) {
    // Fetch data from the database using AJAX
    fetch('fetch-rate.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            // Populate modal fields with data
            document.getElementById('modalTitle').textContent = data.name
            document.getElementById('modalPrice').textContent = data.price;
            document.getElementById('modalHoursOfStay').textContent = data.hoursofstay;
            document.getElementById('modalDescription').textContent = data.description;
            document.getElementById('modalPicture').src = data.picture;

            // Show the modal
            document.getElementById('detailsModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error fetching data:', error));
}

function closeModal() {
    // Hide the modal
    document.getElementById('detailsModal').classList.add('hidden');
}

function closeModal() {
    // Hide the modal
    const modal = document.getElementById('archiveModal');
    modal.classList.add('hidden');
}

function calculateCheckout() {
    const hoursInput = document.getElementById("hours").value;
    const checkinTime = document.getElementById("checkin").value;

    if (hoursInput && checkinTime) {
        const [checkinHour, checkinMinute] = checkinTime.split(":").map(Number);
        let checkoutHour = checkinHour + parseInt(hoursInput);
        const checkoutMinute = checkinMinute;

        if (checkoutHour >= 24) {
            checkoutHour -= 24;
        }

        const formattedCheckoutHour = checkoutHour.toString().padStart(2, "0");
        const formattedCheckoutMinute = checkoutMinute.toString().padStart(2, "0");

        document.getElementById("checkout").value = `${formattedCheckoutHour}:${formattedCheckoutMinute}`;
    }
}

function calculateCheckoutUpdate() {
    // Get the values from the input fields
    const hoursInput = document.getElementById("updatehoursofstay").value;
    const checkinTime = document.getElementById("updatecheckin").value;

    // Check if both inputs have values
    if (hoursInput && checkinTime) {
        // Split the check-in time into hours and minutes
        const [checkinHour, checkinMinute] = checkinTime.split(":").map(Number);

        // Calculate the checkout hour by adding the hours of stay
        let checkoutHour = checkinHour + parseInt(hoursInput);

        // Handle overflow (if checkout hour is 24 or more)
        if (checkoutHour >= 24) {
            checkoutHour -= 24;
        }

        // Format the checkout hour and minute to always have two digits
        const formattedCheckoutHour = checkoutHour.toString().padStart(2, "0");
        const formattedCheckoutMinute = checkinMinute.toString().padStart(2, "0");

        // Update the checkout field with the calculated time
        document.getElementById("updatecheckout").value = `${formattedCheckoutHour}:${formattedCheckoutMinute}`;
    }
}

function restoreRate(rateId) {
    // Create a request to restore the rate
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "restore_rate.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Server Response: ", xhr.responseText);
            if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
                showModal('successModal', 'The rate has been successfully restored.');
                // Reload page to show the restored rate
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showModal('errorModal', 'Failed to restore the rate. Please try again.');
            }
        }
    };

    // Send the rate ID to the server to mark it as active
    xhr.send("rate_id=" + rateId);
}


