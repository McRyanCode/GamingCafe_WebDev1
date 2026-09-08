<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Station & Time - Gaming Cafe</title>
    <link rel="stylesheet" href="booking.css">
</head>
<body>

    <header class="booking-header">
        <a href="../index.php" class="back-link">&larr; Back to Home</a>
        <h1>Select Your Station</h1>
    </header>

    <main class="booking-container">
        
        <!-- SELECTION VIEW -->
        <div id="selectionStep">
            
            <!-- PC SECTION -->
            <section class="device-section">
                <h2 class="section-title">Gaming PCs</h2>
                <div class="device-grid" id="pcGrid">
                    
                    <div class="device-card" data-device-id="PC-01" data-device-type="PC" data-device-name="PC 01">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/pc-placeholder.jpg" alt="PC 01" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH PC 01 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>PC 01</h3>
                            <p class="specs">RTX 4070 | i7-13700K | 240Hz</p>
                            <button type="button" class="select-btn">Select PC 01</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="PC-02" data-device-type="PC" data-device-name="PC 02">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/pc-placeholder.jpg" alt="PC 02" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH PC 02 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>PC 02</h3>
                            <p class="specs">RTX 4070 | i7-13700K | 240Hz</p>
                            <button type="button" class="select-btn">Select PC 02</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="PC-03" data-device-type="PC" data-device-name="PC 03">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/pc-placeholder.jpg" alt="PC 03" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH PC 03 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>PC 03</h3>
                            <p class="specs">RTX 3060 | i5-12400F | 144Hz</p>
                            <button type="button" class="select-btn">Select PC 03</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="PC-04" data-device-type="PC" data-device-name="PC 04">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/pc-placeholder.jpg" alt="PC 04" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH PC 04 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>PC 04</h3>
                            <p class="specs">RTX 3060 | i5-12400F | 144Hz</p>
                            <button type="button" class="select-btn">Select PC 04</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="PC-05" data-device-type="PC" data-device-name="PC 05">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/pc-placeholder.jpg" alt="PC 05" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH PC 05 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>PC 05</h3>
                            <p class="specs">RTX 3060 | i5-12400F | 144Hz</p>
                            <button type="button" class="select-btn">Select PC 05</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="PC-06" data-device-type="PC" data-device-name="PC 06">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/pc-placeholder.jpg" alt="PC 06" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH PC 06 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>PC 06</h3>
                            <p class="specs">RTX 3060 | i5-12400F | 144Hz</p>
                            <button type="button" class="select-btn">Select PC 06</button>
                        </div>
                    </div>

                </div>
            </section>

            <!-- CONSOLE SECTION -->
            <section class="device-section">
                <h2 class="section-title">Consoles</h2>
                <div class="device-grid" id="consoleGrid">
                    
                    <div class="device-card" data-device-id="Console-01" data-device-type="Console" data-device-name="Console 01">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/console-placeholder.jpg" alt="Console 01" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH CONSOLE 01 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>Console 01</h3>
                            <p class="specs">PS5 | 4K OLED TV | 2 Controllers</p>
                            <button type="button" class="select-btn">Select Console 01</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="Console-02" data-device-type="Console" data-device-name="Console 02">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/console-placeholder.jpg" alt="Console 02" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH CONSOLE 02 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>Console 02</h3>
                            <p class="specs">PS5 | 4K OLED TV | 2 Controllers</p>
                            <button type="button" class="select-btn">Select Console 02</button>
                        </div>
                    </div>

                    <div class="device-card" data-device-id="Console-03" data-device-type="Console" data-device-name="Console 03">
                        <span class="status-badge available">Available</span>
                        <div class="image-wrapper">
                            <img src="images/console-placeholder.jpg" alt="Console 03" class="device-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-fallback">[ REPLACE WITH CONSOLE 03 IMAGE ]</div>
                        </div>
                        <div class="device-details">
                            <h3>Console 03</h3>
                            <p class="specs">Xbox Series X | 4K TV | 2 Controllers</p>
                            <button type="button" class="select-btn">Select Console 03</button>
                        </div>
                    </div>

                </div>
            </section>

            <!-- DATE & TIME SELECTION SECTION -->
            <section class="datetime-section">
                <h2 class="section-title">Select Date & Start Time</h2>
                <div class="datetime-grid">
                    <div class="input-group">
                        <label for="bookingDate">Booking Date:</label>
                        <input type="date" id="bookingDate" class="custom-input">
                    </div>
                    <div class="input-group">
                        <label for="bookingTime">Start Time:</label>
                        <input type="time" id="bookingTime" class="custom-input">
                    </div>
                </div>
            </section>

            <!-- DURATION & TIME SELECTION SECTION -->
            <section class="time-section">
                <h2 class="section-title">Select Duration</h2>
                
                <div class="time-mode-selector">
                    <label class="mode-option">
                        <input type="radio" name="timeMode" value="Fixed" checked>
                        <span class="mode-card">
                            <strong>Fixed Time</strong>
                            <small>Specify exact hours</small>
                        </span>
                    </label>
                    
                    <label class="mode-option">
                        <input type="radio" name="timeMode" value="Open">
                        <span class="mode-card">
                            <strong>Open Time</strong>
                            <small>Pay as you play</small>
                        </span>
                    </label>
                </div>

                <!-- Fixed Hours Selector -->
                <div class="fixed-hours-wrapper" id="fixedHoursWrapper">
                    <p class="label-text">Select Number of Hours:</p>
                    <div class="counter-box">
                        <button type="button" class="counter-btn" id="minusBtn" aria-label="Decrease hours">-</button>
                        <input type="number" id="hoursInput" value="1" min="1" step="1">
                        <button type="button" class="counter-btn" id="plusBtn" aria-label="Increase hours">+</button>
                    </div>
                </div>

                <!-- Open Time Description -->
                <div class="open-info-wrapper" id="openInfoWrapper" style="display: none;">
                    <p class="open-info-text">⚡ <strong>Open Time Selected:</strong> Requires a minimum initial deposit upon session start. Billed by actual usage upon exit.</p>
                </div>
            </section>

            <!-- PRICING REVIEW DISPLAY CARD -->
            <section class="price-summary-card">
                <h2 class="section-title">Pricing Breakdown</h2>
                <div class="price-details-grid">
                    <div class="price-item">
                        <span class="price-label">Pricing Tier:</span>
                        <span class="price-value tier-badge" id="tierDisplay">BASIC</span>
                    </div>
                    <div class="price-item">
                        <span class="price-label">Estimated Price:</span>
                        <span class="price-value highlight-price" id="priceDisplay">₱40</span>
                    </div>
                </div>
            </section>

        </div>

        <!-- BOOKING SUMMARY REVIEW CARD -->
        <div id="summaryStep" class="summary-wrapper" style="display: none;">
            <div class="summary-card">
                <h2 class="summary-header">Review Your Booking</h2>
                <p class="summary-subtitle">Please double-check all details before confirming your reservation.</p>

                <div class="summary-details-grid">
                    <div class="summary-row">
                        <span class="summary-label">Selected Station:</span>
                        <span class="summary-value" id="sumDevice">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Device Type:</span>
                        <span class="summary-value" id="sumType">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Booking Date:</span>
                        <span class="summary-value" id="sumDate">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Start Time:</span>
                        <span class="summary-value" id="sumTime">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Duration / Mode:</span>
                        <span class="summary-value" id="sumDuration">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Pricing Tier:</span>
                        <span class="summary-value tier-badge" id="sumTier">BASIC</span>
                    </div>
                    <div class="summary-row highlight-row">
                        <span class="summary-label">Total Amount:</span>
                        <span class="summary-value highlight-price" id="sumPrice">₱0</span>
                    </div>
                </div>

                <div class="summary-actions">
                    <button type="button" class="btn-secondary" id="backToEditBtn">&larr; Back to Edit</button>
                    <button type="button" class="btn-primary" id="confirmBookingBtn">Confirm Booking</button>
                </div>
            </div>
        </div>

        <!-- SUCCESS CONFIRMATION STEP CARD -->
        <div id="successStep" class="summary-wrapper" style="display: none;">
            <div class="summary-card success-card">
                <h2 class="summary-header success-title">🎉 Booking Confirmed!</h2>
                <p class="summary-subtitle">Your reservation has been recorded in our system.</p>

                <div class="summary-details-grid">
                    <div class="summary-row">
                        <span class="summary-label">Booking Reference ID:</span>
                        <span class="summary-value highlight-id" id="succBookingId">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Station:</span>
                        <span class="summary-value" id="succDevice">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Date & Time:</span>
                        <span class="summary-value" id="succDateTime">--</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Duration:</span>
                        <span class="summary-value" id="succDuration">--</span>
                    </div>
                    <div class="summary-row highlight-row">
                        <span class="summary-label">Total Amount Paid/Due:</span>
                        <span class="summary-value highlight-price" id="succPrice">--</span>
                    </div>
                </div>

                <div class="summary-actions">
                    <a href="../index.php" class="btn-primary text-center">Return to Home</a>
                </div>
            </div>
        </div>

        <!-- Current Selection Status Bar -->
        <div class="selection-bar" id="selectionBar">
            <div class="summary-details">
                <span>Station: <strong id="selectedDeviceText">None</strong></span>
                <span class="divider">|</span>
                <span>Time: <strong id="selectedTimeText">1 Hour(s) (Fixed)</strong></span>
                <span class="divider">|</span>
                <span>Total: <strong id="barPriceText">₱0</strong></span>
            </div>
            <button type="button" class="review-btn" id="reviewBookingBtn">Review & Proceed &rarr;</button>
        </div>

    </main>

    <script src="booking.js"></script>
</body>
</html>