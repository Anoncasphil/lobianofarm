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

async function fetchDisabledDates() {
    try {
        const response = await fetch('../api/get_disabled_dates.php'); // Update the URL if necessary
        const disabledDates = await response.json();

        console.log("Fetched Disabled Dates:", disabledDates);

        if (!disabledDates || !Array.isArray(disabledDates.disableDates)) {
            console.error('Expected an array for disabled dates but received:', disabledDates);
            return [];
        }

        // Return the array of disabled dates
        return disabledDates.disableDates.map(item => item.date); // Ensure we are returning just an array of dates
    } catch (error) {
        console.error('Error fetching disabled dates:', error);
        return [];
    }
}

async function initializeFlatpickr(rateType) {
    const { reservedDaytime, reservedNighttime, reservedWholeDay } = await fetchReservedDates();
    const disabledDates = await fetchDisabledDates();  // Fetch disabled dates

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

    // Combine the disabled dates from both sources
    const allDisabledDates = disableDates.concat(disabledDates.map((date) => ({ from: date, to: date })));

    flatpickr("#check-out-date", {
        dateFormat: "Y-m-d"
    });

    flatpickr(checkInDateInput, {
        dateFormat: "Y-m-d",
        disable: allDisabledDates,  // Disable both reserved and fetched disabled dates
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
initializeFlatpickr();  // Use the appropriate rate type when calling this function



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

    // Populate text and input fields
    setValue("first-name-p", data.first_name);
    setValue("last-name-p", data.last_name);
    setValue("email-p", data.email);
    setValue("mobile-number-p", data.contact_number);

    // Ensure numeric values are properly parsed
    const totalPrice = parseFloat(data.total_price.replace(/,/g, '')) || 0;
    const validAmountPaid = parseFloat(data.valid_amount_paid.replace(/,/g, '')) || 0;
    const ratePrice = parseFloat(data.rate_price.replace(/,/g, '')) || 0;
    const extraPax = parseInt(data.extra_pax) || 0;
    const extraPaxPrice = parseFloat(data.extra_pax_price.replace(/,/g, '')) || 0;

    // Calculate the new total amount
    const newTotal = totalPrice - validAmountPaid;

    // Apply formatted text
    setText("invoice-date", data.invoice_date);
    setText("invoice-no", data.invoice_number);
    setText("total-price", formatCurrency(totalPrice));
    setText("valid_amount_paid", formatNegativeCurrency(validAmountPaid));
    setText("new_total_amount", formatCurrency(newTotal));

    // Display the rate name
    setText("rate-name", data.rate_name || "N/A");

    // Handle rates and addons in the invoice
    const invoiceItemsElement = document.getElementById("invoice-items");
    if (invoiceItemsElement) {
        let itemsHTML = "";

        // Render the rate details
        itemsHTML += `
            <tr>
                <td class="py-2 px-4">Rate</td>
                <td class="py-2 px-4">${data.rate_name || "N/A"}</td>
                <td class="py-2 px-4">${formatCurrency(ratePrice)}</td>
            </tr>
        `;

        // Render extra pax details if applicable
        if (extraPax > 0) {
            itemsHTML += `
                <tr>
                    <td class="py-2 px-4">Extra Pax</td>
                    <td class="py-2 px-4">${extraPax} person(s)</td>
                    <td class="py-2 px-4">${formatCurrency(extraPaxPrice)}</td>
                </tr>
            `;
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

  async function loadRateDetails(rateId) {
    try {
        const response = await fetch(`../api/get_reservations_rates_admin.php?reservation_id=${rateId}`);
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
            console.log('Check-in Date:', check_in_date);

            // Store the rate price for later use
            window.ratePrice = parseFloat(rate_price);

            // Check if the selected rate is already booked for the check-in date
            const isRateReserved = 
                (rate_type === 'Nighttime' && reservedDates.reservedDaytime.includes(check_in_date)) ||
                (rate_type === 'Daytime' && reservedDates.reservedNighttime.includes(check_in_date));

            const rateSelected = document.getElementById('rateSelected');
            if (!rateSelected) {
                console.error('Rate selection dropdown not found');
                return;
            }

            rateSelected.disabled = isRateReserved;
            if (isRateReserved) {
                showError('Cannot change rate. The rate is already booked for this date.');
                return;
            }

            // Fetch all rates
            const allRatesResponse = await fetch('../api/get_rates.php');
            if (!allRatesResponse.ok) {
                throw new Error('Failed to fetch all rates');
            }
            const allRatesData = await allRatesResponse.json();
            
            if (allRatesData.status === 'success' && Array.isArray(allRatesData.rates)) {
                console.log('Rate dropdown found');

                // Clear existing options
                rateSelected.innerHTML = '';

                // Convert rate_id to string to ensure consistency
                const rateIdStr = String(rate_id);

                // Populate the dropdown with all available rates
                let rateFound = false;
                allRatesData.rates.forEach(rate => {
                    const rateOption = document.createElement('option');
                    rateOption.value = String(rate.id); // Ensure ID is a string
                    rateOption.textContent = rate.name;
                    rateSelected.appendChild(rateOption);

                    if (String(rate.id) === rateIdStr) {
                        rateFound = true;
                    }
                });

                // If the rate is missing, add it manually
                if (!rateFound) {
                    console.warn(`Rate ID ${rateIdStr} not found in available rates. Adding it manually.`);
                    
                    const missingRateOption = document.createElement('option');
                    missingRateOption.value = rateIdStr;
                    missingRateOption.textContent = `${rate_name} (Not in list)`;
                    missingRateOption.selected = true;
                    rateSelected.appendChild(missingRateOption);
                } else {
                    rateSelected.value = rateIdStr;
                }

                console.log('Rate options populated');
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
        // Fetch reservation details
        const response = await fetch(`../api/get_reservations_rates_admin.php?reservation_id=${rateId}`);
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();
        if (data.status !== 'success' || !data.reservation) {
            console.error("Error fetching reservation details:", data.message);
            return;
        }

        const { rate_id, rate_name, rate_price, rate_type, check_in_date } = data.reservation;
        console.log("Fetched Rate ID from Reservation API:", rate_id);

        // Store the rate price for later use
        window.ratePrice = parseFloat(rate_price);

        // Fetch all available rates
        const allRatesResponse = await fetch('../api/get_rates.php');
        if (!allRatesResponse.ok) throw new Error('Failed to fetch all rates');

        const allRatesData = await allRatesResponse.json();
        if (allRatesData.status !== 'success' || !Array.isArray(allRatesData.rates)) {
            console.error("Error fetching available rates");
            return;
        }

        // Log the fetched rates for debugging
        console.log("Fetched Rates:", allRatesData.rates);

        // Extract available rate IDs
        const availableRateIds = allRatesData.rates.map(rate => String(rate.id));
        console.log("Available Rate IDs:", availableRateIds);

        // Check if the rate ID exists in available rates
        const rateExists = availableRateIds.includes(String(rate_id));

        const rateSelected = document.getElementById('rateSelected');
        const rateDisplay = document.getElementById('rateDisplay');
        if (!rateSelected || !rateDisplay) {
            console.error('Rate selection dropdown or display element not found');
            return;
        }

        // Clear existing options
        rateSelected.innerHTML = '';

        // Populate the dropdown with available rates
        allRatesData.rates.forEach(rate => {
            const rateOption = document.createElement('option');
            rateOption.value = rate.id;
            rateOption.textContent = rate.name;
            rateSelected.appendChild(rateOption);
        });

        // Set the selected rate
        if (rateExists) {
            rateSelected.value = String(rate_id);
        } else {
            console.warn(`Rate ID ${rate_id} not found in available rates`);

            // Force adding the missing rate
            const missingRateOption = document.createElement('option');
            missingRateOption.value = rate_id;
            missingRateOption.textContent = `${rate_name} (Not in list)`;
            rateSelected.appendChild(missingRateOption);
            rateSelected.value = rate_id;
        }

        console.log('Rate options populated successfully');

        // Check if the selected rate conflicts with existing reservations
        if (isRateAlreadyBooked(rate_type, check_in_date)) {
            showError("Conflict detected: Rate selection disabled.");

            // Disable the dropdown and show the rate in a read-only format
            rateSelected.style.display = 'none'; // Hide the dropdown
            rateDisplay.style.display = 'inline'; // Show the display element
            rateDisplay.textContent = rate_name; // Display the rate name

            showError("Cannot change rate. The other rate type is already booked for this date.");
        } else {
            // Enable the dropdown and hide the display element
            rateSelected.style.display = 'inline'; // Show the dropdown
            rateDisplay.style.display = 'none'; // Hide the display element
            rateSelected.disabled = false;

                // Hide the rate input field and display the rate name
            const rateInput = document.getElementById('rateInput');
            if (rateInput) {
            rateInput.style.display = 'none'; // Hide the input field when there's no conflict
            }

        }

        // Fetch and display add-ons
        await loadAddons(rate_id);
    } catch (error) {
        console.error("Error in loadRateDetails:", error);
    }
}
/**
/**
 * Checks if the selected rate type conflicts with an existing reservation.
 * Disables the selection dropdown if a conflict is found.
 * @param {string} rateType - The selected rate type ('Daytime' or 'Nighttime').
 * @param {string} checkInDate - The reservation date.
 * @returns {boolean} - Returns true if there is a conflict.
 */
function isRateAlreadyBooked(rateType, checkInDate) {
    // Assuming `reservedDates` is a global object that contains reserved dates for each rate type
    if (
        (rateType === 'Nighttime' && reservedDates.reservedDaytime.includes(checkInDate)) ||
        (rateType === 'Daytime' && reservedDates.reservedNighttime.includes(checkInDate))
    ) {
        console.warn("Conflict detected: Rate selection disabled.");
        return true;
    }
    return false;
}





// Function to fetch and display reservation addons
async function loadAddons(reservationId) {
    try {
        console.log('Fetching addons for reservation ID:', reservationId);
        
        const response = await fetch(`../api/get_reservation_rates_id.php?reservation_id=${reservationId}`);
        console.log('API Request URL:', response.url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Addons API Response:', data);

        if (data.status === 'success' && data.addons) {
            const addonsDiv = document.getElementById('addonsDisplay');
            console.log('Addons Display Div:', addonsDiv);

            let totalAddonPrice = 0;

            if (addonsDiv) {
                addonsDiv.innerHTML = '';

                const addonsTitle = document.createElement('h3');
                addonsTitle.textContent = 'Addons for this reservation:';
                addonsTitle.classList.add('text-lg', 'font-semibold', 'text-gray-800');
                addonsDiv.appendChild(addonsTitle);

                if (Array.isArray(data.addons) && data.addons.length > 0) {
                    data.addons.forEach(addon => {
                        const addonDiv = document.createElement('div');
                        addonDiv.id = `addon-${addon.addon_id}`;
                        addonDiv.classList.add('flex', 'items-center', 'justify-between', 'bg-gray-100', 'p-2', 'mt-2', 'rounded-lg', 'shadow-sm');

                        const addonText = document.createElement('span');
                        addonText.textContent = `${addon.addon_name} - ₱${addon.addon_price}`;
                        addonText.classList.add('text-sm', 'font-medium', 'text-gray-700');
                        addonDiv.appendChild(addonText);

                        totalAddonPrice += parseFloat(addon.addon_price);

                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'X';
                        removeButton.classList.add('text-red-500', 'font-bold', 'hover:text-red-700', 'cursor-pointer');
                        removeButton.onclick = () => removeAddon(addon.addon_id);
                        addonDiv.appendChild(removeButton);

                        addonsDiv.appendChild(addonDiv);
                    });
                } else {
                    const noAddonsMessage = document.createElement('p');
                    noAddonsMessage.textContent = 'No addons for this reservation.';
                    noAddonsMessage.classList.add('text-sm', 'text-gray-500');
                    addonsDiv.appendChild(noAddonsMessage);
                }
            } else {
                console.error('Addons display div not found');
            }

            const totalPrice = window.ratePrice + totalAddonPrice;
            const totalPriceDisplay = document.getElementById('total-price');
            
            if (totalPriceDisplay) {
                totalPriceDisplay.textContent = `₱${totalPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
            } else {
                console.error('Total Price display div not found');
            }

            updateTotalPrice(reservationId, totalPrice);
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

        // Store success message in localStorage
        localStorage.setItem('successMessage', 'Invoice successfully modified');

        closeAllModals();

        // Reload the page after closing modals (with a slight delay to allow closing effect)
        setTimeout(() => {
            location.reload(); 
            
        }, 500); // 500 milliseconds for smooth transition (adjust if necessary)
    } else {
        console.error('No reservation ID found');
    }
}


// Function to close all modals
function closeAllModals() {
    // Get the modals by their IDs
    const editModal = document.getElementById('editModal');
    const validationModal = document.getElementById('validation-modal');
    
    // Add 'hidden' class to hide modals
    if (editModal && !editModal.classList.contains('hidden')) {
        editModal.classList.add('hidden'); // Close the edit modal
    }

    if (validationModal && !validationModal.classList.contains('hidden')) {
        validationModal.classList.add('hidden'); // Close the validation modal
    }
}


function showSuccessMessage() {
    const alertModal = document.getElementById('alert-modal');
    const alertMessage = document.getElementById('alert-message-modal');
    
    // Get the success message from localStorage
    const successMessage = localStorage.getItem('successMessage');
    const errorMessage = localStorage.getItem('updateReservationError');
    
    // Check for the success message first
    if (successMessage) {
        alertMessage.textContent = successMessage; // Set the success message text
        
        // Show the alert modal
        if (alertModal) {
            alertModal.classList.remove('hidden'); // Show the modal
        }
        
        // Remove the success message from localStorage after 5 seconds
        setTimeout(() => {
            localStorage.removeItem('successMessage');  // Remove success message from localStorage
            alertModal.classList.add('hidden');         // Hide the alert modal after 5 seconds
        }, 5000); // 5000 milliseconds = 5 seconds
    }
    // If there's an error message, show it instead
    else if (errorMessage) {
        alertMessage.textContent = errorMessage; // Set the error message text
        
        // Show the alert modal
        if (alertModal) {
            alertModal.classList.remove('hidden'); // Show the modal
        }
        
        // Remove the error message from localStorage after 5 seconds
        setTimeout(() => {
            localStorage.removeItem('updateReservationError');  // Remove error message from localStorage
            alertModal.classList.add('hidden');                 // Hide the alert modal after 5 seconds
        }, 5000); // 5000 milliseconds = 5 seconds
    }
}

// Check if there's a success or error message in localStorage when the page loads
document.addEventListener('DOMContentLoaded', () => {
    showSuccessMessage(); // Display the message (if exists)
});



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

function updateRate(newRateId, reservationId) {
    console.log('Updating rate...');
    console.log('New Rate ID:', newRateId, 'Type:', typeof newRateId);
    console.log('Reservation ID:', reservationId, 'Type:', typeof reservationId);

    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    formData.append('new_rate_id', newRateId);

    fetch('../api/update_reservation_rate.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Inspect raw response
    .then(text => {
        console.log('Raw response:', text); // Log raw response
        try {
            const data = JSON.parse(text);
            if (data.status === 'success') {
                console.log('Rate updated successfully');
                loadRateDetails(reservationId);
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
    const modal = document.getElementById('info-error-modal');
    const alertMessage = document.getElementById('error-message-modal');

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

// Function to populate the status dropdown with all possible status options
// Function to populate the status dropdown with all possible status options
async function populateStatusDropdown(reservationId) {
    try {
        // Fetch reservation details from the server
        const response = await fetch(`../api/get_reservations_status.php?id=${reservationId}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const data = await response.json();

        if (data.status) {
            // Get the status dropdown element
            const statusDropdown = document.getElementById("status-dropdown");

            // Define all possible status options
            const statuses = ["Pending", "Confirmed", "Completed", "Cancelled"];

            // Populate the dropdown with the status options
            statuses.forEach(status => {
                const option = document.createElement("option");
                option.value = status.toLowerCase();
                option.textContent = status;
                statusDropdown.appendChild(option);
            });

            // Set the selected status in the dropdown based on the fetched status
            const status = data.status || "Pending"; // Default to Pending if no status is found
            statusDropdown.value = status.toLowerCase(); // Ensure the dropdown matches the fetched status

            // Get the selected status element
            const selectedStatusElement = document.getElementById("selected-status");

            // Define dynamic background color based on status
            let bgColorClass = '';
            switch (status) {
                case "Pending":
                    bgColorClass = 'bg-orange-500 text-white ';
                    break;
                case "Confirmed":
                    bgColorClass = 'bg-green-600 text-white';
                    break;
                case "Completed":
                    bgColorClass = 'bg-blue-600 text-white';
                    break;
                case "Cancelled":
                    bgColorClass = 'bg-red-600 text-white';
                    break;
                default:
                    bgColorClass = 'bg-gray-600 text-white';
                    break;
            }

            // Update the status display with the appropriate class
            selectedStatusElement.innerHTML = `Current Status: <span class='${bgColorClass} text-xs font-medium me-2 px-2 py-2 rounded-full'>
                                                 ${status}
                                               </span>`;

            console.log("Dropdown Updated with Status:", status);
        } else {
            console.error("Error: No status returned", data.error);
        }
    } catch (error) {
        console.error("Error fetching reservation details:", error);
    }
}


// Call the function to load reservation details
const reservationId = localStorage.getItem("reservationID_admin");
console.log("Reservation ID from localStorage:", reservationId);

if (reservationId) {
    populateStatusDropdown(reservationId);
} else {
    console.error("No reservation ID found in localStorage.");
}




document.addEventListener("DOMContentLoaded", function () {
    const submitButton = document.getElementById("applyButton");
    const confirmButton = document.getElementById("submitBTN");
    const modal = document.getElementById("submit-validation");

    // Show modal when clicking the submit button
    submitButton.addEventListener("click", function () {
        console.log("Submit button clicked");
        if (modal) {
            modal.classList.remove("hidden");
            modal.style.display = "flex";
        } else {
            console.error("❌ Modal element not found");
        }
    });

    // Hide modal when clicking the cancel button
    document.querySelector("[data-modal-hide='no-validation']").addEventListener("click", function () {
        if (modal) {
            modal.classList.add("hidden");
            modal.style.display = "none";
        }
    });

    confirmButton.addEventListener("click", async function () {
        if (modal) {
            modal.classList.add("hidden");
            modal.style.display = "none";
        }

        // Retrieve reservationId from localStorage
        let reservationId = localStorage.getItem("reservationID_admin");

        if (!reservationId) {
            alert("❌ Reservation ID not found. Please try again.");
            return;
        }

        const reservationData = {
            reservation_id: reservationId, 
            check_in_date: document.getElementById("check-in-date").value,
            check_out_date: document.getElementById("check-out-date").value,
            status: document.getElementById("status-dropdown").value,
            first_name: document.getElementById("first-name-p").value,
            last_name: document.getElementById("last-name-p").value,
            email: document.getElementById("email-p").value,
            mobile_number: document.getElementById("mobile-number-p").value
        };

        // Check for missing fields
        if (!reservationData.check_in_date || !reservationData.check_out_date || !reservationData.status || 
            !reservationData.first_name || !reservationData.last_name || !reservationData.email || !reservationData.mobile_number) {
            alert("❌ Please fill in all fields.");
            return;
        }

        try {
            console.log("🔄 Sending reservation update...");

            const response = await fetch("../api/submit_reservation.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(reservationData)
            });

            const result = await response.json();

            if (result.status === "success") {
                console.log("✅ Reservation updated successfully!");

                // Reload the page immediately after success
                location.reload();

                // Send email after the reload without waiting
                sendEmail(reservationId).catch((error) => {
                    console.error("❌ Error sending email:", error);
                });

            } else {
                console.error("❌ Reservation update failed:", result.message);
                alert("❌ Error: " + result.message);
            }
        } catch (error) {
            console.error("❌ Error updating reservation:", error);
            alert("❌ An error occurred. Please try again.");
        }
    });


    async function sendEmail(reservationId) {
        try {
            // Retrieve form data again
            let checkInDate = document.getElementById("check-in-date").value;
            let checkOutDate = document.getElementById("check-out-date").value;
            let status = document.getElementById("status-dropdown").value;
            let email = document.getElementById("email-p").value;
            let mobileNumber = document.getElementById("mobile-number-p").value;
            let firstName = document.getElementById("first-name-p").value;
            let lastName = document.getElementById("last-name-p").value;
    
            // Log the data being sent
            console.log("📨 Sending Data:", JSON.stringify({ 
                reservation_id: reservationId,
                check_in_date: checkInDate,
                check_out_date: checkOutDate,
                status: status,
                email: email,
                mobile_number: mobileNumber,
                first_name: firstName,
                last_name: lastName
            }));
    
            let response = await fetch("../landing_page_customer/email_status_send.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ 
                    reservation_id: reservationId,
                    check_in_date: checkInDate,
                    check_out_date: checkOutDate,
                    status: status,
                    email: email,
                    mobile_number: mobileNumber,
                    first_name: firstName,
                    last_name: lastName
                })
            });
    
            let text = await response.text();
            try {
                var data = JSON.parse(text); 
            } catch (e) {
                console.error("❌ JSON Parsing Error:", text);
                return;
            }
    
            if (data.status === "success") {
                console.log("✅ Email sent successfully.");
            } else {
                console.error("❌ Email sending failed:", data.message);
            }
        } catch (error) {
            console.error("❌ Error sending email:", error);
        }
    }
    

    
});




document.addEventListener("DOMContentLoaded", function () {
    let reservationId = localStorage.getItem("reservationID_admin");

    if (!reservationId) {
        console.error("No reservation ID found in localStorage.");
        return;
    }

    fetch(`../api/get_reschedule_request.php?reservation_id=${reservationId}`)
        .then(response => response.json())
        .then(data => {
            console.log("API Response:", data); // Debugging output

            let rescheduleRequestDiv = document.getElementById("reschedule-request");

            if (data.status === "success" && data.reschedule_request) {
                console.log("Reschedule request found:", data.reschedule_request); // Debugging output

                if (data.reschedule_request.status === "Approved") {
                    rescheduleRequestDiv.classList.add("hidden");
                    console.log("Hiding section because request is Approved");
                    return;
                }

                rescheduleRequestDiv.classList.remove("hidden");

                let message = `Customer requested a reschedule from <strong>${data.reschedule_request.check_in_date}</strong> 
                               to <strong>${data.reschedule_request.check_out_date}</strong>.`;
                document.getElementById("reschedule-message").innerHTML = message;

                document.getElementById("acceptRequest").onclick = function () {
                    updateRescheduleStatus(data.reschedule_request.request_id, 'Approved');
                };
                document.getElementById("declineRequest").onclick = function () {
                    updateRescheduleStatus(data.reschedule_request.request_id, 'Denied');
                };
            } else {
                console.log("No reschedule request found. Hiding section.");
                rescheduleRequestDiv.classList.add("hidden");
            }
        })
        .catch(error => console.error("Error fetching reschedule request:", error));
});

function updateRescheduleStatus(requestId, status) {
    fetch('../api/update_reschedule_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ request_id: requestId, status: status })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status !== "success") {
            throw new Error(data.message || "Failed to update reschedule status.");
        }
        console.log("Update success, proceeding to send email...");

        return fetch('../api/send_email_reschedule.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ request_id: requestId, status: status })
        });
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Email API HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(emailData => {
        console.log("Email API Response:", emailData);

        if (emailData.status === "success") {
            alert("Email notification sent!");
            localStorage.setItem("rescheduleSuccessMessage", `Reschedule request ${status.toLowerCase()} successfully!`);
        } else {
            alert("Reschedule updated, but email sending failed.");
            localStorage.setItem("rescheduleErrorMessage", emailData.message || "Reschedule updated but failed to send email.");
        }

        console.log("Reloading page...");
        location.reload(); // Removed the 10-second delay
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while processing the request.");
        localStorage.setItem("rescheduleErrorMessage", error.message || "An error occurred.");
        location.reload();
    });
}




document.addEventListener("DOMContentLoaded", function () {
    // Get the reservation ID from localStorage
    let reservationId = localStorage.getItem("reservationID_admin");

    if (!reservationId) {
        console.error("No reservation ID found in localStorage.");
        return;
    }

    // Fetch the reservation code from the database using the reservation ID
    fetch(`../api/get_reservation_code.php?reservation_id=${reservationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success" && data.reservation_code) {
                // Update the reservation code in the HTML
                document.getElementById("reservation-code").innerText = data.reservation_code;
            } else {
                console.error("Reservation code not found.");
            }
        })
        .catch(error => console.error("Error fetching reservation code:", error));
});