
  // Slideshow functionality
  const slideshow = document.getElementById("slideshow");
  if (slideshow) {
    const slides = slideshow.children;
    const prev = document.getElementById("prev");
    const next = document.getElementById("next");

    let currentIndex = 0;

    function updateSlide() {
      slideshow.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    if (prev) {
      prev.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlide();
      });
    }

    if (next) {
      next.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlide();
      });
    }
  }

  // Fancybox initialization (if jQuery is loaded)
  if (typeof $ !== 'undefined') {
    $(document).ready(function () {
      $('[data-fancybox="gallery"]').fancybox({
        loop: true,
        arrows: true,
        caption: function (instance, item) {
          return $(this).find('img').attr('alt');
        },
      });
    });
  }


// Function to show the success modal with a message
function showSuccessModal(message) {
  const modal = document.getElementById('successModal');
  if (modal) {
    const messageElement = modal.querySelector('.modal-message');
    messageElement.textContent = message; // Set the success message
    modal.classList.remove('hidden'); // Show the modal
    setTimeout(() => {
      modal.classList.add('hidden'); // Hide the modal after 3 seconds
    }, 3000); // 3000ms = 3 seconds
  }
}

// Function to show error modal
function showErrorModal(message) {
  const modal = document.getElementById("checkInModal");
  if (modal) {
    const modalMessage = document.getElementById("modalMessage");
    modalMessage.textContent = message;
    modal.classList.remove("hidden");
  }
}

// Function to close the modal
function closeModal() {
  const modal = document.getElementById("checkInModal");
  if (modal) {
    modal.classList.add("hidden");
  }
}
// Function to show success modal (for simplicity, just a placeholder function)
function showSuccessModal(message) {
  alert(message);  // Replace with your actual modal implementation
}


// Function to open the modal and display data
function openModal(picture, name, description, hours, checkinTime, checkoutTime, price) {
  // Set modal content
  document.getElementById('modal-picture').src = '../src/uploads/rates/' + picture;
  document.getElementById('modal-name').textContent = name;
  document.getElementById('modal-description').textContent = description;
  document.getElementById('modal-hours').textContent = hours + ' hours';
  document.getElementById('modal-checkin-time').textContent = checkinTime;
  document.getElementById('modal-checkout-time').textContent = checkoutTime;
  document.getElementById('modal-price').textContent = price;

  // Show modal with fade-in effect
  const modal = document.getElementById('rate-modal');
  modal.classList.remove('hidden');
  setTimeout(() => modal.classList.remove('opacity-0'), 10);  // Trigger the fade-in effect
}

// Close the modal when clicking the close button
document.getElementById('close-modal').addEventListener('click', function() {
  const modal = document.getElementById('rate-modal');
  modal.classList.add('opacity-0'); // Fade out
  setTimeout(() => modal.classList.add('hidden'), 500);  // Hide after fade-out transition
});

