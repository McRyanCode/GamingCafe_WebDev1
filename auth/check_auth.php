<?php
session_start();
header('Content-Type: application/json');

// Ensure error messages don't break JSON output format
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (isset($_SESSION['user_id']) && !empty($_SESSION['username'])) {
    echo json_encode([
        "logged_in"    => true,
        "user_id"      => $_SESSION['user_id'],
        "username"     => $_SESSION['username'],
        "role"         => $_SESSION['role'] ?? 'user',
        "pricing_tier" => $_SESSION['pricing_tier'] ?? 'Standard Plan',
        "booked_pc"    => $_SESSION['booked_pc'] ?? 'No PC Booked'
    ]);
} else {
    echo json_encode([
        "logged_in" => false
    ]);
}
exit();
?>