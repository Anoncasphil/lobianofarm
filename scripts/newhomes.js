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

  // Initialize Flatpickr for the check-in date picker
  flatpickr("#check-in", {
    disable: disabledDates, // Disable reserved dates
    dateFormat: "Y-m-d", // Format for displaying selected dates
  });

// Function to open the modal
function openModal() {
  const modal = document.getElementById('checkInModal');
  modal.classList.remove('hidden'); // Show the modal

  // Apply the slide-in animation
  modal.style.animation = 'slideInRight 0.5s ease-out forwards';

  // Hide the modal after 3 seconds with the slide-out effect
  setTimeout(() => {
    modal.classList.add('hidden'); // This will trigger the slide-out animation
  }, 3000);
}

// Book button click handler
const bookButton = document.getElementById("book-btn");
bookButton.addEventListener("click", () => {
  const checkInDate = document.getElementById("check-in").value;

  if (!checkInDate) {
    openModal(); // Show modal instead of alert
    return;
  }

  // Redirect to the booking page with the selected check-in date
  const url = `/Admin/landing_page_customer/booking.php?checkin=${checkInDate}`;
  window.location.href = url;
});





  // Slideshow functionality
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

  $(document).ready(function() {
    $('[data-fancybox="gallery"]').fancybox({
      loop: true,  // Allow looping through images
      arrows: true,  // Show navigation arrows
      caption: function(instance, item) {
        return $(this).find('img').attr('alt');  // Use the alt text as caption
      }
    });
  });

});

// Function to show the success modal with a message
function showSuccessModal(message) {
  const modal = document.getElementById('successModal');
  const messageElement = modal.querySelector('.modal-message');
  messageElement.textContent = message; // Set the success message
  modal.classList.remove('hidden'); // Show the modal
  setTimeout(() => {
    modal.classList.add('hidden'); // Hide the modal after 3 seconds
  }, 3000); // 3000ms = 3 seconds
}

// Function to show the error modal with a message
function showErrorModal(message) {
  const modal = document.getElementById('errorModal');
  const messageElement = modal.querySelector('.modal-message');
  messageElement.textContent = message; // Set the error message
  modal.classList.remove('hidden'); // Show the modal
  setTimeout(() => {
    modal.classList.add('hidden'); // Hide the modal after 3 seconds
  }, 3000); // 3000ms = 3 seconds
}

// Book button click handler
const bookButton = document.getElementById("book-btn");
bookButton.addEventListener("click", () => {
  const checkInDate = document.getElementById("check-in").value;

  if (!checkInDate) {
    showErrorModal("Please select a check-in date."); // Show error modal if no date selected
    return;
  }

  // Redirect to the booking page with the selected check-in date
  const url = `/Admin/landing_page_customer/booking.php?checkin=${checkInDate}`;
  showSuccessModal("Check-in date selected successfully!"); // Show success modal
  setTimeout(() => {
    window.location.href = url; // Redirect after showing success
  }, 3000); // Wait for the success modal to disappear before redirect
});

// JavaScript to hide the preloader once the page is fully loaded
window.addEventListener("load", function() {
  // Hide the preloader after the page is loaded
  document.getElementById('preloader').style.display = 'none';
});