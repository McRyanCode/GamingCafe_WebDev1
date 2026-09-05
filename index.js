console.log("Website loaded!");


document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('gamesCarousel');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    if (carousel && scrollLeftBtn && scrollRightBtn) {
        const scrollAmount = 340; // Card width + gap

        // Scroll Right with Endless Loop
        scrollRightBtn.addEventListener('click', () => {
            const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;
            
            // If near the right edge, loop back to the beginning
            if (carousel.scrollLeft >= maxScrollLeft - 10) {
                carousel.scrollTo({
                    left: 0,
                    behavior: 'smooth'
                });
            } else {
                carousel.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        });

        // Scroll Left with Endless Loop
        scrollLeftBtn.addEventListener('click', () => {
            const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;

            // If near the left edge, loop around to the end
            if (carousel.scrollLeft <= 10) {
                carousel.scrollTo({
                    left: maxScrollLeft,
                    behavior: 'smooth'
                });
            } else {
                carousel.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            }
        });
    }
});