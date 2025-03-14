function updateStatus(status) {
    const steps = ["pending", "confirmed", "completed"];
    steps.forEach((step, index) => {
        let stepElement = document.getElementById(step);

        if (!stepElement) {
            return;
        }

        // Reset the design for all steps first
        stepElement.classList.remove("pending", "confirmed", "completed");
        stepElement.querySelector("span").classList.remove("bg-blue", "bg-green", "bg-gray");
        stepElement.querySelector("h4").classList.remove("text-blue", "text-green", "text-gray");

        // Apply the design based on the status
        if (steps.indexOf(status) >= index) {
            stepElement.classList.add(step); // Add the corresponding status class
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Fetch the stored reservation_id from localStorage
    let storedReservationId = localStorage.getItem('reservation_id');
    
    // Check if reservation_id exists and is not empty
    if (!storedReservationId || storedReservationId.trim() === '') {
        console.error("Reservation ID not found or empty.");
        return;
    }

    console.log("Reservation ID: " + storedReservationId);

    // Find the input field for reservation_id if needed (for example, if you need to use it elsewhere in the code)
    const reservationIdInput = document.getElementById('reservation_id');
    
    // Set the value of the reservation_id input field to the one from localStorage
    if (reservationIdInput) {
        reservationIdInput.value = storedReservationId;
    }

// Fetch the status using the reservation_id from localStorage
fetch(`../api/fetch_status.php?reservation_id=${storedReservationId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            console.log("Reservation Status: " + data.status); // Log the reservation status

            // Handle button visibility based on status
            const cancelBtn = document.getElementById('cancel-btn');
            const rescheduleBtn = document.getElementById('reschedule-btn');
            const reviewBtn = document.getElementById('review-btn'); // Select review button by id
            const resubmitBtn = document.getElementById('resubmit-btn');
            const infoAlert = document.getElementById('info-alert');
            const alertMessage = document.getElementById('alert-message'); // Select the alert message element
            const alertTitle = document.getElementById('alert-title'); // Select the alert title element

            const status = data.status.trim().toLowerCase(); // Ensure status is consistent (trim and lowercase)

            // Hide the info alert initially
            if (infoAlert) infoAlert.classList.add('hidden');

            if (status === 'pending') {
                // Only show the cancel and resubmit buttons for pending status
                if (cancelBtn) cancelBtn.classList.remove('hidden');
                if (rescheduleBtn) rescheduleBtn.classList.add('hidden');
                if (reviewBtn) reviewBtn.classList.add('hidden');
                if (resubmitBtn) resubmitBtn.classList.remove('hidden');
            } else if (status === 'confirmed') {
                // Only show the cancel and reschedule buttons for confirmed status
                if (cancelBtn) cancelBtn.classList.remove('hidden');
                if (rescheduleBtn) rescheduleBtn.classList.remove('hidden');
                if (reviewBtn) reviewBtn.classList.add('hidden');
                if (resubmitBtn) resubmitBtn.classList.add('hidden');
            } else if (status === 'cancelled') {
                // Only show the review button for cancelled status
                if (cancelBtn) cancelBtn.classList.add('hidden');
                if (rescheduleBtn) rescheduleBtn.classList.add('hidden');
                if (reviewBtn) reviewBtn.classList.remove('hidden');
                if (resubmitBtn) resubmitBtn.classList.add('hidden');

                // Show the info alert with the cancellation message
                if (infoAlert) {
                    infoAlert.classList.remove('hidden');
                    if (alertTitle) alertTitle.textContent = "Reservation Cancelled";
                    if (alertMessage) alertMessage.textContent = "Your reservation has been cancelled. We will contact you via email about our refund policy. Thank you.";
                }
            } else {
                // For other statuses, only show the review button
                if (cancelBtn) cancelBtn.classList.add('hidden');
                if (rescheduleBtn) rescheduleBtn.classList.add('hidden');
                if (reviewBtn) reviewBtn.classList.remove('hidden');
                if (resubmitBtn) resubmitBtn.classList.add('hidden');
            }

            updateStatus(status); // Use the lowercase status
        }
    })
    .catch(error => console.error("Error fetching status:", error));




    // Review Modal functionality
    const reviewModal = document.getElementById('review-modal');
    const reviewBtn = document.getElementById('review-btn');
    const closeReviewBtn = document.getElementById('close-review-btn');
    const reviewForm = document.getElementById('review-form');
    const ratingStars = document.querySelectorAll('#rating svg');

    // Open review modal
    reviewBtn.addEventListener('click', function () {
        reviewModal.classList.remove('hidden');
    });

    // Close review modal
    closeReviewBtn.addEventListener('click', function () {
        reviewModal.classList.add('hidden');
    });

    // Handle rating stars click
    ratingStars.forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-rating');
            ratingStars.forEach(s => s.classList.remove('text-yellow-500'));
            for (let i = 0; i < rating; i++) {
                ratingStars[i].classList.add('text-yellow-500');
            }
            document.getElementById('rating').setAttribute('data-rating', rating);
        });
    });

    // Handle review form submission
    reviewForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const reviewData = {
            user_id: document.getElementById('user-id').value,
            rating: document.getElementById('rating').getAttribute('data-rating'),
            title: document.getElementById('title').value,
            review_text: document.getElementById('review-text').value,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        };

        console.log('Review Data:', reviewData);

        // Submit the review data to the server
        fetch('../api/submit_review.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(reviewData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Review submitted successfully');
                reviewModal.classList.add('hidden');
            } else {
                console.error('Error submitting review:', data.message);
            }
        })
    });
});

// Wait for the DOM to fully load before running the script

window.onload = function () {
    // Get the reservation ID from localStorage
    const reservationId = localStorage.getItem('reservation_id');
    
    // Check if reservation ID exists in localStorage
    if (reservationId) {
        const reservationIdElement = document.getElementById('reservation-id');
        if (reservationIdElement) {
            reservationIdElement.innerText = reservationId;  // Display the reservation ID
            // Fetch reservation details using the reservation ID
            fetchReservationDetails(reservationId);
        } else {
            console.error('Reservation ID element not found!');
        }
    } else {
        console.error('Reservation ID not found in localStorage!');
    }
};

function fetchReservationDetails(reservationId) {
    fetch(`../api/get_invoice_details.php?reservation_id=${reservationId}`)
    .then(response => response.json())
    .then(data => {
        if (["Cancelled", "Confirmed", "Pending", "Completed"].includes(data.status)) {
            console.log("Reservation Status: " + data.status);

            // Fetch and display invoice details
            document.getElementById('invoice-no-details').innerText = data.invoice_number || 'N/A';
            document.getElementById('invoice-date-details').innerText = data.invoice_date || 'N/A';

            // Function to format currency with commas
            const formatCurrency = (amount) => {
                return `₱${parseFloat(amount || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            };

            // Remove commas and parse as float
            const totalPrice = parseFloat(data.total_price.replace(/,/g, '')) || 0;
            const validAmountPaid = parseFloat(data.valid_amount_paid.replace(/,/g, '')) || 0;
            const ratePrice = parseFloat(data.rate_price.replace(/,/g, '')) || 0;
            const extraPax = parseInt(data.extra_pax) || 0;
            const extraPaxPrice = parseFloat(data.extra_pax_price.replace(/,/g, '')) || 0;

            // Calculate remaining balance
            const newTotal = totalPrice - validAmountPaid;

            // Update price details with formatted currency
            document.getElementById('total-price-details').innerText = formatCurrency(totalPrice);
            document.getElementById('downpayment').innerText = formatCurrency(validAmountPaid);
            document.getElementById('new-total-display').innerText = formatCurrency(newTotal);

            // Populate the invoice items table
            let itemsHTML = '';

            // Display the rate directly from reservation data
            itemsHTML += `
                <tr>
                    <td class="py-2 px-4">Rate</td>
                    <td class="py-2 px-4">${data.rate_name || "N/A"}</td>
                    <td class="py-2 px-4">${formatCurrency(ratePrice)}</td>
                </tr>
            `;

            // Display extra pax if applicable
            if (extraPax > 0) {
                itemsHTML += `
                    <tr>
                        <td class="py-2 px-4">Extra Pax</td>
                        <td class="py-2 px-4">${extraPax} person(s)</td>
                        <td class="py-2 px-4">${formatCurrency(extraPaxPrice)}</td>
                    </tr>
                `;
            }

            // Display addons
            if (Array.isArray(data.addons) && data.addons.length > 0) {
                data.addons.forEach(addon => {
                    itemsHTML += `
                        <tr>
                            <td class="py-2 px-4">Addon</td>
                            <td class="py-2 px-4">${addon.addon_name}</td>
                            <td class="py-2 px-4">${formatCurrency(parseFloat(addon.addon_price))}</td>
                        </tr>
                    `;
                });
            }

            document.getElementById('invoice-items-details').innerHTML = itemsHTML;

            // Populate personal information
            document.getElementById('fname-details').value = data.first_name || '';
            document.getElementById('lname-details').value = data.last_name || '';
            document.getElementById('email-details').value = data.email || '';
            document.getElementById('contact-details').value = data.contact_number || '';

            // Populate check-in and check-out details
            document.getElementById('checkin-details').value = data.checkin_date || '';
            document.getElementById('checkout-details').value = data.checkout_date || '';
            document.getElementById('checkin-time-details').value = data.checkin_time || '';
            document.getElementById('checkout-time-details').value = data.checkout_time || '';
        } else {
            console.error('Error fetching data:', data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}






document.addEventListener("DOMContentLoaded", function() {
    // Get the button, modal, and close button elements
    const rescheduleBtn = document.getElementById("reschedule-btn");
    const rescheduleModal = document.getElementById("reschedule-modal");
    const closeBtn = document.getElementById("close-btn");

    // Add event listener to open the modal when the "Reschedule Reservation" button is clicked
    rescheduleBtn.addEventListener("click", function() {
        rescheduleModal.classList.remove("hidden");
    });

    // Add event listener to close the modal when the close button is clicked
    closeBtn.addEventListener("click", function() {
        rescheduleModal.classList.add("hidden");
    });

    // Optional: Close the modal if the user clicks outside the modal content
    window.addEventListener("click", function(e) {
        if (e.target === rescheduleModal) {
            rescheduleModal.classList.add("hidden");
        }
    });
});

// Show the alert based on form submission status (success or failure)
function showAlert(status, message) {
    const alertElement = document.getElementById('info-alert');
    const alertTitleElement = document.getElementById('alert-title');
    const alertMessageElement = document.getElementById('alert-message');

    if (!alertElement || !alertTitleElement || !alertMessageElement) {
        console.warn("Alert elements not found!");
        return;
    }

    let alertTitle;
    let alertMessage;

    if (status === 'success') {
        alertTitle = "Successful";
        alertMessage = message || "Your reschedule request was successfully submitted.";
    } else if (status === 'failure') {
        alertTitle = "Upload Failed";
        alertMessage = message || "There was an issue with submitting your reschedule request. Please try again.";
    } else {
        alertTitle = "Error";
        alertMessage = "An unexpected error occurred. Please try again.";
    }

    // Set the title and message dynamically
    alertTitleElement.innerText = alertTitle;
    alertMessageElement.innerText = alertMessage;

    // Show the alert
    alertElement.classList.remove('hidden');

    // Hide the alert after 5 seconds
    setTimeout(() => {
        alertElement.classList.add('hidden');
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Flatpickr initialization for check-in date
    flatpickr("#check-in-date", {
        dateFormat: "Y-m-d", // Ensure the date format matches your PHP requirements
        onChange: function(selectedDates, dateStr, instance) {
            // Set the check-in date value
            const checkInDate = selectedDates[0];
            if (checkInDate) {
                const checkOutDate = new Date(checkInDate);
                checkOutDate.setDate(checkOutDate.getDate() + 1); // Set check-out date as the next day

                // Format the date back to string for input
                const formattedCheckOutDate = checkOutDate.toISOString().split('T')[0];
                
                // Set the hidden check-out date field
                document.getElementById("check-out-date").value = formattedCheckOutDate;
            }
        }
    });
});

// Wait for the DOM to be fully loaded before adding event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Get references to form fields
    const rescheduleForm = document.getElementById('reschedule-form');
    const submitButton = document.getElementById('submit-btn');
    const rescheduleModal = document.getElementById('reschedule-modal'); // Reference to the reschedule modal

    // Add an event listener to handle form submission via AJAX
    submitButton.addEventListener('click', function(event) {
        // Prevent the form from submitting the traditional way
        event.preventDefault();

        // Log to check if the button is clicked
        console.log('Submit button clicked');

        // Gather form data
        const reservationId = document.getElementById('reservation_id').value;
        const checkInDate = document.getElementById('check-in-date').value;
        const checkOutDate = document.getElementById('check-out-date').value;
        const reason = document.getElementById('schedule_reason').value;

        // Log the form data to verify the values
        console.log('Form data:', {
            reservationId,
            checkInDate,
            checkOutDate,
            reason
        });

        // Create an object to send as the data payload
        const formData = new FormData();
        formData.append('reservation_id', reservationId);
        formData.append('check_in_date', checkInDate);
        formData.append('check_out_date', checkOutDate);  // Assuming this field is handled in your JS
        formData.append('description', reason);
        formData.append('status', 'Pending');
// Make the AJAX request to the PHP script
fetch('../api/store_reschedule.php', {
    method: 'POST',
    body: formData,
})
.then(response => response.text())  // Read the response as plain text
.then(data => {
    console.log('Raw Response:', data);  // Log the raw response for inspection

    try {
        // Try parsing the response as JSON
        const jsonData = JSON.parse(data);
        console.log('Parsed Response:', jsonData);

        // Check for success or failure and display messages
        if (jsonData.status === 'success') {
            // Close the reschedule modal
            rescheduleModal.classList.add('hidden');  // Hide the reschedule modal
            
            // Display success message in the #info-alert div
            document.getElementById('alert-title').textContent = 'Success';
            document.getElementById('alert-message').textContent = 'Reschedule request submitted successfully!';
            const infoAlert = document.getElementById('info-alert');
            infoAlert.classList.remove('hidden');  // Show the success message

            // Hide the success alert after 5 seconds
            setTimeout(() => {
                infoAlert.classList.add('hidden');
            }, 5000);

            // Call the notificationSuccess function
            notificationSuccess(jsonData.reservation_id); // Pass reservation_id from response
        } else {
            // Show error in the modal if the response status is failure
            document.getElementById('alert-title').textContent = 'Error';
            document.getElementById('alert-message-modal').textContent = jsonData.message;

            // Display the modal
            const modal = document.getElementById('info-alert-modal');
            modal.classList.remove('hidden');
            
            // Hide the modal after 5 seconds
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 5000);
        }
    } catch (error) {
        console.error('Error parsing JSON:', error);

        // Handle the error parsing the response
        document.getElementById('alert-title').textContent = 'Error';
        document.getElementById('alert-message-modal').textContent = 'There was an error processing your request.';

        // Display the modal
        const modal = document.getElementById('info-alert-modal');
        modal.classList.remove('hidden');
        
        // Hide the modal after 5 seconds
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 5000);
    }
})
.catch(error => {
    console.error('Request failed:', error);
    
    // Handle network or request failure
    document.getElementById('alert-title').textContent = 'Error';
    document.getElementById('alert-message-modal').textContent = 'There was an error processing your request. Please try again later.';
    
    // Display the modal
    const modal = document.getElementById('info-alert-modal');
    modal.classList.remove('hidden');
    
    // Hide the modal after 5 seconds
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 5000);
});
    });
});

function notificationSuccess() {
    // Access the userId globally

    // Fetch the reservation_id from the hidden input field
    const reservationId = document.getElementById('reservation_id').value;

    // Check if reservationId is available
    if (!reservationId) {
        console.error('Reservation ID is missing.');
        alert('Reservation ID not found.');
        return; // Stop execution if reservationId is not found
    }

    console.log('Fetching reservation code for reservation ID:', reservationId);

    // Fetch the reservation code from the database based on reservationId
    fetch(`../api/get_reservation_code.php?reservation_id=${reservationId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const reservationCode = data.reservation_code;
            const title = 'Customer Reschedule Request';
            const message = `Customer with Reservation Code: ${reservationCode} has requested to reschedule reservation ID: ${reservationId}. Please review the request.`;

            const notificationData = new FormData();
            notificationData.append('user_id', window.userId);
            notificationData.append('reservation_id', reservationId);
            notificationData.append('title', title);
            notificationData.append('message', message);
            notificationData.append('type', 'info');
            
            fetch('../api/store_notifications.php', {
                method: 'POST',
                body: notificationData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Notification sent to admin successfully');
                } else {
                    console.log('Failed to send notification to admin:', data.message);
                }
            })
            .catch(error => {
                console.error('Error sending notification to admin:', error);
            });
        } else {
            console.error('Failed to fetch reservation code:', data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching reservation code:', error);
    });
}

// Get elements
const reviewBtn = document.getElementById("review-btn");
const reviewModal = document.getElementById("review-modal");
const closeReviewBtn = document.getElementById("close-review-btn");

// Function to open the modal
function openReviewModal() {
  reviewModal.classList.remove("hidden");
  reviewModal.setAttribute("aria-hidden", "false");
  reviewModal.setAttribute("tabindex", "0");
}

// Function to close the modal
function closeReviewModal() {
  reviewModal.classList.add("hidden");
  reviewModal.setAttribute("aria-hidden", "true");
  reviewModal.setAttribute("tabindex", "-1");
}

// When the review button is clicked, open the modal
reviewBtn.addEventListener("click", openReviewModal);

// When the close button (X) is clicked, close the modal
closeReviewBtn.addEventListener("click", closeReviewModal);

// Close the modal if the user clicks outside the modal content
window.addEventListener("click", function (event) {
  if (event.target === reviewModal) {
    closeReviewModal();
  }
});

 // Function to toggle modal visibility
 function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.toggle('hidden');
  }

  // Function to handle reservation cancellation
  function cancelReservation() {
    // Get the reservation ID from localStorage
    const reservationId = JSON.parse(localStorage.getItem('reservation_id'));

    if (!reservationId) {
        alert('No reservation ID found!');
        return;
    }

    // Send the reservation ID to the backend to update status
    fetch('../api/cancel_reservation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reservation_id: reservationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success alert after successful cancellation
            showAlert('success', 'Your reservation has been cancelled successfully.');

            // Close the modal
            toggleModal('cancel-reservation');
            
            // Optionally refresh or update the UI here
        } else {
            showAlert('failure', data.message || 'Failed to cancel reservation. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('failure', 'An error occurred while cancelling the reservation.');
    });
}
