window.onload = function() {
    // Call the function to populate the form and invoice
    populateForm();
    populateInvoice();
};

function populateForm() {
    // Your code for populating user and reservation details
    const selectionsJSON = localStorage.getItem('selections');
    if (selectionsJSON) {
        const selections = JSON.parse(selectionsJSON);
        // Populate the fields as before
        document.getElementById('first-name-p').value = selections.user.firstName || '';
        document.getElementById('last-name-p').value = selections.user.lastName || '';
        document.getElementById('email-p').value = selections.user.email || '';
        document.getElementById('mobile-number-p').value = selections.user.mobileNumber || '';
        document.getElementById('check-in-date').value = selections.reservation.checkInDate || '';
        document.getElementById('check-out-date').value = selections.reservation.checkOutDate || '';
        document.getElementById('check-in-time').value = selections.reservation.checkInTime || '';
        document.getElementById('check-out-time').value = selections.reservation.checkOutTime || '';
    }
}

async function populateInvoice() {
// Retrieve the populated invoice from localStorage
const populatedInvoice = JSON.parse(localStorage.getItem('populatedInvoice'));
if (!populatedInvoice) {
    console.log('No populated invoice found in localStorage');
    return;
}

// Display invoice details
document.getElementById('invoice-date').value = populatedInvoice.invoiceDate; // Set value for input
document.getElementById('invoice-no').value = populatedInvoice.invoiceNo;     // Set value for input

// Populate the items table
let itemHtml = '';
populatedInvoice.items.forEach(item => {
    itemHtml += `
        <tr>
            <td>${item.category}</td>
            <td>${item.name}</td>
            <td>₱${item.price}</td>
        </tr>
    `;
});

// Display the total price
document.getElementById('invoice-items').innerHTML = itemHtml;
document.getElementById('total-price').innerText = '₱' + populatedInvoice.totalPrice;
}
