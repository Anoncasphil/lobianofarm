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
