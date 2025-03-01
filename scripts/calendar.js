document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '../calendar/get-reservations.php', // Your endpoint for reservations
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
                    return ['bg-orange-500', 'text-white', 'rounded-lg', 'p-2', 'shadow-md'];
                case 'Confirmed':
                    return ['bg-green-600', 'text-white', 'rounded-lg', 'p-2', 'shadow-md'];
                case 'Completed':
                    return ['bg-blue-500', 'text-white', 'rounded-lg', 'p-2', 'shadow-md'];
                case 'Cancelled':
                    return ['bg-red-600', 'text-white', 'rounded-lg', 'p-2', 'shadow-md'];
                default:
                    return ['bg-gray-500', 'text-white', 'rounded-lg', 'p-2', 'shadow-md'];
            }
        },
        eventDidMount: function (info) {
            info.el.style.backgroundColor = '';
            if (info.event.extendedProps.isDisabled) {
                info.el.style.backgroundColor = '#d3d3d3'; // Light gray to indicate disabled date
                info.el.style.pointerEvents = 'none'; // Disable interaction with this date
            }
        },
        contentHeight: 'auto',
        aspectRatio: 2,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay',
        },
        eventsMinWidth: 100,
        dayHeaderClassNames: ['bg-blue-200', 'text-gray-800'],
        dayCellClassNames: ['text-center', 'py-4', 'px-2'],
        eventColor: '#FFFFFF',
        eventBorderColor: '#FFFFFF',
    });

    // Fetch disabled dates and disable them in the calendar
    async function fetchDisabledDates() {
        try {
            const response = await fetch('../api/get_disabled_dates.php');
            const data = await response.json();
            
            console.log("Fetched disabled dates:", data); // Debugging log

            if (data.disableDates && Array.isArray(data.disableDates)) {
                return data.disableDates;
            } else {
                console.error("Invalid disabled dates structure:", data);
                return [];
            }
        } catch (error) {
            console.error("Error fetching disabled dates:", error);
            return [];
        }
    }

    // Fetch the disabled dates and disable them in the calendar
    fetchDisabledDates().then(disabledDates => {
        if (disabledDates.length === 0) {
            console.warn("No disabled dates found."); // Debugging log
        }

        const disabledEvents = disabledDates.map(dateInfo => ({
            start: dateInfo.date,
            end: dateInfo.date,
            rendering: 'background',
            color: '#d3d3d3',         // Light gray color for disabled dates
            textColor: '#ffffff',     // Text color (won't be visible in background)
            title: 'Disabled',        // Tooltip message for disabled dates
            extendedProps: {
                isDisabled: true,     // Add flag to indicate it's a disabled date
                reason: dateInfo.reason || 'No reason provided',  // Reason for the disabled date
            }
        }));

        // Log disabled events to confirm they are created
        console.log("Disabled events:", disabledEvents);

        // Add the disabled dates as background events
        calendar.addEventSource({
            events: disabledEvents
        });

        // Render the calendar
        calendar.render();
    });

    // Open modal when a disabled date is clicked
    calendar.on('dateClick', function (info) {
        // Check if the clicked date is disabled
        const clickedDate = info.dateStr;
        const disabledEvent = calendar.getEvents().find(event => event.startStr === clickedDate && event.extendedProps.isDisabled);

        if (disabledEvent) {
            const reason = disabledEvent.extendedProps.reason; // Get the reason for the disabled date
            openDisableDateModal(clickedDate, reason);
        }
    });

    // Open the modal to show the disabled date and reason
    function openDisableDateModal(date, reason) {
        const modal = document.getElementById('date-info'); // Corrected ID reference
        const dateElement = document.getElementById('disable-date');
        const reasonElement = document.getElementById('disable-reason');
        const reenableButton = document.getElementById('reenable-btn');

        // Set the content of the modal
        dateElement.innerText = `Date: ${date}`;
        reasonElement.innerText = `Reason: ${reason}`;
        
        // Handle re-enable functionality
        reenableButton.onclick = function () {
            reenableDate(date);
        };

        // Show the modal by removing the 'hidden' class
        modal.classList.remove('hidden');
    }

    // Close modal
    document.getElementById('close-btn').addEventListener('click', function () {
        const modal = document.getElementById('date-info');
        modal.classList.add('hidden'); // Hide the modal by adding the 'hidden' class
    });

    // Re-enable the date
    async function reenableDate(date) {
        try {
            const response = await fetch('../api/remove_disabled_date.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    date: date,
                })
            });

            const data = await response.json();
            if (data.success) {
                // Reload the calendar after re-enabling the date
                location.reload();
            } else {
                console.error("Failed to re-enable date:", data.message);
            }
        } catch (error) {
            console.error("Error re-enabling date:", error);
        }
    }
});
