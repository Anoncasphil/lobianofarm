document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar with minimum date set to today
    flatpickr("#calendar", {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: "today",
        inline: true,
        onChange: function(selectedDates, dateStr) {
            if (selectedDates.length === 2) {
                // Update hidden fields
                document.getElementById('reservation_check_in_date').value = 
                    flatpickr.formatDate(selectedDates[0], "Y-m-d");
                document.getElementById('reservation_check_out_date').value = 
                    flatpickr.formatDate(selectedDates[1], "Y-m-d");
            }
        }
    });

    const modal = document.getElementById('reservationChoiceModal');
    const selfBtn = document.getElementById('selfReservation');
    const otherBtn = document.getElementById('otherReservation');
    const firstNameInput = document.querySelector('input[name="first_name"]');
    const lastNameInput = document.querySelector('input[name="last_name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const mobileNumberInput =document.querySelector('input[name="mobile_number"]');

    selfBtn.addEventListener('click', async function() {
        try {
            const response = await fetch('../reservation/get_user_info.php');
            const userData = await response.json();
            
            if (userData.error) {
                console.error('Error:', userData.error);
                return;
            }

            firstNameInput.value = userData.first_name;
            lastNameInput.value = userData.last_name;
            emailInput.value = userData.email;
            mobileNumberInput.value = userData.mobile_number; // Updated line
            modal.style.display = 'none';
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    });

    otherBtn.addEventListener('click', function() {
        firstNameInput.value = '';
        lastNameInput.value = '';
        emailInput.value = '';
        modal.style.display = 'none';
    });
});