document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menu_toggle");
    const menuContainer = document.getElementById("menu_container");

    // Toggle menu visibility
    menuToggle.addEventListener("click", (e) => {
        e.stopPropagation(); // Prevent event from bubbling to the document
        menuContainer.classList.toggle("hidden");
    });

    // Close the menu when clicking outside
    document.addEventListener("click", (e) => {
        if (!menuContainer.classList.contains("hidden") && 
            !menuContainer.contains(e.target) && 
            !menuToggle.contains(e.target)) {
            menuContainer.classList.add("hidden");
        }
    });
});

// document.addEventListener('DOMContentLoaded', function() {
//     const modal = document.getElementById('detailsModal');
//     const closeBtn = document.getElementById('closeModal');
    
//     document.querySelectorAll('#view_details_btn').forEach(button => {
//         button.addEventListener('click', function() {
//             modal.classList.remove('hidden');
//             modal.classList.add('flex');
//         });
//     });
    
//     closeBtn.addEventListener('click', function() {
//         modal.classList.add('hidden');
//         modal.classList.remove('flex');
//     });
    
//     // Close on outside click
//     modal.addEventListener('click', function(e) {
//         if (e.target === modal) {
//             modal.classList.add('hidden');
//             modal.classList.remove('flex');
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailsModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalPrice = document.getElementById('modalPrice');
    const modalDescription = document.getElementById('modalDescription');
    const closeModal = document.getElementById('closeModal');

    document.querySelectorAll('#view_details_btn').forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            const type = this.dataset.type;
            
            try {
                const response = await fetch(`get_details.php?type=${type}&id=${id}`);
                const data = await response.json();
                
                modalImage.src = `data:image/jpeg;base64,${data.picture}`;
                modalTitle.textContent = data.name;
                modalPrice.textContent = `₱${parseFloat(data.price).toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
                modalDescription.textContent = data.description || '';
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const reviewModal = document.getElementById('reviewModal');
    const submitReviewBtn = document.getElementById('submitReview');
    const closeReviewModalBtn = document.getElementById('closeReviewModal');
    const reviewText = document.getElementById('reviewText');

    document.querySelectorAll('a[href="submit_review.php"]').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            reviewModal.classList.remove('hidden');
            reviewModal.classList.add('flex');
        });
    });

    closeReviewModalBtn.addEventListener('click', function() {
        reviewModal.classList.add('hidden');
        reviewModal.classList.remove('flex');
    });

    reviewModal.addEventListener('click', function(e) {
        if (e.target === reviewModal) {
            reviewModal.classList.add('hidden');
            reviewModal.classList.remove('flex');
        }
    });

    submitReviewBtn.addEventListener('click', function() {
        const reviewContent = reviewText.value;
        if (reviewContent.trim() === '') {
            alert('Please write a review before submitting.');
            return;
        }

        // Submit review via AJAX or form submission
        // Example:
        // fetch('submit_review.php', {
        //     method: 'POST',
        //     body: JSON.stringify({ review: reviewContent }),
        //     headers: { 'Content-Type': 'application/json' }
        // }).then(response => response.json()).then(data => {
        //     if (data.success) {
        //         alert('Review submitted successfully!');
        //         reviewModal.classList.add('hidden');
        //         reviewModal.classList.remove('flex');
        //     } else {
        //         alert('Error submitting review.');
        //     }
        // });

        // For now, just close the modal
        reviewModal.classList.add('hidden');
        reviewModal.classList.remove('flex');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    let currentRating = 1;

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            currentRating = value;
            updateStars();
        });
    });

    function updateStars() {
        stars.forEach(star => {
            const value = parseInt(star.dataset.value);
            star.classList.toggle('selected', value <= currentRating);
        });
    }
});