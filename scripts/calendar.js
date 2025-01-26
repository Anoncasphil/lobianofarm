document.addEventListener('DOMContentLoaded', function () {
    // Constants and Variables
    const modal = document.getElementById('dateModal');
    const closeModal = document.getElementById('closeModal');
    const approveButton = document.querySelector('.approve-button');
    let currentReservation = null; // Holds data for the currently selected reservation

    const modalId = document.getElementById('modal-id'); // Reference for modal-id
    const modalName = document.getElementById('modal-name');    
    const modalReservationDate = document.getElementById('modal-reservation-date');
    const modalDesiredDate = document.getElementById('modal-desired-date');

    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '../calendar/get-reservations.php', // Endpoint to fetch events
        eventClick: function (info) {
            
            if (info.event.title === 'Approved') {
                return; // Exit the handler
            }

            // Populate the currentReservation object with all necessary details
            currentReservation = {
                id: info.event.id,
                firstName: info.event.extendedProps.firstName,
                lastName: info.event.extendedProps.lastName,
                checkInDate: info.event.startStr, // Use startStr for check-in date
                checkOutDate: info.event.endStr || info.event.startStr // Use endStr for check-out date, or startStr if endStr is null
            };
            console.log('Stored reservation data:', currentReservation); // Debug log
            // Ensure the check-out date is set to the same as the check-in date if they are the same
            if (!currentReservation.checkOutDate) {
                currentReservation.checkOutDate = currentReservation.checkInDate;
            }

            // Populate modal fields
            modalId.textContent = currentReservation.id; // Display reservation ID
            modalName.textContent = `${currentReservation.firstName} ${currentReservation.lastName}`;
            modalReservationDate.textContent = currentReservation.checkInDate;
            modalDesiredDate.textContent = currentReservation.checkOutDate;

            // Show the modal
            modal.style.display = 'flex';
        },
    });

    // Approve Button Handler
    if (approveButton) {
        approveButton.addEventListener('click', async function () {
            try {
                if (!currentReservation) {
                    alert('No reservation selected!');
                    return;
                }

                console.log('Sending data:', currentReservation); // Debug sent data

                // Define the new title for the reservation
                const newTitle = 'Approved';

                // Send update request to the server
                const response = await fetch('../calendar/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        reservationId: currentReservation.id,
                        firstName: currentReservation.firstName,
                        lastName: currentReservation.lastName,
                        checkInDate: currentReservation.checkInDate,
                        title: newTitle, // Send the new title
                    }),
                });

                const result = await response.json();
                console.log('Response from server:', result); // Debug server response

                if (result.success) {
                    alert('Reservation approved successfully!');

                    // Update the calendar event title
                    const event = calendar.getEvents().find(
                        (ev) =>
                            ev.extendedProps.firstName === currentReservation.firstName &&
                            ev.extendedProps.lastName === currentReservation.lastName &&
                            ev.startStr === currentReservation.checkInDate
                    );

                    if (event) {
                        event.setProp('title', newTitle); // Update the title on the calendar
                    }

                    calendar.refetchEvents(); // Refresh the events

                    // Close the modal
                    modal.style.display = 'none';
                } else {
                    alert(result.message || 'Failed to approve reservation.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while approving the reservation.');
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
