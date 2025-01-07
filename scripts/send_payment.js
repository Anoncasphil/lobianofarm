// Select the form and button
const form = document.getElementById('uploadForm');
const confirmButton = document.getElementById('confirm_btn');

// Attach a click event listener to the button
confirmButton.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent default behavior of the button
    form.action = 'send_receipt.php'; // Set the form action to send_receipt.php
    form.submit(); // Submit the form programmatically
});