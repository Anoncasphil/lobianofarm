document.querySelectorAll('.peer').forEach(input => {
    const label = input.nextElementSibling;
  
    input.addEventListener('focus', () => {
      label.classList.add('top-0', '                ');
      label.classList.remove('top-1/2');
    });
  
    input.addEventListener('blur', () => {
      if (input.value === '') {
        label.classList.remove('top-0', 'mt-3');
        label.classList.add('top-1/2');
      }
    });
  
    // Initial check in case the field already has a value
    if (input.value !== '') {
      label.classList.add('top-0', 'mt-3');
      label.classList.remove('top-1/2');
    }
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    // Check if any input already has a value and trigger the label float
    const inputs = document.querySelectorAll('.peer');
    
    inputs.forEach(input => {
      if (input.value !== "") {
        const label = input.nextElementSibling;
        label.classList.add("peer-focus");
      }
    });
  });

  
  function populateForm() {
    try {
        // Retrieve the stored selections from localStorage
        const selectionsJSON = localStorage.getItem('selections');

        // Check if selections exist in localStorage
        if (!selectionsJSON) {
            console.log('❌ No selections found in localStorage');
            return;
        }

        const selections = JSON.parse(selectionsJSON);

        // ✅ Debugging: Log retrieved selections
        console.log('📥 Retrieved selections:', selections);

        // Populate user details if elements exist
        document.getElementById('first-name-p')?.setAttribute('value', selections.user?.firstName || '');
        document.getElementById('last-name-p')?.setAttribute('value', selections.user?.lastName || '');
        document.getElementById('email-p')?.setAttribute('value', selections.user?.email || '');
        document.getElementById('mobile-number-p')?.setAttribute('value', selections.user?.mobileNumber || '');

        // Populate reservation details
        document.getElementById('check-in-date')?.setAttribute('value', selections.reservation?.checkInDate || '');
        document.getElementById('check-out-date')?.setAttribute('value', selections.reservation?.checkOutDate || '');
        document.getElementById('check-in-time')?.setAttribute('value', selections.reservation?.checkInTime || '');
        document.getElementById('check-out-time')?.setAttribute('value', selections.reservation?.checkOutTime || '');

        // Populate rate ID and price
        document.getElementById('rate-id-field')?.setAttribute('value', selections.rate?.rateId || '');
        document.getElementById('rate-price-field')?.setAttribute('value', selections.rate?.ratePrice || '0');

        // Populate add-ons (IDs and Prices)
        document.getElementById('addon-ids-field')?.setAttribute('value', selections.addons?.addonIds?.join(',') || '');
        document.getElementById('addon-prices-field')?.setAttribute('value', selections.addons?.addonPrices?.join(',') || '');

        // Populate extra pax and price
        document.getElementById('extra-pax-field')?.setAttribute('value', selections.reservation?.extraPax || '0');
        document.getElementById('extra-pax-price-field')?.setAttribute('value', selections.reservation?.extraPaxPrice || '0');

        // ✅ Debugging: Log success message
        console.log('✅ Form populated successfully!');
    } catch (error) {
        console.error('❌ Error populating form:', error);
    }
}


// Function to remove an add-on
function removeAddon(event) {
    const addonRow = event.target.closest('tr');
    const addonId = addonRow.dataset.addonId;
    addonRow.remove();

    // Update localStorage
    const selections = JSON.parse(localStorage.getItem('selections'));
    selections.addons = selections.addons.filter(id => id !== addonId);
    localStorage.setItem('selections', JSON.stringify(selections));

    // Recalculate total price
    populateInvoice();
}


window.onload = populateForm;

