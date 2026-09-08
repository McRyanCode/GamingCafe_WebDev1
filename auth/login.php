<?php
session_start();
header('Content-Type: application/json');

// Ensure error messages don't break JSON output format
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php'; // Loads $pdo from auth/db.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw JSON body or fall back to standard $_POST
    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $login_input = trim($input['login_input'] ?? $input['username'] ?? '');
    $password    = $input['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please enter all fields."]);
        exit;
    }

    try {
        // Updated: Use distinct placeholders (:username and :email)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute([
            ':username' => $login_input,
            ':email'    => $login_input
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
$_SESSION['role']     = $user['role'];
            echo json_encode([
                "status"   => "success",
                "message"  => "Login successful!",
                "username" => $user['username']
            ]);
            exit;
        }

        echo json_encode(["status" => "error", "message" => "Invalid username/email or password."]);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        exit;
    }
}
?>