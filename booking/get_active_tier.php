<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'tier' => 'No booking yet']);
    exit();
}

require_once '../auth/db.php';

try {
    $stmt = $pdo->prepare("SELECT pricing_tier FROM bookings WHERE user_id = :uid AND status != 'Cancelled' ORDER BY id DESC LIMIT 1");
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking && !empty($booking['pricing_tier'])) {
        echo json_encode(['success' => true, 'tier' => strtoupper($booking['pricing_tier'])]);
    } else {
        echo json_encode(['success' => true, 'tier' => 'No booking yet']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'tier' => 'No booking yet']);
}