async function populateInvoice() {
    console.log('🚀 populateInvoice() function is running...');

    // Retrieve selections from localStorage
    const selections = JSON.parse(localStorage.getItem('selections'));
    console.log('📌 Retrieved selections:', selections);

    if (!selections) {
        console.error('❌ No selections found in localStorage');
        return;
    }

    const rateId = selections.rate?.rateId;
    const addonIds = selections.addons?.addonIds || [];
    const extraPax = parseInt(selections.reservation?.extraPax) || 0;
    const extraPaxPrice = parseFloat(selections.reservation?.extraPaxPrice) || 0;

    console.log('✅ Rate ID:', rateId);
    console.log('✅ Add-on IDs:', addonIds);
    console.log('✅ Extra Pax:', extraPax);
    console.log('✅ Extra Pax Price:', extraPaxPrice);

    // Fetch rate data
    const rateData = await fetchDataFromServer(rateId, 'rate');
    if (!rateData || !rateData.price) {
        console.error('❌ Error: rateData is invalid:', rateData);
        return;
    }
    console.log('📌 Retrieved Rate Data:', rateData);

    // Ensure price is a valid number
    const ratePrice = parseFloat(rateData.price) || 0;

    // Fetch add-ons data
    let addonsData = [];
    if (addonIds.length > 0) {
        addonsData = await fetchDataFromServer(addonIds, 'addons');
        console.log('📌 Retrieved Add-ons Data:', addonsData);
    }

    // Get Invoice Elements
    const invoiceItemsDiv = document.getElementById('invoice-items');
    if (!invoiceItemsDiv) {
        console.error('❌ Invoice items table not found in HTML');
        return;
    }

    // Get the current date
    const invoiceDate = new Date().toLocaleDateString();
    document.getElementById('invoice-date').innerText = invoiceDate;

    // Generate invoice number
    const invoiceNo = 'INV-' + Math.floor(Math.random() * 1000000);
    document.getElementById('invoice-no').innerText = invoiceNo;

    // Initialize Invoice Items and Total Price
    let totalPrice = ratePrice;
    let itemHtml = `
        <tr>
            <td class="py-2">Rate</td>
            <td class="py-2">${rateData.name}</td>
            <td class="py-2 text-right">₱${ratePrice.toFixed(2)}</td>
        </tr>
    `;

    // Add Extra Pax if applicable
    if (extraPax > 0) {
        itemHtml += `
            <tr>
                <td class="py-2"></td>
                <td class="py-2">Extra Pax</td>
                <td class="py-2">${extraPax} Pax</td>
                <td class="py-2 text-right">₱${extraPaxPrice.toFixed(2)}</td>
            </tr>
        `;
        totalPrice += extraPaxPrice;
    }

    // Add Add-ons if applicable
    if (addonsData.length > 0) {
        addonsData.forEach(addon => {
            const addonPrice = parseFloat(addon.price) || 0;
            itemHtml += `
                <tr>
                    <td class="py-2">Add-on</td>
                    <td class="py-2">${addon.name}</td>
                    <td class="py-2 text-right">₱${addonPrice.toFixed(2)}</td>
                </tr>
            `;
            totalPrice += addonPrice;
        });
    } else {
        itemHtml += `
            <tr>
                <td class="py-2 text-gray-500" colspan="4">No add-ons selected</td>
            </tr>
        `;
    }

    // Update Invoice Items in HTML
    invoiceItemsDiv.innerHTML = itemHtml;

    // Update total price
    document.getElementById('total-price').innerText = `₱${totalPrice.toFixed(2)}`;

    // Calculate and update downpayment (50% of total price)
    const downpayment = totalPrice / 2;
    document.getElementById('downpayment').innerText = `₱${downpayment.toFixed(2)}`;

    // Store Invoice Data
    const populatedInvoice = {
        invoiceDate,
        invoiceNo,
        items: [
            { category: 'Rate', name: rateData.name, price: ratePrice.toFixed(2) },
            ...(extraPax > 0 ? [{ category: 'Extra Pax', name: `${extraPax} Pax`, price: extraPaxPrice.toFixed(2) }] : []),
            ...addonsData.map(addon => ({
                category: 'Add-on',
                name: addon.name,
                price: parseFloat(addon.price).toFixed(2)
            }))
        ],
        totalPrice: totalPrice.toFixed(2),
        downpayment: downpayment.toFixed(2)
    };

    localStorage.setItem('populatedInvoice', JSON.stringify(populatedInvoice));
    console.log('✅ Final Populated Invoice:', populatedInvoice);
}

// Ensure function runs after the DOM is loaded
window.addEventListener('DOMContentLoaded', () => {
    console.log('📢 DOM fully loaded, running populateInvoice()...');
    populateInvoice();
});


// Function to simulate fetching data (for rate and add-ons)
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

// Function to retrieve and display the stored invoice from localStorage
function showStoredInvoice() {
    const storedInvoice = JSON.parse(localStorage.getItem('populatedInvoice'));
    
    if (storedInvoice) {
        console.log('Retrieved Populated Invoice:', storedInvoice);
    } else {
        console.log('No populated invoice found in localStorage');
    }
}

// Call this function to show the stored invoice after it's populated
populateInvoice();  // Populate and store the invoice
showStoredInvoice();  // Show the stored invoice in the console

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

/**
 * Fetches the rate type from the API using the stored rateId
 * @returns {Promise<string>} - A promise that resolves to the rate type
 */
