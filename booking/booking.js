document.addEventListener("DOMContentLoaded", () => {
    // State Tracking
    let selectedDevice = null;
    let timeMode = "Fixed"; // "Fixed" or "Open"
    let hours = 1;
    let selectedDate = "";
    let selectedTime = "";

    // Set Default Today's Date
    const today = new Date().toISOString().split('T')[0];
    const bookingDateInput = document.getElementById("bookingDate");
    if (bookingDateInput) {
        bookingDateInput.value = today;
        bookingDateInput.min = today;
        selectedDate = today;
    }

    // Set Default Current Time
    const bookingTimeInput = document.getElementById("bookingTime");
    if (bookingTimeInput) {
        const now = new Date();
        const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        bookingTimeInput.value = currentTime;
        selectedTime = currentTime;
    }

    // UI Elements - Selection View
    const selectionStep = document.getElementById("selectionStep");
    const summaryStep = document.getElementById("summaryStep");
    const selectionBar = document.getElementById("selectionBar");

    const deviceCards = document.querySelectorAll(".device-card");
    const selectedDeviceText = document.getElementById("selectedDeviceText");
    const selectedTimeText = document.getElementById("selectedTimeText");
    const barPriceText = document.getElementById("barPriceText");

    const timeModeRadios = document.querySelectorAll("input[name='timeMode']");
    const fixedHoursWrapper = document.getElementById("fixedHoursWrapper");
    const openInfoWrapper = document.getElementById("openInfoWrapper");

    const hoursInput = document.getElementById("hoursInput");
    const minusBtn = document.getElementById("minusBtn");
    const plusBtn = document.getElementById("plusBtn");

    const tierDisplay = document.getElementById("tierDisplay");
    const priceDisplay = document.getElementById("priceDisplay");

    // UI Elements - Buttons
    const reviewBookingBtn = document.getElementById("reviewBookingBtn");
    const backToEditBtn = document.getElementById("backToEditBtn");
    const confirmBookingBtn = document.getElementById("confirmBookingBtn");

    // UI Elements - Summary Fields
    const sumDevice = document.getElementById("sumDevice");
    const sumType = document.getElementById("sumType");
    const sumDate = document.getElementById("sumDate");
    const sumTime = document.getElementById("sumTime");
    const sumDuration = document.getElementById("sumDuration");
    const sumTier = document.getElementById("sumTier");
    const sumPrice = document.getElementById("sumPrice");

    // 1. Calculation Function
    function calculatePricing() {
        const isConsole = selectedDevice && selectedDevice.type === "Console";
        const baseRate = isConsole ? 30 : 40;
        const standardBase = isConsole ? 80 : 100;
        const premiumBase = isConsole ? 120 : 150;

        if (timeMode === "Open") {
            return {
                tier: "OPEN TIME",
                price: baseRate,
                displayText: `₱${baseRate} (Min Deposit)`
            };
        }

        if (hours <= 2) {
            const total = hours * baseRate;
            return { tier: "BASIC", price: total, displayText: `₱${total}` };
        } else if (hours <= 4) {
            const extraHours = hours - 3;
            const total = standardBase + (extraHours * baseRate);
            return { tier: "STANDARD", price: total, displayText: `₱${total}` };
        } else {
            const extraHours = hours - 5;
            const total = premiumBase + (extraHours * baseRate);
            return { tier: "PREMIUM", price: total, displayText: `₱${total}` };
        }
    }

    // 2. Event Listeners for Date and Time
    bookingDateInput.addEventListener("change", (e) => {
        selectedDate = e.target.value;
    });

    bookingTimeInput.addEventListener("change", (e) => {
        selectedTime = e.target.value;
    });

    // 3. Device Selection Handler
    deviceCards.forEach(card => {
        card.addEventListener("click", () => {
            deviceCards.forEach(c => {
                c.classList.remove("selected");
                const btn = c.querySelector(".select-btn");
                if (btn) btn.textContent = `Select ${c.dataset.deviceName}`;
            });

            card.classList.add("selected");
            const btn = card.querySelector(".select-btn");
            if (btn) btn.textContent = "Selected";

            selectedDevice = {
                id: card.dataset.deviceId,
                type: card.dataset.deviceType,
                name: card.dataset.deviceName
            };

            updateSummary();
        });
    });

    // 4. Time Mode Change Handler
    timeModeRadios.forEach(radio => {
        radio.addEventListener("change", (e) => {
            timeMode = e.target.value;

            if (timeMode === "Open") {
                fixedHoursWrapper.style.display = "none";
                openInfoWrapper.style.display = "block";
            } else {
                fixedHoursWrapper.style.display = "inline-block";
                openInfoWrapper.style.display = "none";
            }

            updateSummary();
        });
    });

    // 5. Counter Controls
    plusBtn.addEventListener("click", () => {
        hours++;
        hoursInput.value = hours;
        updateSummary();
    });

    minusBtn.addEventListener("click", () => {
        if (hours > 1) {
            hours--;
            hoursInput.value = hours;
            updateSummary();
        }
    });

    hoursInput.addEventListener("input", (e) => {
        let val = parseInt(e.target.value, 10);
        if (isNaN(val) || val < 1) val = 1;
        hours = val;
        hoursInput.value = hours;
        updateSummary();
    });

    // 6. Update Selection Displays
    function updateSummary() {
        const pricing = calculatePricing();

        if (selectedDevice) {
            selectedDeviceText.textContent = `${selectedDevice.name} (${selectedDevice.type})`;
        } else {
            selectedDeviceText.textContent = "None";
        }

        if (timeMode === "Open") {
            selectedTimeText.textContent = "Open Time";
        } else {
            selectedTimeText.textContent = `${hours} Hour(s) (Fixed)`;
        }

        tierDisplay.textContent = pricing.tier;
        priceDisplay.textContent = pricing.displayText;
        barPriceText.textContent = pricing.displayText;

        minusBtn.disabled = (hours <= 1);
    }

    // 7. Review Booking Button Click
    reviewBookingBtn.addEventListener("click", () => {
        // Validation Checks
        if (!selectedDevice) {
            alert("Please select a PC or Console station before proceeding.");
            return;
        }

        if (!selectedDate) {
            alert("Please select a valid booking date.");
            return;
        }

        if (!selectedTime) {
            alert("Please select a valid start time.");
            return;
        }

        const pricing = calculatePricing();

        // Populate Summary Fields
        sumDevice.textContent = selectedDevice.name;
        sumType.textContent = selectedDevice.type;
        sumDate.textContent = selectedDate;
        sumTime.textContent = selectedTime;
        sumDuration.textContent = timeMode === "Open" ? "Open Time" : `${hours} Hour(s) (Fixed)`;
        sumTier.textContent = pricing.tier;
        sumPrice.textContent = pricing.displayText;

        // Switch Views
        selectionStep.style.display = "none";
        selectionBar.style.display = "none";
        summaryStep.style.display = "block";
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // 8. Back to Edit Button Click
    backToEditBtn.addEventListener("click", () => {
        summaryStep.style.display = "none";
        selectionStep.style.display = "block";
        selectionBar.style.display = "flex";
    });

    // 9. Confirm Booking Click (Placeholder Notice - No DB Save yet)
    confirmBookingBtn.addEventListener("click", () => {
        alert(`Booking summary reviewed successfully!\n\nNote: Step 6 complete. Database storage will be implemented in Step 7.`);
    });

    // Initialize display state
    updateSummary();
});