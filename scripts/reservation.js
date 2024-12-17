document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reservationChoiceModal');
    const selfBtn = document.getElementById('selfReservation');
    const otherBtn = document.getElementById('otherReservation');
    const firstNameInput = document.querySelector('input[name="first_name"]');
    const lastNameInput = document.querySelector('input[name="last_name"]');
    const emailInput = document.querySelector('input[name="email"]');

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