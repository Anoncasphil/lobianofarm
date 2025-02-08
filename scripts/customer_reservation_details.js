 
 // Retrieve the reservation ID from localStorage and populate the hidden input
 document.addEventListener("DOMContentLoaded", function () {
    let storedReservationId = localStorage.getItem('reservation_id');
    

    if (storedReservationId) {
      let reservationId = JSON.parse(storedReservationId);
      // Set the hidden input value
      document.getElementById("reservation-id").value = reservationId;
      // Display the reservation ID for checking
      document.getElementById("reservation-id-display").innerText = "Reservation ID: " + reservationId;
    } else {

    }
  });

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
        } else {
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const reservationIdInput = document.getElementById("reservation-id");
    
    if (!reservationIdInput || !reservationIdInput.value.trim()) {
        console.error("Reservation ID not found or empty.");
        return;
    }

    const reservationId = reservationIdInput.value.trim();

    fetch(`../api/fetch_status.php?reservation_id=${reservationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                updateStatus(data.status.toLowerCase()); // Convert to lowercase for consistency
            } else {
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
    } else {
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

            if (data.status === 'Confirmed' || data.status === 'Pending' || data.status === 'Completed') {
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
  