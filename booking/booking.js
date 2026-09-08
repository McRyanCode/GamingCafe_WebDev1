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

    // UI Views
    const selectionStep = document.getElementById("selectionStep");
    const summaryStep = document.getElementById("summaryStep");
    const successStep = document.getElementById("successStep");
    const selectionBar = document.getElementById("selectionBar");

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

    // UI Buttons
    const reviewBookingBtn = document.getElementById("reviewBookingBtn");
    const backToEditBtn = document.getElementById("backToEditBtn");
    const confirmBookingBtn = document.getElementById("confirmBookingBtn");

    // Summary Display Elements
    const sumDevice = document.getElementById("sumDevice");
    const sumType = document.getElementById("sumType");
    const sumDate = document.getElementById("sumDate");
    const sumTime = document.getElementById("sumTime");
    const sumDuration = document.getElementById("sumDuration");
    const sumTier = document.getElementById("sumTier");
    const sumPrice = document.getElementById("sumPrice");

    // Success Display Elements
    const succBookingId = document.getElementById("succBookingId");
    const succDevice = document.getElementById("succDevice");
    const succDateTime = document.getElementById("succDateTime");
    const succDuration = document.getElementById("succDuration");
    const succPrice = document.getElementById("succPrice");

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

    // Date/Time Events
    bookingDateInput.addEventListener("change", (e) => selectedDate = e.target.value);
    bookingTimeInput.addEventListener("change", (e) => selectedTime = e.target.value);

    // Device Cards Selection
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

    // Time Mode Selection
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

    // Hour Counter Buttons
    plusBtn.addEventListener("click", () => { hours++; hoursInput.value = hours; updateSummary(); });
    minusBtn.addEventListener("click", () => { if (hours > 1) { hours--; hoursInput.value = hours; updateSummary(); } });
    hoursInput.addEventListener("input", (e) => {
        let val = parseInt(e.target.value, 10);
        if (isNaN(val) || val < 1) val = 1;
        hours = val;
        hoursInput.value = hours;
        updateSummary();
    });

    function updateSummary() {
        const pricing = calculatePricing();
        selectedDeviceText.textContent = selectedDevice ? `${selectedDevice.name} (${selectedDevice.type})` : "None";
        selectedTimeText.textContent = timeMode === "Open" ? "Open Time" : `${hours} Hour(s) (Fixed)`;
        tierDisplay.textContent = pricing.tier;
        priceDisplay.textContent = pricing.displayText;
        barPriceText.textContent = pricing.displayText;
        minusBtn.disabled = (hours <= 1);
    }

    // Review Button Click
    reviewBookingBtn.addEventListener("click", () => {
        if (!selectedDevice) return alert("Please select a PC or Console station before proceeding.");
        if (!selectedDate) return alert("Please select a valid booking date.");
        if (!selectedTime) return alert("Please select a valid start time.");

        const pricing = calculatePricing();
        sumDevice.textContent = selectedDevice.name;
        sumType.textContent = selectedDevice.type;
        sumDate.textContent = selectedDate;
        sumTime.textContent = selectedTime;
        sumDuration.textContent = timeMode === "Open" ? "Open Time" : `${hours} Hour(s) (Fixed)`;
        sumTier.textContent = pricing.tier;
        sumPrice.textContent = pricing.displayText;

        selectionStep.style.display = "none";
        selectionBar.style.display = "none";
        summaryStep.style.display = "block";
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Back Button Click
    backToEditBtn.addEventListener("click", () => {
        summaryStep.style.display = "none";
        selectionStep.style.display = "block";
        selectionBar.style.display = "flex";
    });

 confirmBookingBtn.addEventListener("click", () => {
        confirmBookingBtn.disabled = true;
        confirmBookingBtn.textContent = "Saving Booking...";

        // Fetch direct input values from DOM to ensure non-empty strings
        const dateVal = document.getElementById("bookingDate") ? document.getElementById("bookingDate").value : selectedDate;
        const timeVal = document.getElementById("bookingTime") ? document.getElementById("bookingTime").value : selectedTime;

        const payload = {
            device_id: selectedDevice ? selectedDevice.id : "",
            device_type: selectedDevice ? selectedDevice.type : "",
            booking_date: dateVal,
            start_time: timeVal,
            time_mode: timeMode,
            hours: parseInt(hours, 10) || 1
        };

        fetch("process_booking.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
       .then(data => {
    if (data.success) {
        succBookingId.textContent = `#BOOK-${String(data.booking_id).padStart(5, '0')}`;
        succDevice.textContent = `${data.device_id} (${data.device_type})`;
        succDateTime.textContent = `${data.booking_date} at ${data.start_time}`;
        succDuration.textContent = data.duration;
        succPrice.textContent = data.total_price;

        summaryStep.style.display = "none";
        successStep.style.display = "block";
    } else {
        // Displays the detailed message returned by PHP
        alert(data.message);
        confirmBookingBtn.disabled = false;
        confirmBookingBtn.textContent = "Confirm Booking";
    }
})  
.catch(error => {
    console.error("Booking Error:", error);
    alert("Network or Parsing Error: " + error.message);
    confirmBookingBtn.disabled = false;
    confirmBookingBtn.textContent = "Confirm Booking";
});

    });

});