<?php session_start(); ?>

<?php
$title = "Gamora's Gaming Cafe";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="drpdwn/pricingtier.css">
</head>

<body>
<!-- Hero Container with Embedded Scalable Image -->
<div class="hero-wrapper">
    <!-- Fixed Navigation Bar -->
<header class="site-header">
    <div class="nav-container">
        <a href="#home" class="brand-logo">
            <img src="image_GamingCafe/Logo.png" alt="Gamora's Gaming Cafe">
        </a>

<nav class="nav-links">
                <a href="#hero">HOME</a>
                <a href="#why-choose">PCs</a>
                <a href="#pricing">RATES</a>
                <a href="#events">EVENTS</a>
                <a href="#contact">CONTACT</a>
                <a href="#about">ABOUT US</a>
                
                <!-- Auth Controls Wrapper -->
              <div class="nav-auth-container">
  <!-- Shown when Logged Out -->
  <div id="loggedOutNav" class="auth-btn-group">
    <button id="openSignInBtn" class="btn-nav">Sign In</button>
    <button id="openSignUpBtn" class="btn-nav-primary">Sign Up</button>
  </div>

  <!-- Shown when Logged In (GitHub Style Dropdown) -->
  <div id="loggedInNav" class="user-profile-wrapper" style="display: none;">
    <button id="profileMenuBtn" class="profile-avatar-btn">
      <img src="image_GamingCafe/profile.png" alt="Profile Avatar" class="user-avatar">
    </button>

    <div id="profileDropdown" class="github-dropdown-menu">
  <div class="dropdown-header">
    <span class="user-handle" id="userHandleDisplay">gamer</span>
    <span class="user-status-badge">ONLINE</span>
  </div>

  <div class="dropdown-divider"></div>

  <!-- Profile / Account Settings Trigger -->
  <!-- Profile Link (Opens Account Settings / User Info) -->
<a href="drpdwn/profile.php" id="menuProfileBtn" class="dropdown-item">
  
  <div class="item-text">
    <span class="item-title">Profile</span>
    <span class="item-sub">Manage account</span>
  </div>
</a>


  <!-- Booked PC / Station Reservation Link -->
  <a href="booking/my_bookings.php" id="menuBookedPcBtn" class="dropdown-item">
    
    <div class="item-text">
      <span class="item-title">Booked PC</span>
    </div>
  </a>


<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<a href="admin/customers.php" id="menuAdminBtn" class="dropdown-item">
  <span class="item-icon">🛠️</span>
  <div class="item-text">
    <span class="item-title" style="color: #00f5d4;">Admin Area</span>
    <span class="item-sub">Manage Customers</span>
  </div>
</a>
<?php endif; ?>

  <div class="dropdown-divider"></div>

  <!-- Sign Out Trigger -->
  <button type="button" id="logoutBtn" class="dropdown-item logout-item">
    <span class="item-title">Sign out</span>
  </button>
</div>
  </div>
</div>
            </nav>
        </div> <!-- ADDED: Closes nav-container -->
    </header> <!-- ADDED: Closes site-header -->

    <!-- Main Hero Body -->
    <div id="hero" class="hero-body">
        <div class="hero-container">
            <div class="hero-content">
                <h1>WHERE<br><span class="cyan-text">CHAMPIONS</span> ARE BORN.</h1>
                
                <div class="hero-buttons">
                    <a href="./booking/booking.php" class="custom-hero-btn custom-primary-btn">BOOK NOW</a>
                    <a href="#pricing" class="custom-hero-btn custom-secondary-btn">VIEW RATES</a>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Why Choose Us Section -->
<section class="why-choose-us">

    <div id="why-choose" class="why-choose-container">

        <!-- Left Column: Title & Feature Capsules -->
        <div class="why-choose-content">
            <h2><span style="color:#000000">WHY</span> <span style="color:#00d2ff">CHOOSE</span> <span style="color:#000000">US ?</span></h2>
            
            <ul class="feature-pills">
                <li><span class="bullet">•</span> RTX Gaming PCs</li>
                <li><span class="bullet">•</span> Gaming Consoles</li>
                <li><span class="bullet">•</span> Fast Fiber Internet</li>
                <li> &emsp; &nbsp; • Tournaments & Events</li>
            </ul>
        </div>

       <!-- Right Column: 4 Separate Device Images Grouped Together -->
<div class="why-choose-media">
    <div class="devices-cluster">
        <img src="image_GamingCafe/pc.png" class="device device-1">
        <img src="image_GamingCafe/Xbox.png" class="device device-2">
        <img src="image_GamingCafe/PS5.png" class="device device-3">
        <img src="image_GamingCafe/swtch.png" alt="Xbox Console" class="device device-4">
    </div>
</div>

