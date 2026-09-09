<?php
session_start();
header('Content-Type: application/json');

// Suppress raw PHP errors so JSON output stays clean
error_reporting(0);
ini_set('display_errors', 0);

require_once '../auth/db.php';

$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';

if (empty($date) || empty($time)) {
    echo json_encode(['occupied_stations' => []]);
    exit();
}

// Ensure standard HH:MM:SS format
if (strlen($time) === 5) {
    $time .= ':00';
}

$check_datetime = "$date $time";

try {
    // Uses TIMESTAMP() to evaluate complete date+time ranges safely
    $sql = "SELECT DISTINCT device_id 
            FROM bookings 
            WHERE status != 'Cancelled'
              AND TIMESTAMP(booking_date, start_time) <= :check_start1
              AND DATE_ADD(TIMESTAMP(booking_date, start_time), INTERVAL COALESCE(duration_hours, 1) HOUR) > :check_start2";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':check_start1' => $check_datetime,
        ':check_start2' => $check_datetime
    ]);

    $occupied = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['occupied_stations' => $occupied]);
} catch (PDOException $e) {
    echo json_encode(['occupied_stations' => [], 'error' => $e->getMessage()]);
}