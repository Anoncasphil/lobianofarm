document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("reservationModal");
    const closeModal = document.getElementById("closeModal");
    const viewButtons = document.querySelectorAll(".view-button");
    const tabs = document.querySelectorAll(".tab-button");
    const contents = document.querySelectorAll(".tab-content");

    // Check if all elements exist
    if (!modal || !closeModal || viewButtons.length === 0 || tabs.length === 0 || contents.length === 0) {
        console.error("One or more required elements are missing.");
        return;
    }

    function openModal(reservationId) {
        if (!reservationId) {
            console.error("Invalid reservation ID.");
            alert("Reservation ID is missing.");
            return;
        }

        console.log(`Fetching details for reservation ID: ${reservationId}`);

        fetch(`../reservation/get_details.php?id=${reservationId}`) // Corrected path
            .then(response => response.text()) // Fetch as text first
            .then(text => {
                try {
                    const data = JSON.parse(text); // Parse the JSON
                    console.log("Response data:", data);

                    if (data.status === 'error') {
                        console.error("Error:", data.message);
                        alert("Error loading reservation details.");
                        return;
                    }

                    const reservation = data.data;

                    // Update Reservation Details Tab
                    console.log("Updating Reservation Details Tab with:", reservation);
                    if (reservation.id) {
                        document.getElementById("modal-reservation-id").textContent = reservation.id;
                        console.log("Reservation ID:", reservation.id);
                    }
                    if (reservation.first_name && reservation.last_name) {
                        document.getElementById("modal-name").textContent = `${reservation.first_name} ${reservation.last_name}`;
                        console.log("Name:", `${reservation.first_name} ${reservation.last_name}`);
                    }
                    if (reservation.email) {
                        document.getElementById("modal-email").textContent = reservation.email;
                        console.log("Email:", reservation.email);
                    }
                    if (reservation.contact_number) {
                        document.getElementById("modal-phone-number").textContent = reservation.contact_number;
                        console.log("Phone Number:", reservation.contact_number);
                    }
                    if (reservation.check_in_date) {
                        document.getElementById("modal-check-in").textContent = reservation.check_in_date;
                        console.log("Check-in Date:", reservation.check_in_date);
                    }
                    if (reservation.check_out_date) {
                        document.getElementById("modal-check-out").textContent = reservation.check_out_date;
                        console.log("Check-out Date:", reservation.check_out_date);
                    }
                    if (reservation.total_price) {
                        document.getElementById("modal-total-amount").textContent = `₱${reservation.total_price.toFixed(2)}`;
                        console.log("Total Amount:", `₱${reservation.total_price.toFixed(2)}`);
                    }

                    // Update Invoice Tab (Rate and Addons)
                    if (reservation.rate.name) {
                        document.getElementById("modal-rate-name").textContent = reservation.rate.name;
                        console.log("Rate Name:", reservation.rate.name);
                    }
                    if (reservation.rate.price) {
                        document.getElementById("modal-rate-price").textContent = `₱${reservation.rate.price.toFixed(2)}`;
                        console.log("Rate Price:", `₱${reservation.rate.price.toFixed(2)}`);
                    }

                    if (reservation.addons && reservation.addons.length > 0) {
                        const addonsNames = reservation.addons.map(addon => addon.name).join("<br>");
                        const addonsPrices = reservation.addons.map(addon => `₱${parseFloat(addon.price).toFixed(2)}`).join("<br>");
                        document.getElementById("modal-addons-name").innerHTML = addonsNames;
                        document.getElementById("modal-addons-price").innerHTML = addonsPrices;
                        console.log("Addons Names:", addonsNames);
                        console.log("Addons Prices:", addonsPrices);
                    } else {
                        document.getElementById("modal-addons-name").textContent = "No add-ons selected";
                        document.getElementById("modal-addons-price").textContent = "₱0.00";
                    }

                    // Update Total Price
                    const totalPrice = reservation.rate.price + (reservation.addons ? reservation.addons.reduce((sum, addon) => sum + parseFloat(addon.price), 0) : 0);
                    document.getElementById("modal-total-price").textContent = `₱${totalPrice.toFixed(2)}`;
                    console.log("Total Price:", `₱${totalPrice.toFixed(2)}`);

                    // Update Payment Tab
                    if (reservation.payment_receipt) {
                        document.getElementById("modal-payment-proof").setAttribute('src', reservation.payment_receipt);
                    } else {
                        document.getElementById("modal-payment-proof").setAttribute('src', '');
                        document.getElementById("modal-payment-proof").alt = 'No payment proof available';
                    }

                    // Open the modal
                    modal.classList.remove("hidden");
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                    alert("Unable to load reservation details. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error fetching reservation details:", error);
                alert("Unable to load reservation details. Please try again.");
            });
    }

    // Attach click event to all view buttons
    viewButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            const reservationId = e.target.dataset.id;  // Use data-id attribute from button
            openModal(reservationId);
        });
    });

    // Close modal when the close button is clicked
    closeModal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Handle tab switching inside the modal
    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active", "text-blue-500", "border-blue-500"));
            tab.classList.add("active", "text-blue-500", "border-blue-500");

            contents.forEach(content => content.classList.add("hidden"));
            document.getElementById(tab.dataset.tab)?.classList.remove("hidden");
        });
    });

    // Sort reservations by id in ascending order
    const reservationRows = Array.from(document.querySelectorAll("tbody tr"));
    reservationRows.sort((a, b) => {
        const idA = parseInt(a.querySelector("td:first-child").textContent);
        const idB = parseInt(b.querySelector("td:first-child").textContent);
        return idA - idB;
    });

    const tbody = document.querySelector("tbody");
    reservationRows.forEach(row => tbody.appendChild(row));
});
