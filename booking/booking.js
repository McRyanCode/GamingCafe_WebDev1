document.addEventListener("DOMContentLoaded", () => {
    // State Tracking
    let selectedDevice = null;
    let timeMode = "Fixed"; // "Fixed" or "Open"
    let hours = 1;

    // UI Elements
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

    // 1. Calculation Function
    function calculatePricing() {
        // Base rate based on device type
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

        // Fixed Hours Tier Logic
        if (hours <= 2) {
            const total = hours * baseRate;
            return { tier: "BASIC", price: total, displayText: `₱${total}` };
        } else if (hours <= 4) {
            // Standard Tier: 3 hrs flat package + extra hrs
            const extraHours = hours - 3;
            const total = standardBase + (extraHours * baseRate);
            return { tier: "STANDARD", price: total, displayText: `₱${total}` };
        } else {
            // Premium Tier: 5 hrs flat package + extra hrs
            const extraHours = hours - 5;
            const total = premiumBase + (extraHours * baseRate);
            return { tier: "PREMIUM", price: total, displayText: `₱${total}` };
        }
    }

    // 2. Device Selection Handler
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

    // 3. Time Mode Change Handler
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

    // 4. Counter Controls (+ and -)
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
        if (isNaN(val) || val < 1) {
            val = 1;
        }
        hours = val;
        hoursInput.value = hours;
        updateSummary();
    });

    // 5. Update Footer & Pricing Display
    function updateSummary() {
        const pricing = calculatePricing();

        // Update Device Text
        if (selectedDevice) {
            selectedDeviceText.textContent = `${selectedDevice.name} (${selectedDevice.type})`;
        } else {
            selectedDeviceText.textContent = "None";
        }

        // Update Time Text
        if (timeMode === "Open") {
            selectedTimeText.textContent = "Open Time";
        } else {
            selectedTimeText.textContent = `${hours} Hour(s) (Fixed)`;
        }

        // Update Pricing Breakdown Card & Footer Bar
        tierDisplay.textContent = pricing.tier;
        priceDisplay.textContent = pricing.displayText;
        barPriceText.textContent = pricing.displayText;

        minusBtn.disabled = (hours <= 1);

        console.log("Current State & Calculated Pricing:", {
            device: selectedDevice,
            mode: timeMode,
            hours: timeMode === "Fixed" ? hours : null,
            pricing: pricing
        });
    }

    // Initialize display state
    updateSummary();
});