 // Function to handle floating label behavior
 document.querySelectorAll('.peer').forEach(input => {
    const label = input.nextElementSibling;

    input.addEventListener('focus', () => {
      label.classList.add('top-0',  'mt-3');
      label.classList.remove('top-1/2');
    });

    input.addEventListener('blur', () => {
      if (input.value === '') {
        label.classList.remove('top-0', 'mt-3');
        label.classList.add('top-1/2');
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
