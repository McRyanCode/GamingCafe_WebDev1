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

    // Step 2: Username Validation
    if (!preg_match('/[a-zA-Z]/', $username)) {
        echo json_encode(["status" => "error", "message" => "Username must contain at least one letter."]);
        exit;
    }

    // Steps 3 & 4: Gmail Validation
    $email_parts = explode('@', $email);
    $email_prefix = $email_parts[0] ?? '';
    $email_domain = strtolower($email_parts[1] ?? '');

    if ($email_domain !== 'gmail.com' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Please use a valid Gmail address."]);
        exit;
    }

    if (!preg_match('/[a-zA-Z]/', $email_prefix)) {
        echo json_encode(["status" => "error", "message" => "Gmail address must contain at least one letter."]);
        exit;
    }

    // Step 5: Password Validation
    if (strlen($password) < 8) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters."]);
        exit;
    }

    if (!preg_match('/[a-zA-Z]/', $password)) {
        echo json_encode(["status" => "error", "message" => "Password must contain at least one letter."]);
        exit;
    }

    if (!preg_match('/[0-9]/', $password)) {
        echo json_encode(["status" => "error", "message" => "Password must contain at least one number."]);
        exit;
    }

    if ($password !== $confirm) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit;
    }

    try {
        // Duplicate Username Check
        $userStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $userStmt->execute([$username]);
        if ($userStmt->fetch()) {
            echo json_encode(["status" => "error", "message" => "Username is already taken."]);
            exit;
        }

        // Duplicate Gmail Check
        $emailStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $emailStmt->execute([$email]);
        if ($emailStmt->fetch()) {
            echo json_encode(["status" => "error", "message" => "Email is already registered."]);
            exit;
        }

        // Securely hash password and save
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $success = $insertStmt->execute([$username, $email, $hashed_password]);

        if ($success) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            echo json_encode(["status" => "success", "message" => "Registration successful! You can now log in."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
        }
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "A server error occurred. Please try again later."]);
        exit;
    }
}
?>