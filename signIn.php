<!-- AUTHENTICATION MODAL (GitHub Style) -->
<div class="auth-modal-overlay" id="authModal">
    <div class="auth-card">
        <!-- Close Button -->
        <button class="modal-close-btn" id="closeAuthBtn" aria-label="Close modal">&times;</button>
        
        <!-- Cafe Logo Header -->
        <div class="auth-header">
            <img src="image_GamingCafe/Logo.png" alt="Gamora's Gaming Cafe" class="auth-logo">
            <h2 id="authTitle">Access Your Profile</h2>
        </div>

        <!-- Auth Form -->
        <form class="auth-form" id="authForm" action="auth-process.php" method="POST">
            <div class="form-group">
                <label for="userEmail">Username or email address</label>
                <input type="text" id="userEmail" name="username" required autocomplete="off">
            </div>

            <div class="form-group">
                <div class="label-row">
                    <label for="userPassword">Password</label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
                <input type="password" id="userPassword" name="password" required>
            </div>

            <button type="submit" class="auth-submit-btn" id="authSubmitBtn">Sign in</button>
        </form>

        <!-- Toggle Sign In / Sign Up Footer -->
        <div class="auth-footer">
            <p id="toggleAuthText">NOT A MEMBER YET? <a href="#" id="toggleAuthLink">Create an account</a>.</p>
        </div>
    </div>
</div>