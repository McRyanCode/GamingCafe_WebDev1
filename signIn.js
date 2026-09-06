document.addEventListener('DOMContentLoaded', () => {
    const openSignInBtn = document.getElementById('openSignInBtn');
    const openSignUpBtn = document.getElementById('openSignUpBtn');
    const navProfileBtn = document.getElementById('navProfileBtn');
    const authButtonsGroup = document.getElementById('authButtonsGroup');
    const profileDropdown = document.getElementById('profileDropdown');
    const statusDot = document.getElementById('statusDot');
    
    const authModal = document.getElementById('authModal');
    const closeAuthBtn = document.getElementById('closeAuthBtn');
    const authTitle = document.getElementById('authTitle');
    const authSubmitBtn = document.getElementById('authSubmitBtn');
    const toggleAuthText = document.getElementById('toggleAuthText');
    const authForm = document.getElementById('authForm');
    const logoutBtn = document.getElementById('logoutBtn');

    // App Logged-In State Flag
    let isLoggedIn = false;
    let isSignUpState = false;

    // Helper to toggle Sign In / Sign Up modal titles
    function setAuthMode(signUp) {
        isSignUpState = signUp;
        if (isSignUpState) {
            authTitle.textContent = "Create Player Profile";
            authSubmitBtn.textContent = "Sign Up";
            toggleAuthText.innerHTML = 'Already registered? <a href="#" id="toggleAuthLink">Sign in</a>.';
        } else {
            authTitle.textContent = "Enter the Lobby";
            authSubmitBtn.textContent = "Sign In";
            toggleAuthText.innerHTML = 'New player? <a href="#" id="toggleAuthLink">Create an account</a>.';
        }
    }

    // Direct Button Click Handlers
    if (openSignInBtn) {
        openSignInBtn.addEventListener('click', () => {
            setAuthMode(false);
            authModal.classList.add('active');
        });
    }

    if (openSignUpBtn) {
        openSignUpBtn.addEventListener('click', () => {
            setAuthMode(true);
            authModal.classList.add('active');
        });
    }

    // Profile Icon Smart Click
    if (navProfileBtn) {
        navProfileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!isLoggedIn) {
                // Not logged in: Route directly to Sign In modal
                setAuthMode(false);
                authModal.classList.add('active');
            } else {
                // Logged in: Toggle dropdown card
                profileDropdown.classList.toggle('show');
            }
        });
    }

    // Modal Close Handler
    if (closeAuthBtn) {
        closeAuthBtn.addEventListener('click', () => authModal.classList.remove('active'));
    }

    // Modal Dynamic Switch (Sign In <-> Sign Up)
    document.addEventListener('click', (e) => {
        if (e.target && e.target.id === 'toggleAuthLink') {
            e.preventDefault();
            setAuthMode(!isSignUpState);
        }
    });

    // Simulate Login Action on Form Submit
    if (authForm) {
        authForm.addEventListener('submit', (e) => {
            e.preventDefault();
            isLoggedIn = true;
            
            // Hide auth buttons, show green status dot
            authButtonsGroup.style.display = 'none';
            if (statusDot) statusDot.style.display = 'block';
            
            authModal.classList.remove('active');
        });
    }

    // Logout Handler
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            isLoggedIn = false;
            
            // Hide dropdown & status dot, bring back auth buttons
            profileDropdown.classList.remove('show');
            if (statusDot) statusDot.style.display = 'none';
            authButtonsGroup.style.display = 'flex';
        });
    }

    // Close Dropdown when clicking outside
    window.addEventListener('click', () => {
        if (profileDropdown) profileDropdown.classList.remove('show');
    });
});