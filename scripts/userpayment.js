function populateForm() {
    // Retrieve the stored selections from localStorage
    const selectionsJSON = localStorage.getItem('selections');

    // Check if selections exist in localStorage
    if (selectionsJSON) {
        const selections = JSON.parse(selectionsJSON);

        // Populate user details
        document.getElementById('first-name-p').value = selections.user.firstName || '';
        document.getElementById('last-name-p').value = selections.user.lastName || '';
        document.getElementById('email-p').value = selections.user.email || '';
        document.getElementById('mobile-number-p').value = selections.user.mobileNumber || '';

        // Populate reservation details
        document.getElementById('check-in-date').value = selections.reservation.checkInDate || '';
        document.getElementById('check-out-date').value = selections.reservation.checkOutDate || '';
        document.getElementById('check-in-time').value = selections.reservation.checkInTime || '';
        document.getElementById('check-out-time').value = selections.reservation.checkOutTime || '';

        // Optional: Populate rate and add-ons (if needed)
        document.getElementById('rate-id-field').value = selections.rate.rateId || '';
        document.getElementById('addon-ids-field').value = selections.addons.join(',') || '';
        
        // Optional: Log the populated data for debugging
        console.log('Form populated with selections:', selections);
    } else {
        console.log('No selections found in localStorage');
    }
}

// Call the function when the page loads
window.onload = populateForm;

// Fetch invoice data and populate the form
// Fetch invoice data and populate the form
async function populateInvoice() {
    // Retrieve the selections from localStorage
    const selections = JSON.parse(localStorage.getItem('selections'));
    
    if (!selections) {
        console.log('No selections found in localStorage');
        return;
    }

    const rateId = selections.rate.rateId;
    const addonIds = selections.addons;

    // Fetch the rate data
    const rateData = await fetchDataFromServer(rateId, 'rate');
    
    // Get the current date
    const invoiceDate = new Date().toLocaleDateString();
    document.getElementById('invoice-date').innerText = invoiceDate;

    // Generate invoice number (auto-generated, simple approach)
    const invoiceNo = 'INV-' + Math.floor(Math.random() * 1000000);
    document.getElementById('invoice-no').innerText = invoiceNo;

    // Populate the rate section
    const invoiceItemsDiv = document.getElementById('invoice-items');
    let totalPrice = parseFloat(rateData.price);
    let itemHtml = `
        <tr>
            <td class="py-2">Rate</td>
            <td class="py-2">${rateData.name}</td>
            <td class="py-2 text-right">${rateData.price}</td>
        </tr>
    `;
    invoiceItemsDiv.innerHTML = itemHtml;

    // Populate add-ons section
    if (addonIds.length > 0) {
        const addonsData = await fetchDataFromServer(addonIds, 'addons');
        addonsData.forEach(addon => {
            itemHtml += `
                <tr>
                    <td class="py-2">Add-on</td>
                    <td class="py-2">${addon.name}</td>
                    <td class="py-2 text-right">${addon.price}</td>
                </tr>
            `;
            totalPrice += parseFloat(addon.price);
        });
        invoiceItemsDiv.innerHTML = itemHtml;
    } else {
        itemHtml += `
            <tr>
                <td class="py-2 text-gray-500" colspan="3">No add-ons selected</td>
            </tr>
        `;
        invoiceItemsDiv.innerHTML = itemHtml;
    }

    // Update the total price
    document.getElementById('total-price').innerText = totalPrice.toFixed(2);
}

// Fetch data from the server
async function fetchDataFromServer(ids, type) {
    const response = await fetch('invoice.php', {
        method: 'POST',
        body: JSON.stringify({ ids: ids, type: type }),
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    return data;
}

// Call the function to populate the invoice
populateInvoice();
// Select the file input and preview div
const fileInput = document.getElementById('dropzone-file');
const previewDiv = document.getElementById('preview');

// Listen for changes in the file input
fileInput.addEventListener('change', function(event) {
  const file = event.target.files[0]; // Get the first selected file
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      // Set the background image of the preview div
      previewDiv.style.backgroundImage = `url(${e.target.result})`;
    };
    reader.readAsDataURL(file); // Read the file as a data URL
  }
});

// Function to handle the reservation submission
function submitReservation() {
    // Gather values from input fields
    const firstName = document.getElementById('first-name-p').value;
    const lastName = document.getElementById('last-name-p').value;
    const email = document.getElementById('email-p').value;
    const mobileNumber = document.getElementById('mobile-number-p').value;
    const checkInDate = document.getElementById('check-in-date').value;
    const checkOutDate = document.getElementById('check-out-date').value;
    const checkInTime = document.getElementById('check-in-time').value;
    const checkOutTime = document.getElementById('check-out-time').value;

    // Get the file input for payment receipt
    const paymentReceipt = document.getElementById('dropzone-file').files[0];

    if (!paymentReceipt) {
        alert("Please upload the payment receipt.");
        return;
    }

    // Retrieve additional values from the invoice section
    const referenceNumber = document.getElementById('reference-number').value;
    const invoiceDate = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
    const invoiceNumber = document.getElementById('invoice-no').innerText;
    const totalPrice = parseFloat(document.getElementById('total-price').innerText.replace('₱', '').trim()); // Assuming the price is formatted

    // Gather rate and addon information from the selections
    const selections = JSON.parse(localStorage.getItem('selections'));
    const rateId = selections ? selections.rate.rateId : null;
    const addonIds = selections ? selections.addons : []; // Array of selected addon IDs

    if (!rateId) {
        alert("Rate is not selected.");
        return;
    }

    // Create FormData to send the data
    const formData = new FormData();
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('email', email);
    formData.append('mobile_number', mobileNumber);
    formData.append('check_in_date', checkInDate);
    formData.append('check_out_date', checkOutDate);
    formData.append('check_in_time', checkInTime);
    formData.append('check_out_time', checkOutTime);
    formData.append('reference_number', referenceNumber);
    formData.append('invoice_date', invoiceDate); // Use current date as invoice date
    formData.append('invoice_number', invoiceNumber);
    formData.append('total_price', totalPrice);
    formData.append('payment_receipt', paymentReceipt); // Append the image
    formData.append('rate_id', rateId); // Append the selected rate ID
    formData.append('addon_ids', JSON.stringify(addonIds)); // Append the selected addon IDs as a JSON string

    // Send data using Fetch API
    fetch('submit_reservation.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result.includes("Reservation successfully added")) {
            // Show the success modal
            document.getElementById('success-modal').classList.remove('hidden');

            // Countdown for redirect
            let countdown = 5;
            const countdownElement = document.getElementById('countdown-timer');
            const interval = setInterval(() => {
                countdownElement.textContent = countdown;
                if (countdown === 0) {
                    clearInterval(interval);
                    window.location.href = "homepage.php"; // Redirect to the home page after countdown
                }
                countdown--;
            }, 1000); // Update every second
        } else {
            alert(result); // Handle error or failure
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Function to redirect to homepage.php when the button is clicked
function redirectHome() {
    window.location.href = "homepage.php"; // Redirect to the homepage
}





