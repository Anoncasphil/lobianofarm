document.addEventListener('DOMContentLoaded', async function () {
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
        dayHeaderClassNames: ['bg-blue-200', 'text-gray-800'],
        dayCellClassNames: ['text-center', 'py-4', 'px-2'],
        eventColor: '#FFFFFF',
        eventBorderColor: '#FFFFFF',
        selectable: true, // Allows date selection
        dateClick: function (info) {
            const clickedDate = info.dateStr;
            const disabledEvent = calendar.getEvents().find(event => event.startStr === clickedDate && event.extendedProps.isDisabled);

            if (disabledEvent) {
                openDisableDateModal(clickedDate, disabledEvent.extendedProps.reason);
            }
        }
    });

    // Fetch disabled dates and disable them in the calendar
    async function fetchDisabledDates() {
        try {
            const response = await fetch('../api/get_disabled_dates.php');
            const data = await response.json();
            
            console.log("Fetched Disabled Dates:", data); // Debugging log
    
            if (data.disableDates && Array.isArray(data.disableDates)) {
                return data.disableDates.map(dateInfo => ({
                    title: 'Disabled',
                    start: dateInfo.date,
                    end: dateInfo.date,
                    display: 'background', // Correct method for FullCalendar v5+
                    color: '#d3d3d3', // Light gray background
                    textColor: '#000000', // Black text for visibility
                    extendedProps: {
                        isDisabled: true,
                        reason: dateInfo.reason || 'No reason provided',
                    }
                }));
            } else {
                console.error("Invalid disabled dates structure:", data);
                return [];
            }
        } catch (error) {
            console.error("Error fetching disabled dates:", error);
            return [];
        }
    }
    
    // Fetch the disabled dates and add them to FullCalendar
    fetchDisabledDates().then(disabledEvents => {
        if (disabledEvents.length === 0) {
            console.warn("No disabled dates found.");
        }
    
        console.log("Adding disabled dates to FullCalendar:", disabledEvents);
    
        calendar.addEventSource(disabledEvents);
    
        // Force re-rendering
        calendar.refetchEvents();
    });

    // Render the calendar
    calendar.render();

    // Open the modal to show the disabled date and reason
    function openDisableDateModal(date, reason) {
        const modal = document.getElementById('date-info'); // Corrected ID reference
        const dateElement = document.getElementById('disable-date');
        const reasonElement = document.getElementById('disable-reason');
        const reenableButton = document.getElementById('reenable-btn');

        // Format the date to "February 15, 2025"
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Set the content of the modal
        dateElement.innerText = formattedDate;
        reasonElement.innerText = reason;

        // Handle re-enable functionality
        reenableButton.onclick = function () {
            reenableDate(date);
        };

        // Show the modal by removing the 'hidden' class
        modal.classList.remove('hidden');
    }

    // Close modal
    document.getElementById('close-btn-info').addEventListener('click', function () {
        const modal = document.getElementById('date-info');
        modal.classList.add('hidden'); // Hide the modal by adding the 'hidden' class
    });

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
                console.log("Date re-enabled successfully.");
                // Set a flag in sessionStorage to show the success message after reload
                sessionStorage.setItem('reenableSuccess', 'true');
                // Reload the calendar after re-enabling the date
                location.reload();
            } else {
                console.error("Failed to re-enable date:", data.message);
            }
        } catch (error) {
            console.error("Error re-enabling date:", error);
        }
    }

    // Check if the success message flag exists in sessionStorage after page reload
    const successMessageFlag = sessionStorage.getItem('reenableSuccess');
    if (successMessageFlag === 'true') {
        // Display the success message inside the 'info-alert' div
        const alertMessage = document.getElementById('alert-message');
        const infoAlert = document.getElementById('info-alert');
        const alertTitle = document.getElementById('alert-title');

        // Set the content for the alert
        alertTitle.innerText = 'Success!';
        alertMessage.innerText = 'Date successfully re-enabled!';

        // Show the alert by removing the 'hidden' class
        infoAlert.classList.remove('hidden');

        // Hide the alert after 3 seconds
        setTimeout(() => {
            infoAlert.classList.add('hidden');
            // Remove the success message flag from sessionStorage
            sessionStorage.removeItem('reenableSuccess');
        }, 3000);
    }
});
