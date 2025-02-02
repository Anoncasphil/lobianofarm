window.onload = function() {
    // Select elements for animation
    const reserveText = document.getElementById('reserve-text');
    const reserveImage = document.getElementById('reserve-image');
    const heroText = document.getElementById('hero-text');
    const checkInForm = document.getElementById('check-in-form');
    const rateCards = document.querySelectorAll('.rate-card');
    const addonCards = document.querySelectorAll('.addons-card');
    const rateHeadings = document.querySelectorAll('#rates-section .heading-addon, #rates-section .text-addon');
    const addonHeadings = document.querySelectorAll('#addons-section .header-rate, #addons-section .text-rate');
    const aboutSection = document.getElementById('about'); // About Us Section
    const albumSection = document.getElementById('album'); // Album Section

    // -------------------------
    // Hero Section Animation
    // -------------------------
    heroText.style.opacity = 0;
    heroText.style.transform = 'scale(0.95)';
    checkInForm.style.opacity = 0;
    checkInForm.style.transform = 'scale(0.95)';

    // -------------------------
    // Reserve Section Animation
    // -------------------------
    reserveText.style.opacity = 0;
    reserveText.style.transform = 'translateX(-50px)';
    reserveImage.style.opacity = 0;
    reserveImage.style.transform = 'translateX(50px)';

    // Transition style
    const transitionStyle = 'opacity 0.7s ease, transform 0.7s ease';

    // Apply the transition style to Hero and Reserve elements
    heroText.style.transition = transitionStyle;
    checkInForm.style.transition = transitionStyle;
    reserveText.style.transition = transitionStyle;
    reserveImage.style.transition = transitionStyle;

    // Animate Hero Section with delays
    setTimeout(() => {
        heroText.style.opacity = 1;
        heroText.style.transform = 'scale(1)';
    }, 100);

    setTimeout(() => {
        checkInForm.style.opacity = 1;
        checkInForm.style.transform = 'scale(1)';
    }, 300);

    // Animate Reserve Section
    setTimeout(() => {
        reserveText.style.opacity = 1;
        reserveText.style.transform = 'translateX(0)';
    }, 700);

    setTimeout(() => {
        reserveImage.style.opacity = 1;
        reserveImage.style.transform = 'translateX(0)';
    }, 1000);

    // -------------------------
    // Intersection Observer for Rates & Add-ons Cards
    // -------------------------
    const animateOnScroll = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                // Determine index for staggered effect (for either rateCards or addonCards)
                const index = Array.from(rateCards).indexOf(target) !== -1 
                    ? Array.from(rateCards).indexOf(target) 
                    : Array.from(addonCards).indexOf(target);
                
                setTimeout(() => {
                    target.style.opacity = 1;
                    target.style.transform = 'scale(1)';
                    target.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                }, 150 + index * 150);
                
                observer.unobserve(target);
            }
        });
    };

    const observerOptions = {
        threshold: 0.2
    };
    const observer = new IntersectionObserver(animateOnScroll, observerOptions);

    // Animate Rates & Add-ons Cards
    rateCards.forEach(card => {
        card.style.opacity = 0;
        card.style.transform = 'scale(0.9)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });

    addonCards.forEach(card => {
        card.style.opacity = 0;
        card.style.transform = 'scale(0.9)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });

    // -------------------------
    // Rates & Add-ons Text Animation
    // -------------------------
    rateHeadings.forEach((text, index) => {
        text.style.opacity = 0;
        text.style.transform = 'translateY(50px)';
        text.style.transition = 'opacity 1.2s ease, transform 1.2s ease';
        setTimeout(() => {
            text.style.opacity = 1;
            text.style.transform = 'translateY(0)';
        }, 300 + index * 150);
    });

    addonHeadings.forEach((text, index) => {
        text.style.opacity = 0;
        text.style.transform = 'translateY(50px)';
        text.style.transition = 'opacity 1.2s ease, transform 1.2s ease';
        setTimeout(() => {
            text.style.opacity = 1;
            text.style.transform = 'translateY(0)';
        }, 300 + index * 150);
    });

    // -------------------------
    // About Us Section Animation on Scroll
    // -------------------------
    aboutSection.style.opacity = 0;
    aboutSection.style.transform = 'translateY(50px)';
    aboutSection.style.transition = 'opacity 1s ease, transform 1s ease';

    const aboutObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    aboutObserver.observe(aboutSection);

    // -------------------------
    // Album Section Animation on Scroll
    // -------------------------
    // Set initial styles for Album section
    albumSection.style.opacity = 0;
    albumSection.style.transform = 'translateY(50px)';
    albumSection.style.transition = 'opacity 1s ease, transform 1s ease';

    const albumObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    albumObserver.observe(albumSection);
};
