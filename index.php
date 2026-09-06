<?php
$title = "Gamora's Gaming Cafe";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="signIn.css">
    <link rel="stylesheet" href="index.css">
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
    <a href="#home">HOME</a>
    <a href="#pcs">PCs</a>
    <a href="#rates">RATES</a>
    <a href="#events">EVENTS</a>
    <a href="#contact">CONTACT</a>
    <a href="#about">ABOUT US</a>
    
    <!-- Auth Controls Wrapper -->
    <div class="nav-auth-wrapper">
        <!-- Action Buttons Group (Hidden when logged in) -->
        <div class="auth-buttons-group" id="authButtonsGroup">
            <button class="nav-btn-secondary" id="openSignInBtn" type="button">SIGN IN</button>
            <button class="nav-btn-primary" id="openSignUpBtn" type="button">SIGN UP</button>
        </div>

        <!-- Circular Profile Icon & Dropdown Container -->
        <div class="profile-nav-wrapper">
            <button class="profile-icon-btn" id="navProfileBtn" type="button" aria-label="Account Menu">
                <svg class="profile-svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span class="active-status-dot" id="statusDot"></span>
            </button>

            <!-- Dropdown Card -->
            <div class="profile-card-dropdown" id="profileDropdown">
                <div class="profile-card-header">
                    <div class="user-info">
                        <span class="gamer-tag">Gamer_77</span>
                        <span class="station-badge">Station #14 • VIP</span>
                    </div>
                </div>

                <div class="session-info-box">
                    <div class="session-row">
                        <span>Remaining Time:</span>
                        <strong class="time-left">2h 45m</strong>
                    </div>
                    <div class="session-row">
                        <span>Pricing Plan:</span>
                        <strong>Night Pass</strong>
                    </div>
                    <div class="session-row">
                        <span>Wallet Credit:</span>
                        <strong class="cyan-text">$15.00</strong>
                    </div>
                </div>

                <button class="logout-btn" id="logoutBtn" type="button">Log Out</button>
            </div>
        </div>
    </div>
</nav>
    </div>
</header>

    <!-- Main Hero Body -->
    <div class="hero-body">
        <div class="hero-container">
            <div class="hero-content">
                <h1>WHERE<br><span class="cyan-text">CHAMPIONS</span> ARE BORN.</h1>
                
                <div class="hero-buttons">
                    <a href="#book" class="hero-btn primary-btn">BOOK NOW</a>
                    <a href="#rates" class="hero-btn secondary-btn">VIEW RATES</a>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Why Choose Us Section -->
<section class="why-choose-us">

    <div class="why-choose-container">

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
<section class="pricing">

    <h2><span style="color:#00d2ff">PRICING</span> REVIEW</h2>

    <div class="pricing-container">
        
        <!-- Card 1: Basic -->
        <div class="price-card">
            <h3 class="tier-title">BASIC</h3>
            <p class="duration">1 HOUR</p>
            <div class="price"><span class="currency">₱</span>40</div>
            <a href="#contact" class="reserve-btn">RESERVE NOW</a>
        </div>

        <!-- Card 2: Standard -->
        <div class="price-card">
            <h3 class="tier-title">STANDARD</h3>
            <p class="duration">3 HOURS</p>
            <div class="price"><span class="currency">₱</span>100</div>
            <a href="#contact" class="reserve-btn">RESERVE NOW</a>
        </div>

        <!-- Card 3: Premium -->
        <div class="price-card">
            <h3 class="tier-title">PREMIUM</h3>
            <p class="duration">5 HOURS</p>
            <div class="price"><span class="currency">₱</span>150</div>
            <a href="#contact" class="reserve-btn">RESERVE NOW</a>
        </div>

    </div>

    <!-- Pop-out Headset Asset -->
    <img src="headset.png" alt="Gaming Headset" class="headset-popout">

</section>


   <!-- Events and Tournaments -->
<!-- Events and Tournaments Section -->
<section class="events-section">

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

            <a href="#events" class="view-events-btn">VIEW EVENTS</a>
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
                <span class="highlight-cyan">Gaming Cafe</span> is a modern gaming space where players can enjoy high-performance gaming, connect with friends, and become part of a welcoming gaming community. More than just a place to play, GG's Gaming Cafe is designed to provide a comfortable, exciting, and inclusive environment for gamers of all skill levels.
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
                        <li><a href="#home">HOME</a></li>
                        <li><a href="#pcs">PCs</a></li>
                        <li><a href="#rates">RATES</a></li>
                        <li><a href="#events">EVENTS</a></li>
                        <li><a href="#contact">CONTACT</a></li>
                        <li><a href="#about">ABOUT US</a></li>
                    </ul>
                </div>

                <!-- Column 2: Legal -->
                <div class="footer-col">
                    <h3>LEGAL</h3>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>

                <!-- Column 3: Follow Us -->
                <div class="footer-col">
                    <h3>FOLLOW US</h3>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">TikTok</a></li>
                        <li><a href="#">Discord</a></li>
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
                <a href="#"><img src="image_GamingCafe/discord-icon.png" alt="Discord"></a>
                <a href="#"><img src="image_GamingCafe/facebook-icon.png" alt="Facebook"></a>
                <a href="#"><img src="image_GamingCafe/x-icon.png" alt="X"></a>
                <a href="#"><img src="image_GamingCafe/tiktok-icon.png" alt="TikTok"></a>
            </div>
        </div>

    </div>

    <img src="image_GamingCafe/logo2.png" alt="" class="footer-bg-logo">
</footer>

</div>
<?php include 'signIn.php'; ?>
<script src="index.js"></script>
<script src="signIn.js"></script>

</body>
</html>