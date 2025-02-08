document.querySelectorAll('.peer').forEach(input => {
  const label = input.nextElementSibling;

  input.addEventListener('focus', () => {
    label.classList.add('top-0', 'mt-3');
    label.classList.remove('top-1/2');
  });

  input.addEventListener('blur', () => {
    if (input.value === '') {
      label.classList.remove('top-0', 'mt-3');
      label.classList.add('top-1/2');
    }
  });

  // Initial check in case the field already has a value
  if (input.value !== '') {
    label.classList.add('top-0', 'mt-3');
    label.classList.remove('top-1/2');
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Check if any input already has a value and trigger the label float
  const inputs = document.querySelectorAll('.peer');
  
  inputs.forEach(input => {
    if (input.value !== "") {
      const label = input.nextElementSibling;
      label.classList.add("peer-focus");
    }
  });
});


  document.addEventListener('DOMContentLoaded', function () {
    // Function to open the modal with animation
    function openModal(rate) {
      const modal = document.getElementById('modal');
      const modalTitle = document.getElementById('modalTitle');
      const modalDescription = document.getElementById('modalDescription');
      const modalPrice = document.getElementById('modalPrice');
  
      console.log("openModal triggered with rate: ", rate); // Log when openModal is triggered
  
      // Set the modal content based on the clicked card
      if (rate === 'rate1') {
        modalTitle.textContent = 'Rate 1 Details';
        modalDescription.textContent = 'Detailed description for Rate 1.';
        modalPrice.textContent = '$50';
      } else if (rate === 'rate2') {
        modalTitle.textContent = 'Rate 2 Details';
        modalDescription.textContent = 'Detailed description for Rate 2.';
        modalPrice.textContent = '$75';
      } else if (rate === 'rate3') {
        modalTitle.textContent = 'Rate 3 Details';
        modalDescription.textContent = 'Detailed description for Rate 3.';
        modalPrice.textContent = '$100';
      }
  
      // Show the modal with animation by adding 'show' class
      modal.classList.add('show');
      console.log("Modal class added: ", modal.classList); // Log the modal's classes after adding 'show'
    }
  
    // Function to close the modal with animation
    function closeModal() {
      const modal = document.getElementById('modal');
  
      console.log("closeModal triggered"); // Log when closeModal is triggered
  
      // Remove the 'show' class to trigger the closing animation
      modal.classList.remove('show');
      console.log("Modal class removed: ", modal.classList); // Log the modal's classes after removing 'show'
    }
  
    // Expose the functions globally
    window.openModal = openModal;
    window.closeModal = closeModal;
  });
  
  currentSlide = 1;

function moveCarousel(direction) {
  const carousel = document.getElementById('carousel');
  const totalCards = carousel.children.length;
  const cardWidth = carousel.children[0].offsetWidth; // Get the width of a card
  const maxSlides = totalCards - 3; // Allow for only 3 cards visible at a time

  currentSlide += direction;

  // Ensure the currentSlide stays within bounds
  if (currentSlide < 0) {
    currentSlide = 0;
  } else if (currentSlide > maxSlides) {
    currentSlide = maxSlides;
  }

  // Move the carousel by updating the transform property
  carousel.style.transform = `translateX(-${currentSlide * cardWidth}px)`;
}


  document.addEventListener("DOMContentLoaded", function () {
    fetch("populate_user_data.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Populate fields with user data
          document.getElementById("first-name").value = data.data.first_name || "";
          document.getElementById("last-name").value = data.data.last_name || "";
          document.getElementById("email").value = data.data.email || "";
          document.getElementById("mobile-number").value = data.data.contact_no || "";
        } else {
          console.error("Error fetching user data:", data.message);
        }
      })

  });

  let bookedRates = [];  // Global variable to store booked rates
  let selectedRate = null;  // Track selected rate
let selectedAddons = {};  // Store selected addons

