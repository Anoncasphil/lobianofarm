document.getElementById('submitReview').addEventListener('click', async function() {
    const rating = document.querySelector('.star.selected').dataset.value;
    const title = document.getElementById('reviewTitle').value;
    const reviewText = document.getElementById('reviewText').value;

    try {
        const response = await fetch('submit_review.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `rating=${rating}&title=${encodeURIComponent(title)}&review_text=${encodeURIComponent(reviewText)}`
        });

        const data = await response.json();
        if (data.success) {
            document.getElementById('reviewModal').classList.add('hidden');
            // Optionally refresh reviews display
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
});