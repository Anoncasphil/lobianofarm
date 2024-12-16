let reservation_check_in_date;
let reservation_check_out_date;

flatpickr("#calendar", {
    mode: "range",
    inline: true, // Renders the calendar inline
    dateFormat: "Y-m-d",
    onDayCreate: (dObj, dStr, fp, dayElem) => {
        // Mark specific dates
        const markedDates = ['2024-03-21', '2024-11-08', '2024-11-26', '2024-11-16'];
        if (markedDates.includes(dayElem.dateObj.toISOString().slice(0, 10))) {
            dayElem.style.backgroundColor = "red";
            dayElem.style.color = "white";
            dayElem.style.pointerEvents = "none"; // Make the date unclickable
            dayElem.style.opacity = "0.5"; // Optional: Add a faded effect
            dayElem.setAttribute("title", "This date is unavailable"); // Add a tooltip
        }
    },
    onChange: (selectedDates) => {
        if (selectedDates.length === 2) {
            const startDate = selectedDates[0];
            const endDate = selectedDates[1];
            startDate.setDate(startDate.getDate() + 1); // Add 1 day to start date
            endDate.setDate(endDate.getDate() + 1); // Add 1 day to end date

            
            // Store the values in reservation_check_in_date and reservation_check_out_date
            reservation_check_in_date = startDate.toISOString().slice(0, 10);
            reservation_check_out_date = endDate.toISOString().slice(0, 10);
            
            console.log(`Start Date: ${reservation_check_in_date}`);
            console.log(`End Date: ${reservation_check_out_date}`);
            
            // Set the hidden input values
            document.getElementById("reservation_check_in_date").value = reservation_check_in_date;
            document.getElementById("reservation_check_out_date").value = reservation_check_out_date;
        }
    }
});



document.addEventListener('DOMContentLoaded', function () {
    const rateButtons = document.querySelectorAll('#rate_btn');
    const selectedRates = new Set();
    const selectedAddons = new Set(); // Set to store selected addons

    rateButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const card = button.closest('#rates_card');
            const name = card.querySelector('h1').textContent.trim();
            const price = card.querySelector('.text-lg').textContent.trim();
            const rateId = button.getAttribute('data-rate-id');

            const existingRow = findRowByName(name);

            if (existingRow) {
                existingRow.remove();
                selectedRates.delete(rateId);
                button.textContent = "Select Rate";
            } else {
                addItemToInvoice(name, price);
                selectedRates.add(rateId);
                button.textContent = "Unselect Rate";
            }

            document.getElementById('rate_id').value = Array.from(selectedRates).join(',');
            updateInvoiceTotal();
        });
    });

    // Handle Addon Selection
    const addonsButtons = document.querySelectorAll('#addons_btn');
    addonsButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const card = button.closest('#addons_card');
            const name = card.querySelector('h1').textContent.trim();
            const price = card.querySelector('.text-lg').textContent.trim();
            const addonsId = button.getAttribute('data-addons-id');
            
            const existingRow = findRowByName(name);

            if (existingRow) {
                existingRow.remove();
                selectedAddons.delete(addonsId); // Remove from selected addons
                button.textContent = "Select Addons";
            } else {
                addItemToInvoice(name, price);
                selectedAddons.add(addonsId); // Add to selected addons
                button.textContent = "Unselect Addons";
            }

            // Update hidden input with all selected addon IDs (comma-separated)
            document.getElementById('addons_id').value = Array.from(selectedAddons).join(',');
            updateInvoiceTotal();
        });
    });

    // Function to add item to the invoice
    function addItemToInvoice(name, price) {
        const invoiceBody = document.querySelector('#invoice tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="text-left">${name}</td>
            <td class="text-right">${price}</td>
        `;
        invoiceBody.appendChild(newRow);
    }

    // Function to find an existing row by item name
    function findRowByName(name) {
        const rows = document.querySelectorAll('#invoice tbody tr');
        return Array.from(rows).find(row => row.querySelector('td').textContent.trim() === name);
    }

    // Function to update the total price in the invoice
    function updateInvoiceTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#invoice tbody tr');
        rows.forEach(row => {
            const priceText = row.querySelector('td:last-child').textContent;
            const price = parseFloat(priceText.replace('₱', '').replace(',', ''));
            total += isNaN(price) ? 0 : price;
        });

        const totalElement = document.querySelector('#invoice tfoot #totalPrice');
        totalElement.textContent = `₱${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        const totalAmountInput = document.querySelector('#total_amount');
        totalAmountInput.value = total;
    }

    // Form submission validation
    const bookingButton = document.querySelector('button[name="Book_me_now_pls"]');
    bookingButton.addEventListener('click', function (event) {
        const rateId = document.getElementById('rate_id').value;
        const addonsId = document.getElementById('addons_id').value;
        const checkInDate = document.getElementById('reservation_check_in_date').value;
        const checkOutDate = document.getElementById('reservation_check_out_date').value;

        if (!rateId) {
            alert('Please select at least one rate.');
            event.preventDefault(); // Prevent form submission
            return;
        }

        if (!addonsId) {
            alert('Please select at least one addon.');
            event.preventDefault(); // Prevent form submission
            return;
        }

        if (!checkInDate || !checkOutDate) {
            alert('Please select a check-in and check-out date before proceeding.');
            event.preventDefault(); // Prevent form submission
            return;
        }

    });
});
