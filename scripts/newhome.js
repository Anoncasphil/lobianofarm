document.addEventListener("DOMContentLoaded", () => {
    // Mock data for reserved dates
    const reservedDates = [
      { start: "2025-01-25", end: "2025-01-27" },
      { start: "2025-02-01", end: "2025-02-03" },
      { start: "2025-02-10", end: "2025-02-12" }
    ];

    // Convert reserved date ranges to an array of dates
    const disabledDates = [];
    reservedDates.forEach(range => {
      let start = new Date(range.start);
      let end = new Date(range.end);

      while (start <= end) {
        disabledDates.push(start.toISOString().split('T')[0]); // Store in YYYY-MM-DD format
        start.setDate(start.getDate() + 1);
      }
    });

    // Initialize Flatpickr for check-in and check-out date pickers
    flatpickr("#check-in", {
      disable: disabledDates, // Disable reserved dates
      dateFormat: "Y-m-d", // Format for displaying selected dates
    });

    flatpickr("#check-out", {
      disable: disabledDates, // Disable reserved dates
      dateFormat: "Y-m-d", // Format for displaying selected dates
    });

// Book button click handler
const bookButton = document.getElementById("book-btn");
bookButton.addEventListener("click", () => {
  const checkInDate = document.getElementById("check-in").value;
  const checkOutDate = document.getElementById("check-out").value;

  if (!checkInDate || !checkOutDate) {
    alert("Please select both check-in and check-out dates.");
    return;
  }

  // Redirect to the booking page with selected dates
  const url = `/Admin/landing_page_customer/booking.php?checkin=${checkInDate}&checkout=${checkOutDate}`;
  window.location.href = url;
});
  });

  document.addEventListener("DOMContentLoaded", () => {
    const slideshow = document.getElementById("slideshow");
    const slides = slideshow.children;
    const prev = document.getElementById("prev");
    const next = document.getElementById("next");

    let currentIndex = 0;

    function updateSlide() {
      slideshow.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    prev.addEventListener("click", () => {
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      updateSlide();
    });

    next.addEventListener("click", () => {
      currentIndex = (currentIndex + 1) % slides.length;
      updateSlide();
    });
  });