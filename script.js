document.addEventListener('DOMContentLoaded', function() {
    // Animation au défilement
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.section-offer, .section-passion, .offer-item, .passion-content, .passion-img');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.style.opacity = "1";
                element.style.transform = "translateY(0)";
            }
        });
    };

    // Initialiser les éléments hors écran
    const offScreenElements = document.querySelectorAll('.section-offer, .section-passion, .offer-item, .passion-content, .passion-img');
    offScreenElements.forEach(element => {
        element.style.opacity = "0";
        element.style.transform = "translateY(20px)";
        element.style.transition = "all 0.8s ease-out";
    });

    // Exécuter l'animation au chargement et au défilement
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);

    // Animation des boutons
    const buttons = document.querySelectorAll('.order-button, .view-menu-button');
    buttons.forEach(button => {
        button.addEventListener('mouseover', function() {
            this.style.transform = "translateY(-3px)";
            this.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.2)";
        });

        button.addEventListener('mouseout', function() {
            this.style.transform = "translateY(0)";
            this.style.boxShadow = "none";
        });
    });
});