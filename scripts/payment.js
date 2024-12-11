flatpickr("#calendar", {
    mode: "range",
    inline: true, // Renders the calendar inline
    dateFormat: "Y-m-d", // Customize the date format
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
    onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
            // Add 1 day to each selected date
            let startDate = new Date(selectedDates[0]);
            let endDate = new Date(selectedDates[1]);
            startDate.setDate(startDate.getDate() + 1); // Add 1 day to start date
            endDate.setDate(endDate.getDate() + 1); // Add 1 day to end date

            // Log the adjusted selected date range
            console.log(`Selected Range: ${startDate.toISOString().slice(0, 10)} to ${endDate.toISOString().slice(0, 10)}`);
        }
    }
});






// Function to update the invoice table
function updateInvoice(itemName, itemPrice) {
    // Get the invoice table body
    const tableBody = document.querySelector('#invoice tbody');
    
    // Create a new row
    const newRow = document.createElement('tr');
    
    // Add the item name and price to the row (format price with commas)
    newRow.innerHTML = `
        <td>${itemName}</td>
        <td>₱${formatPrice(itemPrice)}</td>
    `;
    
    // Append the new row to the table
    tableBody.appendChild(newRow);
    
    // Update the total price
    updateTotalPrice();
}

// Function to calculate the total price
function updateTotalPrice() {
    const tableRows = document.querySelectorAll('#invoice tbody tr');
    let total = 0;
    
    // Sum up all the prices in the table
    tableRows.forEach(row => {
        const priceCell = row.querySelectorAll('td')[1];
        const price = parseFloat(priceCell.textContent.replace('₱', '').replace(/,/g, ''));
        total += price;
    });
    
    // Update the total price in the footer (formatted with commas)
    document.querySelector('#totalPrice').textContent = '₱' + formatPrice(total);
}

// Function to format numbers with commas and 2 decimal places
function formatPrice(price) {
    return parseFloat(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Event listener for the "Select" buttons
function setupSelectButtonListeners() {
    const selectButtons = document.querySelectorAll('#select_btn');
    selectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemName = this.getAttribute('data-name');
            const itemPrice = this.getAttribute('data-price');
            
            // Add the selected item to the invoice
            updateInvoice(itemName, itemPrice);
        });
    });
}

// Call the setup function when the page loads
document.addEventListener('DOMContentLoaded', function() {
    setupSelectButtonListeners();
});
