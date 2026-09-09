<script>
let currentFetchController = null;

async function checkStationStatus() {
    const dateInput = document.getElementById('bookingDate').value;
    const timeInput = document.getElementById('startTime').value;

    if (!dateInput || !timeInput) return;

    // Abort any slow pending fetch request to avoid UI flickers
    if (currentFetchController) {
        currentFetchController.abort();
    }
    currentFetchController = new AbortController();

    try {
        const response = await fetch(`check_availability.php?date=${dateInput}&time=${timeInput}`, {
            signal: currentFetchController.signal
        });
        const data = await response.json();
        const occupied = data.occupied_stations || [];

        document.querySelectorAll('.station-card').forEach(card => {
            const stationId = card.getAttribute('data-station-id');
            const badge = card.querySelector('.status-badge');
            const button = card.querySelector('.btn-select');

            if (occupied.includes(stationId)) {
                badge.textContent = 'OCCUPIED';
                badge.className = 'status-badge occupied';
                card.classList.add('is-occupied');
                if (button) {
                    button.disabled = true;
                    button.textContent = 'Unavailable';
                }
            } else {
                badge.textContent = 'AVAILABLE';
                badge.className = 'status-badge available';
                card.classList.remove('is-occupied');
                if (button) {
                    button.disabled = false;
                    button.textContent = `Select ${stationId}`;
                }
            }
        });
    } catch (error) {
        if (error.name !== 'AbortError') {
            console.error("Error fetching availability:", error);
        }
    }
}

// Event listeners for inputs
['input', 'change'].forEach(evt => {
    document.getElementById('bookingDate').addEventListener(evt, checkStationStatus);
    document.getElementById('startTime').addEventListener(evt, checkStationStatus);
});

// Run immediately on page load
document.addEventListener('DOMContentLoaded', checkStationStatus);
</script>