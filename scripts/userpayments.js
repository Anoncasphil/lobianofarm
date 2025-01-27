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
async function populateInvoice() {
    // Retrieve selections from localStorage
    const selections = JSON.parse(localStorage.getItem('selections'));

    if (!selections) {
        console.log('No selections found in localStorage');
        return;  // Exit if no selections are found
    }

    const rateId = selections.rate.rateId;
    const addonIds = selections.addons;

    // Fetch the rate data from a JSON or an API
    const rateData = await fetchDataFromServer(rateId, 'rate');
    
    // Fetch the add-ons data if there are any add-ons
    let addonsData = [];
    if (addonIds.length > 0) {
        addonsData = await fetchDataFromServer(addonIds, 'addons');
    }

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
    if (addonsData.length > 0) {
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

    // Now, create the JSON object based on the populated invoice
    const populatedInvoice = {
        invoiceDate: invoiceDate,
        invoiceNo: invoiceNo,
        items: [
            {
                category: 'Rate',
                name: rateData.name,
                price: rateData.price
            },
            ...addonsData.map(addon => ({
                category: 'Add-on',
                name: addon.name,
                price: addon.price
            }))
        ],
        totalPrice: totalPrice.toFixed(2)
    };

    // Store the populated invoice JSON in localStorage
    localStorage.setItem('populatedInvoice', JSON.stringify(populatedInvoice));

    // Log the populated invoice for debugging
    console.log('Populated invoice stored:', populatedInvoice);
}

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

    // Create FormData to send the reservation data
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

    // Send reservation data using Fetch API
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

            // Send the confirmation email after the reservation is successful
            sendEmail(firstName, lastName, email, referenceNumber, invoiceNumber);
        } else {
            alert(result); // Handle error or failure
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Function to send email (you need to implement this function on your server or use an API)
function sendEmail(firstName, lastName, email, referenceNumber, invoiceNumber) {
    // Example of email details you might want to include in the email
    const emailBody = `
        Reservation Confirmation:
        Name: ${firstName} ${lastName}
        Email: ${email}
        Reference Number: ${referenceNumber}
        Invoice Number: ${invoiceNumber}
        Thank you for your reservation!`;

    // You can use a server-side service or API to send the email
    // This example assumes you have a function that sends the email.
    fetch('send_email.php', {
        method: 'POST',
        body: JSON.stringify({
            to: email,
            subject: "Reservation Confirmation",
            body: emailBody
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.text())
    .then(result => {
        console.log('Email sent:', result);
    })
    .catch(error => {
        console.error('Error sending email:', error);
    });
}




// Function to redirect to homepage.php when the button is clicked
function redirectHome() {
    window.location.href = "homepage.php"; // Redirect to the homepage
}



// Function to collect form and invoice data and send it to PHP
 // Function to collect the data and send it via AJAX
 function sendEmail() {
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
      total_price: document.getElementById('total-price').textContent.replace('₱', '').trim()
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









