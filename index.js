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
                if (firstCard) {
                    carousel.appendChild(firstCard);
                    carousel.scrollLeft -= cardWidth;
                }
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

    // ================= 2. MODAL & DROPDOWN CONTROLS =================
    const signInModal = document.getElementById("signInModal");
    const signUpModal = document.getElementById("signUpModal");
    const openSignInBtn = document.getElementById("openSignInBtn");
    const openSignUpBtn = document.getElementById("openSignUpBtn");
    const closeSignIn = document.getElementById("closeSignIn");
    const closeSignUp = document.getElementById("closeSignUp");
    const switchToSignUp = document.getElementById("switchToSignUp");
    const switchToSignIn = document.getElementById("switchToSignIn");

    const profileMenuBtn = document.getElementById("profileMenuBtn");
    const profileDropdown = document.getElementById("profileDropdown");
    const menuProfileBtn = document.getElementById("menuProfileBtn");
    const menuBookedPcBtn = document.getElementById("menuBookedPcBtn");
    const menuPricingBtn = document.getElementById("menuPricingBtn");

    const bookingButtons = document.querySelectorAll(".hero-btn.secondary-btn, .hero-btn.primary-btn, .reserve-btn, .btn-book-now");

    // Open Modals
    if (openSignInBtn && signInModal) openSignInBtn.addEventListener("click", () => signInModal.style.display = "flex");
    if (openSignUpBtn && signUpModal) openSignUpBtn.addEventListener("click", () => signUpModal.style.display = "flex");

    // Close Modals on 'X'
    if (closeSignIn && signInModal) closeSignIn.addEventListener("click", () => signInModal.style.display = "none");
    if (closeSignUp && signUpModal) closeSignUp.addEventListener("click", () => signUpModal.style.display = "none");

    // Switch between Sign In / Sign Up
    if (switchToSignUp) {
        switchToSignUp.addEventListener("click", (e) => {
            e.preventDefault();
            if (signInModal) signInModal.style.display = "none";
            if (signUpModal) signUpModal.style.display = "flex";
        });
    }
    if (switchToSignIn) {
        switchToSignIn.addEventListener("click", (e) => {
            e.preventDefault();
            if (signUpModal) signUpModal.style.display = "none";
            if (signInModal) signInModal.style.display = "flex";
        });
    }

    // Close Modals on Overlay Click
    window.addEventListener("click", (e) => {
        if (e.target === signInModal) signInModal.style.display = "none";
        if (e.target === signUpModal) signUpModal.style.display = "none";
    });

    // Profile Dropdown Toggle
    if (profileMenuBtn && profileDropdown) {
        profileMenuBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle("show");
        });

        window.addEventListener("click", () => {
            profileDropdown.classList.remove("show");
        });
    }

    // Menu Item Handlers
    if (menuProfileBtn) {
        menuProfileBtn.addEventListener("click", () => {
            if (profileDropdown) profileDropdown.classList.remove("show");
            showToast("ACCOUNT SETTINGS", "Opening account settings...", "success");
        });
    }

    if (menuBookedPcBtn) {
        menuBookedPcBtn.addEventListener("click", () => {
            if (profileDropdown) profileDropdown.classList.remove("show");
            const bookedPcText = document.getElementById("bookedPcDisplay")?.textContent || "No PC Booked";
            showToast("RESERVATION STATUS", bookedPcText, "success");
        });
    }

    if (menuPricingBtn) {
        menuPricingBtn.addEventListener("click", () => {
            if (profileDropdown) profileDropdown.classList.remove("show");
            const currentTier = document.getElementById("pricingTierDisplay")?.textContent || "Standard Plan";
            showToast("CURRENT PLAN", `Active Tier: ${currentTier}`, "success");
        });
    }

    // Booking Buttons Handler
    bookingButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();

            fetch("auth/check_auth.php")
                .then(res => res.json())
                .then(data => {
                    if (data.logged_in) {
                        window.location.href = "booking/booking.php";
                    } else {
                        if (signInModal) signInModal.style.display = "flex";
                        showToast("AUTHENTICATION REQUIRED", "Please sign in to reserve a PC or tier.", "error");
                    }
                })
                .catch(err => console.error("Error checking auth status:", err));
        });
    });

    // ================= 3. AUTH FORM HANDLERS =================
    
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
                })
                .catch(err => console.error("Logout failed:", err));
        });
    }

    // Initialize Auth and Profile Data
    checkLoginStatus();
});

// ================= GLOBAL HELPER FUNCTIONS =================

function checkLoginStatus() {
    fetch("auth/check_auth.php")
        .then(res => res.json())
        .then(data => {
            const loggedInNav = document.getElementById("loggedInNav");
            const loggedOutNav = document.getElementById("loggedOutNav");
            const userHandle = document.getElementById("userHandleDisplay");
            const profileDisplay = document.getElementById("profileUsername");

            if (data.logged_in) {
                if (loggedOutNav) loggedOutNav.style.display = "none";
                if (loggedInNav) loggedInNav.style.display = "inline-block";
                if (userHandle) userHandle.textContent = "@" + data.username;
                if (profileDisplay) profileDisplay.textContent = data.username;

                fetchBookingDetails();
            } else {
                if (loggedOutNav) loggedOutNav.style.display = "flex";
                if (loggedInNav) loggedInNav.style.display = "none";
                if (profileDisplay) profileDisplay.textContent = "Guest / Sign In";

                // Reset booking displays on logout
                const bookedPc = document.getElementById("bookedPcDisplay");
                const pricingTier = document.getElementById("pricingTierDisplay");
                if (bookedPc) bookedPc.textContent = "No PC Booked";
                if (pricingTier) pricingTier.textContent = "Standard Plan";
            }
        })
        .catch(err => console.error("Auth status check failed:", err));
}

function fetchBookingDetails() {
    fetch("booking/my_bookings.php?format=json")
        .then(res => {
            if (!res.ok) throw new Error("HTTP error " + res.status);
            return res.json();
        })
        .then(data => {
            const bookedPc = document.getElementById("bookedPcDisplay");
            const pricingTier = document.getElementById("pricingTierDisplay");

            if (data.status === "success" && data.latest_booking) {
                if (bookedPc) bookedPc.textContent = `${data.latest_booking.device_name} (${data.latest_booking.booking_type})`;
                if (pricingTier) pricingTier.textContent = `${data.latest_booking.pricing_tier} Plan`;
            } else {
                if (bookedPc) bookedPc.textContent = "No PC Booked";
                if (pricingTier) pricingTier.textContent = "Standard Plan";
            }
        })
        .catch(err => console.error("Booking data fetch failed:", err));
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
        if (toastIcon) toastIcon.textContent = "✕";
    } else {
        toast.className = "toast-notification success show";
        if (toastIcon) toastIcon.textContent = "✓";
    }

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3500);
}

document.addEventListener("DOMContentLoaded", fetchUserTier);

function fetchUserTier() {
    fetch("booking/get_user_tier.php")
        .then(res => res.json())
        .then(data => {
            const display = document.getElementById("pricingTierDisplay");
            const badge = document.getElementById("modalTierBadge");
            const desc = document.getElementById("modalTierDesc");

            if (display) display.textContent = data.tier + " Plan";
            if (badge) badge.textContent = data.tier;
            if (desc) desc.textContent = data.description;
        })
        .catch(() => {
            const display = document.getElementById("pricingTierDisplay");
            if (display) display.textContent = "Standard Plan";
        });
}

function openTierModal() {
    document.getElementById("tierModal").style.display = "flex";
}

function closeTierModal() {
    document.getElementById("tierModal").style.display = "none";
}