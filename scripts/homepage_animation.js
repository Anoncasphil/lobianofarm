window.onload = function() {
    // Select elements for animation
    const reserveText = document.getElementById('reserve-text');
    const reserveImage = document.getElementById('reserve-image');
    const heroText = document.getElementById('hero-text');
    const checkInForm = document.getElementById('check-in-form'); // Make sure this id is correct
    const rateCards = document.querySelectorAll('.rate-card'); // Select all rate cards
    const addonCards = document.querySelectorAll('.addons-card'); // Select all add-on cards
    const rateHeadings = document.querySelectorAll('#rates-section .heading-addon, #rates-section .text-addon'); // Select text elements in Rates section
    const addonHeadings = document.querySelectorAll('#addons-section .header-rate, #addons-section .text-rate'); // Select text elements in Add-ons section

    // Hero Section Animation (no image animation, only text and form)
    heroText.style.opacity = 0;
    heroText.style.transform = 'scale(0.95)';
    checkInForm.style.opacity = 0;
    checkInForm.style.transform = 'scale(0.95)';

    // Reserve Section Animation
    reserveText.style.opacity = 0;
    reserveText.style.transform = 'translateX(-50px)';
    reserveImage.style.opacity = 0;
    reserveImage.style.transform = 'translateX(50px)';

    // Transition style
    const transitionStyle = 'opacity 0.7s ease, transform 0.7s ease';

    // Apply the transition style to all elements inside the Hero Section (excluding the image)
    heroText.style.transition = transitionStyle;
    checkInForm.style.transition = transitionStyle;
    
    // Apply the transition style to both elements in the reserve section
    reserveText.style.transition = transitionStyle;
    reserveImage.style.transition = transitionStyle;

    // Delay the animation to make it sequential for Hero Section
    setTimeout(() => {
        heroText.style.opacity = 1;
        heroText.style.transform = 'scale(1)';
    }, 100); // Delay to start the animation for text

    setTimeout(() => {
        checkInForm.style.opacity = 1;
        checkInForm.style.transform = 'scale(1)';
    }, 300); // Delay for form

    // Trigger the animations for the Reserve Section
    setTimeout(() => {
        reserveText.style.opacity = 1;
        reserveText.style.transform = 'translateX(0)';
    }, 700); // Start animation for text after Hero Section animations

    setTimeout(() => {
        reserveImage.style.opacity = 1;
        reserveImage.style.transform = 'translateX(0)';
    }, 1000); // Start animation for image after text animation

    // Create Intersection Observer callback function
    const animateOnScroll = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                // Apply staggered animation when element is in view
                const index = Array.from(rateCards).indexOf(target) !== -1 
                    ? Array.from(rateCards).indexOf(target) 
                    : Array.from(addonCards).indexOf(target);
                
                // Set animation with delay based on index
                setTimeout(() => {
                    target.style.opacity = 1;
                    target.style.transform = 'scale(1)';
                    target.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                }, 150 + index * 150); // Staggered effect based on index
                
                observer.unobserve(target); // Stop observing after animation
            }
        });
    };

    // Observer options
    const observerOptions = {
        threshold: 0.2 // Trigger when 20% of the element is visible
    };
    const observer = new IntersectionObserver(animateOnScroll, observerOptions);

// Rates Section Text Animation
rateHeadings.forEach((text, index) => {
    // Ensure only text elements are affected by the animation
    text.style.opacity = 0;
    text.style.transform = 'translateY(50px)'; // Increased the Y distance for more movement
    text.style.transition = 'opacity 1.2s ease, transform 1.2s ease'; // Increased transition duration
    setTimeout(() => {
        text.style.opacity = 1;
        text.style.transform = 'translateY(0)';
    }, 300 + index * 150); // Staggered effect for text
});

// Add-ons Section Text Animation
addonHeadings.forEach((text, index) => {
    // Ensure only text elements are affected by the animation
    text.style.opacity = 0;
    text.style.transform = 'translateY(50px)'; // Increased the Y distance for more movement
    text.style.transition = 'opacity 1.2s ease, transform 1.2s ease'; // Increased transition duration
    setTimeout(() => {
        text.style.opacity = 1;
        text.style.transform = 'translateY(0)';
    }, 300 + index * 150); // Staggered effect for text
});


    // Rates Section Animation
    rateCards.forEach((card) => {
        card.style.opacity = 0;
        card.style.transform = 'scale(0.9)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });

    // Add-ons Section Animation
    addonCards.forEach((card) => {
        card.style.opacity = 0;
        card.style.transform = 'scale(0.9)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
};
