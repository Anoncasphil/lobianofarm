
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

  async function fetchReservedDates() {
    try {
      const response = await fetch('api/get_reserved_dates_booking.php');
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
  
  async function fetchDisabledDates() {
    try {
      const response = await fetch('api/get_disabled_dates.php');
      const disabledDates = await response.json();
  
      console.log("Fetched Disabled Dates:", disabledDates);
  
      // Ensure the structure is correct
      if (!disabledDates || !Array.isArray(disabledDates.disableDates)) {
        console.error('Expected an array for disabled dates but received:', disabledDates);
        return [];
      }
  
      // If it's an object with a key "disableDates", map the dates
      const dates = disabledDates.disableDates.map(item => item.date);  // Directly map the 'date' field from each object
  
      console.log("Mapped Disabled Dates:", dates);
  
      return dates; // Return an array of date strings
    } catch (error) {
      console.error('Error fetching disabled dates:', error);
      return [];
    }
  }
  
  async function initializeFlatpickr() {
    const { reservedDaytime, reservedNighttime, reservedWholeDay } = await fetchReservedDates();
    const disabledDates = await fetchDisabledDates();

    const checkInDateInput = document.getElementById("check-in");

    const nighttimeSet = new Set(reservedNighttime);
    const fullyReservedDates = new Set();

    // Combine reserved dates (daytime + nighttime = fully booked)
    reservedDaytime.forEach(date => {
        if (nighttimeSet.has(date)) {
            fullyReservedDates.add(date);
        }
    });

    reservedWholeDay.forEach(date => {
        fullyReservedDates.add(date);
    });

    // Add server-disabled dates to the fullyReservedDates set
    disabledDates.forEach(date => {
        console.log("Adding Disabled Date:", date);
        fullyReservedDates.add(date);
    });

    console.log("Formatted Disable Dates for Flatpickr:", Array.from(fullyReservedDates));

    // Ensure all dates are in "YYYY-MM-DD" format
    const disableDatesFormatted = Array.from(fullyReservedDates).map(date => date.split("T")[0]);

    console.log("Final Disabled Dates:", disableDatesFormatted);

    // Get today's date in "YYYY-MM-DD" format
    const today = new Date().toISOString().split("T")[0];

    // Get the current date in "YYYY-MM-DD" format
    const currentDate = new Date().toISOString().split("T")[0];

    // Initialize Flatpickr with the updated `disable` configuration
    flatpickr(checkInDateInput, {
        dateFormat: "Y-m-d",
        disable: [
            // Disable all dates before today (including 50 years ago)
            { from: "1970-01-01", to: currentDate },
            // Disable reserved dates
            ...reservedWholeDay.map(date => ({ from: date, to: date })),
            ...reservedDaytime.filter(date => reservedNighttime.includes(date)).map(date => ({
                from: date,
                to: date
            })),
            // Disable server-disabled dates
            ...disabledDates.map(date => ({ from: date, to: date }))
        ],
        minDate: today, // Ensure users can only select today or future dates
        onChange: function (selectedDates) {
            if (selectedDates[0]) {
                console.log(`Check-In Date Selected: ${selectedDates[0].toISOString().split("T")[0]}`);
            }
        },

    });
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM Loaded. Initializing Flatpickr...");
    initializeFlatpickr();  // Ensure this is called once the DOM is fully loaded
});
