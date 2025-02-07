document.addEventListener('DOMContentLoaded', function () {
    // Constants and Variables
    const modal = document.getElementById('dateModal');
    const closeModal = document.getElementById('closeModal');
    const confirmButton = document.querySelector('.confirm-button'); // Change to confirm button
    const declineButton = document.querySelector('.decline-button');
    let currentReservation = null; // Holds data for the currently selected reservation

    const modalId = document.getElementById('modal-id'); // Reference for modal-id
    const modalName = document.getElementById('modal-name'); // Reference for modal-name
    const modalReservationDate = document.getElementById('modal-reservation-date');
    const modalDesiredDate = document.getElementById('modal-desired-date');
    const modalCheckinTime = document.getElementById('modal-checkin-time'); 
    const modalCheckoutTime = document.getElementById('modal-checkout-time'); 
    const modalPaymentReceipt = document.getElementById('modal-payment-receipt'); // Reference for modal-payment-receipt

    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '../calendar/get-reservations.php', // Endpoint to fetch events
        eventClick: function (info) {
            
            if (info.event.extendedProps.status === 'Confirmed') { // Change to confirmed
                return; // Exit the handler if the event is confirmed
            }

            // Populate the currentReservation object with all necessary details
            currentReservation = {
                id: info.event.id,
                userId: info.event.extendedProps.userId, // Include user id
                firstName: info.event.extendedProps.firstName, // Include first name
                lastName: info.event.extendedProps.lastName, // Include last name
                checkInDate: info.event.startStr, // Use startStr for check-in date
                checkOutDate: info.event.endStr || info.event.startStr, // Use endStr for check-out date, or startStr if endStr is null
                checkInTime: info.event.extendedProps.checkInTime, // Use extendedProps for check-in time
                checkOutTime: info.event.extendedProps.checkOutTime, // Use extendedProps for check-out time
                paymentReceipt: info.event.extendedProps.paymentReceipt // Include payment receipt
            };
            console.log('Stored reservation data:', currentReservation); // Debug log
            // Ensure the check-out date is set to the same as the check-in date if they are the same
            if (!currentReservation.checkOutDate) {
                currentReservation.checkOutDate = currentReservation.checkInDate;
            }

            // Populate modal fields
            modalId.textContent = currentReservation.id; // Display reservation ID
            modalName.textContent = currentReservation.firstName + ' ' + currentReservation.lastName; // Display full name
            modalReservationDate.textContent = currentReservation.checkInDate;
            modalDesiredDate.textContent = currentReservation.checkOutDate;
            modalCheckinTime.textContent = currentReservation.checkInTime; // Display check-in time
            modalCheckoutTime.textContent = currentReservation.checkOutTime; // Display check-out time
            modalPaymentReceipt.src = currentReservation.paymentReceipt; // Display payment receipt

            // Show the modal
            modal.style.display = 'flex';
        },
        eventContent: function(arg) {
            let title = document.createElement('div');
            title.classList.add('fc-event-title');
            title.innerText = arg.event.title;
            return { domNodes: [title] };
        }
    });

    // Confirm Button Handler
    if (confirmButton) {
        confirmButton.addEventListener('click', async function () {
            try {
                if (!currentReservation) {
                    alert('No reservation selected!');
                    return;
                }

                console.log('Sending data:', currentReservation); // Debug sent data

                // Define the new title for the reservation
                const newTitle = 'Confirmed'; // Change to confirmed

                // Send update request to the server
                const response = await fetch('../calendar/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        reservationId: currentReservation.id,
                        userId: currentReservation.userId, // Send user id
                        firstName: currentReservation.firstName, // Send first name
                        lastName: currentReservation.lastName, // Send last name
                        checkInDate: currentReservation.checkInDate,
                        checkOutDate: currentReservation.checkOutDate,
                        checkInTime: currentReservation.checkInTime, // Send check-in time
                        checkOutTime: currentReservation.checkOutTime, // Send check-out time
                        title: newTitle, // Send the new title
                    }),
                });

                const result = await response.json();
                console.log('Response from server:', result); // Debug server response

                if (result.success) {
                    alert('Reservation Confirmed!'); // Change to confirmed

                    // Update the calendar event title
                    const event = calendar.getEventById(currentReservation.id);
                    if (event) {
                        event.setProp('title', newTitle); // Update the title on the calendar
                        event.setExtendedProp('status', 'Confirmed'); // Update the status on the calendar
                    }

                    calendar.refetchEvents(); // Refresh the events

                    // Close the modal
                    modal.style.display = 'none';
                } else {
                    alert(result.message || 'Failed to confirm reservation.'); // Change to confirm
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while confirming the reservation.'); // Change to confirm
            }
        });
    }

    // Decline Button Handler
    if (declineButton) {
        declineButton.addEventListener('click', async function () {
            try {
                if (!currentReservation) {
                    alert('No reservation selected!');
                    return;
                }

                console.log('Sending data:', currentReservation); // Debug sent data

                // Define the new title for the reservation
                const newTitle = 'Declined';

                // Send update request to the server
                const response = await fetch('../api/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        reservationId: currentReservation.id,
                        userId: currentReservation.userId, // Send user ID
                        checkInDate: currentReservation.checkInDate,
                        checkOutDate: currentReservation.checkOutDate,
                        checkInTime: currentReservation.checkInTime, // Send check-in time
                        checkOutTime: currentReservation.checkOutTime, // Send check-out time
                        title: newTitle, // Send the new title
                    }),
                });

                const result = await response.json();
                console.log('Response from server:', result); // Debug server response

                if (result.success) {
                    alert('Reservation Declined!');

                    // Update the calendar event title
                    const event = calendar.getEvents().find(
                        (ev) =>
                            ev.extendedProps.userId === currentReservation.userId &&
                            ev.startStr === currentReservation.checkInDate
                    );

                    if (event) {
                        event.setProp('title', newTitle); // Update the title on the calendar
                        event.setExtendedProp('status', 'Declined'); // Update the status on the calendar
                    }

                    calendar.refetchEvents(); // Refresh the events

                    // Close the modal
                    modal.style.display = 'none';
                } else {
                    alert(result.message || 'Failed to decline reservation.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while declining the reservation.');
            }
        });
    }

    // Modal Close Handlers
    closeModal.onclick = function () {
        modal.style.display = 'none';
    };

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };

    // Render the calendar
    calendar.render();
});