window.addEventListener("load", function () {
  const checkInDate = document.getElementById("check-in-date");
  const selectedDate = checkInDate.value;

  if (selectedDate) {
      fetch(`../api/fetch_reserved_rates.php?date=${selectedDate}`)
          .then(response => response.json())
          .then(data => {
              bookedRates = data;  // Store the booked rates globally
              console.log("Booked Rates:", bookedRates); // Debugging output
              const allRateCards = Array.from(document.getElementsByClassName('rate-card'));

              // Filter out the booked rate cards
              const availableRateCards = allRateCards.filter(card => {
                  const rateId = parseInt(card.getAttribute("data-id")); // Convert to integer
                  return !bookedRates.includes(rateId); // Only include the cards that are not booked
              });

              console.log("Available Rate Cards:", availableRateCards); // Debugging output

              // Enable available rate cards
              availableRateCards.forEach(card => {
                  const selectButton = card.querySelector(".select-button");
                  card.classList.remove("opacity-50", "cursor-not-allowed");
                  selectButton.disabled = false;
                  selectButton.classList.remove("bg-gray-400", "hover:bg-gray-400", "cursor-not-allowed");
                  selectButton.classList.add("bg-blue-600", "hover:bg-blue-700");
              });

              // Disable booked rate cards
              allRateCards.forEach(card => {
                  const rateId = parseInt(card.getAttribute("data-id"));
                  if (bookedRates.includes(rateId)) {
                      card.classList.add("opacity-50", "cursor-not-allowed");
                      const selectButton = card.querySelector(".select-button");
                      selectButton.disabled = true;
                      selectButton.classList.add("bg-gray-400", "hover:bg-gray-400", "cursor-not-allowed");
                      selectButton.classList.remove("bg-blue-600", "hover:bg-blue-700");
                  }
              });
          })
          .catch(error => console.error("Error fetching booked rates:", error));
  }

  // Add event listener to the check-in date field
  checkInDate.addEventListener("change", function () {
      const selectedDate = this.value;

      if (!selectedDate) return;

      // Remove all selected rates when the date changes
      const selectedItems = document.querySelectorAll("#selected-items li");
      selectedItems.forEach(item => {
          const rateId = item.getAttribute("data-id");
          removeRate(rateId);  // Call removeRate for each selected rate
      });

      fetch(`../api/fetch_reserved_rates.php?date=${selectedDate}`)
          .then(response => response.json())
          .then(data => {
              bookedRates = data;  // Store the booked rates globally
              console.log("Booked Rates:", bookedRates); // Debugging output
              const allRateCards = Array.from(document.getElementsByClassName('rate-card'));

              // Filter out the booked rate cards
              const availableRateCards = allRateCards.filter(card => {
                  const rateId = parseInt(card.getAttribute("data-id")); // Convert to integer
                  return !bookedRates.includes(rateId); // Only include the cards that are not booked
              });

              console.log("Available Rate Cards:", availableRateCards); // Debugging output

              // Enable available rate cards
              availableRateCards.forEach(card => {
                  const selectButton = card.querySelector(".select-button");
                  card.classList.remove("opacity-50", "cursor-not-allowed");
                  selectButton.disabled = false;
                  selectButton.classList.remove("bg-gray-400", "hover:bg-gray-400", "cursor-not-allowed");
                  selectButton.classList.add("bg-blue-600", "hover:bg-blue-700");
              });

              // Disable booked rate cards
              allRateCards.forEach(card => {
                  const rateId = parseInt(card.getAttribute("data-id"));
                  if (bookedRates.includes(rateId)) {
                      card.classList.add("opacity-50", "cursor-not-allowed");
                      const selectButton = card.querySelector(".select-button");
                      selectButton.disabled = true;
                      selectButton.classList.add("bg-gray-400", "hover:bg-gray-400", "cursor-not-allowed");
                      selectButton.classList.remove("bg-blue-600", "hover:bg-blue-700");
                  }
              });
          })
          .catch(error => console.error("Error fetching booked rates:", error));
  });
});



