document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('dateModal');
    const closeModal = document.getElementById('closeModal');

    const modalName = document.getElementById('modal-name');
    const modalReservationDate = document.getElementById('modal-reservation-date');
    const modalDesiredDate = document.getElementById('modal-desired-date');
    const modalQR = document.getElementById('modal-qr');

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        dateClick: function (info) {
            const exampleData = {
                name: 'Jane Doe',
                reservationDate: info.dateStr,
                desiredDate: '2024-12-20',
                qrCodeUrl: 'https://via.placeholder.com/150'
            };

            modalName.textContent = exampleData.name;
            modalReservationDate.textContent = exampleData.reservationDate;
            modalDesiredDate.textContent = exampleData.desiredDate;
            modalQR.src = exampleData.qrCodeUrl;

            modal.style.display = 'flex';
        },
        events: [
            { title: 'Pending', start: '2024-12-08', end: '2024-12-10' },
            { title: 'Reserved', start: '2024-12-11', end: '2024-12-13' },
        ]
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