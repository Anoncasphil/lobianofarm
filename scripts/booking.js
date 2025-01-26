// Function to handle floating label behavior
document.querySelectorAll('.peer').forEach(input => {
  const label = input.nextElementSibling;

  input.addEventListener('focus', () => {
    label.classList.add('top-0', 'mt-3');
    label.classList.remove('top-1/2');
  });

  input.addEventListener('blur', () => {
    if (input.value === '') {
      label.classList.remove('top-0', 'mt-3');
      label.classList.add('top-1/2');
    }
  });

  // Initial check in case the field already has a value
  if (input.value !== '') {
    label.classList.add('top-0', 'mt-3');
    label.classList.remove('top-1/2');
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Check if any input already has a value and trigger the label float
  const inputs = document.querySelectorAll('.peer');
  
  inputs.forEach(input => {
    if (input.value !== "") {
      const label = input.nextElementSibling;
      label.classList.add("peer-focus");
    }
  });
});


  document.addEventListener('DOMContentLoaded', function () {
    // Function to open the modal with animation
    function openModal(rate) {
      const modal = document.getElementById('modal');
      const modalTitle = document.getElementById('modalTitle');
      const modalDescription = document.getElementById('modalDescription');
      const modalPrice = document.getElementById('modalPrice');
  
      console.log("openModal triggered with rate: ", rate); // Log when openModal is triggered
  
      // Set the modal content based on the clicked card
      if (rate === 'rate1') {
        modalTitle.textContent = 'Rate 1 Details';
        modalDescription.textContent = 'Detailed description for Rate 1.';
        modalPrice.textContent = '$50';
      } else if (rate === 'rate2') {
        modalTitle.textContent = 'Rate 2 Details';
        modalDescription.textContent = 'Detailed description for Rate 2.';
        modalPrice.textContent = '$75';
      } else if (rate === 'rate3') {
        modalTitle.textContent = 'Rate 3 Details';
        modalDescription.textContent = 'Detailed description for Rate 3.';
        modalPrice.textContent = '$100';
      }
  
      // Show the modal with animation by adding 'show' class
      modal.classList.add('show');
      console.log("Modal class added: ", modal.classList); // Log the modal's classes after adding 'show'
    }
  
    // Function to close the modal with animation
    function closeModal() {
      const modal = document.getElementById('modal');
  
      console.log("closeModal triggered"); // Log when closeModal is triggered
  
      // Remove the 'show' class to trigger the closing animation
      modal.classList.remove('show');
      console.log("Modal class removed: ", modal.classList); // Log the modal's classes after removing 'show'
    }
  
    // Expose the functions globally
    window.openModal = openModal;
    window.closeModal = closeModal;
  });
  
  let currentSlide = 0;

function moveCarousel(direction) {
  const carousel = document.getElementById('carousel');
  const totalCards = carousel.children.length;
  const cardWidth = carousel.children[0].offsetWidth; // Get the width of a card
  const maxSlides = totalCards - 3; // Allow for only 3 cards visible at a time

  currentSlide += direction;

  // Ensure the currentSlide stays within bounds
  if (currentSlide < 0) {
    currentSlide = 0;
  } else if (currentSlide > maxSlides) {
    currentSlide = maxSlides;
  }

  // Move the carousel by updating the transform property
  carousel.style.transform = `translateX(-${currentSlide * cardWidth}px)`;
}


  document.addEventListener("DOMContentLoaded", function () {
    fetch("populate_user_data.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Populate fields with user data
          document.getElementById("first-name").value = data.data.first_name || "";
          document.getElementById("last-name").value = data.data.last_name || "";
          document.getElementById("email").value = data.data.email || "";
          document.getElementById("mobile-number").value = data.data.contact_no || "";
        } else {
          console.error("Error fetching user data:", data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  });

  let selectedRate = null;  // To track the currently selected rate

  function selectRate(rateId, rateName, ratePrice) {
      const rateCard = document.querySelector(`[data-id='${rateId}']`);
      const allRateCards = document.querySelectorAll('.rate-card');
      const totalPriceElement = document.getElementById("total-price");
      const itemList = document.getElementById("selected-items");
      const priceList = document.getElementById("selected-prices");
      const rateButton = rateCard.querySelector(".select-button");
  
      // If there's a previously selected rate, unselect it and restore all rate cards
      if (selectedRate && selectedRate !== rateCard) {
          // Deselect the previously selected rate
          selectedRate.classList.remove('opacity-50', 'pointer-events-none');
          selectedRate.querySelector(".select-button").classList.remove('bg-red-700', 'text-white', 'hover:bg-red-900');
          selectedRate.querySelector(".select-button").classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');  // Restore original button color and hover
          selectedRate.querySelector(".select-button").textContent = 'Select';  // Change button text back to 'Select'
      }
  
      // If the same rate is clicked again, unselect it
      if (selectedRate === rateCard) {
          selectedRate = null;
          rateCard.classList.remove('opacity-50', 'pointer-events-none');  // Remove the grey-out effect
          itemList.innerHTML = '';
          priceList.innerHTML = '';
          totalPriceElement.textContent = '₱0.00';
  
          // Reset button text and color
          rateButton.textContent = 'Select';
          rateButton.classList.remove('bg-red-700', 'text-white', 'hover:bg-red-900');
          rateButton.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
  
          // Enable all rate cards when unselected
          allRateCards.forEach(card => {
              card.classList.remove('opacity-50', 'pointer-events-none');
          });
      } else {
          // Select the new rate
          selectedRate = rateCard;
          rateCard.classList.add('opacity-50', 'pointer-events-none');  // Disable the selected card
          rateButton.classList.remove('bg-blue-600');
          rateButton.classList.add('bg-red-700', 'text-white', 'hover:bg-red-900');  // Change button color to red with hover effect
          rateButton.textContent = 'Unselect';  // Change button text to 'Unselect'
  
          // Add selected rate details to the summary
          itemList.innerHTML = `<li>${rateName}</li>`;
          priceList.innerHTML = `<li>₱${ratePrice}</li>`;
  
          // Update the total price
          totalPriceElement.textContent = `₱${ratePrice}`;
  
          // Disable all other rate cards when one is selected
          allRateCards.forEach(card => {
              if (card !== rateCard) {
                  card.classList.add('opacity-50', 'pointer-events-none');  // Grey out non-selected cards
              } else {
                  card.classList.remove('opacity-50', 'pointer-events-none');  // Ensure the selected card is interactive
              }
          });
      }
  }
  
  // Open modal function for the Info button
  function openModal(rateId) {
      // Open your modal here
      console.log("Open modal for rate id:", rateId);
  }
  