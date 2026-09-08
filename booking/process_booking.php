<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

// 1. Session Login Protection
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in first.']);
    exit();
}

require_once '../auth/db.php';
$user_id = $_SESSION['user_id'];

// Parse Payload
$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true) ?? $_POST;

$device_id    = trim($input['device_id'] ?? '');
$device_type  = trim($input['device_type'] ?? '');
$booking_date = trim($input['booking_date'] ?? '');
$start_time   = trim($input['start_time'] ?? '');
$time_mode    = trim($input['time_mode'] ?? '');
$hours        = isset($input['hours']) ? intval($input['hours']) : 1;

if (empty($device_id) || empty($device_type) || empty($booking_date) || empty($start_time) || empty($time_mode)) {
    echo json_encode(['success' => false, 'message' => 'Missing required booking information.']);
    exit();
}

if (strlen($start_time) === 5) {
    $start_time .= ':00';
}

// 2. Server-Side Pricing Verification (Tamper-Proof)
$isConsole = ($device_type === 'Console');
$baseRate = $isConsole ? 30 : 40;
$standardBase = $isConsole ? 80 : 100;
$premiumBase = $isConsole ? 120 : 150;

if ($time_mode === 'Open') {
    $pricing_tier = 'OPEN TIME';
    $total_price = (float)$baseRate;
    $duration_hours = null;
    $calculated_seconds = 4 * 3600; // Default overlap check buffer for open session
} else {
    $duration_hours = max(1, $hours);
    $calculated_seconds = $duration_hours * 3600;
    
    if ($duration_hours <= 2) {
        $pricing_tier = 'BASIC';
        $total_price = (float)($duration_hours * $baseRate);
    } else if ($duration_hours <= 4) {
        $pricing_tier = 'STANDARD';
        $extraHours = $duration_hours - 3;
        $total_price = (float)($standardBase + ($extraHours * $baseRate));
    } else {
        $pricing_tier = 'PREMIUM';
        $extraHours = $duration_hours - 5;
        $total_price = (float)($premiumBase + ($extraHours * $baseRate));
    }
}


   
 try {
    // 3. Double-Booking Overlap Check (Positional Parameters)
    $overlapSql = "SELECT COUNT(*) FROM bookings 
                   WHERE device_id = ? 
                     AND booking_date = ? 
                     AND status != 'Cancelled'
                     AND (
                        (time_mode = 'Open' AND start_time <= ADDTIME(?, SEC_TO_TIME(?)))
                        OR
                        (
                          start_time < ADDTIME(?, SEC_TO_TIME(?)) 
                          AND 
                          ADDTIME(start_time, SEC_TO_TIME(COALESCE(duration_hours, 4) * 3600)) > ?
                        )
                     )";
                     
    $overlapStmt = $pdo->prepare($overlapSql);
    $overlapStmt->execute([
        $device_id,
        $booking_date,
        $start_time,
        $calculated_seconds,
        $start_time,
        $calculated_seconds,
        $start_time
    ]);

    if ($overlapStmt->fetchColumn() > 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'Station ' . $device_id . ' is already booked during this time slot.'
        ]);
        exit();
    }

    // 4. Persistence
    $sql = "INSERT INTO bookings (user_id, device_id, device_type, booking_date, start_time, time_mode, duration_hours, pricing_tier, total_price, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $user_id,
        $device_id,
        $device_type,
        $booking_date,
        $start_time,
        $time_mode,
        $duration_hours,
        $pricing_tier,
        $total_price
    ]);

    $booking_id = $pdo->lastInsertId();

    echo json_encode([
        'success'      => true,
        'message'      => 'Booking confirmed!',
        'booking_id'   => $booking_id,
        'device_id'    => $device_id,
        'device_type'  => $device_type,
        'booking_date' => $booking_date,
        'start_time'   => $start_time,
        'time_mode'    => $time_mode,
        'duration'     => $time_mode === 'Open' ? 'Open Time' : $duration_hours . ' Hour(s)',
        'pricing_tier' => $pricing_tier,
        'total_price'  => '₱' . number_format($total_price, 2)
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit();
}