<?php
session_start();
header('Content-Type: application/json');

// Prevent raw PHP warnings from breaking JSON syntax
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php'; // Loads PDO $pdo instance

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw JSON body or fall back to standard $_POST
    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $username = trim($input['username'] ?? '');
    $email    = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    $confirm  = $input['confirm_password'] ?? $input['confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email address."]);
        exit;
    }

    if ($password !== $confirm) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit;
    }

    try {
        // Check for existing user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            echo json_encode(["status" => "error", "message" => "Username or Email is already taken."]);
            exit;
        }

        // Securely hash password and save
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $success = $insertStmt->execute([$username, $email, $hashed_password]);

        if ($success) {
            // Automatically assign user session upon successful registration
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            echo json_encode(["status" => "success", "message" => "Registration successful! You can now log in."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
        }
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        exit;
    }
}
?>