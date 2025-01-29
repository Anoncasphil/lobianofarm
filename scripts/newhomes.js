document.addEventListener("DOMContentLoaded", () => {
  // Fetch reserved dates
  fetch('get_reserved_dates.php')
    .then(response => response.json())
    .then(reservedDateRanges => {
      const reservedDates = [];
      reservedDateRanges.forEach(range => {
        let start = new Date(range.start);
        let end = new Date(range.end);
        while (start <= end) {
          reservedDates.push(start.toISOString().split('T')[0]);
          start.setDate(start.getDate() + 1);
        }
      });

      const disabledDates = [...reservedDates];
      const today = new Date();
      const startDate = new Date();
      startDate.setDate(today.getDate() - 30);

      let day = new Date(startDate);
      while (day <= today) {
        disabledDates.push(day.toISOString().split('T')[0]);
        day.setDate(day.getDate() + 1);
      }

      // Initialize Flatpickr
      flatpickr("#check-in", {
        disable: disabledDates,
        dateFormat: "Y-m-d",
        onChange: function (selectedDates, dateStr) {
          const formattedDate = formatDate(dateStr);
          document.getElementById("check-in").value = formattedDate;
        },
      });
    })
    .catch(error => console.error('Error fetching reserved dates:', error));

  // Event listener for the Book button
  const bookButton = document.getElementById("book-btn");
  bookButton.addEventListener("click", () => {
    const checkInDate = document.getElementById("check-in").value;

    if (!checkInDate) {
      openModal(); // Show modal if no date is selected
      return;
    }

    // Create a JSON object to store the selected date
    const selectedDate = {
      checkIn: checkInDate,
    };

    // Save the JSON object to localStorage or sessionStorage
    localStorage.setItem("selectedDate", JSON.stringify(selectedDate)); // Persist even after browser closes
    // OR
    // sessionStorage.setItem("selectedDate", JSON.stringify(selectedDate)); // Clear when the session ends

    // Redirect to the next page without using URL parameters
    window.location.href = "booking.php";
  });

  function openModal() {
    const modal = document.getElementById('checkInModal');
    modal.classList.remove('hidden');
    modal.style.animation = 'slideInRight 0.5s ease-out forwards';
    setTimeout(() => modal.classList.add('hidden'), 3000);
  }
});

window.addEventListener('load', function() {
  const container = document.getElementById('rates-container');
  const cards = container.querySelectorAll('.rate-card');
  
  if (cards.length < 3) {
    container.classList.add('justify-center');
  } else {
    container.classList.remove('justify-center');
  }
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

window.addEventListener('load', function() {
  const container = document.getElementById('rates-container');
  const cards = container.querySelectorAll('.rate-card');
  
  if (cards.length < 3) {
    container.classList.add('justify-center');
  } else {
    container.classList.remove('justify-center');
  }
});