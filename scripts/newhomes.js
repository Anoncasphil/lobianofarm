// Function to format the date as mm/dd/yyyy
function formatDate(date) {
  const d = new Date(date);
  const month = String(d.getMonth() + 1).padStart(2, '0');  // Add leading zero if needed
  const day = String(d.getDate()).padStart(2, '0');  // Add leading zero if needed
  const year = d.getFullYear();
  return `${month}/${day}/${year}`;  // Return the formatted date
}

document.addEventListener("DOMContentLoaded", () => {
  // Fetch the reserved dates from the PHP script
  fetch('get_reserved_dates.php') // Adjust the path to your PHP endpoint
    .then(response => response.json())
    .then(reservedDateRanges => {
      // Parse the reserved dates into an array
      const reservedDates = [];

      reservedDateRanges.forEach(range => {
        let start = new Date(range.start);
        let end = new Date(range.end);

        // Add all dates within the range to reservedDates
        while (start <= end) {
          reservedDates.push(start.toISOString().split('T')[0]); // Format as YYYY-MM-DD
          start.setDate(start.getDate() + 1); // Increment day
        }
      });

      // Initialize the disabledDates array
      const disabledDates = [...reservedDates];

      // Disable 30 days before today
      const today = new Date();
      const startDate = new Date();
      startDate.setDate(today.getDate() - 30); // 30 days before today

      // Add all dates 30 days before today to disabledDates
      let day = new Date(startDate);
      while (day <= today) {
        disabledDates.push(day.toISOString().split('T')[0]); // Format as YYYY-MM-DD
        day.setDate(day.getDate() + 1); // Increment day
      }

      // Initialize Flatpickr
      flatpickr("#check-in", {
        disable: disabledDates, // Disable the dates in the array
        dateFormat: "m/d/Y", // Set the format to mm/dd/yyyy
        onChange: function(selectedDates, dateStr, instance) {
          // Format the selected date as mm/dd/yyyy and update the input value
          const formattedDate = formatDate(dateStr);
          document.getElementById("check-in").value = formattedDate;
        },
      });
    })
    .catch(error => {
      console.error('Error fetching reserved dates:', error);
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

    // Format the date for the URL (mm/dd/yyyy)
    const formattedDateForUrl = checkInDate.split('/').reverse().join('-');

    // Redirect to the booking page with the selected check-in date
    const url = `/Admin/landing_page_customer/booking.php?checkin=${formattedDateForUrl}`;
    window.location.href = url;
  });
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


// Book button click handler
// Book button click handler
const bookButton = document.getElementById("book-btn");
bookButton.addEventListener("click", () => {
  const checkInDate = document.getElementById("check-in").value;

  // Check if a date is selected
  if (!checkInDate) {
    showErrorModal("Please select a check-in date."); // Show error modal if no date selected
    return;
  }

  // Get today's date in YYYY-MM-DD format
  const today = new Date().toISOString().split('T')[0]; // Format the date as YYYY-MM-DD

  // Check if the selected date is in the past or the current date
  if (checkInDate <= today) {
    showErrorModal("Date input is invalid. Please select a future date."); // Show error modal for invalid date
    return;
  }

  // Redirect to the booking page with the selected check-in date
  const url = `/Admin/landing_page_customer/booking.php?checkin=${checkInDate}`;
  showSuccessModal("Check-in date selected successfully!"); // Show success modal
  setTimeout(() => {
    window.location.href = url; // Redirect after showing success
  }, 3000); // Wait for the success modal to disappear before redirect
});

// Function to show error modal
function showErrorModal(message) {
  const modal = document.getElementById("checkInModal");
  const modalMessage = document.getElementById("modalMessage");

  // Set the error message in the modal
  modalMessage.textContent = message;

  // Show the modal
  modal.classList.remove("hidden");
}

// Function to close the modal
function closeModal() {
  const modal = document.getElementById("checkInModal");
  modal.classList.add("hidden");
}

// Function to show success modal (for simplicity, just a placeholder function)
function showSuccessModal(message) {
  alert(message);  // Replace with your actual modal implementation
}
