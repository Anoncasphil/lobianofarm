window.onload = function() {
    const heroText = document.getElementById('hero-text');
    const checkInForm = document.getElementById('check-in-form');
    const rateCards = document.querySelectorAll('.rate-card');
    const addonCards = document.querySelectorAll('.addons-card');
    const rateHeadings = document.querySelectorAll('#rates-section .heading-addon, #rates-section .text-addon');
    const addonHeadings = document.querySelectorAll('#addons-section .header-rate, #addons-section .text-rate');

    // -------------------------
    // Reusable Function for Initial Style Setup
    // -------------------------
    function setInitialStyle(elements, opacity = 0, transform = 'translateY(50px)', transition = 'opacity 1s ease, transform 1s ease') {
        elements.forEach(el => {
            el.style.opacity = opacity;
            el.style.transform = transform;
            el.style.transition = transition;
        });
    }

    // -------------------------
    // Reusable Function for Intersection Observer
    // -------------------------
    function createObserver(elements, delay = 150) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    setTimeout(() => {
                        target.style.opacity = 1;
                        target.style.transform = 'translateY(0)';
                    }, delay);
                    observer.unobserve(target);
                }
            });
        }, { threshold: 0.2 });

        elements.forEach(el => observer.observe(el));
    }

    // -------------------------
    // Hero & Check-in Form Animation
    // -------------------------
    [heroText, checkInForm].forEach((el, index) => {
        el.style.opacity = 0;
        el.style.transform = 'scale(0.95)';
        el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
        setTimeout(() => {
            el.style.opacity = 1;
            el.style.transform = 'scale(1)';
        }, 100 + index * 200);
    });

    // -------------------------
    // Rates & Add-ons Cards Animation
    // -------------------------
    setInitialStyle([...rateCards, ...addonCards], 0, 'scale(0.9)', 'opacity 0.5s ease, transform 0.5s ease');
    
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const card = entry.target;
                const index = [...rateCards, ...addonCards].indexOf(card);
                setTimeout(() => {
                    card.style.opacity = 1;
                    card.style.transform = 'scale(1)';
                }, 150 + index * 150);
                cardObserver.unobserve(card);
            }
        });
    }, { threshold: 0.2 });

    [...rateCards, ...addonCards].forEach(card => cardObserver.observe(card));

    // -------------------------
    // Rates & Add-ons Headings Animation
    // -------------------------
    [rateHeadings, addonHeadings].forEach(headings => {
        headings.forEach((text, index) => {
            text.style.opacity = 0;
            text.style.transform = 'translateY(50px)';
            text.style.transition = 'opacity 1.2s ease, transform 1.2s ease';
            setTimeout(() => {
                text.style.opacity = 1;
                text.style.transform = 'translateY(0)';
            }, 300 + index * 150);
        });
    });

    // -------------------------
    // Sections to Observe (About, Location, Album, Video, Review, Contact)
    // -------------------------
    const sections = ['about', 'location', 'album', 'video-tour', 'review', 'contact'].map(id => document.getElementById(id));
    setInitialStyle(sections);
    createObserver(sections);
};
