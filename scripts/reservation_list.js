document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("reservationModal");
    const closeModal = document.getElementById("closeModal");
    const viewButtons = document.querySelectorAll(".view-button");
    const tabs = document.querySelectorAll(".tab-button");
    const contents = document.querySelectorAll(".tab-content");

    // Check if all elements exist
    if (!modal || !closeModal || viewButtons.length === 0 || tabs.length === 0 || contents.length === 0) {
        console.error("One or more required elements are missing.");
        return;
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    // Attach click event to all view buttons
    viewButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            const reservationId = e.target.dataset.id;  // Use data-id attribute from button
            openModal(reservationId);
        });
    });

    // Close modal when the close button is clicked
    closeModal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Handle tab switching inside the modal
    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active", "text-blue-500", "border-blue-500"));
            tab.classList.add("active", "text-blue-500", "border-blue-500");

            contents.forEach(content => content.classList.add("hidden"));
            document.getElementById(tab.dataset.tab)?.classList.remove("hidden");
        });
    });

    // Sort reservations by id in ascending order
    const reservationRows = Array.from(document.querySelectorAll("tbody tr"));
    reservationRows.sort((a, b) => {
        const idA = parseInt(a.querySelector("td:first-child").textContent);
        const idB = parseInt(b.querySelector("td:first-child").textContent);
        return idA - idB;
    });

    const tbody = document.querySelector("tbody");
    reservationRows.forEach(row => tbody.appendChild(row));
});

document.querySelector("tbody").addEventListener("click", function (e) {
    if (e.target.closest(".view-button")) {
        const button = e.target.closest(".view-button"); // Ensure correct button selection
        const reservationId = button.dataset.id; // Fetch the `data-id`
        console.log("Reservation ID:", reservationId); // Debugging: Check if it's correctly retrieved
        openModal(reservationId); // Call your modal function
    }
});





let hoursOfStay; // Declare in global scope

document.addEventListener("DOMContentLoaded", async () => {
    const checkInDateInput = document.getElementById("check-in-date");
    const checkOutDateInput = document.getElementById("check-out-date");
    const checkInTimeInput = document.getElementById("check-in-time");
    const checkOutTimeInput = document.getElementById("check-out-time");

    // Default hours of stay


    async function fetchReservationDetails(reservationId) {
        if (!reservationId) {
            console.error("No reservation ID provided.");
            return;
        }
    
        try {
            const response = await fetch(`/api/get_reservation_details.php?id=${reservationId}`);
    
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
    
            const reservation = await response.json();
    
            if (reservation && !reservation.error) {
                checkInDateInput.value = reservation.check_in_date || "";
                checkOutDateInput.value = reservation.check_out_date || "";
                checkInTimeInput.value = reservation.check_in_time || "";
                checkOutTimeInput.value = reservation.check_out_time || "";
    
                // Assign rateType and rateID
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
    

    document.addEventListener("click", function (event) {
        if (event.target.closest(".view-button")) {
            const button = event.target.closest(".view-button");
            const reservationId = button.getAttribute("data-id");

            console.log("Clicked reservation ID:", reservationId);
            fetchReservationDetails(reservationId);
        }
   

    checkInDateInput.addEventListener("change", updateCheckoutDate);
    checkInTimeInput.addEventListener("change", updateCheckoutDate);
    });

    async function fetchReservedDates() {
        try {
            const response = await fetch("/api/get_reserved_dates_booking.php");
            const reservedDates = await response.json();

            if (!reservedDates || !reservedDates.reservedDaytime || !reservedDates.reservedNighttime || !reservedDates.reservedWholeDay) {
                console.error("Unexpected reserved dates structure:", reservedDates);
                return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
            }

            return reservedDates;
        } catch (error) {
            console.error("Error fetching reserved dates:", error);
            return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
        }
    }

    console.log("Fetching reserved dates", reservedDates);

    async function initializeFlatpickr(rateType) {
        const { reservedDaytime, reservedNighttime, reservedWholeDay } = await fetchReservedDates();
    
        function checkAndShowAlert(selectedDate) {
            if (!selectedDate) return;
    
            console.log(`Checking reservations for: ${selectedDate}`);
    
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

    
        // Add hoursOfStay to the check-in time
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
    
        const { checkoutDate, checkoutTime } = calculateCheckoutDate(checkInDate, checkInTime, hoursOfStay);
    
        checkOutDateInput.value = checkoutDate;
        checkOutTimeInput.value = checkoutTime;
    
        console.log(`Check-in: ${checkInDate} ${checkInTime}, Check-out: ${checkoutDate} ${checkoutTime}`);
    }
    

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

  function storeReservationAndRedirect(button) {
    // Get the reservation ID from the button's data attribute
    let reservationId = button.getAttribute("data-id");

    // Store the reservation ID in localStorage
    localStorage.setItem("reservationID_admin", reservationId);        
    console.log("Stored reservation ID:", reservationId);

    // Redirect to reservation_customer.php
    window.location.href = "reservation_customer.php";
}