</section>


    <!-- Featured Games -->
  <section class="featured-games" id="games">
    <h2><span style="color:#00d2ff">FEATURED</span> GAMES</h2>
    
    <div class="carousel-wrapper">
        <button class="scroll-btn left-btn" id="scrollLeft" aria-label="Scroll Left">&#10094;</button>
        
        <div class="games-carousel" id="gamesCarousel">
            <div class="game-card">
                <div class="game-img-wrapper">
                    <img src=image_GamingCafe/mrv.avif alt="MARVEL Tokon" class="game-img">
                </div>
                <div class="game-info">
                    <span class="game-tag">New releases</span>
                    <h3 class="game-title">MARVEL Tokon: Fighting Souls</h3>
                    <p class="game-desc">Check out this month's biggest new titles, including MARVEL Tokon.</p>
                </div>
            </div>

            <div class="game-card">
                <div class="game-img-wrapper">
                    <img src=image_GamingCafe/csgo.jpg alt="Counter-Strike 2" class="game-img">
                </div>
                <div class="game-info">
                    <span class="game-tag">Competitive FPS</span>
                    <h3 class="game-title">Counter-Strike 2</h3>
                    <p class="game-desc">The legendary tactical shooter experience with updated graphics and server sub-tick precision.</p>
                </div>
            </div>

            <div class="game-card">
                <div class="game-img-wrapper">
                    <img src=image_GamingCafe/mine2.avif alt="Minecraft" class="game-img">
                </div>
                <div class="game-info">
                    <span class="game-tag">Community Favorite</span>
                    <h3 class="game-title">Minecraft</h3>
                    <p class="game-desc">Explore infinite world creation, survival servers, and custom multiplayer mini-games.</p>
                </div>
            </div>

            <div class="game-card">
                <div class="game-img-wrapper">
                    <img src=image_GamingCafe/tek4.webp alt="Tekken 8" class="game-img">
                </div>
                <div class="game-info">
                    <span class="game-tag">Fighting Game</span>
                    <h3 class="game-title">Tekken 8</h3>
                    <p class="game-desc">Experience next-gen 3D fighting action with intense heat mechanics and fluid combat.</p>
                </div>
            </div>

            <div class="game-card">
                <div class="game-img-wrapper">
                    <img src=image_GamingCafe/cod1.jpg alt="Call of Duty" class="game-img">
                </div>
                <div class="game-info">
                    <span class="game-tag">Latest Updates</span>
                    <h3 class="game-title">Call of Duty: Black Ops 7</h3>
                    <p class="game-desc">Keep up to date with the month's biggest new events and season drops.</p>
                </div>
            </div>

            <div class="game-card">
                <div class="game-img-wrapper">
                    <img src=image_GamingCafe/val2.jpg alt="Valorant" class="game-img">
                </div>
                <div class="game-info">
                    <span class="game-tag">Trending Now</span>
                    <h3 class="game-title">Valorant</h3>
                    <p class="game-desc">Experience fast-paced, precise tactical shooting with your team.</p>
                </div>
            </div>
        </div>

        <button class="scroll-btn right-btn" id="scrollRight" aria-label="Scroll Right">&#10095;</button>
    </div>
</section>


    <!-- Price Review -->
   <!-- Pricing Review Section -->
<section id="pricing" class="pricing">

    <h2><span style="color:#00d2ff">PRICING</span> REVIEW</h2>

    <div class="pricing-container">
        
        <!-- Card 1: Basic -->
        <div class="price-card">
            <h3 class="tier-title">BASIC</h3>
            <p class="duration">1 HOUR</p>
            <div class="price"><span class="currency">₱</span>40</div>
            <a  href="./booking/booking.php" class="reserve-btn">RESERVE NOW</a>
        </div>

        <!-- Card 2: Standard -->
        <div class="price-card">
            <h3 class="tier-title">STANDARD</h3>
            <p class="duration">3 HOURS</p>
            <div class="price"><span class="currency">₱</span>100</div>
            <a  class="reserve-btn">RESERVE NOW</a>
        </div>

        <!-- Card 3: Premium -->
        <div class="price-card">
            <h3 class="tier-title">PREMIUM</h3>
            <p class="duration">5 HOURS</p>
            <div class="price"><span class="currency">₱</span>150</div>
            <a  class="reserve-btn">RESERVE NOW</a>
        </div>

    </div>

    <!-- Pop-out Headset Asset -->
    <img src="headset.png" alt="Gaming Headset" class="headset-popout">

</section>


   <!-- Events and Tournaments -->
