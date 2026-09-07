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

document.addEventListener("DOMContentLoaded", () => {
    // 1. Check if user is logged in on page load
    checkLoginStatus();

    // 2. Handle Sign Up Form Submit
    const signUpForm = document.getElementById("signUpForm");
    if (signUpForm) {
        signUpForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(signUpForm);
            fetch("auth/register.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") signUpForm.reset();
                });
        });
    }

    // 3. Handle Login Form Submit
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            fetch("auth/login.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        checkLoginStatus();
                        loginForm.reset();
                    }
                });
        });
    }

    // 4. Handle Logout Button Click
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            fetch("auth/logout.php")
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        checkLoginStatus();
                    }
                });
        });
    }
});

function checkLoginStatus() {
    fetch("auth/check_auth.php")
        .then(res => res.json())
        .then(data => {
            const profileDisplay = document.getElementById("profileUsername");
            if (profileDisplay) {
                if (data.logged_in) {
                    profileDisplay.textContent = data.username;
                } else {
                    profileDisplay.textContent = "Guest / Sign In";
                }
            }
        });
}

document.addEventListener("DOMContentLoaded", () => {
    // Check session status on load
    checkLoginStatus();

    // Open Sign In Modal
    const openSignInBtn = document.getElementById("openSignInBtn");
    const signInModal = document.getElementById("signInModal"); // Ensure this matches your modal ID
    if (openSignInBtn && signInModal) {
        openSignInBtn.addEventListener("click", () => {
            signInModal.style.display = "flex";
        });
    }

    // Open Sign Up Modal
    const openSignUpBtn = document.getElementById("openSignUpBtn");
    const signUpModal = document.getElementById("signUpModal"); // Ensure this matches your modal ID
    if (openSignUpBtn && signUpModal) {
        openSignUpBtn.addEventListener("click", () => {
            signUpModal.style.display = "flex";
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    checkLoginStatus();

    // Modal Elements
    const signInModal = document.getElementById("signInModal");
    const signUpModal = document.getElementById("signUpModal");

    // Trigger Buttons
    const openSignInBtn = document.getElementById("openSignInBtn");
    const openSignUpBtn = document.getElementById("openSignUpBtn");

    // Close Buttons
    const closeSignIn = document.getElementById("closeSignIn");
    const closeSignUp = document.getElementById("closeSignUp");

    // Open Sign In
    if (openSignInBtn && signInModal) {
        openSignInBtn.addEventListener("click", () => {
            signInModal.style.display = "flex";
        });
    }

    // Open Sign Up
    if (openSignUpBtn && signUpModal) {
        openSignUpBtn.addEventListener("click", () => {
            signUpModal.style.display = "flex";
        });
    }

    // Close Modals on 'X' click
    if (closeSignIn) closeSignIn.addEventListener("click", () => signInModal.style.display = "none");
    if (closeSignUp) closeSignUp.addEventListener("click", () => signUpModal.style.display = "none");

    // Close Modals when clicking dark background
    window.addEventListener("click", (e) => {
        if (e.target === signInModal) signInModal.style.display = "none";
        if (e.target === signUpModal) signUpModal.style.display = "none";
    });


// Switch from Sign In modal to Sign Up modal
const switchToSignUp = document.getElementById("switchToSignUp");
if (switchToSignUp) {
    switchToSignUp.addEventListener("click", (e) => {
        e.preventDefault();
        signInModal.style.display = "none";
        signUpModal.style.display = "flex";
    });
}

// Switch from Sign Up modal to Sign In modal
const switchToSignIn = document.getElementById("switchToSignIn");
if (switchToSignIn) {
    switchToSignIn.addEventListener("click", (e) => {
        e.preventDefault();
        signUpModal.style.display = "none";
        signInModal.style.display = "flex";
    });
}});

