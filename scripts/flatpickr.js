let hoursOfStay; // Declare in global scope

document.addEventListener("DOMContentLoaded", async () => {
    const checkInDateInput = document.getElementById("check-in-date");
    const checkOutDateInput = document.getElementById("check-out-date");
    const checkInTimeInput = document.getElementById("check-in-time");
    const checkOutTimeInput = document.getElementById("check-out-time");

    // Default hours of stay
// Fetch reservation ID from localStorage
let reservationId = localStorage.getItem('reservation_id');

// Call the fetchReservationDetails function with the retrieved ID
fetchReservationDetails(reservationId);

// Default hours of stay function
async function fetchReservationDetails(reservationId) {
    if (!reservationId) {
        console.error("No reservation ID provided.");
        return;
    }

    try {
        const response = await fetch(`../api/get_reservation_details.php?id=${reservationId}`);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const reservation = await response.json();

        if (reservation && !reservation.error) {
            // Update the form inputs with reservation details
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
            const response = await fetch("../api/get_reserved_dates_booking.php");
            const reservedDates = await response.json();
    
            if (!reservedDates || !reservedDates.reservedDaytime || !reservedDates.reservedNighttime || !reservedDates.reservedWholeDay) {
                console.error("Unexpected reserved dates structure:", reservedDates);
                return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
            }
    
            console.log("Fetched reserved dates:", reservedDates); // Log fetched reserved dates
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
        const disabledDates = await fetchDisabledDates();
    
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
    
        // Add disabled dates to the disable list
        disableDates = disableDates.concat(
            disabledDates.map((date) => ({ from: date, to: date }))
        );
    
        // Disable whole day reservations as well (for any rate type)
        disableDates = disableDates.concat(
            reservedWholeDay.map((date) => ({ from: date, to: date }))
        );
    
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
    
    // Initialize Flatpickr after setting everything up
    initializeFlatpickr();
    
    // Code for selectedItems buttons (if needed)
    selectedItems.forEach((button) => {
        button.addEventListener("click", (e) => {
            const rateId = e.target.dataset.id;
            const rateName = e.target.dataset.name;
            const ratePrice = e.target.dataset.price;
            selectRate(rateId, rateName, ratePrice);
        });
    });
    
});