<!-- Events and Tournaments Section -->
<section id="events" class="events-section">

    <!-- Pop-out Peripheral Assets -->
    <img src="keyboard.png" alt="Gaming Keyboard" class="peripheral-keyboard">
    <img src="headset-top.png" alt="Gaming Headset" class="peripheral-headset">

    <div class="events-container">
        
        <!-- Left Content Block -->
        <div class="events-info">
            <h2>PROVE YOUR <span style="color:#00d2ff">SKILLS</span></h2>
            
            <p>
                Join exciting tournaments, community events, and friendly competitions. 
                Compete, showcase your skills, and win amazing prizes while connecting with fellow gamers.
            </p>

            <a href="main/events.php" class="view-events-btn">VIEW EVENTS</a>
        </div>

        <!-- Right Media Card -->
        <div class="events-card">
            <img src=image_GamingCafe/celeb1.png alt="Tournament Trophy Celebration" class="events-banner">
        </div>

    </div>

</section>

<!-- Contact and Map -->
<!-- Contact & Map Section -->
<section class="contact-section" id="contact">

    <!-- Top-Left Keyboard Graphic Bleed -->
    <img src="keyboard-corner.png" alt="Keyboard Graphic" class="contact-keyboard">

    <div class="contact-container">

        <!-- Left Column: Rounded Map Card -->
        <div class="map-card">
            <img 
            src="image_GamingCafe/map.png" class="map" alt="Gaming Map">
        </div>

        <!-- Right Column: Info Details -->
        <div class="contact-info">
            <h2>Visit Us</h2>
            
            <p class="cafe-name">GG Gaming Cafe</p>
            <p class="address">
                123 Gamer Street, Barangay Central,<br>
                Guihulngan City, Negros Oriental
            </p>
            <p class="phone">(123) 456-7890</p>
            <p class="hours">Open Daily: 10:00 AM – 12:00 AM</p>
        </div>

    </div>

</section>

<!-- About Us Section -->
<!-- About Us Section -->
<section class="about-section" id="about">

    <div class="about-container">

        <!-- Left Column: Title with Accent Bar & Copy -->
        <div class="about-content">
            <div class="about-title-wrap">
                <span class="vertical-bar"></span>
                <h2>ABOUT US</h2>
            </div>

            <p class="about-description">
                <span class="highlight-cyan">Gamora's Gaming Cafe</span> is a modern gaming space where players can enjoy high-performance gaming, connect with friends, and become part of a welcoming gaming community. More than just a place to play, Gamora's Gaming Cafe is designed to provide a comfortable, exciting, and inclusive environment for gamers of all skill levels.
            </p>
        </div>

        <!-- Right Column: Card Frame & Character Pop-out -->
        <div class="about-media">
            <div class="character-card">
                <img src="image_GamingCafe/minechr.png" alt="Minecraft Character" class="character-img">
            </div>
        </div>

    </div>

</section>


<!-- Footer -->
<footer class="site-footer">
    <div class="footer-container">
        
        <!-- Top Divider Line -->
        <hr class="footer-divider">

        <!-- Main Middle Grid -->
        <div class="footer-main-grid">
            
            <!-- Logo Section -->
            <div class="footer-brand">
                <img src="image_Gamingcafe/logo.png" alt="Gamora's Gaming Cafe" class="footer-logo">
            </div>

            <!-- Links Grid -->
            <div class="footer-links-wrapper">
                
                <!-- Column 1: Explore -->
                <div class="footer-col">
                    <h3>EXPLORE</h3>
                    <ul>
                        <li><a href="#hero">HOME</a></li>
                        <li><a href="#why-choose">PCs</a></li>
                        <li><a href="#pricing">RATES</a></li>
                        <li><a href="#events">EVENTS</a></li>
                        <li><a href="#contact">CONTACT</a></li>
                        <li><a href="#about">ABOUT US</a></li>
                    </ul>
                </div>

                <!-- Column 2: Legal -->
                <div class="footer-col">
                    <h3>LEGAL</h3>
                    <ul>
                        <li><a href="Terms & Condition/privacy.php">Privacy Policy</a></li>
                        <li><a href="Terms & Condition/terms.php">Terms & Conditions</a></li>
                    </ul>
                </div>

                <!-- Column 3: Follow Us -->
                <div class="footer-col">
                    <h3>FOLLOW US</h3>
                    <ul>
                        <li><a href="https://www.youtube.com/">Facebook</a></li>
                        <li><a href="https://www.instagram.com/">Instagram</a></li>
                        <li><a href="https://www.tiktok.com/">TikTok</a></li>
                        <li><a href="https://discord.gg/">Discord</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Bottom Divider Line -->
        <hr class="footer-divider">

        <!-- Bottom Copyright & Social Icons Row -->
        <div class="footer-bottom">
            <p>© 2026 GGs Gaming Cafe. All Rights Reserved</p>
            
            <div class="social-icons">
                <a href="https://discord.gg/"><img src="image_GamingCafe/discord-icon.png" alt="Discord"></a>
                <a href=""><img src="image_GamingCafe/facebook-icon.png" alt="Facebook"></a>
                <a href="https://twitter.com/"><img src="image_GamingCafe/x-icon.png" alt="X"></a>
                <a href="https://www.tiktok.com/"><img src="image_GamingCafe/tiktok-icon.png" alt="TikTok"></a>
            </div>
        </div>

    </div>

    <img src="image_GamingCafe/logo2.png" alt="" class="footer-bg-logo">