function fetchRateType() {
    // Retrieve rateId from localStorage
    const storedSelections = JSON.parse(localStorage.getItem('selections'));
    const rateId = storedSelections?.rate?.rateId || '';

    if (!rateId) {
        console.error("No rateId found in localStorage.");
        return Promise.resolve("XX"); // Default fallback
    }

    return fetch(`../api/get_rate_type.php?rate_id=${rateId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.rate_type) {
                return data.rate_type;
            } else {
                throw new Error("Failed to retrieve rate type.");
            }
        })
        .catch(error => {
            console.error('Error fetching rate type:', error);
            return "XX"; // Default fallback
        });
}

/**
 * Generates a unique reservation code based on the retrieved rate type
 * @returns {Promise<string>} - A promise that resolves to the generated reservation code
 */
function generateReservationCode() {
    return fetchRateType().then(rateType => {
        let prefix = "";
        switch (rateType) {
            case "Daytime":
                prefix = "DT";
                break;
            case "Nighttime":
                prefix = "NT";
                break;
            case "WholeDay":
                prefix = "WD";
                break;
            default:
                prefix = "XX"; // Default fallback if rateType is unknown
        }

        let lastNumber = localStorage.getItem("lastReservationNumber") || 0;
        lastNumber = parseInt(lastNumber) + 1;
        localStorage.setItem("lastReservationNumber", lastNumber);

        const formattedNumber = String(lastNumber).padStart(6, "0");
        return `${prefix}-${formattedNumber}`;
    });
}

generateReservationCode().then(reservationCode => {
    console.log("Generated Reservation Code:", reservationCode);
    // You can now use reservationCode where needed
});

/**
 * Displays the generated reservation code in the span with id="code"
 */
function displayReservationCode() {
    generateReservationCode().then(reservationCode => {
        const codeElement = document.getElementById("code");
        if (codeElement) {
            codeElement.textContent = reservationCode;
        } else {
            console.error("Element with ID 'code' not found.");
        }
    });
}

// Call this function when the page loads
document.addEventListener("DOMContentLoaded", displayReservationCode);


function submitReservation() {
    const submitButton = document.getElementById('submitButton');
    submitButton.innerHTML = 'Submitted';
    submitButton.disabled = true;

    const firstName = document.getElementById('first-name-p').value;
    const lastName = document.getElementById('last-name-p').value;
    const email = document.getElementById('email-p').value;
    const mobileNumber = document.getElementById('mobile-number-p').value;
    const checkInDate = document.getElementById('check-in-date').value;
    const checkOutDate = document.getElementById('check-out-date').value;
    const checkInTime = document.getElementById('check-in-time').value;
    const checkOutTime = document.getElementById('check-out-time').value;
    const paymentReceipt = document.getElementById('dropzone-file').files[0];

    if (!paymentReceipt) {
        alert("Please upload the payment receipt.");
        submitButton.innerHTML = 'Submit';
        submitButton.disabled = false;
        return;
    }

    const referenceNumber = document.getElementById('reference-number').value;
    const invoiceDate = new Date().toISOString().split('T')[0];
    const invoiceNumber = document.getElementById('invoice-no').innerText;
    const totalPrice = parseFloat(document.getElementById('total-price').innerText.replace('₱', '').trim());
    const downpayment = totalPrice / 2;

    const selections = JSON.parse(localStorage.getItem('selections'));
    const rateId = selections ? selections.rate.rateId : null;
    const addonIds = selections ? selections.addons : [];

    if (!rateId) {
        alert("Rate is not selected.");
        submitButton.innerHTML = 'Submit';
        submitButton.disabled = false;
        return;
    }

    const amountPaidInput = document.getElementById('amount-paid-input');
    const amountPaid = parseFloat(amountPaidInput.value) || 0;
    const validAmountPaid = amountPaid > 0 ? amountPaid : 0;
    const newTotal = totalPrice - validAmountPaid;

    // Call generateReservationCode to get the reservation code
    generateReservationCode().then(reservationCode => {
        // Append all data to FormData
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
        formData.append('invoice_date', invoiceDate);
        formData.append('invoice_number', invoiceNumber);
        formData.append('total_price', totalPrice);
        formData.append('downpayment', downpayment);
        formData.append('payment_receipt', paymentReceipt);
        formData.append('rate_id', rateId);
        formData.append('addon_ids', JSON.stringify(addonIds));
        formData.append('new_total', newTotal);
        formData.append('amount_paid', validAmountPaid);
        formData.append('reservation_code', reservationCode); // Add reservation code to formData

        // Submit the form
        fetch('submit_reservation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if (result.includes("Reservation successfully added")) {
                sendEmail(); // Call sendEmail only if reservation is successful
                document.getElementById('success-modal').classList.remove('hidden');

                let countdown = 5;
                const countdownElement = document.getElementById('countdown-timer');
                const interval = setInterval(() => {
                    countdownElement.textContent = countdown;
                    if (countdown === 0) {
                        clearInterval(interval);
                        window.location.href = "../index.php"; // Redirect to homepage
                    }
                    countdown--;
                }, 1000);
            } else {
                alert(result); // Show error message
                submitButton.innerHTML = 'Submit';
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitButton.innerHTML = 'Submit';
            submitButton.disabled = false;
        });
    });
}







// Function to redirect to homepage.php when the button is clicked
function redirectHome() {
    window.location.href = "../index.php"; // Redirect to the homepage
}
 // Function to collect the data and send it via AJAX
 function sendEmail() {
    // Get the reservation code from the element with id="code"
    const reservationCode = document.getElementById('code')?.textContent || 'N/A';
    const amountPaid = parseFloat(document.getElementById('amount-paid-input').value) || 0;
    
    var reservationData = {
      first_name: document.getElementById('first-name-p').value,
      last_name: document.getElementById('last-name-p').value,
      email: document.getElementById('email-p').value,
      mobile_number: document.getElementById('mobile-number-p').value,
      check_in_date: document.getElementById('check-in-date').value,
      check_out_date: document.getElementById('check-out-date').value,
      check_in_time: document.getElementById('check-in-time').value,
      check_out_time: document.getElementById('check-out-time').value,
      invoice_date: document.getElementById('invoice-date').textContent,
      invoice_no: document.getElementById('invoice-no').textContent,
      invoice_items: getInvoiceItems(),  // Function to get dynamic invoice items
      total_price: document.getElementById('total-price').textContent.replace('₱', '').trim(),
      reservation_code: reservationCode,
      status: 'Pending',
      valid_amount_paid: amountPaid.toFixed(2) // Add valid amount paid
    };
  
    // Send AJAX request to the PHP script
    $.ajax({
      url: 'send_email.php',  // Path to your PHP script
      type: 'POST',
      data: reservationData,
      success: function(response) {
        const result = JSON.parse(response);
        if (result.status === 'success') {
        //   put alert here
        } else {
          alert('Error: ' + result.message);
        }
      },
      error: function() {
        alert('An error occurred while sending the email.');
      }
    });
  }

  // Function to gather invoice items dynamically (this assumes you've already created the table)
  function getInvoiceItems() {
    let items = '';
    // Assuming you have rows inside #invoice-items in your table
    $('#invoice-items tr').each(function() {
      var category = $(this).find('td:nth-child(1)').text();
      var item = $(this).find('td:nth-child(2)').text();
      var price = $(this).find('td:nth-child(3)').text();
      items += `<tr><td>${category}</td><td>${item}</td><td>${price}</td></tr>`;
    });
    return items;
  }

// Get the input field, the display span for amount paid, and the display for the new total
const amountPaidInput = document.getElementById('amount-paid-input');
const amountPaidDisplay = document.getElementById('amount-paid-display');
const totalPriceElement = document.getElementById('total-price');
const newTotalElement = document.getElementById('new-total');

// Function to update the amount paid and new total
function updateAmountPaidAndNewTotal() {
    // Get the value from the input for amount paid
    const amountPaid = parseFloat(amountPaidInput.value);

    // If the amount paid is a valid number, update the display, otherwise show 0.00
    if (!isNaN(amountPaid)) {
        amountPaidDisplay.innerText = '-' + amountPaid.toFixed(2);
    } else {
        amountPaidDisplay.innerText = '- 0.00';
    }

    // Ensure the total price is retrieved from the total price element and parsed correctly
    const totalPrice = parseFloat(totalPriceElement.innerText.replace('₱', '').trim());

    // Calculate the new total (totalPrice - amountPaid)
    const validAmountPaid = isNaN(amountPaid) ? 0 : amountPaid;
    const newTotal = totalPrice - validAmountPaid;

    // Update the new total display
    newTotalElement.innerText = '₱' + newTotal.toFixed(2); // Format as currency (₱0.00)
}

// Add event listener to update both displays in real time as user types in amount paid input
amountPaidInput.addEventListener('input', updateAmountPaidAndNewTotal);

// Optionally, update on page load to ensure it's displayed correctly if amount paid has a value
updateAmountPaidAndNewTotal();