function selectRate(id, name, price) {
    const selectedItems = document.getElementById("selected-items");
    const selectedPrices = document.getElementById("selected-prices");
    const allRateCards = document.getElementsByClassName('rate-card');

    // Filter out the booked rate cards
    const availableRateCards = Array.from(allRateCards).filter(card => {
        const rateId = parseInt(card.getAttribute("data-id")); // Convert to integer
        return !bookedRates.includes(rateId); // Only include the cards that are not booked
    });

    let rateCard = null;
    // Find the card for the selected rate ID
    for (let card of allRateCards) {
        if (card.getAttribute('data-id') === id.toString()) {
            rateCard = card;
            break;
        }
    }

    if (!rateCard) {
        console.error(`Rate card with id ${id} not found!`);
        return;
    }

    const selectButton = rateCard.getElementsByClassName('select-button')[0];

    // Deselect previous rate if any
    if (selectedRate) {
        const rateItem = document.querySelector(`#selected-items li[data-id='${selectedRate.id}']`);
        const priceItem = document.querySelector(`#selected-prices li[data-id='${selectedRate.id}']`);
        if (rateItem && priceItem) {
            rateItem.remove();
            priceItem.remove();
        }
        // Remove the previous hidden input for the rate ID if any
        const hiddenInput = document.querySelector(`input[name="rate_id"][value="${selectedRate.id}"]`);
        if (hiddenInput) {
            hiddenInput.remove();
        }
    }

    // If rate is already selected, unselect it
    if (selectedRate && selectedRate.id === id) {
        unselectRate(selectButton, id, availableRateCards);
    } else {
        selectedRate = { id, name, price };  // Set the selected rate correctly

        // Add rate to the summary
        selectedItems.innerHTML += `<li data-id="${id}">${name} <button onclick="removeRate(${id})"></button></li>`;
        selectedPrices.innerHTML += `<li data-id="${id}">₱${price}</li>`;

        // Create and append hidden input for rateId (optional if you want to store it in a form)
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "rate_id"; // Ensure this name matches the expected form name
        hiddenInput.value = id; // Set the rateId value
        document.getElementById("summary-form").appendChild(hiddenInput);

        // Change button to 'Unselect'
        selectButton.innerText = "Unselect";
        selectButton.classList.remove('bg-blue-600');
        selectButton.classList.add('bg-red-600');

        // Disable other rate cards
        for (let card of availableRateCards) {
            if (card.getAttribute('data-id') !== id.toString()) {
                card.classList.add('opacity-50', 'cursor-not-allowed');
                const button = card.getElementsByClassName('select-button')[0];
                button.disabled = true;
            }
        }
    }

    // Update the total price and store selections
    updateTotalPrice();

    // Capture and store rateId in the hidden field (this will be passed to storeSelections)
    const rateIdField = document.getElementById('rate-id-field');
    rateIdField.value = id; // Store the selected rateId in the hidden field

    // Call storeSelections after selecting the rate
    storeSelections(); // This function will now capture and store all the data
}