</footer>



</div>

<!-- SIGN IN MODAL -->
<div id="signInModal" class="modal-overlay" style="display: none;">
  <div class="github-style-card">
    <span class="close-btn" id="closeSignIn">&times;</span>
    
    <div class="card-brand">
      <img src="image_GamingCafe/Logo.png" alt="Gamora's Gaming Cafe Logo" class="brand-icon">
      <h2>Welcome Back  <span class="cyan-text">Player</span></h2>
    </div>

    <form id="loginForm">
      <div class="form-group">
        <label for="loginInput">Username or email address</label>
        <input type="text" id="loginInput" name="login_input" required autocomplete="off">
      </div>

      <div class="form-group">
        <div class="label-row">
          <label for="loginPassword">Password</label>
          <a href="auth/forgot_password.php" class="forgot-link">Forgot password?</a>
        </div>
        <input type="password" id="loginPassword" name="password" required>
      </div>

      <button type="submit" class="btn-cyan-submit">Sign in</button>
    </form>
  </div>
</div>

<!-- SIGN IN MODAL -->
<div id="signInModal" class="modal-overlay" style="display: none;">
  <div class="github-style-card">
    <span class="close-btn" id="closeSignIn">&times;</span>
    
    <div class="card-brand">
      <img src="image_GamingCafe/Logo.png" alt="Gamora's Gaming Cafe Logo" class="brand-icon">
      <h2>Sign in to <span class="cyan-text">Gamora's</span></h2>
    </div>

    <form id="loginForm">
      <div class="form-group">
        <label for="loginInput">Username or email address</label>
        <input type="text" id="loginInput" name="login_input" required autocomplete="off">
      </div>

      <div class="form-group">
        <div class="label-row">
          <label for="loginPassword">Password</label>
          <a href="#" class="forgot-link">Forgot password?</a>
        </div>
        <input type="password" id="loginPassword" name="password" required>
      </div>

      <button type="submit" class="btn-cyan-submit">Sign in</button>
    </form>

    <div class="auth-switch-footer">
      <span>New to Gamora's? <a href="#" id="switchToSignUp" class="cyan-link">Create an account</a></span>
    </div>
  </div>
</div>

<!-- SIGN UP MODAL -->
<div id="signUpModal" class="modal-overlay" style="display: none;">
  <div class="github-style-card">
    <span class="close-btn" id="closeSignUp">&times;</span>
    
    <div class="card-brand">
      <img src="image_GamingCafe/Logo.png" alt="Gamora's Gaming Cafe Logo" class="brand-icon">
      <h2>Create your <span class="cyan-text">Account</span></h2>
    </div>

    <form id="signUpForm">
      <div class="form-group">
        <label for="regUsername">Username</label>
        <input type="text" id="regUsername" name="username" required autocomplete="off">
      </div>

      <div class="form-group">
        <label for="regEmail">Email address</label>
        <input type="email" id="regEmail" name="email" required autocomplete="off">
      </div>

      <div class="form-group">
        <label for="regPassword">Password</label>
        <input type="password" id="regPassword" name="password" required>
      </div>

      <div class="form-group">
        <label for="regConfirm">Confirm Password</label>
        <input type="password" id="regConfirm" name="confirm_password" required>
      </div>

      <button type="submit" class="btn-cyan-submit">Create Account</button>
    </form>

    <div class="auth-switch-footer">
      <span>Already have an account? <a href="#" id="switchToSignIn" class="cyan-link">Sign In</a></span>
    </div>
  </div>
</div>

<!-- Custom Gaming Toast Notification -->
<div id="toastNotification" class="toast-notification">
    <div class="toast-icon" id="toastIcon">✓</div>
    <div class="toast-content">
        <span class="toast-title" id="toastTitle">System Message</span>
        <span class="toast-message" id="toastMessage">Action completed successfully.</span>
    </div>
</div>

<<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('../booking/get_active_tier.php')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.tier) {
                const tierElement = document.getElementById('current-active-tier');
                if (tierElement) {
                    tierElement.textContent = data.tier;
                }
            }
        })
        .catch(err => console.error('Error fetching tier:', err));
});
</script>
<script src="index.js"></script>
<?php include_once 'drpdwn/pricingtier.php'; ?>

</body>
</html>