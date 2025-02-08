function storeReservationId(reservationId) {
    // Store the reservation ID as JSON in localStorage
    localStorage.setItem('reservation_id', JSON.stringify(reservationId));
    // Redirect to the details page
    window.location.href = 'customer_reservation_details.php';
}

// Tab functionality
document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab-button");
    const contents = document.querySelectorAll(".tab-content");

    // Set the initial active tab to "Pending"
    document.querySelector('[data-tab="pending"]').classList.add("bg-blue-500", "text-white");
    document.getElementById("pending").classList.remove("hidden");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("bg-blue-500", "text-white"));
            tabs.forEach(t => t.classList.add("bg-gray-200", "text-gray-800"));
            tab.classList.add("bg-blue-500", "text-white");

            contents.forEach(content => content.classList.add("hidden"));
            document.getElementById(tab.dataset.tab).classList.remove("hidden");
        });
    });
});