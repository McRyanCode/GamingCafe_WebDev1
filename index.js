document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('gamesCarousel');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    if (carousel && scrollLeftBtn && scrollRightBtn) {
        let isScrolling = false;

        // Function to move to the next card (Right Arrow)
        const slideNext = () => {
            if (isScrolling) return;
            isScrolling = true;

            const cardWidth = carousel.querySelector('.game-card').offsetWidth + 20; // Card width + gap

            carousel.scrollBy({
                left: cardWidth,
                behavior: 'smooth'
            });

            // After the smooth scroll completes, move the first element to the end
            setTimeout(() => {
                const firstCard = carousel.querySelector('.game-card');
                carousel.appendChild(firstCard); // Moves first element to the end
                carousel.scrollLeft -= cardWidth; // Adjusts scroll position silently to prevent jumping
                isScrolling = false;
            }, 350); // Matches smooth scroll duration
        };

        // Function to move to the previous card (Left Arrow)
        const slidePrev = () => {
            if (isScrolling) return;
            isScrolling = true;

            const cards = carousel.querySelectorAll('.game-card');
            const lastCard = cards[cards.length - 1];
            const cardWidth = lastCard.offsetWidth + 20;

            // Prepend the last card to the start instantly behind the scenes
            carousel.insertBefore(lastCard, carousel.firstChild);
            carousel.scrollLeft += cardWidth; // Compensate scroll position instantly

            // Smooth scroll back to the new left position
            carousel.scrollBy({
                left: -cardWidth,
                behavior: 'smooth'
            });

            setTimeout(() => {
                isScrolling = false;
            }, 350);
        };

        // Event Listeners
        scrollRightBtn.addEventListener('click', slideNext);
        scrollLeftBtn.addEventListener('click', slidePrev);
    }
});