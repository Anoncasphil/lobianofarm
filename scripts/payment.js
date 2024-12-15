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
    // Handle Rate Selection
    const rateButtons = document.querySelectorAll('#rate_btn');
    rateButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const card = button.closest('#rates_card');
            const name = card.querySelector('h1').textContent.trim();  
            const price = card.querySelector('.text-lg').textContent.trim();
            const rateId = button.getAttribute('data-rate-id'); // Get the selected rate ID

            const existingRow = findRowByName(name);
        
            if (existingRow) {
                existingRow.remove();
                button.textContent = "Select Rate";
            } else {
                addItemToInvoice(name, price);
                button.textContent = "Unselect Rate"; 
            }
        
            updateInvoiceTotal();

            // Store the selected rate ID in the hidden input field
            document.getElementById('rate_id').value = rateId;  // Store rate_id
        });
    });

    // Handle Addson Selection
    const addonsButtons = document.querySelectorAll('#addons_btn');
    addonsButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 
            const card = button.closest('#addons_card');
            const name = card.querySelector('h1').textContent.trim();  
            const price = card.querySelector('.text-lg').textContent.trim();
            const addonsId = button.getAttribute('data-addons-id'); // Get the selected amenity ID
            
            const existingRow = findRowByName(name);
        
            if (existingRow) {
                existingRow.remove();
                button.textContent = "Select Addons";
            } else {
                addItemToInvoice(name, price);
                button.textContent = "Unselect Addons"; 
            }
        
            updateInvoiceTotal();

            // Store the selected amenity ID in the hidden input field
            document.getElementById('addons_id').value = addonsId;  // Store addons_id
        });
    });

    // Add an item to the invoice
    function addItemToInvoice(name, price) {
        const invoiceBody = document.querySelector('#invoice tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="text-left">${name}</td>
            <td class="text-right">${price}</td>
        `;
        invoiceBody.appendChild(newRow);
    }

    // Find an existing row by item name
    function findRowByName(name) {
        const rows = document.querySelectorAll('#invoice tbody tr');
        return Array.from(rows).find(row => row.querySelector('td').textContent.trim() === name);
    }

    // Update the total amount on the invoice
    function updateInvoiceTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#invoice tbody tr');
        rows.forEach(row => {
            const priceText = row.querySelector('td:last-child').textContent;
            const price = parseFloat(priceText.replace('₱', '').replace(',', ''));
            total += isNaN(price) ? 0 : price; // Sum the prices, ensuring valid price format
        });

        // Update total in the footer
        const totalElement = document.querySelector('#invoice tfoot #totalPrice');
        totalElement.textContent = `₱${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        // Update the hidden input for total_amount
        const totalAmountInput = document.querySelector('#total_amount');
        totalAmountInput.value = total;
    }
});
