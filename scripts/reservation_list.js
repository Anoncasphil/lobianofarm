document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("reservationModal");
    const closeModal = document.getElementById("closeModal");
    const viewButtons = document.querySelectorAll(".view-button");

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

                // Assuming `data.data` contains the reservation details
                const reservation = data.data;

                // Set reservation details in modal
                document.getElementById("modal-reservation-id").textContent = reservation.reservation_id;
                document.getElementById("modal-name").textContent = `${reservation.first_name} ${reservation.last_name}`;
                document.getElementById("modal-email").textContent = reservation.email;
                document.getElementById("modal-phone-number").textContent = reservation.phone_number;
                document.getElementById("modal-check-in").textContent = reservation.check_in_date;
                document.getElementById("modal-check-out").textContent = reservation.check_out_date;
                document.getElementById("modal-total-amount").textContent = `₱${parseFloat(reservation.total_amount).toFixed(2)}`;

                // Get rate and add-on names, prices
                document.getElementById("modal-rate-name").textContent = reservation.rate_name || 'N/A';
                document.getElementById("modal-addons-name").textContent = reservation.addons_name || 'N/A';
                document.getElementById("modal-rate-price").textContent = `₱${parseFloat(reservation.rate_price).toFixed(2)}`;
                document.getElementById("modal-addons-price").textContent = `₱${parseFloat(reservation.addons_price).toFixed(2)}`;
                

                // Calculate total price (Rate + Add-Ons)
                const totalPrice = parseFloat(reservation.rate_price) + parseFloat(reservation.addons_price);
                document.getElementById("modal-total-price").textContent = `₱${totalPrice.toFixed(2)}`;

                // Set payment proof
                document.getElementById("modal-payment-proof").src = reservation.payment_proof;


                // Show modal
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
    const tabs = document.querySelectorAll(".tab-button");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active", "text-blue-500", "border-blue-500"));
            tab.classList.add("active", "text-blue-500", "border-blue-500");

            contents.forEach(content => content.classList.add("hidden"));
            document.getElementById(tab.dataset.tab).classList.remove("hidden");
        });
    });
});
