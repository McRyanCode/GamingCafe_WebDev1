<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['tier' => 'GUEST', 'description' => 'Log in to view your tier status.']);
    exit();
}

require_once '../auth/db.php';

try {
    // Fetch the user's most recent confirmed booking
    $stmt = $pdo->prepare("SELECT pricing_tier, device_type, duration_hours, time_mode 
                           FROM bookings 
                           WHERE user_id = :user_id AND status != 'Cancelled'
                           ORDER BY id DESC LIMIT 1");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $latest = $stmt->fetch();

    if ($latest) {
        $tier = strtoupper($latest['pricing_tier']);
        $type = $latest['device_type'];
        $time = $latest['time_mode'] === 'Open' ? 'Open Time' : $latest['duration_hours'] . ' Hour(s)';
        
        echo json_encode([
            'tier' => $tier,
            'description' => "Based on your latest $type reservation ($time)."
        ]);
    } else {
        echo json_encode([
            'tier' => 'STANDARD',
            'description' => 'No active bookings found. Default plan active.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['tier' => 'STANDARD', 'description' => 'Unable to load status.']);
}