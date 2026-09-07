document.addEventListener('DOMContentLoaded', () => {
    
    // ================= 1. GAMES CAROUSEL =================
    const carousel = document.getElementById('gamesCarousel');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    if (carousel && scrollLeftBtn && scrollRightBtn) {
        let isScrolling = false;

        const slideNext = () => {
            if (isScrolling) return;
            isScrolling = true;

            const card = carousel.querySelector('.game-card');
            if (!card) return;
            
            const cardWidth = card.offsetWidth + 20;

            carousel.scrollBy({ left: cardWidth, behavior: 'smooth' });

            setTimeout(() => {
                const firstCard = carousel.querySelector('.game-card');
                carousel.appendChild(firstCard);
                carousel.scrollLeft -= cardWidth;
                isScrolling = false;
            }, 350);
        };

        const slidePrev = () => {
            if (isScrolling) return;
            isScrolling = true;

            const cards = carousel.querySelectorAll('.game-card');
            if (cards.length === 0) return;

            const lastCard = cards[cards.length - 1];
            const cardWidth = lastCard.offsetWidth + 20;

            carousel.insertBefore(lastCard, carousel.firstChild);
            carousel.scrollLeft += cardWidth;

            carousel.scrollBy({ left: -cardWidth, behavior: 'smooth' });

            setTimeout(() => {
                isScrolling = false;
            }, 350);
        };

        scrollRightBtn.addEventListener('click', slideNext);
        scrollLeftBtn.addEventListener('click', slidePrev);
    }


    // ================= 2. AUTH STATUS CHECK =================
    checkLoginStatus();


    // ================= 3. MODAL CONTROLS =================
    const signInModal = document.getElementById("signInModal");
    const signUpModal = document.getElementById("signUpModal");
    const openSignInBtn = document.getElementById("openSignInBtn");
    const openSignUpBtn = document.getElementById("openSignUpBtn");
    const closeSignIn = document.getElementById("closeSignIn");
    const closeSignUp = document.getElementById("closeSignUp");
    const switchToSignUp = document.getElementById("switchToSignUp");
    const switchToSignIn = document.getElementById("switchToSignIn");

    // Open Modals
    if (openSignInBtn && signInModal) {
        openSignInBtn.addEventListener("click", () => signInModal.style.display = "flex");
    }
    if (openSignUpBtn && signUpModal) {
        openSignUpBtn.addEventListener("click", () => signUpModal.style.display = "flex");
    }

    // Close Modals on 'X'
    if (closeSignIn) closeSignIn.addEventListener("click", () => signInModal.style.display = "none");
    if (closeSignUp) closeSignUp.addEventListener("click", () => signUpModal.style.display = "none");

    // Switch between Sign In / Sign Up
    if (switchToSignUp) {
        switchToSignUp.addEventListener("click", (e) => {
            e.preventDefault();
            signInModal.style.display = "none";
            signUpModal.style.display = "flex";
        });
    }
    if (switchToSignIn) {
        switchToSignIn.addEventListener("click", (e) => {
            e.preventDefault();
            signUpModal.style.display = "none";
            signInModal.style.display = "flex";
        });
    }

    // Close Modals on Overlay Click
    window.addEventListener("click", (e) => {
        if (e.target === signInModal) signInModal.style.display = "none";
        if (e.target === signUpModal) signUpModal.style.display = "none";
    });


    // ================= 4. AUTH FORM HANDLERS =================
    
    // Sign Up Submission
    const signUpForm = document.getElementById("signUpForm");
    if (signUpForm) {
        signUpForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(signUpForm);

            fetch("auth/register.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        showToast("ACCOUNT CREATED", data.message || "Registration successful!", "success");
                        signUpForm.reset();
                        if (signUpModal) signUpModal.style.display = "none";
                    } else {
                        showToast("REGISTRATION FAILED", data.message || "Could not register.", "error");
                    }
                })
                .catch(() => showToast("SYSTEM ERROR", "Could not connect to server.", "error"));
        });
    }

    // Login Submission
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);

            fetch("auth/login.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        showToast("ACCESS GRANTED", data.message || "Successfully logged in!", "success");
                        checkLoginStatus();
                        loginForm.reset();
                        if (signInModal) signInModal.style.display = "none";
                    } else {
                        showToast("ACCESS DENIED", data.message || "Invalid credentials.", "error");
                    }
                })
                .catch(() => showToast("SYSTEM ERROR", "Could not connect to server.", "error"));
        });
    }

    // Logout Handler
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            fetch("auth/logout.php")
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        showToast("LOGGED OUT", "You have been logged out.", "success");
                        checkLoginStatus();
                    }
                });
        });
    }
});


// ================= HELPER FUNCTIONS =================

function checkLoginStatus() {
    fetch("auth/check_auth.php")
        .then(res => res.json())
        .then(data => {
            const profileDisplay = document.getElementById("profileUsername");
            if (profileDisplay) {
                profileDisplay.textContent = data.logged_in ? data.username : "Guest / Sign In";
            }
        })
        .catch(err => console.error("Auth status check failed:", err));
}

function showToast(title, message, type = "success") {
    const toast = document.getElementById("toastNotification");
    const toastTitle = document.getElementById("toastTitle");
    const toastMessage = document.getElementById("toastMessage");
    const toastIcon = document.getElementById("toastIcon");

    if (!toast) return;

    toastTitle.textContent = title;
    toastMessage.textContent = message;

    if (type === "error") {
        toast.className = "toast-notification error show";
        toastIcon.textContent = "✕";
    } else {
        toast.className = "toast-notification success show";
        toastIcon.textContent = "✓";
    }

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3500);
}