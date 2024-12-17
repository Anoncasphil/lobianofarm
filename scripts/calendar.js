document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('dateModal');
    const closeModal = document.getElementById('closeModal');
    const approveButton = document.querySelector('.approve-button');
    let currentReservation = null; // Add at top of file with other constants

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '../calendar/get-reservations.php', // Load events from PHP
        eventClick: function(info) {
            currentReservation = {
                firstName: info.event.extendedProps.firstName,
                lastName: info.event.extendedProps.lastName,
                checkInDate: info.event.start.toISOString().slice(0, 10)
            };
            console.log('Stored reservation data:', currentReservation); // Debug log
            // Display reservation details in modal when event is clicked
            modalName.textContent = info.event.title;
            modalReservationDate.textContent = info.event.start.toISOString().slice(0, 10);
            modalDesiredDate.textContent = info.event.end.toISOString().slice(0, 10);
            
            // Display the modal
            modal.style.display = 'flex';
        }
    });

    if (approveButton) {
        approveButton.addEventListener('click', async function() {
            try {
                console.log('Sending data:', currentReservation); // Debug data being sent
                
                // Define the new title for the reservation
                const newTitle = "Approved";
        
                // Send updated title and reservation details to the server
                const response = await fetch('../calendar/update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        firstName: currentReservation.firstName,
                        lastName: currentReservation.lastName,
                        checkInDate: currentReservation.checkInDate,
                        title: newTitle // Send the new title
                    })
                });
        
                const result = await response.json();
                console.log('Response from server:', result); // Debug server response
                if (result.success) {
                    console.log("Approved");
                    
                    // Find the event in the calendar
                    const event = calendar.getEvents().find(ev => 
                        ev.extendedProps.firstName === currentReservation.firstName &&
                        ev.extendedProps.lastName === currentReservation.lastName &&
                        ev.start.toISOString().slice(0, 10) === currentReservation.checkInDate
                    );
        
                    if (event) {
                        // Update the title of the event
                        event.setProp('title', newTitle);
                    }
        
                    // Close the modal
                    modal.style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
        
    }
    
    const modalName = document.getElementById('modal-name');
    const modalReservationDate = document.getElementById('modal-reservation-date');
    const modalDesiredDate = document.getElementById('modal-desired-date');
    const modalQR = document.getElementById('modal-qr');

    calendar.render();

    closeModal.onclick = function () {
        modal.style.display = 'none';
    };

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
});