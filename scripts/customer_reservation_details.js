
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
                updateStatus(data.status.toLowerCase()); // Convert to lowercase for consistency
            }
        })
        .catch(error => console.error("Error fetching status:", error));
});

// Wait for the DOM to fully load before running the script
document.addEventListener("DOMContentLoaded", function() {
    // Get the total price from the element
    const totalPriceElement = document.getElementById('total-price-details');
    const downpaymentElement = document.getElementById('downpayment');
    const newTotalElement = document.getElementById('new-total');
  
    // Check if elements are found
    if (totalPriceElement && downpaymentElement && newTotalElement) {
        // Get the total price and remove the currency symbol
        const totalPrice = parseFloat(totalPriceElement.innerText.replace('₱', '').trim());
        const downpayment = totalPrice / 2;
        const newTotal = totalPrice - downpayment;

        // Update the total price, downpayment, and new total display
        totalPriceElement.innerText = '₱' + totalPrice.toFixed(2);
        downpaymentElement.innerText = '- ₱' + downpayment.toFixed(2);
        newTotalElement.innerText = '₱' + newTotal.toFixed(2);
    }
});

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
    // Fetch the reservation details from the PHP script using the reservation ID
    fetch(`../api/get_invoice_details.php?reservation_id=${reservationId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        return response.text();  // Read the response as text first
    })
    .then(text => {
        try {
            const data = JSON.parse(text);  // Try parsing the text as JSON

            if (data.status === 'success' || data.status === 'Pending') {
                // Populate the invoice details
                document.getElementById('invoice-date-details').innerText = data.invoice_date || 'N/A';
                document.getElementById('invoice-no-details').innerText = data.invoice_number || 'N/A';
                document.getElementById('total-price-details').innerText = `₱${parseFloat(data.total_price).toFixed(2)}`;

                // Populate the items table with rates and addons
                let itemsHTML = '';
                if (data.rates && data.rates.length > 0) {
                    data.rates.forEach(rate => {
                        itemsHTML += `
                            <tr>
                                <td class="py-2 px-4">Rate</td>
                                <td class="py-2 px-4">${rate.rate_name}</td>
                                <td class="py-2 px-4">₱${parseFloat(rate.rate_price).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                }
                if (data.addons && data.addons.length > 0) {
                    data.addons.forEach(addon => {
                        itemsHTML += `
                            <tr>
                                <td class="py-2 px-4">Addon</td>
                                <td class="py-2 px-4">${addon.addon_name}</td>
                                <td class="py-2 px-4">₱${parseFloat(addon.addon_price).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('invoice-items-details').innerHTML = itemsHTML;

                // Populate the personal information
                document.getElementById('fname-details').value = data.first_name || '';
                document.getElementById('lname-details').value = data.last_name || '';
                document.getElementById('email-details').value = data.email || '';
                document.getElementById('contact-details').value = data.contact_number || '';

                // Ensure date and time values are in the correct format
                const checkinDate = data.checkin_date || '';
                const checkoutDate = data.checkout_date || '';
                const checkinTime = data.checkin_time || '';
                const checkoutTime = data.checkout_time || '';

                // Check-In and Check-Out Dates (Convert to text if necessary)
                if (checkinDate) {
                    document.getElementById('checkin-details').value = checkinDate;
                } else {
                    console.log("Check-in date is missing or empty.");
                }

                if (checkoutDate) {
                    document.getElementById('checkout-details').value = checkoutDate;
                } else {
                    console.log("Check-out date is missing or empty.");
                }

                // Check-In and Check-Out Times
                if (checkinTime) {
                    document.getElementById('checkin-time-details').value = checkinTime;
                } else {
                    console.log("Check-in time is missing or empty.");
                }

                if (checkoutTime) {
                    document.getElementById('checkout-time-details').value = checkoutTime;
                } else {
                    console.log("Check-out time is missing or empty.");
                }
            } else {
                console.error('Error fetching data:', data.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);  // Catch JSON parsing errors
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
        alertTitle = "Upload Successful";
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
