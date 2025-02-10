document.addEventListener("DOMContentLoaded", async () => {
    const checkInDateInput = document.getElementById("check-in-date");
    const checkOutDateInput = document.getElementById("check-out-date");
    const checkInTimeInput = document.getElementById("check-in-time");
    const checkOutTimeInput = document.getElementById("check-out-time");

    // Fetch reservation ID from localStorage once
    let reservationId = localStorage.getItem("reservationID_admin");

    if (reservationId) {
        fetchReservationDetails(reservationId);
    } else {
        console.error("No stored reservation ID found.");
    }

    // Function to fetch reservation details
    async function fetchReservationDetails(reservationId) {
        try {
            const response = await fetch(`../api/get_reservation_details.php?id=${reservationId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const reservation = await response.json();

            if (reservation && !reservation.error) {
                // Populate form fields
                checkInDateInput.value = reservation.check_in_date || "";
                checkOutDateInput.value = reservation.check_out_date || "";
                checkInTimeInput.value = reservation.check_in_time || "";
                checkOutTimeInput.value = reservation.check_out_time || "";
                document.addEventListener("DOMContentLoaded", function () {
                    const reservationId = localStorage.getItem("reservationID_admin");
                
                    if (reservationId) {
                        fetchReservationDetails(reservationId);
                        localStorage.removeItem("reservationID_admin"); // Optional: Remove after use
                    } else {
                        console.error("No reservation ID provided.");
                    }
                });
                
                rateType = reservation.rate_type;
                rateID = reservation.rate_id;
                hoursOfStay = reservation.hoursofstay;

                // Initialize Flatpickr with updated disabled dates based on rateType
                initializeFlatpickr(rateType);
            } else {
                console.error("No reservation found:", reservation.error);
            }
        } catch (error) {
            console.error("Error fetching reservation details:", error);
        }
    }


    async function fetchReservedDates() {
        try {
            const response = await fetch("../api/get_reserved_dates_booking.php");
            const reservedDates = await response.json();

            if (!reservedDates || !reservedDates.reservedDaytime || !reservedDates.reservedNighttime || !reservedDates.reservedWholeDay) {
                console.error("Unexpected reserved dates structure:", reservedDates);
                return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
            }
            window.reservedDates = reservedDates; // Make reservedDates accessible globally
            return reservedDates;
        } catch (error) {
            console.error("Error fetching reserved dates:", error);
            return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
        }
    }

    async function initializeFlatpickr(rateType) {
        const { reservedDaytime, reservedNighttime, reservedWholeDay } = await fetchReservedDates();
        
        function checkAndShowAlert(selectedDate) {

            if (reservedDaytime.includes(selectedDate) && reservedNighttime.includes(selectedDate)) {
                showAlert("Both Daytime and Nighttime");
            } else if (reservedNighttime.includes(selectedDate)) {
                showAlert("Nighttime");
            } else if (reservedDaytime.includes(selectedDate)) {
                showAlert("Daytime");
            } else {
                hideAlert();
            }
        }

        checkInDateInput.addEventListener("change", function () {
            checkAndShowAlert(checkInDateInput.value);
            updateCheckoutDate();
        });

        const currentDate = new Date().toISOString().split("T")[0];

        let disableDates = [{ from: "1970-01-01", to: currentDate }];

        // Disable dates based on selected rate type
        if (rateType === "WholeDay") {
            disableDates = disableDates.concat(
                reservedWholeDay.map((date) => ({ from: date, to: date }))
            );
        } else if (rateType === "Daytime") {
            disableDates = disableDates.concat(
                reservedDaytime.map((date) => ({ from: date, to: date }))
            );
        } else if (rateType === "Nighttime") {
            disableDates = disableDates.concat(
                reservedNighttime.map((date) => ({ from: date, to: date }))
            );
        }

        flatpickr("#check-out-date", {
            dateFormat: "Y-m-d"
        });

        flatpickr(checkInDateInput, {
            dateFormat: "Y-m-d",
            disable: disableDates,
            onChange: updateCheckoutDate,
        });
    }

    function showAlert(rateType) {
        const alertElement = document.getElementById("info-alert");
        const alertMessageElement = document.getElementById("alert-message");

        if (!alertElement || !alertMessageElement) {
            console.warn("Alert elements not found!");
            return;
        }

        let alertMessage;
        if (rateType === "Nighttime") {
            alertMessage = "The Overnight Stay has been booked for this date. Please choose another date.";
        } else if (rateType === "Daytime") {
            alertMessage = "The Standard Stay has been booked for this date. Please choose another date.";
        } else if (rateType === "Both Daytime and Nighttime") {
            alertMessage = "Both Daytime and Nighttime rates have been booked.";
        }

        alertMessageElement.innerText = alertMessage;
        alertElement.classList.remove("hidden");
    }

    function hideAlert() {
        document.getElementById("info-alert")?.classList.add("hidden");
    }

    function calculateCheckoutDate(checkInDate, checkInTime, hoursOfStay) {
        // Create a Date object for check-in
        const checkInDateTime = new Date(`${checkInDate}T${checkInTime}`);
    
        // Add the adjusted hoursOfStay to the check-in time
        checkInDateTime.setHours(checkInDateTime.getHours() + hoursOfStay);
    
        // Extract correct checkout date and time (avoid UTC conversion issues)
        const checkoutDate = checkInDateTime.getFullYear() + "-" +
                             String(checkInDateTime.getMonth() + 1).padStart(2, '0') + "-" +
                             String(checkInDateTime.getDate()).padStart(2, '0');
    
        const checkoutTime = String(checkInDateTime.getHours()).padStart(2, '0') + ":" +
                             String(checkInDateTime.getMinutes()).padStart(2, '0') + ":" +
                             String(checkInDateTime.getSeconds()).padStart(2, '0');
    
        return { checkoutDate, checkoutTime };
    }
    

    function updateCheckoutDate() {
        const checkInDate = checkInDateInput.value;
        const checkInTime = checkInTimeInput.value;
    
        if (!checkInDate || !checkInTime) return;
    
        // Adjust hours of stay based on rate type
        const adjustedHoursOfStay = rateType === 'Nighttime' ? 24 : hoursOfStay;
    
        const { checkoutDate, checkoutTime } = calculateCheckoutDate(checkInDate, checkInTime, adjustedHoursOfStay);
    
        checkOutDateInput.value = checkoutDate;
        checkOutTimeInput.value = checkoutTime;
    
        console.log(`Check-in: ${checkInDate} ${checkInTime}, Check-out: ${checkoutDate} ${checkoutTime}`);
    }
    
    // Initialize Flatpickr after setting everything up
    initializeFlatpickr();

});



function fetchReservationDetailsAdmin(reservationId) {
    console.log("Fetching reservation details for ID: " + reservationId);  // Check if this logs
    fetch(`../api/get_invoice_details.php?reservation_id=${reservationId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok.");
        }
        return response.json();  // directly parsing as JSON since PHP returns JSON
    })
    .then(data => {
        console.log("Fetched data:", data);  // Check what data is being returned
        if (data.status === 'error') {
            console.error(data.message);
            return;
        }

        // ✅ Update status dropdown
        if (data.status) {
            updateStatusDropdown(data.status);
        }

        // ✅ Populate all other fields
        populateFields(data);
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });
}



function populateFields(data) {
    const setText = (id, value) => {
        const element = document.getElementById(id);
        if (element) {
            element.innerText = value || "N/A";
        } else {
            console.error(`Element with ID '${id}' not found.`);
        }
    };

    const formatCurrency = (amount) => {
        return `₱${parseFloat(amount || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    };

    const formatNegativeCurrency = (amount) => {
        return `- ₱${parseFloat(amount || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    };

    const setValue = (id, value) => {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || "";
        } else {
            console.error(`Input element with ID '${id}' not found.`);
        }
    };

    // Populate fields with the data from the response
    setValue("first-name-p", data.first_name);
    setValue("last-name-p", data.last_name);
    setValue("email-p", data.email);
    setValue("mobile-number-p", data.contact_number);

    // Remove commas before parsing the numbers
    const totalPrice = parseFloat(data.total_price.replace(/,/g, '')) || 0;
    const validAmountPaid = parseFloat(data.valid_amount_paid.replace(/,/g, '')) || 0;

    // Calculate the new total (total-price - valid_amount_paid)
    const newTotal = totalPrice - validAmountPaid;

    // Apply formatting after calculation
    setText("invoice-date", data.invoice_date);
    setText("invoice-no", data.invoice_number);
    setText("total-price", formatCurrency(totalPrice));
    setText("valid_amount_paid", formatNegativeCurrency(validAmountPaid));
    setText("new-total", formatCurrency(newTotal));

    // Handle rates and addons in the invoice
    const invoiceItemsElement = document.getElementById("invoice-items");
    if (invoiceItemsElement) {
        let itemsHTML = "";

        // Render the rates
        if (Array.isArray(data.rates) && data.rates.length > 0) {
            data.rates.forEach(rate => {
                itemsHTML += `
                    <tr>
                        <td class="py-2 px-4">Rate</td>
                        <td class="py-2 px-4">${rate.rate_name}</td>
                        <td class="py-2 px-4">${formatCurrency(rate.rate_price)}</td>
                    </tr>
                `;
            });
        }

        // Render the addons
        if (Array.isArray(data.addons) && data.addons.length > 0) {
            data.addons.forEach(addon => {
                itemsHTML += `
                    <tr>
                        <td class="py-2 px-4">Addon</td>
                        <td class="py-2 px-4">${addon.addon_name}</td>
                        <td class="py-2 px-4">${formatCurrency(addon.addon_price)}</td>
                    </tr>
                `;
            });
        }

        invoiceItemsElement.innerHTML = itemsHTML;
    } else {
        console.error("Element with ID 'invoice-items' not found.");
    }
}



function updateStatusDropdown(status) {
    const statusDropdown = document.getElementById("status-dropdown");
    if (statusDropdown) {
        statusDropdown.value = status.toLowerCase(); // Set the dropdown value
    } else {
        console.error("Status dropdown element not found.");
    }
}


function updateStatusColor(selectElement) {
    const colors = {
        pending: "text-orange-500",
        confirmed: "text-green-500",
        completed: "text-blue-500",
        cancelled: "text-red-500"
    };

    // Remove previous color classes
    selectElement.classList.remove("text-orange-500", "text-green-500", "text-blue-500", "text-red-500");

    // Apply new color
    const selectedValue = selectElement.value;
    if (colors[selectedValue]) {
        selectElement.classList.add(colors[selectedValue]);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const reservationId = localStorage.getItem("reservationID_admin");

    if (reservationId) {
        fetchReservationDetailsAdmin(reservationId);
    } else {
        console.error("No reservation ID provided.");
    }
});

 // Function to toggle the modal visibility
 function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.toggle('hidden');
  }



// Function to open and populate the modal
function openPaymentModal() {
    // Retrieve the reservationId from localStorage
    let reservationId = localStorage.getItem("reservationID_admin");
  
    if (!reservationId) {
      alert('No reservation ID found');
      return;
    }
  
    // Make a fetch request to payment_details.php with the reservationId
    fetch(`../api/payment_details.php?reservation_id=${reservationId}`)
      .then(response => response.json())
      .then(data => {
        // Check if there is an error
        if (data.error) {
          alert(data.error);
          return;
        }
  
        // Populate the modal with the data from the server
        document.getElementById('referenceNumber').value = data.reference_number;
  
        // Ensure valid_amount_paid is treated as a number and format it with commas
        const validAmountPaid = parseFloat(data.valid_amount_paid).toFixed(2);
        const formattedAmountPaid = parseFloat(validAmountPaid).toLocaleString();
  
        document.getElementById('validAmountPaid').value = `₱${formattedAmountPaid}`;
  
        document.getElementById('paymentReceipt').src = `../src/uploads/customerpayment/${data.payment_receipt}`;
        document.getElementById('receiptLink').href = `../src/uploads/customerpayment/${data.payment_receipt}`;
  
        // Open the modal
        toggleModal('payment-modal');
      })
      .catch(error => {
        alert('Error fetching payment details: ' + error);
      });
      
  }

  // Function to fetch and populate the rates dropdown
function populateRates() {
    fetch('../api/get_rates.php')
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          const rateSelected = document.getElementById('rateSelected');
          rateSelected.innerHTML = ''; // Clear any existing options
  
          // Populate the rates dropdown
          data.rates.forEach(rate => {
            const option = document.createElement('option');
            option.value = rate.id;
            option.textContent = rate.name
            rateSelected.appendChild(option);
          });
        } else {
          console.error('Error fetching rates:', data.message);
        }
      })
      .catch(error => console.error('Error fetching rates:', error));
  }
  
  // Function to fetch and populate the addons dropdown
  function populateAddons() {
    fetch('../api/get_addons.php')
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          const addonsSelect = document.getElementById('addonsSelect');
          addonsSelect.innerHTML = '<option value="">-- Select an Addon --</option>'; // Reset the select options
  
          // Populate the addons dropdown
          data.addons.forEach(addon => {
            const option = document.createElement('option');
            option.value = addon.id;
            option.textContent = addon.name
            addonsSelect.appendChild(option);
          });
        } else {
          console.error('Error fetching addons:', data.message);
        }
      })
      .catch(error => console.error('Error fetching addons:', error));
  }
  
  document.addEventListener('DOMContentLoaded', () => {
    // Fetch the reservation ID from localStorage and load the data
    const reservationId = localStorage.getItem("reservationID_admin");
    console.log('Loaded Reservation ID:', reservationId); // Log the reservation ID

    if (reservationId) {
        loadReservationData(reservationId); // Call the function with the reservation ID
    } else {
        console.error('No reservation ID found in localStorage');
    }

    // Call the functions to populate the dropdowns when the page loads
    populateRates();
    populateAddons();

    // Event listener for rate selection change
    const rateSelected = document.getElementById('rateSelected');
    if (rateSelected) {
        rateSelected.addEventListener('change', (event) => {
            const newRateId = event.target.value;
            updateRate(newRateId, reservationId); // Update the rate when the user selects a new rate
        });
    }

    // Event listener for Save Addons button (if you have a button for saving)
    document.getElementById('saveAddonsBtn')?.addEventListener('click', () => {
        saveAddons(reservationId);
    });
});