function unselectRate(selectButton, id, availableRateCards) {
    // Reset selectedRate to null
    selectedRate = null;

    // Change button to 'Select'
    selectButton.innerText = "Select";
    selectButton.classList.remove('bg-red-600');
    selectButton.classList.add('bg-blue-600');

    // Re-enable all rate cards
    for (let card of availableRateCards) {
        card.classList.remove('opacity-50', 'cursor-not-allowed');
        const button = card.getElementsByClassName('select-button')[0];
        button.disabled = false; // Enable the button
    }

    // Remove rate from the summary
    const rateItem = document.querySelector(`#selected-items li[data-id='${id}']`);
    const priceItem = document.querySelector(`#selected-prices li[data-id='${id}']`);
    if (rateItem && priceItem) {
        rateItem.remove();
        priceItem.remove();
    }

    // Remove hidden input for the unselected rate
    document.querySelector(`input[name="rate_id"][value="${id}"]`)?.remove();

    // Clear the check-out date and times if no rate is selected
    if (!selectedRate) {
        document.getElementById("check-out-date").value = "";
        document.getElementById("check-in-time").value = "";
        document.getElementById("check-out-time").value = "";
    }

    // Update the total price
    updateTotalPrice();
}

  
  
  function removeRate(id) {
    const selectedItems = document.getElementById("selected-items");
    const selectedPrices = document.getElementById("selected-prices");
  
    // Remove the rate from the summary (both name and price)
    const rateItem = document.querySelector(`#selected-items li[data-id='${id}']`);
    const priceItem = document.querySelector(`#selected-prices li[data-id='${id}']`);
    if (rateItem && priceItem) {
      rateItem.remove();
      priceItem.remove();
    }
  
    // Remove hidden input for the removed rate
    const hiddenInput = document.querySelector(`input[name="rate_id"][value="${id}"]`);
    if (hiddenInput) {
      hiddenInput.remove();
    }
  
    // Re-enable the rate card
    const rateCard = document.querySelector(`.rate-card[data-id='${id}']`);
    const selectButton = rateCard.getElementsByClassName('select-button')[0];
    selectButton.innerText = "Select";
    selectButton.classList.remove('bg-red-600');
    selectButton.classList.add('bg-blue-600');
    rateCard.classList.remove('opacity-50', 'cursor-not-allowed');
  
    // Reset selectedRate
    selectedRate = null;
  
    // Update the total price
    updateTotalPrice();
  }
  
  
  function toggleAddonSelection(id, name, price) {
  
      const addonCard = document.querySelector(`[data-id="${id}"]`);
      const addonButton = addonCard ? addonCard.querySelector('.select-button') : null;
  
      // Check if addonCard exists
      if (!addonCard || !addonButton) {
          console.error(`Addon card or button with id ${id} not found!`);
          return;
      }
  
      if (selectedAddons[id]) {
          // Deselect the addon
          delete selectedAddons[id];
          addonCard.classList.remove('bg-red-500');
          addonButton.classList.remove('bg-red-600');
          addonButton.innerText = "Select";
          document.querySelector(`input[name="addon_id_${id}"]`)?.remove();
      } else {
          // Select the addon
          selectedAddons[id] = { name, price };
          addonCard.classList.add('bg-red-500');
          addonButton.classList.add('bg-red-600');
          addonButton.innerText = "Unselect";
          const hiddenInput = document.createElement("input");
          hiddenInput.type = "hidden";
          hiddenInput.name = `addon_id_${id}`;
          hiddenInput.value = id;
          document.getElementById("summary-form").appendChild(hiddenInput);
      }
  
      // Update the summary and total price
      updateSummary();
  }
  
  function updateSummary() {
    const itemsList = document.getElementById('selected-items');
    const pricesList = document.getElementById('selected-prices');
    const totalPriceElement = document.getElementById('total-price');
    let total = 0;

    // Clear current summary
    itemsList.innerHTML = '';
    pricesList.innerHTML = '';

    // Add selected rate to the summary
    if (selectedRate) {
        const ratePrice = parseFloat(selectedRate.price.replace(/,/g, '')); // Remove commas before parsing
        itemsList.innerHTML += `<li data-id="${selectedRate.id}">${selectedRate.name}</li>`;
        pricesList.innerHTML += `<li data-id="${selectedRate.id}">₱${ratePrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</li>`;
        total += ratePrice;
    }

    // Add selected addons to the summary
    for (let id in selectedAddons) {
        const addon = selectedAddons[id];
        const addonPrice = parseFloat(addon.price.replace(/,/g, '')); // Remove commas before parsing
        itemsList.innerHTML += `<li>${addon.name}</li>`;
        pricesList.innerHTML += `<li>₱${addonPrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</li>`;
        total += addonPrice;
    }

    // Update total price
    totalPriceElement.textContent = '₱' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Update hidden fields with the current selection
    document.getElementById('rate-id-field').value = selectedRate ? selectedRate.id : '';
    document.getElementById('addon-ids-field').value = Object.keys(selectedAddons).join(',');
}

  // Function to update the total price
  function updateTotalPrice() {
      const totalPriceElement = document.getElementById('total-price');
      let total = 0;
  
      // Add selected rate price to the total
      if (selectedRate) {
          const ratePrice = parseFloat(selectedRate.price.replace(/,/g, '')); // Remove commas before parsing
          total += ratePrice;
      }
  
      // Add selected addon prices to the total
      for (let id in selectedAddons) {
          const addon = selectedAddons[id];
          const addonPrice = parseFloat(addon.price.replace(/,/g, '')); // Remove commas before parsing
          total += addonPrice;
      }
  
      // Update the total price display with commas
      totalPriceElement.textContent = '₱' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  
  function validateForm() {
    // Get the values from the input fields
    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
    const mobileNumber = document.getElementById('mobile-number').value;
    const checkInDate = document.getElementById('check-in-date').value;
    
    // Assuming 'selectedRate' holds the selected rate object
    const rateId = selectedRate ? selectedRate.id : null; // Get the rateId from selectedRate
    
    // Check if any of the required fields are empty
    if (!firstName || !lastName || !email || !mobileNumber || !checkInDate || !rateId) {
      // Show an alert or error message
      alert("Please fill out all the required fields.");
      return false; // Prevent submission
    }
    return true; // Allow submission if all fields are filled
  }
  
  function storeSelections() {
    // Get the input values from the form
    const firstName = document.getElementById('first-name').value || '';  
    const lastName = document.getElementById('last-name').value || '';
    const email = document.getElementById('email').value || '';
    const mobileNumber = document.getElementById('mobile-number').value || '';
    const checkInDate = document.getElementById('check-in-date').value || '';
    const checkOutDate = document.getElementById('check-out-date').value || '';
    const checkInTime = document.getElementById('check-in-time').value || '';
    const checkOutTime = document.getElementById('check-out-time').value || '';

    // Capture rate ID from the <input> field
    const rateIdField = document.getElementById('rate-id-field');
    const rateId = rateIdField ? rateIdField.value.trim() : ''; // Use trim() to remove extra spaces

    // Log the rateId to the console


    // Check if the rateId is valid (not empty)
    if (!rateId) {
        console.log("No rateId found or rateId is empty.");
        return; // Prevent storing selections if no rate is selected
    }

    // Capture selected add-on IDs from the hidden field in the form (set to empty array if none)
    const addonIdsField = document.getElementById('addon-ids-field');
    const addonIds = addonIdsField ? addonIdsField.value.split(',') : [];  // Make sure it's an array

    // Prepare the selections object
    const selections = {
        user: {
            firstName: firstName,
            lastName: lastName,
            email: email,
            mobileNumber: mobileNumber
        },
        reservation: {
            checkInDate: checkInDate,
            checkOutDate: checkOutDate,  
            checkInTime: checkInTime,    
            checkOutTime: checkOutTime   
        },
        rate: {
            rateId: rateId  // Ensure rateId is stored
        },
        addons: addonIds.length ? addonIds : []  // Store add-ons even if empty
    };

    // Convert the selections object to JSON
    const selectionsJSON = JSON.stringify(selections);

    // Store it in localStorage
    localStorage.setItem('selections', selectionsJSON);

    // Log the stored selections object to the console
    // console.log('Stored selections:', selections);
}




function redirectToPayment() {
    // Redirect to payment.php
    if (!validateForm()) return; // Stop if validation fails
    window.location.href = "payment.php";
}

function prepareForSubmission() {
    // Store selections and redirect to the next page
    storeSelections();
}



document.addEventListener("DOMContentLoaded", () => {
  const selectedItems = document.querySelectorAll(".select-button");
  const checkInDateInput = document.getElementById("check-in-date");
  const checkOutDateInput = document.getElementById("check-out-date");
  const checkInTimeInput = document.getElementById("check-in-time");
  const checkOutTimeInput = document.getElementById("check-out-time");

  let selectedRate = null;

  // Load and display the previously stored check-in date
  const selectedDate = JSON.parse(localStorage.getItem("selectedDate"));
  if (selectedDate && selectedDate.checkIn) {
    checkInDateInput.value = selectedDate.checkIn; // Set the value in the check-in date input
  }

  // Fetch rate details (check-in time, hours of stay, etc.)
  async function fetchRateDetails(rateId) {
    try {
      const response = await fetch(`../api/get-rate-details.php?id=${rateId}`);
      const rate = await response.json();
      return rate;
    } catch (error) {
      console.error('Error fetching rate details:', error);
    }
  }
  

  function calculateCheckoutDate(checkInDate, checkInTime, hoursOfStay) {
    // Create a new Date object for the check-in date and time
    const checkInDateTime = new Date(`${checkInDate}T${checkInTime}`);
  
    // Add hours of stay to the check-in time
    checkInDateTime.setHours(checkInDateTime.getHours() + hoursOfStay);
  
    // Extract the new checkout date and time
    const checkoutDate = checkInDateTime.toISOString().split("T")[0]; // Get the date in YYYY-MM-DD format
    const checkoutTime = checkInDateTime.toTimeString().split(":").slice(0, 2).join(":"); // Get the time in HH:MM format
  
    // If the checkout date goes past midnight, adjust the date correctly
    if (checkInDateTime.getDate() !== new Date(`${checkInDate}T${checkInTime}`).getDate()) {
      const nextDay = new Date(checkInDateTime);
      nextDay.setDate(checkInDateTime.getDate() + 1); // Add 1 day if the checkout crosses midnight
      return { checkoutDate: nextDay.toISOString().split("T")[0], checkoutTime };
    }
  
    return { checkoutDate, checkoutTime };
  }

  function selectRate(rateId, rateName, ratePrice) {
    if (selectedRate && selectedRate.id === rateId) {
      return;
    }

    const selectButton = document.querySelector(`button[data-id="${rateId}"]`);
    if (selectButton && selectButton.innerText === "Select") {
      return;
    }

    fetchRateDetails(rateId).then((rate) => {
      console.log("Fetched Rate:", rate); // Log fetched rate details
      if (!rateId) {
        console.error("Rate ID is missing or invalid.");
        return;
      }
      if (rate) {
        const { checkin_time, checkout_time, hoursofstay } = rate;
        selectedRate = rate;

        const checkInTime = checkin_time || '14:00'; 
        const checkOutTime = checkout_time || '12:00';

        checkInTimeInput.value = checkInTime;
        checkOutTimeInput.value = checkOutTime;

        if (checkInDateInput.value) {
          const checkInDate = checkInDateInput.value;
          const checkInTime = checkInTimeInput.value;

          const validCheckInDate = Date.parse(`${checkInDate}T${checkInTime}`);
          if (isNaN(validCheckInDate)) {
            console.error("Invalid check-in date or time:", checkInDate, checkInTime);
            return;
          }

          const { checkoutDate, checkoutTime } = calculateCheckoutDate(
            checkInDate,
            checkInTime,
            hoursofstay
          );

          checkOutDateInput.value = checkoutDate;
          checkOutTimeInput.value = checkoutTime;

          localStorage.setItem(
            "selectedDate",
            JSON.stringify({ checkIn: checkInDateInput.value })
          );
        }
      }
    }).catch((error) => {
      console.error("Error fetching rate details:", error);
    });
  }

  async function fetchReservedDates() {
    try {
      const response = await fetch('/api/get_reserved_dates_booking.php'); // API call to fetch all reservations
      const reservedDates = await response.json();
  
      // Check if the response contains the expected structure
      if (!reservedDates || !reservedDates.reservedDaytime || !reservedDates.reservedNighttime || !reservedDates.reservedWholeDay) {
        console.error('Expected structure with reservedDaytime, reservedNighttime, reservedWholeDay but received:', reservedDates);
        return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] }; // Return empty arrays if not correct
      }
  
      return reservedDates;
    } catch (error) {
      console.error('Error fetching reserved dates:', error);
      return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] }; // Return empty arrays on error
    }
  }
  
  async function initializeFlatpickr() {
    const { reservedDaytime, reservedNighttime, reservedWholeDay } = await fetchReservedDates();

    console.log("Reserved Daytime Dates:", reservedDaytime);
    console.log("Reserved Nighttime Dates:", reservedNighttime);
    console.log("Reserved Whole Day Dates:", reservedWholeDay);

    const checkInDateInput = document.getElementById("check-in-date");
    const checkOutDateInput = document.getElementById("check-out-date");

    function checkAndShowAlert(selectedDate) {
        if (!selectedDate) return; // Ignore empty input

        console.log(`Checking reservations for: ${selectedDate}`);

        if (reservedDaytime.includes(selectedDate) && reservedNighttime.includes(selectedDate)) {
            showAlert('Both Daytime and Nighttime');
        } else if (reservedNighttime.includes(selectedDate)) {
            showAlert('Nighttime');
        } else if (reservedDaytime.includes(selectedDate)) {
            showAlert('Daytime');
        } else {
            hideAlert();
        }
    }

    // 🔹 On page load: Check if check-in date already has a value & show alert
    if (checkInDateInput.value) {
        checkAndShowAlert(checkInDateInput.value);
    }

    // 🔹 On date selection: Check reservation status and update alert
    checkInDateInput.addEventListener("change", function () {
        checkAndShowAlert(checkInDateInput.value);
    });

    // Get the current date
    const currentDate = new Date().toISOString().split("T")[0]; // Get current date in YYYY-MM-DD format

    flatpickr(checkInDateInput, {
        dateFormat: "Y-m-d",
        defaultDate: selectedDate ? selectedDate.checkIn : null,
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates[0] && checkInTimeInput.value) {
                const checkInDate = selectedDates[0].toISOString().split("T")[0];
                const checkInTime = checkInTimeInput.value;
                const { checkoutDate, checkoutTime } = calculateCheckoutDate(
                    checkInDate,
                    checkInTime,
                    selectedRate ? selectedRate.hoursofstay : 24
                );
                checkOutDateInput.value = checkoutDate;
                checkOutTimeInput.value = checkoutTime;
            }
        },
        disable: [
            // Disable all dates before today
            { from: "1970-01-01", to: currentDate },
            // Disable reserved dates
            ...reservedWholeDay.map(date => ({ from: date, to: date })),
            ...reservedDaytime.filter(date => reservedNighttime.includes(date)).map(date => ({
                from: date,
                to: date
            }))
        ]
    });

    flatpickr(checkOutDateInput, {
        dateFormat: "Y-m-d",
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates[0]) {
                const checkOutDate = selectedDates[0].toISOString().split("T")[0];
                checkOutDateInput.value = checkOutDate;
            }
        }
    });
}


