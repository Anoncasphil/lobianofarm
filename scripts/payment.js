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
    const selectButtons = document.querySelectorAll('#select_btn');

    // Event listener for each 'Select' button
    selectButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 
            
            // Determine whether the card is a rates_card or amenities_card
            const card = button.closest('#rates_card') || button.closest('#amenities_card');
            
            // Get the name (h1) and price (p with class 'text-lg') from the selected card
            const name = card.querySelector('h1').textContent.trim();  // Name of the card (item)
            const price = card.querySelector('.text-lg').textContent.trim();  // Price of the card (item)
    
            // Check if the item is already in the invoice
            const existingRow = findRowByName(name);
    
            if (existingRow) {
                // If the item already exists, remove it from the invoice
                existingRow.remove();
                button.textContent = "Select"; // Change button text back to "Select"
            } else {
                // If the item doesn't exist, add it to the invoice
                addItemToInvoice(name, price);
                button.textContent = "Unselect"; // Change button text to "Unselect"
            }
    
            // Update the total price in the invoice
            updateInvoiceTotal();
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
    

    // Optional: Event listener for the "Book" button (if present) to confirm the booking
    const bookButton = document.querySelector('#calendar_side button');
    if (bookButton) {
        bookButton.addEventListener('click', function () {
            const totalAmount = document.querySelector('#invoice tfoot #totalPrice').textContent;
            alert(`Your booking has been confirmed! Total Amount: ${totalAmount}`);
        });
    }
});