async function loadRateDetails(rateId) {
    try {
        const response = await fetch(`../api/get_reservation_rates_id.php?reservation_id=${rateId}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Rate API Response:', data); // Log the rate response

        if (data.status === 'success' && data.reservation) {
            const { rate_id, rate_name, rate_price, rate_type, check_in_date } = data.reservation;

            console.log('Rate ID:', rate_id);
            console.log('Rate Name:', rate_name);
            console.log('Rate Price:', rate_price);
            console.log('Rate Type:', rate_type);
            console.log('Check in Date:', check_in_date);

            // Store the rate price for later use
            window.ratePrice = parseFloat(rate_price);

            // Check if the selected rate is already booked for the check-in date
            const isRateReserved = 
                (rate_type === 'Nighttime' && reservedDates.reservedDaytime.includes(check_in_date)) ||
                (rate_type === 'Daytime' && reservedDates.reservedNighttime.includes(check_in_date));

            if (isRateReserved) {
                // Disable the rate selection dropdown if the rate is reserved
                const rateSelected = document.getElementById('rateSelected');
                if (rateSelected) {
                    rateSelected.disabled = true; // Disable the dropdown
                }

                showError('Cannot change rate. The rate is already booked for this date.');
                return;
            } else {
                // Enable the rate selection dropdown if no conflict
                const rateSelected = document.getElementById('rateSelected');
                if (rateSelected) {
                    rateSelected.disabled = false; // Enable the dropdown
                }
            }

            // Fetch all rates (Assuming your API provides this data or a separate API endpoint)
            const allRatesResponse = await fetch('../api/get_all_rates.php');
            if (!allRatesResponse.ok) {
                throw new Error('Failed to fetch all rates');
            }
            const allRatesData = await allRatesResponse.json();
            if (allRatesData.status === 'success' && allRatesData.rates) {
                const rateSelected = document.getElementById('rateSelected');
                if (rateSelected) {
                    console.log('Rate dropdown found');

                    // Clear existing options
                    rateSelected.innerHTML = '';

                    // Populate the dropdown with all available rates
                    allRatesData.rates.forEach(rate => {
                        const rateOption = document.createElement('option');
                        rateOption.value = rate.rate_id;
                        rateOption.textContent = rate.rate_name;

                        // Set the default selected rate
                        if (rate.rate_id === rate_id) {
                            rateOption.selected = true;
                        }

                        rateSelected.appendChild(rateOption);
                    });

                    console.log('Rate options populated');
                }
            } else {
                console.error('Failed to fetch available rates');
            }

            // Fetch and display addons
            await loadAddons(rate_id);
        } else {
            console.error('Error fetching reservation details for rate:', data.message);
        }
    } catch (error) {
        console.error('Error fetching reservation rate details:', error);
    }
}




// Function to fetch and display reservation addons
async function loadAddons(reservationId) {
    try {
        const response = await fetch(`../api/get_reservation_rates_id.php?reservation_id=${reservationId}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Addons API Response:', data); // Log the addons response

        if (data.status === 'success' && data.addons) {
            const addonsDiv = document.getElementById('addonsDisplay');
            console.log('Addons Display Div:', addonsDiv); // Check if it exists in the DOM

            let totalAddonPrice = 0; // Initialize total addon price

            if (addonsDiv) {
                // Clear the existing content
                addonsDiv.innerHTML = '';

                // Add a title to the addons section
                const addonsTitle = document.createElement('h3');
                addonsTitle.textContent = 'Addons for this reservation:';
                addonsTitle.classList.add('text-lg', 'font-semibold', 'text-gray-800');
                addonsDiv.appendChild(addonsTitle);

                // Check if addons is an array and contains items
                if (Array.isArray(data.addons) && data.addons.length > 0) {
                    data.addons.forEach(addon => {
                        const addonDiv = document.createElement('div');
                        addonDiv.id = `addon-${addon.addon_id}`;
                        addonDiv.classList.add('flex', 'items-center', 'justify-between', 'bg-gray-100', 'p-2', 'mt-2', 'rounded-lg', 'shadow-sm');

                        // Add addon name and price
                        const addonText = document.createElement('span');
                        addonText.textContent = `${addon.addon_name} - ₱${addon.addon_price}`;
                        addonText.classList.add('text-sm', 'font-medium', 'text-gray-700');
                        addonDiv.appendChild(addonText);

                        // Add the price of the addon to the total
                        totalAddonPrice += parseFloat(addon.addon_price);

                        // Create the "X" button to remove the addon
                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'X';
                        removeButton.classList.add('text-red-500', 'font-bold', 'hover:text-red-700', 'cursor-pointer');
                        removeButton.onclick = () => removeAddon(addon.addon_id);
                        addonDiv.appendChild(removeButton);

                        // Append the addon div to the container
                        addonsDiv.appendChild(addonDiv);
                    });
                } else {
                    // If no addons, display a message
                    const noAddonsMessage = document.createElement('p');
                    noAddonsMessage.textContent = 'No addons for this reservation.';
                    noAddonsMessage.classList.add('text-sm', 'text-gray-500');
                    addonsDiv.appendChild(noAddonsMessage);
                }
            } else {
                console.error('Addons display div not found');
            }

            // Calculate the total price (rate + addons)
            const totalPrice = window.ratePrice + totalAddonPrice;

            // Display the total price in the total price section with comma formatting
            const totalPriceDisplay = document.getElementById('total-price');
            if (totalPriceDisplay) {
                totalPriceDisplay.textContent = `₱${totalPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`; // Add commas to total price
            } else {
                console.error('Total Price display div not found');
            }

            // Update the total price in the database (optional)
            updateTotalPrice(reservationId, totalPrice);

        } else {
            console.error('Error fetching reservation details for addons:', data.message);
        }
    } catch (error) {
        console.error('Error fetching reservation addons details:', error);
    }
}

// Function to display the total price (on the page and in the console)


// Function to update the total price in the reservation
function updateTotalPrice(reservationId, totalPrice) {
    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    formData.append('total_price', totalPrice);

    fetch('../api/update_reservation_total.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Use .text() to inspect the raw response
    .then(text => {
        console.log('Raw response:', text); // Log the raw response
        try {
            const data = JSON.parse(text);  // Attempt to parse the response as JSON
            if (data.status === 'success') {
                console.log('Total price updated successfully');
            } else {
                console.error('Error updating total price:', data.message);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    })
    .catch(error => {
        console.error('Error updating total price:', error);
    });
}




// Function to load both rates and addons for a reservation
async function loadReservationData(reservationId) {
    console.log('Reservation ID:', reservationId);

    // Fetch and display rate details
    await loadRateDetails(reservationId);

    // Fetch and display addons
    await loadAddons(reservationId);
}





async function fetchReservedDates() {
    try {
        const response = await fetch("../api/get_reserved_dates_booking.php");
        const reservedDates = await response.json();

        if (!reservedDates || !reservedDates.reservedDaytime || !reservedDates.reservedNighttime || !reservedDates.reservedWholeDay) {
            console.error("Unexpected reserved dates structure:", reservedDates);
            return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
        }
        window.reservedDates = reservedDates; // Make reservedDates accessible globally
        return reservedDates;
    } catch (error) {
        console.error("Error fetching reserved dates:", error);
        return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
    }
}


function confirmAction() {
    const reservationId = localStorage.getItem("reservationID_admin"); // Get the reservation ID from localStorage
    const newRateId = document.getElementById('rateSelected').value; // Get the new selected rate ID

    // Save the selected addons
    if (reservationId) {
        saveAddons(reservationId);
        // Update the rate in the reservation
        updateRate(newRateId, reservationId);

        // Show the success message modal
        showSuccessMessage();

        // Reload the page after 2 seconds (or adjust time as needed)
        setTimeout(() => {
            location.reload(); // This will reload the page
        }, 2000); // 2000 milliseconds = 2 seconds
    } else {
        console.error('No reservation ID found');
    }
}


function showSuccessMessage() {
    const alertModal = document.getElementById('alert-modal');
    const alertMessage = document.getElementById('alert-message-modal');

    // Set the success message
    alertMessage.textContent = 'Invoice successfully modified';

    // Show the modal by removing the 'hidden' class
    alertModal.classList.remove('hidden');
    alertModal.classList.add('block'); // Make the modal visible

    // Optionally, hide it after a few seconds
    setTimeout(() => {
        alertModal.classList.remove('block');
        alertModal.classList.add('hidden'); // Hide the modal after 5 seconds
    }, 5000); // 5000 milliseconds = 5 seconds
}


// Function to save the selected addons to the reservation
function saveAddons(reservationId) {
    const selectedAddons = [];
    const addonDivs = document.querySelectorAll('#addonsDisplay .flex');
    addonDivs.forEach(addonDiv => {
        const addonId = addonDiv.id.split('-')[1]; // Extract addon_id from the div's id
        selectedAddons.push(addonId);
    });

    // Prepare the data for the POST request
    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    formData.append('addons', JSON.stringify(selectedAddons));

    fetch('../api/save_invoice.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Addons saved successfully');
            } else {
                console.error('Error saving addons:', data.message);
            }
        })
        .catch(error => {
            console.error('Error saving addons:', error);
        });
}

// Function to update the rate in the reservation
function updateRate(newRateId, reservationId) {
    // Prepare the data for the POST request to update the rate
    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    formData.append('new_rate_id', newRateId);

    fetch('../api/update_reservation_rate.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())  // Use .text() to inspect the raw response
        .then(text => {
            console.log('Raw response:', text); // Log the raw response
            try {
                const data = JSON.parse(text);  // Attempt to parse the response as JSON
                if (data.status === 'success') {
                    console.log('Rate updated successfully');
                    // Optionally, you can reload the rate details or update the UI
                    loadRateDetails(reservationId);  // Call the function to update the UI
                } else {
                    console.error('Error updating rate:', data.message);
                }
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        })
        .catch(error => {
            console.error('Error updating rate:', error);
        });
}

// Function to remove an addon from the list
function removeAddon(addonId) {
    console.log('Removing addon with ID:', addonId);

    // Remove the div with the corresponding addon ID
    const addonDiv = document.getElementById(`addon-${addonId}`);
    if (addonDiv) {
        addonDiv.remove();
    } else {
        console.error('Addon div not found.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Populate the addons dropdown
    populateAddons();

    // Event listener for the "Add" button
    document.getElementById('addAddonBtn').addEventListener('click', () => {
        const addonSelect = document.getElementById('addonsSelect');
        const selectedAddonId = addonSelect.value;
        const selectedAddonName = addonSelect.options[addonSelect.selectedIndex].text;

        if (selectedAddonId) {
            addAddonToList(selectedAddonId, selectedAddonName);
        } else {
            console.error('No addon selected');
        }
    });
});

// Function to add the selected addon to the list
function addAddonToList(addonId, addonName) {
    const addonsDisplay = document.getElementById('addonsDisplay');

    // Check if the addon is already in the list
    const existingAddon = document.getElementById(`addon-${addonId}`);
    if (existingAddon) {
        // Show error if the addon is already added
        showError('This addon has already been added to the list!');
        return; // Prevent adding the addon again
    }

    // Create a new div for the addon
    const addonDiv = document.createElement('div');
    addonDiv.id = `addon-${addonId}`;  // Set a unique ID based on the addon ID
    addonDiv.classList.add('flex', 'items-center', 'justify-between', 'bg-gray-100', 'p-2', 'mt-2', 'rounded-lg', 'shadow-sm');

    // Add the addon name
    const addonText = document.createElement('span');
    addonText.textContent = addonName;
    addonText.classList.add('text-sm', 'font-medium', 'text-gray-700');
    addonDiv.appendChild(addonText);

    // Create the "X" button to remove the addon
    const removeButton = document.createElement('button');
    removeButton.textContent = 'X';
    removeButton.classList.add('text-red-500', 'font-bold', 'hover:text-red-700', 'cursor-pointer');
    removeButton.onclick = () => removeAddon(addonId);
    addonDiv.appendChild(removeButton);

    // Append the addon div to the container
    addonsDisplay.appendChild(addonDiv);
}

// Function to show error message
function showError(message) {
    const modal = document.getElementById('info-alert-modal');
    const alertMessage = document.getElementById('alert-message-modal');

    // Set the error message
    alertMessage.textContent = message;

    // Make the modal visible
    modal.classList.remove('hidden');
    modal.classList.add('block');

    // Optionally, hide the modal after 5 seconds
    setTimeout(() => {
        modal.classList.remove('block');
        modal.classList.add('hidden');
    }, 10000); // Adjust the duration as needed
}

// Example of how to use the showError function
// This will show an error when the addon is already added.




  