// Show the alert (Ensures only one is visible at a time)
function showAlert(rateType) {
    const alertElement = document.getElementById('info-alert');
    const alertMessageElement = document.getElementById('alert-message');

    if (!alertElement || !alertMessageElement) {
        console.warn("Alert elements not found!");
        return;
    }

    let alertMessage;
    if (rateType === 'Nighttime') {
        alertMessage = "The Overnight Stay has been booked for this date. Please choose another rate or select another date.";
    } else if (rateType === 'Daytime') {
        alertMessage = "The Standard Stay has been booked for this date. Please choose another rate or select another date";
    } else if (rateType === 'Both Daytime and Nighttime') {
        alertMessage = "Both Daytime and Nighttime rates have been booked.";
    }

    console.log(`Showing alert: ${alertMessage}`);

    alertMessageElement.innerText = alertMessage;
    alertElement.classList.remove('hidden');
}

// Hide the alert (Ensures no duplicate modals)
function hideAlert() {
    console.log("Hiding alert.");
    document.getElementById('info-alert')?.classList.add('hidden');
}

// Initialize Flatpickr and fetch reserved dates
initializeFlatpickr();



  selectedItems.forEach((button) => {
    button.addEventListener("click", (e) => {
      const rateId = e.target.dataset.id;
      const rateName = e.target.dataset.name;
      const ratePrice = e.target.dataset.price;
      selectRate(rateId, rateName, ratePrice);
    });
  });
});



