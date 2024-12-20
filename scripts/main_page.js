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