// Close the modal when clicking outside of the modal
document.getElementById('rate-modal').addEventListener('click', function(event) {
  if (event.target === document.getElementById('rate-modal')) {
    const modal = document.getElementById('rate-modal');
    modal.classList.add('opacity-0'); // Fade out
    setTimeout(() => modal.classList.add('hidden'), 500);  // Hide after fade-out transition
  }
});

  // Function to open the add-on modal and display data with animation
  function openAddonModal(picture, name, description, price) {
    // Set modal content
    document.getElementById('addon-modal-picture').src = '../src/uploads/addons/' + picture;
    document.getElementById('addon-modal-name').textContent = name;
    document.getElementById('addon-modal-description').textContent = description;
    document.getElementById('addon-modal-price').textContent = price;

    // Show modal with fade-in and scale-up effect
    const modal = document.getElementById('addon-modal');
    const modalContent = modal.querySelector('div');
    
    // Remove hidden and apply transition styles
    modal.classList.remove('hidden');
    modal.style.opacity = 0;
    modal.style.transition = "opacity 0.5s ease-in-out";
    modalContent.style.transform = "scale(0.95)";
    modalContent.style.transition = "transform 0.5s ease-in-out";

    setTimeout(function() {
      modal.style.opacity = 1;
      modalContent.style.transform = "scale(1)";
    }, 10);  // Allow styles to take effect before transitioning
  }

  // Close the add-on modal when clicking the close button with smooth transition
  document.getElementById('close-addon-modal').addEventListener('click', function() {
    const modal = document.getElementById('addon-modal');
    const modalContent = modal.querySelector('div');

    // Apply fade-out and scale-down effect
    modal.style.opacity = 0;
    modalContent.style.transform = "scale(0.95)";
    
    // Set a timeout for the animation to complete before hiding the modal
    setTimeout(function() {
      modal.classList.add('hidden');
    }, 500); // Match duration of transition
  });

  // Close the modal when clicking outside of the modal
  document.getElementById('addon-modal').addEventListener('click', function(event) {
    if (event.target === document.getElementById('addon-modal')) {
      const modal = document.getElementById('addon-modal');
      const modalContent = modal.querySelector('div');
      
      modal.style.opacity = 0;
      modalContent.style.transform = "scale(0.95)";
      
      setTimeout(function() {
        modal.classList.add('hidden');
      }, 500); // Match duration of transition
    }
  });

  document.addEventListener("DOMContentLoaded", function() {
    initializeFlatpickr();
  });
  
  async function fetchReservedDates() {
    try {
      const response = await fetch('/api/get_reserved_dates_booking.php');
      const reservedDates = await response.json();
  
      if (!reservedDates || !reservedDates.reservedDaytime || !reservedDates.reservedNighttime || !reservedDates.reservedWholeDay) {
        console.error('Expected structure but received:', reservedDates);
        return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
      }
  
      return reservedDates;
    } catch (error) {
      console.error('Error fetching reserved dates:', error);
      return { reservedDaytime: [], reservedNighttime: [], reservedWholeDay: [] };
    }
  }
  
  async function initializeFlatpickr() {
    const { reservedDaytime, reservedNighttime, reservedWholeDay } = await fetchReservedDates();
  
    const checkInDateInput = document.getElementById("check-in");
  
    // Find dates that should be fully disabled
    const fullyReservedDates = new Set(reservedWholeDay);
  
    reservedDaytime.forEach(date => {
      if (reservedNighttime.includes(date)) {
        fullyReservedDates.add(date);  // Add date only if it's in both lists
      }
    });
  
    // Log fully reserved dates in the console
    console.log("Disabled Dates:", Array.from(fullyReservedDates));
  
    // Initialize Flatpickr
    flatpickr(checkInDateInput, {
      dateFormat: "Y-m-d",
      onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates[0]) {
          console.log(`Check-In Date Selected: ${selectedDates[0].toISOString().split("T")[0]}`);
        }
      },
      disable: [
        { from: "1970-01-01", to: new Date().toISOString().split("T")[0] },  // Disable past dates
        ...Array.from(fullyReservedDates).map(date => ({ from: date, to: date }))  // Disable fully reserved dates
      ]
    });
  
    // Add event listener to the "Book" button
    const bookButton = document.getElementById("book-btn");
    bookButton.addEventListener("click", function(event) {
      event.preventDefault(); // Prevent the default form submission
  
      const selectedDate = checkInDateInput.value; // Get the selected date
  
      if (selectedDate) {
        // Store the selected date in localStorage
        localStorage.setItem("selectedDate", JSON.stringify({ checkIn: selectedDate }));
  
        // Redirect to booking.php
        window.location.href = "/landing_page_customer/booking.php";  // Change path if necessary
      } else {
        alert("Please select a check-in date.");
      }
    });
  }
  
  document.addEventListener("DOMContentLoaded", function() {
    initializeFlatpickr();
  });
  