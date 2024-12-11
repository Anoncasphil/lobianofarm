function toggleAddEventModal() {
    const modal = document.getElementById('add-event-modal');
    modal.classList.toggle('hidden');
}

function hideAddEventModal() {
    const modal = document.getElementById('add-event-modal');
    modal.classList.add('hidden');
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            // Set the source of the image
            imagePreview.src = e.target.result;

            imagePreview.style.width = '100%';
            imagePreview.style.height = '100px'; 
            imagePreview.className = 'object-cover rounded-lg img-zoom-out';

            imagePreviewContainer.style.display = 'block'; 
        };
        reader.readAsDataURL(file);
    }
}
