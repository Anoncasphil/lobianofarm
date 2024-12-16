document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('dateModal');
    const closeModal = document.getElementById('closeModal');
    const approveButton = document.querySelector('.approve-button');

    if (approveButton) {
        approveButton.addEventListener('click', function() {
            console.log("Reserved");
            modal.style.display = 'none';
        });
    }
    
    const modalName = document.getElementById('modal-name');
    const modalReservationDate = document.getElementById('modal-reservation-date');
    const modalDesiredDate = document.getElementById('modal-desired-date');
    const modalQR = document.getElementById('modal-qr');

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '../calendar/get-reservations.php', // Load events from PHP
        eventClick: function(info) {
            // Display reservation details in modal when event is clicked
            modalName.textContent = info.event.title;
            modalReservationDate.textContent = info.event.start.toISOString().slice(0, 10);
            modalDesiredDate.textContent = info.event.end.toISOString().slice(0, 10);
            
            // Display the modal
            modal.style.display = 'flex';
        }
    });

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