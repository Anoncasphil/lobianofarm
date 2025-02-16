document.addEventListener('DOMContentLoaded', function () {
    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '../calendar/get-reservations.php', // Endpoint to fetch events
        eventClick: function (info) {
            if (info.event.extendedProps.status === 'Confirmed') {
                return; // Exit if the event is already confirmed
            }

            // Store reservation ID in localStorage
            localStorage.setItem("reservationID_admin", info.event.id);
            console.log("Stored reservation ID:", info.event.id);

            // Redirect to reservation page
            window.location.href = "../reservation/reservation_customer.php";
        },
        eventClassNames: function (arg) {
            switch (arg.event.extendedProps.status) {
                case 'Pending':
                    return ['bg-orange-700', 'text-white', 'rounded', 'p-1'];
                case 'Confirmed':
                    return ['bg-green-700', 'text-white', 'rounded', 'p-1'];
                case 'Completed':
                    return ['bg-blue-700', 'text-white', 'rounded', 'p-1'];
                case 'Cancelled':
                    return ['bg-blue-700', 'text-white', 'rounded', 'p-1'];
                default:
                    return ['bg-gray-700', 'text-white', 'rounded', 'p-1'];
            }
        },
        eventDidMount: function (info) {
            // Remove inline background color from FullCalendar
            info.el.style.backgroundColor = '';
        }
    });

    // Render the calendar
    calendar.render();
});
