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

        fetch(`get_details.php?id=${reservationId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    console.error("Error:", data.message);
                    alert("Error loading reservation details.");
                    return;
                }

                const reservation = data.data;

                // Update Reservation Details Tab
                document.getElementById("modal-reservation-id").textContent = reservation.id;
                document.getElementById("modal-name").textContent = `${reservation.first_name} ${reservation.last_name}`;
                document.getElementById("modal-email").textContent = reservation.email;
                document.getElementById("modal-phone-number").textContent = reservation.contact_number;
                document.getElementById("modal-check-in").textContent = reservation.check_in_date;
                document.getElementById("modal-check-out").textContent = reservation.check_out_date;
                document.getElementById("modal-total-amount").textContent = `₱${reservation.total_price.toFixed(2)}`;

                // Update Invoice Tab (Rate and Addons)
                document.getElementById("modal-rate-name").textContent = reservation.rate_name;
                document.getElementById("modal-rate-price").textContent = `₱${reservation.rate_price.toFixed(2)}`;

                if (reservation.addons_name) {
                    document.getElementById("modal-addons-name").textContent = reservation.addons_name;
                    document.getElementById("modal-addons-price").textContent = `₱${reservation.addons_price.toFixed(2)}`;
                } else {
                    document.getElementById("modal-addons-name").textContent = "None";
                    document.getElementById("modal-addons-price").textContent = "₱0.00";
                }

                // Update Total Price
                const totalPrice = (reservation.rate_price + (reservation.addons_price || 0));
                document.getElementById("modal-total-price").textContent = `₱${totalPrice.toFixed(2)}`;

                // Update Payment Tab
                document.getElementById("modal-payment-proof").setAttribute('src', reservation.payment_receipt);

                // Open the modal
                modal.classList.remove("hidden");
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
